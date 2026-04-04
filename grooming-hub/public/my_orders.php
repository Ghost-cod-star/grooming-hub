<?php
include '../includes/db.php';
include '../includes/header.php';

// Require Login
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['email'])) {
    echo "<p style='text-align:center; padding:40px;'>
            Please <a href='login.php' style='color:gold; font-weight:bold;'>login</a> to view your orders.
          </p>";
    include '../includes/footer.php';
    exit;
}

$userEmail = $_SESSION['user']['email'];

echo "<h2 style='text-align:center; margin:30px 0;'>📦 My Orders</h2>";

// Fetch user orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE email = ? ORDER BY order_date DESC");
$stmt->execute([$userEmail]);
$orders = $stmt->fetchAll();

if (empty($orders)) {
    echo "<p style='text-align:center; margin:40px 0; font-size:18px;'>
            You have no orders yet. <a href='products.php' style='color:gold; font-weight:bold;'>Start Shopping</a>
          </p>";
} else {
    echo "<div style='max-width:900px; margin:0 auto; padding:0 20px;'>";
    
    foreach ($orders as $order) {
        // Fetch order items
        $itemsStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $itemsStmt->execute([$order['id']]);
        $items = $itemsStmt->fetchAll();

        // Use the total from database (now properly calculated!)
        $total = $order['total'] ?? 0;
        
        // Format order number
        $orderNumber = str_pad($order['id'], 6, '0', STR_PAD_LEFT);
        
        echo "<div class='order-card' style='background:#111; padding:25px; margin-bottom:25px; border-radius:12px; border:2px solid #333;'>";
        
        // Header
        echo "<div style='display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; padding-bottom:15px; border-bottom:2px solid #333;'>";
        echo "  <div>";
        echo "    <h3 style='color:gold; margin-bottom:5px;'>Order #{$orderNumber}</h3>";
        echo "    <p style='color:#888; font-size:14px;'>📅 " . date("F d, Y", strtotime($order['order_date'])) . "</p>";
        echo "  </div>";
        echo "  <div>";
        echo "    <span class='status pending' style='background:#ff9800; color:#111; padding:8px 16px; border-radius:20px; font-weight:bold;'>";
        echo "      " . htmlspecialchars($order['status'] ?? 'Pending') . "</span>";
        echo "  </div>";
        echo "</div>";
        
        // Delivery Info
        echo "<div style='background:#1a1a1a; padding:15px; border-radius:8px; margin-bottom:15px;'>";
        echo "  <p style='margin-bottom:5px;'><strong>📍 Delivery Address:</strong> " . htmlspecialchars($order['address']) . "</p>";
        echo "  <p style='margin-bottom:5px;'><strong>📞 Phone:</strong> " . htmlspecialchars($order['phone']) . "</p>";
        echo "</div>";
        
        // Order Items
        echo "<div class='order-items' style='margin-bottom:15px;'>";
        echo "  <h4 style='color:#fff; margin-bottom:10px;'>Items:</h4>";
        foreach ($items as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            echo "<div style='display:flex; justify-content:space-between; padding:10px; background:#1a1a1a; margin-bottom:8px; border-radius:6px;'>";
            echo "  <span>✅ " . htmlspecialchars($item['product_name']) . " × " . $item['quantity'] . "</span>";
            echo "  <span style='color:gold; font-weight:bold;'>Ksh " . number_format($itemTotal, 2) . "</span>";
            echo "</div>";
        }
        echo "</div>";

        // Total
        echo "<div style='background:#1a1a1a; padding:15px; border-radius:8px; text-align:right;'>";
        echo "  <p style='font-size:20px;'><strong>💰 TOTAL:</strong> ";
        echo "  <span style='color:gold; font-weight:bold; font-size:24px;'>Ksh " . number_format($total, 2) . "</span></p>";
        echo "</div>";

        echo "</div>";
    }
    
    echo "</div>";
}

include '../includes/footer.php';
?>
