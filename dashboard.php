<?php
require_once 'auth.php';
$user = get_logged_in_user();
if (!$user) {
    header('Location: login.php');
    exit;
}

$role = $user['role'];
$redirect_url = ($role === 'jobseeker') ? 'jobseeker_dashboard.php' : 'company_dashboard.php';
header("Location: $redirect_url");
exit;
?>
