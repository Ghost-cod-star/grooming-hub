<?php
include '../includes/header.php';

// Block access if user NOT logged in
if (!isset($_SESSION['user'])) {
    echo "<p style='text-align:center; padding:20px;'>
            You must <a href='login.php'>login</a> to view your cart.
          </p>";
    include '../includes/footer.php';
    exit;
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Security token validation failed');
    }
    
    // Add to cart
    if (isset($_POST['add_to_cart'])) {
        $id = (int)$_POST['id'];
        $name = htmlspecialchars($_POST['name']);
        $price = (float)$_POST['price'];
        $quantity = max(1, min(99, (int)$_POST['quantity']));
        $image = htmlspecialchars($_POST['image'] ?? '');
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $image
            ];
        }
        
        echo "<p style='color:green;text-align:center;padding:10px;'>✅ {$name} added to cart!</p>";
    }
    
    // Update quantity
    if (isset($_POST['update_cart'])) {
        $id = (int)$_POST['id'];
        $quantity = max(1, min(99, (int)$_POST['quantity']));
        
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $quantity;
            header("Location: cart.php");
            exit;
        }
    }
    
    // Remove item
    if (isset($_POST['remove_item'])) {
        $id = (int)$_POST['id'];
        unset($_SESSION['cart'][$id]);
        header("Location: cart.php");
        exit;
    }
    
    // Clear cart
    if (isset($_POST['clear_cart'])) {
        unset($_SESSION['cart']);
        header("Location: cart.php");
        exit;
    }
}

echo "<h2 style='text-align:center;margin:20px 0;'>🛒 Your Shopping Cart</h2>";

// Display cart
if (empty($_SESSION['cart'])) {
    echo "<p style='text-align:center;margin:40px 0;'>
            Your cart is empty. <a href='products.php' style='color:gold;font-weight:bold;'>Start Shopping</a>
          </p>";
} else {
    $total = 0;
    
    echo "<div style='max-width:1000px; margin:20px auto; padding:0 20px;'>";
    echo "<table style='width:100%; border-collapse:collapse; background:#111; border-radius:8px; overflow:hidden;'>";
    echo "<thead>
            <tr style='background:#1a1a1a;'>
                <th style='padding:15px; text-align:left;'>Product</th>
                <th style='padding:15px; text-align:center;'>Price</th>
                <th style='padding:15px; text-align:center;'>Quantity</th>
                <th style='padding:15px; text-align:center;'>Subtotal</th>
                <th style='padding:15px; text-align:center;'>Action</th>
            </tr>
          </thead>";
    echo "<tbody>";
    
    foreach ($_SESSION['cart'] as $id => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        
        echo "<tr style='border-bottom:1px solid #333;'>";
        echo "<td style='padding:15px;'>" . htmlspecialchars($item['name']) . "</td>";
        echo "<td style='padding:15px; text-align:center;'>Ksh " . number_format($item['price'], 2) . "</td>";
        echo "<td style='padding:15px; text-align:center;'>
                <form method='POST' style='display:inline-flex; align-items:center; gap:8px;'>
                    <input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>
                    <input type='hidden' name='id' value='{$id}'>
                    <input type='number' name='quantity' value='{$item['quantity']}' 
                           min='1' max='99' style='width:70px; padding:6px; text-align:center; border-radius:4px; border:1px solid #555; background:#0b0b0b; color:#fff;'>
                    <button type='submit' name='update_cart' 
                            style='padding:6px 12px; background:#555; border:none; border-radius:4px; color:#fff; cursor:pointer;'>
                            Update
                    </button>
                </form>
              </td>";
        echo "<td style='padding:15px; text-align:center; font-weight:bold; color:gold; font-size:16px;'>
                Ksh " . number_format($subtotal, 2) . "</td>";
        echo "<td style='padding:15px; text-align:center;'>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>
                    <input type='hidden' name='id' value='{$id}'>
                    <button type='submit' name='remove_item' 
                            style='padding:8px 16px; background:#d32f2f; border:none; border-radius:4px; color:#fff; cursor:pointer; font-weight:bold;'
                            onclick='return confirm(\"Remove this item from cart?\")'>
                            Remove
                    </button>
                </form>
              </td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "<tfoot>
            <tr style='background:#1a1a1a; font-size:20px; font-weight:bold;'>
                <td colspan='3' style='padding:20px; text-align:right;'>TOTAL:</td>
                <td style='padding:20px; text-align:center; color:gold; font-size:24px;'>
                    Ksh " . number_format($total, 2) . "</td>
                <td></td>
            </tr>
          </tfoot>";
    echo "</table>";
    
    echo "<div style='margin-top:30px; text-align:center; display:flex; gap:15px; justify-content:center;'>
            <a href='checkout.php' class='button' 
               style='background:gold; color:#111; padding:15px 30px; font-size:18px; font-weight:bold; text-decoration:none; border-radius:8px; display:inline-block;'>
               Proceed to Checkout →
            </a>
            
            <a href='products.php' class='button'
               style='background:#555; color:#fff; padding:15px 30px; font-size:18px; text-decoration:none; border-radius:8px; display:inline-block;'>
               Continue Shopping
            </a>
            
            <form method='POST' style='display:inline;'>
                <input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>
                <button type='submit' name='clear_cart' 
                        style='background:#333; color:#fff; padding:15px 30px; font-size:18px; border:none; border-radius:8px; cursor:pointer;'
                        onclick='return confirm(\"Clear entire cart?\")'>
                    Clear Cart
                </button>
            </form>
          </div>";
    
    echo "</div>";
}

include '../includes/footer.php';
?>
