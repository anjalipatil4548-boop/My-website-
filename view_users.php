<?php
require_once 'auth.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jobportal_demo";

$conn = new mysqli($servername, $username, $password, $dbname);
include 'header.php';
?>

<div class="main-content" style="margin-left: 0;">
    <div class="page-container" style="margin-left: 0; max-width: 1200px; margin: 0 auto;">

        <div class="dashboard-header mb-8">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h1 class="text-5xl font-bold mb-2">👥 Database Users</h1>
                    <p class="text-secondary text-xl">View all registered jobseekers and companies.</p>
                </div>
                <a href="login.php" class="btn-outline">← Back to Login</a>
            </div>

        <!-- Jobseekers -->
        <div class="glass-card mb-8">
            <h2 class="text-2xl font-bold mb-5">👨‍💼 Jobseekers (<?php echo mysqli_num_rows($conn->query("SELECT * FROM jobseekers")); ?>)</h2>
            <?php
            $result = $conn->query("SELECT * FROM jobseekers ORDER BY created_at DESC");
            if ($result->num_rows > 0): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone'] ?? '-'); ?></td>
                                    <td class="text-secondary text-sm"><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-secondary text-center p-8">No jobseekers registered yet.</p>
            <?php endif; ?>
        </div>

        <!-- Companies -->
        <div class="glass-card">
            <h2 class="text-2xl font-bold mb-5">🏢 Companies (<?php echo mysqli_num_rows($conn->query("SELECT * FROM companies")); ?>)</h2>
            <?php
            $result = $conn->query("SELECT * FROM companies ORDER BY created_at DESC");
            if ($result->num_rows > 0): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone'] ?? '-'); ?></td>
                                    <td class="text-secondary text-sm"><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-secondary text-center p-8">No companies registered yet.</p>
            <?php endif; ?>
        </div>
</div>

<?php include 'footer.php'; ?>
