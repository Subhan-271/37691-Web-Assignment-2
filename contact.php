<?php
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name'] ?? '');
    $email   = sanitize($_POST['email'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    $errors = [];
    if (!$name)  $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (!$message) $errors[] = 'Message is required.';

    if (!$errors) {
        $ins = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?,?,?)");
        $ins->bind_param('sss', $name, $email, $message);
        $ins->execute();
        flashMessage('Message sent! We will get back to you soon.','success');
        redirect('contact.php');
    }
}
?>

<div class="page-header">
    <h2>Contact Us</h2>
    <p>Have a question? We'd love to hear from you.</p>
</div>

<div class="contact-grid">
    <div class="form-card" style="max-width:100%;margin:0;">
        <h2>Send a Message</h2>
        <p class="form-subtitle">Fill out the form and we'll respond within 24 hours.</p>
        <?php if (!empty($errors)) foreach ($errors as $e): ?>
            <div class="alert alert-danger">&#9888; <?php echo $e; ?></div>
        <?php endforeach; ?>
        <form method="POST">
            <div class="form-group">
                <label>Your Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea name="message" rows="6" placeholder="Write your message here..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">&#128228; Send Message</button>
        </form>
    </div>

    <div class="contact-info">
        <div class="contact-item">
            <div class="ci-icon">&#128231;</div>
            <div>
                <h4>Email Us</h4>
                <p>info@jobboard.com</p>
                <p>support@jobboard.com</p>
            </div>
        </div>
        <div class="contact-item">
            <div class="ci-icon">&#128205;</div>
            <div>
                <h4>Our Address</h4>
                <p>Iqra University, H-9<br>Islamabad, Pakistan</p>
            </div>
        </div>
        <div class="contact-item">
            <div class="ci-icon">&#128222;</div>
            <div>
                <h4>Phone</h4>
                <p>+92-51-1234567</p>
            </div>
        </div>
        <div class="contact-item">
            <div class="ci-icon">&#128336;</div>
            <div>
                <h4>Working Hours</h4>
                <p>Monday &ndash; Friday<br>9:00 AM &ndash; 6:00 PM PKT</p>
            </div>
        </div>
        <div class="about-section" style="margin:0;padding:22px;">
            <h2 style="font-size:1rem;margin-bottom:12px;">Quick Links</h2>
            <p><a href="jobs.php">&#8594; Browse all jobs</a></p>
            <p style="margin-top:8px;"><a href="register.php">&#8594; Create an account</a></p>
            <p style="margin-top:8px;"><a href="about.php">&#8594; Learn about us</a></p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
