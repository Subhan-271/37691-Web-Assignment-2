<?php
require_once 'includes/header.php';
if (isLoggedIn()) redirect('index.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (!$email || !$pass) {
        $errors[] = 'All fields are required.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];

            if ($user['role'] === 'admin') redirect('admin/index.php');
            else redirect('index.php');
        } else {
            $errors[] = 'Invalid email or password. Please try again.';
        }
    }
}
?>

<div style="max-width:480px;margin:20px auto;">
    <div class="form-card">
        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:2.8rem;margin-bottom:12px;">&#128274;</div>
            <h2>Welcome Back</h2>
            <p class="form-subtitle">Login to your JobBoard account</p>
        </div>

        <?php foreach ($errors as $e): ?>
            <div class="alert alert-danger">&#9888; <?php echo $e; ?></div>
        <?php endforeach; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="your@email.com" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:13px;font-size:.97rem;">Login &rarr;</button>
        </form>

        <p style="text-align:center;margin-top:20px;color:var(--text-muted);font-size:.9rem;">
            Don't have an account? <a href="register.php"><strong>Create one free</strong></a>
        </p>
        <div class="alert alert-info" style="margin-top:16px;font-size:.82rem;">
            &#128274; Demo Admin: <strong>admin@jobboard.com</strong> / <strong>password</strong>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
