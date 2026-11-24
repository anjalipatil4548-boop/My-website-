<?php
$conn = new mysqli("localhost", "root", "", "jobportal_demo");
$message = "";
$user_role = $_POST['role'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $user_role) {
    echo $email = trim($_POST['email']);

    echo $password = $_POST['password'];
    echo $phone = $_POST['phone'] ?? '';
    echo $pass_hash = password_hash($password, PASSWORD_DEFAULT);
      
  /*  if (strlen($password) < 6) {
        $message = "error";
        $error_msg = "Password too short";
    } else */
    if ($user_role === 'jobseeker') {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
       echo $sql = "INSERT INTO jobseekers (first_name, last_name, email, password_hash, phone) 
       VALUES ('$first_name', '$last_name', '$email', '$pass_hash', '$phone')";
        
       if ($conn->query($sql)) {
            $message = "success";
            $success_msg = "Jobseeker ID " . $conn->insert_id . " registered!";
        } else {
            $error_msg = $conn->error;
        }
    } elseif ($user_role === 'company') {
        $company_name = trim($_POST['company_name']);
         $email_1 = trim($_POST['email_1']);
          $phone_1 = trim($_POST['phone_1']);
        $password_1 = $_POST['password_1'];
    
     $pass_hash_1 = password_hash($password_1, PASSWORD_DEFAULT);
     $phone_1 = trim($_POST['phone_1']);
        $sql = "INSERT INTO companies (company_name, email, password_hash, phone) VALUES ('$company_name', '$email_1', 
        '$pass_hash_1', '$phone_1')";
        if ($conn->query($sql)) {
            $message = "success";
            $success_msg = "Company ID " . $conn->insert_id . " registered!";
        } else {
            $error_msg = $conn->error;
        }
    }
}


include 'header.php';



// Debug: Log registration attempt
if (isset($result)) {
    error_log("Reg attempt: role=$user_role, email=" . ($email ?? 'none') . ", success=" . ($result['success'] ?? 'no result'));
} else {
    error_log("Reg attempt: No result - role=$user_role");
}


?>

<div class="auth-container">
    <div class="auth-box split-layout">
        <div class="auth-image" style="background-image: url('assets/login_bg.png');">
            <h3>Join CareerWave</h3>
            <p>Create your new account as a job seeker or company to unlock full platform features.</p>
        </div>
        
        <div class="auth-form-side">
            <h2>Create Account</h2>
            
            <?php if ($message): ?>
                <?php if ($message == "success"): ?>
                    <div class='msg success'><?php echo $success_msg ?? 'Account created successfully! <a href="login.php">Login here</a>'; ?></div>
                    
                    <!-- Show registered user data -->
                    <?php
$conn = new mysqli("localhost", "root", "", "jobportal_demo");
                    if ($user_role === 'jobseeker') {
                        $latest = $conn->query("SELECT * FROM jobseekers ORDER BY id DESC LIMIT 1");
                    } else {
                        $latest = $conn->query("SELECT * FROM companies ORDER BY id DESC LIMIT 1");
                    }
                    if ($latest && $row = $latest->fetch_assoc()) {
                        echo "<div style='background: rgba(16,185,129,0.2); padding: 15px; border-radius: 8px; margin-top: 10px; font-size: 14px;'>";
                        if ($user_role === 'jobseeker') {
                            echo "✅ Jobseeker Created: " . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . " (" . $row['email'] . ")";
                        } else {
                            echo "✅ Company Created: " . htmlspecialchars($row['company_name']) . " (" . $row['email'] . ")";
                        }
                        echo "<br>ID: " . $row['id'] . " | " . $row['created_at'];
                        echo "</div>";
                    }
                    $conn->close();
                    ?>

                <?php else: ?>
                    <div class='msg error'><?php echo $error_msg ?? 'Error creating account. Please try again.'; ?></div>
                <?php endif; ?>
            <?php endif; ?>


            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600;">Register as:</label>
                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="showForm('jobseeker')" class="role-btn <?php echo ($user_role === 'jobseeker') ? 'active' : ''; ?>">👨‍💼 Job Seeker</button>
                    <button type="button" onclick="showForm('company')" class="role-btn <?php echo ($user_role === 'company') ? 'active' : ''; ?>">🏢 Company</button>
                </div>
            </div>

            <form id="regForm" method="POST">
                <input type="hidden" name="role" id="regRole" value="jobseeker">
                
                <!-- Job Seeker Form -->
                <div id="jobseekerForm" class="role-form">
                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>First Name</label>
                            <input type="text" name="first_name" placeholder="First Name">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Last Name</label>
                            <input type="text" name="last_name" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Email Address">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Create a password (8+ chars)">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone" placeholder="Phone Number">
                    </div>
                </div>

                <!-- Company Form -->
                <div id="companyForm" class="role-form" style="display: none;">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" name="company_name" placeholder="Your Company Name">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email_1" placeholder="Company Email">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password_1" placeholder="Create a password ">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone_1" placeholder="Company Phone">
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">Register</button>
            </form>

            <style>
            .role-btn { 
                flex: 1; padding: 12px 20px; border: none; border-radius: 12px; 
                background: rgba(99,102,241,0.1); color: var(--text-primary); 
                font-weight: 600; cursor: pointer; transition: all 0.3s; 
                border: 2px solid transparent;
            }
            .role-btn:hover { background: rgba(99,102,241,0.2); }
            .role-btn.active { 
                background: rgba(99,102,241,0.3); border-color: #6366f1; 
            }
            .role-form { transition: opacity 0.3s; }
            .role-form.hidden { display: none; }
            </style>

            <script>
            function showForm(role) {
                document.getElementById('jobseekerForm').style.display = role === 'jobseeker' ? 'block' : 'none';
                document.getElementById('companyForm').style.display = role === 'company' ? 'block' : 'none';
                document.getElementById('regRole').value = role;
            }
            showForm('jobseeker');
            </script>

            <div class="auth-links">
                <p>Already have an account? <a href="login.php">Sign In</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

