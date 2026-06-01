<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Get user ID from query param
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user = null;
if ($userId > 0) {
  $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([$userId]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<style>
.dashboard-main {
  font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
  min-height: 100vh;
  background: #f7f8fa;
  padding: 0 0 40px 0;
}
.dashboard-header {
  background: #38598b;
  color: #fff;
  border-radius: 0 0 18px 18px;
  margin-bottom: 34px;
  padding: 30px 38px 20px 38px;
  box-shadow: 0 4px 24px -10px #38598b40;
}
.dashboard-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.dashboard-title h1,
.dashboard-title h2 {
  font-size: 2.2rem;
  font-weight: 800;
  letter-spacing: 0.2px;
  margin-bottom: 0;
}
.dashboard-badge {
  background: #fff;
  color: #38598b;
  font-size: 1.15rem;
  font-weight: 700;
  border-radius: 20px;
  padding: 8px 26px;
  box-shadow: 0 2px 8px -2px #00000018;
}
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 0;
  margin-bottom: 0;
  max-width: 680px;
  margin: 38px auto 0 auto;
}
.dashboard-card h3 {
  font-size: 1.38rem;
  color: #38598b;
  margin-bottom: 20px;
  font-weight: 700;
  letter-spacing: 0.2px;
}
.details-row {
  display: flex;
  gap: 32px;
  flex-wrap: wrap;
  align-items: flex-start;
}
.details-img {
  min-width: 130px;
  text-align: center;
}
.details-img img {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #dedede;
  box-shadow: 0 2px 8px rgba(60,60,60,0.07);
}
.details-info {
  flex: 1;
}
.details-table {
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}
.details-table th,
.details-table td {
  text-align: left;
  padding: 8px 10px;
  font-size: 1.08rem;
}
.details-table th {
  width: 180px;
  color: #38598b;
  font-weight: 600;
  background: #f3f6fb;
}
.details-table tr {
  border-bottom: 1px solid #e0e0e0;
}
.details-table tr:last-child {
  border-bottom: none;
}
.details-actions {
  margin-top: 28px;
  text-align: right;
}
.w3-button.w3-blue {
  background: #38598b !important;
  color: #fff !important;
  font-size: 1.09rem !important;
  font-weight: 600;
  border-radius: 8px !important;
  box-shadow: 0 2px 8px -2px #38598b28 !important;
}
.w3-button.w3-blue:hover {
  background: #2c406b !important;
}
.w3-button.w3-light-grey {
  background: #e0e7ef !important;
  color: #333 !important;
  border-radius: 8px !important;
}

@media (max-width: 800px) {
  .dashboard-card {
    max-width: 98vw;
    margin: 18px 7px 0 7px;
  }
  .details-row {
    flex-direction: column;
    gap: 18px;
    align-items: center;
  }
  .details-img {
    min-width: unset;
    margin-bottom: 12px;
  }
}
</style>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
  <header class="dashboard-header">
    <div class="dashboard-row">
      <div class="dashboard-col dashboard-title">
        <h1><b><i class="fa fa-user"></i> User Details</b></h1>
      </div>
      <div class="dashboard-col dashboard-date">
        <div class="dashboard-badge">
          <?php
          $stmt = $db->query("SELECT COUNT(*) AS total_users FROM users");
          $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
          echo $totalUsers . " Total Users";
          ?>
        </div>
      </div>
    </div>
  </header>

  <div class="dashboard-card">
    <?php if ($user): ?>
      <h3><i class="fa fa-eye"></i> Viewing User: <span style="color:#2c406b;"><?= htmlspecialchars($user['username']) ?></span></h3>
      <div class="details-row">
        <div class="details-img">
          <img src="<?= (!empty($user['profile_img']) && file_exists($user['profile_img'])) ? $user['profile_img'] : 'assets/img/default-avatar.png' ?>" alt="Avatar">
        </div>
        <div class="details-info">
          <table class="details-table">
            <tr>
              <th>First Name</th>
              <td><?= htmlspecialchars($user['first_name']) ?></td>
            </tr>
            <tr>
              <th>Last Name</th>
              <td><?= htmlspecialchars($user['last_name']) ?></td>
            </tr>
            <tr>
              <th>Middle Name</th>
              <td><?= htmlspecialchars($user['middle_name']) ?></td>
            </tr>
            <tr>
              <th>Username</th>
              <td><?= htmlspecialchars($user['username']) ?></td>
            </tr>
            <tr>
              <th>Password</th>
              <td>
                <input type="password" value="<?= htmlspecialchars($user['password']) ?>" style="width:210px;font-size:1.1rem;" readonly id="passwordField">
                <button type="button" class="w3-button w3-light-grey w3-round-large" onclick="togglePassword()"><i class="fa fa-eye"></i></button>
              </td>
            </tr>
            <tr>
              <th>Role</th>
              <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
            </tr>
            <tr>
              <th>Status</th>
              <td>
                <?php
                  if (isset($user['status'])) {
                    echo $user['status'] == 1 ?
                      '<span class="w3-tag w3-round-large w3-green"><i class="fa fa-check"></i> Active</span>' :
                      '<span class="w3-tag w3-round-large w3-red"><i class="fa fa-times"></i> Inactive</span>';
                  } else {
                    echo '<span class="w3-tag w3-round-large w3-grey"><i class="fa fa-question"></i> Unknown</span>';
                  }
                ?>
              </td>
            </tr>
            <tr>
              <th>Gender</th>
              <td><?= htmlspecialchars(ucfirst($user['sex'])) ?></td>
            </tr>
            <tr>
              <th>Province</th>
              <td><?= htmlspecialchars($user['province']) ?></td>
            </tr>
            <tr>
              <th>Municipality</th>
              <td><?= htmlspecialchars($user['municipality']) ?></td>
            </tr>
            <tr>
              <th>Barangay</th>
              <td><?= htmlspecialchars($user['barangay']) ?></td>
            </tr>
            <tr>
              <th>Street Address</th>
              <td><?= htmlspecialchars($user['street_address']) ?></td>
            </tr>
            <tr>
              <th>Contact Number</th>
              <td><?= htmlspecialchars($user['contact_number']) ?></td>
            </tr>
            <tr>
              <th>Date of Birth</th>
              <td><?= htmlspecialchars($user['birthday']) ?></td>
            </tr>
            <tr>
              <th>Age</th>
              <td><?= htmlspecialchars($user['age']) ?></td>
            </tr>
            <tr>
              <th>Last Login</th>
              <td><?= isset($user['last_login']) && $user['last_login'] ? date('M d, Y h:i A', strtotime($user['last_login'])) : 'Never' ?></td>
            </tr>
          </table>
          <div class="details-actions">
            <a href="edit_user.php?id=<?= $user['id'] ?>" class="w3-button w3-blue w3-round-large"><i class="fa fa-edit"></i> Edit</a>
            <a href="manage_users.php" class="w3-button w3-light-grey w3-round-large"><i class="fa fa-arrow-left"></i> Back</a>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="w3-panel w3-red w3-round-large w3-padding"><p>
        <i class="fa fa-exclamation-triangle"></i> User not found or invalid ID.
      </p></div>
    <?php endif; ?>
  </div>
</div>

<?php include 'theme/foot.php'; ?>

<script>
function togglePassword() {
  var field = document.getElementById("passwordField");
  field.type = field.type === "password" ? "text" : "password";
}
</script>