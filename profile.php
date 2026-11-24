<?php
require_once 'config.php';

if($_POST)
{
    $fullname=$_POST['fullname'];
    $email =$_POST['email'];
    $phonenumber=$_POST['phonenumber'];
    $softskills=$_POST['softskills'];
    $University=$_POST['University'];
    $langauge=$_POST['langauge'];
    $Technologies=$_POST['Technologies'];
    $SocialMediaProfiless=$_POST['SocialMediaProfiles'];
    $ShortDescription=$_POST['ShortDescription'];
    $LongDescription=$_POST['LongDescription'];
    $yearpassing=$_POST['yearpassing'];

    $insertquery = "INSERT INTO myprofile 
    (fullname, email, phonenumber, softskills, University, langauge, Technologies, SocialMediaProfiles, ShortDescription, LongDescription, yearpassing, createdate, modifydate) 
    VALUES 
    ('$fullname','$email','$phonenumber','$softskills','$University','$langauge','$Technologies','$SocialMediaProfiless','$ShortDescription','$LongDescription','$yearpassing',NOW(),NOW())";

    if($conn->query($insertquery) === TRUE){
        echo "<script>alert('Profile inserted successfully');</script>";
    } else {
        echo "<script>alert('Error: ".$conn->error."');</script>";
    }
}
include 'header.php';
?>

<?php include 'common_sidebar.php'; ?>

<div class="main-content">
<div class="auth-container">
    <div class="auth-box split-layout" style="max-width: 1100px !important;">
        <div class="auth-image" style="background-image: url('assets/jobseeker_bg.png'); min-height: 800px;">
            <h3>Your Identity</h3>
            <p>Craft a compelling profile that catches the eyes of top recruiters globally.</p>
        </div>
        
        <div class="auth-form-side">
            <h2>👤 My Profile</h2>

            <div style="display: flex; justify-content: center; margin-bottom: 25px;">
                <img id="profile-pic" src="https://via.placeholder.com/130" style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 4px solid var(--accent-color); cursor: pointer; transition: transform 0.3s;" onclick="document.getElementById('pic-upload').click()">
            </div>
            <input type="file" id="pic-upload" accept="image/*" style="display:none" onchange="previewPic(event)">

            <form method="POST">
                <!-- Personal Details -->
                <h3 style="margin-top: 20px; margin-bottom: 15px; color: var(--accent-color); border-bottom: 1px solid var(--glass-border); padding-bottom: 5px;">Personal Details</h3>
                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Full Name</label>
                        <input type="text" name="fullname" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phonenumber" required>
                </div>

                <!-- Education -->
                <h3 style="margin-top: 20px; margin-bottom: 15px; color: var(--accent-color); border-bottom: 1px solid var(--glass-border); padding-bottom: 5px;">Education</h3>
                <div class="form-group">
                    <label>University / College</label>
                    <input type="text" name="University">
                </div>
                <div class="form-group">
                    <label>Passing Year</label>
                    <input type="text" name="yearpassing">
                </div>
                <div class="form-group">
                    <label>Upload Degree Certificate</label>
                    <input type="file" accept=".pdf,.doc,.docx" style="padding: 11px;">
                </div>

                <!-- Skills & Languages -->
                <h3 style="margin-top: 20px; margin-bottom: 15px; color: var(--accent-color); border-bottom: 1px solid var(--glass-border); padding-bottom: 5px;">Skills & Languages</h3>
                <div class="form-group">
                    <label>Soft Skills</label>
                    <input type="text" name="softskills">
                </div>
                <div class="form-group">
                    <label>Technologies</label>
                    <input type="text" name="Technologies">
                </div>
                <div class="form-group">
                    <label>Languages</label>
                    <input type="text" name="langauge">
                </div>

                <!-- Social Media & Resumes -->
                <h3 style="margin-top: 20px; margin-bottom: 15px; color: var(--accent-color); border-bottom: 1px solid var(--glass-border); padding-bottom: 5px;">Social Media & Resume</h3>
                <div class="form-group">
                    <label>Social Media Profiles (LinkedIn / GitHub)</label>
                    <input type="text" name="SocialMediaProfiles" placeholder="Profile URLs">
                </div>
                <div class="form-group">
                    <label>Upload Resume</label>
                    <input type="file" accept=".pdf,.doc,.docx" style="padding: 11px;">
                </div>

                <!-- Profile Description -->
                <h3 style="margin-top: 20px; margin-bottom: 15px; color: var(--accent-color); border-bottom: 1px solid var(--glass-border); padding-bottom: 5px;">Profile Description</h3>
                <div class="form-group">
                    <label>Short Title / Description</label>
                    <input type="text" name="ShortDescription">
                </div>
                <div class="form-group">
                    <label>Long Description</label>
                    <textarea name="LongDescription" rows="4" placeholder="Detailed professional description"></textarea>
                </div>

                <button type="submit" class="btn-submit">Save Profile</button>
            </form>
        </div>
    </div>
</div>
</div>

<script>
function previewPic(event){
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('profile-pic').src = reader.result;
    }
    if(event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>

<?php include 'footer.php'; ?>
