<?php
session_start();
include __DIR__ . '/../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['course_id'];

    // Check if the user is enrolled
    $stmt = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the enrollment
        $stmt = $conn->prepare("DELETE FROM enrollments WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $user_id, $course_id);
        if ($stmt->execute()) {
            // Decrease the student count
            $conn->query("UPDATE courses SET current_students = current_students - 1 WHERE course_id = $course_id");
            header("Location: enrollment.php?dropped=1");
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "You are not enrolled in this course!";
    }

    $stmt->close();
}

$conn->close();
?>
