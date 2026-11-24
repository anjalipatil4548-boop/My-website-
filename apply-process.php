<?php
require_once 'apply_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $job = $_POST['job'];
    $cover = $_POST['cover'];
    
    // Handle resume upload
    $resume_name = $_FILES['resume']['name'];
    $resume_tmp = $_FILES['resume']['tmp_name'];
    $resume_ext = pathinfo($resume_name, PATHINFO_EXTENSION);
    $new_resume_name = uniqid() . '.' . $resume_ext;
    $upload_path = 'uploads/resumes/' . $new_resume_name;
    
    // Create uploads directory
    if (!file_exists('uploads/resumes')) {
        mkdir('uploads/resumes', 0777, true);
    }
    
    if (move_uploaded_file($resume_tmp, $upload_path)) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO applications (name, email, phone, job_title, cover_letter, resume_path, applied_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssss", $name, $email, $phone, $job, $cover, $upload_path);
        
        if ($stmt->execute()) {
            $success = "Application submitted successfully! We'll contact you soon.";
        } else {
            $error = "Error submitting application. Try again.";
        }
    } else {
        $error = "Resume upload failed.";
    }
}

header("Location: applyjob.php?" . ($success ? "success=1&msg=" . urlencode($success) : "error=1&msg=" . urlencode($error ?? "Unknown error")));
?>

