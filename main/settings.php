<?php
include __DIR__ . '/../database/db.php'; // Adjust the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password & Personal Info</title>
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

        form {
            width: 60%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #007bff;
        }

        label {
            font-size: 14px;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        button:focus {
            outline: none;
        }

        /* Error Message Styling */
        p {
            color: red;
            text-align: center;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            form {
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

if (!$user_id) {
    header('Location: /../auth/login.php'); // Redirect if not logged in
    exit();
}

// Fetch current user details from the database
$sql = "SELECT password_hash, FirstName, MidName, LastName, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<p>User not found.</p>";
    exit();
}

// Update details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if current password matches
        if (password_verify($current_password, $user['password_hash'])) {
            $update_sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("si", $hashed_password, $user_id);

                if ($stmt->execute()) {
                    echo "<p>Password updated successfully!</p>";
                    header('location: profile.php');
                    exit();
                } else {
                    echo "<p>Failed to update password. Please try again.</p>";
                }
            } else {
                echo "<p>New passwords do not match.</p>";
            }
        } else {
            echo "<p>Current password is incorrect.</p>";
        }
    }

    if (isset($_POST['update_info'])) {
        $FirstName = $_POST['FirstName'];
        $MidName = $_POST['MidName'];
        $LastName = $_POST['LastName'];
        $phone = $_POST['phone'];

        // Update personal information with the user_id condition
        $update_sql = "UPDATE users SET FirstName = ?, MidName = ?, LastName = ?, phone = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $FirstName, $MidName, $LastName, $phone, $user_id); // Adding user_id to the query

        if ($stmt->execute()) {
            echo "<p>Personal information updated successfully!</p>";
            header('location: profile.php');
            exit();
        } else {
            echo "<p>Failed to update personal information. Please try again.</p>";
        }
    }
}
?>

<h2>Change Password & Update Personal Info</h2>

<!-- Change Password Section -->
<form action="settings.php" method="POST">
    <h3>Change Password</h3>
    <!-- Current Password -->
    <label for="current_password">Current Password:</label>
    <input type="password" id="current_password" name="current_password" required><br><br>

    <!-- New Password -->
    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password"><br><br>

    <!-- Confirm New Password -->
    <label for="confirm_password">Confirm New Password:</label>
    <input type="password" id="confirm_password" name="confirm_password"><br><br>

    <button type="submit" name="update_password">Update Password</button>
</form>

<br><br>

<!-- Update Personal Information Section -->
<form action="settings.php" method="POST">
    <h3>Update Personal Information</h3>
    <!-- First Name -->
    <label for="FirstName">First Name:</label>
    <input type="text" id="FirstName" name="FirstName" value="<?php echo htmlspecialchars($user['FirstName']); ?>" required><br><br>

    <!-- Middle Name -->
    <label for="MidName">Middle Name:</label>
    <input type="text" id="MidName" name="MidName" value="<?php echo htmlspecialchars($user['MidName']); ?>"><br><br>

    <!-- Last Name -->
    <label for="LastName">Last Name:</label>
    <input type="text" id="LastName" name="LastName" value="<?php echo htmlspecialchars($user['LastName']); ?>" required><br><br>

    <!-- Phone -->
    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required><br><br>

    <button type="submit" name="update_info">Update Personal Info</button>
</form>

<?php include __DIR__ . '/portions/footer.php'; ?>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
