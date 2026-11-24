<?php require_once 'auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAREER MITRA</title>

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <script src="toggle.js" defer></script>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar">
    <a href="index.php" class="logo">
        <img src="icon.jpg" alt="Career Mitra logo" class="site-logo-image">
        <span class="site-logo-text">CAREER MITRA</span>
    </a>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="search.php">Find Jobs</a>
        <a href="employeer.php">Post Jobs</a>
        <a href="admin/index.php">Admin</a>

        <?php
        $user = get_logged_in_user();
        if ($user) {
            echo '<span class="user-name">Hi, ' . htmlspecialchars($user['name']) . '</span>';
            echo '<a href="auth.php?logout=1" class="btn-primary">Logout</a>';
        } else {
            echo '<a href="login.php" class="btn-outline">Sign In</a>';
            echo '<a href="reg.php" class="btn-primary">Register</a>';
        }
        ?>
    </div>
</nav>

<main class="home-wrapper">
    <section class="home-hero">
        <div>
            <span class="home-kicker">
                <img src="icon.jpg" alt="Career Mitra icon" class="home-kicker-logo">
                Career growth for job seekers and employers
            </span>
            <h1>Build Your Career or Hire Talent</h1>

            <p>
                CAREER MITRA brings job seekers and employers together in one clean, practical workspace. Explore
                opportunities, build your profile, track applications, publish vacancies, and manage hiring without
                extra complexity.
            </p>

            <div class="home-actions">
                <a href="jobseeker.php" class="btn-primary">Find Jobs</a>
                <a href="employeer.php" class="btn-outline">Post Jobs</a>
            </div>
        </div>

        <div class="home-hero-art">
            <img src="assets/bg.jpg" alt="Illustration representing career growth and job opportunities">
        </div>
    </section>

    <section class="home-section">
        <div class="home-section-intro">
            <h2>Choose Your Path</h2>
            <p>
                Whether you are searching for your next role or hiring for your growing team, the platform gives you
                the right tools in the right place.
            </p>
        </div>

        <div class="home-feature-grid">
            <article class="home-feature-card">
                <div class="home-feature-media">
                    <img src="assets/job.jpg" alt="Job search illustration for job seekers">
                </div>
                <div class="home-feature-body">
                    <h3>Job Seekers</h3>
                    <p>
                        Search current openings, build a stronger profile, and move from browsing to applying in just a
                        few steps.
                    </p>
                    <p>
                        From internships to full-time roles, CAREER MITRA helps you discover opportunities that match
                        your skills and goals.
                    </p>

                    <ul class="home-feature-list">
                        <li>Easy job search</li>
                        <li>One-click apply flow</li>
                        <li>Resume and profile support</li>
                        <li>Application tracking</li>
                    </ul>

                    <a href="jobseeker.php" class="btn-primary">Explore Jobs</a>
                </div>
            </article>

            <article class="home-feature-card">
                <div class="home-feature-media">
                    <img src="assets/hiring.jpg" alt="Hiring banner for employers and recruiters">
                </div>
                <div class="home-feature-body">
                    <h3>Employers</h3>
                    <p>
                        Publish open positions, present your company clearly, and review applicants from a single
                        dashboard.
                    </p>
                    <p>
                        The hiring flow is built to save time, reduce friction, and help teams connect with stronger
                        candidates faster.
                    </p>

                    <ul class="home-feature-list">
                        <li>Post jobs quickly</li>
                        <li>Manage applicants easily</li>
                        <li>Keep hiring organized</li>
                        <li>Move faster on talent</li>
                    </ul>

                    <a href="employeer.php" class="btn-primary">Start Hiring</a>
                </div>
            </article>
        </div>
    </section>

    <section class="home-section">
        <div class="home-story-banner">
            <div class="home-story-copy">
                <h2>Why Choose CAREER MITRA?</h2>
                <p>
                    The platform is focused on clarity, speed, and confidence. Job seekers can act faster, while
                    employers can keep hiring work organized without heavy tools or confusing steps.
                </p>

                <div class="home-highlights">
                    <div class="home-highlight">Clean and simple navigation</div>
                    <div class="home-highlight">Useful pages for resumes and applications</div>
                    <div class="home-highlight">Dedicated spaces for companies and candidates</div>
                    <div class="home-highlight">A consistent dashboard-driven experience</div>
                </div>
            </div>

            <div class="home-story-media">
                <img src="assets/WhatsApp Image 2026-04-29 at 16.38.07.jpeg" alt="Professional workplace scene for the Career Mitra platform">
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="home-cta-panel">
            <h2>Start Your Journey Today</h2>
            <p>
                Join CAREER MITRA to discover better opportunities, showcase your talent, or build a stronger hiring
                pipeline for your company.
            </p>

            <div class="home-actions" style="justify-content:center; margin-top:0;">
                <a href="reg.php" class="btn-primary">Get Started</a>
                <a href="login.php" class="btn-outline">Login</a>
            </div>
        </div>
    </section>
</main>

<!-- ================= FOOTER ================= -->
<footer style="text-align:center; padding:20px; background:#f1f1f1;">
    <p>&copy; <?php echo date("Y"); ?> CAREER MITRA. All rights reserved.</p>
</footer>

</body>
</html>
