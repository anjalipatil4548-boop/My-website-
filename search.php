<?php
require_once 'config.php';
require_once 'auth.php';
include 'header.php';
//include 'common_sidebar.php';

$keyword = trim($_GET['keyword'] ?? '');
$location = trim($_GET['location'] ?? '');
$jobtype = trim($_GET['jobtype'] ?? '');
$experience = trim($_GET['experience'] ?? '');

$filters = [];
$params = [];
$types = '';

if ($keyword !== '') {
    $filters[] = "(jobtitle LIKE ? OR companyname LIKE ? OR RequiredSkills LIKE ? OR JobDescription LIKE ?)";
    $keyword_like = '%' . $keyword . '%';
    array_push($params, $keyword_like, $keyword_like, $keyword_like, $keyword_like);
    $types .= 'ssss';
}

if ($location !== '') {
    $filters[] = "location LIKE ?";
    $params[] = '%' . $location . '%';
    $types .= 's';
}

if ($jobtype !== '') {
    $filters[] = "jobtype = ?";
    $params[] = $jobtype;
    $types .= 's';
}

if ($experience !== '') {
    $filters[] = "experiencelevel = ?";
    $params[] = $experience;
    $types .= 's';
}

$sql = "SELECT id, jobtitle, companyname, location, salary, salaryrange, jobtype, experiencelevel, RequiredSkills, JobDescription
        FROM postjob";
if (!empty($filters)) {
    $sql .= " WHERE " . implode(' AND ', $filters);
}
$sql .= " ORDER BY id DESC";

$jobs = [];
$stmt = $conn->prepare($sql);
if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    $stmt->close();
}

$job_types = ['Full Time', 'Part Time', 'Internship', 'Remote', 'Contractual'];
$experience_levels = ['Fresher', '1-2 Years', '3-5 Years', '5+ Years', '15+ Years', '25+ years', 'Any'];
?>

<div class="page-container-s">
    <div class="page-header" style="background-image: url('assets/jobseeker_bg.png'); background-size: cover; background-position: center; border-radius: 20px; position: relative; overflow: hidden; padding: 60px 20px; z-index: 1;">
        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(11,15,25,0.85) 0%, rgba(99,102,241,0.5) 100%); z-index: -1;"></div>
        <h1 style="color: white; font-size: 42px; margin-bottom: 5px;">Search Jobs</h1>
        <p style="color: rgba(255,255,255,0.8); font-size: 18px;">Filter the real jobs posted by companies and find the right role faster.</p>
    </div>

    <div class="job-search-section">
        <form method="get" action="search.php">
            <input type="text" name="keyword" placeholder="Search title, company, skill..." value="<?php echo htmlspecialchars($keyword); ?>">
            <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($location); ?>">
            <select name="jobtype">
                <option value="">All job types</option>
                <?php foreach ($job_types as $type_option): ?>
                    <option value="<?php echo htmlspecialchars($type_option); ?>" <?php if ($jobtype === $type_option) { echo 'selected'; } ?>>
                        <?php echo htmlspecialchars($type_option); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="experience">
                <option value="">All experience levels</option>
                <?php foreach ($experience_levels as $experience_option): ?>
                    <option value="<?php echo htmlspecialchars($experience_option); ?>" <?php if ($experience === $experience_option) { echo 'selected'; } ?>>
                        <?php echo htmlspecialchars($experience_option); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-primary">Search</button>
        </form>
    </div>

    <!-- <div class="page-promo-banner" style="margin-top: 1.5rem;">
        <div class="page-promo-copy">
            <h2>Filtered Results From Posted Jobs</h2>
            <p>
                These results come from the same posted jobs shown in the Available Jobs page, so anything you see here
                is ready for job seekers to review and apply for.
            </p>
            <ul>
                <li>Search by title, company, skill, or description</li>
                <li>Filter by location, job type, and experience</li>
                <li>Apply directly to any matching job</li>
            </ul>
        </div>

        <div class="page-promo-media">
            <img src="assets/job.jpg" alt="Job search illustration connected to available job listings">
        </div>
    </div> -->

    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin: 28px auto 18px; max-width: 1000px; flex-wrap: wrap;">
        <h2 class="text-white text-2xl font-bold m-0">Matching Jobs</h2>
        <span class="job-tag"><?php echo count($jobs); ?> result<?php echo count($jobs) === 1 ? '' : 's'; ?></span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 20px; max-width: 1000px; margin-left: auto; margin-right: auto;">
        <?php if (empty($jobs)): ?>
            <div class="glass-card text-center p-10">
                <h3 class="text-2xl font-bold mb-3">No matching jobs found</h3>
                <p class="text-secondary">Try adjusting your filters or browse all jobs from the available jobs page.</p>
                <div style="margin-top: 16px;">
                    <a href="job.php" class="btn-outline">View All Jobs</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($jobs as $job): ?>
                <?php
                $job_title = trim((string) ($job['jobtitle'] ?? 'Untitled Job'));
                $company_name = trim((string) ($job['companyname'] ?? 'Company'));
                $salary_display = trim((string) ($job['salaryrange'] ?? ''));
                if ($salary_display === '') {
                    $salary_display = !empty($job['salary']) ? 'INR ' . $job['salary'] : 'Salary not specified';
                }
                $skills = array_filter(array_map('trim', preg_split('/[,]+/', (string) ($job['RequiredSkills'] ?? ''))));
                $top_skills = array_slice($skills, 0, 4);
                ?>
                <div class="public-job-card" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
                    <div style="flex: 1;">
                        <h3 style="color: var(--text); font-size: 24px; margin: 0 0 8px;"><?php echo htmlspecialchars($job_title); ?></h3>
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
                                <?php echo htmlspecialchars(mb_strimwidth($job['JobDescription'], 0, 200, '...')); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div style="min-width: 170px; display: flex; justify-content: flex-end;">
                        <!-- <a href="applyjob.php?job_id=<?php echo (int) $job['id']; ?>" class="btn-primary" style="padding: 10px 24px; text-decoration: none; display: inline-block;">Apply Now</a> -->
                         <a href="reg.php" class="btn-primary" style="padding: 10px 24px; text-decoration: none; display: inline-block;">Apply Now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
