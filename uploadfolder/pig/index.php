<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo NAME_X; ?> - Modern Hog Farming System</title>
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
            line-height: 1.6;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 40px;
        }
        
        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .system-name {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }
        
        .system-tagline {
            font-size: 1.5rem;
            margin-bottom: 30px;
            max-width: 800px;
        }
        
        .features-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto 40px;
            padding: 0 20px;
        }
        
        .feature-card {
            flex: 0 0 calc(33.333% - 30px);
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        
        .login-container {
            max-width: 400px;
            margin: 0 auto 60px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .login-title {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 25px;
            color: var(--dark-color);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
        }
        
        .btn {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
        }
        
        .btn:hover {
            background: var(--secondary-color);
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .close {
            float: right;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: 0.5;
            background: transparent;
            border: none;
        }
        
        .tips-section {
            max-width: 1200px;
            margin: 0 auto 60px;
            padding: 0 20px;
        }
        
        .section-title {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 40px;
            color: var(--dark-color);
        }
        
        .tip-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .tip-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        footer {
            background: var(--dark-color);
            color: white;
            padding: 30px 0;
            text-align: center;
        }
        :root {
    --primary-color: #4a6fa5;
    --secondary-color: #6c8fc7;
    --accent-color: #ff7e5f;
    --dark-color: #2c3e50;
    --light-color: #f8f9fa;
    --success-color: #28a745;
}

body {
    position: relative;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f7fa;
    color: #333;
    line-height: 1.6;
}

body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('img/DSC_0054.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
    opacity: 0.3;
    z-index: -1;
    pointer-events: none;
}

/* The rest of your existing CSS remains the same */
        .contact-info {
            margin-top: 20px;
            font-size: 0.9rem;
        }
        
        .contact-info a {
            color: var(--light-color);
            margin: 0 10px;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .contact-info a:hover {
            color: var(--accent-color);
        }
        
        .contact-info i {
            margin-right: 5px;
        }
        
        @media (max-width: 768px) {
            .feature-card {
                flex: 0 0 100%;
            }
            
            .system-name {
                font-size: 2.2rem;
            }
            
            .system-tagline {
                font-size: 1.2rem;
            }
            
            .contact-info {
                display: flex;
                flex-direction: column;
            }
            
            .contact-info a {
                margin: 5px 0;
            }
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="system-name"><?php echo NAME_X; ?></h1>
            <p class="system-tagline">Transforming traditional hog farming into modern, data-driven agriculture with our comprehensive piggery management system</p>
        </div>
    </div>
    
    <div class="features-container">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-piggy-bank"></i>
            </div>
            <h3 class="feature-title">Comprehensive Pig Management</h3>
            <p>Track every aspect of your herd from birth to market with our detailed record-keeping system designed specifically for hog raisers.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="feature-title">Data-Driven Decisions</h3>
            <p>Make informed decisions with real-time analytics on feed conversion, growth rates, and health metrics for optimal farm performance.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="feature-title">Disease Prevention</h3>
            <p>Early warning systems and quarantine management help prevent disease outbreaks before they impact your entire operation.</p>
        </div>
    </div>
    
    <div class="tips-section">
        <h2 class="section-title">Modern Hog Farming Tips</h2>
        
        <div class="tip-card">
            <h3 class="tip-title">Optimal Feeding Strategies</h3>
            <p>Implement phase feeding to match nutritional requirements with growth stages. Our system helps you track feed conversion ratios to maximize efficiency.</p>
        </div>
        
        <div class="tip-card">
            <h3 class="tip-title">Biosecurity Measures</h3>
            <p>Maintain strict biosecurity protocols. Use our quarantine management features to monitor new arrivals and prevent disease introduction.</p>
        </div>
        
        <div class="tip-card">
            <h3 class="tip-title">Breeding Optimization</h3>
            <p>Track sow productivity and genetic lines to identify your best performers. Our system helps you make data-driven breeding decisions.</p>
        </div>
    </div>
    
    <div class="login-container">
        <h2 class="login-title">System Login</h2>
        
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><?php echo $error; ?></strong>
            </div>
        <?php } ?>
        
        <form method="post" autocomplete="off">
            <div class="form-group">
                <label class="control-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="control-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <button name="submit" type="submit" class="btn">Log in</button>
        </form>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php echo NAME_X; ?>. All rights reserved.</p>
        <p>Transforming traditional farming through technology</p>
        
        <div class="contact-info">
            <a href="https://www.facebook.com/profile.php?id=100084915082290" target="_blank">
                <i class="fab fa-facebook"></i> Facebook
            </a>
            <a href="https://www.instagram.com/jadeedmarsesuca/" target="_blank">
                <i class="fab fa-instagram"></i> Instagram
            </a>
            <a href="mailto:jadeedmars@gmail.com">
                <i class="fas fa-envelope"></i> jadeedmars@gmail.com
            </a>
            <a href="mailto:jeesesuca@unp.edu.ph">
                <i class="fas fa-envelope"></i> jeesesuca@unp.edu.ph
            </a>
            <a href="tel:09614889820">
                <i class="fas fa-phone"></i> 09614889820
            </a>
        </div>
    </footer>

    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
        
        // Close alert button
        document.querySelectorAll('.close').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
        
    </script>
</body>

</html>
<?php
// Modify your getClientIP() function to prefer IPv4
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '::1') {
        return $_SERVER['REMOTE_ADDR'];
    }
    return '127.0.0.1'; // Default to IPv4 loopback
}
            if (isset($_POST['submit'])) {
                $username = trim($_POST['username']);
                $password = $_POST['password'];
 $ip_address = getClientIP(); // Get the IP address
                // Hash the password
                $hash = sha1($password);

                // Query to check the user credentials and get the role and status
                $q = $db->query("SELECT * FROM users WHERE username = '$username' AND password = '$hash' LIMIT 1 ");
                $count = $q->rowCount();
                $rows = $q->fetchAll(PDO::FETCH_OBJ);

               if ($count > 0) {
    foreach ($rows as $row) {
        $user_id = $row->id;
        $user = $row->username;
        $role = $row->role;
        $status = $row->status;

        if ($status == 0) {
            $error = 'Your account  is temporarily suspended. Please contact the admin.';
            break;
        }

        $_SESSION['id'] = $user_id;
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $role;

      $db->query("INSERT INTO audit_logs (user_id, action, details, ip_address, status) 
                       VALUES ('$user_id', 'login_success', 'User logged in successfully', '$ip_address', 'success')");
        if ($role == 'admin' || $role == 'owner' || $role == 'employee' || $role == 'veterinarian') {
            header('location: dashboard.php');
            exit();
        }
    }
} else {
    // Log failed login attempt with username info, user_id = 0 or null
   $db->query("INSERT INTO audit_logs (user_id, action, details, ip_address, status) 
                   VALUES (0, 'login_failed', 'Failed login attempt for username: $username', '$ip_address', 'failed')");
    $error = 'Incorrect login details';
}

            }

            if (isset($error)) { ?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><?php echo $error; ?></strong>
    </div>
            <?php }
            ?>
        </div>
    </div>
</div>
<?php include 'theme/foot.php'; ?>