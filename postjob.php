<?php
require_once 'auth.php';
require_login('company');
require_once 'config.php';
$user = get_logged_in_user();

ensure_column_exists($conn, 'postjob', 'posted_by_user_id', 'INT NULL');
if($_POST)
{
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
// 1. Get the form data and sanitize it for safety
    $jobtitle = mysqli_real_escape_string($conn, $_POST['jobtitle']);
    $jobtype = mysqli_real_escape_string($conn, $_POST['jobtype']);
    $experiencelevel = mysqli_real_escape_string($conn, $_POST['experiencelevel']);
    $companyname = mysqli_real_escape_string($conn, $_POST['companyname']);
    $agelimit = mysqli_real_escape_string($conn, $_POST['agelimit']);
    $genderpreference = mysqli_real_escape_string($conn, $_POST['genderpreference']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $skills = mysqli_real_escape_string($conn, $_POST['RequiredSkills']);
    $description = mysqli_real_escape_string($conn, $_POST['JobDescription']);    
    $requirements = mysqli_real_escape_string($conn, $_POST['JobRequirements']);
    $salaryrange = mysqli_real_escape_string($conn, $_POST['salaryrange']);
    $posted_by_user_id = (int) ($user['id'] ?? 0);

    // 2. Fix the File Upload variable name (changed to match your SQL)
    $document = $_FILES['UploadjobDocument']['name'] ?? '';

    // 3. The SQL Query (Ensure 'location' is spelled correctly in your DB table)
    $sql = "INSERT INTO postjob
    (jobtitle, jobtype, experiencelevel, companyname, agelimit, genderpreference, salary, location, RequiredSkills, JobDescription, JobRequirements, UploadjobDocument, salaryrange, posted_by_user_id, modifydate)
    VALUES 
    ('$jobtitle', '$jobtype', '$experiencelevel', '$companyname', '$agelimit', '$genderpreference', '$salary', '$location', '$skills', '$description', '$requirements', '$document', '$salaryrange', '$posted_by_user_id', NOW())";
    if ($conn->query($sql) === TRUE) {
        echo "Job added successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}
}
?>
<?php include 'header.php'; ?>
<?php include 'company_sidebar.php'; ?>
<div class="main-content">
            <!-- <div class="auth-image" style="background-image: url('assets/employer_bg.png'); min-height: 800px;">
                <h3>Hire The Best</h3>
            <p>Post your job requirements to our extensive network of skilled professionals.</p>
        </div> -->

        <div class="auth-form-side">
            <h2>📢 Post a Job</h2>

           <form action="postjob.php" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" name="jobtitle" required>
                </div>

                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Job Type</label>
                        <select name="jobtype">
                            <option value="" disabled selected>Select Job Type</option>
                            <option>Full Time</option>
                            <option>Part Time</option>
                            <option>Internship</option>
                            <option>Remote</option>
                            <option>Contractual</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Experience Level</label>
                        <select name="experiencelevel">
                            <option value="" disabled selected>Select Experience</option>
                            <option>Fresher</option>
                            <option>1-2 Years</option>
                            <option>3-5 Years</option>
                            <option>5+ Years</option>
                            <option>15+ Years</option>
                            <option>25+ years</option>
                            <option>Any</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="companyname" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
                </div>

                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Age Limit</label>
                        <input type="number" name="agelimit">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Gender Preference</label>
                        <select name="genderpreference">
                            <option value="" disabled selected>Select</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Any</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Salary</label>
                        <input type="text" name="salary">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Salary Range</label>
                        <input type="text" name="salaryrange" placeholder="e.g. 20000 - 40000">
                    </div>
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location">
                </div>

                <div class="form-group">
                    <label>Required Skills</label>
                    <textarea name="RequiredSkills" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Job Description</label>
                    <textarea name="JobDescription" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label>Job Requirements</label>
                    <textarea name="JobRequirements" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label>Upload Job Document (Optional)</label>
                    <input type="file" name="UploadjobDocument" style="padding: 11px;">
                </div>

                <button type="submit" class="btn-submit">Post Job</button>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
