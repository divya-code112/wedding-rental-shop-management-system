<?php
session_start();
include __DIR__ . '/../includes/db.php'; // Adjust path if needed

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Sanitize inputs
    $name    = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email   = mysqli_real_escape_string($conn, trim($_POST['email']));
    $subject = mysqli_real_escape_string($conn, trim($_POST['subject']));
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));

    // Optional: validate email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        die("Invalid email address.");
    }

    // Insert into database (if you want to store)
    $sql = "INSERT INTO contact_messages (name,email,subject,message) VALUES ('$name','$email','$subject','$message')";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Thank you for contacting us! We will get back to you soon.'); window.location='contact.php';</script>";
        exit();
    } else {
        die("Error: ".mysqli_error($conn));
    }
} else {
    header("Location: contact.php");
    exit();
}
?>
