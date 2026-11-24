<?php
/**
 * Sidebar Menu for Job Portal
 */
require_once 'auth.php';
$user = get_logged_in_user();
$role = $user['role'] ?? $_GET['role'] ?? '';
if (!$role) $role = 'jobseeker';
$is_jobseeker = ($role === 'jobseeker');
$is_company = ($role === 'company');

$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="left-sidebar" id="sidebar">
    <div class="sidebar-user">
        <?php if ($user): ?>
            <div class="flex items-center gap-3">
                <div class="user-avatar">
                    <?php echo htmlspecialchars(substr($user['name'], 0, 1)); ?>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-base m-0"><?php echo htmlspecialchars($user['name']); ?></h3>
                    <p class="text-secondary text-sm m-0"><?php echo ucfirst($role); ?> Account</p>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center">
                <div class="user-avatar mx-auto mb-2">?</div>
                <p class="text-secondary text-sm">Guest User</p>
            </div>
        <?php endif; ?>
    </div>

    <nav class="sidebar-nav">
        <!-- Main Menu -->
        <div class="nav-section">
            <ul>
                <li>
                    <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">🏠</span>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $is_jobseeker ? 'jobseeker_dashboard.php' : 'company_dashboard.php'; ?>"
                       class="nav-link <?php echo in_array($current_page, ['jobseeker_dashboard.php','company_dashboard.php','dashboard.php']) ? 'active' : ''; ?>">
                        <span class="nav-icon">📊</span>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <?php if ($is_jobseeker && $user): ?>
        <!-- Job Seeker Menu -->
        <div class="nav-section">
            <h4 class="nav-section-title">Job Seeker</h4>
            <ul>
                <li>
                    <a href="jobseeker.php" class="nav-link <?php echo ($current_page == 'jobseeker.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">🎯</span>
                        <span>Find Jobs</span>
                    </a>
                </li>
                <li>
                    <a href="search.php" class="nav-link <?php echo ($current_page == 'search.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">🔍</span>
                        <span>Search Jobs</span>
                    </a>
                </li>
                <li>
                    <a href="profile.php" class="nav-link <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">👤</span>
                        <span>Create Profile</span>
                        
                    </a>
                </li>
                <li>
                    <a href="resume.php" class="nav-link <?php echo ($current_page == 'resume.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">📄</span>
                        <span>Create Resume</span>
                    </a>
                </li>
                <li>
                    <a href="applyjob.php" class="nav-link <?php echo ($current_page == 'applyjob.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">📨</span>
                        <span>Applications</span>
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ($is_company && $user): ?>
        <!-- Company Menu -->
        <div class="nav-section">
            <h4 class="nav-section-title">Company</h4>
            <ul>
                <li>
                    <a href="employeer.php" class="nav-link <?php echo ($current_page == 'employeer.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">🏢</span>
                        <span>Employer Hub</span>
                    </a>
                </li>
                <li>
                    <a href="company.php" class="nav-link <?php echo ($current_page == 'company.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">💼</span>
                        <span>Company Profile</span>
                    </a>
                </li>
                <li>
                    <a href="postjob.php" class="nav-link <?php echo ($current_page == 'postjob.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">➕</span>
                        <span>Post New Job</span>
                    </a>
                </li>
                <li>
                    <a href="view-applicants.php" class="nav-link <?php echo ($current_page == 'view-applicants.php') ? 'active' : ''; ?>">
                        <span class="nav-icon">👥</span>
                        <span>View Applicants</span>
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Account -->
        <div class="nav-section">
            <h4 class="nav-section-title">Account</h4>
            <ul>
                <?php if ($user): ?>
                    <li>
                        <a href="auth.php?logout=1" onclick="return confirm('Are you sure you want to logout?')" class="nav-link logout-link">
                            <span class="nav-icon">🚪</span>
                            <span>Logout</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="login.php" class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">
                            <span class="nav-icon">🔐</span>
                            <span>Login</span>
                        </a>
                    </li>
                    <li>
                        <a href="reg.php" class="nav-link <?php echo ($current_page == 'reg.php') ? 'active' : ''; ?>">
                            <span class="nav-icon">📝</span>
                            <span>Register</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</aside>

