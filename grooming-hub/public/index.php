<?php
include '../includes/db.php';
include '../includes/header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-copy">
            <h2>Built for Men of Discipline.</h2>
            <p class="lead">Premium shaving, beard care and skincare essentials – crafted to elevate your routine.</p>
            <a href="#featured" class="button cta">Shop Featured</a>
        </div>
        <div class="hero-image">
            <img src="../assets/images/hero_dark.jpg" alt="The Grooming Hub">
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section id="featured" class="featured-section">
    <div class="container">
        <h2 class="section-title">Featured Collection</h2>

        <div class="featured-grid">
            <?php
            // Fetch featured products from database
            $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 8");
            $products = $stmt->fetchAll();
            
            // If no products in database, show defaults
            if (empty($products)) {
                $products = [
                    ['id'=>1,'name'=>'Royal Beard Balm','image'=>'Royal_Beard_Balm.jpg','price'=>'750','description'=>'Smooth finishing balm for a clean, refined look.'],
                    ['id'=>2,'name'=>'Precision Metal Razor','image'=>'Precision_Metal_Razor.jpg','price'=>'2990','description'=>'Engineered for unmatched precision and control.'],
                    ['id'=>3,'name'=>'Midnight Shave Cream','image'=>'Midnight_Shave_Cream.jpg','price'=>'550','description'=>'A rich, luxurious lather for a flawless shave.'],
                    ['id'=>4,'name'=>'Ironclad Aftershave','image'=>'Ironclad_Aftershave.jpg','price'=>'650','description'=>'Instant cooling and soothing after every shave.'],
                    ['id'=>5,'name'=>'Alpha Beard Oil','image'=>'Alpha_Beard_Oil.jpg','price'=>'600','description'=>'Adds shine, strength, and confidence to every beard.'],
                    ['id'=>6,'name'=>'Razor Kit','image'=>'Razor_Kit.jpg','price'=>'2200','description'=>'Complete set for a professional grooming experience.'],
                    ['id'=>7,'name'=>'Classic Beard Oil','image'=>'beard_oil.jpg','price'=>'580','description'=>'Nourishing oil for healthy, manageable beards.'],
                    ['id'=>8,'name'=>'Premium Shaving Cream','image'=>'shaving_cream.jpg','price'=>'500','description'=>'Smooth, hydrating cream for the perfect shave.']
                ];
            }

            foreach ($products as $p) {
                echo "<article class='featured-card'>";
                echo "  <div class='card-media'><img src=\"../assets/images/{$p['image']}\" alt=\"" . htmlspecialchars($p['name']) . "\"></div>";
                echo "  <div class='card-body'>";
                echo "      <h3 class='card-title'>" . htmlspecialchars($p['name']) . "</h3>";
                echo "      <p class='card-desc'>" . htmlspecialchars($p['description']) . "</p>";
                echo "      <div class='card-meta'><span class='price'>Ksh " . number_format($p['price'], 2) . "</span></div>";
                echo "      <div class='star-rating'>★★★★★</div>";
                echo "      <a href='product.php?id={$p['id']}' class='button'>View Product</a>";
                echo "  </div>";
                echo "</article>";
            }
            ?>
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section class="categories">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="category-grid">
            <a class="cat" href="products.php?category=Shaving">
                <img src="../assets/images/Precision_Metal_Razor.jpg" alt="Shaving">
                <span>Shaving</span>
            </a>
            <a class="cat" href="products.php?category=Beard">
                <img src="../assets/images/Alpha_Beard_Oil.jpg" alt="Beard Care">
                <span>Beard Care</span>
            </a>
            <a class="cat" href="products.php?category=Skin">
                <img src="../assets/images/Midnight_Shave_Cream.jpg" alt="Skincare">
                <span>Skincare</span>
            </a>
            <a class="cat" href="products.php?category=Hair">
                <img src="../assets/images/Royal_Beard_Balm.jpg" alt="Hair">
                <span>Hair Styling</span>
            </a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
