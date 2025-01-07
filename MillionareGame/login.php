<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $userFound = false;

    // Basic validation to ensure fields are not empty
    if (!empty($username) && !empty($password)) {
        // Open the file to check if the username and password are correct
        $file = "users.txt";
        if (file_exists($file)) {
            $file_handle = fopen($file, "r");
            while (($line = fgets($file_handle)) !== false) {
                list($storedUsername, $storedPassword) = explode(",", trim($line));
                
                // Check if username exists and password matches
                if ($storedUsername === $username && $storedPassword === $password) {
                    $userFound = true;
                    $_SESSION['username'] = $username; // Start a session for the user
                    header("Location: index.php"); // Redirect to index.php
                    exit();
                }
            }
            fclose($file_handle);
        }

        if (!$userFound) {
            $message = "Invalid username or password.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <div class="logo-container">
        <img src="images/million.png" alt="Who Wants to Be a Millionaire Logo" class="logo">
    </div>

    <div class="wrapper">
        <div class="container">
            <h1>Login</h1>
            
            <!-- Display message if set -->
            <?php if (!empty($message)) echo "<p class='message error'>$message</p>"; ?>

            <!-- Login form -->
            <form action="login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
        </div>
    </div>

</body>
</html>
