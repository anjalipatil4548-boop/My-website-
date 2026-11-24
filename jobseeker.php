<?php
require_once 'auth.php';
require_login('jobseeker');
include 'header.php';
include 'common_sidebar.php';
?>

<div class="page-container">
    <div class="page-header" style="background-image: url('assets/jobseeker_bg.png');">
        <div class="page-header-overlay"></div>
        <h1 class="text-white text-5xl font-bold mb-2">👨‍💼 Job Seeker Dashboard</h1>
        <p class="text-secondary text-xl">Manage your profile, search for opportunities, and track your applications.</p>
    </div>

    <div class="stats-grid">
        <div class="glass-card cursor-pointer transition" onclick="window.location.href='profile.php'">
            <div class="text-5xl mb-3">👤</div>
            <h2 class="text-white text-xl font-bold mb-2">Profile</h2>
            <p class="text-secondary">Create & edit your profile</p>
        </div>

        <div class="glass-card cursor-pointer transition" onclick="window.location.href='resume.php'">
            <div class="text-5xl mb-3">📄</div>
            <h2 class="text-white text-xl font-bold mb-2">Resume</h2>
            <p class="text-secondary">Upload or create resume</p>
        </div>

        <div class="glass-card cursor-pointer transition" onclick="window.location.href='search.php'">
            <div class="text-5xl mb-3">🔍</div>
            <h2 class="text-white text-xl font-bold mb-2">Search Jobs</h2>
            <p class="text-secondary">Find jobs by filters</p>
        </div>

        <div class="glass-card cursor-pointer transition" onclick="window.location.href='job.php'">
            <div class="text-5xl mb-3">💼</div>
            <h2 class="text-white text-xl font-bold mb-2">Jobs</h2>
            <p class="text-secondary">Browse available jobs</p>
        </div>

        <div class="glass-card cursor-pointer transition" onclick="window.location.href='applyjob.php'">
            <div class="text-5xl mb-3">📨</div>
            <h2 class="text-white text-xl font-bold mb-2">Apply</h2>
            <p class="text-secondary">Apply for selected jobs</p>
        </div>

        <div class="glass-card cursor-pointer transition" onclick="window.location.href='dashboard.php'">
            <div class="text-5xl mb-3">📊</div>
            <h2 class="text-white text-xl font-bold mb-2">Dashboard</h2>
            <p class="text-secondary">Track your applications</p>
        </div>
</div>

<?php include 'footer.php'; ?>
