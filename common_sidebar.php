<?php
/**
 * Common Sidebar Menu for Job Portal
 * Include in shared dashboard-style pages.
 */
require_once 'auth.php';

$user = get_logged_in_user();
$current_page = basename($_SERVER['PHP_SELF']);
$user_name = trim((string) ($user['name'] ?? 'Guest User'));
$user_name = $user_name !== '' ? $user_name : 'Guest User';
$user_initial = strtoupper(substr($user_name, 0, 1));
$user_role = $user ? ucfirst((string) ($user['role'] ?? 'Member')) : 'Guest';
$dashboard_link = $user && ($user['role'] ?? '') === 'company' ? 'company_dashboard.php' : 'jobseeker_dashboard.php';

$nav_sections = [
    [
        'title' => 'Main Menu',
        'links' => [
            ['href' => 'index.php', 'label' => 'Home', 'icon' => '&#8962;', 'pages' => ['index.php']],
        ],
    ],
];

if ($user) {
    $nav_sections[0]['links'][] = ['href' => $dashboard_link, 'label' => 'Dashboard', 'icon' => '&#128202;', 'pages' => ['jobseeker_dashboard.php', 'company_dashboard.php']];

    if (($user['role'] ?? '') === 'jobseeker') {
        $nav_sections[] = [
            'title' => 'Job Tools',
            'links' => [
                ['href' => 'search.php', 'label' => 'Search Jobs', 'icon' => '&#128269;', 'pages' => ['search.php', 'job.php']],
                ['href' => 'applyjob.php', 'label' => 'Apply Jobs', 'icon' => '&#128233;', 'pages' => ['applyjob.php']],
                ['href' => 'resume.php', 'label' => 'My Resumes', 'icon' => '&#128196;', 'pages' => ['resume.php']],
                ['href' => 'show_applications.php', 'label' => 'Applications', 'icon' => '&#128221;', 'pages' => ['show_applications.php']],
                ['href' => 'profile.php', 'label' => 'Profile', 'icon' => '&#128100;', 'pages' => ['profile.php', 'jobseeker.php']],
            ],
        ];
    } elseif (($user['role'] ?? '') === 'company') {
        $nav_sections[] = [
            'title' => 'Hiring Tools',
            'links' => [
                ['href' => 'postjob.php', 'label' => 'Post Job', 'icon' => '&#10133;', 'pages' => ['postjob.php']],
                ['href' => 'view-applicants.php', 'label' => 'Applicants', 'icon' => '&#128101;', 'pages' => ['view-applicants.php']],
                ['href' => 'company.php', 'label' => 'Company Profile', 'icon' => '&#128188;', 'pages' => ['company.php']],
            ],
        ];
    }

    if (isset($_SESSION['admin_id'])) {
        $nav_sections[] = [
            'title' => 'Admin',
            'links' => [
                ['href' => 'admin/dashboard.php', 'label' => 'Admin Panel', 'icon' => '&#9881;', 'pages' => ['dashboard.php']],
            ],
        ];
    }

    $nav_sections[] = [
        'title' => 'Account',
        'links' => [
            ['href' => 'auth.php?logout=1', 'label' => 'Logout', 'icon' => '&#128682;', 'pages' => [], 'danger' => true, 'confirm' => 'Logout from all sessions?'],
        ],
    ];
} else {
    $nav_sections[] = [
        'title' => 'Account',
        'links' => [
            ['href' => 'login.php', 'label' => 'Login', 'icon' => '&#128274;', 'pages' => ['login.php']],
            ['href' => 'reg.php', 'label' => 'Register', 'icon' => '&#9997;', 'pages' => ['reg.php']],
        ],
    ];
}
?>

<aside class="common-sidebar">
    <div class="sidebar-brand-block">
        <a href="index.php" class="sidebar-brand-link">
            <span class="sidebar-brand-title">CAREER MITRA</span>
            <span class="sidebar-brand-subtitle">Quick navigation for your workspace</span>
        </a>
    </div>

    <div class="sidebar-profile-card">
        <div class="sidebar-profile-avatar"><?php echo htmlspecialchars($user_initial); ?></div>
        <div class="sidebar-profile-copy">
            <div class="sidebar-profile-name"><?php echo htmlspecialchars($user_name); ?></div>
            <div class="sidebar-profile-role"><?php echo htmlspecialchars($user_role); ?></div>
        </div>
    </div>

    <nav class="sidebar-nav-shell" aria-label="Common sidebar navigation">
        <?php foreach ($nav_sections as $section): ?>
            <div class="sidebar-nav-section">
                <div class="sidebar-nav-heading"><?php echo htmlspecialchars($section['title']); ?></div>
                <ul class="sidebar-nav-list">
                    <?php foreach ($section['links'] as $link): ?>
                        <?php
                        $is_active = !empty($link['pages']) && in_array($current_page, $link['pages'], true);
                        $link_classes = 'sidebar-nav-link' . ($is_active ? ' active' : '') . (!empty($link['danger']) ? ' is-danger' : '');
                        $confirm = isset($link['confirm']) ? ' onclick="return confirm(\'' . htmlspecialchars($link['confirm'], ENT_QUOTES) . '\')"' : '';
                        ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($link['href']); ?>" class="<?php echo $link_classes; ?>"<?php echo $confirm; ?>>
                                <span class="sidebar-nav-icon"><?php echo $link['icon']; ?></span>
                                <span class="sidebar-nav-label"><?php echo htmlspecialchars($link['label']); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </nav>
</aside>
