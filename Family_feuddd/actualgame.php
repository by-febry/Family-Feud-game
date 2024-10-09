<?php
include 'config/connection.php';

// Fetch team names from the database
$teamA_name = '';
$teamB_name = '';

// Query to get the latest two team names
$sql = "SELECT team_name FROM scores ORDER BY (date) DESC LIMIT 2"; // Adjust the query if necessary

$result = mysqli_query($conn, $sql);

// If there are results, assign them to the team variables
if (mysqli_num_rows($result) >= 2) {
    $row = mysqli_fetch_assoc($result);
    $teamA_name = $row['team_name']; // First team

    $row = mysqli_fetch_assoc($result);
    $teamB_name = $row['team_name']; // Second team
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Feud Game</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom styles for game status */
        #game-status {
            font-size: 2rem; /* Increase the font size */
            font-weight: bold; /* Make the text bold */
            color: #ff0000; /* Optional: Change the color for visibility */
            margin-top: 20px; /* Add some space above the status message */
        }

        /* Other existing styles */
        #results {
            font-size: 30px; /* Adjust the font size to make it larger */
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }

        #current-team {
            font-size: 22px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header class="bg-primary text-white py-3">
    <h1 class="text-center">Family Feud Game</h1>
</header>

<section id="game-intro" class="text-center mt-4">
    <p>Welcome to Family Feud! Two teams will compete to answer survey questions. The team with the highest score at the end wins!</p>
</section>

<div id="game-container" class="container my-4">
    <h2 class="text-center">Game Setup</h2>

    <!-- Current Team Display -->
    <div id="current-team" class="mb-3 text-center"></div> <!-- Display current team -->

    <!-- Question Display -->
    <div id="results" class="mb-3"></div>

    <!-- Answer Form -->
    <form id="answer-form" method="POST" class="mb-3 d-flex justify-content-center">
        <input type="text" id="user-answer" class="form-control mr-2" placeholder="Type your answer here" required style="max-width: 300px;">
        <button type="submit" class="btn btn-primary">Submit Answer</button>
    </form>

    <!-- Score Table -->
    <table class="score-table table table-bordered text-center">
        <thead>
            <tr>
                <!-- Dynamic team names fetched from the database -->
                <th id="team-a-name"><?php echo $teamA_name; ?></th>
                <th id="team-b-name"><?php echo $teamB_name; ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="score-team-a">0</td>
                <td id="score-team-b">0</td>
            </tr>
        </tbody>
    </table>

    <!-- Start Game Button -->
    <div class="text-center my-3">
        <button id="start-game" class="btn btn-success">Start Game</button>
        <button id="end-game" class="btn btn-danger">End Game</button> <!-- New End Game Button -->
    </div>

    <!-- Timer (Optional) -->
    <div id="timer" class="timer">Time Left: <span id="time-remaining">30</span> seconds</div>
    <progress id="time-progress" value="0" max="100" class="w-100"></progress>
</div>

<!-- Game Status Message -->
<div id="game-status" class="text-center"></div>

<footer class="bg-light text-center py-3">
    <h3 id="end-game-message" style="display: none;">Game Over!</h3>
</footer>

<!-- Updated Scores Section -->
<div id="updated-scores" class="container my-4 text-center"></div>

<script>
    // PHP variables to JavaScript
    let teamAName = '<?php echo $teamA_name; ?>';
    let teamBName = '<?php echo $teamB_name; ?>';

    // Start with Team A
    let currentTeam = teamAName; 
    let scoreTeamA = 0;
    let scoreTeamB = 0;
    let currentQuestionIndex = 0;

    // Function to start the game
    function startGame() {
        // Reset scores and questions
        currentQuestionIndex = 0;
        scoreTeamA = 0;
        scoreTeamB = 0;

        // Set team names in the score table
        document.getElementById('team-a-name').innerText = teamAName;
        document.getElementById('team-b-name').innerText = teamBName;

        // Initialize current team to Team A
        currentTeam = teamAName;
        document.getElementById('current-team').innerText = `Current Team: ${currentTeam}`;

        // Update UI elements
        document.getElementById('score-team-a').innerText = scoreTeamA;
        document.getElementById('score-team-b').innerText = scoreTeamB;
        document.getElementById('game-status').innerText = "Game Start!"; // Game status message
        document.getElementById('answer-form').style.display = "flex"; // Show answer form

        // Show the first question
        showNextQuestion();
    }

    // Event listener for the "Start Game" button
    document.getElementById('start-game').addEventListener('click', startGame);

    // Questions with scores
    const questions = [
        {
            question: "Magbigay ng salitang pwedeng pang-describe sa saging?",
            answers: [
                { answer: "Mahaba", score: 43 },
                { answer: "Masarap", score: 10 },
                { answer: "Matamis", score: 9 },
                { answer: "Dilaw", score: 6 },
                { answer: "Malambot", score: 4 },
                { answer: "Kurbado", score: 4 }
            ]
        },
        {
            question: "Mahirap maging (blank).",
            answers: [
                { answer: "Pogi", score: 60 },
                { answer: "Mahirao", score: 17 },
                { answer: "Mabait", score: 4 },
                { answer: "Pangit", score: 4 },
                { answer: "Single", score: 3 }
            ]
        },
        {
            question: "Ano ang karaniwang ginagawa sa dilim?",
            answers: [
                { answer: "Natutulog", score: 21 },
                { answer: "Kiss / Sexy time", score: 16 },
                { answer: "Nangangapa", score: 11 },
                { answer: "Nagtatago", score: 11 },
                { answer: "Nagse-cellphone", score: 7 }
            ]
        },
        {
            question: "Anong mga pambobola ang sinasabi ng lalaki sa babae?",
            answers: [
                { answer: "Ang ganda mo", score: 32 },
                { answer: "Ikaw lang wala na", score: 31 },
                { answer: "Di kita iiwan", score: 8 },
                { answer: "I miss you", score: 8 },
                { answer: "Ang sexy mo", score: 3 }
            ]
        },
        {
            question: "Magbigay ng tunog na nalilikha ng katawan?",
            answers: [
                { answer: "Utot", score: 24 },
                { answer: "Boses", score: 14 },
                { answer: "Sipol", score: 10 },
                { answer: "Hilik", score: 9 },
                { answer: "Palakpak", score: 8 }
            ]
        },
        {
            question: "Matutuwa ka kung ano ang mabango sa lalaki?",
            answers: [
                { answer: "Buhok / Ulo", score: 32 },
                { answer: "Kilikili", score: 18 },
                { answer: "Leeg", score: 12 },
                { answer: "Bibig / Hininga", score: 10 },
                { answer: "Paa", score: 9 }
            ]
        },
        {
            question: "Ano ang mga senyales ng naliligaw?",
            answers: [
                { answer: "Bumibili ng regalo", score: 22 },
                { answer: "Laging tumatawag", score: 15 },
                { answer: "Laging may oras", score: 11 },
                { answer: "Tumingin / Magsalita", score: 9 },
                { answer: "Magpalakad", score: 5 }
            ]
        },
    ];

    // Function to show the next question
    function showNextQuestion() {
        if (currentQuestionIndex < questions.length) {
            // Display the current question
            document.getElementById('results').innerText = questions[currentQuestionIndex].question;
            startTimer(); // Start timer for the question
        } else {
            endGame(); // End the game if no more questions
        }
    }

    // Timer functionality
    let timerInterval;
    function startTimer() {
        let timeLeft = 30; // 30 seconds for each question
        document.getElementById('time-remaining').innerText = timeLeft;
        document.getElementById('time-progress').value = 0; // Reset progress bar

        timerInterval = setInterval(() => {
            timeLeft--;
            document.getElementById('time-remaining').innerText = timeLeft;
            document.getElementById('time-progress').value += 3.33; // Update progress bar (100/30)

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                currentQuestionIndex++; // Move to the next question
                showNextQuestion(); // Show the next question
            }
        }, 1000);
    }

    // Event listener for answer submission
    document.getElementById('answer-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const userAnswer = document.getElementById('user-answer').value.toLowerCase().trim();
        const correctAnswers = questions[currentQuestionIndex].answers;

        // Check if the answer matches any correct answers
        let foundAnswer = false;
        correctAnswers.forEach(answer => {
            if (userAnswer === answer.answer.toLowerCase()) {
                foundAnswer = true;
                if (currentTeam === teamAName) {
                    scoreTeamA += answer.score; // Add score to Team A
                    document.getElementById('score-team-a').innerText = scoreTeamA; // Update score display
                } else {
                    scoreTeamB += answer.score; // Add score to Team B
                    document.getElementById('score-team-b').innerText = scoreTeamB; // Update score display
                }
                document.getElementById('results').innerText = `Correct! ${answer.score} points!`;
            }
        });

        // If answer was not found, display a message
        if (!foundAnswer) {
            document.getElementById('results').innerText = 'Wrong answer, try again!';
        }

        // Switch teams for the next question
        currentTeam = currentTeam === teamAName ? teamBName : teamAName; 
        document.getElementById('current-team').innerText = `Current Team: ${currentTeam}`;

        // Prepare for the next question
        document.getElementById('user-answer').value = ''; // Clear input
        clearInterval(timerInterval); // Clear the timer
        currentQuestionIndex++; // Move to the next question
        showNextQuestion(); // Show the next question
    });

    // Function to end the game
    function endGame() {
        clearInterval(timerInterval);
        document.getElementById('answer-form').style.display = "none"; // Hide answer form
        document.getElementById('game-status').innerText = `Game Over! Final Scores - ${teamAName}: ${scoreTeamA}, ${teamBName}: ${scoreTeamB}`;
        document.getElementById('end-game-message').style.display = "block"; // Show game over message

        // Fetch and display updated scores from the database
        fetchUpdatedScores();

        // Update scores in the database
        updateScoresInDatabase();
    }

    // Function to update scores in the database
    function updateScoresInDatabase() {
        const scores = {
            teamA: scoreTeamA,
            teamB: scoreTeamB
        };

        fetch('update_scores.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(scores) // Send scores as JSON
        })
        .then(response => response.json())
        .then(data => {
            console.log('Scores updated:', data);
        })
        .catch(error => {
            console.error('Error updating scores:', error);
        });
    }

    // Function to fetch updated scores from the database
    function fetchUpdatedScores() {
        fetch('fetch_scores.php') // Create this PHP file to handle fetching scores
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    // Build the table to display scores
                    let scoreTable = '<table class="table table-bordered text-center"><thead><tr><th>Team Name</th><th>Score</th></tr></thead><tbody>';
                    
                    data.forEach(row => {
                        scoreTable += `<tr><td>${row.team_name}</td><td>${row.score}</td></tr>`;
                    });

                    scoreTable += '</tbody></table>';
                    document.getElementById('updated-scores').innerHTML = scoreTable; // Display the scores in the designated div
                } else {
                    document.getElementById('updated-scores').innerText = 'No scores available.';
                }
            })
            .catch(error => console.error('Error fetching scores:', error));
    }

    // Event listener for the "End Game" button
    document.getElementById('end-game').addEventListener('click', endGame);
</script>
</body>
</html>
