<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Luxury Resort</title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">Luxury Resort</div>
            <ul class="nav-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../rooms.php">Rooms</a></li>
                <li><a href="../amenities.php">Amenities</a></li>
                <li><a href="../contact.php">Contact</a></li>
                <li class="auth-links">
                    <a href="login.php" id="loginBtn">Login</a>
                    <a href="register.php" id="registerBtn" class="active">Register</a>
                    <a href="#" id="logoutBtn" style="display:none;" onclick="logout(); return false;">Logout</a>
                </li>
                <li class="admin-link" id="adminLink" style="display:none;">
                    <a href="admin.php">Admin</a>
                </li>
            </ul>
        </div>
    </nav>

    <section class="auth-section">
        <div class="container">
            <div class="auth-container">
                <div class="auth-card">
                    <h1>Create Account</h1>
                    <p>Join us for the best hotel experience</p>

                    <form id="registerForm">
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input type="text" id="fullName" name="fullName" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" required class="form-input">
                        </div>

                        <div class="form-check">
                            <input type="checkbox" id="terms" name="terms" required class="checkbox-input">
                            <label for="terms">I agree to the <a href="#">Terms and Conditions</a></label>
                        </div>

                        <button type="submit" class="btn btn-primary full-width">Create Account</button>
                    </form>

                    <div class="auth-footer">
                        <p>Already have an account? <a href="login.php">Sign in here</a></p>
                    </div>

                    <div id="errorMessage" class="error-message" style="display:none;"></div>
                    <div id="successMessage" class="success-message" style="display:none;"></div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>About Us</h4>
                    <p>Luxury Resort offers world-class hospitality and unforgettable experiences.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="rooms.php">Rooms</a></li>
                        <li><a href="amenities.php">Amenities</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Email: info@luxuryresort.com</p>
                    <p>Phone: +1-800-RESORT</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Luxury Resort. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="app.js"></script>
    <script src="auth.js"></script>
</body>
</html>
