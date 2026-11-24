<?php
require_once 'config.php';
include 'header.php';
include 'common_sidebar.php';

$posted_jobs = [];
$job_query = "SELECT id, jobtitle, companyname, location, salary, salaryrange, jobtype, experiencelevel, RequiredSkills, JobDescription
              FROM postjob
              ORDER BY id DESC";
$job_result = mysqli_query($conn, $job_query);
if ($job_result) {
    while ($row = mysqli_fetch_assoc($job_result)) {
        $posted_jobs[] = $row;
    }
}
?>

<div class="page-container">
    <div class="page-header" style="background-image: url('assets/jobseeker_bg.png'); background-size: cover; background-position: center; border-radius: 20px; position: relative; overflow: hidden; padding: 60px 20px; z-index: 1;">
        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(11,15,25,0.85) 0%, rgba(99,102,241,0.5) 100%); z-index: -1;"></div>
        <h1 style="color: white; font-size: 42px; margin-bottom: 5px;">Available Jobs</h1>
        <p style="color: rgba(255,255,255,0.8); font-size: 18px;">Browse the jobs companies have posted and apply directly from the listings below.</p>
    </div>

    <div class="page-promo-banner">
        <div class="page-promo-copy">
            <h2>Live Jobs From Employer Posts</h2>
            <p>
                This section now shows the real jobs posted by companies on CareerWave, so job seekers can explore
                current openings and move straight into the application flow.
            </p>
            <ul>
                <li>See the latest posted job titles and companies</li>
                <li>Review location, type, salary, and experience quickly</li>
                <li>Open the application page with the selected job prefilled</li>
            </ul>
        </div>

        <div class="page-promo-media">
            <img src="assets/WhatsApp Image 2026-04-29 at 16.41.44.jpeg" alt="Job vacancies illustration for candidates exploring opportunities">
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 30px; max-width: 1000px; margin-left: auto; margin-right: auto;">
        <?php if (empty($posted_jobs)): ?>
            <div class="glass-card text-center p-10">
                <h3 class="text-2xl font-bold mb-3">No posted jobs yet</h3>
                <p class="text-secondary">When companies publish new jobs, they will appear here for job seekers to apply.</p>
            </div>
        <?php else: ?>
            <?php foreach ($posted_jobs as $job): ?>
                <?php
                $job_title = trim((string) ($job['jobtitle'] ?? 'Untitled Job'));
                $company_name = trim((string) ($job['companyname'] ?? 'Company'));
                $salary_display = trim((string) ($job['salaryrange'] ?? ''));
                if ($salary_display === '') {
                    $salary_display = !empty($job['salary']) ? 'INR ' . $job['salary'] : 'Salary not specified';
                }
                $skills = array_filter(array_map('trim', preg_split('/[,]+/', (string) ($job['RequiredSkills'] ?? ''))));
                $top_skills = array_slice($skills, 0, 3);
                ?>
                <div class="public-job-card" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
                    <div style="flex: 1;">
                        <h3 style="color: var(--text); font-size: 24px; margin: 0 0 8px;"> <?php echo htmlspecialchars($job_title); ?></h3>
                        <p style="color: var(--text-muted); margin: 0 0 12px; font-weight: 500;">
                            <?php echo htmlspecialchars($company_name); ?>
                            &bull; <?php echo htmlspecialchars($job['location'] ?: 'Location not specified'); ?>
                            &bull; <?php echo htmlspecialchars($salary_display); ?>
                        </p>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px;">
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
                            <p style="color: var(--text-light); margin: 0; line-height: 1.7;">
                                <?php echo htmlspecialchars(mb_strimwidth($job['JobDescription'], 0, 180, '...')); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div style="min-width: 170px; display: flex; justify-content: flex-end;">
                        <a href="applyjob.php?job_id=<?php echo (int) $job['id']; ?>" class="btn-primary" style="padding: 10px 24px; text-decoration: none; display: inline-block;">Apply Now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

