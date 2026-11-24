<?php
require_once 'auth.php';
require_login('company');
include 'header.php';
include 'company_sidebar.php';
?>

<div class="page-container">
    <div class="page-header" style="background-image: url('assets/employer_bg.png');">
        <div class="page-header-overlay" style="background: linear-gradient(135deg, rgba(11,15,25,0.9) 0%, rgba(16,185,129,0.4) 100%);"></div>
        <h1 class="text-white text-5xl font-bold mb-2">🏢 Find Top Talent</h1>
        <p class="text-secondary text-xl">Post open positions, manage your company profile, and discover top talent.</p>
    </div>

    <div class="stats-grid">
      
        <div class="glass-card cursor-pointer transition" onclick="window.location.href='company.php'">
            <div class="text-5xl mb-3">💼</div>
            <h2 class="text-white text-xl font-bold mb-2">Company Profile</h2>
            <p class="text-secondary">Create & edit company details</p>
        </div>

        <div class="glass-card cursor-pointer transition" onclick="window.location.href='postjob.php'">
            <div class="text-5xl mb-3">📢</div>
            <h2 class="text-white text-xl font-bold mb-2">Post Jobs</h2>
            <p class="text-secondary">Add new job listings</p>
        </div>

        <div class="glass-card cursor-pointer transition" onclick="window.location.href='view-applicants.php'">
            <div class="text-5xl mb-3">👥</div>
            <h2 class="text-white text-xl font-bold mb-2">View Applicants</h2>
            <p class="text-secondary">See who applied</p>
        </div>

        <div class="glass-card cursor-pointer transition" onclick="window.location.href='company_dashboard.php'">
            <div class="text-5xl mb-3">📊</div>
            <h2 class="text-white text-xl font-bold mb-2">Dashboard</h2>
            <p class="text-secondary">View analytics and activity</p>
        </div>
</div>

<?php include 'footer.php'; ?>
