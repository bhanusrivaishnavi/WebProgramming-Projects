<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = isset($_POST['rating']) ? $_POST['rating'] : 'No rating';
    $comments = isset($_POST['comments']) ? $_POST['comments'] : 'No comments';

    $feedbackEntry = date("Y-m-d H:i:s") . " | Rating: $rating | Comments: $comments" . PHP_EOL;

    $filePath = 'feedback.txt';

    file_put_contents($filePath, $feedbackEntry, FILE_APPEND | LOCK_EX);

    // Reset game-related session variables for a new game
    unset($_SESSION['questionIndex']);
    unset($_SESSION['secondChanceUsed']);

    // session_destroy(); 
    header("Location: leaderboard.php"); 
    exit();
} else {
    header("Location: leaderboard.php");
    exit();
}
?>