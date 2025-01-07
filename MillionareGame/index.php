<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="logo-container">
        <img src="images/million.png" alt="Who Wants to Be a Millionaire Logo" class="logo">
    </div>

    <!-- Tagline Section -->
    <div class="tagline">
        <p>Are you ready to test your knowledge and win big?</p>
    </div>

    <div class="wrapper">
        <div class="fcontainer">
        <?php
            $file = 'feedback.txt'; // Specify the path to your text file

            // Check if the file exists
            if (file_exists($file)) {
                $lines = [];
                $handle = fopen($file, "r");

                // Read all lines into an array
                while (($line = fgets($handle)) !== false) {
                    $lines[] = rtrim($line); // Trim new line characters
                }
                fclose($handle);

                // Get the last 5 feedback entries
                $lastFiveLines = array_slice($lines, -5);
                
                // Display the last 5 lines
                echo "<h2>Feedback</h2>";
                echo "<div class='feedback-container'>";
                
                foreach ($lastFiveLines as $line) {
                    // Use regex to extract the rating and comment
                    if (preg_match('/Rating: (\d) \| Comments: (.+)/', $line, $matches)) {
                        $rating = $matches[1]; // The rating
                        $comment = $matches[2]; // The comment
                        
                        // Display the rating as stars
                        echo "<div class='feedback-entry'>";
                        echo "<div class='star-rating'>";
                        
                        // Loop to display stars
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) {
                                echo "&#9733;"; // Filled star
                            } else {
                                echo "&#9734;"; // Empty star
                            }
                        }
                        
                        echo "</div>";
                        echo "<p>" . htmlspecialchars($comment) . "</p>"; // Display comment
                        echo "</div>";
                    }
                }
                
                echo "</div>";
            } else {
                echo "Feedback file does not exist.";
            }
        ?>
        </div>
        <div class="container">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

            <!-- Button Section -->
            <div class="button-container">
                <a href="game.php" class="button">Play Game</a>
                <a href="leaderboard.php" class="button">Show Leaderboard</a>
            </div>
            
            <!-- Logout Option -->
            <div class="logout">
                <a href="logout.php" class="logout-button">Logout</a>
            </div>
        </div>
        <div class="container">
            <h1>Instructions</h1>

            <div class="instructions">
                <p> A player can win from $100 to $100,000 by answering each question. A lifeline is provided 
                    as second chance to select the answer. Game ends if the player
                    selects a wrong answer. A player can exit the game by clicking on exit game 
                    button and submit the feedback.
                </p>
            </div>
        </div>
    </div>

</body>
</html>
