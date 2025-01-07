<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Function to update the user's prize if the new prize is higher
function updateUserPrize($username, $newPrize) {
    $file = 'users.txt';
    $updatedData = '';
    $found = false;

    // Read each line from the file
    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            list($storedUsername, $storedPassword, $storedPrize) = explode(',', trim($line));

            // If we find the user, update their prize if the new prize is higher
            if ($storedUsername === $username) {
                $storedPrize = max($storedPrize, $newPrize); // Update only if newPrize is higher
                $line = "$storedUsername,$storedPassword,$storedPrize";
                $found = true;
            }
            $updatedData .= $line . PHP_EOL;
        }
    }

    // If the user is not found (just in case), add them with the new prize
    if (!$found) {
        $updatedData .= "$username,password_placeholder,$newPrize" . PHP_EOL; // Replace password_placeholder if needed
    }

    // Write the updated data back to the file
    file_put_contents($file, $updatedData);
}




// Update the user's prize after the game ends
if (isset($_SESSION['new_prize'])) {
    $username = $_SESSION['username'];
    $newPrize = $_SESSION['new_prize'];
    updateUserPrize($username, $newPrize);

    // Remove the new prize from the session to prevent re-updating on refresh
    unset($_SESSION['new_prize']);
}

// Reading data from users.txt to prepare the leaderboard
$users = [];
$file = 'users.txt';

if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        list($username, $password, $prize) = explode(',', trim($line));
        $users[] = ['username' => $username, 'prize' => (int)$prize];
    }

    // Sort users by prize in descending order
    usort($users, function($a, $b) {
        return $b['prize'] - $a['prize'];
    });
}

// Extract top 3 players
$topThree = array_slice($users, 0, 3);
$remainingPlayers = array_slice($users, 3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="logo-container">
        <img src="images/million.png" alt="Who Wants to Be a Millionaire Logo" class="logo">
    </div>

    <div class="leaderboard-container">
        <h1>Leaderboard</h1>

        <!-- Top 3 Players -->
        <div class="top-three">
            <?php if (!empty($topThree)) { ?>
                <div class="second-place">
                    <h2>2nd Place</h2>
                    <p><?php echo htmlspecialchars($topThree[1]['username']); ?></p>
                    <p>Prize: <?php echo htmlspecialchars($topThree[1]['prize']); ?></p>
                </div>
                <div class="first-place">
                    <h2>1st Place</h2>
                    <p><?php echo htmlspecialchars($topThree[0]['username']); ?></p>
                    <p>Prize: <?php echo htmlspecialchars($topThree[0]['prize']); ?></p>
                </div>
                <div class="third-place">
                    <h2>3rd Place</h2>
                    <p><?php echo htmlspecialchars($topThree[2]['username']); ?></p>
                    <p>Prize: <?php echo htmlspecialchars($topThree[2]['prize']); ?></p>
                </div>
            <?php } ?>
        </div>

        <!-- Remaining Players -->
        <h2>Other Players</h2>
        <div class="remaining-players">
            <?php foreach ($remainingPlayers as $player) { ?>
                <p><?php echo htmlspecialchars($player['username']); ?> - Prize: <?php echo htmlspecialchars($player['prize']); ?></p>
            <?php } ?>
        </div>
    </div>

    <!-- Back to Index Button -->
    <div class="back-button-container">
        <a href="index.php" class="back-button">Back to Home</a>
    </div>
</body>
</html>
