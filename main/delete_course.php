<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    include __DIR__ . '/../database/db.php';

    $course_id = $_POST['course_id'];
    $delete_sql = "DELETE FROM courses WHERE course_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $course_id);
    
    if ($stmt->execute()) {
        header("Location: courses.php"); // Redirect to courses page after deletion
    } else {
        echo "<p>Failed to delete course. Please try again.</p>";
    }
}
?>
