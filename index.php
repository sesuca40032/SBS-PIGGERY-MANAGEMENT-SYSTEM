<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>

<?php
// Fetch info section contents from DB
$info_query = $db->query("SELECT * FROM info_sections LIMIT 1");
$info = $info_query->fetch(PDO::FETCH_ASSOC);

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
            --header-height: 52px;
            --footer-height: 36px;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            position: relative;
            height: 100vh;
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
            opacity: 0.22;
            z-index: -1;
            pointer-events: none;
        }
        .dashboard-nav {
            background-color: var(--dark-color);
            color: white;
            height: var(--header-height);
            min-height: var(--header-height);
            max-height: var(--header-height);
            padding: 0;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: var(--header-height);
            min-height: var(--header-height);
            padding: 0 14px;
        }
        .nav-brand {
            font-size: 1.18rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .nav-brand i { margin-right: 7px; color: var(--accent-color);}
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            height: var(--header-height);
            align-items: center;
        }
        .nav-item { margin-left: 14px;}
        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 0.99rem;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.08);
            color: var(--accent-color);
        }
        .nav-link.btn-login {
            background-color: var(--accent-color);
            color: white;
            padding: 6px 16px;
        }
        .nav-link.btn-login:hover {
            background-color: #ff6a45;
        }
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.35rem;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .mobile-menu-btn { display: block; }
            .nav-menu {
                position: fixed;
                top: var(--header-height);
                left: -100%;
                width: 100%;
                background-color: var(--dark-color);
                flex-direction: column;
                align-items: center;
                padding: 12px 0;
                transition: left 0.3s ease;
                height: auto;
            }
            .nav-menu.active { left: 0; }
            .nav-item { margin: 8px 0;}
        }

        #main-content {
            position: absolute;
            top: var(--header-height);
            left: 0;
            right: 0;
            bottom: var(--footer-height);
            overflow: hidden;
            z-index: 10;
            width: 100vw;
            height: calc(100vh - var(--header-height) - var(--footer-height));
            min-height: calc(100vh - var(--header-height) - var(--footer-height));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }
        .tab-screen {
            display: none;
            width: 100vw;
            height: 100%;
            max-width: 100vw;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            animation: fadeIn 0.32s;
            overflow: hidden;
        }
        .tab-screen.active {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100%;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px);}
            to { opacity: 1; transform: translateY(0);}
        }
        /* Section Titles */
        .section-title {
            text-align: center;
            font-size: 1.48rem;
            margin: 20px 0 16px;
            color: var(--dark-color);
            position: relative;
        }
        .section-title:after {
            content: "";
            display: block;
            width: 45px;
            height: 2px;
            background-color: var(--accent-color);
            margin: 6px auto 0;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            width: 100vw;
            min-height: 181px;
            padding: 24px 0 14px;
            text-align: center;
        }
        .system-name {
            font-size: 2.1rem;
            font-weight: 700;
            margin-bottom: 12px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.13);
        }
        .system-tagline {
            font-size: 1.08rem;
            margin-bottom: 17px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .cta-buttons {
            margin-top: 12px;
        }
        .btn {
            display: inline-block;
            padding: 8px 22px;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0 7px;
            font-size: 0.98rem;
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
            background-color: rgba(255,255,255,0.09);
        }

        /* Features Section */
        .features-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 900px;
            margin: 0 auto;
            gap: 22px;
        }
        .feature-card {
            flex: 0 0 270px;
            background: white;
            border-radius: 8px;
            padding: 21px 15px;
            margin-bottom: 6px;
            box-shadow: 0 5px 12px rgba(0,0,0,0.04);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(0,0,0,0.08);
        }
        .feature-icon {
            font-size: 2.1rem;
            color: var(--primary-color);
            margin-bottom: 11px;
        }
        .feature-title {
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 9px;
            color: var(--dark-color);
        }
        @media (max-width: 1050px) {
            .features-container { max-width: 760px; }
            .feature-card { flex: 0 0 48%; }
        }
        @media (max-width: 700px) {
            .features-container { max-width: 98vw; }
            .feature-card { flex: 0 0 95vw; margin: 0 auto 11px auto; }
        }

        /* About Section */
        .about-content {
            max-width: 820px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 28px;
            padding: 0 12px;
        }
        .about-text { flex: 1; }
        .about-image { flex: 1; text-align: center;}
        .about-image img {
            max-width: 86%;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.07);
        }

        /* Tips Section */
        .tips-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 0 10px;
        }
        .tip-card {
            background: white;
            border-radius: 8px;
            padding: 17px;
            margin-bottom: 14px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: flex-start;
        }
        .tip-icon {
            font-size: 1.3rem;
            color: var(--accent-color);
            margin-right: 13px;
            margin-top: 4px;
        }
        .tip-content { flex: 1; }
        .tip-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--primary-color);
        }

        /* Learn More (Team/Why) Section */
        .learnmore-content {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 0 10px;
            height: 100%;
        }
        .learnmore-desc {
            background: white;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            padding: 38px 28px 26px 28px;
            margin-bottom: 30px;
            text-align: center;
            font-size: 1.15rem;
            color: #222;
            max-width: 900px;
        }
        .team-title {
            font-size: 1.22rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 28px;
            text-align: center;
            letter-spacing: 0.02em;
        }
        .team-members {
            display: flex;
            flex-wrap: wrap;
            gap: 38px;
            justify-content: center;
        }
        .team-member {
            background: linear-gradient(135deg, #e6f0fa 0%, #fef6f0 100%);
            border-radius: 14px;
            box-shadow: 0 8px 34px rgba(74,111,165,0.09);
            padding: 30px 22px 22px 22px;
            text-align: center;
            width: 290px;
            min-width: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: box-shadow 0.3s, transform 0.3s;
            border: 2px solid #f7f7f7;
        }
        .team-member:hover {
            box-shadow: 0 16px 48px rgba(74,111,165,0.15);
            transform: translateY(-5px) scale(1.03);
        }
        .team-photo {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 16px;
            box-shadow: 0 4px 16px rgba(74,111,165,0.15);
            border: 3px solid var(--primary-color);
            background: #fff;
        }
        .team-name {
            font-size: 1.17rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 7px;
        }
        .team-role {
            font-size: 1.03rem;
            color: #444;
            margin-top: 4px;
            font-weight: 500;
            margin-bottom: 5px;
        }
        .team-location {
            font-size: 0.98rem;
            color: #888;
            margin-top: 4px;
            font-style: italic;
        }

        /* Login Section */
        .login-section-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100vw;
        }
        .login-container {
            max-width: 440px;
            width: 100%;
            margin: 0 auto;
            background: linear-gradient(135deg, #f7fbff 0%, #fef6f0 100%);
            padding: 42px 35px 35px 35px;
            border-radius: 18px;
            box-shadow: 0 14px 50px rgba(74,111,165,0.12);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .login-title {
            text-align: center;
            font-size: 1.43rem;
            margin-bottom: 25px;
            color: var(--dark-color);
            font-weight: 700;
            letter-spacing: 0.01em;
        }
        .form-group { margin-bottom: 18px;}
        .form-control {
            width: 100%;
            padding: 15px 18px;
            border: 1px solid #ccd;
            border-radius: 5px;
            font-size: 1.08rem;
            transition: border-color 0.3s;
            background: #f8fbff;
            box-shadow: 0 3px 12px rgba(74,111,165,0.04);
        }
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 4px rgba(74, 111, 165, 0.10);
            background: #fff;
        }
        .password-wrapper { position: relative;}
        .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            font-size: 1.22rem;
        }
        .btn-submit {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            border: none;
            padding: 14px;
            font-size: 1.18rem;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            margin-top: 12px;
        }
        .btn-submit:hover { background: linear-gradient(135deg, var(--accent-color) 0%, var(--primary-color) 100%); transform: scale(1.02);}
        .alert {
            padding: 13px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 1.05rem;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .close {
            float: right;
            font-size: 1.19rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: 0.5;
            background: transparent;
            border: none;
            margin-left: 6px;
        }

        /* Footer */
        footer {
            background: var(--dark-color);
            color: white;
            height: var(--footer-height);
            min-height: var(--footer-height);
            max-height: var(--footer-height);
            line-height: var(--footer-height);
            text-align: center;
            position: fixed;
            left: 0; right: 0; bottom: 0;
            z-index: 300;
            font-size: 0.94rem;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.05);
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 12px;
            height: var(--footer-height);
            min-height: var(--footer-height);
        }
        .footer-left, .footer-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .footer-links {
            list-style: none;
            display: flex;
            gap: 12px;
            margin: 0;
            padding: 0;
        }
        .footer-links a {
            color: #ddd;
            text-decoration: none;
            font-size: 0.94rem;
            transition: color 0.3s;
        }
        .footer-links a:hover { color: var(--accent-color);}
        .footer-contact {
            display: flex;
            gap: 14px;
        }
        .footer-contact a {
            color: #ddd;
            text-decoration: none;
            font-size: 0.94rem;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }
        .footer-contact a:hover { color: var(--accent-color);}
        .footer-contact i { margin-right: 5px;}
        @media (max-width: 700px) {
            .features-container, .about-content, .tips-container, .learnmore-content { max-width: 98vw; }
            .feature-card, .about-content, .login-container, .learnmore-desc, .team-member { padding: 10px; }
            .footer-content { flex-direction: column; gap: 6px; height: auto; min-height: unset; }
            .team-members { gap: 16px; }
            .team-member { min-width: 95vw; width: 95vw; }
            .learnmore-desc { max-width: 99vw;}
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <nav class="dashboard-nav">
        <div class="nav-container">
            <a href="index.php" class="nav-brand">
                <i class="fas fa-piggy-bank"></i> <?php echo NAME_X; ?>
            </a>
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item"><a href="#" class="nav-link tab-link active" data-tab="features">Features</a></li>
                <li class="nav-item"><a href="#" class="nav-link tab-link" data-tab="about">About Us</a></li>
                <li class="nav-item"><a href="#" class="nav-link tab-link" data-tab="tips">Farming Tips</a></li>
                <li class="nav-item"><a href="#" class="nav-link tab-link" data-tab="learnmore">Learn More</a></li>
                <li class="nav-item"><a href="#" class="nav-link btn-login tab-link" data-tab="login">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content (Fixed screen, no scroll between sections) -->
    <div id="main-content">
        <!-- Features Screen -->
        <div id="features" class="tab-screen active">
            <div class="hero-section">
                <h1 class="system-name"><?php echo NAME_X; ?></h1>
                <p class="system-tagline">Transforming traditional hog farming into modern, data-driven agriculture with our comprehensive piggery management system</p>
                <div class="cta-buttons">
                    <a href="#" class="btn btn-primary tab-link" data-tab="login">Get Started</a>
                    <a href="#" class="btn btn-outline tab-link" data-tab="learnmore">Learn More</a>
                </div>
            </div>
            <h2 class="section-title">Key Features</h2>
            <?php if (!empty($features_text)): ?>
                <div style="margin-bottom:17px; text-align:center; font-size:0.99rem; color:#444;">
                    <?= nl2br(htmlspecialchars($features_text)) ?>
                </div>
            <?php endif; ?>
            <div class="features-container">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-piggy-bank"></i></div>
                    <div class="feature-title">Comprehensive Pig Management</div>
                    <p>Track every aspect of your herd from birth to market with our detailed record-keeping system designed specifically for hog raisers.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="feature-title">Data-Driven Decisions</div>
                    <p>Make informed decisions with real-time analytics on feed conversion, growth rates, and health metrics for optimal farm performance.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <div class="feature-title">Disease Prevention</div>
                    <p>Early warning systems and quarantine management help prevent disease outbreaks before they impact your entire operation.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-calculator"></i></div>
                    <div class="feature-title">Financial Tracking</div>
                    <p>Monitor expenses, revenues, and profitability with integrated financial tools tailored for hog farming operations.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-bell"></i></div>
                    <div class="feature-title">Automated Alerts</div>
                    <p>Receive timely notifications for vaccinations, breeding cycles, and other critical events in your farming calendar.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                    <div class="feature-title">Mobile Access</div>
                    <p>Manage your farm from anywhere with our responsive mobile-friendly interface that works on all devices.</p>
                </div>
            </div>
        </div>
        <!-- About Us Screen -->
        <div id="about" class="tab-screen">
            <h2 class="section-title">About Our System</h2>
            <div class="about-content">
                <div class="about-text">
                    <p><?= nl2br(htmlspecialchars($about_text)) ?></p>
                </div>
                <div class="about-image">
                    <img src="img/istockphoto-1350796429-612x612.jpg" alt="Modern Hog Farm">
                </div>
            </div>
        </div>
        <!-- Tips Screen -->
        <div id="tips" class="tab-screen">
            <h2 class="section-title">Modern Hog Farming Tips</h2>
            <?php if (!empty($farming_tips_text)): ?>
                <div style="margin-bottom:15px; font-size:0.96rem; color:#446;">
                    <?= nl2br(htmlspecialchars($farming_tips_text)) ?>
                </div>
            <?php endif; ?>
            <div class="tips-container">
                <div class="tip-card">
                    <div class="tip-icon"><i class="fas fa-utensils"></i></div>
                    <div class="tip-content">
                        <h3 class="tip-title">Optimal Feeding Strategies</h3>
                        <p>Implement phase feeding to match nutritional requirements with growth stages. Our system helps you track feed conversion ratios to maximize efficiency and reduce waste while ensuring optimal growth rates.</p>
                    </div>
                </div>
                <div class="tip-card">
                    <div class="tip-icon"><i class="fas fa-shield-virus"></i></div>
                    <div class="tip-content">
                        <h3 class="tip-title">Biosecurity Measures</h3>
                        <p>Maintain strict biosecurity protocols including foot baths, visitor logs, and equipment sanitation. Use our quarantine management features to monitor new arrivals and prevent disease introduction to your main herd.</p>
                    </div>
                </div>
                <div class="tip-card">
                    <div class="tip-icon"><i class="fas fa-dna"></i></div>
                    <div class="tip-content">
                        <h3 class="tip-title">Breeding Optimization</h3>
                        <p>Track sow productivity, farrowing rates, and genetic lines to identify your best performers. Our system helps you make data-driven breeding decisions to improve herd genetics and productivity over time.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Learn More Screen -->
        <div id="learnmore" class="tab-screen">
            <div class="learnmore-content">
                <h2 class="section-title">Introducing the System</h2>
                <div class="learnmore-desc">
                    <p>
                        <strong><?php echo NAME_X; ?></strong> was built to empower farmers with an integrated, easy-to-use platform for hog farming management. 
                        Our mission is to simplify daily operations, improve productivity, and guide farmers with data-driven insights. 
                        Built with real-world farm experience, this system is designed for both smallholders and commercial operations.
                    </p>
                    <p>
                        The system was created to address common challenges in hog farming such as record-keeping, data analysis, herd management, and disease prevention. 
                        With modern technology, we help farmers focus on what matters—healthy animals and profitable operations.
                    </p>
                </div>
                <div class="team-title">Meet the Developers</div>
                <div class="team-members">
                    <div class="team-member">
                        <img class="team-photo" src="uploadfolder/jadeprof.jpg" alt="Jade Edmar Sesuca">
                        <div class="team-name">Jade Edmar Sesuca</div>
                        <div class="team-role">Leader &amp; Programmer<br>Frontend, Backend, Integration</div>
                        <div class="team-location">Cabugao, Ilocos Sur</div>
                    </div>
                    <div class="team-member">
                        <img class="team-photo" src="uploadfolder/sienna.jpg" alt="Christian Jake Siena">
                        <div class="team-name">Christian Jake Siena</div>
                        <div class="team-role">Tester &amp; Assistant Documenter</div>
                        <div class="team-location">Cabugao, Ilocos Sur</div>
                    </div>
                    <div class="team-member">
                        <img class="team-photo" src="uploadfolder/allyzadane.jpg" alt="Allyzza Dane Brub">
                        <div class="team-name">Allyzza Dane Brub</div>
                        <div class="team-role">Documenter</div>
                        <div class="team-location">Abra</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Login Screen -->
        <div id="login" class="tab-screen">
            <div class="login-section-wrapper">
                <div class="login-container">
                    <h2 class="login-title">System Login</h2>
                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><?php echo $error; ?></strong>
                        </div>
                    <?php } ?>
                    <form method="post" autocomplete="off" id="loginForm">
                        <div class="form-group">
                            <label class="control-label">Username</label>
                            <input type="text" name="username" class="form-control" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Password</label>
                            <div class="password-wrapper">
                                <input type="password" name="password" id="password" class="form-control" required autocomplete="off">
                                <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                            </div>
                        </div>
                        <button name="submit" type="submit" class="btn-submit">Log in</button>
                        <div style="text-align: center; margin-top: 13px;">
                            <a href="forgot_password.php" style="color: var(--primary-color); text-decoration: none;">Forgot Password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-left">
                <span><?php echo NAME_X; ?> &copy; <?php echo date('Y'); ?>. All rights reserved.</span>
                <ul class="footer-links">
                    <li><a href="#" class="tab-link" data-tab="features">Features</a></li>
                    <li><a href="#" class="tab-link" data-tab="about">About</a></li>
                    <li><a href="#" class="tab-link" data-tab="tips">Tips</a></li>
                    <li><a href="#" class="tab-link" data-tab="learnmore">Learn More</a></li>
                    <li><a href="#" class="tab-link" data-tab="login">Login</a></li>
                </ul>
            </div>
            <div class="footer-right footer-contact">
                <a href="https://www.facebook.com/profile.php?id=100084915082290" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="https://www.instagram.com/jadeedmarsesuca/" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="mailto:jadeedmars@gmail.com"><i class="fas fa-envelope"></i></a>
                <a href="tel:09614889820"><i class="fas fa-phone"></i></a>
            </div>
        </div>
    </footer>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-screen').forEach(function(screen) {
                screen.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
            document.querySelectorAll('.tab-link').forEach(function(link){
                link.classList.remove('active');
                if(link.dataset.tab === tabId){
                    link.classList.add('active');
                }
            });
        }
        document.querySelectorAll('.tab-link').forEach(function(link){
            link.addEventListener('click', function(e){
                e.preventDefault();
                showTab(link.dataset.tab);
            });
        });

        // Toggle mobile menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navMenu = document.getElementById('navMenu');
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.querySelector('i').classList.toggle('fa-bars');
            this.querySelector('i').classList.toggle('fa-times');
        });
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
        if(togglePassword && password){
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
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

        // Always clear input boxes (username and password) on tab open
        function clearLoginInputs() {
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.reset();
            }
        }
        // Clear login form inputs whenever switching to login tab
        document.querySelectorAll('.tab-link[data-tab="login"]').forEach(function(link){
            link.addEventListener('click', function(){
                setTimeout(clearLoginInputs, 180); // after animation
            });
        });

        // Also clear when returning to login from "forgot password" or page reload
        document.addEventListener('DOMContentLoaded', function(){
            if(document.getElementById('login').classList.contains('active')){
                clearLoginInputs();
            }
        });

    </script>
</body>
</html>

<?php
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