<?php
include 'auth_check.php';
include '../includes/db.php';
include '../includes/header.php';

echo "<section class='page' style='max-width:1200px;'>";
echo "<h2>Order Management</h2>";

// Fetch all orders
$stmt = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
$orders = $stmt->fetchAll();

if (empty($orders)) {
    echo "<p style='text-align:center; padding:40px; color:#888;'>No orders yet.</p>";
} else {
    echo "<p style='color:#888; margin-bottom:20px;'>Total Orders: " . count($orders) . "</p>";
    
    foreach ($orders as $order) {
        // Fetch order items
        $itemsStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $itemsStmt->execute([$order['id']]);
        $items = $itemsStmt->fetchAll();
        
        $orderNumber = str_pad($order['id'], 6, '0', STR_PAD_LEFT);
        
        echo "<div style='background:#1a1a1a; padding:20px; margin-bottom:20px; border-radius:10px; border:2px solid #333;'>";
        
        // Header
        echo "<div style='display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; padding-bottom:15px; border-bottom:1px solid #333;'>";
        echo "  <div>";
        echo "    <h3 style='color:gold; margin-bottom:5px;'>Order #{$orderNumber}</h3>";
        echo "    <p style='color:#888; font-size:14px;'>" . date("F d, Y g:i A", strtotime($order['order_date'])) . "</p>";
        echo "  </div>";
        echo "  <div>";
        echo "    <span style='background:#ff9800; color:#111; padding:6px 14px; border-radius:16px; font-weight:bold; font-size:13px;'>";
        echo "      " . htmlspecialchars($order['status'] ?? 'Pending') . "</span>";
        echo "  </div>";
        echo "</div>";
        
        // Customer Info
        echo "<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:15px; margin-bottom:15px;'>";
        echo "  <div>";
        echo "    <p style='color:#888; font-size:13px; margin-bottom:3px;'>Customer Name</p>";
        echo "    <p style='font-weight:bold;'>" . htmlspecialchars($order['name']) . "</p>";
        echo "  </div>";
        echo "  <div>";
        echo "    <p style='color:#888; font-size:13px; margin-bottom:3px;'>Email</p>";
        echo "    <p style='font-weight:bold;'>" . htmlspecialchars($order['email']) . "</p>";
        echo "  </div>";
        echo "  <div>";
        echo "    <p style='color:#888; font-size:13px; margin-bottom:3px;'>Phone</p>";
        echo "    <p style='font-weight:bold;'>" . htmlspecialchars($order['phone']) . "</p>";
        echo "  </div>";
        echo "</div>";
        
        echo "<div style='margin-bottom:15px;'>";
        echo "  <p style='color:#888; font-size:13px; margin-bottom:3px;'>Delivery Address</p>";
        echo "  <p style='font-weight:bold;'>" . htmlspecialchars($order['address']) . "</p>";
        echo "</div>";
        
        // Order Items
        echo "<div style='background:#0f0f10; padding:15px; border-radius:6px; margin-bottom:15px;'>";
        echo "  <h4 style='margin-bottom:10px;'>Items:</h4>";
        foreach ($items as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            echo "<div style='display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #333;'>";
            echo "  <span>" . htmlspecialchars($item['product_name']) . " × " . $item['quantity'] . "</span>";
            echo "  <span style='color:gold; font-weight:bold;'>Ksh " . number_format($itemTotal, 2) . "</span>";
            echo "</div>";
        }
        echo "</div>";
        
        // Total
        echo "<div style='text-align:right; font-size:20px;'>";
        echo "  <strong>TOTAL:</strong> <span style='color:gold; font-weight:bold;'>Ksh " . number_format($order['total'], 2) . "</span>";
        echo "</div>";
        
        echo "</div>";
    }
}

echo "</section>";

include '../includes/footer.php';
?>
