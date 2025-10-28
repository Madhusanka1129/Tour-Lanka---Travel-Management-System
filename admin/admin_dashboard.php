<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/db_connect.php';


if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$dashboard_data = [
    'total_tours' => 0,
    'total_hotels' => 0,
    'total_bookings' => 0,
    'pending_bookings' => 0,
    'recent_bookings' => []
];

try {
    $result = $conn->query("SELECT COUNT(*) AS count FROM tours");
    $dashboard_data['total_tours'] = $result ? $result->fetch_assoc()['count'] : 0;

    $result = $conn->query("SELECT COUNT(*) AS count FROM hotels");
    $dashboard_data['total_hotels'] = $result ? $result->fetch_assoc()['count'] : 0;

    $result = $conn->query("SELECT COUNT(*) AS count FROM bookings");
    $dashboard_data['total_bookings'] = $result ? $result->fetch_assoc()['count'] : 0;

    $result = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE status='Pending'");
    $dashboard_data['pending_bookings'] = $result ? $result->fetch_assoc()['count'] : 0;

    $result = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5");
    if ($result) {
        $dashboard_data['recent_bookings'] = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Tour Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 30px 20px;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            color: #009fc7;
            margin-bottom: 5px;
            font-size: 1.3rem;
        }

        .sidebar-header p {
            color: #bdc3c7;
            font-size: 0.9rem;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu a {
            color: #ecf0f1;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 25px;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar-menu a:hover {
            background: rgba(0, 159, 199, 0.1);
            color: #009fc7;
            border-left-color: #009fc7;
        }

        .sidebar-menu a.active {
            background: rgba(0, 159, 199, 0.2);
            color: #009fc7;
            border-left-color: #009fc7;
        }

        .content {
            flex-grow: 1;
            padding: 30px;
            background: #f8f9fa;
        }

        .header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2rem;
        }

        .header h1 i {
            color: #009fc7;
            margin-right: 10px;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .card-item {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid #009fc7;
        }

        .card-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .card-item h3 {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .card-item .number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0;
        }

        .card-item.pending {
            border-left-color: #e74c3c;
        }

        .card-item.pending .number {
            color: #e74c3c;
        }

        .card-item.tours {
            border-left-color: #27ae60;
        }

        .card-item.tours .number {
            color: #27ae60;
        }

        .card-item.hotels {
            border-left-color: #f39c12;
        }

        .card-item.hotels .number {
            color: #f39c12;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8f9fa;
        }

        .section h2 i {
            color: #009fc7;
            margin-right: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: #009fc7;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #0681a0;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #e74c3c;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-success {
            background: #27ae60;
        }

        .btn-success:hover {
            background: #219653;
        }

        .quick-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .admin-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>Tour Lanka Admin</h3>
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></p>
            </div>
            <div class="sidebar-menu">
                <a href="admin_dashboard.php" class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="manage_tours.php">
                    <i class="fas fa-route"></i> Manage Tours
                </a>
                <a href="manage_hotels.php">
                    <i class="fas fa-hotel"></i> Manage Hotels
                </a>
                <a href="manage_bookings.php">
                    <i class="fas fa-book-open"></i> View Bookings
                </a>
                <a href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <div class="content">
            <div class="header">
                <h1><i class="fas fa-chart-line"></i> Dashboard Overview</h1>
            </div>

            <div class="dashboard-cards">
                <div class="card-item tours">
                    <h3>Total Tours</h3>
                    <div class="number"><?php echo $dashboard_data['total_tours']; ?></div>
                    <p>Active tour packages</p>
                </div>
                <div class="card-item hotels">
                    <h3>Total Hotels</h3>
                    <div class="number"><?php echo $dashboard_data['total_hotels']; ?></div>
                    <p>Partner hotels</p>
                </div>
                <div class="card-item">
                    <h3>All Bookings</h3>
                    <div class="number"><?php echo $dashboard_data['total_bookings']; ?></div>
                    <p>Total reservations</p>
                </div>
                <div class="card-item pending">
                    <h3>Pending Bookings</h3>
                    <div class="number"><?php echo $dashboard_data['pending_bookings']; ?></div>
                    <p>Require attention</p>
                </div>
            </div>

            <div class="section">
                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                <p>Access the main management sections quickly:</p>
                <div class="quick-actions">
                    <a href="manage_tours.php" class="btn">
                        <i class="fas fa-plus"></i> Add New Tour
                    </a>
                    <a href="manage_hotels.php" class="btn">
                        <i class="fas fa-plus"></i> Add New Hotel
                    </a>
                    <a href="manage_bookings.php" class="btn btn-danger">
                        <i class="fas fa-eye"></i> Review Bookings
                    </a>
                </div>
            </div>

            <?php if (!empty($dashboard_data['recent_bookings'])): ?>
                <div class="section">
                    <h2><i class="fas fa-clock"></i> Recent Bookings</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dashboard_data['recent_bookings'] as $booking): ?>
                                <tr>
                                    <td>#<?php echo $booking['booking_id']; ?></td>
                                    <td><?php echo htmlspecialchars($booking['customer_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($booking['booking_type'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($booking['created_at'])); ?></td>
                                    <td>
                                        <span class="status-<?php echo strtolower($booking['status']); ?>">
                                            <?php echo $booking['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>