<?php
include '../includes/db.php';
include '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Security token validation failed');
    }
    
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (!$email) {
        $error = "Invalid email format";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$name, $email, $hashed_password]);
            $success = "Registration successful! You can now <a href='login.php'>login</a>.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                $error = "Email already exists!";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<section class="page">
    <h2>Create Account</h2>

    <?php if ($error): ?>
        <p style="color:red; text-align:center;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p style="color:green; text-align:center;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label>Full Name</label>
        <input type="text" name="name" required maxlength="100">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password (min 6 characters)</label>
        <input type="password" name="password" required minlength="6">
        
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required minlength="6">

        <button type="submit">Register</button>
    </form>

    <p style="text-align:center; margin-top:10px;">
        Already have an account? <a href="login.php">Login</a>
    </p>
</section>

<?php include '../includes/footer.php'; ?>
