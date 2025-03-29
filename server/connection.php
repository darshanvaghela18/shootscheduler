<?php
$host = "localhost"; 
$user = "root"; 
$pass = ""; // Agar password set kiya ho to yaha likho
$db = "shootscheduler"; 

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
