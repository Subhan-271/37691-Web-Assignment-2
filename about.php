<?php require_once 'includes/header.php'; ?>

<div class="page-header">
    <h2>About JobBoard</h2>
    <p>Learn more about our platform and mission</p>
</div>

<div class="about-section fade-in">
    <h2>&#127919; What is JobBoard?</h2>
    <p>JobBoard is a modern, dynamic web-based job listing platform that connects employers and job seekers across Pakistan. Employers can post job openings and manage applications, while job seekers can browse, search, and apply for jobs with ease.</p>
    <p>This project was developed as part of <strong>CSC 337 &mdash; Web Programming Languages</strong> at Iqra University Islamabad, demonstrating the use of PHP, MySQL, and modern frontend techniques.</p>
</div>

<div class="about-section fade-in">
    <h2>&#10024; Key Features</h2>
    <div class="jobs-grid" style="grid-template-columns:repeat(auto-fill,minmax(260px,1fr));">
        <?php
        $features = [
            ['&#128269;','Smart Search','Search jobs by title, company, or location with instant keyword filters.'],
            ['&#128100;','User Accounts','Dedicated portals for Job Seekers and Employers with role-based access.'],
            ['&#128203;','Easy Apply','Submit your cover letter and resume directly with one-click application.'],
            ['&#127891;','Admin Panel','Full management dashboard for jobs, users, applications, and messages.'],
            ['&#128202;','Pagination','Browse hundreds of listings smoothly with smart pagination.'],
            ['&#128274;','Secure Auth','Passwords hashed with bcrypt. Session-based login system.'],
        ];
        foreach ($features as [$icon, $title, $desc]):
        ?>
        <div class="job-card" style="border-top:none;">
            <div class="job-card-logo"><?php echo $icon; ?></div>
            <h3><?php echo $title; ?></h3>
            <div class="desc"><?php echo $desc; ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="about-section fade-in">
    <h2>&#128101; Project Team</h2>
    <div class="team-grid">
        <div class="team-card">
            <div class="avatar">A</div>
            <h4>Abdullah</h4>
            <p>Roll No: 37891</p>
            <p>CSC 337 &mdash; Web Programming</p>
            <p style="margin-top:6px;"><span class="badge badge-blue">Lead Developer</span></p>
        </div>
    </div>
</div>

<div class="about-section fade-in">
    <h2>&#128296; Technology Stack</h2>
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:8px;">
        <?php foreach (['PHP 8+','MySQL','HTML5','CSS3 (Custom)','JavaScript','Apache / XAMPP','Inter Font'] as $t): ?>
            <span class="badge badge-blue" style="font-size:.88rem;padding:7px 14px;"><?php echo $t; ?></span>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
