// Retrieve the team names from localStorage
let teamAName = localStorage.getItem('teamA') || 'Team A';
let teamBName = localStorage.getItem('teamB') || 'Team B';

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
            { answer: "Bibig / Hininga", score: 12 },
            { answer: "Dibdib", score: 4 }
        ]
    },
    {
        question: "Anong nagagawa ng bibe na kaya mo rin gawin?",
        answers: [
            { answer: "Lumangoy / Maligo", score: 38 },
            { answer: "Lumakad / Kumendeng", score: 26 },
            { answer: "Tumuka / Kumain", score: 13 },
            { answer: "Uminom", score: 6 },
            { answer: "Mag quack quack", score: 3 }
        ]
    },
    {
        question: "Sino kinakausap mo pag may problem ka sa lovelife?",
        answers: [
            { answer: "Friend", score: 51 },
            { answer: "Parents", score: 13 },
            { answer: "Kapatid", score: 6 },
            { answer: "Sarili", score: 4 },
            { answer: "Lord", score: 3 }
        ]
    }
];

let timeRemaining = 30; // Initial time in seconds
let timerInterval;

function showNextQuestion() {
    if (currentQuestionIndex < questions.length) {
        const currentQuestion = questions[currentQuestionIndex];
        document.getElementById('results').innerText = currentQuestion.question;
        currentQuestionIndex++;
        startTimer(); // Start the timer when a new question is shown
    } else {
        endGame(); // Call end game when there are no more questions
    }
}

document.getElementById('answer-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    const userAnswer = document.getElementById('user-answer').value;
    validateAnswer(userAnswer);
});

// Validate answer and update scores
function validateAnswer(answer) {
    const currentQuestion = questions[currentQuestionIndex - 1];
    const foundAnswer = currentQuestion.answers.find(a => a.answer.toLowerCase() === answer.toLowerCase());

    if (foundAnswer) {
        if (currentTeam === teamAName) {
            scoreTeamA += foundAnswer.score;
            document.getElementById('score-team-a').innerText = scoreTeamA;
            submitScore(teamAName, scoreTeamA); // Submit score for Team A
        } else {
            scoreTeamB += foundAnswer.score;
            document.getElementById('score-team-b').innerText = scoreTeamB;
            submitScore(teamBName, scoreTeamB); // Submit score for Team B
        }
        showNextQuestion();
    } else {
        alert("Incorrect answer!");
        switchTeams();
    }
}

// Function to submit score to PHP
function submitScore(teamName, score) {
    fetch('updatadescore.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `team_name=${encodeURIComponent(teamName)}&score=${encodeURIComponent(score)}`
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // For debugging, you can remove this later
    })
    .catch(error => console.error('Error:', error));
}

// Function to switch teams
function switchTeams() {
    if (currentTeam === teamAName) {
        currentTeam = teamBName;
    } else {
        currentTeam = teamAName;
    }
    document.getElementById('current-team').innerText = `Current Team: ${currentTeam}`;
}

// Timer function example to show when team switches due to time running out
function startTimer() {
    timeRemaining = 30;
    document.getElementById('time-remaining').innerText = timeRemaining;

    clearInterval(timerInterval);

    timerInterval = setInterval(() => {
        if (timeRemaining > 0) {
            timeRemaining--;
            document.getElementById('time-remaining').innerText = timeRemaining;
        } else {
            clearInterval(timerInterval);
            alert("Time's up!");
            switchTeams(); // Switch teams when time is up
            showNextQuestion(); // Show the next question
        }
    }, 1000);
}

// Function to end the game
function endGame() {
    clearInterval(timerInterval); // Clear the timer interval
    document.getElementById('game-status').innerText = ""; // Clear game status message
    document.getElementById('answer-form').style.display = "none"; // Hide the answer form

    // Determine the winning team and alert the final scores
    const winningTeam = scoreTeamA > scoreTeamB ? teamAName : teamBName;
    const winningScore = Math.max(scoreTeamA, scoreTeamB);
    alert(`Final Scores:\n${teamAName}: ${scoreTeamA}\n${teamBName}: ${scoreTeamB}\nCongratulations ${winningTeam}! You won with ${winningScore} points!`);

    // Record the scores to the database
    fetch('endgame.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            scoreTeamA: scoreTeamA,
            scoreTeamB: scoreTeamB,
            winningTeam: winningTeam
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Game data recorded:', data);
    })
    .catch(error => console.error('Error:', error));
}

// Event listener for the "End Game" button
document.getElementById('end-game').addEventListener('click', endGame);
