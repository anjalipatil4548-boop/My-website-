<?php
require_once 'auth.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
$message = "";
$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'jobseeker';

    $result = login($email, $password, $role);
    
    if ($result['success']) {
        $redirect = ($role === 'jobseeker') ? 'jobseeker.php' : 'employeer.php';
        header("Location: $redirect");
        exit;
    } else {
        $login_error = $result['message'] ?? 'Login failed. Please check your credentials.';
    }
}

$redirect_url = $_GET['redirect'] ?? '';
include 'header.php';
?>

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-image" style="background-image: url('assets/login_bg.png');">
            <h3>Secure Access</h3>
            <p>Sign in to access your dashboard, applications, and opportunities.</p>
        </div>
        
        <div class="auth-form-side">
            <h2>Welcome Back</h2>
            
            <?php if ($login_error): ?>
                <div class="glass-panel p-4 mb-4 text-danger"><?php echo htmlspecialchars($login_error); ?></div>
            <?php endif; ?>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Login as:</label>
                <div class="flex gap-3">
                    <button type="button" onclick="showLoginForm('jobseeker')" class="role-btn active" id="btn-jobseeker">👨‍💼 Job Seeker</button>
                    <button type="button" onclick="showLoginForm('company')" class="role-btn" id="btn-company">🏢 Company</button>
                </div>

            <form id="loginForm" method="POST">
                <input type="hidden" name="role" id="loginRole" value="jobseeker">
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter your email" required autofocus>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn-submit">Sign In</button>
            </form>
        
            <div class="auth-links">
                <p>New user? <a href="reg.php">Create Account</a></p>
            </div>
    </div>

<script>
function showLoginForm(role) {
    document.getElementById('loginRole').value = role;
    
    document.querySelectorAll('.role-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('btn-' + role).classList.add('active');
}
</script>

<?php include 'footer.php'; ?>
