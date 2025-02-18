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

    // Check if the course is full
    $stmt = $conn->prepare("SELECT max_students, current_students FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $stmt->bind_result($max_students, $current_students);
    $stmt->fetch();
    $stmt->close();

    if ($current_students >= $max_students) {
        echo "Course is full!";
        exit();
    }

    // Enroll the student
    $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $course_id);
    if ($stmt->execute()) {
        // Update course student count
        $conn->query("UPDATE courses SET current_students = current_students + 1 WHERE course_id = $course_id");
        header("Location: home.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
