<?php
declare(strict_types=1);
require_once 'setting/system.php';
require_once 'theme/head.php';
require_once 'theme/sidebar.php';
require_once 'session.php';

// CSRF protection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$user_id = $_SESSION['id'] ?? 0;

// Handle form submission, including info sections
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    // User settings
    $theme_mode = filter_input(INPUT_POST, 'theme_mode', FILTER_SANITIZE_STRING) ?? 'light';
    $language = filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING) ?? 'en';
    $font_style = filter_input(INPUT_POST, 'font_style', FILTER_SANITIZE_STRING) ?? 'Arial';
    $notifications = isset($_POST['notifications']) ? 1 : 0;
    $font_size = filter_input(INPUT_POST, 'font_size', FILTER_VALIDATE_INT, ['options' => ['min_range' => 10, 'max_range' => 32]]) ?? 18;
    $timezone = filter_input(INPUT_POST, 'timezone', FILTER_SANITIZE_STRING) ?? 'UTC';

    // Info section fields
    $about_us = trim($_POST['about_us'] ?? '');
    $features = trim($_POST['features'] ?? '');
    $farming_tips = trim($_POST['farming_tips'] ?? '');

    try {
        // User settings update/insert
        $check = $db->prepare("SELECT id FROM user_settings WHERE user_id = ?");
        $check->execute([$user_id]);
        if ($check->rowCount() > 0) {
            $update = $db->prepare("UPDATE user_settings SET 
                                  theme_mode=?, language=?, font_style=?, notifications=?, font_size=?, timezone=?, updated_at=NOW() 
                                  WHERE user_id=?");
            $update->execute([$theme_mode, $language, $font_style, $notifications, $font_size, $timezone, $user_id]);
        } else {
            $insert = $db->prepare("INSERT INTO user_settings 
                                  (user_id, theme_mode, language, font_style, notifications, font_size, timezone, created_at, updated_at) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $insert->execute([$user_id, $theme_mode, $language, $font_style, $notifications, $font_size, $timezone]);
        }

        // Info sections table: update/insert
        $info_check = $db->query("SELECT id FROM info_sections LIMIT 1");
        if ($info_check->rowCount() > 0) {
            $update_info = $db->prepare("UPDATE info_sections SET about_us=?, features=?, farming_tips=?, updated_at=NOW() WHERE id=1");
            $update_info->execute([$about_us, $features, $farming_tips]);
        } else {
            $insert_info = $db->prepare("INSERT INTO info_sections (id, about_us, features, farming_tips, created_at, updated_at) VALUES (1,?,?,?,NOW(),NOW())");
            $insert_info->execute([$about_us, $features, $farming_tips]);
        }

        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Settings & Information text saved successfully!'];
        header("Location: settings.php");
        exit();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Failed to save settings. Please try again.'];
    }
}

// Load current settings
$settings = $db->prepare("SELECT * FROM user_settings WHERE user_id = ?");
$settings->execute([$user_id]);
$setting = $settings->fetch(PDO::FETCH_ASSOC) ?? [];

$info_query = $db->query("SELECT * FROM info_sections LIMIT 1");
$info = $info_query->fetch(PDO::FETCH_ASSOC);
?>


<!-- Main Content Wrapper - Adjusted for Sidebar -->
<div class="main-wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <?php include 'theme/sidebar.php'; ?>
    </div>

    <!-- Main Content -->
   <div class="main-wrapper">
    <div class="sidebar" id="sidebar">
        <?php include 'theme/sidebar.php'; ?>
    </div>
    <div class="main-content" id="mainContent">
        <div class="container-fluid settings-container">
            <div class="header">
                <h1><i class="fas fa-cog"></i> User Settings</h1>
                <p class="text-muted">Customize your application experience & landing page information text</p>
            </div>
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?>">
                    <?= $_SESSION['flash_message']['message'] ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php unset($_SESSION['flash_message']); ?>
            <?php endif; ?>
            <div class="settings-card">
                <div class="settings-card-body">
                    <form method="post" id="settingsForm" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="row">
                            <div class="col-md-6 col-settings">

                        <div class="row">
                            <div class="col-md-6 col-settings">
                                <div class="form-group">
                                    <label for="theme_mode"><i class="fas fa-palette"></i> Theme Mode</label>
                                    <select class="form-control" id="theme_mode" name="theme_mode">
                                        <option value="light" <?= ($setting['theme_mode'] ?? 'light') === 'light' ? 'selected' : '' ?>>Light</option>
                                        <option value="dark" <?= ($setting['theme_mode'] ?? '') === 'dark' ? 'selected' : '' ?>>Dark</option>
                                        <option value="system" <?= ($setting['theme_mode'] ?? '') === 'system' ? 'selected' : '' ?>>System Default</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="language"><i class="fas fa-language"></i> Language</label>
                                    <select class="form-control" id="language" name="language">
                                        <option value="en" <?= ($setting['language'] ?? 'en') === 'en' ? 'selected' : '' ?>>English</option>
                                        <option value="fil" <?= ($setting['language'] ?? '') === 'fil' ? 'selected' : '' ?>>Filipino</option>
                                        <option value="es" <?= ($setting['language'] ?? '') === 'es' ? 'selected' : '' ?>>Spanish</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="timezone"><i class="fas fa-clock"></i> Timezone</label>
                                    <select class="form-control" id="timezone" name="timezone">
                                        <?php
                                        $timezones = DateTimeZone::listIdentifiers();
                                        foreach ($timezones as $tz) {
                                            $selected = ($setting['timezone'] ?? 'UTC') === $tz ? 'selected' : '';
                                            echo "<option value=\"$tz\" $selected>$tz</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-settings">
                                <div class="form-group">
                                    <label for="font_style"><i class="fas fa-font"></i> Font Style</label>
                                    <select class="form-control" id="font_style" name="font_style">
                                        <option value="Arial" <?= ($setting['font_style'] ?? 'Arial') === 'Arial' ? 'selected' : '' ?>>Arial</option>
                                        <option value="Verdana" <?= ($setting['font_style'] ?? '') === 'Verdana' ? 'selected' : '' ?>>Verdana</option>
                                        <option value="Georgia" <?= ($setting['font_style'] ?? '') === 'Georgia' ? 'selected' : '' ?>>Georgia</option>
                                        <option value="Tahoma" <?= ($setting['font_style'] ?? '') === 'Tahoma' ? 'selected' : '' ?>>Tahoma</option>
                                        <option value="'Segoe UI', sans-serif" <?= ($setting['font_style'] ?? '') === "'Segoe UI', sans-serif" ? 'selected' : '' ?>>Segoe UI</option>
                                        <option value="'Roboto', sans-serif" <?= ($setting['font_style'] ?? '') === "'Roboto', sans-serif" ? 'selected' : '' ?>>Roboto</option>
                                    </select>
                                </div>

                               <div class="col-md-6 col-settings">
                                <!-- Info Text Section -->
                                <div class="form-group">
                                    <label for="about_us"><i class="fas fa-info-circle"></i> About Us Text</label>
                                    <textarea class="form-control" id="about_us" name="about_us" rows="4"><?= htmlspecialchars($info['about_us'] ?? '') ?></textarea>
                                    <small class="text-muted">Displayed on landing page About Us section.</small>
                                </div>
                                <div class="form-group">
                                    <label for="features"><i class="fas fa-star"></i> Features Text</label>
                                    <textarea class="form-control" id="features" name="features" rows="3"><?= htmlspecialchars($info['features'] ?? '') ?></textarea>
                                    <small class="text-muted">Displayed on landing page Features section above cards.</small>
                                </div>
                                <div class="form-group">
                                    <label for="farming_tips"><i class="fas fa-lightbulb"></i> Farming Tips Text</label>
                                    <textarea class="form-control" id="farming_tips" name="farming_tips" rows="3"><?= htmlspecialchars($info['farming_tips'] ?? '') ?></textarea>
                                    <small class="text-muted">Displayed above tips cards in Farming Tips section.</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-4 settings-btn-group">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-lg" id="resetSettings">
                                <i class="fas fa-undo"></i> Reset to Defaults
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this CSS to your theme/head.php or create a separate CSS file -->
<style>
:root {
    --sidebar-width: 320px;
    --sidebar-bg: #222d3b;
    --settings-card-bg: #fff;
    --settings-card-bg-dark: #23272f;
    --settings-card-shadow: 0 4px 32px rgba(60,60,140,0.09);
    --settings-accent: #4a6fa5;
    --settings-accent-dark: #ff7e5f;
    --settings-btn-radius: 7px;
    --settings-form-gap: 2.2rem;
    --settings-padding: 38px 38px 30px 38px;
    --settings-header-font: 2.1rem;
    --settings-label-font: 1.13rem;
    --settings-input-font: 1.07rem;
    --settings-border-radius: 17px;
}

.main-wrapper {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: var(--sidebar-width);
    min-height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    transition: width 0.3s;
    font-size: 1.17rem;
}

.main-content {
    flex: 1;
    margin-left: var(--sidebar-width);
    padding: 36px 25px 25px 25px;
    transition: margin-left 0.3s;
    background: #f7f8fa;
    min-height: 100vh;
}
.settings-container {
    max-width: 1060px;
    margin: 0 auto;
    padding: 0;
}
.header {
    margin-bottom: 27px;
}
.header h1 {
    font-size: var(--settings-header-font);
    font-weight: 800;
    color: var(--settings-accent);
    margin-bottom: 6px;
    letter-spacing: 0.5px;
}
.header p {
    margin-bottom: 0;
    font-size: 1.08rem;
    color: #757ea5;
}

.settings-card {
    background: var(--settings-card-bg);
    border-radius: var(--settings-border-radius);
    box-shadow: var(--settings-card-shadow);
    margin-bottom: 35px;
    border: 1.5px solid #e0e7f1;
    transition: background 0.3s;
}
[data-theme="dark"] .settings-card {
    background: var(--settings-card-bg-dark);
    border-color: #23272f;
    color: #e0e6ef;
}
.settings-card-body {
    padding: var(--settings-padding);
}
.form-group {
    margin-bottom: 1.85rem;
}
.form-group label {
    font-size: var(--settings-label-font);
    font-weight: 600;
    color: var(--settings-accent);
    margin-bottom: 0.35rem;
    display: flex;
    align-items: center;
    gap: 6px;
}
.form-control, .form-select {
    font-size: var(--settings-input-font);
    border-radius: var(--settings-btn-radius);
    border: 1.5px solid #bfc4d1;
    padding: 0.6rem 0.72rem;
}
.form-control:focus {
    border-color: var(--settings-accent);
    box-shadow: 0 0 0 0.11rem var(--settings-accent);
    outline: none;
}
.form-control-range {
    width: 100%;
    margin-top: 5px;
    accent-color: var(--settings-accent-dark);
}
#font_size_value {
    font-weight: 700;
    margin-left: 12px;
    font-size: 1.07rem;
    color: var(--settings-accent-dark);
}
.settings-btn-group {
    display: flex;
    gap: 18px;
    margin-top: 1.8rem;
}
.btn-lg {
    font-size: 1.09rem;
    padding: 9px 32px;
    border-radius: var(--settings-btn-radius);
}
.btn-primary {
    background: linear-gradient(90deg, var(--settings-accent), var(--settings-accent-dark));
    border: none;
}
.btn-primary:hover, .btn-outline-secondary:hover {
    opacity: 0.93;
}
.btn-outline-secondary {
    border: 1.5px solid var(--settings-accent);
    color: var(--settings-accent);
    background: transparent;
}
.btn-outline-secondary:hover {
    background: var(--settings-accent);
    color: #fff;
}
.col-settings {
    min-width: 320px;
}
@media (max-width: 1100px) {
    .settings-container {
        max-width: 99vw;
    }
    .settings-card-body {
        padding: 20px;
    }
    .col-settings {
        min-width: 220px;
    }
}

@media (max-width: 900px) {
    .main-content {
        padding: 18px;
    }
    .settings-card-body {
        padding: 14px;
    }
    .settings-btn-group {
        flex-direction: column;
        gap: 10px;
    }
}

@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        position: relative;
        min-height: auto;
    }
    .main-content {
        margin-left: 0;
        padding: 13px;
    }
    .settings-card-body {
        padding: 7px;
    }
}

::-webkit-scrollbar {
    width: 7px;
    background: #f7f7ff;
}
::-webkit-scrollbar-thumb {
    background: #e0e7f1;
    border-radius: 4px;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Font size live update
    document.getElementById('font_size').addEventListener('input', function() {
        document.getElementById('font_size_value').textContent = this.value + 'px';
    });

    // Reset settings to defaults
    document.getElementById('resetSettings').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset all settings to default values?')) {
            document.getElementById('theme_mode').value = 'light';
            document.getElementById('language').value = 'en';
            document.getElementById('font_style').value = 'Arial';
            document.getElementById('font_size').value = 18;
            document.getElementById('font_size_value').textContent = '18px';
            document.getElementById('notifications').checked = false;
            document.getElementById('timezone').value = 'UTC';
        }
    });

    // Theme mode live preview
    document.getElementById('theme_mode').addEventListener('change', function() {
        document.documentElement.setAttribute('data-theme', this.value);
    });

    // --- Sidebar width consistency when settings open ---
    // If your sidebar.js triggers width changes, override here for settings page
    function consistentSidebar() {
        var sidebar = document.getElementById('sidebar');
        var mainContent = document.getElementById('mainContent');
        sidebar.style.width = '320px';
        mainContent.style.marginLeft = '320px';
    }
    consistentSidebar();

    // If using a sidebar open/close, ensure settings always uses the correct width
    window.addEventListener('resize', consistentSidebar);

    // Optionally: Prevent sidebar shrink when settings cog is clicked
    document.querySelectorAll('.settings-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            // Just scroll to settings content
            document.getElementById('mainContent').scrollIntoView({ behavior: 'smooth' });
        });
    });
});
</script>

<?php include 'theme/foot.php'; ?>