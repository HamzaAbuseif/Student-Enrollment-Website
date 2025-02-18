<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Courses</title>
    
    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin: auto;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #f1f1f1;
        }

        /* Buttons */
        .button-table {
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
            margin: 2px;
        }

        .button-table:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        .button-table.enroll {
            background: #28a745; /* Green */
            color: white;
        }

        .button-table.enroll:hover {
            background: #218838;
        }

        .button-table.drop {
            background: #dc3545; /* Red */
            color: white;
        }

        .button-table.drop:hover {
            background: #c82333;
        }

        .button-table.admin {
            background: #ffc107; /* Yellow */
            color: black;
        }

        .button-table.admin:hover {
            background: #e0a800;
        }

        .button-table.delete {
            background: #ff4444; /* Dark Red */
            color: white;
        }

        .button-table.delete:hover {
            background: #cc0000;
        }

        /* Admin Add Course Button */
        .admin-actions {
            text-align: center;
            margin-bottom: 15px;
        }

        .add-course-btn {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .add-course-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/portions/header.php'; ?>

<?php
include __DIR__ . '/../database/db.php';

$user_id = $_SESSION['user_id'] ?? null;
$is_admin = false;

// Check if the user is an admin
if ($user_id) {
    $user_query = "SELECT userRole FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    if ($user_data['userRole'] === 'admin') {
        $is_admin = true;
    }
    $stmt->close();
}

// Fetch all courses
$sql = "SELECT course_id, course_name, duration, fee, max_students, current_students FROM courses";
$result = $conn->query($sql);

// Get enrolled courses for the user
$enrolled_courses = [];
if ($user_id) {
    $enrollment_query = "SELECT course_id FROM enrollments WHERE user_id = ?";
    $stmt = $conn->prepare($enrollment_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $enrollment_result = $stmt->get_result();
    while ($row = $enrollment_result->fetch_assoc()) {
        $enrolled_courses[] = $row['course_id'];
    }
    $stmt->close();
}
?>

<h2>Available Courses</h2>

<!-- Admin Add Course Button -->
<?php if ($is_admin): ?>
    <div class="admin-actions">
        <a href="add_course.php" class="add-course-btn">Add New Course</a>
    </div>
<?php endif; ?>

<table>
    <tr>
        <th>Course Name</th>
        <th>Duration (Days)</th>
        <th>Fees (JOD)</th>
        <th>Enrolment Limit</th>
        <th>Enrolled Students</th>
        <th>Is Full?</th>
        <th>Actions</th>
    </tr>

    <?php
    while ($row = $result->fetch_assoc()) {
        $isFull = $row['current_students'] >= $row['max_students'] ? 'Yes' : 'No';
        $isEnrolled = in_array($row['course_id'], $enrolled_courses);

        echo "<tr>
                <td>{$row['course_name']}</td>
                <td>{$row['duration']}</td>
                <td>{$row['fee']}</td>
                <td>{$row['max_students']}</td>
                <td>{$row['current_students']}</td>
                <td>{$isFull}</td>
                <td>
                    <form action='enroll.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='course_id' value='{$row['course_id']}'>
                        <button type='submit' class='button-table enroll' " . ($isEnrolled || $isFull == 'Yes' || $is_admin ? 'disabled' : '') . ">Enroll</button>
                    </form>

                    <form action='drop.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='course_id' value='{$row['course_id']}'>
                        <button type='submit' class='button-table drop' " . (!$isEnrolled ? 'disabled' : '') . ">Drop</button>
                    </form>";

        // Admin actions (Update & Delete)
        if ($is_admin) {
            echo "
                <form action='update_course.php' method='GET' style='display:inline;'>
                    <input type='hidden' name='course_id' value='{$row['course_id']}'>
                    <button type='submit' class='button-table admin'>Update</button>
                </form>
                <form action='delete_course.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='course_id' value='{$row['course_id']}'>
                    <button type='submit' class='button-table delete' onclick='return confirm(\"Are you sure you want to delete this course?\")'>Delete</button>
                </form>";
        }

        echo "</td></tr>";
    }
    ?>
</table>

<?php include __DIR__ . '/portions/footer.php'; ?>

</body>
</html>

<?php
$conn->close();
?>
