<?php
session_start(); // Start the session

// Include the database connection
include 'connection.php';

// Initialize messages
if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = '';
}

// Function to check if the score has been uploaded correctly
function checkScoreUploaded($conn, $playerName, $expectedScore) {
    $sql = "SELECT score FROM score WHERE player_Names = ? ORDER BY game_Date DESC LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("SQL statement preparation failed: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "s", $playerName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $latestScore);
    
    if (mysqli_stmt_fetch($stmt)) {
        return $latestScore == $expectedScore; // Score has been uploaded correctly
    }
    mysqli_stmt_close($stmt);
    
    return false; // Score was not uploaded correctly
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $playerName = trim($_POST['playerName']);
    $score = trim($_POST['score']);
    
    // Error messages
    $errors = [];
    if (empty($playerName)) {
        $errors[] = "Player name is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9 ]*$/", $playerName)) {
        $errors[] = "Player name can only contain letters, numbers, and spaces.";
    }
    if (empty($score)) {
        $errors[] = "Score is required.";
    } elseif (!filter_var($score, FILTER_VALIDATE_INT) || $score < 0) {
        $errors[] = "Score must be a non-negative integer.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    } else {
        $_SESSION['playerName'] = $playerName;
        $_SESSION['gameStatus'] = 'active';
        $gameDate = date('Y-m-d');

        $sql = "INSERT INTO score (player_Names, score, game_Date) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            die("SQL statement preparation failed: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "sis", $playerName, $score, $gameDate);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Score added successfully for player: " . htmlspecialchars($playerName) . "!";
            if (checkScoreUploaded($conn, $playerName, $score)) {
                $_SESSION['message'] .= " Score upload verified successfully!";
            } else {
                $_SESSION['message'] .= " Error: Score upload verification failed.";
            }
        } else {
            $_SESSION['message'] = "Error adding score: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
}

// Display player name and game status
if (isset($_SESSION['playerName'])) {
    echo "<p>Welcome, " . htmlspecialchars($_SESSION['playerName']) . "!</p>";
    echo "<p>Current game status: " . htmlspecialchars($_SESSION['gameStatus']) . "</p>";
}

// Retrieve stored scores from the database
$sql = "SELECT player_Names, score, game_Date FROM score ORDER BY score DESC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error retrieving scores: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FamilyFeud Player Score</title>
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light background */
            color: #333; /* Text color */
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #007bff; /* Header color */
        }
        fieldset {
            border: 2px solid #007bff; /* Border color */
            border-radius: 5px; /* Rounded corners */
            padding: 20px; /* Spacing inside the fieldset */
            max-width: 400px; /* Max width of the fieldset */
            margin: 20px auto; /* Center the fieldset */
            background-color: #ffffff; /* Background color */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Shadow for depth */
        }
        label {
            display: block; /* Block layout for labels */
            margin-bottom: 5px; /* Space below labels */
            font-weight: bold; /* Bold font */
        }
        input[type="text"],
        input[type="number"] {
            width: calc(100% - 12px); /* Full width minus padding */
            padding: 10px; /* Padding inside the input */
            margin-bottom: 10px; /* Space below inputs */
            border: 1px solid #ccc; /* Border for inputs */
            border-radius: 5px; /* Rounded corners */
        }
        button {
            background-color: #007bff; /* Button color */
            color: white; /* Text color */
            border: none; /* No border */
            padding: 10px 15px; /* Padding inside the button */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
            width: 100%; /* Full width button */
        }
        button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
        a {
            text-decoration: none; /* No underline */
            color: #007bff; /* Link color */
            font-weight: bold; /* Bold link */
            float: right; /* Align logout link to the right */
        }
    </style>
    <script>
        // Function to fetch and update the leaderboard
        function updateLeaderboard() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_scores.php', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const scores = JSON.parse(this.responseText);
                    const leaderboard = document.getElementById('leaderboard');
                    leaderboard.innerHTML = ""; // Clear existing leaderboard

                    // Populate the leaderboard with new scores
                    scores.forEach(score => {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td>${score.player_Names}</td><td>${score.score}</td><td>${score.game_Date}</td>`;
                        leaderboard.appendChild(row);
                    });
                }
            };
            xhr.send();
        }

        setInterval(updateLeaderboard, 5000);
    </script>
</head>
<body>
  
<h1>Enter Player Name and Score</h1>
<a href="logout.php">Logout</a>

<fieldset>
    <!-- Form to collect player name and score -->
    <form method="POST" action="">
        <label for="playerName">Player Name:</label>
        <input type="text" id="playerName" name="playerName" required>

        <label for="score">Score:</label>
        <input type="number" id="score" name="score" required>

        <button type="submit">Submit Score</button>
    </form>
</fieldset>

<?php
// Display session message
if (!empty($_SESSION['message'])) {
    echo "<p style='color: green; text-align: center;'>" . htmlspecialchars($_SESSION['message']) . "</p>";
    // Clear the message after displaying
    unset($_SESSION['message']);
}
?>

<div id="leaderboard">
    <!-- Leaderboard will be populated here -->
    <h2>Leaderboard</h2>
    <table>
        <tr>
            <th>Player Name</th>
            <th>Score</th>
            <th>Date</th>
        </tr>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <!-- Existing scores will be added here -->
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['player_Names']); ?></td>
                    <td><?php echo htmlspecialchars($row['score']); ?></td>
                    <td><?php echo htmlspecialchars($row['game_Date']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No scores recorded yet.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
