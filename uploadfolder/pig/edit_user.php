<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php
// Check if 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch user details from the database
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user not found, redirect to user list
    if (!$user) {
        header("Location: add-users.php");
        exit();
    }
} else {
    header("Location: add-users.php");
    exit();
}

// Handle form submission to update user details
if (isset($_POST['update'])) {
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $status = $_POST['status'];
    $name = trim($_POST['name']);
    $sex = $_POST['sex'];
    $age = $_POST['age'];
    $address = trim($_POST['address']);
    $contact_number = trim($_POST['contact_number']);

    // Update user details in the database
    $stmt = $db->prepare("UPDATE users SET username = ?, role = ?, status = ?, name = ?, sex = ?, age = ?, address = ?, contact_number = ? WHERE id = ?");
    $stmt->execute([$username, $role, $status, $name, $sex, $age, $address, $contact_number, $userId]);

    echo '<div class="w3-panel w3-green"><p>User updated successfully.</p></div>';
}
?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Edit User</b></h5>
  </header>

  <div class="w3-container" style="padding-top:22px">
    <form method="POST">
      <div class="w3-row">
        <div class="w3-col s6">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" class="w3-input" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="w3-col s6">
          <label for="role">Role:</label>
          <select id="role" name="role" class="w3-select" required>
            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="employee" <?php echo $user['role'] == 'employee' ? 'selected' : ''; ?>>Employee</option>
            <option value="veterinarian" <?php echo $user['role'] == 'veterinarian' ? 'selected' : ''; ?>>Veterinarian</option>
            <option value="owner" <?php echo $user['role'] == 'owner' ? 'selected' : ''; ?>>Owner</option>
          </select>
        </div>
      </div>

      <div class="w3-row">
        <div class="w3-col s6">
          <label for="status">Status:</label>
          <select id="status" name="status" class="w3-select" required>
            <option value="1" <?php echo $user['status'] == 1 ? 'selected' : ''; ?>>Active</option>
            <option value="0" <?php echo $user['status'] == 0 ? 'selected' : ''; ?>>Blocked</option>
          </select>
        </div>
      </div>

      <div class="w3-row">
        <div class="w3-col s6">
          <label for="name">Full Name:</label>
          <input type="text" id="name" name="name" class="w3-input" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>
        <div class="w3-col s6">
          <label for="sex">Sex:</label>
          <select id="sex" name="sex" class="w3-select" required>
            <option value="Male" <?php echo $user['sex'] == 'Male' ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo $user['sex'] == 'Female' ? 'selected' : ''; ?>>Female</option>
          </select>
        </div>
      </div>

      <div class="w3-row">
        <div class="w3-col s6">
          <label for="age">Age:</label>
          <input type="text" id="age" name="age" class="w3-input" value="<?php echo htmlspecialchars($user['age']); ?>" readonly>
        </div>
        <div class="w3-col s6">
          <label for="birthday">Birthday:</label>
          <input type="date" id="birthday" name="birthday" class="w3-input" value="<?php echo htmlspecialchars($user['birthday']); ?>" required onchange="calculateAge()">
        </div>
      </div>

      <div class="w3-row">
        <div class="w3-col s6">
          <label for="address">Address:</label>
          <input type="text" id="address" name="address" class="w3-input" value="<?php echo htmlspecialchars($user['address']); ?>" required>
        </div>
        <div class="w3-col s6">
          <label for="contact_number">Contact Number:</label>
          <input type="text" id="contact_number" name="contact_number" class="w3-input" value="<?php echo htmlspecialchars($user['contact_number']); ?>" required>
        </div>
      </div>

      <button type="submit" name="update" class="w3-button w3-dark-grey">Update User</button>
    </form>
  </div>
</div>

<script>
  function calculateAge() {
    var birthdate = document.getElementById('birthday').value;
    var birthDateObj = new Date(birthdate);
    var today = new Date();
    var age = today.getFullYear() - birthDateObj.getFullYear();
    var m = today.getMonth() - birthDateObj.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDateObj.getDate())) {
      age--;
    }
    document.getElementById('age').value = age;
  }
</script>

<?php include 'theme/foot.php'; ?>
