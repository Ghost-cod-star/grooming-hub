<?php
include '../includes/db.php';
include '../includes/header.php';

// Get category or search filter
$category = isset($_GET['category']) ? trim($_GET['category']) : null;
$search = isset($_GET['q']) ? trim($_GET['q']) : null;

$where = [];
$params = [];

if ($category) {
    $where[] = "category = ?";
    $params[] = $category;
}

if ($search) {
    $where[] = "(name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql = "SELECT * FROM products";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!-- PAGE TITLE + SEARCH -->
<section class="container" style="padding:28px 20px 8px;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">
        <h2 style="margin:0;color:var(--gold)">
            Products
            <?php if($category) echo " – ".htmlspecialchars($category); ?>
            <?php if($search) echo " – Search: \"".htmlspecialchars($search)."\""; ?>
        </h2>

        <form method="get" action="products.php" style="display:flex;gap:8px;align-items:center;">
            <?php if ($category): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <?php endif; ?>
            <input name="q" placeholder="Search products..." value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                   style="padding:8px;border-radius:6px;border:1px solid #333;background:#0b0b0b;color:#fff;">
            <button class="button" type="submit">Search</button>
        </form>
    </div>
</section>

<!-- PRODUCT GRID -->
<section>
    <?php if (empty($products)): ?>
        <p style="text-align:center; margin:40px 0; font-size:18px;">
            No products found. <a href="products.php" style="color:gold;">View all products</a>
        </p>
    <?php else: ?>
        <div class="product-list container" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:24px;padding:20px 0;">
            <?php foreach ($products as $p): ?>
                <div class="product" style="background:#111;border-radius:10px;padding:18px;text-align:center;box-shadow:0 0 14px rgba(0,0,0,0.4);transition:transform 0.2s;">
                    <img src="../assets/images/<?php echo htmlspecialchars($p['image']); ?>" 
                         alt="<?php echo htmlspecialchars($p['name']); ?>" 
                         style="width:100%;border-radius:8px;object-fit:cover;height:230px;">
                    <h3 style="margin-top:14px;color:#fff;"><?php echo htmlspecialchars($p['name']); ?></h3>
                    <p style="color:#bbb;font-size:14px;min-height:45px;"><?php echo htmlspecialchars(substr($p['description'] ?? '', 0, 80)); ?>...</p>
                    <div style="color:var(--gold);font-weight:bold;font-size:18px;">Ksh <?php echo number_format($p['price'], 2); ?></div>
                    <div class="star-rating" style="margin:8px 0;color:gold;">★★★★★</div>
                    <a href="product.php?id=<?php echo $p['id']; ?>" class="button" style="margin-top:10px;display:inline-block;">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>
