<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

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
.dashboard-charts-row {
  display: flex;
  gap: 32px;
  margin: 0 38px;
  flex-wrap: wrap;
}
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 0;
  margin-bottom: 0;
}
.dashboard-card h3,
.dashboard-card h4 {
  font-size: 1.22rem;
  color: #38598b;
  margin-bottom: 20px;
  font-weight: 700;
  letter-spacing: 0.2px;
}
.form-control,
.w3-input,
.w3-select {
  border-radius: 7px !important;
  border: 1px solid #b4c7e7 !important;
  box-shadow: 0 1px 4px -2px #38598b18 !important;
  font-size: 1.12rem !important;
  padding: 10px 14px !important;
}
.btn-primary,
.w3-button.w3-blue {
  background: #38598b !important;
  color: #fff !important;
  font-size: 1.09rem !important;
  font-weight: 600;
  border-radius: 8px !important;
  box-shadow: 0 2px 8px -2px #38598b28 !important;
}
.btn-primary:hover,
.w3-button.w3-blue:hover {
  background: #2c406b !important;
}
.table thead th,
.w3-table th {
  font-weight: 700;
  color: #38598b;
  background: #f3f6fb;
  border-bottom: 2px solid #b4c7e7;
}
.table tr td,
.w3-table td {
  font-size: 1.07rem;
  vertical-align: middle;
}
.table-hover tbody tr:hover,
.w3-table-hover tr:hover {
  background: #f7f8fa;
}
.dashboard-card form .form-group label {
  font-weight: 600;
  color: #38598b;
  font-size: 1.05rem;
}
.dashboard-card form .form-control {
  border-radius: 7px;
  border: 1px solid #b4c7e7;
  box-shadow: 0 1px 4px -2px #38598b18;
  font-size: 1.12rem;
  padding: 10px 14px;
}
.dashboard-card form button.btn-primary {
  background: #38598b;
  color: #fff;
  font-size: 1.09rem;
  font-weight: 600;
  border-radius: 8px;
  box-shadow: 0 2px 8px -2px #38598b28;
}
.dashboard-card form button.btn-primary:hover {
  background: #2c406b;
}
.profile-upload {
  text-align: left;
  margin-bottom: 18px;
  display: flex;
  align-items: center;
  gap: 12px;
  position: relative;
}
.profile-upload .profile-preview-container {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 110px;
  height: 110px;
}
.profile-upload img {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #dedede;
  vertical-align: middle;
  box-shadow: 0 2px 8px rgba(60,60,60,0.06);
  transition: box-shadow 0.2s;
}
.profile-upload .profile-preview-label {
  position: absolute;
  left: 0;
  top: 0;
  width: 110px;
  height: 110px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #888;
  font-size: 14px;
  font-weight: 600;
  pointer-events: none;
  text-align: center;
  z-index: 2;
}
.profile-upload label,
.profile-upload .camera-btn {
  font-size: 13px;
  padding: 4px 10px;
  border-radius: 18px;
  min-width: 0;
  height: 32px;
  line-height: 22px;
  display: inline-flex;
  align-items: center;
  margin: 0;
}
.profile-upload label {
  background: #f0f4f8;
  border: 2px dashed #b0b3b8;
  color: #555;
  cursor: pointer;
  margin-right: 0;
  transition: background 0.2s, border-color 0.2s;
}
.profile-upload label:hover {
  background: #e8f0fe;
  border-color: #4285f4;
}
.profile-upload input[type="file"] {
  display: none;
}
.profile-upload .camera-btn {
  background: #e0e7ef;
  color: #333;
  border: none;
  cursor: pointer;
  font-size: 13px;
  margin-left: 6px;
}
.profile-upload .camera-btn:hover {
  background: #c8daf4;
}
#cameraModal video,
#cameraModal canvas {
  width: 380px !important;
  height: 285px !important;
  border-radius: 12px;
  background: #222;
  box-shadow: 0 4px 24px rgba(60,60,60,0.12);
}
.form-one-col .w3-row-padding {
  display: block;
  margin-left: 0;
  margin-right: 0;
}
.form-one-col .w3-row-padding > div {
  width: 100% !important;
  margin-bottom: 16px;
  float: none;
}
.form-one-col label {
  margin-bottom: 6px;
  display: block;
}
.password-policy {
  margin-top: 8px;
  font-size: 13px;
  color: #555;
  background: #f5f7fa;
  border-radius: 10px;
  padding: 10px 15px;
  border: 1px solid #e0e0e0;
}
.password-policy ul {
  margin: 0;
  padding-left: 20px;
}
.password-policy .pass-ok {
  color: #289c3f;
  font-weight: 600;
}
.password-policy .pass-bad {
  color: #d32f2f;
  font-weight: 600;
}
@media (max-width: 1100px) {
  .dashboard-charts-row {
    flex-direction: column;
    gap: 18px;
  }
  .dashboard-card {
    min-width: unset;
    max-width: 99vw;
  }
}
@media (max-width: 768px) {
  .dashboard-main {
    margin-left: 0;
    padding: 0 0 10px 0;
  }
  .dashboard-header,
  .dashboard-charts-row {
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 7px;
    padding-right: 7px;
  }
  .dashboard-header {
    padding: 21px 8px 14px 8px;
  }
}
</style>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
  <header class="dashboard-header">
    <div class="dashboard-row">
      <div class="dashboard-col dashboard-title">
        <h1><b><i class="fa fa-user-plus"></i> User Management</b></h1>
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

  <div class="dashboard-card" style="margin:38px 38px 0 38px;">
    <div class="w3-bar w3-light-grey w3-round-large" style="margin-bottom: 24px;">
      <button class="w3-bar-item w3-button tablink w3-blue" onclick="openTab(event,'addUser')"><i class="fa fa-plus-circle"></i> Add User</button>
      <button class="w3-bar-item w3-button tablink" onclick="openTab(event,'manageUsers')"><i class="fa fa-users"></i> Manage Users</button>
    </div>

    <div id="addUser" class="tab-content" style="display:block;">
      <div class="dashboard-card" style="max-width:500px;margin:auto;">
        <h3><i class="fa fa-user-plus"></i> Create New User</h3>
        <p class="w3-text-grey" style="font-size:15px;">Fill in the form below to add a new system user</p>
        <hr>

        <div id="cameraModal" style="display:none;position:fixed;z-index:2000;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.65);align-items:center;justify-content:center;">
          <div style="background:#fff;padding:32px 20px 20px 20px;border-radius:16px;max-width:95vw;max-height:90vh;position:relative;display:flex;flex-direction:column;align-items:center;">
            <video id="video" width="380" height="285" autoplay></video>
            <canvas id="canvas" width="380" height="285" style="display:none"></canvas>
            <div style="margin-top:16px;display:flex;gap:12px;">
              <button type="button" class="w3-button w3-blue w3-round-large camera-btn" style="padding:4px 10px;" onclick="takePhoto()">Take Photo</button>
              <button type="button" class="w3-button w3-light-grey w3-round-large camera-btn" style="padding:4px 10px;" onclick="closeCameraModal()">Cancel</button>
            </div>
            <button type="button" onclick="closeCameraModal()" style="position:absolute;top:8px;right:12px;background:none;border:none;font-size:23px;color:#888;cursor:pointer;">&times;</button>
          </div>
        </div>

        <form method="POST" enctype="multipart/form-data" id="addUserForm" autocomplete="off">
          <div class="w3-row-padding">
            <div>
              <div class="profile-upload">
                <div class="profile-preview-container">
                  <img src="assets/img/default-avatar.png" id="profilePreview" alt="   ">
                  <span class="profile-preview-label" id="profilePreviewText">Profile Preview</span>
                </div>
                <label for="profile_img" title="Upload Photo">
                  <i class="fa fa-upload"></i>
                </label>
                <input type="file" id="profile_img" name="profile_img" accept="image/*" onchange="previewProfileImage(event)">
                <button type="button" class="camera-btn" onclick="openCameraModal()" title="Take Photo">
                  <i class="fa fa-camera"></i>
                </button>
                <input type="file" accept="image/*" capture="user" id="cameraInput" name="cameraInput" style="display:none" onchange="previewProfileImage(event)">
                <input type="hidden" id="cameraPhotoData" name="cameraPhotoData">
              </div>
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="lastname" class="w3-text-dark-grey"><b>Last Name</b></label>
              <input type="text" id="lastname" name="lastname" class="w3-input w3-round-large w3-border modern-input" placeholder="Doe" required maxlength="40" pattern="^[a-zA-Z\s\-\.]+$">
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="firstname" class="w3-text-dark-grey"><b>First Name</b></label>
              <input type="text" id="firstname" name="firstname" class="w3-input w3-round-large w3-border modern-input" placeholder="John" required maxlength="40" pattern="^[a-zA-Z\s\-\.]+$">
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="middlename" class="w3-text-dark-grey"><b>Middle Name</b></label>
              <input type="text" id="middlename" name="middlename" class="w3-input w3-round-large w3-border modern-input" placeholder="A." maxlength="40" pattern="^[a-zA-Z\s\-\.]+$">
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="username" class="w3-text-dark-grey"><b>Username</b></label>
              <input type="text" id="username" name="username" class="w3-input w3-round-large w3-border modern-input" placeholder="johndoe" required
                maxlength="40" pattern="^[a-zA-Z0-9_\-\.]+$" autocomplete="off">
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="password" class="w3-text-dark-grey"><b>Password</b></label>
              <div style="display:flex;align-items:center;">
                <input type="password" id="password" name="password" class="w3-input w3-round-large w3-border modern-input" required autocomplete="new-password"
                  style="flex:1;">
                <button type="button" class="w3-button w3-round-large w3-border" onclick="togglePassword()" style="margin-left:8px;">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
              <div class="w3-light-grey w3-round-large w3-small w3-padding-small" style="margin-top:4px;">
                <div id="password-strength-bar" class="w3-container w3-round-large w3-blue" style="height:5px;width:0%"></div>
              </div>
              <span id="password-strength-text" class="w3-small"></span>
              <div class="password-policy" id="password-policy">
                <b>Password requirements:</b>
                <ul>
                  <li id="pass-length" class="pass-bad">Minimum 8–12 characters</li>
                  <li id="pass-complex" class="pass-bad">At least 3 of these: uppercase, lowercase, number, special character</li>
                  <li id="pass-common" class="pass-bad">Not a common password</li>
                  <li id="pass-repeat" class="pass-bad">No sequential or repeating characters</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="sex" class="w3-text-dark-grey"><b>Gender</b></label>
              <select id="sex" name="sex" class="w3-select w3-round-large w3-border modern-input" required>
                <option value="" disabled selected>Select gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="province" class="w3-text-dark-grey"><b>Province</b></label>
              <select id="province" name="province" class="w3-select w3-round-large w3-border modern-input" required>
                <option value="" disabled selected>Select province</option>
              </select>
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="municipality" class="w3-text-dark-grey"><b>Municipality/City</b></label>
              <select id="municipality" name="municipality" class="w3-select w3-round-large w3-border modern-input" required>
                <option value="" disabled selected>Select municipality/city</option>
              </select>
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="barangay" class="w3-text-dark-grey"><b>Barangay</b></label>
              <select id="barangay" name="barangay" class="w3-select w3-round-large w3-border modern-input" required>
                <option value="" disabled selected>Select barangay</option>
              </select>
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="street_address" class="w3-text-dark-grey"><b>Street Address</b></label>
              <input type="text" id="street_address" name="street_address" class="w3-input w3-round-large w3-border modern-input" placeholder="123 Main St" maxlength="120">
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="contact_number" class="w3-text-dark-grey"><b>Contact Number</b></label>
              <input type="text" id="contact_number" name="contact_number" class="w3-input w3-round-large w3-border modern-input" placeholder="0961 488 9820" required
                maxlength="13">
              <span id="contact_error" style="font-size:12px;color:#d32f2f;display:none;">Format: 0961 488 9820</span>
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="birthday" class="w3-text-dark-grey"><b>Date of Birth</b></label>
              <input type="date" id="birthday" name="birthday" class="w3-input w3-round-large w3-border modern-input" required onchange="calculateAge()">
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="age" class="w3-text-dark-grey"><b>Age</b></label>
              <input type="number" id="age" name="age" class="w3-input w3-round-large w3-border modern-input" readonly>
            </div>
          </div>
          <div class="w3-row-padding">
            <div>
              <label for="role" class="w3-text-dark-grey"><b>Role</b></label>
              <select id="role" name="role" class="w3-select w3-round-large w3-border modern-input" required>
                <option value="" disabled selected>Select role</option>
                <option value="employee">Employee</option>
                <option value="manager">Manager</option>
                <option value="user">User</option>
              </select>
            </div>
          </div>
          <div class="w3-margin-top">
            <button type="submit" name="submit" class="w3-button w3-blue w3-round-large w3-hover-dark-grey" style="font-size:15px;padding:8px 20px;">
              <i class="fa fa-save"></i> Save User
            </button>
            <button type="reset" class="w3-button w3-light-grey w3-round-large" style="font-size:15px;padding:8px 20px;">
              <i class="fa fa-undo"></i> Reset
            </button>
          </div>
        </form>

        <?php
        function isCommonPassword($password) {
          $common = [
            'password', '123456', '12345678', 'qwerty', 'abc123', 'letmein', 'monkey', 'dragon', '111111', '123123', 'iloveyou', 'admin', 'welcome', 'login', '1234',
          ];
          return in_array(strtolower($password), $common);
        }
        function hasSequentialOrRepeatingChars($password) {
          for ($i = 0; $i < strlen($password) - 2; $i++) {
            $a = ord($password[$i]);
            $b = ord($password[$i+1]);
            $c = ord($password[$i+2]);
            if (($b == $a + 1 && $c == $b + 1) || ($b == $a - 1 && $c == $b - 1)) {
              return true;
            }
          }
          if (preg_match('/(.)\\1{2,}/', $password)) {
            return true;
          }
          return false;
        }
        function isPasswordValid($password) {
          $length = strlen($password);
          if ($length < 8 || $length > 64) return false;
          $complex = 0;
          if (preg_match('/[A-Z]/', $password)) $complex++;
          if (preg_match('/[a-z]/', $password)) $complex++;
          if (preg_match('/[0-9]/', $password)) $complex++;
          if (preg_match('/[^a-zA-Z0-9]/', $password)) $complex++;
          if ($complex < 3) return false;
          if (isCommonPassword($password)) return false;
          if (hasSequentialOrRepeatingChars($password)) return false;
          return true;
        }
        function validPHMobile($mobile) {
          $digits = preg_replace('/\D/', '', $mobile);
          if (strlen($digits) == 11 && strpos($digits, '09') === 0) {
            return true;
          }
          return false;
        }
        function normalizePHMobile($mobile) {
          $digits = preg_replace('/\D/', '', $mobile);
          if (strlen($digits) == 11) {
            return substr($digits, 0, 4) . ' ' . substr($digits, 4, 3) . ' ' . substr($digits, 7, 4);
          }
          return $mobile;
        }
        if (isset($_POST['submit'])) {
          $lastname = trim($_POST['lastname']);
          $firstname = trim($_POST['firstname']);
          $middlename = trim($_POST['middlename']);
          $name = $lastname . ', ' . $firstname . ($middlename ? ' ' . $middlename : '');

          $username = trim($_POST['username']);
          $password = $_POST['password'];
          $sex = $_POST['sex'];
          $birthdate = $_POST['birthday'];
          $birthDate = new DateTime($birthdate);
          $today = new DateTime();
          $age = $today->diff($birthDate)->y;
          $province = trim($_POST['province']);
          $municipality = trim($_POST['municipality']);
          $barangay = trim($_POST['barangay']);
          $street_address = trim($_POST['street_address']);
          $contact_number = normalizePHMobile($_POST['contact_number']);
          $role = $_POST['role'];

          if (!isPasswordValid($password)) {
            echo '<div class="w3-panel w3-red w3-round-large w3-padding"><p><i class="fa fa-exclamation-triangle"></i> Password does not meet the policy requirements.</p></div>';
          } elseif (!validPHMobile($contact_number)) {
            echo '<div class="w3-panel w3-red w3-round-large w3-padding"><p><i class="fa fa-exclamation-triangle"></i> Invalid contact number format. Use: 0961 488 9820</p></div>';
          } elseif (!in_array($role, ['employee', 'manager', 'user'])) {
            echo '<div class="w3-panel w3-red w3-round-large w3-padding"><p><i class="fa fa-exclamation-triangle"></i> Invalid role selected.</p></div>';
          } else {
            $profile_img = null;
            $uploadDir = "uploads/profiles/";
            if (!file_exists($uploadDir)) {
              mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES["profile_img"]) && $_FILES["profile_img"]["error"] == 0) {
              $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
              if (in_array($_FILES["profile_img"]["type"], $allowedTypes)) {
                $filename = uniqid("profile_", true) . "." . pathinfo($_FILES["profile_img"]["name"], PATHINFO_EXTENSION);
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $targetPath)) {
                  $profile_img = $targetPath;
                }
              }
            }
            if (empty($profile_img) && isset($_FILES["cameraInput"]) && $_FILES["cameraInput"]["error"] == 0) {
              $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
              if (in_array($_FILES["cameraInput"]["type"], $allowedTypes)) {
                $filename = uniqid("profile_", true) . "." . pathinfo($_FILES["cameraInput"]["name"], PATHINFO_EXTENSION);
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES["cameraInput"]["tmp_name"], $targetPath)) {
                  $profile_img = $targetPath;
                }
              }
            }
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->rowCount() > 0) {
              echo '<div class="w3-panel w3-red w3-round-large w3-padding"><p><i class="fa fa-exclamation-triangle"></i> Username already exists. Please choose a different username.</p></div>';
            } else {
              $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (first_name, middle_name, last_name, username, password, role, sex, age, province, municipality, barangay, street_address, contact_number, birthday, profile_img) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([
  $first_name, $middle_name, $last_name, $username, $hashed_password, $role, $sex, $age,
  $province, $municipality, $barangay, $street_address,
  $contact_number, $birthdate, $profile_img
]);
              echo '<div class="w3-panel w3-green w3-round-large w3-padding"><p><i class="fa fa-check-circle"></i> User added successfully.</p></div>';
              echo '<script>setTimeout(function(){ window.location.href = window.location.href; }, 1500);</script>';
            }
          }
        }
        ?>
      </div>
    </div>

    <div id="manageUsers" class="tab-content" style="display:none;">
      <div class="dashboard-card">
        <h3><i class="fa fa-users"></i> User List</h3>
        <p class="w3-text-grey" style="font-size:15px;">Manage existing system users</p>
        <hr>

        <form method="POST">
          <div class="w3-bar w3-margin-bottom">
            <button type="submit" name="delete_selected" class="w3-button w3-red w3-round-large w3-hover-dark-red" 
                    onclick="return confirm('Are you sure you want to delete the selected users? This action cannot be undone.')">
              <i class="fa fa-trash"></i> Delete Selected
            </button>
            <button type="submit" name="toggle_status" class="w3-button w3-blue w3-round-large w3-hover-dark-blue">
              <i class="fa fa-refresh"></i> Toggle Status
            </button>
            <div class="w3-right">
              <input type="text" id="userSearch" class="w3-input w3-round-large w3-border" placeholder="Search users..." style="width:auto;display:inline-block;">
              <button type="button" class="w3-button w3-light-grey w3-round-large"><i class="fa fa-search"></i></button>
            </div>
          </div>

          <div class="w3-responsive">
            <table class="w3-table-all w3-hoverable w3-card-4">
             <thead>
  <tr class="w3-blue">
    <th style="width:5%"><input type="checkbox" id="select-all"></th>
    <th style="width:7%">Avatar</th>
    <th style="width:8%">First Name</th>
    <th style="width:8%">Last Name</th>
    <th style="width:8%">Middle Name</th>
    <th style="width:11%">Username</th>
    <th style="width:13%">Status</th>
    <th style="width:12%">Province</th>
    <th style="width:12%">Municipality</th>
    <th style="width:12%">Barangay</th>
    <th style="width:14%">Last Login</th>
    <th style="width:20%">Actions</th>
  </tr>
</thead>
<tbody>
  <?php
  $limit = 10;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $start = ($page - 1) * $limit;
  $stmt = $db->query("SELECT * FROM users ORDER BY id DESC LIMIT $start, $limit");
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (count($users) > 0) {
    foreach ($users as $user) {
      echo '<tr>';
      echo '<td><input type="checkbox" name="user_ids[]" value="' . $user['id'] . '" class="user-checkbox"></td>';
      $avatar = !empty($user['profile_img']) && file_exists($user['profile_img']) ? $user['profile_img'] : 'assets/img/default-avatar.png';
      echo '<td><img src="'.$avatar.'" alt="avatar" style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:1.5px solid #e0e0e0;"></td>';
     echo '<td>' . htmlspecialchars($user['first_name'] ?? '') . '</td>';
echo '<td>' . htmlspecialchars($user['last_name'] ?? '') . '</td>';
echo '<td>' . htmlspecialchars($user['middle_name'] ?? '') . '</td>';
      echo '<td>' . htmlspecialchars($user['username']) . '</td>';
                    echo '<td>';
                    if (isset($user['status'])) {
                      echo $user['status'] == 1 ? 
                           '<span class="w3-tag w3-round-large w3-green"><i class="fa fa-check"></i> Active</span>' : 
                           '<span class="w3-tag w3-round-large w3-red"><i class="fa fa-times"></i> Inactive</span>';
                    } else {
                      echo '<span class="w3-tag w3-round-large w3-grey"><i class="fa fa-question"></i> Unknown</span>';
                    }
                    echo '</td>';
                    echo '<td>' . htmlspecialchars($user['province']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['municipality']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['barangay']) . '</td>';
                    echo '<td>' . (isset($user['last_login']) ? date('M d, Y h:i A', strtotime($user['last_login'])) : 'Never') . '</td>';
                    echo '<td>';
                    echo '<a href="edit_user.php?id=' . $user['id'] . '" class="w3-button w3-small w3-blue w3-round-large"><i class="fa fa-edit"></i> Edit</a> ';
                    echo '<a href="viewuser.php?id=' . $user['id'] . '" class="w3-button w3-small w3-green w3-round-large"><i class="fa fa-eye"></i> View</a>';
                    echo '</td>';
                    echo '</tr>';
                  }
                } else {
                  echo '<tr><td colspan="10" class="w3-center w3-padding-24"><i class="fa fa-exclamation-circle w3-text-grey"></i> No users found</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>

          <div class="w3-center w3-padding-16">
            <div class="w3-bar w3-border w3-round-large">
              <?php
              $stmt = $db->query("SELECT COUNT(id) AS total FROM users");
              $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
              $pages = ceil($totalUsers / $limit);
              if ($page > 1) {
                echo '<a href="?page='.($page - 1).'#manageUsers" class="w3-bar-item w3-button">&laquo;</a>';
              }
              for ($i = 1; $i <= $pages; $i++) {
                $active = ($i == $page) ? 'w3-blue' : '';
                echo '<a href="?page='.$i.'#manageUsers" class="w3-bar-item w3-button '.$active.'">'.$i.'</a>';
              }
              if ($page < $pages) {
                echo '<a href="?page='.($page + 1).'#manageUsers" class="w3-bar-item w3-button">&raquo;</a>';
              }
              ?>
            </div>
          </div>
        </form>
        
        <?php
        if (isset($_POST['delete_selected'])) {
            if (!empty($_POST['user_ids'])) {
                $placeholders = implode(',', array_fill(0, count($_POST['user_ids']), '?'));
                $stmt = $db->prepare("DELETE FROM users WHERE id IN ($placeholders)");
                $stmt->execute($_POST['user_ids']);
                echo '<div class="w3-panel w3-green w3-round-large w3-padding"><p><i class="fa fa-check-circle"></i> Selected users deleted successfully.</p></div>';
                echo '<script>setTimeout(function(){ window.location.href = window.location.href + "#manageUsers"; }, 1500);</script>';
            } else {
                echo '<div class="w3-panel w3-red w3-round-large w3-padding"><p><i class="fa fa-exclamation-triangle"></i> No users selected for deletion.</p></div>';
            }
        }
        if (isset($_POST['toggle_status'])) {
            if (!empty($_POST['user_ids'])) {
                $placeholders = implode(',', array_fill(0, count($_POST['user_ids']), '?'));
                $stmt = $db->prepare("UPDATE users SET status = NOT status WHERE id IN ($placeholders)");
                $stmt->execute($_POST['user_ids']);
                echo '<div class="w3-panel w3-green w3-round-large w3-padding"><p><i class="fa fa-check-circle"></i> Selected users status toggled successfully.</p></div>';
                echo '<script>setTimeout(function(){ window.location.href = window.location.href + "#manageUsers"; }, 1500);</script>';
            } else {
                echo '<div class="w3-panel w3-red w3-round-large w3-padding"><p><i class="fa fa-exclamation-triangle"></i> No users selected for status change.</p></div>';
            }
        }
        ?>
      </div>
    </div>
  </div>
</div>

<?php include 'theme/foot.php'; ?>

<script>
  function openTab(evt, tabName) {
    var i, x, tablinks;
    x = document.getElementsByClassName("tab-content");
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" w3-blue", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " w3-blue";
    window.location.hash = tabName;
  }
  window.onload = function() {
    loadProvinces();
  }
  function calculateAge() {
    var birthdayInput = document.getElementById("birthday").value;
    if (birthdayInput) {
      var birthday = new Date(birthdayInput);
      var today = new Date();
      var age = today.getFullYear() - birthday.getFullYear();
      var month = today.getMonth() - birthday.getMonth();
      if (month < 0 || (month === 0 && today.getDate() < birthday.getDate())) {
        age--;
      }
      document.getElementById("age").value = age;
    }
  }
  document.getElementById('password').addEventListener('input', function() {
    var password = this.value;
    var strength = 0;
    var complex = 0;
    var lengthOk = password.length >= 8 && password.length <= 64;
    var upper = /[A-Z]/.test(password);
    var lower = /[a-z]/.test(password);
    var num = /\d/.test(password);
    var special = /[^a-zA-Z0-9]/.test(password);
    if (upper) complex++;
    if (lower) complex++;
    if (num) complex++;
    if (special) complex++;
    if (lengthOk) strength++;
    if (complex >= 3) strength++;
    var commonList = ["password","123456","12345678","qwerty","abc123","letmein","monkey","dragon","111111","123123","iloveyou","admin","welcome","login","1234"];
    var isCommon = commonList.includes(password.toLowerCase());
    var repeatSeq = /(.)\1{2,}/.test(password);
    var seq = false;
    for (var i = 0; i < password.length - 2; i++) {
      var a = password.charCodeAt(i);
      var b = password.charCodeAt(i+1);
      var c = password.charCodeAt(i+2);
      if ((b == a + 1 && c == b + 1) || (b == a - 1 && c == b - 1)) {
        seq = true; break;
      }
    }
    var width = (strength / 2) * 100;
    var bar = document.getElementById('password-strength-bar');
    var text = document.getElementById('password-strength-text');
    var colors = ['w3-red', 'w3-yellow', 'w3-green'];
    var messages = ['Weak', 'Moderate', 'Strong'];
    bar.style.width = width + '%';
    bar.className = 'w3-container w3-round-large ' + colors[strength];
    text.textContent = messages[strength];
    text.className = 'w3-text-' + colors[strength].replace('w3-', '');
    document.getElementById('pass-length').className = lengthOk ? 'pass-ok' : 'pass-bad';
    document.getElementById('pass-complex').className = complex >= 3 ? 'pass-ok' : 'pass-bad';
    document.getElementById('pass-common').className = !isCommon ? 'pass-ok' : 'pass-bad';
    document.getElementById('pass-repeat').className = (!repeatSeq && !seq) ? 'pass-ok' : 'pass-bad';
  });
  function togglePassword() {
    var passwordField = document.getElementById("password");
    passwordField.type = passwordField.type === "password" ? "text" : "password";
  }
  function previewProfileImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('profilePreview');
      output.src = reader.result;
      document.getElementById('profilePreviewText').style.display = 'none';
    };
    if(event.target.files[0]) {
      reader.readAsDataURL(event.target.files[0]);
      document.getElementById('profilePreviewText').style.display = 'none';
    }
  }
  let mediaStream = null;
  function openCameraModal() {
    document.getElementById('cameraModal').style.display = 'flex';
    const video = document.getElementById('video');
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
          mediaStream = stream;
          video.srcObject = stream;
          video.play();
        })
        .catch(function(err) {
          alert('Could not access the camera. ' + err);
          closeCameraModal();
        });
    } else {
      alert('Camera not supported in this browser.');
      closeCameraModal();
    }
  }
  function closeCameraModal() {
    document.getElementById('cameraModal').style.display = 'none';
    if (mediaStream) {
      mediaStream.getTracks().forEach(track => track.stop());
      mediaStream = null;
    }
    document.getElementById('canvas').style.display = 'none';
    document.getElementById('video').style.display = '';
  }
  function takePhoto() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    canvas.width = 380;
    canvas.height = 285;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataURL = canvas.toDataURL('image/png');
    document.getElementById('profilePreview').src = dataURL;
    document.getElementById('cameraPhotoData').value = dataURL;
    document.getElementById('profilePreviewText').style.display = 'none';
    canvas.style.display = 'block';
    video.style.display = 'none';
    if (mediaStream) {
      mediaStream.getTracks().forEach(track => track.stop());
      mediaStream = null;
    }
    setTimeout(closeCameraModal, 700);
  }
  function loadProvinces() {
    const addressData = {
      "Ilocos Sur": {
        "Vigan City": ["Ayusan Norte", "Ayusan Sur", "Bantay", "Barraca"]
      }
    };
    var provinceSel = document.getElementById("province");
    provinceSel.innerHTML = '<option value="" disabled selected>Select province</option>';
    for (const prov in addressData) {
      var opt = document.createElement("option");
      opt.value = prov;
      opt.textContent = prov;
      provinceSel.appendChild(opt);
    }
    document.getElementById("municipality").innerHTML = '<option value="" disabled selected>Select municipality/city</option>';
    document.getElementById("barangay").innerHTML = '<option value="" disabled selected>Select barangay</option>';

    provinceSel.onchange = function() {
      var province = this.value;
      var muniSel = document.getElementById("municipality");
      muniSel.innerHTML = '<option value="" disabled selected>Select municipality/city</option>';
      document.getElementById("barangay").innerHTML = '<option value="" disabled selected>Select barangay</option>';
      if (addressData[province]) {
        for(const muni in addressData[province]) {
          var opt = document.createElement("option");
          opt.value = muni;
          opt.textContent = muni;
          muniSel.appendChild(opt);
        }
      }
    };
    document.getElementById("municipality").onchange = function() {
      var province = provinceSel.value;
      var muni = this.value;
      var brgySel = document.getElementById("barangay");
      brgySel.innerHTML = '<option value="" disabled selected>Select barangay</option>';
      if (addressData[province] && addressData[province][muni]) {
        addressData[province][muni].forEach(function(brgy){
          var opt = document.createElement("option");
          opt.value = brgy;
          opt.textContent = brgy;
          brgySel.appendChild(opt);
        });
      }
    };
  }
  document.getElementById("contact_number").addEventListener("input", function(){
    var val = this.value.replace(/\D/g,'');
    if(val.length === 11 && val.startsWith('09')) {
      var formatted = val.substring(0,4) + " " + val.substring(4,7) + " " + val.substring(7,11);
      this.value = formatted;
      document.getElementById("contact_error").style.display = "none";
    } else {
      document.getElementById("contact_error").style.display = val.length === 0 ? "none" : "block";
    }
  });
</script>