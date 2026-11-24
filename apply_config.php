<?php
// Applications Database Config - uses same DB as main app
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "jobportal_demo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!function_exists('ensure_column_exists')) {
    function ensure_column_exists($conn, $table, $column, $definition) {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
        if ($table === '' || $column === '') {
            return false;
        }

        $check_query = "SHOW COLUMNS FROM `$table` LIKE '$column'";
        $check_result = mysqli_query($conn, $check_query);
        if ($check_result && mysqli_num_rows($check_result) > 0) {
            return true;
        }

        $alter_query = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
        return mysqli_query($conn, $alter_query) !== false;
    }
}
?>
