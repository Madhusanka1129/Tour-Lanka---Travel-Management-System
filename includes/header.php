<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php';

$is_logged_in = isset($_SESSION['user_id']);

if (!isset($page_title)) {
    $page_title = "Book Your Dream Holiday";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Tour Lanka</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/about.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" href="assets/images/titleIcon.jpg">

    <style>
        .logo-container {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-main {
            font-size: 1.75rem;
            font-weight: 800;
            color: white;
            line-height: 1;
        }

        .logo-tagline {
            font-size: 0.75rem;
            color: #cbd5e1;
            font-weight: 500;
            letter-spacing: 1px;
        }

        .user-welcome {
            color: #f59e0b;
            font-weight: 600;
            padding: 8px 0;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 5px;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 3px 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
    </style>

</head>

<body>
    <header>
        <nav class="navbar" id="navbar">
            <div class="container">
                <div class="nav-brand">
                    <a href="index.php" class="logo-container">
                        <div class="logo-text">
                            <span class="logo-main">Tour Lanka</span>
                            <span class="logo-tagline">Discover Paradise</span>
                        </div>
                    </a>
                </div>

                <ul class="nav-menu" id="nav-menu">
                    <li><a href="index.php" class="nav-link">Home</a></li>
                    <li><a href="tours.php" class="nav-link">Tours</a></li>
                    <li><a href="hotels.php" class="nav-link">Hotels</a></li>
                    <li><a href="about.php" class="nav-link">About</a></li>
                    <li><a href="contact.php" class="nav-link">Contact</a></li>

                    <?php if ($is_logged_in): ?>
                        <li>
                            <div class="user-welcome">
                                Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                            </div>
                        </li>
                        <li><a href="logout.php" class="nav-link btn-nav">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="nav-link btn-nav">Login/Register</a></li>
                    <?php endif; ?>

                    <li><a href="admin/admin_login.php" class="nav-link admin-link">Admin Panel</a></li>
                </ul>

                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>
    </header>

    <main>