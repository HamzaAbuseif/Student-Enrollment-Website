<?php
include __DIR__ . '/../database/db.php'; // Adjust the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        table {
            width: 70%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Button Styling */
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
            display: block;
            margin: 20px auto;
        }

        button:hover {
            background-color: #218838;
        }

        button:focus {
            outline: none;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            table {
                width: 90%;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<?php include_once __DIR__ . '/portions/header.php'; 

// Check if user is logged in
$user_id = $_SESSION['user_id'] ?? null; // Retrieve user ID from session or null if not set

// Ensure you have a login.php page and a logout.php page in the same directory as this file
if (!$user_id) {
    header('Location: /../auth/login.php'); // Redirect if not logged in
    exit();
}

// Fetch user profile information
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?> <!-- Include header -->

<h2>User Profile</h2>

<table>
    <tr>
        <th>Name:</th>
        <td><?php echo $user['FirstName'] . " " . $user['MidName'] . " " . $user['LastName']; ?></td>
    </tr>
    <tr>
        <th>Email:</th>
        <td><?php echo $user['email']; ?></td>
    </tr>
    <tr>
        <th>Role:</th>
        <td><?php echo $user['userRole']; ?></td>
    </tr>
    <tr>
        <th>Phone:</th>
        <td><?php echo $user['phone']; ?></td>
    </tr>
</table>

<a href="settings.php"><button>Update Profile</button></a> <!-- Link to update profile -->

<?php include_once __DIR__ . '/portions/footer.php'; ?> <!-- Include footer -->

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
