<?php
$servername = "localhost"; 
$username = "bbharatula1";  
$password = "bbharatula1";      
$dbname = "bbharatula1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$user_password = $_POST['password'];
$user_type = $_POST['user_type'];

$hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, email, password, user_type) 
        VALUES ('$name', '$email', '$hashed_password', '$user_type')";

if ($conn->query($sql) === TRUE) {
    header("Location: signin.php");
    exit;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>
