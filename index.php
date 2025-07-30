<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>

<?php
// Fetch info section contents from DB
$info_query = $db->query("SELECT * FROM info_sections LIMIT 1");
$info = $info_query->fetch(PDO::FETCH_ASSOC);

// Fallback values if DB not yet set
$about_text = $info['about_us'] ?? (NAME_X . ' is a comprehensive hog farming management system developed by agricultural technology experts with decades of combined experience in piggery operations. Our mission is to empower farmers with technology that simplifies complex farming operations while improving productivity and profitability through data-driven insights. The system has been field-tested on commercial farms and smallholder operations to ensure it meets the diverse needs of the hog farming community.');
$features_text = $info['features'] ?? '';
$farming_tips_text = $info['farming_tips'] ?? '';
?>

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
        /* [Keep your full CSS here, unchanged] ... */
        body {
            position: relative;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
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
        /* Dashboard Navigation */
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
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-item {
            margin-left: 20px;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: var(--accent-color);
        }
        .nav-link.btn-login {
            background-color: var(--accent-color);
            color: white;
        }
        .nav-link.btn-login:hover {
            background-color: #ff6a45;
        }
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 120px 0 80px;
            margin-bottom: 40px;
            text-align: center;
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
            margin-left: auto;
            margin-right: auto;
        }
        .cta-buttons {
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0 10px;
        }
        .btn-primary {
            background-color: var(--accent-color);
            color: white;
            border: 2px solid var(--accent-color);
        }
        .btn-primary:hover {
            background-color: #ff6a45;
            border-color: #ff6a45;
        }
        .btn-outline {
            background-color: transparent;
            color: white;
            border: 2px solid white;
        }
        .btn-outline:hover {
            background-color: rgba(255,255,255,0.1);
        }
        /* Features Section */
        .section {
            padding: 60px 0;
        }
        .section-title {
            text-align: center;
            font-size: 2.2rem;
            margin-bottom: 50px;
            color: var(--dark-color);
            position: relative;
        }
        .section-title:after {
            content: "";
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--accent-color);
            margin: 15px auto 0;
        }
        .features-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .feature-card {
            flex: 0 0 calc(33.333% - 30px);
            background: white;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        .feature-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        /* About Us Section */
        .about-section {
            background-color: var(--light-color);
        }
        .about-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        .about-text {
            flex: 1;
            padding-right: 40px;
        }
        .about-image {
            flex: 1;
            text-align: center;
        }
        .about-image img {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        /* Login Section */
        .login-section {
            background-color: white;
        }
        .login-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .login-title {
            text-align: center;
            font-size: 1.8rem;
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
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
        }
        .password-wrapper {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
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
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 25px;
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
        /* Tips Section */
        .tips-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .tip-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: flex-start;
        }
        .tip-icon {
            font-size: 1.8rem;
            color: var(--accent-color);
            margin-right: 20px;
            margin-top: 5px;
        }
        .tip-content {
            flex: 1;
        }
        .tip-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        /* Footer */
        footer {
            background: var(--dark-color);
            color: white;
            padding: 50px 0 30px;
            text-align: center;
        }
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            text-align: left;
        }
        .footer-column {
            flex: 0 0 calc(25% - 30px);
            margin-bottom: 30px;
        }
        .footer-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--accent-color);
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-links li {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer-links a:hover {
            color: var(--accent-color);
        }
        .contact-info {
            margin-top: 20px;
        }
        .contact-info a {
            color: #ddd;
            margin: 0 10px;
            text-decoration: none;
            transition: color 0.3s;
            display: inline-block;
            margin-bottom: 10px;
        }
        .contact-info a:hover {
            color: var(--accent-color);
        }
        .contact-info i {
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }
        .copyright {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.9rem;
            color: #aaa;
        }
        /* Responsive Styles */
        @media (max-width: 992px) {
            .feature-card {
                flex: 0 0 calc(50% - 20px);
            }
            .footer-column {
                flex: 0 0 calc(50% - 20px);
            }
            .about-content {
                flex-direction: column;
            }
            .about-text {
                padding-right: 0;
                margin-bottom: 30px;
            }
        }
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            .nav-menu {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                background-color: var(--dark-color);
                flex-direction: column;
                align-items: center;
                padding: 20px 0;
                transition: left 0.3s ease;
            }
            .nav-menu.active {
                left: 0;
            }
            .nav-item {
                margin: 10px 0;
            }
            .feature-card {
                flex: 0 0 100%;
            }
            .system-name {
                font-size: 2.2rem;
            }
            .system-tagline {
                font-size: 1.2rem;
            }
            .btn {
                display: block;
                width: 80%;
                margin: 10px auto;
            }
            .footer-column {
                flex: 0 0 100%;
                text-align: center;
            }
            .footer-links {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    <!-- Dashboard Navigation -->
    <nav class="dashboard-nav">
        <div class="nav-container">
            <a href="index.php" class="nav-brand">
                <i class="fas fa-piggy-bank"></i> <?php echo NAME_X; ?>
            </a>
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item"><a href="#features" class="nav-link">Features</a></li>
                <li class="nav-item"><a href="#about" class="nav-link">About Us</a></li>
                <li class="nav-item"><a href="#tips" class="nav-link">Farming Tips</a></li>
                <li class="nav-item"><a href="#login" class="nav-link btn-login">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="system-name"><?php echo NAME_X; ?></h1>
            <p class="system-tagline">Transforming traditional hog farming into modern, data-driven agriculture with our comprehensive piggery management system</p>
            <div class="cta-buttons">
                <a href="#login" class="btn btn-primary">Get Started</a>
                <a href="#features" class="btn btn-outline">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section">
        <div class="features-container">
            <h2 class="section-title">Key Features</h2>
            <?php if (!empty($features_text)): ?>
                <div style="margin-bottom:40px; text-align:center; font-size:1.15rem; color:#444;">
                    <?= nl2br(htmlspecialchars($features_text)) ?>
                </div>
            <?php endif; ?>
            <div class="features-slider">
                <div class="slider-container">
                    <!-- Feature cards will be duplicated here by JavaScript -->
                </div>
                <div class="slider-controls">
                    <button class="slider-prev"><i class="fas fa-chevron-left"></i></button>
                    <div class="slider-dots"></div>
                    <button class="slider-next"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Features Slider Styles */
        .features-slider {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            overflow: hidden;
        }
        .slider-container {
            display: flex;
            transition: transform 0.5s ease-in-out;
            will-change: transform;
        }
        .slider-feature {
            flex: 0 0 33.333%;
            padding: 0 15px;
            box-sizing: border-box;
            transition: opacity 0.3s ease;
        }
        .slider-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }
        .slider-prev, .slider-next {
            background: var(--primary-color);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .slider-prev:hover, .slider-next:hover {
            background: var(--secondary-color);
        }
        .slider-dots {
            display: flex;
            margin: 0 20px;
        }
        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ddd;
            margin: 0 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .slider-dot.active {
            background: var(--primary-color);
            transform: scale(1.2);
        }
        @media (max-width: 992px) {
            .slider-feature {
                flex: 0 0 50%;
            }
        }
        @media (max-width: 768px) {
            .slider-feature {
                flex: 0 0 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Feature data
            const features = [
                {
                    icon: 'fas fa-piggy-bank',
                    title: 'Comprehensive Pig Management',
                    description: 'Track every aspect of your herd from birth to market with our detailed record-keeping system designed specifically for hog raisers.'
                },
                {
                    icon: 'fas fa-chart-line',
                    title: 'Data-Driven Decisions',
                    description: 'Make informed decisions with real-time analytics on feed conversion, growth rates, and health metrics for optimal farm performance.'
                },
                {
                    icon: 'fas fa-shield-alt',
                    title: 'Disease Prevention',
                    description: 'Early warning systems and quarantine management help prevent disease outbreaks before they impact your entire operation.'
                },
                {
                    icon: 'fas fa-calculator',
                    title: 'Financial Tracking',
                    description: 'Monitor expenses, revenues, and profitability with integrated financial tools tailored for hog farming operations.'
                },
                {
                    icon: 'fas fa-bell',
                    title: 'Automated Alerts',
                    description: 'Receive timely notifications for vaccinations, breeding cycles, and other critical events in your farming calendar.'
                },
                {
                    icon: 'fas fa-mobile-alt',
                    title: 'Mobile Access',
                    description: 'Manage your farm from anywhere with our responsive mobile-friendly interface that works on all devices.'
                }
            ];
            const sliderContainer = document.querySelector('.slider-container');
            const dotsContainer = document.querySelector('.slider-dots');
            let currentSlide = 0;
            let slideInterval;
            const slideDuration = 3000; // 3 seconds per slide
            let slidesPerView = calculateSlidesPerView();
            // Create feature cards and dots
            features.forEach((feature, index) => {
                const featureCard = document.createElement('div');
                featureCard.className = 'slider-feature';
                featureCard.innerHTML = `
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="${feature.icon}"></i>
                        </div>
                        <h3 class="feature-title">${feature.title}</h3>
                        <p>${feature.description}</p>
                    </div>
                `;
                sliderContainer.appendChild(featureCard);
                const dot = document.createElement('div');
                dot.className = 'slider-dot';
                dot.dataset.index = index;
                dot.addEventListener('click', () => goToSlide(index));
                dotsContainer.appendChild(dot);
            });
            // Clone first few slides for infinite loop
            const slides = document.querySelectorAll('.slider-feature');
            for (let i = 0; i < slidesPerView; i++) {
                const clone = slides[i].cloneNode(true);
                sliderContainer.appendChild(clone);
            }
            // Initialize
            updateSlider();
            startSlider();
            // Navigation
            document.querySelector('.slider-prev').addEventListener('click', () => {
                prevSlide();
                resetInterval();
            });
            document.querySelector('.slider-next').addEventListener('click', () => {
                nextSlide();
                resetInterval();
            });
            // Handle window resize
            window.addEventListener('resize', () => {
                slidesPerView = calculateSlidesPerView();
                updateSlider();
            });
            // Functions
            function calculateSlidesPerView() {
                if (window.innerWidth < 768) return 1;
                if (window.innerWidth < 992) return 2;
                return 3;
            }
            function updateSlider() {
                const slideWidth = 100 / slidesPerView;
                const offset = -currentSlide * slideWidth;
                sliderContainer.style.transform = `translateX(${offset}%)`;
                // Update dots
                const dots = document.querySelectorAll('.slider-dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === (currentSlide % features.length));
                });
            }
            function nextSlide() {
                currentSlide++;
                if (currentSlide >= features.length) {
                    currentSlide = 0;
                    sliderContainer.style.transition = 'none';
                    updateSlider();
                    void sliderContainer.offsetWidth;
                    sliderContainer.style.transition = 'transform 0.3s ease-in-out';
                }
                updateSlider();
            }
            function prevSlide() {
                currentSlide--;
                if (currentSlide < 0) {
                    currentSlide = features.length - 1;
                    sliderContainer.style.transition = 'none';
                    updateSlider();
                    void sliderContainer.offsetWidth;
                    sliderContainer.style.transition = 'transform 0.3s ease-in-out';
                }
                updateSlider();
            }
            function goToSlide(index) {
                currentSlide = index;
                updateSlider();
                resetInterval();
            }
            function startSlider() {
                slideInterval = setInterval(nextSlide, slideDuration);
            }
            function resetInterval() {
                clearInterval(slideInterval);
                startSlider();
            }
            // Pause on hover
            sliderContainer.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
            });
            sliderContainer.addEventListener('mouseleave', () => {
                startSlider();
            });
        });
    </script>

    <!-- About Us Section -->
    <section id="about" class="section about-section">
        <div class="about-content">
            <div class="about-text">
                <h2 class="section-title">About Our System</h2>
                <p><?= nl2br(htmlspecialchars($about_text)) ?></p>
            </div>
            <div class="about-image">
                <img src="img/istockphoto-1350796429-612x612.jpg" alt="Modern Hog Farm">
            </div>
        </div>
    </section>

    <!-- Tips Section -->
    <section id="tips" class="section">
        <div class="tips-container">
            <h2 class="section-title">Modern Hog Farming Tips</h2>
            <?php if (!empty($farming_tips_text)): ?>
                <div style="margin-bottom:35px; font-size:1.1rem; color:#446;">
                    <?= nl2br(htmlspecialchars($farming_tips_text)) ?>
                </div>
            <?php endif; ?>
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="tip-content">
                    <h3 class="tip-title">Optimal Feeding Strategies</h3>
                    <p>Implement phase feeding to match nutritional requirements with growth stages. Our system helps you track feed conversion ratios to maximize efficiency and reduce waste while ensuring optimal growth rates.</p>
                </div>
            </div>
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-shield-virus"></i>
                </div>
                <div class="tip-content">
                    <h3 class="tip-title">Biosecurity Measures</h3>
                    <p>Maintain strict biosecurity protocols including foot baths, visitor logs, and equipment sanitation. Use our quarantine management features to monitor new arrivals and prevent disease introduction to your main herd.</p>
                </div>
            </div>
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-dna"></i>
                </div>
                <div class="tip-content">
                    <h3 class="tip-title">Breeding Optimization</h3>
                    <p>Track sow productivity, farrowing rates, and genetic lines to identify your best performers. Our system helps you make data-driven breeding decisions to improve herd genetics and productivity over time.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Section -->
    <section id="login" class="section login-section">
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
                <button name="submit" type="submit" class="btn-submit">Log in</button>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="forgot_password.php" style="color: var(--primary-color); text-decoration: none;">Forgot Password?</a>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3 class="footer-title"><?php echo NAME_X; ?></h3>
                <p>Modern hog farming management system designed to improve productivity and profitability through technology.</p>
            </div>
            <div class="footer-column">
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#tips">Farming Tips</a></li>
                    <li><a href="#login">Login</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3 class="footer-title">Resources</h3>
                <ul class="footer-links">
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Support</a></li>
                    <li><a href="#">API</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3 class="footer-title">Contact Us</h3>
                <div class="contact-info">
                    <a href="https://www.facebook.com/profile.php?id=100084915082290" target="_blank">
                        <i class="fab fa-facebook"></i> Facebook
                    </a>
                    <a href="https://www.instagram.com/jadeedmarsesuca/" target="_blank">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                    <a href="mailto:jadeedmars@gmail.com">
                        <i class="fas fa-envelope"></i> Email Us
                    </a>
                    <a href="tel:09614889820">
                        <i class="fas fa-phone"></i> 09614889820
                    </a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> <?php echo NAME_X; ?>. All rights reserved.</p>
            <p>Transforming traditional farming through technology</p>
        </div>
    </footer>

    <script>
        // Toggle mobile menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navMenu = document.getElementById('navMenu');
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.querySelector('i').classList.toggle('fa-bars');
            this.querySelector('i').classList.toggle('fa-times');
        });
        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
                mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                mobileMenuBtn.querySelector('i').classList.remove('fa-times');
            });
        });
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
        // Close alert button
        document.querySelectorAll('.close').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
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
    $ip_address = getClientIP();
    $hash = sha1($password);
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
                $error = 'Your account is temporarily suspended. Please contact the admin.';
                break;
            }
            $db->query("UPDATE users SET last_login = NOW() WHERE id = '$user_id'");
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
<?php } ?>