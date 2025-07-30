<?php
include 'setting/system.php'; 
include 'theme/head.php'; 
include 'theme/sidebar.php'; 
include 'session.php'; 
$user_id = $_SESSION['id'] ?? 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $theme_mode = $_POST['theme_mode'] ?? 'normal';
  $language = $_POST['language'] ?? 'en';
  $font_style = $_POST['font_style'] ?? 'Arial';

  // Check if settings exist
  $check = $db->prepare("SELECT id FROM user_settings WHERE user_id = ?");
  $check->execute([$user_id]);

  if ($check->rowCount() > 0) {
    // Update
    $update = $db->prepare("UPDATE user_settings SET theme_mode=?, language=?, font_style=? WHERE user_id=?");
    $update->execute([$theme_mode, $language, $font_style, $user_id]);
  } else {
    // Insert
    $insert = $db->prepare("INSERT INTO user_settings (user_id, theme_mode, language, font_style) VALUES (?, ?, ?, ?)");
    $insert->execute([$user_id, $theme_mode, $language, $font_style]);
  }

  echo "<script>alert('Settings saved!'); window.location='settings.php';</script>";
}

// Load current settings
$settings = $db->prepare("SELECT * FROM user_settings WHERE user_id = ?");
$settings->execute([$user_id]);
$setting = $settings->fetch(PDO::FETCH_ASSOC);
?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <div class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-cog"></i> User Settings</b></h5>
  </div>

  <div class="w3-container">
    <form method="post" class="w3-card-4 w3-white w3-padding">

      <label>Theme Mode:</label>
      <select class="w3-select w3-border" name="theme_mode">
        <option value="normal" <?= ($setting['theme_mode'] ?? '') == 'normal' ? 'selected' : '' ?>>Normal</option>
        <option value="dark" <?= ($setting['theme_mode'] ?? '') == 'dark' ? 'selected' : '' ?>>Dark</option>
      </select>

      <label style="margin-top:10px;">Language:</label>
      <select class="w3-select w3-border" name="language">
        <option value="en" <?= ($setting['language'] ?? '') == 'en' ? 'selected' : '' ?>>English</option>
        <option value="fil" <?= ($setting['language'] ?? '') == 'fil' ? 'selected' : '' ?>>Filipino</option>
      </select>

      <label style="margin-top:10px;">Font Style:</label>
      <select class="w3-select w3-border" name="font_style">
        <option value="Arial" <?= ($setting['font_style'] ?? '') == 'Arial' ? 'selected' : '' ?>>Arial</option>
        <option value="Verdana" <?= ($setting['font_style'] ?? '') == 'Verdana' ? 'selected' : '' ?>>Verdana</option>
        <option value="Georgia" <?= ($setting['font_style'] ?? '') == 'Georgia' ? 'selected' : '' ?>>Georgia</option>
        <option value="Tahoma" <?= ($setting['font_style'] ?? '') == 'Tahoma' ? 'selected' : '' ?>>Tahoma</option>
      </select>

      <button class="w3-button w3-blue w3-margin-top" type="submit">Save Settings</button>
    </form>
  </div>
</div>

<?php include 'theme/foot.php'; ?>
