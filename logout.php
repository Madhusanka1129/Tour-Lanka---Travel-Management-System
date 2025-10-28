<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Destroy all session data
session_unset();
session_destroy();


if (isset($_SESSION['admin_id'])) {
    header("Location: admin/admin_login.php");
} else {
    // Regular user logout
    header("Location: index.php");
}
exit();
?>