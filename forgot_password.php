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
                $message = "Password reset successfully! You may now <a href='login.php'>login</a>.";
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
    <title>Forgot Password</title>
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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            margin: 0;
            padding: 0;
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
            opacity: 0.3;
            z-index: -1;
            pointer-events: none;
        }
        .dashboard-nav {
            background-color: var(--dark-color);
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        .nav-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .nav-brand i {
            margin-right: 10px;
            color: var(--accent-color);
        }
        .forgot-section {
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .forgot-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
            padding: 40px 28px 30px 28px;
        }
        .forgot-title {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 30px;
            color: var(--dark-color);
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-control {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.10);
        }
        .btn-submit {
            display: block;
            width: 100%;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: var(--secondary-color);
        }
        .alert {
            padding: 13px 14px;
            border-radius: 4px;
            margin-bottom: 24px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .otp-group {
            display: flex;
            align-items: center;
            background: #f1f3f4;
            border-radius: 8px;
            padding: 9px 12px;
            border: 1px solid #e0e0e0;
            margin-bottom: 18px;
        }
        .otp-group i {
            color: #888;
            margin-right: 8px;
            font-size: 1.1rem;
        }
        .otp-group input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 1rem;
            width: 100%;
            padding: 6px 0;
        }
        .forgot-links {
            text-align: center;
            margin-top: 20px;
        }
        .forgot-links a {
            color: var(--primary-color);
            text-decoration: none;
        }
        .forgot-links a:hover {
            text-decoration: underline;
        }
        @media (max-width: 480px) {
            .forgot-container {
                padding: 20px 6px 16px 6px;
            }
        }
    </style>
</head>
<body>
    <!-- Dashboard Navigation -->
    <nav class="dashboard-nav">
        <div class="nav-container">
            <a href="index.php" class="nav-brand">
                <i class="fas fa-piggy-bank"></i> Forgot Password
            </a>
        </div>
    </nav>
    <section class="forgot-section">
        <div class="forgot-container">
            <h2 class="forgot-title"><i class="fas fa-key"></i> Forgot Password</h2>
            <?php if ($error) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } elseif ($message) { ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php } ?>

            <?php if ($showOtpForm) { ?>
                <!-- OTP Input Form -->
                <form method="post" autocomplete="off">
                    <div class="otp-group">
                        <i class="fa-solid fa-key"></i>
                        <input type="text" name="otp_input" pattern="\d{4,8}" maxlength="8" placeholder="Enter OTP here" required>
                    </div>
                    <button type="submit" class="btn-submit" name="verify_otp"><i class="fa-solid fa-shield-check"></i> Verify OTP</button>
                </form>
            <?php } elseif ($showResetForm) { ?>
                <!-- Password Reset Form -->
                <form method="post" autocomplete="off">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn-submit" name="reset_password"><i class="fas fa-save"></i> Reset Password</button>
                </form>
                <div class="forgot-links">
                    <a href="login.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
                </div>
            <?php } else { ?>
                <!-- Phone Input Form -->
                <form method="post" autocomplete="off">
                    <div class="form-group">
                        <label>Enter your mobile number</label>
                        <div class="otp-group">
                            <i class="fa-solid fa-mobile-screen-button"></i>
                            <input type="text" name="phone" maxlength="13" placeholder="e.g. 09614889820" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit"><i class="fa-solid fa-paper-plane"></i> Send OTP</button>
                </form>
                <div class="forgot-links">
                    <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
                </div>
            <?php } ?>
        </div>
    </section>
</body>
</html>