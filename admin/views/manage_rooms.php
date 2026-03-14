<?php
require_once __DIR__ . '/../helpers/admin_backend.php';
require_once __DIR__ . '/../../helpers/RoomModel.php';

$message = '';
$error = '';
$csrfToken = '';

try {
    $pdo = admin_bootstrap();
    $csrfToken = admin_get_csrf_token();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        admin_require_csrf_token($_POST['csrf_token'] ?? null);

        $action = trim((string)($_POST['action'] ?? ''));
        if ($action === '') {
            $action = 'create_cottage';
        }

        $result = admin_dispatch_action($pdo, $action, $_POST);
        admin_set_flash($result['ok'] ? 'success' : 'error', $result['message']);

        admin_redirect_to_page('manage_rooms');
    }

    $flash = admin_pop_flash();
    if ($flash !== null) {
        if (($flash['type'] ?? '') === 'error') {
            $error = $flash['message'];
        } else {
            $message = $flash['message'];
        }
    }

    $roomModel = new RoomModel();
    $rooms = $roomModel->getAll();

    // Calculate stats
    $roomsTotal = $pdo->query("SELECT COUNT(*) FROM Cottages")->fetchColumn();
    $roomsAvailable = $pdo->query("SELECT COUNT(*) FROM Cottages WHERE status = 'Available'")->fetchColumn();
    $reservationsTotal = $pdo->query("SELECT COUNT(*) FROM Reservations")->fetchColumn();
    $cottageTypes = $pdo->query("SELECT COUNT(*) FROM Cottage_Types")->fetchColumn();

    $types = $pdo->query("SELECT type_id, type_name AS name FROM Cottage_Types ORDER BY type_name ASC")->fetchAll();
} catch (Throwable $e) {
    $error = $e->getMessage();
    $rooms = [];
    $types = [];
    $roomsTotal = 0;
    $roomsAvailable = 0;
    $reservationsTotal = 0;
    $cottageTypes = 0;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>

    <div class="admin-header">
        <h1>Manage Cottages</h1>
    </div>

    <div class="manage-room-parent">
        <div class="div1 div-cards">
            <div class="card-stat">
                    <h2><?php echo ($roomsTotal)?></h2>
                <div class="card-stat-content">
                    <div class="muted">Total Cottages</div>
                    <img src="static/img/adminpanel_icons/bed.svg" alt="">
                </div>
            </div>
        </div>

        <div class="div2">
            <div class="card-stat">
                    <h2><?php echo ($roomsAvailable)?></h2>
                <div class="card-stat-content">
                    <div class="muted">Available</div>
                    <img src="static/img/adminpanel_icons/bed.svg" alt="">
                </div>
            </div>
        </div>

        <div class="div3 div-cards">
            <div class="card-stat">
                <h2><?php echo ($cottageTypes)?></h2>
                <div class="card-stat-content">
                    <div class="muted">Cottage Types</div>
                    <img src="static/img/adminpanel_icons/bed.svg" alt="">
                </div>
            </div>
        </div>

        <div class="div4">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger" style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="card">
                <table class="cottages-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Number</th>
                            <th>Type</th>
                            <th>Base Price</th>
                            <th>Max Occ</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $r): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($r['cottage_id']); ?></td>
                                <td><?php echo htmlspecialchars($r['cottage_number']); ?></td>
                                <td><?php echo htmlspecialchars($r['name'] ?? '—'); ?></td>
                                <td>₱<?php echo number_format($r['base_price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($r['max_occupancy']); ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                        <input type="hidden" name="cottage_id" value="<?php echo $r['cottage_id']; ?>">
                                        <select name="status" class="badge" onchange="this.form.submit()">
                                            <option value="Available" <?php echo $r['status'] === 'Available' ? 'selected' : ''; ?>>Available</option>
                                            <option value="Occupied" <?php echo $r['status'] === 'Occupied' ? 'selected' : ''; ?>>Occupied</option>
                                            <option value="Maintenance" <?php echo $r['status'] === 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                            <option value="Out of Order" <?php echo $r['status'] === 'Out of Order' ? 'selected' : ''; ?>>Out of Order</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="action-btn-container">
                                        <a href="index.php?page=edit_room&id=<?php echo $r['cottage_id']; ?>" class="edit-btn">
                                            <img src="static/img/adminpanel_icons/edit.svg" alt="">
                                        </a>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Delete this cottage?');">
                                            <input type="hidden" name="action" value="delete_cottage">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                            <input type="hidden" name="cottage_id" value="<?php echo $r['cottage_id']; ?>">
                                            <button type="submit" class="delete-btn">
                                                <img src="/admin/static/img//adminpanel_icons/delete.svg" alt="">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="div5">
    
            <div class="modal-wrapper">
                <div class="card">
                    <h3 style="margin-bottom: 0px;">Edit Room</h1>
                    <p style="font-size: 11px; margin-bottom: 20px;">Update room information</p>
                    <?php if ($error): ?>
                        <div class="alert alert-danger" style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <input type="hidden" name="action" value="create_cottage">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                        <div class="form-group">
                            <label class="edit-cottage-label">Cottage Number *</label>
                            <input type="text" name="cottage_number" class="booknow-input" required>
                        </div>

                        <div class="form-group">
                            <label class="edit-cottage-label">Cottage Type *</label>
                            <select name="type_id" class="booknow-select" required>
                                <?php foreach ($types as $t): ?>
                                    <option value="<?php echo $t['type_id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-top:15px;">
                            <label class="edit-cottage-label">Base Price (₱)</label>
                            <input type="number" name="base_price" step="0.01" class="booknow-input" value="0.00">
                        </div>

                        <div class="form-group" style="margin-top:15px;">
                            <label class="edit-cottage-label">Max Occupancy</label>
                            <input type="number" name="max_occupancy" class="booknow-input" value="2">
                        </div>

                        <div class="form-group" style="margin-top:15px;">
                            <label class="edit-cottage-label">Status</label>
                            <select name="status" class="booknow-select">
                                <option value="Available">Available</option>
                                <option value="Occupied">Occupied</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Out of Order">Out of Order</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-top:15px;">
                            <label class="edit-cottage-label">Description</label>
                            <textarea name="description" class="booknow-textarea"></textarea>
                        </div>

                        <div style="margin-top:20px;">
                            <button type="submit" class="btn btn-primary">Save Cottage</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>