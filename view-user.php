<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php
// Validate and get user ID
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        header("Location: add-users.php");
        exit();
    }
} else {
    header("Location: add-users.php");
    exit();
}
?>

<style>
.user-view-card {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 4px 32px 0 rgba(60,60,60,0.08), 0 2.5px 8px 0 rgba(60,60,60,0.05);
  border: 1px solid #e0e0e0;
  padding: 32px 28px 22px 28px;
  max-width: 520px;
  margin: 32px auto 0 auto;
  font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
}
.user-profile-header {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  margin-bottom: 20px;
}
.user-profile-img {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #dedede;
  box-shadow: 0 2px 8px rgba(60,60,60,0.06);
  margin-bottom: 12px;
}
.user-profile-name {
  font-size: 1.45rem;
  font-weight: 700;
  color: #38598b;
  margin-bottom: 2px;
  text-align: center;
}
.user-profile-username {
  color: #7b8ba3;
  font-size: 1.06rem;
  margin-bottom: 7px;
  font-weight: 500;
  text-align: center;
}
.user-profile-role {
  display: inline-block;
  background: #e3e8fd;
  color: #4a6fa5;
  font-size: 0.98rem;
  font-weight: 600;
  border-radius: 11px;
  padding: 4px 14px;
  margin-bottom: 12px;
}
.user-view-details {
  font-size: 1.05rem;
  color: #26334a;
  margin-top: 15px;
}
.user-view-details .label {
  color: #7b8ba3;
  font-weight: 500;
  width: 38%;
  min-width: 120px;
  display: inline-block;
}
.user-view-details .value {
  color: #26334a;
  font-weight: 600;
}
.user-view-details-row {
  margin-bottom: 12px;
  display: flex;
  align-items: center;
}
.user-status-tag {
  display: inline-block;
  border-radius: 30px;
  font-size: 1rem;
  font-weight: 600;
  padding: 4px 16px;
  margin-top: 2px;
}
.user-status-active {
  background: #e7f7ea;
  color: #28a745;
}
.user-status-blocked {
  background: #fde7e7;
  color: #d32f2f;
}
.user-view-actions {
  margin-top: 28px;
  text-align: center;
}
.user-view-actions .w3-button {
  font-size: 15px;
  padding: 9px 22px;
  margin: 0 8px;
}
</style>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <div class="user-view-card">
        <div class="user-profile-header">
            <img src="<?php echo !empty($user['profile_img']) && file_exists($user['profile_img']) ? $user['profile_img'] : 'assets/img/default-avatar.png'; ?>" class="user-profile-img" alt="Profile">
            <div class="user-profile-name"><?php echo htmlspecialchars($user['name']); ?></div>
            <div class="user-profile-username">@<?php echo htmlspecialchars($user['username']); ?></div>
            <span class="user-profile-role">
                <i class="fa fa-user"></i> <?php echo ucfirst($user['role']); ?>
            </span>
            <?php if (isset($user['status'])): ?>
                <span class="user-status-tag <?php echo $user['status'] == 1 ? 'user-status-active' : 'user-status-blocked'; ?>">
                    <?php echo $user['status'] == 1 ? '<i class="fa fa-check"></i> Active' : '<i class="fa fa-times"></i> Blocked'; ?>
                </span>
            <?php endif; ?>
        </div>
        <div class="user-view-details">
            <div class="user-view-details-row">
                <span class="label"><i class="fa fa-venus-mars"></i> Gender:</span>
                <span class="value"><?php echo ucfirst($user['sex']); ?></span>
            </div>
            <div class="user-view-details-row">
                <span class="label"><i class="fa fa-birthday-cake"></i> Birthday:</span>
                <span class="value"><?php echo htmlspecialchars($user['birthday']); ?></span>
            </div>
            <div class="user-view-details-row">
                <span class="label"><i class="fa fa-user"></i> Age:</span>
                <span class="value"><?php echo htmlspecialchars($user['age']); ?></span>
            </div>
            <div class="user-view-details-row">
                <span class="label"><i class="fa fa-map-marker"></i> Province:</span>
                <span class="value"><?php echo htmlspecialchars($user['province']); ?></span>
            </div>
            <div class="user-view-details-row">
                <span class="label">Municipality/City:</span>
                <span class="value"><?php echo htmlspecialchars($user['municipality']); ?></span>
            </div>
            <div class="user-view-details-row">
                <span class="label">Barangay:</span>
                <span class="value"><?php echo htmlspecialchars($user['barangay']); ?></span>
            </div>
            <div class="user-view-details-row">
                <span class="label">Street Address:</span>
                <span class="value"><?php echo htmlspecialchars($user['street_address']); ?></span>
            </div>
            <div class="user-view-details-row">
                <span class="label"><i class="fa fa-phone"></i> Contact Number:</span>
                <span class="value"><?php echo htmlspecialchars($user['contact_number']); ?></span>
            </div>
        </div>
        <div class="user-view-actions">
            <a href="edit_user.php?id=<?php echo $userId; ?>" class="w3-button w3-blue w3-round-large"><i class="fa fa-edit"></i> Edit</a>
            <a href="add-users.php" class="w3-button w3-light-grey w3-round-large"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>