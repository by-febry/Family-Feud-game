<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Scores</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Top Scores</h1>

    <?php
    // Include the database connection
    include 'connection.php';

    // SQL query to get the top scores (top 10)
    $sql = "SELECT player_Names, score, game_Date FROM score ORDER BY score DESC LIMIT 10";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error retrieving top scores: " . mysqli_error($conn));
    }

    // Check if there are results
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Player Name</th><th>Score</th><th>Date</th></tr>";

        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['player_Names']) . "</td>";
            echo "<td>" . htmlspecialchars($row['score']) . "</td>";
            echo "<td>" . htmlspecialchars($row['game_Date']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No scores recorded yet.</p>";
    }

    // Close the connection
    mysqli_close($conn);
    ?>
</body>
</html>
