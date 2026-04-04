<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    // Secure session configuration
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    
    session_start();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['last_activity'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Grooming Hub - Premium Men's Grooming</title>
    <link rel="stylesheet" href="/grooming-hub/assets/css/style.css">
</head>
<body>

<header>
    <h1>The Grooming Hub</h1>
    <nav>
        <a href="/grooming-hub/public/index.php">Home</a>
        <a href="/grooming-hub/public/products.php">Shop</a>
        <a href="/grooming-hub/public/cart.php">Cart</a>
        <a href="/grooming-hub/public/my_orders.php">My Orders</a>
        <a href="/grooming-hub/public/about.php">About</a>
        <a href="/grooming-hub/public/contact.php">Contact</a>

        <?php if (isset($_SESSION['user'])): ?>
            <a href="/grooming-hub/public/profile.php">Profile</a>
            <span style="color: gold; font-weight: bold; margin-left: 10px;">
                Hello, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
            </span>
            <a href="/grooming-hub/public/logout.php">Logout</a>
        <?php else: ?>
            <a href="/grooming-hub/public/login.php">Login</a>
            <a href="/grooming-hub/public/register.php">Register</a>
        <?php endif; ?>
    </nav>

    <!-- Search Bar -->
    <div class="search-bar">
        <form action="/grooming-hub/public/search.php" method="GET">
            <input type="text" name="q" placeholder="Search grooming products..." required>
            <button type="submit">Search</button>
        </form>
    </div>
</header>
<hr>
