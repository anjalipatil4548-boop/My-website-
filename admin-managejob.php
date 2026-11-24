<?php include 'admin_header.php'; include '../db.php'; ?>

<h2>Jobs</h2>

<table>
<tr>
  <th>Title</th>
  <th>Company</th>
  <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM jobs");

while($row = $result->fetch_assoc()) {
  echo "<tr>
    <td>{$row['title']}</td>
    <td>{$row['company']}</td>
    <td>
      <a href='delete_job.php?id={$row['id']}'>Delete</a>
    </td>
  </tr>";
}
?>

</table>

<?php