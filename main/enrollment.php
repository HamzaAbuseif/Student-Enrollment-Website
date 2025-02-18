<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Enrolled Courses</title>

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

<?php include __DIR__ . '/portions/header.php'; ?> <!-- Include navigation -->

<?php
include __DIR__ . '/../database/db.php'; // Adjust as needed

$user_id = $_SESSION['user_id'] ?? null; // Get logged-in user ID

if ($user_id) {
    // Fetch enrolled courses for the user
    $sql = "
        SELECT c.course_id, c.course_name, c.duration, c.fee, c.max_students, c.current_students 
        FROM courses c
        JOIN enrollments e ON c.course_id = e.course_id
        WHERE e.user_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user is enrolled in any courses
    if ($result->num_rows > 0) {
        echo "<h2>Your Enrolled Courses</h2>";
        echo "<table>
                <tr>
                    <th>Course Name</th>
                    <th>Duration (Days)</th>
                    <th>Fees (JOD)</th>
                    <th>Enrolment Limit</th>
                    <th>Enrolled Students</th>
                    <th>Is Full?</th>
                    <th>Actions</th>
                </tr>";

        // Display enrolled courses and the option to drop them
        while ($row = $result->fetch_assoc()) {
            $isFull = $row['current_students'] >= $row['max_students'] ? 'Yes' : 'No';

            echo "<tr>
                    <td>{$row['course_name']}</td>
                    <td>{$row['duration']}</td>
                    <td>{$row['fee']}</td>
                    <td>{$row['max_students']}</td>
                    <td>{$row['current_students']}</td>
                    <td>{$isFull}</td>
                    <td>
                        <form action='drop.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='course_id' value='{$row['course_id']}'>
                            <button type='submit' class='button-table drop'>Drop</button>
                        </form>
                    </td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>You are not enrolled in any courses yet.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Please log in to view your enrolled courses.</p>";
}

?>

<?php include __DIR__ . '/portions/footer.php'; ?> <!-- Include footer -->

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
