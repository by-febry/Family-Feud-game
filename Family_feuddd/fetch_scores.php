<?php
include 'config/connection.php';

// Fetch top 5 scores from the database
$sql = "SELECT team_name, score FROM game_scores ORDER BY score DESC LIMIT 5"; // Adjust table name and field names as needed
$result = mysqli_query($conn, $sql);

$scores = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $scores[] = $row; // Add each score to the array
    }
}

// Return scores as a JSON response
header('Content-Type: application/json');
echo json_encode($scores);
?>