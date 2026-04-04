<?php
include '../includes/db.php';
include '../includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Security token validation failed');
    }
    
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));
    
    if (!$email) {
        $error = "Invalid email address";
    } elseif (strlen($message) < 10) {
        $error = "Message must be at least 10 characters";
    } else {
        try {
            // Create table if it doesn't exist
            $conn->exec("CREATE TABLE IF NOT EXISTS contact_messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100),
                email VARCHAR(100),
                message TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            
            // Save message to database
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
            
            $success = "✅ Thank you for contacting us! We'll respond within 24 hours.";
            
            // Clear form
            $_POST = [];
            
        } catch (Exception $e) {
            error_log("Contact form error: " . $e->getMessage());
            $error = "Failed to send message. Please try again.";
        }
    }
}
?>

<section class="page">
    <h2>Contact Us</h2>
    <p>Have questions or custom orders? Reach us below:</p>
    
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
        
        <label>Your Name:</label>
        <input type="text" name="name" required maxlength="100" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">

        <label>Your Email:</label>
        <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

        <label>Your Message:</label>
        <textarea name="message" required rows="6" minlength="10"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>

        <button type="submit">Send Message</button>
    </form>

    <!-- Contact Info -->
    <div style="margin-top:40px; padding-top:30px; border-top:1px solid #333;">
        <h3 style="color:var(--gold); margin-bottom:15px;">Other Ways to Reach Us</h3>
        <p style="line-height:2; color:var(--muted);">
            📧 Email: support@groominghub.co.ke<br>
            📞 Phone: +254 700 000 000<br>
            📍 Location: Nairobi CBD, Kenya<br>
            🕒 Business Hours: Mon-Sat, 9AM - 6PM
        </p>
    </div>

    <!-- Location Map Section -->
    <h3 style="margin-top:30px; color:var(--gold);">Our Location</h3>
    <p>You can visit us or check our delivery coverage:</p>
    
    <div class="map" style="margin-top:20px; border-radius:8px; overflow:hidden;">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.828848197555!2d36.81667091475826!3d-1.2863899359821376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f10d0e3fb5331%3A0x1e3c3fa16abcc0d9!2sNairobi%20CBD!5e0!3m2!1sen!2ske!4v000000000"
            width="100%" 
            height="300" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
