<?php
require_once '../includes/db_connect.php';

$new_username = 'admin';
$new_password = 'admin';

if ($conn) {
    $check = $conn->prepare("SELECT admin_id FROM admin WHERE username = ?");
    $check->bind_param("s", $new_username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $insert->bind_param("ss", $new_username, $hashed);
        $insert->execute();
        $insert->close();
    }
    $check->close();
} else {
    die("Database connection failed.");
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT admin_id, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($admin_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['admin_id'] = $admin_id;
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Username not found.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Tour Lanka</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85), rgba(30, 41, 59, 0.85)),
                url('https://walkerstours.imgix.net/2025/09/Waterfall.jpg?w=1920&h=1080&fit=crop&crop=center&auto=format,compress,enhance&q=50') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
        }

        .admin-login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #1e3a8a;
            margin: 0 0 10px 0;
            font-size: 24px;
        }

        .login-header p {
            color: #64748b;
            margin: 0;
        }

        .error-message {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #fecaca;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background: #1e40af;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

        .register-link a {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            color: #6b7280;
            text-decoration: none;
        }

        .back-link a:hover {
            color: #374151;
        }
    </style>
</head>

<body class="admin-login-page">
    <div class="login-box">
        <div class="login-header">
            <h1>Admin Login</h1>
            <p>Tour Lanka Admin Panel</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <!-- Registration Link Added Here -->
        <div class="register-link">
            <a href="admin_register.php">Create new admin account</a>
        </div>

        <div class="back-link">
            <a href="../index.php">← Back to Main Site</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
    </script>
</body>

</html>