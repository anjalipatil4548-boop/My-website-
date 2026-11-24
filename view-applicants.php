<?php
require_once 'auth.php';
require_login('company');
require_once 'config.php';

ensure_column_exists($conn, 'postjob', 'posted_by_user_id', 'INT NULL');
ensure_column_exists($conn, 'applications', 'postjob_id', 'INT NULL');

$user = get_logged_in_user();
$company_user_id = (int) ($user['id'] ?? 0);
$applications = [];
$job_title_match = "(CONVERT(a.job_title USING utf8mb4) COLLATE utf8mb4_unicode_ci)";
$postjob_title_match = "(CONVERT(p.jobtitle USING utf8mb4) COLLATE utf8mb4_unicode_ci)";
$postjob_label_match = "(CONVERT(CONCAT(p.jobtitle, ' - ', p.companyname) USING utf8mb4) COLLATE utf8mb4_unicode_ci)";
$application_match_sql = "
    (
        a.postjob_id = p.id
        OR (
            (a.postjob_id IS NULL OR a.postjob_id = 0)
            AND (
                $job_title_match = $postjob_title_match
                OR $job_title_match = $postjob_label_match
            )
        )
    )
";

$query = "
    SELECT
        a.id,
        a.name,
        a.email,
        a.phone,
        a.job_title,
        a.cover_letter,
        a.resume_filename,
        a.status,
        a.applied_date,
        MAX(p.companyname) AS companyname,
        MAX(p.location) AS location,
        MAX(p.jobtype) AS jobtype,
        MAX(p.experiencelevel) AS experiencelevel
    FROM applications a
    INNER JOIN postjob p
        ON p.posted_by_user_id = ?
        AND $application_match_sql
    GROUP BY
        a.id,
        a.name,
        a.email,
        a.phone,
        a.job_title,
        a.cover_letter,
        a.resume_filename,
        a.status,
        a.applied_date
    ORDER BY a.applied_date DESC
";

$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $company_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
    $stmt->close();
}

include 'header.php';
include 'company_sidebar.php';
?>

<style>
.glass-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.glass-table th {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text);
    padding: 15px 20px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--glass-border);
}

.glass-table td {
    padding: 15px 20px;
    color: var(--text-light);
    border-bottom: 1px solid rgba(255,255,255,0.05);
    vertical-align: top;
}

.glass-table tr:hover {
    background: rgba(255,255,255,0.02);
}
</style>

<div class="main-content">
    <div class="page-header" style="background-image: url('assets/employer_bg.png'); background-size: cover; background-position: center; border-radius: 20px; position: relative; overflow: hidden; padding: 60px 20px; z-index: 1;">
        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(11,15,25,0.85) 0%, rgba(16,185,129,0.45) 100%); z-index: -1;"></div>
        <h1 style="color: white; font-size: 42px; margin-bottom: 5px;">View Applicants</h1>
        <p style="color: rgba(255,255,255,0.8); font-size: 18px;">Review candidates who applied to the jobs you posted.</p>
    </div>

    <div style="margin-top: 30px; max-width: 1100px; margin-left: auto; margin-right: auto;">
        <?php if (empty($applications)): ?>
            <div class="glass-card text-center p-10">
                <h3 class="text-2xl font-bold mb-3">No applicants yet</h3>
                <p class="text-secondary">Once job seekers apply to your posted jobs, their application data will appear here.</p>
            </div>
        <?php else: ?>
            <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 16px; box-shadow: var(--shadow); overflow-x: auto;">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Job</th>
                            <th>Details</th>
                            <th>Applied On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td>
                                    <div style="color: white; font-weight: 700;"><?php echo htmlspecialchars($application['name']); ?></div>
                                    <div class="text-sm text-secondary"><?php echo htmlspecialchars($application['email']); ?></div>
                                    <div class="text-sm text-secondary"><?php echo htmlspecialchars($application['phone'] ?: 'Phone not provided'); ?></div>
                                </td>
                                <td>
                                    <div style="color: white; font-weight: 700;"><?php echo htmlspecialchars($application['job_title']); ?></div>
                                    <div class="text-sm text-secondary"><?php echo htmlspecialchars($application['companyname']); ?></div>
                                    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px;">
                                        <?php if (!empty($application['jobtype'])): ?>
                                            <span class="job-tag job-tag-accent"><?php echo htmlspecialchars($application['jobtype']); ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($application['experiencelevel'])): ?>
                                            <span class="job-tag"><?php echo htmlspecialchars($application['experiencelevel']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm text-secondary" style="margin-bottom: 8px;"><?php echo htmlspecialchars($application['location'] ?: 'Location not specified'); ?></div>
                                    <?php if (!empty($application['cover_letter'])): ?>
                                        <div style="color: var(--text-light); line-height: 1.6;"><?php echo htmlspecialchars(mb_strimwidth($application['cover_letter'], 0, 120, '...')); ?></div>
                                    <?php else: ?>
                                        <div class="text-sm text-secondary">No cover letter submitted</div>
                                    <?php endif; ?>
                                    <?php if (!empty($application['resume_filename'])): ?>
                                        <div class="text-sm text-secondary" style="margin-top: 8px;">Resume: <?php echo htmlspecialchars($application['resume_filename']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div><?php echo date('M j, Y', strtotime($application['applied_date'])); ?></div>
                                    <div class="text-sm text-secondary"><?php echo date('h:i A', strtotime($application['applied_date'])); ?></div>
                                    <div style="margin-top: 8px;">
                                        <span class="job-tag"><?php echo htmlspecialchars(ucfirst($application['status'] ?: 'pending')); ?></span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
