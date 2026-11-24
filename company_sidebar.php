<?php
/**
 * Company sidebar menu.
 * Preserves company-only navigation with a cleaner shared layout.
 */
require_once 'auth.php';

$user = get_logged_in_user();
if (!$user || ($user['role'] ?? '') !== 'company') {
    header('Location: login.php');
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
$user_name = trim((string) ($user['name'] ?? 'Company User'));
$user_name = $user_name !== '' ? $user_name : 'Company User';
$user_initial = strtoupper(substr($user_name, 0, 1));

$nav_sections = [
    [
        'title' => 'Main Menu',
        'links' => [
            ['href' => 'index.php', 'label' => 'Home', 'icon' => '&#8962;', 'pages' => ['index.php']],
            ['href' => 'company_dashboard.php', 'label' => 'Dashboard', 'icon' => '&#128202;', 'pages' => ['company_dashboard.php']],
        ],
    ],
    [
        'title' => 'Hiring Tools',
        'links' => [
            ['href' => 'employeer.php', 'label' => 'Employer Hub', 'icon' => '&#127970;', 'pages' => ['employeer.php']],
            ['href' => 'company.php', 'label' => 'Company Profile', 'icon' => '&#128188;', 'pages' => ['company.php']],
            ['href' => 'postjob.php', 'label' => 'Post New Job', 'icon' => '&#10133;', 'pages' => ['postjob.php']],
            ['href' => 'view-applicants.php', 'label' => 'View Applicants', 'icon' => '&#128101;', 'pages' => ['view-applicants.php']],
        ],
    ],
    [
        'title' => 'Account',
        'links' => [
            ['href' => 'auth.php?logout=1', 'label' => 'Logout', 'icon' => '&#128682;', 'pages' => [], 'danger' => true, 'confirm' => 'Logout?'],
        ],
    ],
];
?>

<aside class="company-sidebar">
    <div class="sidebar-brand-block sidebar-brand-block-company">
        <a href="company_dashboard.php" class="sidebar-brand-link">
            <span class="sidebar-brand-title">Company Workspace</span>
            <span class="sidebar-brand-subtitle">Manage jobs, applicants, and profile details</span>
        </a>
    </div>

    <div class="sidebar-profile-card sidebar-profile-card-centered">
        <div class="sidebar-profile-avatar"><?php echo htmlspecialchars($user_initial); ?></div>
        <div class="sidebar-profile-copy">
            <div class="sidebar-profile-name"><?php echo htmlspecialchars($user_name); ?></div>
            <div class="sidebar-profile-role">Company Account</div>
        </div>
    </div>

    <nav class="sidebar-nav-shell" aria-label="Company sidebar navigation">
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
