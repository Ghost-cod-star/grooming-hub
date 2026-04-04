<?php
include '../includes/header.php';

// Block access if user NOT logged in
if (!isset($_SESSION['user'])) {
    echo "<p style='text-align:center; padding:20px;'>
            You must <a href='login.php'>login</a> before checkout.
          </p>";
    include '../includes/footer.php';
    exit;
}

// Block if cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p style='text-align:center; padding:20px;'>
            Your cart is empty. <a href='products.php'>Shop Now</a>
          </p>";
    include '../includes/footer.php';
    exit;
}

// Pre-fill with user data
$userName = $_SESSION['user']['name'];
$userEmail = $_SESSION['user']['email'];

// Calculate cart total
$cartTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartTotal += $item['price'] * $item['quantity'];
}
?>

<section class="page" style="max-width:600px;">
    <h2>Checkout</h2>
    
    <!-- Order Summary -->
    <div style="background:#1a1a1a; padding:20px; border-radius:8px; margin-bottom:20px;">
        <h3 style="color:gold; margin-bottom:15px;">Order Summary</h3>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <div style="display:flex; justify-content:space-between; margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid #333;">
                <span><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['quantity']; ?></span>
                <span style="color:gold; font-weight:bold;">Ksh <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
            </div>
        <?php endforeach; ?>
        <div style="display:flex; justify-content:space-between; font-size:20px; font-weight:bold; margin-top:15px; padding-top:15px; border-top:2px solid gold;">
            <span>TOTAL:</span>
            <span style="color:gold;">Ksh <?php echo number_format($cartTotal, 2); ?></span>
        </div>
    </div>
    
    <form method="post" action="place_order.php">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label>Full Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($userName); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly 
               style="background:#1a1a1a; cursor:not-allowed;">

        <label>Phone Number:</label>
        <input type="tel" name="phone" pattern="[0-9]{10,15}" required 
               placeholder="e.g. 0712345678">

        <label>Delivery Address:</label>
        <textarea name="address" required rows="4" 
                  placeholder="Enter your full delivery address (street, building, city)"></textarea>

        <button type="submit" style="width:100%; padding:15px; font-size:18px; font-weight:bold; background:gold; color:#111;">
            Place Order (Ksh <?php echo number_format($cartTotal, 2); ?>)
        </button>
    </form>
    
    <p style="text-align:center; margin-top:15px;">
        <a href="cart.php" style="color:#888;">← Back to Cart</a>
    </p>
</section>

<?php include '../includes/footer.php'; ?>
