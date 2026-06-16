<?php
require_once 'includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { redirect('jobs.php'); }

$stmt = $conn->prepare("SELECT j.*, u.name as poster, u.email as poster_email FROM jobs j JOIN users u ON j.user_id = u.id WHERE j.id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
if (!$job) { flashMessage('Job not found.','danger'); redirect('jobs.php'); }

$applied = false;
if (isLoggedIn()) {
    $chk = $conn->prepare("SELECT id FROM applications WHERE job_id=? AND user_id=?");
    $chk->bind_param('ii', $id, $_SESSION['user_id']);
    $chk->execute();
    $applied = $chk->get_result()->num_rows > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn() && !$applied) {
    $cover  = sanitize($_POST['cover_letter'] ?? '');
    $resume = '';

    if (!empty($_FILES['resume']['name'])) {
        $ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['pdf','doc','docx'])) {
            $fname = uniqid('cv_') . '.' . $ext;
            move_uploaded_file($_FILES['resume']['tmp_name'], "uploads/resumes/$fname");
            $resume = $fname;
        } else {
            flashMessage('Only PDF, DOC, DOCX files allowed.','danger');
        }
    }

    if (empty($_SESSION['flash'])) {
        $ins = $conn->prepare("INSERT INTO applications (job_id, user_id, cover_letter, resume_file) VALUES (?,?,?,?)");
        $ins->bind_param('iiss', $id, $_SESSION['user_id'], $cover, $resume);
        $ins->execute();
        flashMessage('Application submitted successfully!','success');
        $applied = true;
    }
}

$typeClass = ['Full-time'=>'badge-blue','Part-time'=>'badge-orange','Remote'=>'badge-green','Internship'=>'badge-gray'][$job['type']] ?? 'badge-gray';
?>

<div style="margin-bottom:20px;">
    <a href="jobs.php" class="btn btn-outline btn-sm">&larr; Back to Jobs</a>
</div>

<div class="job-detail">
    <div class="job-detail-header">
        <div class="job-detail-logo">&#128188;</div>
        <div>
            <h1><?php echo htmlspecialchars($job['title']); ?></h1>
            <div class="company-big">&#127970; <?php echo htmlspecialchars($job['company']); ?></div>
            <div class="meta-row">
                <span class="badge <?php echo $typeClass; ?>"><?php echo $job['type']; ?></span>
                <span class="badge badge-gray">&#128205; <?php echo htmlspecialchars($job['location']); ?></span>
                <span class="badge badge-green">&#128176; <?php echo htmlspecialchars($job['salary'] ?: 'Negotiable'); ?></span>
                <span class="badge badge-gray">&#128197; <?php echo date('M d, Y', strtotime($job['created_at'])); ?></span>
            </div>
        </div>
    </div>

    <h3>&#128203; Job Description</h3>
    <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>

    <h3>&#9989; Requirements</h3>
    <p><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>

    <h3>&#128228; Apply for this Job</h3>
    <?php if (!isLoggedIn()): ?>
        <div class="alert alert-info">&#128274; Please <a href="login.php"><strong>login</strong></a> or <a href="register.php"><strong>register</strong></a> to apply for this job.</div>
    <?php elseif ($applied): ?>
        <div class="alert alert-success">&#9989; You have already applied for this job. We'll notify you of any updates.</div>
    <?php else: ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Cover Letter <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
                <textarea name="cover_letter" rows="5" placeholder="Tell the employer why you're a great fit for this role..."></textarea>
            </div>
            <div class="form-group">
                <label>Resume / CV <span style="color:var(--text-muted);font-weight:400;">(PDF, DOC, DOCX)</span></label>
                <input type="file" name="resume" accept=".pdf,.doc,.docx">
            </div>
            <button type="submit" class="btn btn-success btn-lg">&#128228; Submit Application</button>
        </form>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
