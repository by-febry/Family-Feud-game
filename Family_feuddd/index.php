<?php
include 'config/connection.php';

$error_message = ""; // Initialize error message variable

// Insert new team names when the form is submitted
if (isset($_POST['submit'])) {
    $teamA = $_POST['teamA_name'];
    $teamB = $_POST['teamB_name'];
    $initialScore = 0; // Set an initial score of 0

    // Prepare SQL queries to insert both teams
    $sqlA = "INSERT INTO scores (team_name, score) VALUES ('$teamA', $initialScore)";
    $sqlB = "INSERT INTO scores (team_name, score) VALUES ('$teamB', $initialScore)";

    // Execute the queries and check for errors
    if (mysqli_query($conn, $sqlA) && mysqli_query($conn, $sqlB)) {
        echo "Both teams added successfully!";
    } else {
        // Display the error message if something goes wrong
        echo "Error inserting data: " . mysqli_error($conn);
    }
}

// Fetch current teams and scores from the database, sorted by created_at DESC
$query = "SELECT team_name, score FROM scores ORDER BY score DESC LIMIT 5";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        h1 {
            margin-top: 50px;
            color: #343a40;
            font-size: 2.5rem;
        }

        form {
            display: inline-block;
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            margin: 5px 0;
            border: 2px solid #007bff;
            border-radius: 4px;
            width: 250px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #0056b3;
            outline: none;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            margin-top: 20px;
            width: 60%;
            border-collapse: collapse;
            margin: 50px auto;
        }

        table, th, td {
            border: 1px solid #007bff;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<h1>Enter Team Names</h1>
<form method="POST" action="index.php">
    <input type="text" id="teamA" name="teamA_name" placeholder="Team A Name" required><br>
    <input type="text" id="teamB" name="teamB_name" placeholder="Team B Name" required><br><br>
    <button type="submit" name="submit">Add Teams</button>
    <button type="button" onclick="location.href='actualgame.php'">Proceed to Game</button> <!-- Proceed to Game Button -->
</form>

<h2>LEADERBOARDS TOP 5</h2>
<!-- Displaying the teams and scores -->
<?php if (mysqli_num_rows($result) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Team Name</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['team_name']; ?></td>
                    <td><?php echo $row['score']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No teams available yet. Please add new teams.</p>
<?php endif; ?>

</body>
</html>
