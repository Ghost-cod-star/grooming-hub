<?php
// Admin authentication check - Add this to the top of EVERY admin page

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

// Check session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}

// Update last activity
$_SESSION['last_activity'] = time();
?>
