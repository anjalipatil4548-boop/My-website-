<?php include 'admin_header.php'; include '../db.php'; ?>

<h2>Companies</h2>

<table>
<tr>
  <th>Name</th>
  <th>Email</th>
  <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM companies");

while($row = $result->fetch_assoc()) {
  echo "<tr>
    <td>{$row['name']}</td>
    <td>{$row['email']}</td>
    <td>
      <a href='delete_company.php?id={$row['id']}'>Delete</a>
    </td>
  </tr>";
}
?>

</table>

<?php 