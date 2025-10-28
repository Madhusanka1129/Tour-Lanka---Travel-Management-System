<?php
require_once '../includes/db_connect.php';


$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if ($username === '' || $password === '' || $confirm_password === '') {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        $check = $conn->prepare("SELECT admin_id FROM admin WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            $insert->bind_param("ss", $username, $hashed);

            if ($insert->execute()) {
                $success = "Admin account created successfully! You can now login.";
                $username = '';
            } else {
                $error = "Error creating account. Please try again.";
            }
            $insert->close();
        }
        $check->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration | Tour Lanka</title>
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

        .admin-register-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header h1 {
            color: #1e3a8a;
            margin: 0 0 10px 0;
            font-size: 24px;
        }

        .register-header p {
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

        .success-message {
            background: #f0fdf4;
            color: #16a34a;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #bbf7d0;
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
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 12px;
            color: #6b7280;
        }

        .register-btn {
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

        .register-btn:hover {
            background: #1e40af;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
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

<body class="admin-register-page">
    <div class="register-box">
        <div class="register-header">
            <h1>Admin Registration</h1>
            <p>Create a new admin account for Tour Lanka</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
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
                <input type="password" id="password" name="password" class="form-control" required
                    minlength="6">
                <div class="password-strength">Password must be at least 6 characters long</div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>

            <button type="submit" class="register-btn">Register Admin</button>
        </form>

        <div class="login-link">
            <a href="admin_login.php">Already have an account? Login here</a>
        </div>

        <div class="back-link">
            <a href="../index.php">← Back to Main Site</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();

            // Password confirmation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');

            function validatePassword() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity("Passwords don't match");
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }

            password.addEventListener('change', validatePassword);
            confirmPassword.addEventListener('keyup', validatePassword);
        });
    </script>
</body>

</html>