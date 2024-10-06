<?php
// Database connection
$host = 'localhost'; // Change if your host is different
$db = 'familyfeudgame'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if team_id and score are set
if (isset($_POST['team_id']) && isset($_POST['score'])) {
    $team_id = intval($_POST['team_id']);
    $score = intval($_POST['score']);

    // Insert or update score
    $sql = "INSERT INTO game_scores (team_id, score) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE score = score + ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $team_id, $score, $score);

    if ($stmt->execute()) {
        echo "Score updated successfully.";
    } else {
        echo "Error updating score: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Team ID and score must be provided.";
}

$conn->close();
?>
