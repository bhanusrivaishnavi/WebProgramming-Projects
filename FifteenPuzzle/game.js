"use strict";

let hasShuffled = false; // Tracks whether the player has shuffled the puzzle
let emptySpace = { x: 300, y: 300 }; // Initial empty space position
let timerInterval; // Timer interval reference
let secondsElapsed = 0; // Total seconds elapsed
let movesCount = 0; // Number of moves made
let containersize =0;
let size=0
window.onload = function () {
  const playerName = localStorage.getItem("playerName") || "Player";
  const backgroundImage = localStorage.getItem("backgroundImage") || "bg1.jpg";
  size = parseInt(localStorage.getItem("selectedSize")) || 4;
  // Display greeting
  document.getElementById("player-greeting").textContent = `Welcome, ${playerName}!`;
  // Apply background image and set initial positions for tiles
  const puzzlearea=document.getElementById("puzzlearea");
  puzzlearea.innerHTML = "";
  containersize= size*100;
  const tileSize = containersize / size; 
  const backgroundSize = `${size * 100}%`; // Scale the background size
  emptySpace.x = (size - 1) * tileSize;
  emptySpace.y = (size - 1) * tileSize;
  puzzlearea.style.width = `${containersize}px`;
  puzzlearea.style.height = `${containersize}px`;
  for (let i = 0; i < size * size - 1; i++) {
    const div = document.createElement("div");
    div.textContent = i + 1;
    div.className = "puzzlepiece";
    div.style.width = `${tileSize}px`;
    div.style.height = `${tileSize}px`;
    puzzlearea.appendChild(div);
  }

  // Apply styles and positions to tiles
  const tiles = document.querySelectorAll("#puzzlearea div");
  tiles.forEach((tile, index) => {
    const row = Math.floor(index / size);
    const col = index % size;

    tile.style.backgroundImage = `url(${backgroundImage})`;
    tile.style.backgroundSize = backgroundSize; // Adjust background size
    tile.style.backgroundPosition = `-${col * tileSize}px -${row * tileSize}px`; // Adjust based on tile position
    tile.style.left = `${col * tileSize}px`;
    tile.style.top = `${row * tileSize}px`;
  });

  

  // Shuffle button functionality
  document.getElementById("shufflebutton").onclick = function () {
    shuffleTiles();
    if (!hasShuffled) {
      hasShuffled = true; // Allow tile movement after first shuffle
    }
  };

  // Start the timer when the game page loads
  startTimer();

  // Make tiles movable
  makeTilesMovable();

  setupMusicControls();
};

// Music controls
function setupMusicControls() {
  const music = document.getElementById("background-music");
  const muteButton = document.getElementById("mute-button");

  muteButton.addEventListener("click", () => {
    if (music.muted) {
      music.muted = false;
      muteButton.textContent = "Mute Music";
    } else {
      music.muted = true;
      muteButton.textContent = "Unmute Music";
    }
  });
}


// Timer logic
function startTimer() {
  clearInterval(timerInterval); // Clear any existing timer
  const timerElement = document.getElementById("timer");
  timerInterval = setInterval(() => {
    secondsElapsed++;
    const minutes = Math.floor(secondsElapsed / 60);
    const seconds = secondsElapsed % 60;
    timerElement.textContent = `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
  }, 1000);
}

// Stop the timer
function stopTimer() {
  clearInterval(timerInterval);
}

// Increment the move counter
function incrementMoveCounter() {
  movesCount++;
  document.getElementById("moves").textContent = movesCount;
}

// Reset the game stats (timer and moves)
function resetGameStats() {
  stopTimer(); // Stop the timer
  secondsElapsed = 0; // Reset time
  movesCount = 0; // Reset moves
  document.getElementById("timer").textContent = "00:00";
  document.getElementById("moves").textContent = "0";
}

// Make tiles movable
function makeTilesMovable() {
  const tiles = document.querySelectorAll("#puzzlearea div");
  tiles.forEach((tile) => {
    tile.onmouseover = function () {
      if (hasShuffled) {
        this.classList.add("movablepiece");
      }
    };
    tile.onmouseout = function () {
      this.classList.remove("movablepiece");
    };
    tile.onclick = function () {
      if (hasShuffled) {
        moveTile(this);
        incrementMoveCounter(); // Increment moves when a tile is moved
      } else {
        alert("You must shuffle the puzzle before starting!");
      }
    };
  });
}

function moveTile(tile) {
  const tileX = parseInt(tile.style.left);
  const tileY = parseInt(tile.style.top);
  const tileSize = containersize / parseInt(localStorage.getItem("selectedSize"));

  // Check if the tile is adjacent to the empty space
  if (
    (Math.abs(tileX - emptySpace.x) === tileSize && tileY === emptySpace.y) ||
    (Math.abs(tileY - emptySpace.y) === tileSize && tileX === emptySpace.x)
  ) {
    // Swap positions
    tile.style.transition = "all 0.3s ease";
    tile.style.left = `${emptySpace.x}px`;
    tile.style.top = `${emptySpace.y}px`;

    // Update empty space
    emptySpace.x = tileX;
    emptySpace.y = tileY;

    checkGameCompletion();
  }
  
}


function shuffleTiles() {
  const tiles = Array.from(document.querySelectorAll("#puzzlearea div"));
  let shuffleCount = size*25;

  while (shuffleCount > 0) {
    const movableTiles = tiles.filter((tile) => isAdjacent(tile));
    if (movableTiles.length > 0) {
      const randomTile = movableTiles[Math.floor(Math.random() * movableTiles.length)];
      moveTile(randomTile); // Move the random tile
      shuffleCount--;
    }
  }
}

// Helper function to check if a tile is adjacent to the empty space
function isAdjacent(tile) {
  const tileX = parseInt(tile.style.left);
  const tileY = parseInt(tile.style.top);
  const tileSize = containersize / parseInt(localStorage.getItem("selectedSize"));

  return (
    (Math.abs(tileX - emptySpace.x) === tileSize && tileY === emptySpace.y) ||
    (Math.abs(tileY - emptySpace.y) === tileSize && tileX === emptySpace.x)
  );
}


// Navigate back to the home page
function goHome() {
  resetGameStats(); // Reset timer and moves when going home
  window.location.href = "index.html";
}


function checkGameCompletion() {
  if (!hasShuffled) return;
  const tiles = Array.from(document.querySelectorAll("#puzzlearea div"));
  let isCompleted = true;
  const tileSize = containersize / parseInt(localStorage.getItem("selectedSize"));


  tiles.forEach((tile, index) => {
    const expectedX = (index % size) * tileSize;
    const expectedY = Math.floor(index / size) * tileSize;

    const actualX = parseInt(tile.style.left);
    const actualY = parseInt(tile.style.top);

    if (expectedX !== actualX || expectedY !== actualY) {
      isCompleted = false;
    }
  });

  // Check if empty space is at the bottom-right
  if (emptySpace.x !== (size - 1) * tileSize || emptySpace.y !== (size - 1) * tileSize) {
    isCompleted = false;
  }

  // Display a success message if the game is completed
  if (isCompleted) {
    alert("Completed");
    showCongratsPopup();
    stopTimer(); // Stop the timer
  }

  return isCompleted;
}


function showCongratsPopup() {
  const popup = document.getElementById("congrats-popup");
  popup.classList.remove("hidden");
  popup.classList.add("popup")
  // Start the confetti effect
  startConfettiEffect();
}

function saveFeedback() {
  const feedbackInput = document.getElementById("feedback-input").value.trim();
  if (feedbackInput === "") {
    alert("Please provide feedback before submitting.");
    return;
  }

  // Save feedback to local storage
  const timestamp = new Date().toISOString();
  const feedback = { text: feedbackInput, timestamp };

  const feedbackList = JSON.parse(localStorage.getItem("feedbackList")) || [];
  feedbackList.push(feedback);

  // Sort feedback by timestamp in descending order
  feedbackList.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));

  localStorage.setItem("feedbackList", JSON.stringify(feedbackList));

  // Clear the input and hide the popup
  document.getElementById("feedback-input").value = "";
  document.getElementById("congrats-popup").classList.add("hidden");
  document.getElementById("congrats-popup").classList.remove("popup");
  goHome();
}


function startConfettiEffect() {
  const confettiContainer = document.createElement("div");
  confettiContainer.id = "confetti-container";
  document.body.appendChild(confettiContainer);

  const colors = ["#ff0000", "#00ff00", "#0000ff", "#ffff00", "#ff00ff", "#00ffff"];

  // Generate confetti pieces
  for (let i = 0; i < 100; i++) { // Adjust the number of pieces
    const confettiPiece = document.createElement("div");
    confettiPiece.className = "confetti";
    confettiPiece.style.left = `${Math.random() * 100}vw`; // Random horizontal position
    confettiPiece.style.animationDelay = `${Math.random() * 5}s`; // Random start time
    confettiPiece.style.setProperty("--color", colors[Math.floor(Math.random() * colors.length)]);
    confettiContainer.appendChild(confettiPiece);
  }
}

// Call this function at the end of the game
function onGameComplete() {
  showCongratsPopup();
}

