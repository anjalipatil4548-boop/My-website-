<?php
require_once 'auth.php';
require_login('company');
require_once 'config.php';

ensure_column_exists($conn, 'postjob', 'posted_by_user_id', 'INT NULL');
ensure_column_exists($conn, 'applications', 'postjob_id', 'INT NULL');

$user = get_logged_in_user();
$company_user_id = (int) ($user['id'] ?? 0);
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

$posted_jobs_count = 0;
$applicants_count = 0;
$dashboard_jobs = [];
$recent_applicants = [];

$count_query = $conn->prepare("SELECT COUNT(*) AS total FROM postjob WHERE posted_by_user_id = ?");
if ($count_query) {
    $count_query->bind_param("i", $company_user_id);
    $count_query->execute();
    $count_result = $count_query->get_result()->fetch_assoc();
    $posted_jobs_count = (int) ($count_result['total'] ?? 0);
    $count_query->close();
}

$applicant_count_query = $conn->prepare("
    SELECT COUNT(DISTINCT a.id) AS total
    FROM applications a
    INNER JOIN postjob p
        ON p.posted_by_user_id = ?
        AND $application_match_sql
");
if ($applicant_count_query) {
    $applicant_count_query->bind_param("i", $company_user_id);
    $applicant_count_query->execute();
    $count_result = $applicant_count_query->get_result()->fetch_assoc();
    $applicants_count = (int) ($count_result['total'] ?? 0);
    $applicant_count_query->close();
}

$dashboard_jobs_query = $conn->prepare("
    SELECT
        p.id,
        p.jobtitle,
        p.companyname,
        p.location,
        p.salary,
        p.salaryrange,
        p.jobtype,
        p.experiencelevel,
        p.RequiredSkills,
        p.JobDescription,
        p.modifydate,
        COUNT(DISTINCT a.id) AS applicant_count
    FROM postjob p
    LEFT JOIN applications a
        ON $application_match_sql
    WHERE p.posted_by_user_id = ?
    GROUP BY
        p.id,
        p.jobtitle,
        p.companyname,
        p.location,
        p.salary,
        p.salaryrange,
        p.jobtype,
        p.experiencelevel,
        p.RequiredSkills,
        p.JobDescription,
        p.modifydate
    ORDER BY p.id DESC
    LIMIT 4
");
if ($dashboard_jobs_query) {
    $dashboard_jobs_query->bind_param("i", $company_user_id);
    $dashboard_jobs_query->execute();
    $dashboard_jobs = $dashboard_jobs_query->get_result()->fetch_all(MYSQLI_ASSOC);
    $dashboard_jobs_query->close();
}

$recent_applicants_query = $conn->prepare("
    SELECT
        a.id,
        a.name,
        a.job_title,
        a.status,
        a.applied_date,
        a.email,
        MAX(p.companyname) AS companyname
    FROM applications a
    INNER JOIN postjob p
        ON p.posted_by_user_id = ?
        AND $application_match_sql
    GROUP BY a.id, a.name, a.job_title, a.status, a.applied_date, a.email
    ORDER BY a.applied_date DESC
    LIMIT 3
");
if ($recent_applicants_query) {
    $recent_applicants_query->bind_param("i", $company_user_id);
    $recent_applicants_query->execute();
    $recent_applicants = $recent_applicants_query->get_result()->fetch_all(MYSQLI_ASSOC);
    $recent_applicants_query->close();
}

include 'header.php';
include 'company_sidebar.php';
?>

<div class="main-content">
    <div class="dashboard-header-company">
        <div class="flex justify-between items-center flex-wrap gap-5">
            <div>
                <h1 class="text-white text-5xl font-bold mb-1">Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</h1>
                <p class="text-secondary text-xl mt-2">Manage your posted jobs, review applications, and track hiring activity.</p>
            </div>
            <div class="flex gap-4">
                <a href="postjob.php" class="btn-outline px-8 py-4 rounded-xl font-semibold backdrop-blur-md border border-white/20 hover:scale-105">Post New Job</a>
                <a href="index.php" class="btn-primary px-8 py-4 rounded-xl font-semibold shadow-2xl hover:scale-105" style="background: linear-gradient(135deg, #22c55e, #86efac); box-shadow: 0 8px 32px rgba(34,197,94,0.3);">Home</a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card primary-glass">
            <div class="text-5xl mb-3">Jobs</div>
            <h3 class="primary-text text-4xl font-bold mb-1"><?php echo $posted_jobs_count; ?></h3>
            <p class="text-secondary text-lg">Posted Jobs</p>
        </div>

        <div class="stat-card success-glass">
            <div class="text-5xl mb-3">Apps</div>
            <h3 class="success-text text-4xl font-bold mb-1"><?php echo $applicants_count; ?></h3>
            <p class="text-secondary text-lg">Applicants Received</p>
        </div>

        <div class="stat-card warning-glass">
            <div class="text-5xl mb-3">Live</div>
            <h3 class="warning-text text-4xl font-bold mb-1"><?php echo count($dashboard_jobs); ?></h3>
            <p class="text-secondary text-lg">Current Listings</p>
        </div>

        <div class="stat-card danger-glass">
            <div class="text-5xl mb-3">New</div>
            <h3 class="danger-text text-4xl font-bold mb-1"><?php echo count($recent_applicants); ?></h3>
            <p class="text-secondary text-lg">Latest Applications</p>
        </div>
    </div>

    <div class="dashboard-section mt-12">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-white text-3xl font-bold m-0">Current Dashboard Data</h2>
            <a href="view-applicants.php" style="color: #22c55e; text-decoration: none; font-weight: 600;">View All Applicants</a>
        </div>

        <div class="grid-2-cols">
            <div>
                <h3 class="text-white text-2xl font-bold mb-4">Recent Applicants</h3>
                <div class="glass-panel">
                    <?php if (empty($recent_applicants)): ?>
                        <div style="padding: 40px; text-align: center; color: rgba(255,255,255,0.5);">
                            <div style="font-size: 42px; margin-bottom: 15px;">No applicants</div>
                            <p style="margin: 0;">Applicants for your posted jobs will appear here.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_applicants as $app): ?>
                            <div style="padding: 25px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <h4 style="color: white; margin: 0 0 5px; font-size: 16px;"><?php echo htmlspecialchars($app['name'] ?: 'Applicant'); ?></h4>
                                <p style="color: rgba(255,255,255,0.7); margin: 0 0 6px; font-size: 14px;"><?php echo htmlspecialchars($app['job_title']); ?></p>
                                <p style="color: rgba(255,255,255,0.55); margin: 0 0 10px; font-size: 13px;"><?php echo htmlspecialchars($app['email']); ?></p>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span class="status-badge status-<?php echo htmlspecialchars($app['status'] ?: 'pending'); ?>" style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: rgba(255,255,255,0.1);">
                                        <?php echo ucfirst($app['status'] ?: 'pending'); ?>
                                    </span>
                                    <small style="color: rgba(255,255,255,0.5);"><?php echo date('M j', strtotime($app['applied_date'])); ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h3 class="text-white text-2xl font-bold mb-4">Posted Jobs</h3>
                <div class="glass-panel">
                    <?php if (empty($dashboard_jobs)): ?>
                        <div style="padding: 40px; text-align: center; color: rgba(255,255,255,0.5);">
                            <div style="font-size: 42px; margin-bottom: 15px;">No jobs yet</div>
                            <p style="margin: 0;">Post your first job to see it on the company dashboard.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($dashboard_jobs as $job): ?>
                            <?php
                            $job_title = trim((string) ($job['jobtitle'] ?? 'Untitled Job'));
                            $company_name = trim((string) ($job['companyname'] ?? ($user['name'] ?? 'Company')));
                            $salary_display = trim((string) ($job['salaryrange'] ?? ''));
                            if ($salary_display === '') {
                                $salary_display = !empty($job['salary']) ? 'INR ' . $job['salary'] : 'Salary not specified';
                            }
                            $skills = array_filter(array_map('trim', preg_split('/[,]+/', (string) ($job['RequiredSkills'] ?? ''))));
                            $top_skills = array_slice($skills, 0, 3);
                            ?>
                            <div style="padding: 25px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <div style="display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; flex-wrap: wrap;">
                                    <div style="flex: 1; min-width: 240px;">
                                        <h4 style="color: white; margin: 0 0 6px; font-size: 18px;"><?php echo htmlspecialchars($job_title); ?></h4>
                                        <p style="color: rgba(255,255,255,0.7); margin: 0 0 12px; font-size: 14px;">
                                            <?php echo htmlspecialchars($company_name); ?>
                                            &bull; <?php echo htmlspecialchars($job['location'] ?: 'Location not specified'); ?>
                                            &bull; <?php echo htmlspecialchars($salary_display); ?>
                                        </p>

                                        <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px;">
                                            <?php if (!empty($job['jobtype'])): ?>
                                                <span class="job-tag job-tag-accent"><?php echo htmlspecialchars($job['jobtype']); ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($job['experiencelevel'])): ?>
                                                <span class="job-tag"><?php echo htmlspecialchars($job['experiencelevel']); ?></span>
                                            <?php endif; ?>
                                            <?php foreach ($top_skills as $skill): ?>
                                                <span class="job-tag"><?php echo htmlspecialchars($skill); ?></span>
                                            <?php endforeach; ?>
                                        </div>

                                        <?php if (!empty($job['JobDescription'])): ?>
                                            <p style="color: rgba(255,255,255,0.75); margin: 0; line-height: 1.6;">
                                                <?php echo htmlspecialchars(mb_strimwidth($job['JobDescription'], 0, 150, '...')); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div style="min-width: 150px; text-align: right;">
                                        <div class="job-tag job-tag-accent" style="display: inline-flex; margin-bottom: 10px;">
                                            <?php echo (int) $job['applicant_count']; ?> Applicant<?php echo ((int) $job['applicant_count'] === 1) ? '' : 's'; ?>
                                        </div>
                                        <div style="color: rgba(255,255,255,0.5); font-size: 13px; margin-bottom: 14px;">
                                            <?php echo !empty($job['modifydate']) ? date('M j, Y', strtotime($job['modifydate'])) : 'Recently posted'; ?>
                                        </div>
                                        <a href="view-applicants.php" class="btn-outline" style="display: inline-block; padding: 10px 16px; text-decoration: none;">View Applicants</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
