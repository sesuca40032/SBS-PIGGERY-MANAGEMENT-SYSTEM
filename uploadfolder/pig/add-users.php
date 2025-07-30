<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <!-- Page Header -->
  <header class="w3-container w3-padding-24">
    <div class="w3-row">
      <div class="w3-col m8">
        <h1 class="w3-text-dark-grey"><b><i class="fa fa-user-plus"></i> User Management</b></h1>
        <p class="w3-text-grey">Add and manage system users</p>
      </div>
      <div class="w3-col m4 w3-right">
        <div class="w3-panel w3-light-grey w3-round-large w3-padding">
          <i class="fa fa-users w3-large w3-text-blue"></i>
          <?php
          $stmt = $db->query("SELECT COUNT(*) AS total_users FROM users");
          $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
          ?>
          <span class="w3-large"><?php echo $totalUsers; ?></span> Total Users
        </div>
      </div>
    </div>
  </header>

  <div class="w3-container">
    <!-- Tab Navigation -->
    <div class="w3-bar w3-light-grey w3-round-large">
      <button class="w3-bar-item w3-button tablink w3-blue" onclick="openTab(event,'addUser')"><i class="fa fa-plus-circle"></i> Add User</button>
      <button class="w3-bar-item w3-button tablink" onclick="openTab(event,'manageUsers')"><i class="fa fa-users"></i> Manage Users</button>
    </div>

    <!-- Add User Tab -->
    <div id="addUser" class="w3-container w3-border tab-content" style="display:block; padding:24px 16px;">
      <div class="w3-card w3-round-large w3-white w3-padding">
        <h3 class="w3-text-dark-grey"><i class="fa fa-user-plus"></i> Create New User</h3>
        <p class="w3-text-grey">Fill in the form below to add a new system user</p>
        <hr>

        <form method="POST">
          <div class="w3-row-padding">
            <div class="w3-half w3-margin-bottom">
              <label for="name" class="w3-text-dark-grey"><b>Full Name</b></label>
              <input type="text" id="name" name="name" class="w3-input w3-round-large w3-border" placeholder="John Doe" required>
            </div>
            <div class="w3-half w3-margin-bottom">
              <label for="username" class="w3-text-dark-grey"><b>Username</b></label>
              <input type="text" id="username" name="username" class="w3-input w3-round-large w3-border" placeholder="johndoe" required>
            </div>
          </div>

          <div class="w3-row-padding">
            <div class="w3-half w3-margin-bottom">
              <label for="password" class="w3-text-dark-grey"><b>Password</b></label>
              <div class="w3-row">
                <div class="w3-col" style="width:90%">
                  <input type="password" id="password" name="password" class="w3-input w3-round-large w3-border" required>
                </div>
                <div class="w3-col" style="width:10%">
                  <button type="button" class="w3-button w3-round-large w3-border" onclick="togglePassword()" style="margin-left:8px;">
                    <i class="fa fa-eye"></i>
                  </button>
                </div>
              </div>
              <div class="w3-light-grey w3-round-large w3-small w3-padding-small" style="margin-top:4px;">
                <div id="password-strength-bar" class="w3-container w3-round-large w3-blue" style="height:5px;width:0%"></div>
              </div>
              <span id="password-strength-text" class="w3-small"></span>
            </div>
            <div class="w3-half w3-margin-bottom">
              <label for="role" class="w3-text-dark-grey"><b>Role</b></label>
              <select id="role" name="role" class="w3-select w3-round-large w3-border" required>
                <option value="" disabled selected>Select user role</option>
                <option value="admin">Administrator</option>
                <option value="employee">Employee</option>
                <option value="veterinarian">Veterinarian</option>
                <option value="owner">Owner</option>
              </select>
            </div>
          </div>

          <div class="w3-row-padding">
            <div class="w3-half w3-margin-bottom">
              <label for="sex" class="w3-text-dark-grey"><b>Gender</b></label>
              <select id="sex" name="sex" class="w3-select w3-round-large w3-border" required>
                <option value="" disabled selected>Select gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="w3-half w3-margin-bottom">
              <label for="age" class="w3-text-dark-grey"><b>Age</b></label>
              <input type="number" id="age" name="age" class="w3-input w3-round-large w3-border" readonly>
            </div>
          </div>

          <div class="w3-row-padding">
            <div class="w3-half w3-margin-bottom">
              <label for="address" class="w3-text-dark-grey"><b>Address</b></label>
              <input type="text" id="address" name="address" class="w3-input w3-round-large w3-border" placeholder="123 Main St" required>
            </div>
            <div class="w3-half w3-margin-bottom">
              <label for="contact_number" class="w3-text-dark-grey"><b>Contact Number</b></label>
              <input type="text" id="contact_number" name="contact_number" class="w3-input w3-round-large w3-border" placeholder="+1 234 567 8900" required>
            </div>
          </div>

          <div class="w3-row-padding">
            <div class="w3-half w3-margin-bottom">
              <label for="birthday" class="w3-text-dark-grey"><b>Date of Birth</b></label>
              <input type="date" id="birthday" name="birthday" class="w3-input w3-round-large w3-border" required onchange="calculateAge()">
            </div>
          </div>

          <div class="w3-margin-top">
            <button type="submit" name="submit" class="w3-button w3-blue w3-round-large w3-hover-dark-grey">
              <i class="fa fa-save"></i> Save User
            </button>
            <button type="reset" class="w3-button w3-light-grey w3-round-large">
              <i class="fa fa-undo"></i> Reset
            </button>
          </div>
        </form>

        <?php
        if (isset($_POST['submit'])) {
          $name = trim($_POST['name']);
          $username = trim($_POST['username']);
          $password = sha1($_POST['password']); // Password hash
          $role = $_POST['role'];
          $sex = $_POST['sex'];
          $birthdate = $_POST['birthday'];

          // Calculate age based on birthdate
          $birthDate = new DateTime($birthdate);
          $today = new DateTime();
          $age = $today->diff($birthDate)->y;

          $address = $_POST['address'];
          $contact_number = $_POST['contact_number'];

          // Check if username already exists
          $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
          $stmt->execute([$username]);
          
          if ($stmt->rowCount() > 0) {
            echo '<div class="w3-panel w3-red w3-round-large w3-padding"><p><i class="fa fa-exclamation-triangle"></i> Username already exists. Please choose a different username.</p></div>';
          } else {
            // Insert new user into database
            $stmt = $db->prepare("INSERT INTO users (name, username, password, role, sex, age, address, contact_number, birthday) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $username, $password, $role, $sex, $age, $address, $contact_number, $birthdate]);

            echo '<div class="w3-panel w3-green w3-round-large w3-padding"><p><i class="fa fa-check-circle"></i> User added successfully.</p></div>';
            
            // Refresh the page to show the new user
            echo '<script>setTimeout(function(){ window.location.href = window.location.href; }, 1500);</script>';
          }
        }
        ?>
      </div>
    </div>

    <!-- Manage Users Tab -->
    <div id="manageUsers" class="w3-container w3-border tab-content" style="display:none; padding:24px 16px;">
      <div class="w3-card w3-round-large w3-white w3-padding">
        <h3 class="w3-text-dark-grey"><i class="fa fa-users"></i> User List</h3>
        <p class="w3-text-grey">Manage existing system users</p>
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
                  <th style="width:20%">Name</th>
                  <th style="width:15%">Username</th>
                  <th style="width:15%">Role</th>
                  <th style="width:15%">Status</th>
                  <th style="width:15%">Last Login</th>
                  <th style="width:15%">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Fetch all users with pagination
                $limit = 10; // Number of records per page
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $start = ($page - 1) * $limit;
                
                $stmt = $db->query("SELECT * FROM users ORDER BY id DESC LIMIT $start, $limit");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($users) > 0) {
                  foreach ($users as $user) {
                    echo '<tr>';
                    echo '<td><input type="checkbox" name="user_ids[]" value="' . $user['id'] . '" class="user-checkbox"></td>';
                    echo '<td>' . htmlspecialchars($user['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['username']) . '</td>';
                    echo '<td><span class="w3-tag w3-round-large ';
                    
                    // Different colors for different roles
                    switch($user['role']) {
                      case 'admin': echo 'w3-indigo'; break;
                      case 'employee': echo 'w3-green'; break;
                      case 'veterinarian': echo 'w3-orange'; break;
                      case 'owner': echo 'w3-purple'; break;
                      default: echo 'w3-blue';
                    }
                    
                    echo '">' . htmlspecialchars(ucfirst($user['role'])) . '</span></td>';
                    echo '<td>';
                    
                    // Handle 'status' key existence check
                    if (isset($user['status'])) {
                      echo $user['status'] == 1 ? 
                           '<span class="w3-tag w3-round-large w3-green"><i class="fa fa-check"></i> Active</span>' : 
                           '<span class="w3-tag w3-round-large w3-red"><i class="fa fa-times"></i> Inactive</span>';
                    } else {
                      echo '<span class="w3-tag w3-round-large w3-grey"><i class="fa fa-question"></i> Unknown</span>';
                    }
                    
                    echo '</td>';
                    echo '<td>' . (isset($user['last_login']) ? date('M d, Y h:i A', strtotime($user['last_login'])) : 'Never') . '</td>';
                    echo '<td>';
                    echo '<a href="edit_user.php?id=' . $user['id'] . '" class="w3-button w3-small w3-blue w3-round-large"><i class="fa fa-edit"></i> Edit</a> ';
                    echo '<a href="view_user.php?id=' . $user['id'] . '" class="w3-button w3-small w3-green w3-round-large"><i class="fa fa-eye"></i> View</a>';
                    echo '</td>';
                    echo '</tr>';
                  }
                } else {
                  echo '<tr><td colspan="7" class="w3-center w3-padding-24"><i class="fa fa-exclamation-circle w3-text-grey"></i> No users found</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="w3-center w3-padding-16">
            <div class="w3-bar w3-border w3-round-large">
              <?php
              $stmt = $db->query("SELECT COUNT(id) AS total FROM users");
              $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
              $pages = ceil($totalUsers / $limit);
              
              // Previous button
              if ($page > 1) {
                echo '<a href="?page='.($page - 1).'#manageUsers" class="w3-bar-item w3-button">&laquo;</a>';
              }
              
              // Page numbers
              for ($i = 1; $i <= $pages; $i++) {
                $active = ($i == $page) ? 'w3-blue' : '';
                echo '<a href="?page='.$i.'#manageUsers" class="w3-bar-item w3-button '.$active.'">'.$i.'</a>';
              }
              
              // Next button
              if ($page < $pages) {
                echo '<a href="?page='.($page + 1).'#manageUsers" class="w3-bar-item w3-button">&raquo;</a>';
              }
              ?>
            </div>
          </div>
        </form>
        
        <?php
        // Handle bulk delete
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
        
        // Handle bulk status toggle
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
  // Tab functionality
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
    
    // Update URL hash
    window.location.hash = tabName;
  }
  
  // Check if hash exists on page load
  window.onload = function() {
    if (window.location.hash) {
      var hash = window.location.hash.substring(1);
      var tablinks = document.getElementsByClassName("tablink");
      
      for (var i = 0; i < tablinks.length; i++) {
        if (tablinks[i].getAttribute('onclick').includes(hash)) {
          tablinks[i].click();
          break;
        }
      }
    }
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
  
  // Select all checkbox functionality
  document.getElementById('select-all').addEventListener('click', function(e) {
    var checkboxes = document.getElementsByClassName('user-checkbox');
    for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].checked = e.target.checked;
    }
  });
  
  // Password strength meter
  document.getElementById('password').addEventListener('input', function() {
    var password = this.value;
    var strength = 0;
    
    // Check length
    if (password.length >= 8) strength += 1;
    if (password.length >= 12) strength += 1;
    
    // Check for mixed case
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;
    
    // Check for numbers
    if (/\d/.test(password)) strength += 1;
    
    // Check for special chars
    if (/[^a-zA-Z0-9]/.test(password)) strength += 1;
    
    // Update UI
    var bar = document.getElementById('password-strength-bar');
    var text = document.getElementById('password-strength-text');
    var colors = ['w3-red', 'w3-orange', 'w3-yellow', 'w3-light-green', 'w3-green'];
    var messages = ['Very Weak', 'Weak', 'Moderate', 'Strong', 'Very Strong'];
    
    var width = (strength / 5) * 100;
    bar.style.width = width + '%';
    bar.className = 'w3-container w3-round-large ' + colors[strength];
    text.textContent = messages[strength];
    text.className = 'w3-text-' + colors[strength].replace('w3-', '');
  });
  
  // Toggle password visibility
  function togglePassword() {
    var passwordField = document.getElementById("password");
    if (passwordField.type === "password") {
      passwordField.type = "text";
    } else {
      passwordField.type = "password";
    }
  }
</script>