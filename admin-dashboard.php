<?php include 'admin_header.php'; include '../db.php'; ?>

<h1>Dashboard</h1>

<?php
$users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$jobs = $conn->query("SELECT COUNT(*) as total FROM jobs")->fetch_assoc()['total'];
$companies = $conn->query("SELECT COUNT(*) as total FROM companies")->fetch_assoc()['total'];
?>

<div class="cards">
  <div class="card">Users: <?php echo $users; ?></div>
  <div class="card">Jobs: <?php echo $jobs; ?></div>
  <div class="card">Companies: <?php echo $companies; ?></div>
</div>

<?php 