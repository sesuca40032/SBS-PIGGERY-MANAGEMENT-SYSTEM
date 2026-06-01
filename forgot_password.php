<?php
include 'setting/system.php';
include 'theme/head.php';

$error = '';
$message = '';
$showOtpForm = false;
$showResetForm = false;

// Step 1: User submits phone, show OTP form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['phone'])) {
    $phone = trim($_POST['phone']);
    // For your system, don't convert to +63 format, keep as entered (must match your registration format)
    $_SESSION['phone'] = $phone;
    $showOtpForm = true;
    $message = "A one-time password (OTP) has been sent. (For demo use 123456)";
}

// Step 2: User submits OTP
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    $userOtp = $_POST['otp_input'] ?? '';
    if ($userOtp === '123456') {
        $message = "OTP verified! You can now reset your password.";
        $showResetForm = true;
    } else {
        $error = "Incorrect OTP. Please try again. Hint: OTP is 123456";
        $showOtpForm = true;
    }
}

// Step 3: Password reset
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = $_SESSION['phone'] ?? '';

    if (empty($new_password) || empty($confirm_password)) {
        $error = "Both password fields are required.";
        $showResetForm = true;
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
        $showResetForm = true;
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
        $showResetForm = true;
    } elseif (empty($phone)) {
        $error = "Session expired or phone number missing. Please start again.";
    } else {
        // Use sha1 to match your add-user registration logic
        $hashed = sha1($new_password);
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=pig', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE contact_number = :phone");
            $stmt->execute([':password' => $hashed, ':phone' => $phone]);
            if ($stmt->rowCount() > 0) {
                $message = "Password reset successfully! You may now <a href='index.php'>login</a>.";
                session_unset();
                session_destroy();
            } else {
                $error = "Password reset failed. Contact number not found or already updated.";
                $showResetForm = true;
            }
        } catch (Exception $e) {
            $error = "Database error: " . htmlspecialchars($e->getMessage());
            $showResetForm = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo NAME_X; ?> - Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #6c8fc7;
            --accent-color: #ff7e5f;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --success-color: #28a745;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }
        
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('img/DSC_0054.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            opacity: 0.15;
            z-index: -1;
            pointer-events: none;
        }
        
        .forgot-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .forgot-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            padding: 50px 40px;
            width: 100%;
            max-width: 450px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .logo-container {
            margin-bottom: 30px;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
        
        .system-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .system-subtitle {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 30px;
        }
        
        .forgot-form {
            text-align: left;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.95rem;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 18px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fbff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 4px rgba(74, 111, 165, 0.1);
            background: #fff;
        }
        
        .otp-group {
            display: flex;
            align-items: center;
            background: #f8fbff;
            border-radius: 10px;
            padding: 15px 18px;
            border: 2px solid #e1e5e9;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .otp-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(74, 111, 165, 0.1);
            background: #fff;
        }
        
        .otp-group i {
            color: var(--primary-color);
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        .otp-group input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 1rem;
            width: 100%;
            padding: 0;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }
        
        .toggle-password:hover {
            color: var(--primary-color);
        }
        
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            border: none;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(74, 111, 165, 0.3);
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 111, 165, 0.4);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-link a:hover {
            color: var(--accent-color);
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            border: 1px solid transparent;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .alert-success a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .alert-success a:hover {
            color: var(--accent-color);
        }
        
        .close {
            float: right;
            font-size: 1.2rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: 0.5;
            background: transparent;
            border: none;
            margin-left: 10px;
            cursor: pointer;
        }
        
        .close:hover {
            opacity: 0.8;
        }
        
        @media (max-width: 480px) {
            .forgot-box {
                padding: 30px 25px;
                margin: 10px;
            }
            
            .system-title {
                font-size: 1.5rem;
            }
            
            .logo {
                max-width: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-box">
            <div class="logo-container">
                <img src="logo1 wo back.png" alt="<?php echo NAME_X; ?> Logo" class="logo">
                <h1 class="system-title"><?php echo NAME_X; ?></h1>
                <p class="system-subtitle">Password Recovery</p>
        </div>
            
            <?php if ($error) { ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><?php echo $error; ?></strong>
                </div>
            <?php } elseif ($message) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $message; ?>
                </div>
            <?php } ?>

            <?php if ($showOtpForm) { ?>
                <!-- OTP Input Form -->
                <form method="post" autocomplete="off" class="forgot-form">
                    <div class="form-group">
                        <label class="form-label">Enter OTP Code</label>
                    <div class="otp-group">
                        <i class="fa-solid fa-key"></i>
                        <input type="text" name="otp_input" pattern="\d{4,8}" maxlength="8" placeholder="Enter OTP here" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit" name="verify_otp">
                        <i class="fa-solid fa-shield-check" style="margin-right: 8px;"></i>
                        Verify OTP
                    </button>
                </form>
                <div class="back-link">
                    <a href="index.php">
                        <i class="fas fa-arrow-left"></i>
                        Back to Login
                    </a>
                </div>
            <?php } elseif ($showResetForm) { ?>
                <!-- Password Reset Form -->
                <form method="post" autocomplete="off" class="forgot-form">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="new_password" id="new_password" class="form-control" required placeholder="Enter new password">
                            <i class="fas fa-eye toggle-password" id="toggleNewPassword"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required placeholder="Confirm new password">
                            <i class="fas fa-eye toggle-password" id="toggleConfirmPassword"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit" name="reset_password">
                        <i class="fas fa-save" style="margin-right: 8px;"></i>
                        Reset Password
                    </button>
                </form>
                <div class="back-link">
                    <a href="index.php">
                        <i class="fas fa-arrow-left"></i>
                        Back to Login
                    </a>
                </div>
            <?php } else { ?>
                <!-- Phone Input Form -->
                <form method="post" autocomplete="off" class="forgot-form">
                    <div class="form-group">
                        <label class="form-label">Enter your mobile number</label>
                        <div class="otp-group">
                            <i class="fa-solid fa-mobile-screen-button"></i>
                            <input type="text" name="phone" maxlength="13" placeholder="e.g. 09614889820" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-paper-plane" style="margin-right: 8px;"></i>
                        Send OTP
                    </button>
                </form>
                <div class="back-link">
                    <a href="index.php">
                        <i class="fas fa-arrow-left"></i>
                        Back to Login
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        // Toggle password visibility for new password
        const toggleNewPassword = document.querySelector('#toggleNewPassword');
        const newPassword = document.querySelector('#new_password');
        
        if(toggleNewPassword && newPassword) {
            toggleNewPassword.addEventListener('click', function () {
                const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                newPassword.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
        
        // Toggle password visibility for confirm password
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#confirm_password');
        
        if(toggleConfirmPassword && confirmPassword) {
            toggleConfirmPassword.addEventListener('click', function () {
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
        
        // Close alert button
        document.querySelectorAll('.close').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
        
        // Clear form on page load
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                if (form) {
                    form.reset();
                }
            });
        });
    </script>
</body>
</html>