<?php
// Local XAMPP settings. Update these values when uploading to free hosting.
$host = "localhost";
$username = "root";
$password = "";
$database = "library_system";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Database connection failed. Check the settings in db.php.");
}
$conn->set_charset("utf8mb4");
?>
