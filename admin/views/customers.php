<?php
require_once __DIR__ . '/../helpers/admin_backend.php';

$error = null;
$message = '';
$csrfToken = '';
$searchTerm = '';

try {
    $pdo = admin_bootstrap();
    $csrfToken = admin_get_csrf_token();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        admin_require_csrf_token($_POST['csrf_token'] ?? null);

        $action = trim((string)($_POST['action'] ?? ''));
        $result = admin_dispatch_action($pdo, $action, $_POST);
        admin_set_flash($result['ok'] ? 'success' : 'error', $result['message']);

        admin_redirect_to_page('customers');
    }

    $flash = admin_pop_flash();
    if ($flash !== null) {
        if (($flash['type'] ?? '') === 'error') {
            $error = $flash['message'];
        } else {
            $message = $flash['message'];
        }
    }

    // Search logic
    $searchTerm = trim((string)($_GET['search'] ?? ''));
    if (strlen($searchTerm) > 100) {
        $searchTerm = substr($searchTerm, 0, 100);
    }

    if (isset($_GET['clear'])) {
        admin_redirect_to_page('customers');
    }

    $query = "SELECT u.user_id, u.username, g.first_name, g.last_name, u.account_email, u.role 
              FROM Users u
              LEFT JOIN Guests g ON u.guest_id = g.guest_id
              WHERE u.role = 'guest'";

    $params = [];

    if ($searchTerm) {
        $query .= " AND (g.first_name LIKE :s1 OR g.last_name LIKE :s2 OR u.account_email LIKE :s3 OR u.username LIKE :s4)";
        $params[':s1'] = "%$searchTerm%";
        $params[':s2'] = "%$searchTerm%";
        $params[':s3'] = "%$searchTerm%";
        $params[':s4'] = "%$searchTerm%";
    }

    $query .= " ORDER BY u.user_id DESC LIMIT 50";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $recentUsers = $stmt->fetchAll();

} catch (Throwable $e) {
    $error = $e->getMessage();
    $recentUsers = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link rel="stylesheet" href="/static/css/style.css">
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>

    <div class="admin-header">
        <h1>Customers</h1>
        <p>View and manage customer information</p>
    </div>

    <?php if ($error): ?>
        <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($message): ?>
        <div style="padding:12px;background:#e7f7ed;border:1px solid #b8e0c2;color:#124b26;border-radius:4px;margin-bottom:12px;"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div style="margin-top:20px;" class="card">
        <h3>Search Customers</h3>
        <form method="get" id="searchForm">
            <input type="hidden" name="page" value="customers">
            <input class="search-input-customer" type="text" name="search" placeholder="Search customers..." value="<?php echo htmlspecialchars($searchTerm); ?>" autocomplete="off">
            <button class="btn-search" type="submit">Search</button>
            <button class="btn-clear" type="submit" name="clear">Clear</button>
        </form>
    </div>

    <div class="customers-grid">
        <?php if (empty($recentUsers)): ?>
            <div class="muted">No customers found.</div>
        <?php else: ?>
            <?php foreach ($recentUsers as $u): ?>
                <?php
                    $fullName = trim(implode(' ', array_filter([
                        $u['first_name'] ?? '',
                        $u['last_name'] ?? ''
                    ])));
                    if (empty($fullName)) {
                        $fullName = $u['username'];
                    }
                    $initials = '';
                    if (!empty($u['first_name'])) {
                        $initials .= substr($u['first_name'], 0, 1);
                        if (!empty($u['last_name'])) {
                            $initials .= substr($u['last_name'], 0, 1);
                        }
                    } else {
                        $initials = substr($u['username'], 0, 1);
                    }
                    $initials = strtoupper($initials);
                ?>
                <div class="customer-card">
                    <div class="card-top">
                        <div class="avatar">
                            <?php echo $initials ?: 'U'; ?>
                        </div>
                        <button class="view-btn">View</button>
                    </div>
                    <h3><?php echo htmlspecialchars($fullName); ?></h3>
                    <div class="customer-info">
                        <div class="info-row">
                            <img src="/static/img/icons/mail.svg" class="icon" alt="email icon">
                            <span><?php echo htmlspecialchars($u['account_email'] ?? ''); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>
</html>