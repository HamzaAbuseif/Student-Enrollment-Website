<!-- Footer style -->
<style>
        /* Footer Section */
        footer {
            background-color: #333;
            color: white;
            padding: 40px 20px;
            font-size: 16px;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .footer-links,
        .footer-contact,
        .footer-social {
            width: 30%;
        }

        .footer-links h4,
        .footer-contact h4,
        .footer-social h4 {
            margin-bottom: 15px;
            font-size: 18px;
            color: #4CAF50;
        }

        .footer-links ul,
        .footer-contact ul,
        .footer-social ul {
            list-style: none;
            padding: 0;
        }

        .footer-links ul li,
        .footer-contact ul li,
        .footer-social ul li {
            margin-bottom: 10px;
        }

        .footer-links ul li a,
        .footer-social ul li a {
            color: white;
            text-decoration: none;
        }

        .footer-links ul li a:hover,
        .footer-social ul li a:hover {
            color: #4CAF50;
        }

        .footer-contact ul li a {
            color: #4CAF50;
            text-decoration: none;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            border-top: 1px solid #777;
            padding-top: 15px;
        }

        .footer-bottom p {
            margin: 0;
        }

        /* Responsive Design for Footer */
        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                align-items: center;
            }

            .footer-links,
            .footer-contact,
            .footer-social {
                width: 100%;
                margin-bottom: 20px;
            }
        }
    </style>
    <!-- Footer Section -->

<footer>
    <div class="footer-container">
        <!-- Quick Links -->
        <div class="footer-links">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="../about.php">About Us</a></li>
                <li><a href="../courses.php">Available Courses</a></li>
                <li><a href="../contact.php">Contact Us</a></li>
            </ul>
        </div>

        <!-- Contact Information -->
        <div class="footer-contact">
            <h4>Contact Information</h4>
            <ul>
                <li>BrightStart Academy</li>
                <li>123 Education Blvd, Amman, Jordan</li>
                <li>Email: <a href="mailto:contact@brightstartacademy.com">contact@brightstartacademy.com</a></li>
                <li>Phone: +962-123-4567</li>
            </ul>
        </div>

        <!-- Social Media Links -->
        <div class="footer-social">
            <h4>Follow Us</h4>
            <ul>
                <li><a href="https://www.facebook.com" target="_blank">Facebook</a></li>
                <li><a href="https://twitter.com" target="_blank">Twitter</a></li>
                <li><a href="https://www.instagram.com" target="_blank">Instagram</a></li>
                <li><a href="https://www.linkedin.com" target="_blank">LinkedIn</a></li>
            </ul>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> BrightStart Academy. All Rights Reserved.</p>
    </div>
</footer>

