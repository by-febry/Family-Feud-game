<?php
$servername = "localhost"; // Your MySQL server
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "game"; // Your database name

// Create connection using mysqli
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    // Custom error message
    die("Connection failed: " . mysqli_connect_error());
}

// Optionally, you can print a success message
// echo "Connected successfully to the database.";
?>
