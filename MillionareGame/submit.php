
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


// Check if the total prize money is set
if (!isset($_SESSION['new_prize'])) {
    // Redirect to index.php if no prize money
    header("Location: index.php");
    exit();
}

// Get the total prize money
$new_prize = number_format($_SESSION['new_prize']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Congratulations</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .logo img {
            max-width: 100%; /* Make logo responsive */
            height: auto;
        }
        .message {
            font-size: 24px;
            margin: 20px 0;
        }
        .total-prize {
            font-size: 20px;
            margin: 10px 0;
            color: green; /* Green color for the prize money */
        }
        .back-button {
            background-color: #007BFF; /* Bootstrap primary color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .back-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="path/to/logo.png" alt="Logo"> <!-- Replace with your logo path -->
        </div>
        <div class="message">Congratulations!</div>
        <div class="total-prize">You won: $<?php echo $new_prize; ?></div>
        <a href="index.php" class="back-button">Back to Home</a>
    </div>
</body>
</html>

