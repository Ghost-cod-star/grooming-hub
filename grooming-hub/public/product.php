<?php
include '../includes/db.php';
include '../includes/header.php';

// Validate product ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<div class='container' style='padding:20px;'><p>Product not found. <a href='products.php'>Back to Products</a></p></div>";
    include '../includes/footer.php';
    exit;
}

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='container' style='padding:20px;'><p>Product not found. <a href='products.php'>Back to Products</a></p></div>";
    include '../includes/footer.php';
    exit;
}

// Fetch related products (same category)
$category = $product['category'] ?? null;
$related = [];
if ($category) {
    $rstmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND id != ? ORDER BY RAND() LIMIT 4");
    $rstmt->execute([$category, $id]);
    $related = $rstmt->fetchAll();
}
?>

<!-- BACK BUTTON -->
<section class="container" style="padding:24px 0 10px;">
    <a href="products.php" style="color:var(--gold);text-decoration:none;font-weight:bold;">← Back to Products</a>
</section>

<!-- PRODUCT DETAILS -->
<section class="container" style="display:flex;flex-wrap:wrap;gap:40px;align-items:flex-start;padding:30px 0;">
    <!-- Product Image -->
    <div style="flex:1 1 480px;min-width:300px;">
        <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" 
             alt="<?php echo htmlspecialchars($product['name']); ?>" 
             style="width:100%;border-radius:14px;object-fit:cover;box-shadow:0 4px 14px rgba(0,0,0,0.4);">
    </div>

    <!-- Product Info -->
    <div style="flex:1 1 400px;min-width:280px;">
        <h1 style="color:#fff;margin-bottom:10px;"><?php echo htmlspecialchars($product['name']); ?></h1>

        <?php if (!empty($product['category'])): ?>
            <div style="display:inline-block;background:#0f0f10;color:var(--gold);padding:6px 10px;border-radius:6px;margin-bottom:10px;font-weight:600;">
                <?php echo htmlspecialchars($product['category']); ?>
            </div>
        <?php endif; ?>

        <div style="margin-top:10px;font-size:22px;font-weight:700;color:var(--gold);">
            Ksh <?php echo number_format($product['price'], 2); ?>
        </div>

        <!-- Static Stars -->
        <div style="margin:10px 0;font-size:18px;color:gold;">★★★★★</div>

        <p style="color:var(--muted);line-height:1.6;"><?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description available.')); ?></p>

        <!-- Add to Cart -->
        <form method="POST" action="cart.php" style="margin-top:18px; display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
            <input type="hidden" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
            <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">

            <label style="color:var(--muted);">Quantity:</label>
            <div style="display:inline-flex; align-items:center; gap:6px;">
                <button type="button" class="qty-minus" style="padding:6px 10px;background:#555;border:none;color:#fff;border-radius:4px;cursor:pointer;">-</button>
                <input type="number" name="quantity" value="1" min="1" max="99"
                       style="width:70px;padding:8px;border-radius:6px;border:1px solid #333;background:#0b0b0b;color:#fff;text-align:center;">
                <button type="button" class="qty-plus" style="padding:6px 10px;background:#555;border:none;color:#fff;border-radius:4px;cursor:pointer;">+</button>
            </div>

            <input type="hidden" name="add_to_cart" value="1">
            <button type="submit" class="button" 
                    style="background:var(--gold); color:#111; font-weight:800; padding:10px 16px; border-radius:8px;">
                Add to Cart
            </button>
        </form>
    </div>
</section>

<!-- RELATED PRODUCTS -->
<?php if (!empty($related)): ?>
<section class="container" style="margin-top:40px;padding-bottom:60px;">
    <h2 style="color:var(--gold);margin-bottom:16px;">You May Also Like</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px;">
        <?php foreach ($related as $r): ?>
            <div style="
                background:#0f0f10;
                border-radius:14px;
                overflow:hidden;
                transition:transform 0.2s ease, box-shadow 0.2s ease;
            " 
            onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.4)'"
            onmouseout="this.style.transform='';this.style.boxShadow=''">

                <img src="../assets/images/<?php echo htmlspecialchars($r['image']); ?>" 
                     alt="<?php echo htmlspecialchars($r['name']); ?>" 
                     style="width:100%;height:180px;object-fit:cover;">

                <div style="padding:14px;">
                    <h4 style="color:#fff;margin-bottom:6px;font-size:16px;"><?php echo htmlspecialchars($r['name']); ?></h4>
                    <div style="color:var(--gold);font-weight:bold;">Ksh <?php echo number_format($r['price'], 2); ?></div>
                    <div style="color:gold;margin:6px 0;">★★★★★</div>
                    <a href="product.php?id=<?php echo $r['id']; ?>" 
                       class="button" 
                       style="display:inline-block;padding:6px 12px;">
                       View
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Quantity Buttons Script -->
<script>
document.addEventListener('click', function(e){
    if (e.target.matches('.qty-plus')) {
        const input = e.target.parentElement.querySelector('input[type="number"]');
        if (input) input.value = Math.min(99, Math.max(1, parseInt(input.value || 1) + 1));
    }
    if (e.target.matches('.qty-minus')) {
        const input = e.target.parentElement.querySelector('input[type="number"]');
        if (input) input.value = Math.max(1, parseInt(input.value || 1) - 1);
    }
});
</script>

<?php include '../includes/footer.php'; ?>
