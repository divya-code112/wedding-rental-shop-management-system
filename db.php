<?php
$host = "localhost";
$user = "root"; 
$pass = "";  // your XAMPP MySQL password (keep empty if no password)
$db   = "rentalshop";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
