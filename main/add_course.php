<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include __DIR__ . '/portions/header.php'; ?> <!-- Include navigation -->

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include __DIR__ . '/../database/db.php';
    
    $course_name = $_POST['course_name'];
    $duration = $_POST['duration'];
    $fee = $_POST['fee'];
    $max_students = $_POST['max_students'];

    $sql = "INSERT INTO courses (course_name, duration, fee, max_students, current_students) VALUES (?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $course_name, $duration, $fee, $max_students);
    
    if ($stmt->execute()) {
        echo "<p>Course added successfully!</p>";
        header("Location: courses.php"); // Redirect to courses page after update

    } else {
        echo "<p>Failed to add course. Please try again.</p>";
    }
    $stmt->close();
}
?>

<h2>Add New Course</h2>
<form action="add_course.php" method="POST">
    <label for="course_name">Course Name:</label>
    <input type="text" id="course_name" name="course_name" required><br><br>

    <label for="duration">Duration (Days):</label>
    <input type="number" id="duration" name="duration" required><br><br>

    <label for="fee">Fees (JOD):</label>
    <input type="number" id="fee" name="fee" required><br><br>

    <label for="max_students">Enrolment Limit:</label>
    <input type="number" id="max_students" name="max_students" required><br><br>

    <button type="submit">Add Course</button>
</form>

<?php include __DIR__ . '/portions/footer.php'; ?>

</body>
</html>
