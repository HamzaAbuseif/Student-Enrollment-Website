<!-- signupdb.php -->
<?php
session_start();
include __DIR__ . '/../database/db.php'; // Adjust based on directory structure


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $FirstName = $_POST['FirstName'];
    $MidName = $_POST['MidName'];
    $LastName = $_POST['LastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $userRole = $_POST['userRole'];

    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (FirstName, MidName, LastName, email, phone, userRole, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $FirstName, $MidName, $LastName, $email, $phone, $userRole, $password);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id; // Get last inserted user ID
        $_SESSION['userRole'] = $userRole;
        header("Location: ../main/home.php"); // Adjust as needed
        exit();
    } else {
        echo "Error: " . $stmt->error;
        exit();
    }
    
    $stmt->close();
    $conn->close();
}

?>
