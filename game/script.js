document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('player-names-form');
    const gameContainer = document.getElementById('game-container');
    const countdownTimer = document.getElementById('countdown-timer');
    const countdownDisplay = document.getElementById('countdown');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress');
    const resultsContainer = document.getElementById('results');
    const answerForm = document.getElementById('answer-form');
    const userAnswerInput = document.getElementById('answer');
    let countdown;
    
    // Questions and answers
    const questions = [
        {
            question: "Name a popular fruit.",
            answers: ["Apple", "Banana", "Orange", "Grapes", "Mango"]
        },
        {
            question: "Name a household pet.",
            answers: ["Dog", "Cat", "Fish", "Bird", "Hamster"]
        },
        {
            question: "Name a color.",
            answers: ["Red", "Blue", "Green", "Yellow", "Black"]
        }
        // Add more questions as needed
    ];

    let currentQuestionIndex = 0;

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        countdownTimer.style.display = 'block'; // Show the countdown timer
        gameContainer.style.display = 'none'; // Hide the game container
        startCountdown(5); // Start countdown from 5 seconds
    });
    
    function startCountdown(seconds) {
        countdownDisplay.textContent = seconds;
        countdown = setInterval(function () {
            seconds--;
            countdownDisplay.textContent = seconds;
    
            if (seconds <= 0) {
                clearInterval(countdown);
                countdownTimer.style.display = 'none'; // Hide countdown when done
                
                // Hide all other elements
                document.querySelector('nav').style.display = 'none'; // Hide the navigation
                document.querySelector('header').style.display = 'none'; // Hide the header
                document.querySelector('section').style.display = 'none'; // Hide the section with the paragraph
                document.getElementById('player-names-form').style.display = 'none'; // Hide the player names form
                
                gameContainer.style.display = 'block'; // Show the game container
                displayQuestion(); // Display the first question
                startGame(); // Proceed to start the game
            }
        }, 1000);
    }

    function displayQuestion() {
        const currentQuestion = questions[currentQuestionIndex];
        resultsContainer.innerHTML = `<h3>${currentQuestion.question}</h3>`;
        answerForm.style.display = 'block'; // Show answer form
    }

    answerForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const userAnswer = userAnswerInput.value.trim();
        checkAnswer(userAnswer);
        userAnswerInput.value = ''; // Clear the input field
    });

    function checkAnswer(answer) {
        const currentQuestion = questions[currentQuestionIndex];
        const normalizedAnswer = answer.charAt(0).toUpperCase() + answer.slice(1).toLowerCase(); // Capitalize first letter

        if (currentQuestion.answers.includes(normalizedAnswer)) {
            alert(`Correct! ${normalizedAnswer} is one of the answers!`);
        } else {
            alert(`Sorry, ${normalizedAnswer} is not an answer.`);
        }

        // Logic to handle moving to the next question
        currentQuestionIndex++;
        if (currentQuestionIndex < questions.length) {
            displayQuestion();
        } else {
            endGame();
        }
    }

    function startGame() {
        // Show progress bar
        progressContainer.style.display = 'block';
        let timeLeft = 30; // 30 seconds to answer

        const progressInterval = setInterval(function () {
            timeLeft--;
            const percentage = (timeLeft / 30) * 100;
            progressBar.style.width = percentage + '%';

            if (timeLeft <= 0) {
                clearInterval(progressInterval);
                alert("Time's up!");
                progressContainer.style.display = 'none'; // Hide progress bar
                currentQuestionIndex++; // Move to next question
                if (currentQuestionIndex < questions.length) {
                    displayQuestion(); // Show next question
                } else {
                    endGame(); // End the game if no more questions
                }
            }
        }, 1000);
    }

    function endGame() {
        // Logic to handle game over
        alert("Game Over! Thank you for playing.");
        // Optionally reset game state or redirect
    }
});
