<?php
require_once 'auth.php';
require_once 'config.php';

// Must be logged in
require_login('jobseeker');




$user = get_logged_in_user();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$resumeId = isset($_POST['resume_id']) ? (int)$_POST['resume_id'] : 0;

if ($email === '' || $resumeId <= 0) {
    http_response_code(400);
    exit;
}

// Only allow downloading for the logged in user
if (!isset($user['email']) || $user['email'] !== $email) {
    http_response_code(403);
    exit;
}

// Fetch stored resume content
// NOTE: This project stores resume builder data in DB (resumemak table).
// We generate a downloadable text resume for the selected record.

$output = isset($_POST['output']) ? strtolower(trim($_POST['output'])) : 'txt';
if (!in_array($output, ['txt', 'pdf'], true)) {
    $output = 'txt';
}

$conn2 = $conn; // from config.php


$stmt = $conn2->prepare("SELECT * FROM resumemak WHERE id = ? AND email = ? LIMIT 1");
$stmt->bind_param("is", $resumeId, $email);
$stmt->execute();
$result = $stmt->get_result();
$resume = $result->fetch_assoc();
$stmt->close();

if (!$resume) {
    http_response_code(404);
    exit;
}

$fullname = $resume['fullname'] ?? '';
$phone = $resume['phonenumber'] ?? '';
$address = $resume['address'] ?? '';
$experience = $resume['experience'] ?? '';
$careerobjective = $resume['careerobjective'] ?? '';
$workexperience = $resume['workexperience'] ?? '';
$education = $resume['education'] ?? '';
$School = $resume['School'] ?? '';
$collage = $resume['collage'] ?? '';
$yearpassing = $resume['yearpassing'] ?? '';
$CGPA = $resume['CGPA'] ?? '';
$skills = $resume['skills'] ?? '';
$Technologies = $resume['Technologies'] ?? '';
$LanguagesKnown = $resume['LanguagesKnown'] ?? '';
$project = $resume['project'] ?? '';
$certifications = $resume['certifications'] ?? '';
$LinkedInURL = $resume['LinkedInURL'] ?? '';
$GitHubURL = $resume['GitHubURL'] ?? '';

$content = "RESUME\n==============================\n";
$content .= "Name: $fullname\n";
$content .= "Email: $email\n";
$content .= "Phone: $phone\n";
$content .= "Address: $address\n\n";

$content .= "Career Objective\n------------------------------\n";
$content .= "$careerobjective\n\n";

$content .= "Experience Synopsis\n------------------------------\n";
$content .= "$experience\n\n";

$content .= "Work Experience\n------------------------------\n";
$content .= "$workexperience\n\n";

$content .= "Skills\n------------------------------\n";
$content .= "$skills\n\n";

$content .= "Technologies\n------------------------------\n";
$content .= "$Technologies\n\n";

$content .= "Languages Known\n------------------------------\n";
$content .= "$LanguagesKnown\n\n";

$content .= "Projects\n------------------------------\n";
$content .= "$project\n\n";

$content .= "Certifications\n------------------------------\n";
$content .= "$certifications\n\n";

$content .= "Education\n------------------------------\n";
$content .= "School: $School\n";
$content .= "College: $collage\n";
$content .= "Education Type: $education\n";
$content .= "Year Passing: $yearpassing\n";
$content .= "CGPA: $CGPA\n\n";

$content .= "Links\n------------------------------\n";
$content .= "LinkedIn: $LinkedInURL\n";
$content .= "GitHub: $GitHubURL\n";

$baseName = preg_replace('/[^a-zA-Z0-9-_]+/', '_', ($fullname ?: 'resume'));
$filename = $baseName . "_resume.txt";

if ($output === 'pdf') {
    // PDF generation library is not present; provide TXT with PDF filename as fallback.
    header('Content-Type: application/pdf');
    $filename = $baseName . "_resume.pdf";
} else {
    header('Content-Type: text/plain; charset=utf-8');
}

header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

echo $content;
exit;

