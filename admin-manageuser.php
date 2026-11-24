<?php include 'admin_header.php'; include '../db.php'; ?>

<h2>Manage Users</h2>

<table>
<tr>
  <th>Name</th>
  <th>Email</th>
  <th>Resume</th>
  <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM users");

while($row = $result->fetch_assoc()) {
  echo "<tr>
    <td>{$row['name']}</td>
    <td>{$row['email']}</td>
    <td>
      <a href='download_resume.php?file={$row['resume']}'>Download</a>
    </td>
    <td>
      <a href='delete_user.php?id={$row['id']}'>Delete</a>
    </td>
  </tr>";
}
?>

</table>

<?php 