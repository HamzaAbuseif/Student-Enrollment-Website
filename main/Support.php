
<?php include __DIR__ . '/portions/header.php'; 

include __DIR__ . '/../database/db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $message = $_POST['message'];
    $status = 'pending';
    
    $stmt = $conn->prepare("INSERT INTO support_requests (user_id, message, status, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $user_id, $message, $status);
    $stmt->execute();
    $stmt->close();
    
    echo "<p class='success-message'>Support request submitted successfully!</p>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Form</title>
    <link rel="stylesheet" href="styles.css">
    <style>
      

        h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .success-message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit a Support Request</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea name="message" required></textarea>
            </div>
            <button type="submit">Submit</button>
        </form>
       
    </div>
</body>
</html>

