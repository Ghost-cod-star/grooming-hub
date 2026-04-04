<?php
include 'auth_check.php';
include '../includes/db.php';

if (isset($_GET['id']) && isset($_GET['token'])) {
    // Validate CSRF token
    session_start();
    if (!isset($_SESSION['csrf_token']) || $_GET['token'] !== $_SESSION['csrf_token']) {
        die("Invalid security token. <a href='products.php'>Go back</a>");
    }
    
    // Validate ID is numeric
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($id === false || $id <= 0) {
        die("Invalid product ID. <a href='products.php'>Go back</a>");
    }

    try {
        // Get product image before deleting
        $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        
        if ($product) {
            // Delete from database
            $deleteStmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $deleteStmt->execute([$id]);
            
            // Delete image file if exists
            $imagePath = "../assets/images/" . $product['image'];
            if (file_exists($imagePath) && is_file($imagePath)) {
                unlink($imagePath);
            }
            
            // Redirect with success message
            header("Location: products.php?deleted=success");
            exit;
        } else {
            die("Product not found. <a href='products.php'>Go back</a>");
        }
        
    } catch (Exception $e) {
        error_log("Product deletion error: " . $e->getMessage());
        die("Error deleting product. <a href='products.php'>Go back</a>");
    }
} else {
    die("Missing parameters. <a href='products.php'>Go back</a>");
}
?>
