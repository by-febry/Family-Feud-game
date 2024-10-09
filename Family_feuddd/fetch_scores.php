<?php
include 'config/connection.php';

// Fetch updated scores from the database
$sql = "SELECT team_name, score FROM scores ORDER BY score DESC"; // Adjust table name and column names as necessary
$result = mysqli_query($conn, $sql);

$scores = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $scores[] = $row; // Store each score row
    }
}

// Return scores as JSON
header('Content-Type: application/json');
echo json_encode($scores);
?>
