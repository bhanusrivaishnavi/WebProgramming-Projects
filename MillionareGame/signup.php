<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="logo-container">
        <img src="images/million.png" alt="Who Wants to Be a Millionaire Logo" class="logo">
    </div>

    <div class="wrapper">
        <div class="signup-container">
            <h1>Sign Up</h1>

            <!-- Display messages based on the status query parameter -->
            <?php
            if (isset($_GET['status'])) {
                if ($_GET['status'] == 'success') {
                    echo "<p class='message'>Registration successful!</p>";
                } elseif ($_GET['status'] == 'empty') {
                    echo "<p class='message error'>Please fill in all fields.</p>";
                } elseif ($_GET['status'] == 'exists') {
                    echo "<p class='message error'>Username already exists. Choose a different one.</p>";
                }
            }
            ?>

            <!-- Send form data to signup-submit.php -->
            <form action="signup-submit.php" method="post" class="signup-form">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Log in here</a>.</p>
        </div>
    </div>

</body>
</html>
