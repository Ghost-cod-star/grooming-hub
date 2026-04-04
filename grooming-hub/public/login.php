<?php
include '../includes/db.php';
include '../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Security token validation failed');
    }
    
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    
    if (!$email) {
        $error = "Invalid email format";
    } else {
        // Fetch user from database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // SECURITY: Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            // Store user session
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email']
            ];
            $_SESSION['last_activity'] = time();

            // Redirect to homepage
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>

<section class="page">
    <h2>Login</h2>

    <?php if ($error): ?>
        <p style="color:red; text-align:center;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label>Email:</label>
        <input type="email" name="email" required autofocus>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p style="text-align:center; margin-top:10px;">
        Don't have an account? <a href="register.php">Create one</a>
    </p>
</section>

<?php include '../includes/footer.php'; ?>
