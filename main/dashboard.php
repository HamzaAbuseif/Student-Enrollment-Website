<?php
// dashboard.php
include __DIR__ . '/portions/header.php';
include __DIR__ . '/../database/db.php';

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add/Edit operations
    $table = $_POST['table'];
    $action = $_POST['action'];
    
    try {
        if ($action === 'add') {
            // Handle insert
            $columns = [];
            $values = [];
            foreach ($_POST as $key => $value) {
                if ($key !== 'table' && $key !== 'action') {
                    $columns[] = $key;
                    $values[] = "'".$conn->real_escape_string($value)."'";
                }
            }
            $sql = "INSERT INTO $table (".implode(',', $columns).") VALUES (".implode(',', $values).")";
            $conn->query($sql);
        } elseif ($action === 'edit') {
            // Handle update
            $updates = [];
            $id = $_POST['id'];
            foreach ($_POST as $key => $value) {
                if (!in_array($key, ['table', 'action', 'id'])) {
                    $updates[] = "$key = '".$conn->real_escape_string($value)."'";
                }
            }
            $sql = "UPDATE $table SET ".implode(',', $updates)." WHERE ".getPrimaryKey($table)." = $id";
            $conn->query($sql);
        }
    } catch (Exception $e) {
        echo "<div class='error'>Error: ".$e->getMessage()."</div>";
    }
}

if (isset($_GET['delete'])) {
    // Handle delete
    $table = $_GET['table'];
    $id = $_GET['id'];
    try {
        $sql = "DELETE FROM $table WHERE ".getPrimaryKey($table)." = $id";
        $conn->query($sql);
    } catch (Exception $e) {
        echo "<div class='error'>Error: ".$e->getMessage()."</div>";
    }
}

function getPrimaryKey($table) {
    $primaryKeys = [
        'users' => 'user_id',
        'courses' => 'course_id',
        'enrollments' => 'enrollment_id',
        'support_requests' => 'request_id',
        'contact_messages' => 'id'
    ];
    return $primaryKeys[$table];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... existing head content ... -->
    <style>
/* Unified Table Styles */
.table-container {
    overflow-x: auto;
    margin: 20px 0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95em;
    background: white;
}

.styled-table thead tr {
    background: #2c3e50;
    color: #fff;
}

.styled-table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.styled-table td {
    padding: 12px 15px;
    vertical-align: top;
}

.styled-table tbody tr {
    border-bottom: 1px solid #ecf0f1;
    transition: background 0.2s ease;
}

.styled-table tbody tr:nth-of-type(even) {
    background: #f8f9fa;
}

.styled-table tbody tr:hover {
    background: #f1f4f7;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #2c3e50;
}

/* Form Elements in Tables */
.styled-table input,
.styled-table select,
.styled-table textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #bdc3c7;
    border-radius: 4px;
    font-size: 0.95em;
    transition: border-color 0.2s ease;
}

.styled-table input:focus,
.styled-table select:focus,
.styled-table textarea:focus {
    border-color: #3498db;
    outline: none;
}

.styled-table textarea {
    resize: vertical;
    min-height: 60px;
}

/* Action Buttons */
.action-btns {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9em;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: opacity 0.2s ease;
}

.btn:hover {
    opacity: 0.9;
}

.edit-btn {
    background: #3498db;
    color: white;
}

.delete-btn {
    background: #e74c3c;
    color: white;
}

.save-btn {
    background: #2ecc71;
    color: white;
    padding: 8px 20px;
}

.add-btn {
    background: #27ae60;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: 500;
}

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 500;
}

.status-open { background: #f1c40f; color: #2c3e50; }
.status-in-progress { background: #3498db; color: white; }
.status-resolved { background: #2ecc71; color: white; }

/* Date Styling */
.date-cell {
    white-space: nowrap;
    color: #7f8c8d;
    font-size: 0.9em;
}

/* Error/Success Messages */
.alert {
    padding: 15px;
    border-radius: 4px;
    margin: 15px 0;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

/* Enrollment Date Picker */
.datetime-picker {
    max-width: 200px;
    padding: 8px;
    border: 1px solid #bdc3c7;
    border-radius: 4px;
}

/* Student Count Display */
.student-count {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ecf0f1;
    padding: 4px 10px;
    border-radius: 15px;
}

.current-students {
    color: #27ae60;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .styled-table {
        font-size: 0.9em;
    }
    
    .styled-table th,
    .styled-table td {
        padding: 10px;
    }
    
    .action-btns {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}

    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <!-- Users Section -->
<div class="dashboard-section">
    <h2>Users 
        <button class="add-btn" onclick="toggleForm('user-form')">➕ Add User</button>
    </h2>
    
    <!-- Add User Form -->
    <form id="user-form" class="crud-form card" method="POST" style="display: none;">
        <div class="form-grid">
            <input type="hidden" name="table" value="users">
            <input type="hidden" name="action" value="add">
            
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="FirstName" required>
            </div>
            
            <div class="form-group">
                <label>Middle Name:</label>
                <input type="text" name="MidName">
            </div>
            
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="LastName" required>
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="tel" name="phone" required>
            </div>
            
            <div class="form-group">
                <label>Role:</label>
                <select name="userRole" required>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group full-width">
                <button type="submit" class="save-btn">💾 Save User</button>
            </div>
        </div>
    </form>

    <!-- Users Table -->
    <?php
    $users = $conn->query("SELECT * FROM users");
    if ($users->num_rows > 0) {
        echo '<div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
        
        while($row = $users->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['user_id']}</td>
                    <td>
                        <form method='POST' class='inline-form'>
                            <input type='hidden' name='table' value='users'>
                            <input type='hidden' name='action' value='edit'>
                            <input type='hidden' name='id' value='{$row['user_id']}'>
                            <div class='form-grid'>
                                <input type='text' name='FirstName' value='".htmlspecialchars($row['FirstName'])."'>
                                <input type='text' name='MidName' value='".htmlspecialchars($row['MidName'])."'>
                                <input type='text' name='LastName' value='".htmlspecialchars($row['LastName'])."'>
                            </div>
                    </td>
                    <td>
                        <input type='tel' name='phone' value='{$row['phone']}'>
                    </td>
                    <td>
                        <select name='userRole'>
                            <option value='student'".($row['userRole']=='student'?'selected':'').">Student</option>
                            <option value='admin'".($row['userRole']=='admin'?'selected':'').">Admin</option>
                        </select>
                    </td>
                    <td>
                        <input type='email' name='email' value='".htmlspecialchars($row['email'])."'>
                    </td>
                    <td class='date-cell'>".date('M d, Y', strtotime($row['created_at']))."</td>
                    <td>
                        <div class='action-btns'>
                            <button type='submit' class='btn edit-btn'>✏️ Update</button>
                            </form>
                            <a href='?delete=1&table=users&id={$row['user_id']}' 
                               class='btn delete-btn' 
                               onclick='return confirm(\"Delete this user?\")'>🗑️ Delete</a>
                        </div>
                    </td>
                </tr>";
        }
        echo '</tbody></table></div>';
    } else {
        echo "<div class='no-data'>No users found</div>";
    }
    ?>
</div>


    <div class="dashboard-section">
    <h2>Courses 
        <button class="add-btn" onclick="toggleForm('course-form')">➕ Add New Course</button>
    </h2>
    
    <!-- Add Course Form -->
    <form id="course-form" class="crud-form card" method="POST" style="display: none;">
        <div class="form-grid">
            <input type="hidden" name="table" value="courses">
            <input type="hidden" name="action" value="add">
            
            <div class="form-group">
                <label>Course Name:</label>
                <input type="text" name="course_name" required>
            </div>
            
            <div class="form-group">
                <label>Duration:</label>
                <input type="text" name="duration" placeholder="e.g., 12 weeks" required>
            </div>
            
            <div class="form-group">
                <label>Fee ($):</label>
                <input type="number" name="fee" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label>Max Students:</label>
                <input type="number" name="max_students" min="1" required>
            </div>
            
            <div class="form-group full-width">
                <label>Description:</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            
            <div class="form-group full-width">
                <button type="submit" class="save-btn">💾 Save Course</button>
            </div>
        </div>
    </form>

    <!-- Courses Table -->
    <?php
    $courses = $conn->query("SELECT * FROM courses");
    if ($courses->num_rows > 0) {
        echo '<div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course Name</th>
                        <th>Duration</th>
                        <th>Fee</th>
                        <th>Students</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
        
        while($row = $courses->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['course_id']}</td>
                    <td>
                        <form method='POST' class='inline-form'>
                            <input type='hidden' name='table' value='courses'>
                            <input type='hidden' name='action' value='edit'>
                            <input type='hidden' name='id' value='{$row['course_id']}'>
                            <input type='text' name='course_name' value='".htmlspecialchars($row['course_name'])."'>
                    </td>
                    <td>
                        <input type='text' name='duration' value='{$row['duration']}'>
                    </td>
                    <td>
                        $<input type='number' name='fee' step='0.01' value='{$row['fee']}' style='width: 80px;'>
                    </td>
                    <td>
                        <div class='student-count'>
                            <span class='current'>{$row['current_students']}</span>/
                            <input type='number' name='max_students' value='{$row['max_students']}' min='1' style='width: 60px;'>
                        </div>
                    </td>
                    <td>
                        <textarea name='description' rows='2'>".htmlspecialchars($row['description'])."</textarea>
                    </td>
                    <td>".date('M d, Y', strtotime($row['created_at']))."</td>
                    <td>
                        <button type='submit' class='edit-btn'>✏️ Update</button>
                        </form>
                        <a href='?delete=1&table=courses&id={$row['course_id']}' 
                           class='delete-btn' 
                           onclick='return confirm(\"Delete this course?\")'>🗑️ Delete</a>
                    </td>
                </tr>";
        }
        echo '</tbody></table></div>';
    } else {
        echo "<div class='no-data'>No courses found</div>";
    }
    ?>
</div>



    <!-- ================== ENROLLMENTS SECTION ================== -->
   <!-- ================== ENROLLMENTS SECTION ================== -->
<div class="dashboard-section">
    <h2>Enrollments 
        <button class="add-btn" onclick="toggleForm('enrollment-form')">➕ New Enrollment</button>
    </h2>

    <!-- Add Enrollment Form -->
    <form id="enrollment-form" class="crud-form card" method="POST" style="display: none;">
        <div class="form-grid">
            <input type="hidden" name="table" value="enrollments">
            <input type="hidden" name="action" value="add">
            
            <?php
            $users = $conn->query("SELECT user_id, CONCAT(FirstName, ' ', LastName) AS name FROM users");
            $courses = $conn->query("SELECT course_id, course_name FROM courses");
            ?>
            
            <div class="form-group">
                <label>User:</label>
                <select name="user_id" required>
                    <option value="">Select User</option>
                    <?php while($user = $users->fetch_assoc()): ?>
                        <option value="<?= $user['user_id'] ?>"><?= $user['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Course:</label>
                <select name="course_id" required>
                    <option value="">Select Course</option>
                    <?php while($course = $courses->fetch_assoc()): ?>
                        <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Enrollment Date:</label>
                <input type="datetime-local" name="enrollment_date" required>
            </div>

            <div class="form-group full-width">
                <button type="submit" class="save-btn">💾 Save Enrollment</button>
            </div>
        </div>
    </form>

    <!-- Enrollments Table -->
    <?php
    $enrollments = $conn->query("
        SELECT e.*, u.FirstName, u.LastName, c.course_name 
        FROM enrollments e
        JOIN users u ON e.user_id = u.user_id
        JOIN courses c ON e.course_id = c.course_id
    ");
    
    if ($enrollments->num_rows > 0) {
        echo '<div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Course</th>
                        <th>Enrollment Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
        
        while($row = $enrollments->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['enrollment_id']}</td>
                    <td>{$row['FirstName']} {$row['LastName']}</td>
                    <td>{$row['course_name']}</td>
                    <td>
                        <form method='POST' class='inline-form'>
                            <input type='hidden' name='table' value='enrollments'>
                            <input type='hidden' name='action' value='edit'>
                            <input type='hidden' name='id' value='{$row['enrollment_id']}'>
                            <input type='datetime-local' name='enrollment_date' 
                                   value='".date('Y-m-d\TH:i', strtotime($row['enrollment_date']))."'>
                    </td>
                    <td>
                        <div class='action-btns'>
                            <button type='submit' class='btn edit-btn'>✏️ Update</button>
                            </form>
                            <a href='?delete=1&table=enrollments&id={$row['enrollment_id']}' 
                               class='btn delete-btn' 
                               onclick='return confirm(\"Delete this enrollment?\")'>🗑️ Delete</a>
                        </div>
                    </td>
                </tr>";
        }
        echo '</tbody></table></div>';
    } else {
        echo "<div class='no-data'>No enrollments found</div>";
    }
    ?>
</div>

    <!-- ================== REQUESTS SECTION ================== -->
<div class="dashboard-section">
    <h2>Support Requests</h2>
    
    <?php
    $requests = $conn->query("
        SELECT r.*, u.FirstName, u.LastName 
        FROM support_requests r
        JOIN users u ON r.user_id = u.user_id
    ");
    
    if ($requests->num_rows > 0) {
        echo '<div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
        
        while($row = $requests->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['request_id']}</td>
                    <td>{$row['FirstName']} {$row['LastName']}</td>
                    <td>
                        <textarea name='message' form='request-edit-{$row['request_id']}' readonly>
                            ".htmlspecialchars($row['message'])."
                        </textarea>
                    </td>
                    <td>
                        <form method='POST' id='request-edit-{$row['request_id']}'>
                            <input type='hidden' name='table' value='support_requests'>
                            <input type='hidden' name='action' value='edit'>
                            <input type='hidden' name='id' value='{$row['request_id']}'>
                            <select name='status' class='status-badge status-".strtolower($row['status'])."'>
                                <option value='open' ".($row['status']=='Pending'?'selected':'').">Pending</option>
                                <option value='resolved' ".($row['status']=='resolved'?'selected':'').">Resolved</option>
                            </select>
                    </td>
                    <td class='date-cell'>".date('M d, Y H:i', strtotime($row['created_at']))."</td>
                    <td>
                        <div class='action-btns'>
                            <button type='submit' class='btn edit-btn'>✏️ Update</button>
                            </form>
                            <a href='?delete=1&table=support_requests&id={$row['request_id']}' 
                               class='btn delete-btn' 
                               onclick='return confirm(\"Delete this request?\")'>🗑️ Delete</a>
                        </div>
                    </td>
                </tr>";
        }
        echo '</tbody></table></div>';
    } else {
        echo "<div class='no-data'>No requests found</div>";
    }
    ?>
</div>
  <!-- ================== SUBMISSIONS SECTION ================== -->
<div class="dashboard-section">
    <h2>Contact Submissions</h2>
    
    <?php
    $submissions = $conn->query("SELECT * FROM contact_messages");
    
    if ($submissions->num_rows > 0) {
        echo '<div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
        
        while($row = $submissions->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>
                        <input type='text' name='name' value='".htmlspecialchars($row['name'])."'
                               form='submission-edit-{$row['id']}' readonly>
                    </td>
                    <td>
                        <input type='email' name='email' value='".htmlspecialchars($row['email'])."'
                               form='submission-edit-{$row['id']}' readonly>
                    </td>
                    <td>
                        <textarea name='message' form='submission-edit-{$row['id']}' readonly>
                            ".htmlspecialchars($row['message'])."
                        </textarea>
                    </td>
                    <td class='date-cell'>".date('M d, Y H:i', strtotime($row['submitted_at']))."</td>
                    <td>
                        <div class='action-btns'>
                            <a href='?delete=1&table=contact_messages&id={$row['id']}' 
                               class='btn delete-btn' 
                               onclick='return confirm(\"Delete this submission?\")'>🗑️ Delete</a>
                        </div>
                    </td>
                </tr>";
        }
        echo '</tbody></table></div>';
    } else {
        echo "<div class='no-data'>No submissions found</div>";
    }
    ?>
</div>

    <script>
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>