<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<!-- Link External CSS -->
<link rel="stylesheet" href="../static/css/login.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>

<div class="login-card">
    
    <div class="wave-icon">≈≈≈</div>

    <h1>Welcome</h1>
    <p class="subtitle">Sign in to manage your reservations</p>

    <form action="index.php?page=login" method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" placeholder="you@example.com" class="form-input">
        </div>

        <div class="form-group">
            <label>
                Password 
                <a href="#" class="forgot">Forgot password?</a>
            </label>

            <div class="password-wrapper">
                <input type="password" placeholder="Enter your password" class="form-input">
                <span class="eye-icon" onclick="togglePassword()"><i class="fa fa-eye"></i></span>
            </div>
        </div>

        <button type="submit" class="btn-login">Sign In</button>
    </form>

    <div class="footer-text">
        Don’t have an account? <a href="#">Create account</a>
    </div>

    <div class="demo-box">
        Demo: Use <strong>admin@barrmont.com</strong> to access admin dashboard
    </div>

</div>

<script>
function togglePassword() {
    const input = document.querySelector('.password-wrapper input');
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
