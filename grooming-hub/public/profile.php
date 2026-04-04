<?php
include '../includes/db.php';
include '../includes/header.php';

// Block access if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

// Fetch current user details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$userData = $stmt->fetch();

$success = "";
$error = "";

// Update Profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Security token validation failed');
    }
    
    $newName = htmlspecialchars(trim($_POST['name']));
    $newEmail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
    if (!$newEmail) {
        $error = "Invalid email format";
    } else {
        try {
            // Update database
            $updateStmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $updateStmt->execute([$newName, $newEmail, $userId]);

            // Update session
            $_SESSION['user']['name'] = $newName;
            $_SESSION['user']['email'] = $newEmail;

            $success = "✅ Profile updated successfully!";
            
            // Refresh user data
            $userData['name'] = $newName;
            $userData['email'] = $newEmail;
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Email already in use by another account";
            } else {
                $error = "Update failed. Please try again.";
            }
        }
    }
}
?>

<section class="page">
    <h2>My Profile</h2>

    <?php if ($success): ?>
        <p style="color:green; background:#1a4d1a; padding:12px; border-radius:6px; margin-bottom:15px;">
            <?php echo $success; ?>
        </p>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <p style="color:#ff5555; background:#4d1a1a; padding:12px; border-radius:6px; margin-bottom:15px;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label>Full Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" required maxlength="100">

        <label>Email Address:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
        
        <p style="color:#888; font-size:14px; margin-top:-5px;">
            Note: Changing your email will update your login credentials
        </p>

        <button type="submit">Update Profile</button>
    </form>
    
    <div style="margin-top:30px; padding-top:20px; border-top:1px solid #333;">
        <h3 style="margin-bottom:10px;">Account Information</h3>
        <p style="color:#888;">Member since: <?php echo date('F Y', strtotime($userData['created_at'])); ?></p>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
