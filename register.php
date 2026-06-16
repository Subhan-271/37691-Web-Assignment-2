<?php
require_once 'includes/header.php';
if (isLoggedIn()) redirect('index.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $role  = in_array($_POST['role'] ?? '', ['seeker','employer']) ? $_POST['role'] : 'seeker';

    if (!$name)  $errors[] = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email address required.';
    if (strlen($pass) < 6) $errors[] = 'Password must be at least 6 characters.';

    if (!$errors) {
        $chk = $conn->prepare("SELECT id FROM users WHERE email=?");
        $chk->bind_param('s', $email);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) {
            $errors[] = 'This email is already registered.';
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $ins  = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?,?,?,?)");
            $ins->bind_param('ssss', $name, $email, $hash, $role);
            $ins->execute();
            flashMessage('Registration successful! Please login.','success');
            redirect('login.php');
        }
    }
}
?>

<div style="max-width:520px;margin:20px auto;">
    <div class="form-card">
        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:2.8rem;margin-bottom:12px;">&#128100;</div>
            <h2>Create Account</h2>
            <p class="form-subtitle">Join JobBoard for free today</p>
        </div>

        <?php foreach ($errors as $e): ?>
            <div class="alert alert-danger">&#9888; <?php echo $e; ?></div>
        <?php endforeach; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" placeholder="Your full name" required autofocus>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimum 6 characters" required>
            </div>
            <div class="form-group">
                <label>I am a&hellip;</label>
                <select name="role">
                    <option value="seeker" <?php echo ($_POST['role'] ?? '') === 'seeker' ? 'selected' : ''; ?>>&#128269; Job Seeker &mdash; I'm looking for work</option>
                    <option value="employer" <?php echo ($_POST['role'] ?? '') === 'employer' ? 'selected' : ''; ?>>&#127970; Employer &mdash; I want to hire</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:13px;font-size:.97rem;">Create Account &rarr;</button>
        </form>

        <p style="text-align:center;margin-top:20px;color:var(--text-muted);font-size:.9rem;">
            Already have an account? <a href="login.php"><strong>Login here</strong></a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
