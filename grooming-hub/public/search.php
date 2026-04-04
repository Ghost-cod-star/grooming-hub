<?php
include '../includes/db.php';
include '../includes/header.php';

// Get search query
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

echo "<h2 style='text-align:center; margin:30px 0;'>🔍 Search Results for: <em style='color:gold;'>\"" . htmlspecialchars($q) . "\"</em></h2>";

if ($q == '') {
    echo "<p style='text-align:center; margin:40px 0;'>Please enter a search term. <a href='products.php' style='color:gold;'>Browse all products</a></p>";
    include '../includes/footer.php';
    exit;
}

// Search by name + description
$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY name ASC");
$searchTerm = "%" . $q . "%";
$stmt->execute([$searchTerm, $searchTerm]);
$results = $stmt->fetchAll();

if (empty($results)) {
    echo "<div style='text-align:center; margin:40px 0;'>";
    echo "  <p style='font-size:18px; margin-bottom:15px;'>No products found matching your search.</p>";
    echo "  <p><a href='products.php' class='button' style='display:inline-block;'>View All Products</a></p>";
    echo "</div>";
} else {
    echo "<p style='text-align:center; color:#888; margin-bottom:20px;'>Found " . count($results) . " product(s)</p>";
    
    echo "<div class='product-list' style='display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:24px; max-width:1200px; margin:0 auto; padding:20px;'>";
    foreach ($results as $row) {
        echo "<div class='product' style='background:#111; border-radius:10px; padding:18px; text-align:center; box-shadow:0 0 14px rgba(0,0,0,0.4);'>";
        echo "<img src='../assets/images/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' style='width:100%; height:230px; object-fit:cover; border-radius:8px;'>";
        echo "<h3 style='margin-top:14px; color:#fff;'>" . htmlspecialchars($row['name']) . "</h3>";
        echo "<p style='color:#bbb; font-size:14px; min-height:45px;'>" . htmlspecialchars(substr($row['description'], 0, 80)) . "...</p>";
        echo "<p class='stars' style='color:gold; margin:8px 0;'>★★★★★</p>";
        echo "<strong style='color:var(--gold); font-size:18px;'>Ksh " . number_format($row['price'], 2) . "</strong><br>";
        echo "<a href='product.php?id=" . $row['id'] . "' class='button' style='margin-top:10px; display:inline-block;'>View Details</a>";
        echo "</div>";
    }
    echo "</div>";
}

include '../includes/footer.php';
?>
