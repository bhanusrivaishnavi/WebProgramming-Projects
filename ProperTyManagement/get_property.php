<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

$property_id = $_GET['property_id'];
$servername = "localhost"; 
$username = "bbharatula1";  
$password = "bbharatula1";      
$dbname = "bbharatula1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$sql = "SELECT * FROM property WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $property = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'property' => $property]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Property not found']);
}

$conn->close();
?>
