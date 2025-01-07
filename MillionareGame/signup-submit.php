<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and trim whitespace
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Validate that fields are not empty
    if (empty($username) || empty($password)) {
        // Redirect back to signup page with an error message if fields are empty
        header("Location: signup.php?status=empty");
        exit();
    }

    // Define the file where user data is stored
    $file = "users.txt";

    // Check if the username already exists in the file
    $userExists = false;
    if (file_exists($file)) {
        $file_handle = fopen($file, "r");
        while (($line = fgets($file_handle)) !== false) {
            list($storedUsername, $storedPassword, $storedPrize) = explode(",", trim($line));
            if ($storedUsername === $username) {
                $userExists = true;
                break;
            }
        }
        fclose($file_handle);
    }

    // If username already exists, redirect with error message
    if ($userExists) {
        header("Location: signup.php?status=exists");
        exit();
    } else {
        // Set initial prize to 0 for new users
        $initialPrize = 0;

        // Open the file in append mode to add new user data
        $file_handle = fopen($file, "a");
        if ($file_handle) {
            // Format data as "username,password,0" and add a newline
            $data = "$username,$password,$initialPrize\n";
            fwrite($file_handle, $data);
            fclose($file_handle);
            
            // Redirect to signup page with a success message
            header("Location: signup.php?status=success");
            exit();
        } else {
            // Error message if file could not be opened
            echo "Error: Could not open the file for writing.";
        }
    }
} else {
    // Redirect to the signup page if accessed without form submission
    header("Location: signup.php");
    exit();
}
