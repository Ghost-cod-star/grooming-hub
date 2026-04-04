<?php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Security token validation failed');
    }
    
    // Validate user is logged in
    if (!isset($_SESSION['user'])) {
        die('You must be logged in to place an order');
    }
    
    // Validate cart is not empty
    if (empty($_SESSION['cart'])) {
        die('Your cart is empty');
    }
    
    // Get and sanitize customer details
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = preg_replace('/[^0-9+]/', '', $_POST['phone']); // Only numbers and +
    $address = htmlspecialchars(trim($_POST['address']));
    
    // Validate inputs
    if (!$email) {
        die('Invalid email address');
    }
    
    if (strlen($phone) < 10) {
        die('Invalid phone number');
    }
    
    if (strlen($address) < 10) {
        die('Please provide a complete delivery address');
    }
    
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // ✅ CALCULATE TOTAL FROM CART
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        }
        
        // Insert order WITH TOTAL
        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, phone, address, total, order_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $_SESSION['user']['id'] ?? null,
            $name, 
            $email, 
            $phone, 
            $address, 
            $total
        ]);
        $order_id = $conn->lastInsertId();
        
        // Insert order items
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $order_id, 
                $product_id,
                $item['name'], 
                $item['price'], 
                $item['quantity']
            ]);
        }
        
        // Commit transaction
        $conn->commit();
        
        // Clear the cart
        unset($_SESSION['cart']);
        
        // Redirect to success page
        header("Location: success.php?order_id=" . $order_id);
        exit;
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollBack();
        error_log("Order placement error: " . $e->getMessage());
        die("Error placing order. Please try again or contact support.");
    }
} else {
    // Direct access not allowed
    header("Location: checkout.php");
    exit;
}
?>
