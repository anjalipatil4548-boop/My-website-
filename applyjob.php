<?php
require_once 'apply_config.php';
require_once 'auth.php';

ensure_column_exists($conn, 'applications', 'postjob_id', 'INT NULL');

$message = '';
$error = '';
$viewer = get_logged_in_user();
$viewer_email = '';
$viewer_name = '';
$recent_applications = [];

if ($viewer && ($viewer['role'] ?? '') === 'jobseeker') {
    $viewer_email = trim((string) ($viewer['email'] ?? ''));
    $viewer_name = trim((string) ($viewer['name'] ?? ''));
}

$selected_job_id = (int) ($_GET['job_id'] ?? 0);
$selected_job_id = (int) ($_POST['job_id'] ?? $selected_job_id);
$job_options = [];

$job_query = "SELECT id, jobtitle, companyname FROM postjob ORDER BY id DESC";
$job_result = $conn->query($job_query);
if ($job_result) {
    while ($job_row = $job_result->fetch_assoc()) {
        $title = trim((string) ($job_row['jobtitle'] ?? ''));
        $company = trim((string) ($job_row['companyname'] ?? ''));
        if ($title === '') {
            continue;
        }
        $job_row['label'] = $title . ($company !== '' ? ' - ' . $company : '');
        $job_options[] = $job_row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $cover = mysqli_real_escape_string($conn, $_POST['cover']);
    $job_id = (int) ($_POST['job_id'] ?? 0);
    $viewer_name = trim((string) $_POST['name']);
    $viewer_email = trim((string) $_POST['email']);

    $job_lookup = $conn->prepare("SELECT id, jobtitle FROM postjob WHERE id = ?");
    $job_lookup->bind_param("i", $job_id);
    $job_lookup->execute();
    $job_data = $job_lookup->get_result()->fetch_assoc();
    $job_lookup->close();

    if (!$job_data) {
        $error = 'Selected job was not found. Please choose a valid posted job.';
    } else {
        $resume_name = $_FILES['resume']['name'] ?? '';
        $resume_filename = basename($resume_name);
        $job_title = mysqli_real_escape_string($conn, $job_data['jobtitle']);

        $sql = "INSERT INTO applications (name, email, phone, job_title, cover_letter, resume_filename, postjob_id)
                VALUES ('$name', '$email', '$phone', '$job_title', '$cover', '$resume_filename', '$job_id')";

        if ($conn->query($sql) === TRUE) {
            $message = 'Application submitted successfully!';
        } else {
            $error = 'Error: ' . $conn->error;
        }
    }
}

if ($viewer_email !== '') {
    $recent_query = $conn->prepare("SELECT * FROM applications WHERE email = ? ORDER BY applied_date DESC");
    if ($recent_query) {
        $recent_query->bind_param("s", $viewer_email);
        $recent_query->execute();
        $recent_result = $recent_query->get_result();
        while ($row = $recent_result->fetch_assoc()) {
            $recent_applications[] = $row;
        }
        $recent_query->close();
    }
}

include 'header.php';
include 'common_sidebar.php';
?>

<div class="page-container" style="max-width: 1200px;">
        <div class="dashboard-header mb-8">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h1 class="text-5xl font-bold mb-2">Job Application</h1>
                    <p class="text-secondary text-xl">Apply for your dream job with a few clicks.</p>
                </div>
                <a href="jobseeker_dashboard.php" class="btn-outline">Back to Dashboard</a>
            </div>
        </div>

        <div class="page-promo-banner" style="margin-top: 0; margin-bottom: 2rem;">
            <div class="page-promo-copy">
                <h2>Apply With More Confidence</h2>
                <p>
                    Before you submit, take a moment to tailor your cover note, choose the most relevant resume, and
                    double-check the role you are targeting. Small improvements can make your application much stronger.
                </p>
                <ul>
                    <li>Match your resume to the selected posted job</li>
                    <li>Keep your details current and easy to review</li>
                    <li>Use the cover letter field to highlight fit quickly</li>
                </ul>
            </div>

            <div class="page-promo-media">
                <img src="assets/WhatsApp Image 2026-04-29 at 16.26.56.jpeg" alt="Illustration encouraging job seekers while preparing an application">
            </div>
        </div>

        <?php if ($message): ?>
            <div class="glass-panel p-5 mb-8" style="background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3);">
                <p class="text-success font-semibold"><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="glass-panel p-5 mb-8" style="background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3);">
                <p class="text-danger font-semibold"><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <div class="grid-2-cols">
            <div class="glass-card">
                <h2 class="text-2xl font-bold mb-5">Submit Application</h2>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" placeholder="John Doe" value="<?php echo htmlspecialchars($_POST['name'] ?? $viewer_name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" placeholder="john@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? $viewer_email); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone" placeholder="+1 234 567 890" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Job Title *</label>
                        <select name="job_id" required>
                            <option value="">Select Job</option>
                            <?php if (empty($job_options)): ?>
                                <option value="" disabled>No posted jobs available</option>
                            <?php else: ?>
                                <?php foreach ($job_options as $job_option): ?>
                                    <option value="<?php echo (int) $job_option['id']; ?>" <?php if ($selected_job_id === (int) $job_option['id']) { echo 'selected'; } ?>>
                                        <?php echo htmlspecialchars($job_option['label']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cover Letter</label>
                        <textarea name="cover" rows="4" placeholder="Tell us why you're perfect for this job..."><?php echo htmlspecialchars($_POST['cover'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Resume (PDF/Doc) *</label>
                        <input type="file" name="resume" accept=".pdf,.doc,.docx" required>
                    </div>
                    <button type="submit" class="btn-submit">Submit Application</button>
                </form>
            </div>

            <div class="glass-card">
                <h2 class="text-2xl font-bold mb-5">Recent Applications (<?php echo count($recent_applications); ?>)</h2>
                <?php if (!empty($recent_applications)): ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Job</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_applications as $row): ?>
                                    <tr>
                                        <td>
                                            <div class="font-semibold"><?php echo htmlspecialchars($row['name']); ?></div>
                                            <div class="text-sm text-secondary"><?php echo htmlspecialchars($row['email']); ?></div>
                                        </td>
                                        <td><span class="job-tag job-tag-accent"><?php echo htmlspecialchars($row['job_title']); ?></span></td>
                                        <td class="text-sm text-secondary"><?php echo date('M j, Y', strtotime($row['applied_date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center p-10">
                        <h3 class="text-xl font-bold mb-2">No applications yet</h3>
                        <p class="text-secondary">Your own recent applications will appear here after you apply.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</div>

<?php include 'footer.php'; ?>
