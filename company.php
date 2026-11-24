<?php
require_once 'auth.php';
require_login('company');
require_once 'config.php';

// 1. DATABASE LOGIC: Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    // Sanitize inputs to prevent SQL errors (like apostrophes in names)
    $companyname = mysqli_real_escape_string($con, $_POST['companyname']);
    $industry = mysqli_real_escape_string($con, $_POST['industry'] ?? '');
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $zipcode = mysqli_real_escape_string($con, $_POST['zipcode']);
    $headofficeloaction = mysqli_real_escape_string($con, $_POST['headofficeloaction']);
    $shortdesc = mysqli_real_escape_string($con, $_POST['shortdescription']);
    $longdesc = mysqli_real_escape_string($con, $_POST['longdescription']);
    
    // Handle File Upload
    $companylogo = $_FILES['companylogo']['name'];
    $tmp_name = $_FILES['companylogo']['tmp_name'];
    $path = "uploads/" . $companylogo;
    
    // Create uploads folder if it doesn't exist
    if (!is_dir('uploads')) {
        mkdir('uploads');
    }
    move_uploaded_file($tmp_name, $path);

    // SQL Query - Removed 'id' assuming it's Auto-Increment
    $insertquery = "INSERT INTO `employer_registration` 
    (`companyname`, `companylogo`, `industry`, `address`, `city`, `state`, `country`, `zipcode`, `headofficeloaction`, `shortdescription`, `longdescription`, `active`, `createdate`, `modifydate`)
    VALUES 
    ('$companyname', '$companylogo', '$industry', '$address', '$city', '$state', '$country', '$zipcode', '$headofficeloaction', '$shortdesc', '$longdesc', 1, NOW(), NOW())";

    // 2. CRITICAL FIX: Execute the query
    if (mysqli_query($con, $insertquery)) {
        echo "<script>alert('Company Registered Successfully!'); window.location.href='company.php';</script>";
        exit();
    } else {
        // This will show you exactly if a column name is misspelled
        die("Database Error: " . mysqli_error($con));
    }
}

include 'header.php';
include 'company_sidebar.php';
?>

<div class="main-content">
<div class="auth-container">
    <div class="auth-box split-layout" style="max-width: 1100px !important;">
        <div class="auth-image" style="background-image: url('assets/employer_bg.png'); min-height: 800px;">
            <h3>Scale Your Business</h3>
            <p>Register your company on Nexus to get access to top-tier talent matching your specific industry needs.</p>
        </div>

        <div class="auth-form-side">
            <h2>🏢 Company Registration</h2>

            <form action="" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="companyname" required>
                </div>

                <div class="form-group">
                    <label>Company Logo</label>
                    <input type="file" name="companylogo" required style="padding: 11px;">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" required rows="3"></textarea>
                </div>

                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>City</label>
                        <input type="text" name="city" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>State</label>
                        <input type="text" name="state" required>
                    </div>
                </div>

                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Country</label>
                        <input type="text" name="country" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Zip Code</label>
                        <input type="text" name="zipcode" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Company Size</label>
                    <select name="industry" required> <option value="" disabled selected>Select Size</option>
                        <option value="1-10">1-10 Employees</option>
                        <option value="11-50">11-50 Employees</option>
                        <option value="51-200">51-200 Employees</option>
                        <option value="201-500">201-500 Employees</option>
                        <option value="500+">500+ Employees</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Head Office Location</label>
                    <input type="text" name="headofficeloaction">
                </div>

                <div class="form-group">
                    <label>Short Description</label>
                    <textarea name="shortdescription" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Long Description</label>
                    <textarea name="longdescription" rows="5"></textarea>
                </div>

                <button type="submit" class="btn-submit">Register Company</button>
            </form>
        </div>
    </div>
</div>
</div>

<?php include 'footer.php'; ?>