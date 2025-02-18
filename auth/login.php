
<!-- logindb.php -->
<?php
session_start();
include __DIR__ . '/../database/db.php'; // Adjust based on directory structure

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password_hash, userRole FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['userRole'] = $user['userRole'];
            if ($user['userRole'] === 'admin') {
                header("Location: ../main/dashboard.php");
                exit();
            }
            
            header("Location: ../main/home.php");
            exit();
        } else {
            echo "Invalid credentials.";
            exit();
        }
    } else {
        echo "Invalid credentials.";
        exit();
    }
    $stmt->close();
    $conn->close();
}
?>
