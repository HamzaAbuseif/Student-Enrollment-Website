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
        }

        h2 {
            text-align: center;
            color: #333;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
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

        /* Button Styling */
        .button-table {
            display: inline-block;
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
            text-decoration: none;
            margin: 2px;
        }

        .button-table:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        /* Enroll Button */
        .button-table.enroll {
            background: #28a745; /* Green */
            color: white;
        }

        .button-table.enroll:hover {
            background: #218838;
        }

        /* Drop Button */
        .button-table.drop {
            background: #dc3545; /* Red */
            color: white;
        }

        .button-table.drop:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/portions/header.php'; ?>

<?php
include __DIR__ . '/../database/db.php';

$user_id = $_SESSION['user_id'] ?? null;

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
                        <button type='submit' class='button-table enroll' " . ($isEnrolled || $isFull == 'Yes' ? 'disabled' : '') . ">Enroll</button>
                    </form>
                    <form action='drop.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='course_id' value='{$row['course_id']}'>
                        <button type='submit' class='button-table drop' " . (!$isEnrolled ? 'disabled' : '') . ">Drop</button>
                    </form>
                </td>
              </tr>";
    }
    ?>
</table>

<?php include __DIR__ . '/portions/footer.php'; ?>

</body>
</html>

<?php
$conn->close();
?>
