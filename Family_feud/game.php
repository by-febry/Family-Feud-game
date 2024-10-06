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

// Fetch teams and scores
$sql = "SELECT team_id,team_name, COALESCE(gs.score, 0) AS score
        FROM teams 
        LEFT JOIN game_scores gs ON team_id = gs.team_id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Family Feud Game</title>
</head>
<body>
    <h1>Team Scores</h1>
    <table border="1">
        <tr>
            <th>Team Name</th>
            <th>Score</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['team_name']) . "</td>
                        <td>" . htmlspecialchars($row['score']) . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No teams found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
