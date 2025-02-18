<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include __DIR__ . '/portions/header.php'; ?>

<?php
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    include __DIR__ . '/../database/db.php';

    $sql = "SELECT * FROM courses WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $course_name = $_POST['course_name'];
        $duration = $_POST['duration'];
        $fee = $_POST['fee'];
        $max_students = $_POST['max_students'];

        $update_sql = "UPDATE courses SET course_name = ?, duration = ?, fee = ?, max_students = ? WHERE course_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssdis", $course_name, $duration, $fee, $max_students, $course_id);  // Corrected bind_param types
        
        if ($stmt->execute()) {
            echo "<p>Course updated successfully!</p>";
            header("Location: courses.php"); // Redirect to courses page after update
        } else {
            echo "<p>Failed to update course. Please try again.</p>";
        }
    }
}
?>

<h2>Update Course</h2>
<form action="update_course.php?course_id=<?php echo $course_id; ?>" method="POST">
    <label for="course_name">Course Name:</label>
    <input type="text" id="course_name" name="course_name" value="<?php echo $course['course_name']; ?>" required><br><br>

    <label for="duration">Duration (Days):</label>
    <input type="number" id="duration" name="duration" value="<?php echo $course['duration']; ?>" required><br><br>

    <label for="fee">Fees (JOD):</label>
    <input type="number" id="fee" name="fee" value="<?php echo $course['fee']; ?>" required><br><br>

    <label for="max_students">Enrolment Limit:</label>
    <input type="number" id="max_students" name="max_students" value="<?php echo $course['max_students']; ?>" required><br><br>

    <button type="submit">Update Course</button>
</form>

<?php include __DIR__ . '/portions/footer.php'; ?>

</body>
</html>
