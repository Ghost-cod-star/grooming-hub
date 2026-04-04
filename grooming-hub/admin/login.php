<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

$error = '';

// Check if already logged in as admin
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: index.php");
    exit;
}

// Show timeout message
if (isset($_GET['timeout'])) {
    $error = "Session expired. Please login again.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // For school project: Simple hardcoded admin
    // Default credentials: username = "admin", password = "password"
    $admin_username = 'admin';
    $admin_password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
    
    // In production: Query from database
    // $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
    // $stmt->execute([$username]);
    // $admin = $stmt->fetch();
    
    if ($username === $admin_username && password_verify($password, $admin_password_hash)) {
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        $_SESSION['admin'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['last_activity'] = time();
        
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<section class="page">
    <h2>Admin Login</h2>
    
    <?php if ($error): ?>
        <p style="color:#ff5555; background:#4d1a1a; padding:12px; border-radius:6px; text-align:center; margin-bottom:15px;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>
    
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required autofocus>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>
    
    <div style="background:#1a1a1a; padding:15px; border-radius:8px; margin-top:20px;">
        <p style="text-align:center; color:#888; font-size:14px; margin-bottom:5px;">
            <strong>Default Login Credentials:</strong>
        </p>
        <p style="text-align:center; color:#ccc;">
            Username: <code style="background:#0b0b0b; padding:4px 8px; border-radius:4px;">admin</code><br>
            Password: <code style="background:#0b0b0b; padding:4px 8px; border-radius:4px;">password</code>
        </p>
        <p style="text-align:center; color:#ff9800; font-size:12px; margin-top:10px;">
            ⚠️ Change these credentials in production!
        </p>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
