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
$edit_tour = null;

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $tour_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM tours WHERE tour_id = ?");
    $stmt->bind_param("i", $tour_id);
    if ($stmt->execute()) {
        $message = " Tour ID $tour_id deleted successfully.";
    } else {
        $message = " Error deleting tour: " . $conn->error;
    }
    $stmt->close();
    header("Location: manage_tours.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $tour_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM tours WHERE tour_id = ?");
    $stmt->bind_param("i", $tour_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_tour = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_tour'])) {
    $tour_name = trim($_POST['tour_name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $image = trim($_POST['image']);
    $tour_id = isset($_POST['tour_id']) ? intval($_POST['tour_id']) : 0;

    if (empty($tour_name) || empty($description) || $price <= 0) {
        $message = " Error: Tour Name, Description, and Price are required.";
    } else {
        if ($tour_id > 0) {
            if (!empty($image)) {
                $stmt = $conn->prepare("UPDATE tours SET tour_name = ?, description = ?, price = ?, image = ? WHERE tour_id = ?");
                $stmt->bind_param("ssdsi", $tour_name, $description, $price, $image, $tour_id);
            } else {
                $stmt = $conn->prepare("UPDATE tours SET tour_name = ?, description = ?, price = ? WHERE tour_id = ?");
                $stmt->bind_param("ssdi", $tour_name, $description, $price, $tour_id);
            }

            if ($stmt->execute()) {
                $message = "Tour ID $tour_id updated successfully.";
                $edit_tour = null;
            } else {
                $message = "Error updating tour: " . $stmt->error;
            }
            $stmt->close();
        } else {
            if (!empty($image)) {
                $stmt = $conn->prepare("INSERT INTO tours (tour_name, description, price, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssds", $tour_name, $description, $price, $image);
            } else {
                $stmt = $conn->prepare("INSERT INTO tours (tour_name, description, price) VALUES (?, ?, ?)");
                $stmt->bind_param("ssd", $tour_name, $description, $price);
            }

            if ($stmt->execute()) {
                $message = "New tour '{$tour_name}' added successfully.";
            } else {
                $message = "Error adding tour: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$tours_result = $conn->query("SELECT * FROM tours ORDER BY tour_id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tours | Admin</title>
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
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
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
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        h1 i {
            color: #3498db;
            margin-right: 10px;
        }

        .tour-form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #3498db;
        }

        .tour-form h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
            transition: border 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            transition: background 0.3s ease;
            margin-right: 10px;
        }

        .btn:hover {
            background: #2980b9;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table th,
        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            text-align: left;
            font-size: 0.95em;
        }

        .data-table th {
            background-color: #34495e;
            color: white;
            font-weight: 600;
        }

        .data-table tr:hover {
            background-color: #f8f9fa;
        }

        .action-links a {
            margin-right: 10px;
            color: #3498db;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background 0.3s ease;
        }

        .action-links a:hover {
            background: #e3f2fd;
            text-decoration: none;
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

        hr {
            border: none;
            border-top: 2px solid #e9ecef;
            margin: 30px 0;
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        <div class="sidebar">
            <h3>Tour Lanka Admin</h3>
            <p>Logged in: <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></p>
            <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="manage_tours.php" style="color: #3498db; border-left: 3px solid #3498db;"><i class="fas fa-route"></i> Manage Tours</a>
            <a href="manage_hotels.php"><i class="fas fa-hotel"></i> Manage Hotels</a>
            <a href="manage_bookings.php"><i class="fas fa-book-open"></i> View Bookings</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <div class="content">
            <h1><i class="fas fa-route"></i> Manage Tour Packages</h1>

            <?php if ($message): ?>
                <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="tour-form">
                <h2><?php echo $edit_tour ? 'Edit Tour ID: ' . $edit_tour['tour_id'] : 'Add New Tour Package'; ?></h2>
                <form action="manage_tours.php" method="POST">
                    <?php if ($edit_tour): ?>
                        <input type="hidden" name="tour_id" value="<?php echo htmlspecialchars($edit_tour['tour_id']); ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="tour_name">Tour Name:</label>
                        <input type="text" id="tour_name" name="tour_name" value="<?php echo htmlspecialchars($edit_tour['tour_name'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($edit_tour['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Price ($):</label>
                        <input type="number" step="0.01" min="0" id="price" name="price" value="<?php echo htmlspecialchars($edit_tour['price'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="image">Image URL:</label>
                        <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($edit_tour['image'] ?? ''); ?>" placeholder="https://example.com/image.jpg">
                    </div>

                    <button type="submit" name="submit_tour" class="btn">
                        <?php echo $edit_tour ? '<i class="fas fa-save"></i> Save Changes' : '<i class="fas fa-plus"></i> Add Tour'; ?>
                    </button>

                    <?php if ($edit_tour): ?>
                        <a href="manage_tours.php" class="btn" style="background: #95a5a6;">Cancel Edit</a>
                    <?php endif; ?>
                </form>
            </div>

            <hr>

            <h2>Existing Tour Packages</h2>
            <?php if ($tours_result && $tours_result->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description Snippet</th>
                            <th>Image Path</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $tours_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['tour_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['tour_name']); ?></strong></td>
                                <td>$<?php echo number_format($row['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 50)) . '...'; ?></td>
                                <td><?php echo htmlspecialchars($row['image'] ?? 'No image'); ?></td>
                                <td class="action-links">
                                    <a href="manage_tours.php?action=edit&id=<?php echo $row['tour_id']; ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="manage_tours.php?action=delete&id=<?php echo $row['tour_id']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this tour? This cannot be undone.');"
                                        style="color: #e74c3c;">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 20px; background: #fff; border-radius: 8px; border-left: 5px solid #3498db; text-align: center;">
                    <p style="margin: 0; color: #7f8c8d;">
                        <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                        No tours have been added yet. Use the form above to add your first package.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>