<?php
session_start();
$userRole = $_SESSION['userRole']?? null;

?>

<style>

        /* Container */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Heading */
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 36px;
        }

        /* Navigation Bar */
        nav {
            background-color: #333;
            padding: 10px 20px;
            border-radius: 8px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 4px;
        }

        /* Hover effect for navigation links */
        nav ul li a:hover {
            background-color: #4CAF50;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
                align-items: center;
            }

            nav ul li {
                margin-bottom: 10px;
            }
        }
    </style>
<div class="container">
    <h2>SEWA</h2>
    <nav>
        <ul>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <?php if  ($_SESSION['userRole'] ==='admin') { ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="../auth/logout.php">Logout</a></li>

                <?php } else{?>
                    <li><a href="home.php">Home</a></li> 
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="courses.php">Courses</a></li>
                    <li><a href="enrollment.php">Enrolment</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="Support.php">Support</a></li>
                    <li><a href="../auth/logout.php">Logout</a></li>
                <?php } ?>
                
            <?php } else { ?>
                <li><a href="home.php">Home</a></li> 
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="../auth/signup.html">Sign Up</a></li>
                <li><a href="../auth/login.html">Log In</a></li>
            <?php } ?>
        </ul>
    </nav>
</div>
