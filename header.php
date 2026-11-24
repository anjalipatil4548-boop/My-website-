<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAREER MITRA</title>

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <script src="toggle.js" defer></script>

</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <a href="index.php" class="logo">
        <span class="site-logo-text">CAREER MITRA</span>
    </a>
    <div class="nav-links">
       
        
   

        <?php
        require_once 'auth.php';
        $user = get_logged_in_user();
        if ($user) {
            echo '<span class="user-name">Hi, ' . htmlspecialchars($user['name']) . '!</span>';
            echo '<a href="?logout=1" onclick="if(confirm(\'Logout?\')){location.href=\'auth.php?logout\';return false;}" class="btn-primary ml-2">Logout</a>';
        } else {
            echo '<a href="login.php" class="btn-outline">Sign In</a>';
            echo '<a href="reg.php" class="btn-primary">Register</a>';
        }
        ?>
    </div>
</nav>
