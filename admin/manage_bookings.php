<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}
require_once '../includes/db_connect.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_status = trim($_POST['new_status']);

    if (!empty($new_status)) {
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
        $stmt->bind_param("si", $new_status, $booking_id);

        if ($stmt->execute()) {
            $message = "Booking ID $booking_id status updated to '{$new_status}'.";
        } else {
            $message = "Error updating booking status: " . $conn->error;
        }
        $stmt->close();
    }
}

$sql = "
    SELECT 
        b.booking_id, b.booking_date, b.status, b.created_at,
        u.name AS user_name, u.email AS user_email,
        t.tour_name, 
        h.hotel_name, h.location AS hotel_location
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    LEFT JOIN tours t ON b.tour_id = t.tour_id
    LEFT JOIN hotels h ON b.hotel_id = h.hotel_id
    ORDER BY b.created_at DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings | Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        .admin-wrapper { 
            display: flex; 
            min-height: 100vh; 
        }
        
        .sidebar { 
            width: 250px; 
            background: #2c3e50; 
            color: white; 
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar h3 {
            padding: 20px;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
            color: #ecf0f1;
        }
        
        .sidebar p {
            padding: 0 20px 20px;
            color: #bdc3c7;
            font-size: 0.9em;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }
        
        .sidebar a { 
            color: #bdc3c7; 
            display: block; 
            padding: 12px 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar a:hover { 
            color: #3498db; 
            background: #34495e;
            border-left: 3px solid #3498db;
        }
        
        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .content { 
            flex-grow: 1; 
            padding: 30px;
            background: #f8f9fa;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }
        
        h1 i {
            color: #3498db;
            margin-right: 10px;
        }
        
        /* Table Styling */
        .data-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            background: white; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .data-table th, 
        .data-table td { 
            padding: 15px; 
            border-bottom: 1px solid #e9ecef; 
            text-align: left; 
            font-size: 0.9em; 
        }
        
        .data-table th { 
            background-color: #34495e; 
            color: white; 
            font-weight: 600;
        }
        
        .data-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .status-pending { 
            color: #e67e22; 
            font-weight: bold; 
            background: #fef9e7;
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .status-confirmed { 
            color: #27ae60; 
            font-weight: bold; 
            background: #e8f8ef;
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .status-cancelled { 
            color: #e74c3c; 
            font-weight: bold; 
            background: #fdedec;
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .status-form { 
            display: flex; 
            gap: 8px; 
            align-items: center; 
        }
        
        .status-form select { 
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9em;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .message {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        
        .tour-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85em;
        }
        
        .hotel-badge {
            background: #fce4ec;
            color: #c2185b;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85em;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <div class="sidebar">
            <h3>Tour Lanka Admin</h3>
            <p style="color: #bdc3c7; margin-bottom: 20px;">
                Logged in: <?php echo htmlspecialchars($_SESSION['username'] ?? $_SESSION['admin_username'] ?? 'Admin'); ?>
            </p>
            <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="manage_tours.php"><i class="fas fa-route"></i> Manage Tours</a>
            <a href="manage_hotels.php"><i class="fas fa-hotel"></i> Manage Hotels</a>
            <a href="manage_bookings.php" style="color: #3498db; border-left: 3px solid #3498db;"><i class="fas fa-book-open"></i> View Bookings</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        
        <div class="content">
            <h1><i class="fas fa-book-open"></i> Manage Customer Bookings</h1>
            <p style="color: #666; margin-bottom: 20px;">Review and update the status of all incoming tour and hotel bookings.</p>
            
            <?php if ($message): ?>
                <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($result && $result->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Booked Item</th>
                            <th>Booking Date</th>
                            <th>Booked On</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): 
                            if ($row['tour_name']) {
                                $item = '<span class="tour-badge"><i class="fas fa-route"></i> Tour: ' . htmlspecialchars($row['tour_name']) . '</span>';
                            } else {
                                $item = '<span class="hotel-badge"><i class="fas fa-hotel"></i> Hotel: ' . htmlspecialchars($row['hotel_name']) . ' (' . htmlspecialchars($row['hotel_location']) . ')</span>';
                            }
                            
                            $status_class = 'status-' . strtolower($row['status']);
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['booking_id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                                <td><?php echo $item; ?></td>
                                <td><?php echo date('M j, Y', strtotime($row['booking_date'])); ?></td>
                                <td><?php echo date('M j, Y g:i A', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <span class="<?php echo $status_class; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="manage_bookings.php" method="POST" class="status-form">
                                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                        <select name="new_status" required>
                                            <option value="">Change Status</option>
                                            <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Confirmed" <?php echo $row['status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="Cancelled" <?php echo $row['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn">
                                            <i class="fas fa-sync-alt"></i> Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 30px; background: #fff; border-radius: 8px; border-left: 5px solid #3498db; text-align: center;">
                    <i class="fas fa-inbox" style="font-size: 3em; color: #bdc3c7; margin-bottom: 15px;"></i>
                    <h3 style="color: #7f8c8d; margin-bottom: 10px;">No Bookings Found</h3>
                    <p style="color: #95a5a6;">No customer bookings have been made yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>