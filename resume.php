<?php
require_once 'auth.php';
require_once 'config.php';

// 1. DATABASE LOGIC: Handle the form submission
if(isset($_POST['submit'])) 
{
    // Sanitize all inputs to prevent SQL injection and errors with quotes
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phonenumber = mysqli_real_escape_string($conn, $_POST['phonenumber']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $Technologies = mysqli_real_escape_string($conn, $_POST['Technologies']);
    $LinkedInURL = mysqli_real_escape_string($conn, $_POST['LinkedInURL']);
    $GitHubURL = mysqli_real_escape_string($conn, $_POST['GitHubURL']);
    $School = mysqli_real_escape_string($conn, $_POST['School']);
    $education = mysqli_real_escape_string($conn, $_POST['education']);
    $workexperience = mysqli_real_escape_string($conn, $_POST['workexperience']);
    $project = mysqli_real_escape_string($conn, $_POST['project']);
    $certifications = mysqli_real_escape_string($conn, $_POST['certifications']);
    $collage = mysqli_real_escape_string($conn, $_POST['collage']);
    $yearpassing = mysqli_real_escape_string($conn, $_POST['yearpassing']);
    $careerobjective = mysqli_real_escape_string($conn, $_POST['careerobjective']);
    $CGPA = mysqli_real_escape_string($conn, $_POST['CGPA']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    $LanguagesKnown = mysqli_real_escape_string($conn, $_POST['LanguagesKnown']);
    $active = 1; // Default status

    $query = "INSERT INTO resumemak 
    (fullname, email, phonenumber, address, experience, Technologies, LinkedInURL, GitHubURL, School, education, workexperience, project, certifications, collage, yearpassing, careerobjective, CGPA, skills, LanguagesKnown, active) 
    VALUES 
    ('$fullname', '$email', '$phonenumber', '$address', '$experience', '$Technologies', '$LinkedInURL', '$GitHubURL', '$School', '$education', '$workexperience', '$project', '$certifications', '$collage', '$yearpassing', '$careerobjective', '$CGPA', '$skills', '$LanguagesKnown', '$active')";

    if(mysqli_query($conn, $query)){
        // JavaScript Alert + Redirect to refresh the data view
        echo "<script>
                alert('Data Inserted Successfully');
                window.location.href='resume.php';
              </script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch the latest resume for download (jobseeker)
$latestResume = null;
$u = get_logged_in_user();
if ($u && ($u['role'] ?? '') === 'jobseeker' && !empty($u['email'])) {
    $latestEmail = $u['email'];
    $stmt = $conn->prepare("SELECT * FROM resumemak WHERE email = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $latestEmail);
    $stmt->execute();
    $latestResume = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}


include 'header.php';
include 'common_sidebar.php';
?>

<div class="main-content">
<div class="auth-container">
    <div class="auth-box split-layout" style="max-width: 1100px !important;">
        <div class="auth-image" style="background-image: url('assets/WhatsApp Image 2026-04-29 at 16.30.39.jpeg'); min-height: 800px;">
            <h3>The Perfect Resume</h3>
            <p>Build a professional resume step by step that highlights your best achievements and skills.</p>
        </div>
        
        <div class="auth-form-side">
            <h2>📄 Resume Maker</h2>

            <div style="text-align: center; margin-bottom: 20px; font-weight: bold; color: var(--accent-color);">
                Step <span id="stepNumber">1</span> of 5
            </div>

            <form method="POST" action="resume.php">
                
                <div class="step" id="step0" style="display: block;">
                    <div class="form-group"><label>Full Name</label><input type="text" name="fullname" required></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
                    <div class="form-group"><label>Phone Number</label><input type="text" name="phonenumber"></div>
                    <div class="form-group"><label>Address</label><textarea name="address"></textarea></div>
                </div>

                <div class="step" id="step1" style="display: none;">
                    <div class="form-group"><label>Career Objective</label><textarea name="careerobjective"></textarea></div>
                    <div class="form-group"><label>Experience Synopsis</label><textarea name="experience"></textarea></div>
                    <div class="form-group"><label>Work Experience</label><textarea name="workexperience"></textarea></div>
                </div>

                <div class="step" id="step2" style="display: none;">
                    <div class="form-group"><label>School</label><input type="text" name="School"></div>
                    <div class="form-group"><label>College</label><input type="text" name="collage"></div>
                    <div class="form-group"><label>Education Type</label><input type="text" name="education"></div>
                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;"><label>Year Passing</label><input type="text" name="yearpassing"></div>
                        <div class="form-group" style="flex: 1;"><label>CGPA</label><input type="text" name="CGPA"></div>
                    </div>
                </div>

                <div class="step" id="step3" style="display: none;">
                    <div class="form-group"><label>Skills</label><input type="text" name="skills"></div>
                    <div class="form-group"><label>Technologies</label><input type="text" name="Technologies"></div>
                    <div class="form-group"><label>Languages Known</label><input type="text" name="LanguagesKnown"></div>
                    <div class="form-group"><label>Projects</label><textarea name="project"></textarea></div>
                    <div class="form-group"><label>Certifications</label><textarea name="certifications"></textarea></div>
                </div>

                <div class="step" id="step4" style="display: none;">
                    <div class="form-group"><label>LinkedIn URL</label><input type="text" name="LinkedInURL"></div>
                    <div class="form-group"><label>GitHub URL</label><input type="text" name="GitHubURL"></div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 25px;">

                    <button type="button" class="btn-submit" style="width: auto; padding: 10px 25px; background: rgba(255,255,255,0.1); border: 1px solid var(--glass-border); box-shadow: none;" onclick="prevStep()">Previous</button>

                    
                    <button type="button" class="btn-submit" style="width: auto; padding: 10px 25px;" onclick="nextStep()" id="nextBtn">Next</button>
                    
                    <button type="submit" name="submit" class="btn-submit" style="display:none; width: auto; padding: 10px 25px;" id="submitBtn">Submit Resume</button>                </div>

            </form>

            <?php if (!empty($latestResume)): ?>
                <div style="margin-top: 20px; text-align: center;">
                    <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
                        <a
                            class="btn-submit"
                            style="display:inline-block; text-decoration:none; background: rgba(255,255,255,0.12); border: 1px solid var(--glass-border); padding: 10px 25px;"
                            href="download_resume.php"
                            onclick="event.preventDefault();
                                const form = document.getElementById('resumeDownloadForm');
                                form.querySelector('input[name=resume_id]').value = '<?php echo (int)($latestResume['id'] ?? 0); ?>';
                                form.querySelector('input[name=email]').value = '<?php echo htmlspecialchars($latestResume['email'] ?? '', ENT_QUOTES); ?>';
                                form.querySelector('input[name=output]').value = 'txt';
                                form.submit();
                            ">
                            ⬇️ Download TXT
                        </a>
                        <a
                            class="btn-submit"
                            style="display:inline-block; text-decoration:none; background: rgba(255,255,255,0.12); border: 1px solid var(--glass-border); padding: 10px 25px;"
                            href="download_resume.php"
                            onclick="event.preventDefault();
                                const form = document.getElementById('resumeDownloadForm');
                                form.querySelector('input[name=resume_id]').value = '<?php echo (int)($latestResume['id'] ?? 0); ?>';
                                form.querySelector('input[name=email]').value = '<?php echo htmlspecialchars($latestResume['email'] ?? '', ENT_QUOTES); ?>';
                                form.querySelector('input[name=output]').value = 'pdf';
                                form.submit();
                            ">
                            ⬇️ Download PDF
                        </a>
                    </div>

                    <form id="resumeDownloadForm" method="POST" action="download_resume.php" style="display:none;">
                        <input type="hidden" name="output" value="">

                        <input type="hidden" name="resume_id" value="">
                        <input type="hidden" name="email" value="">
                    </form>
                </div>
            <?php endif; ?>

            </div>
    </div>
</div>
</div>


<script>
let currentStep = 0;
const steps = document.querySelectorAll(".step");
const stepNumber = document.getElementById("stepNumber");
const submitBtn = document.getElementById("submitBtn");
const nextBtn = document.getElementById("nextBtn");

function showStep(step){
    steps.forEach((s,index)=>{
        s.style.display = (index === step) ? "block" : "none";
    });

    stepNumber.innerText = step + 1;

    if(step === steps.length - 1){
        submitBtn.style.display = "inline-block";
        nextBtn.style.display = "none";
    } else {
        submitBtn.style.display = "none";
        nextBtn.style.display = "inline-block";
    }
}

function nextStep(){
    if(currentStep < steps.length - 1){
        currentStep++;
        showStep(currentStep);
    }
}

function prevStep(){
    if(currentStep > 0){
        currentStep--;
        showStep(currentStep);
    }
}
</script>

<?php include 'footer.php'; ?>
