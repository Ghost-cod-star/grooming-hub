<?php
include 'auth_check.php';
include '../includes/db.php';
include '../includes/header.php';

$error = '';
$success = '';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Security token validation failed');
    }
    
    // Get and sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $description = htmlspecialchars(trim($_POST['description']));
    $category = htmlspecialchars(trim($_POST['category']));
    
    // Validate price
    if ($price === false || $price <= 0) {
        $error = "Invalid price";
    } else {
        // ✅ SECURE FILE UPLOAD
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        // Check if file was uploaded
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = "File upload error. Please select an image.";
        }
        // Check file size
        elseif ($_FILES['image']['size'] > $max_size) {
            $error = "File too large. Maximum 5MB allowed.";
        }
        // Check MIME type
        elseif (!in_array($_FILES['image']['type'], $allowed_types)) {
            $error = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
        }
        else {
            // Get file extension
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            // Double-check extension
            if (!in_array($file_extension, $allowed_extensions)) {
                $error = "Invalid file extension.";
            } else {
                // Generate unique filename
                $unique_name = 'product_' . uniqid() . '.' . $file_extension;
                
                $target_dir = "../assets/images/";
                $target_file = $target_dir . $unique_name;
                
                // Move uploaded file
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    // Insert into database (store only filename!)
                    try {
                        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$name, $description, $price, $unique_name, $category]);
                        
                        $success = "✅ Product added successfully!";
                        
                        // Clear form
                        $_POST = [];
                        
                    } catch (Exception $e) {
                        // Delete uploaded file if database insert fails
                        unlink($target_file);
                        $error = "Failed to add product to database.";
                    }
                } else {
                    $error = "Failed to upload file. Check permissions.";
                }
            }
        }
    }
}
?>

<section class="page">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">Add New Product</h2>
        <a href="products.php" style="color:#888;">← Back to Products</a>
    </div>

    <?php if ($error): ?>
        <p style="color:#ff5555; background:#4d1a1a; padding:12px; border-radius:6px; margin-bottom:15px;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green; background:#1a4d1a; padding:12px; border-radius:6px; margin-bottom:15px;">
            <?php echo $success; ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label>Product Name:</label>
        <input type="text" name="name" required maxlength="100" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">

        <label>Category:</label>
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="Shaving" <?php echo (($_POST['category'] ?? '') == 'Shaving') ? 'selected' : ''; ?>>Shaving</option>
            <option value="Beard" <?php echo (($_POST['category'] ?? '') == 'Beard') ? 'selected' : ''; ?>>Beard Care</option>
            <option value="Skin" <?php echo (($_POST['category'] ?? '') == 'Skin') ? 'selected' : ''; ?>>Skincare</option>
            <option value="Hair" <?php echo (($_POST['category'] ?? '') == 'Hair') ? 'selected' : ''; ?>>Hair Styling</option>
        </select>

        <label>Description:</label>
        <textarea name="description" required maxlength="500" rows="4"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

        <label>Price (Ksh):</label>
        <input type="number" name="price" step="0.01" min="0" required value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">

        <label>Product Image (Max 5MB - JPG, PNG, GIF, WEBP only):</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp" required>
        
        <p style="color:#888; font-size:14px; margin-top:-5px;">
            Supported formats: JPG, PNG, GIF, WEBP | Maximum size: 5MB
        </p>

        <button type="submit" style="width:100%; padding:12px; font-size:16px;">Add Product</button>
    </form>
</section>

<?php include '../includes/footer.php'; ?>
