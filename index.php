<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Drag and Drop</title>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/smoothness/jquery-ui.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Gabarito&family=Inter&family=Inter+Tight&family=Julius+Sans+One&family=Poppins&family=Quicksand:wght@400;500&family=Roboto&family=Thasadith&display=swap"
    rel="stylesheet">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>
  <script type="text/javascript">
    var correctCards = 0;
    var score = 0;
    var timer;

    $(init);

    function init() {
      // Ask for confirmation before starting the game
      var startGame = window.confirm('Are you sure you want to start the game?');

      if (!startGame) {
        return; // If the user cancels the confirmation, do not start the game
      }
      // Hide the success and error messages
      $('#successMessage').hide();
      $('#errorMessage').hide();

      // Reset the game
      correctCards = 0;
      score = 0;
      updateScoreDisplay();
      $('#cardPile').html('');
      $('#cardSlots').html('');

      // Create the pile of shuffled cards with images
      var numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
      var shuffledNumbers = shuffleArray(numbers);
      for (var i = 0; i < 12; i++) {
        $('<div><img src="image' + shuffledNumbers[i] + '.jpg" alt="Image ' + shuffledNumbers[i] + '"></div>').data('number', shuffledNumbers[i]).attr('id', 'card' + shuffledNumbers[i]).appendTo('#cardPile').draggable({
          stack: '#cardPile div',
          cursor: 'move',
          revert: true
        });
      }

      // Create the card slots with images
      var words = ['Surf Powder', 'Downy Fabcon', 'Silver Swan Soy Sauce', 'Trust', 'Skyflakes', 'Dove', 'Colgate', 'Nivea', 'Zondrox', 'Safeguard', 'Listerine', 'Nips'];
      for (var i = 1; i <= 12; i++) {
        $('<div>' + words[i - 1] + '</div>').data('number', i).appendTo('#cardSlots').droppable({
          accept: '#cardPile div',
          hoverClass: 'hovered',
          drop: handleCardDrop
        });
      }

      // Start the 1-minute timer
      startTimer(20);
    }

    function handleCardDrop(event, ui) {
      var slotNumber = $(this).data('number');
      var cardNumber = ui.draggable.data('number');
      var messageElement = null;

      // Check if the card was dropped to the correct slot
      if (slotNumber == cardNumber) {
        ui.draggable.addClass('correct');
        ui.draggable.draggable('disable');
        $(this).droppable('disable');
        ui.draggable.position({ of: $(this), my: 'left top', at: 'left top' });
        ui.draggable.draggable('option', 'revert', false);
        correctCards++;
        score += 1; // Increase score for correct placement
        ui.draggable.css('background-color', '#27ae60'); // Green background for correct placement
      } else {
        ui.draggable.addClass('incorrect');
        ui.draggable.draggable('disable');
        $(this).droppable('disable');
        ui.draggable.position({ of: $(this), my: 'left top', at: 'left top' });
        ui.draggable.draggable('option', 'revert', false);
        correctCards++;
        score -= 1; // Decrease score for incorrect placement
        ui.draggable.css('background-color', '#e74c3c'); // Red background for incorrect placement
      }

      updateScoreDisplay();

      // If all the cards have been placed correctly then stop the timer,
      // display a message, and reset the cards for another go
      if (correctCards === 12) {
        stopTimer();

        if (score >= 6) {
          messageElement = $('#successMessage');
        } else {
          messageElement = $('#errorMessage');
        }

        percentage = (score / 12) * 100;
        status = score >= 6 ? 'PASSED' : 'FAILED';

        messageElement.show();
        messageElement.find('h2').text('Game Over!');
        messageElement.find('#scoreDisplay').html('Your Score: ' + score + '<br>Percentage: ' + percentage.toFixed(2) + '%<br>' + status);
        messageElement.animate({
          left: '50%',
          top: '30%',
          marginLeft: '-200px',
          marginTop: '-50px',
          width: '400px',
          height: '400px',
          opacity: 100,
        });
      }
    }

    function updateScoreDisplay() {
      $('#scoreDisplay').text('Score: ' + score);
    }

    function startTimer(seconds) {
      var display = $('#timerDisplay');
      var timerInterval = 1000; // 1 second interval

      timer = setInterval(function () {
        display.text('Time Left: ' + formatTime(seconds));
        seconds--;

        if (seconds < 0) {
          stopTimer();
          handleTimeout();
        }
      }, timerInterval);
    }

    function stopTimer() {
      clearInterval(timer);
    }

    function formatTime(seconds) {
      var min = Math.floor(seconds / 60);
      var sec = seconds % 60;
      return min + ':' + (sec < 10 ? '0' : '') + sec;
    }

    function handleTimeout() {
      // Game over due to timeout logic here
      var messageElement = null;

      if (score >= 6) {
        messageElement = $('#successMessage');
      } else {
        messageElement = $('#errorMessage');
      }

      percentage = score / 12 * 100;
      status = (score >= 6) ? "PASSED" : "FAILED";

      messageElement.show();
      messageElement.find('h2').text('Game Over!');
      messageElement.find('#scoreDisplay').html('Your Score: ' + score + '<br>Percentage: ' + percentage.toFixed(2) + '%<br>' + status);
      messageElement.animate({
        left: '50%',
        top: '30%',
        marginLeft: '-200px',
        marginTop: '-50px',
        width: '400px',
        height: '400px',
        opacity: 100
      });
    }


    // Shuffle function
    function shuffleArray(array) {
      var currentIndex = array.length,
        randomIndex;

      while (currentIndex !== 0) {
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;

        [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
      }

      return array;
    }
  </script>
  <style>
    body {
      font-family: "Inter Tight", sans-serif;
      margin: 0;
      padding: 0;
    }

    #content {
      text-align: center;
      margin: 50px auto;
    }

    #scoreDisplay {
      font-size: 18px;
      margin-bottom: 20px;
    }

    #timerDisplay {
      font-size: 18px;
      margin-bottom: 20px;
      color: red;
    }

    #cardPile,
    #cardSlots {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      gap: 10px;
    }

    #cardPile div,
    #cardSlots div {
      width: 23%;
      /* 25% is used here to accommodate the gap between elements */
      height: 80px;
      text-align: center;
    }

    #cardSlots {
      margin-top: 4rem;
    }

    #cardSlots div {
      border: 1px dashed #3498db;
      text-align: center;
      box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    }

    #cardPile div img {
      max-width: 100%;
      max-height: 100%;
    }

    .correct {
      background: #27ae60 !important;
    }

    #successMessage,
    #errorMessage {
      position: absolute;
      width: 400px;
      height: 400px;
      background: #2ecc71;
      border: 2px solid #27ae60;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      font-size: 18px;
      font-weight: bold;
      opacity: 0;
      transition: opacity 0.5s ease-in-out;
    }

    #successMessage button,
    #errorMessage button {
      margin-top: 10px;
      padding: 5px 10px;
      font-size: 16px;
      cursor: pointer;
      background: #3498db;
      border: none;
      border-radius: 5px;
      color: white;
      transition: background 0.3s ease-in-out;
    }

    #successMessage button:hover,
    #errorMessage button:hover {
      background: #2980b9;
    }
  </style>
</head>

<body>
  <div id="content">
    <div id="timerDisplay"></div>
    <div id="scoreDisplay"></div>
    <div id="cardPile"></div>
    <div id="cardSlots"></div>

    <div id="successMessage">
      <h2>Congratulations!</h2>
      <div id="scoreDisplay"></div>
      <button onclick="init()">Play Again</button>
    </div>
    <div id="errorMessage">
      <h2>Game Over!</h2>
      <div id="scoreDisplay"></div>
      <button onclick="init()">Play Again</button>
    </div>
  </div>
</body>

</html>