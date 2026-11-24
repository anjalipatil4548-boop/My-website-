<?php
require_once 'auth.php';
require_login('jobseeker');

$user = get_logged_in_user();
include 'header.php';
include 'common_sidebar.php';

// Get real application count and recent applications
$applied_count = 0;
$recent_applications = [];

// Use db_connect() from auth.php (connects to jobportal_demo)
try {
    $aj_conn = db_connect();
    
    // Count total applications by this user
    $stmt = $aj_conn->prepare("SELECT COUNT(*) as total FROM applications WHERE email = ?");
    $user_email = $user['email'] ?? '';
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $count_result = $stmt->get_result()->fetch_assoc();
    $applied_count = $count_result['total'] ?? 0;
    $stmt->close();

    // Get recent applications
    $stmt = $aj_conn->prepare("SELECT id, name, email, job_title, status, applied_date 
                               FROM applications 
                               WHERE email = ? 
                               ORDER BY applied_date DESC 
                               LIMIT 5");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recent_applications[] = $row;
    }
    $stmt->close();
    $aj_conn->close();
} catch (Exception $e) {
    // Silently fail if table doesn't exist yet
    $applied_count = 0;
    $recent_applications = [];
}
?>

<div class="main-content">

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="flex justify-between items-center flex-wrap gap-5">
            <div>
                <h1 class="text-white text-5xl font-bold mb-2">Welcome back, <?php echo htmlspecialchars($user['name']); ?>! 👋</h1>
                <p class="text-secondary text-xl">Here's what's happening with your job search.</p>
            </div>
            <div class="flex gap-4">
                <a href="search.php" class="btn-outline">🔍 Find Jobs</a>
                <a href="index.php" class="btn-primary">🏠 Home</a>
            </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card success-glass">
            <div class="text-6xl">📄</div>
            <h3 class="text-success text-4xl font-bold">0</h3>
            <p class="text-secondary">Resumes Uploaded</p>
        </div>
        <div class="stat-card primary-glass">
            <div class="text-6xl">📋</div>
            <h3 class="text-primary text-4xl font-bold" id="appliedCount"><?php echo $applied_count; ?></h3>
            <p class="text-secondary">Jobs Applied</p>
        </div>
        <div class="stat-card warning-glass">
            <div class="text-6xl">✅</div>
            <h3 class="text-warning text-4xl font-bold">0</h3>
            <p class="text-secondary">Invited for Interview</p>
        </div>

    <!-- Recent Applications -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-white text-3xl font-bold">Recent Applications</h2>
            <a href="show_applications.php" class="text-primary font-semibold">View All →</a>
        </div>

        <div class="glass-panel">
            <?php if (empty($recent_applications)): ?>
                <div class="p-10 text-center text-secondary">
                    <div class="text-6xl mb-5">📭</div>
                    <h3 class="text-2xl font-bold mb-2">No applications yet</h3>
                    <p>Start applying to jobs to see your activity here.</p>
                    <a href="applyjob.php" class="btn-primary mt-4 inline-block">Apply for Jobs</a>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_applications as $app): ?>
                            <tr>
                                <td>
                                    <div class="font-semibold"><?php echo htmlspecialchars($app['job_title']); ?></div>
                                    <div class="text-sm text-secondary"><?php echo htmlspecialchars($app['name']); ?></div>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($app['applied_date'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $app['status'] ?? 'pending'; ?>">
                                        <?php echo ucfirst($app['status'] ?? 'Pending'); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    <!-- Quick Job Recommendations -->
    <div class="mt-12">
        <h2 class="text-white text-3xl font-bold mb-5">Recommended Jobs</h2>
        <div class="glass-panel p-8 text-center text-secondary">
            <p>Job recommendations will appear here based on your profile and search history.</p>
        </div>

</div>

<?php include 'footer.php'; ?>
