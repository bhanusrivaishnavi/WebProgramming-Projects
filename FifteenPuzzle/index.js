let selectedBackground = "";
let selectedsize;

window.onload = function () {
  let feedbackList = JSON.parse(localStorage.getItem("feedbackList")) || [];
  const feedbackContainer = document.getElementById("feedback-list");
  feedbackList=feedbackList.sort((a, b) => b.timestamp - a.timestamp).slice(0, 4);
  feedbackContainer.innerHTML = feedbackList
    .map(
      (feedback) => `
      <div class="feedback-item">
        <p>${feedback.text}</p>
      </div>
    `
    )
    .join("");
}
// Function to handle background selection
function selectBackground(thumbnail) {
  document.querySelectorAll(".thumbnail").forEach((thumb) => {
    thumb.classList.remove("selected");
  });
  thumbnail.classList.add("selected");
  selectedBackground = thumbnail.dataset.image;
}

//Function to handle size selection
function selectSize(thumbnail,size) {
  document.querySelectorAll(".thumbnail-size").forEach((thumb) => {
    thumb.classList.remove("selected");
  });
  thumbnail.classList.add("selected");
  selectedsize = size;
}

// Function to handle the Play Now button
function startGame() {
  const playerName = document.getElementById("player-name").value.trim();
  if (!playerName) {
    alert("Please enter your name before starting the game!");
    return;
  }
  if (!selectedBackground) {
    alert("Please select a background image before starting the game!");
    return;
  }
  if(!selectedsize){
    alert("Please select a puzzle size before starting the game!");
    return;
  }
  // Save player data to localStorage
  localStorage.setItem("playerName", playerName);
  localStorage.setItem("backgroundImage", selectedBackground);
  localStorage.setItem("selectedSize", selectedsize);

  // Redirect to game section
  window.location.href = "game.html";
}
