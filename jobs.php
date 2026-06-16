<?php
require_once 'includes/header.php';

$search = sanitize($_GET['search'] ?? '');
$type   = sanitize($_GET['type'] ?? '');

$perPage = 9;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

$where  = "WHERE j.status = 'active'";
$params = [];
$types  = '';

if ($search) {
    $where   .= " AND (j.title LIKE ? OR j.company LIKE ? OR j.location LIKE ?)";
    $s        = "%$search%";
    $params   = array_merge($params, [$s, $s, $s]);
    $types   .= 'sss';
}
if ($type) {
    $where   .= " AND j.type = ?";
    $params[] = $type;
    $types   .= 's';
}

$countSQL = "SELECT COUNT(*) as c FROM jobs j $where";
$stmt = $conn->prepare($countSQL);
if ($params) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$total      = $stmt->get_result()->fetch_assoc()['c'];
$totalPages = ceil($total / $perPage);

$sql   = "SELECT j.*, u.name as poster FROM jobs j JOIN users u ON j.user_id = u.id $where ORDER BY j.created_at DESC LIMIT $perPage OFFSET $offset";
$stmt2 = $conn->prepare($sql);
if ($params) { $stmt2->bind_param($types, ...$params); }
$stmt2->execute();
$jobs = $stmt2->get_result();
?>

<div class="page-header">
    <h2>Browse Jobs</h2>
    <p>Find the perfect job opportunity for you</p>
</div>

<!-- SEARCH FILTER -->
<form method="GET" class="search-bar">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="&#128269; Search job title, company, location...">
    <select name="type">
        <option value="">All Types</option>
        <?php foreach (['Full-time','Part-time','Remote','Internship'] as $t): ?>
            <option value="<?php echo $t; ?>" <?php echo $type === $t ? 'selected' : ''; ?>><?php echo $t; ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary">Filter</button>
    <?php if ($search || $type): ?>
        <a href="jobs.php" class="btn btn-outline">&#10005; Clear</a>
    <?php endif; ?>
</form>

<p style="color:var(--text-muted);margin-bottom:22px;font-size:.9rem;">
    Showing <strong><?php echo $jobs->num_rows; ?></strong> of <strong><?php echo $total; ?></strong> jobs
    <?php if ($search): ?> for "<strong><?php echo htmlspecialchars($search); ?></strong>"<?php endif; ?>
    <?php if ($type): ?> &mdash; <span class="badge badge-blue"><?php echo $type; ?></span><?php endif; ?>
</p>

<?php if ($total === 0): ?>
    <div class="no-results">
        <div class="nr-icon">&#128269;</div>
        <h3>No jobs found</h3>
        <p>Try different keywords or remove filters.</p>
        <a href="jobs.php" class="btn btn-primary" style="margin-top:18px;">Clear Filters</a>
    </div>
<?php else: ?>
<div class="jobs-grid">
<?php
$icons = ['&#128187;','&#127912;','&#128202;','&#127891;','&#9881;','&#128184;','&#128100;','&#127968;','&#128640;'];
$i = 0;
while ($job = $jobs->fetch_assoc()):
    $typeClass = ['Full-time'=>'badge-blue','Part-time'=>'badge-orange','Remote'=>'badge-green','Internship'=>'badge-gray'][$job['type']] ?? 'badge-gray';
?>
    <div class="job-card fade-in">
        <div class="job-card-logo"><?php echo $icons[$i++ % 9]; ?></div>
        <h3><?php echo htmlspecialchars($job['title']); ?></h3>
        <div class="company">&#127970; <?php echo htmlspecialchars($job['company']); ?></div>
        <div class="meta">
            <span class="badge <?php echo $typeClass; ?>"><?php echo $job['type']; ?></span>
            <span class="badge badge-gray">&#128205; <?php echo htmlspecialchars($job['location']); ?></span>
        </div>
        <div class="salary">&#128176; <?php echo htmlspecialchars($job['salary'] ?: 'Negotiable'); ?></div>
        <div class="desc"><?php echo substr(htmlspecialchars($job['description']), 0, 100); ?>...</div>
        <div class="job-card-footer">
            <a href="job.php?id=<?php echo $job['id']; ?>" class="btn btn-primary btn-sm">View Details &rarr;</a>
        </div>
    </div>
<?php endwhile; ?>
</div>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>">&laquo; Prev</a>
    <?php endif; ?>
    <?php for ($i = max(1,$page-2); $i <= min($totalPages,$page+2); $i++): ?>
        <?php if ($i == $page): ?>
            <span class="current"><?php echo $i; ?></span>
        <?php else: ?>
            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
        <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>">Next &raquo;</a>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
