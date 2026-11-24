<?php
/**
 * Job Portal Authentication System
 * Centralized secure auth functions with prepared statements
 */

function db_connect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jobportal_demo"; 

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

function register_jobseeker($first_name, $last_name, $email, $plain_password, $phone = '') {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT id FROM jobseekers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return ['success' => false, 'message' => 'Email already registered'];
    }

    $password_hash = password_hash($plain_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO jobseekers (first_name, last_name, email, password_hash, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $password_hash, $phone);
    
    if ($stmt->execute()) {
        $user_id = $conn->insert_id;
        return ['success' => true, 'message' => 'Jobseeker registered', 'user_id' => $user_id, 'role' => 'jobseeker'];
    }
    return ['success' => false, 'message' => 'Registration failed'];
}

function register_company($company_name, $email, $plain_password, $phone = '') {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT id FROM companies WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return ['success' => false, 'message' => 'Email already registered'];
    }

    $password_hash = password_hash($plain_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO companies (company_name, email, password_hash, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $company_name, $email, $password_hash, $phone);
    
    if ($stmt->execute()) {
        $company_id = $conn->insert_id;
        return ['success' => true, 'message' => 'Company registered', 'user_id' => $company_id, 'role' => 'company'];
    }
    return ['success' => false, 'message' => 'Registration failed'];
}

function login($email, $plain_password, $role) {
    
    $conn = db_connect();
    $table = ($role === 'jobseeker') ? 'jobseekers' : 'companies';
    // Use proper escaping for the CONCAT fields
    //echo $email;;
    //echo $plain_password;
    //echo $role; exit;
    $name_field = ($role === 'jobseeker') ? "CONCAT(first_name, ' ', last_name)" : "company_name";
    //echo $ko = "SELECT id, email, password_hash, $name_field as name FROM $table WHERE email = ?"; exit;
    $stmt = $conn->prepare("SELECT id, email, password_hash, $name_field as name FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($plain_password, $user['password_hash'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $role;
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            return ['success' => true, 'user' => $user];
        }
    }
    return ['success' => false, 'message' => 'Invalid credentials'];
}

function logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();
    header('Location: index.php');
    exit;
}

// FIXED SYNTAX BELOW
function get_logged_in_user() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['user_id'], $_SESSION['role'])) {
        return [
            'id' => $_SESSION['user_id'],
            'role' => $_SESSION['role'],
            'name' => $_SESSION['user_name'] ?? '',
            'email' => $_SESSION['email'] ?? ''
        ];
    }
    return null;
}

function is_logged_in($required_role = null) {
    // Corrected to call get_logged_in_user() instead of get_current_user()
    $user = get_logged_in_user(); 
    if (!$user) return false;
    if ($required_role && $user['role'] !== $required_role) return false;
    return true;
}

// Handle logout
if (isset($_GET['logout']) || isset($_POST['logout'])) {
    logout();
}

function require_login($required_role = null) {
    if (!is_logged_in($required_role)) {
        header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}
?>
