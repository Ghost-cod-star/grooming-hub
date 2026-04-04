<?php
include 'auth_check.php';
include '../includes/db.php';
include '../includes/header.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

echo "<section class='page' style='max-width:1200px;'>";
echo "<div style='display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;'>";
echo "  <h2 style='margin:0;'>Product Management</h2>";
echo "  <a href='add_product.php' class='button' style='background:gold; color:#111; text-decoration:none; padding:10px 20px;'>+ Add New Product</a>";
echo "</div>";

// Fetch all products
$stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();

if (empty($products)) {
    echo "<p style='text-align:center; padding:40px; color:#888;'>No products yet. Add your first product!</p>";
} else {
    echo "<table style='width:100%; border-collapse:collapse; background:#1a1a1a; border-radius:8px; overflow:hidden;'>";
    echo "<thead>
            <tr style='background:#0f0f10;'>
                <th style='padding:15px; text-align:left;'>Image</th>
                <th style='padding:15px; text-align:left;'>Name</th>
                <th style='padding:15px; text-align:left;'>Category</th>
                <th style='padding:15px; text-align:left;'>Price</th>
                <th style='padding:15px; text-align:center;'>Actions</th>
            </tr>
          </thead>";
    echo "<tbody>";

    foreach ($products as $row) {
        echo "<tr style='border-bottom:1px solid #333;'>";
        echo "  <td style='padding:15px;'><img src='../assets/images/{$row['image']}' style='width:60px; height:60px; object-fit:cover; border-radius:6px;'></td>";
        echo "  <td style='padding:15px;'>" . htmlspecialchars($row['name']) . "</td>";
        echo "  <td style='padding:15px; color:#888;'>" . htmlspecialchars($row['category'] ?? 'N/A') . "</td>";
        echo "  <td style='padding:15px; color:gold; font-weight:bold;'>Ksh " . number_format($row['price'], 2) . "</td>";
        echo "  <td style='padding:15px; text-align:center;'>";
        echo "    <a href='edit_product.php?id={$row['id']}' style='color:#2196f3; margin-right:15px; text-decoration:none;'>Edit</a>";
        echo "    <a href='delete_product.php?id={$row['id']}&token={$_SESSION['csrf_token']}' ";
        echo "       style='color:#f44336; text-decoration:none;' ";
        echo "       onclick='return confirm(\"Delete this product?\")'>Delete</a>";
        echo "  </td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    
    echo "<p style='margin-top:15px; color:#888; text-align:center;'>Total: " . count($products) . " product(s)</p>";
}

echo "</section>";

include '../includes/footer.php';
?>
