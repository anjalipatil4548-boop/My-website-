<?php
require_once 'auth.php';
require_login('jobseeker');

$user = get_logged_in_user();
include 'header.php';
include 'common_sidebar.php';

$applications = [];
$db_error = null;

// Use db_connect() from auth.php (connects to jobportal_demo)
try {
    $aj_conn = db_connect();
    $stmt = $aj_conn->prepare("SELECT * FROM applications WHERE email = ? ORDER BY applied_date DESC");
    $stmt->bind_param("s", $user['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
    $stmt->close();
    $aj_conn->close();
} catch (Exception $e) {
    $db_error = "Database connection failed: " . $e->getMessage();
}
?>

<div class="main-content">
    <div class="dashboard-header">
        <div class="flex justify-between items-center flex-wrap gap-5">
            <div>
                <h1 class="text-white text-4xl font-bold mb-2">📋 My Applications</h1>
                <p class="text-secondary text-xl">Track all your job applications in one place.</p>
            </div>
            <div class="flex gap-4">
                <a href="applyjob.php" class="btn-primary">➕ New Application</a>
                <a href="jobseeker_dashboard.php" class="btn-outline">← Dashboard</a>
            </div>
    </div>

    <div class="glass-panel">
        <?php if ($db_error): ?>
            <div class="p-10 text-center text-secondary">
                <div class="text-6xl mb-5">⚠️</div>
                <h3 class="text-2xl font-bold mb-2">Database Error</h3>
                <p class="mb-5"><?php echo htmlspecialchars($db_error); ?></p>
            </div>
        <?php elseif (empty($applications)): ?>
            <div class="p-10 text-center text-secondary">
                <div class="text-6xl mb-5">📭</div>
                <h3 class="text-2xl font-bold mb-2">No applications yet</h3>
                <p class="mb-5">You haven't submitted any job applications yet.</p>
                <a href="applyjob.php" class="btn-primary inline-block">Apply for Your First Job</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Job Title</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $row): ?>
                        <tr>
                            <td><strong>#<?php echo $row['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td>
                                <span class="job-tag job-tag-accent"><?php echo htmlspecialchars($row['job_title']); ?></span>
                            </td>
                            <td><?php echo date('M j, Y H:i', strtotime($row['applied_date'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $row['status'] ?? 'pending'; ?>">
                                    <?php echo ucfirst($row['status'] ?? 'Pending'); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php include 'footer.php'; ?>
