<?php
include 'config/connection.php';

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('log.txt', print_r($data, true)); // Log incoming data for debugging

if (isset($data['teamA']) && isset($data['teamB'])) {
    $teamA_score = $data['teamA'];
    $teamB_score = $data['teamB'];

    // Update the scores in the database
    $sqlA = "UPDATE scores SET score = $teamA_score WHERE team_name = '$teamA_name'";
    $sqlB = "UPDATE scores SET score = $teamB_score WHERE team_name = '$teamB_name'";

    $resultA = mysqli_query($conn, $sqlA);
    $resultB = mysqli_query($conn, $sqlB);

    // Check if the queries were successful
    if ($resultA && $resultB) {
        echo json_encode(['status' => 'success', 'message' => 'Scores updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update scores', 'error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>