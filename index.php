<?php
require_once 'includes/header.php';

$result = $conn->query("SELECT j.*, u.name as poster FROM jobs j JOIN users u ON j.user_id = u.id WHERE j.status = 'active' ORDER BY j.created_at DESC LIMIT 6");

$totalJobs  = $conn->query("SELECT COUNT(*) as c FROM jobs WHERE status='active'")->fetch_assoc()['c'];
$totalUsers = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='seeker'")->fetch_assoc()['c'];
$totalApps  = $conn->query("SELECT COUNT(*) as c FROM applications")->fetch_assoc()['c'];
?>

<!-- HERO -->
<div class="hero">
    <div class="hero-badge">&#127775; #1 Job Portal in Pakistan</div>
    <h1>Find Your <span>Dream Job</span><br>Today</h1>
    <p>Thousands of job listings. One platform. Connect with top employers and start your career journey now.</p>
    <div class="hero-search">
        <form action="jobs.php" method="GET" style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;width:100%;max-width:640px;">
            <div class="hero-search-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="search" placeholder="Search jobs, companies, locations...">
            </div>
            <button type="submit" class="btn btn-primary btn-lg">Search Jobs</button>
        </form>
    </div>
    <div class="hero-tags">
        Popular: <a href="jobs.php?search=developer">Developer</a>
        <a href="jobs.php?search=designer">Designer</a>
        <a href="jobs.php?search=manager">Manager</a>
        <a href="jobs.php?type=Remote">Remote</a>
        <a href="jobs.php?type=Internship">Internship</a>
    </div>
</div>

<!-- STATS -->
<div class="stats-row fade-in">
    <div class="stat-card">
        <div class="stat-icon">&#128188;</div>
        <h2><?php echo number_format($totalJobs); ?></h2>
        <p>Active Jobs</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">&#128100;</div>
        <h2><?php echo number_format($totalUsers); ?></h2>
        <p>Job Seekers</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">&#128203;</div>
        <h2><?php echo number_format($totalApps); ?></h2>
        <p>Applications</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">&#127970;</div>
        <h2>50+</h2>
        <p>Companies</p>
    </div>
</div>

<!-- JOB CATEGORIES -->
<div class="fade-in">
    <div class="section-header">
        <div>
            <h2 class="section-title">Browse by Category</h2>
            <p class="section-subtitle">Explore opportunities across industries</p>
        </div>
        <a href="jobs.php" class="btn btn-outline">View All &rarr;</a>
    </div>
    <div class="categories-grid">
        <a href="jobs.php?search=IT" class="cat-card">
            <div class="cat-icon">&#128187;</div>
            <h4>Information Technology</h4>
            <div class="cat-count">Software, Dev, Data</div>
        </a>
        <a href="jobs.php?search=design" class="cat-card">
            <div class="cat-icon">&#127912;</div>
            <h4>Design &amp; Creative</h4>
            <div class="cat-count">UI/UX, Graphics, Media</div>
        </a>
        <a href="jobs.php?search=marketing" class="cat-card">
            <div class="cat-icon">&#128202;</div>
            <h4>Marketing &amp; Sales</h4>
            <div class="cat-count">Digital, SEO, Growth</div>
        </a>
        <a href="jobs.php?search=finance" class="cat-card">
            <div class="cat-icon">&#128184;</div>
            <h4>Finance &amp; Banking</h4>
            <div class="cat-count">Accounting, Audit, FinTech</div>
        </a>
        <a href="jobs.php?search=education" class="cat-card">
            <div class="cat-icon">&#127891;</div>
            <h4>Education</h4>
            <div class="cat-count">Teaching, Training, Tutor</div>
        </a>
        <a href="jobs.php?search=healthcare" class="cat-card">
            <div class="cat-icon">&#9877;</div>
            <h4>Healthcare</h4>
            <div class="cat-count">Medical, Pharmacy, Nursing</div>
        </a>
        <a href="jobs.php?search=engineering" class="cat-card">
            <div class="cat-icon">&#9881;</div>
            <h4>Engineering</h4>
            <div class="cat-count">Civil, Electrical, Mechanical</div>
        </a>
        <a href="jobs.php?type=Remote" class="cat-card">
            <div class="cat-icon">&#127968;</div>
            <h4>Remote Work</h4>
            <div class="cat-count">Work from Anywhere</div>
        </a>
    </div>
</div>

<!-- LATEST JOBS -->
<div class="fade-in">
    <div class="section-header">
        <div>
            <h2 class="section-title">Latest Job Openings</h2>
            <p class="section-subtitle">Fresh opportunities just posted</p>
        </div>
        <a href="jobs.php" class="btn btn-outline">See All Jobs &rarr;</a>
    </div>

    <div class="jobs-grid">
    <?php
    $icons = ['&#128187;','&#127912;','&#128202;','&#127891;','&#9881;','&#128184;'];
    $i = 0;
    while ($job = $result->fetch_assoc()):
        $typeClass = ['Full-time'=>'badge-blue','Part-time'=>'badge-orange','Remote'=>'badge-green','Internship'=>'badge-gray'][$job['type']] ?? 'badge-gray';
    ?>
        <div class="job-card">
            <div class="job-card-logo"><?php echo $icons[$i++ % 6]; ?></div>
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
</div>

<!-- HOW IT WORKS -->
<div class="fade-in" style="margin-top:55px;">
    <div class="section-header">
        <div>
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Three simple steps to your next opportunity</p>
        </div>
    </div>
    <div class="steps-grid">
        <div class="step-card">
            <div class="step-num">1</div>
            <div class="step-icon">&#128100;</div>
            <h3>Create Your Account</h3>
            <p>Sign up as a Job Seeker or Employer in under a minute. It's free and easy.</p>
        </div>
        <div class="step-card">
            <div class="step-num">2</div>
            <div class="step-icon">&#128269;</div>
            <h3>Search &amp; Filter Jobs</h3>
            <p>Browse thousands of listings and filter by type, location, or keyword to find the perfect fit.</p>
        </div>
        <div class="step-card">
            <div class="step-num">3</div>
            <div class="step-icon">&#128203;</div>
            <h3>Apply with One Click</h3>
            <p>Submit your cover letter and resume directly to the employer and track your application.</p>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="cta-section fade-in">
    <h2>Ready to Find Your Next Job?</h2>
    <p>Join thousands of professionals who found their dream careers through JobBoard.</p>
    <div class="cta-btns">
        <a href="register.php" class="btn btn-primary btn-lg">Get Started Free</a>
        <a href="jobs.php" class="btn btn-ghost btn-lg">Browse Jobs</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
