<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Luxury Resort</title>
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
                    <a href="login.php" id="loginBtn" class="active">Login</a>
                    <a href="register.php" id="registerBtn">Register</a>
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
                    <h1>Login</h1>
                    <p>Sign in to your account</p>

                    <form id="loginForm">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required class="form-input">
                        </div>

                        <div class="form-check">
                            <input type="checkbox" id="rememberMe" name="rememberMe" class="checkbox-input">
                            <label for="rememberMe">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-primary full-width">Sign In</button>
                    </form>

                    <div class="auth-footer">
                        <p>Don't have an account? <a href="register.php">Sign up here</a></p>
                        <p><a href="#">Forgot your password?</a></p>
                    </div>

                    <div id="errorMessage" class="error-message" style="display:none;"></div>
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
