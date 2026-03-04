<?php
require_once '../auth/auth_functions.php';
require_once '../helpers/DB.php';

requireLogin();
requireAdmin();

$error = null;
$message = '';
try {
    $pdo = DB::getPDO();

    // Handle admin actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'update_reservation_status' && !empty($_POST['reservation_id']) && isset($_POST['status'])) {
            $stmt = $pdo->prepare('UPDATE Reservations SET status = :s WHERE reservation_id = :id');
            $stmt->execute([':s' => $_POST['status'], ':id' => (int)$_POST['reservation_id']]);
            $message = 'Reservation status updated.';
        }

        if ($action === 'delete_reservation' && !empty($_POST['reservation_id'])) {
            $stmt = $pdo->prepare('DELETE FROM Reservations WHERE reservation_id = :id');
            $stmt->execute([':id' => (int)$_POST['reservation_id']]);
            $message = 'Reservation deleted.';
        }

        if ($action === 'delete_user' && !empty($_POST['user_id'])) {
            $stmt = $pdo->prepare('DELETE FROM Users WHERE user_id = :id');
            $stmt->execute([':id' => (int)$_POST['user_id']]);
            $message = 'User deleted.';
        }

        if ($action === 'update_room_status' && !empty($_POST['room_id']) && isset($_POST['status'])) {
            // Map room status to is_available for Cottages
            $isAvail = (strtolower($_POST['status']) === 'available') ? 1 : 0;
            $stmt = $pdo->prepare('UPDATE Cottages SET is_available = :avail WHERE cottage_id = :id');
            $stmt->execute([':avail' => $isAvail, ':id' => (int)$_POST['room_id']]);
            $message = 'Cottage availability updated.';
        }
    }
    
    // Recent reservations
    $recentReservations = $pdo->query(
        "SELECT r.reservation_id, r.check_in_date, r.check_out_date, r.status, CONCAT(g.first_name, ' ', g.last_name) AS guest_name, c.cottage_number
         FROM Reservations r
         LEFT JOIN Guests g ON r.guest_id = g.guest_id
         LEFT JOIN Cottages c ON r.cottage_id = c.cottage_id
         ORDER BY r.reservation_id DESC LIMIT 8"
    )->fetchAll();

    $recentUsers = $pdo->query('SELECT user_id, username, first_name, middle_name, last_name, account_email, role FROM Users ORDER BY user_id DESC LIMIT 8')->fetchAll();

} catch (Exception $e) {
    $error = $e->getMessage();
    $roomsTotal = $roomsAvailable = $reservationsTotal = $reservationsPending = $usersTotal = 0;
    $paymentsTotal = 0.0;
    $recentReservations = [];
    $recentUsers = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/css/style.css">
<title>Content Management</title>
<script>
function showTab(tab) {
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(t => t.style.display = 'none');
    document.getElementById(tab).style.display = 'block';
    const buttons = document.querySelectorAll('.tabs button');
    buttons.forEach(b => b.classList.remove('active'));
    document.querySelector('.tabs button[data-tab="'+tab+'"]').classList.add('active');
}
window.onload = function() {
    showTab('homepage'); // default
}
</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
    <div class="">
        
        <h1>Reports</h1>
        <?php if ($error): ?>
            <div style="padding:12px;background:#fdecea;border:1px solid #f5c2c2;color:#6b0b0b;border-radius:4px;margin-bottom:12px;">Error: <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="grid">
            <div class="card-stat">
                <h2><?php echo $roomsTotal; ?></h2>
                <div class="muted">Total Cottages</div>
            </div>
            <div class="card-stat">
                <h2><?php echo $roomsAvailable; ?></h2>
                <div class="muted">Available Cottages</div>
            </div>
            <div class="card-stat">
                <h2><?php echo $reservationsTotal; ?></h2>
                <div class="muted">Total Reservations</div>
            </div>
            <div class="card-stat">
                <h2><?php echo $reservationsPending; ?></h2>
                <div class="muted">Pending Reservations</div>
            </div>
            <div class="card-stat">
                <h2><?php echo $usersTotal; ?></h2>
                <div class="muted">Registered Users</div>
            </div>
            <div class="card-stat">
                <h2>&#8369; <?php echo number_format((float)$paymentsTotal,2); ?></h2>
                <div class="muted">Total Payments</div>
            </div>
        </div>

        <div style="margin-top:20px;" class="card">
            <h3>Revenue Trend</h3>
        </div>

        <div style="margin-top:20px;" class="card">
            <h3>Top Performing Rooms</h3>
        </div>

</body>
</html>
