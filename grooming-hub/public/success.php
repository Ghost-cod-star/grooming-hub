<?php
include '../includes/header.php';

if (isset($_GET['order_id'])) {
    $order_id = (int)$_GET['order_id'];
    ?>
    
    <section class="page" style="text-align:center;">
        <div style="font-size:80px; margin-bottom:20px;">🎉</div>
        <h2 style="color:gold; margin-bottom:15px;">Order Placed Successfully!</h2>
        
        <div style="background:#1a1a1a; padding:30px; border-radius:12px; margin:20px auto; max-width:500px;">
            <p style="font-size:18px; margin-bottom:10px;">Your Order Number:</p>
            <p style="font-size:32px; font-weight:bold; color:gold; margin-bottom:20px;">
                #<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?>
            </p>
            <p style="color:#bbb; line-height:1.8;">
                Thank you for shopping with <strong style="color:gold;">The Grooming Hub</strong>!<br>
                We'll process your order and contact you shortly.
            </p>
        </div>
        
        <div style="margin-top:30px; display:flex; gap:15px; justify-content:center;">
            <a href="my_orders.php" class="button" style="background:gold; color:#111; padding:12px 24px; text-decoration:none;">
                View My Orders
            </a>
            <a href="products.php" class="button" style="background:#555; color:#fff; padding:12px 24px; text-decoration:none;">
                Continue Shopping
            </a>
        </div>
    </section>
    
    <?php
} else {
    echo "<p style='text-align:center; padding:40px;'>No order information found.</p>";
}

include '../includes/footer.php';
?>
