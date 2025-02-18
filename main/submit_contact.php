<?php
include __DIR__ . '/../database/db.php'; // Adjust the database connection

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Escape values to prevent XSS
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    // Prepare SQL statement to insert the contact message into the database
    $sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // Success message
        echo "<p>Your message has been sent successfully! We'll get back to you shortly.</p>";
        header('location : /contact.php');
        exit();
    } else {
        // Error message
        echo "<p>Failed to send the message. Please try again later.</p>";
        exit();
    }

    $stmt->close();
}

$conn->close(); // Close the database connection
?>
