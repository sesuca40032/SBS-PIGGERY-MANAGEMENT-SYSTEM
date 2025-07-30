<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

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

if (isset($_POST['update'])) {
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $suffix = trim($_POST['suffix']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $status = $_POST['status'];
    $sex = $_POST['sex'];
    $birthdate = $_POST['birthday'];
    $birthDateObj = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($birthDateObj)->y;
    $province = trim($_POST['province']);
    $municipality = trim($_POST['municipality']);
    $barangay = trim($_POST['barangay']);
    $street_address = trim($_POST['street_address']);
    $contact_number = normalizePHMobile($_POST['contact_number']);

    $profile_img = $user['profile_img'];
    $uploadDir = "uploads/profiles/";
    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
    if (isset($_FILES["profile_img"]) && $_FILES["profile_img"]["error"] == 0) {
      $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
      if (in_array($_FILES["profile_img"]["type"], $allowedTypes)) {
        $filename = uniqid("profile_", true) . "." . pathinfo($_FILES["profile_img"]["name"], PATHINFO_EXTENSION);
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $targetPath)) {
          $profile_img = $targetPath;
        }
      }
    }
    if ((empty($profile_img) || $profile_img == $user['profile_img']) && isset($_FILES["cameraInput"]) && $_FILES["cameraInput"]["error"] == 0) {
      $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
      if (in_array($_FILES["cameraInput"]["type"], $allowedTypes)) {
        $filename = uniqid("profile_", true) . "." . pathinfo($_FILES["cameraInput"]["name"], PATHINFO_EXTENSION);
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES["cameraInput"]["tmp_name"], $targetPath)) {
          $profile_img = $targetPath;
        }
      }
    }
    if ((empty($profile_img) || $profile_img == $user['profile_img']) && !empty($_POST['cameraPhotoData'])) {
      $imgData = $_POST['cameraPhotoData'];
      if (preg_match('/^data:image\/(\w+);base64,/', $imgData, $type)) {
        $data = substr($imgData, strpos($imgData, ',') + 1);
        $data = base64_decode($data);
        $ext = strtolower($type[1]);
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
          $filename = uniqid("profile_", true) . "." . $ext;
          $targetPath = $uploadDir . $filename;
          file_put_contents($targetPath, $data);
          $profile_img = $targetPath;
        }
      }
    }

    $stmt = $db->prepare("UPDATE users SET username = ?, role = ?, status = ?, first_name = ?, middle_name = ?, last_name = ?, suffix = ?, sex = ?, age = ?, province = ?, municipality = ?, barangay = ?, street_address = ?, contact_number = ?, birthday = ?, profile_img = ? WHERE id = ?");
    $stmt->execute([
      $username, $role, $status, $first_name, $middle_name, $last_name, $suffix, $sex, $age,
      $province, $municipality, $barangay, $street_address,
      $contact_number, $birthdate, $profile_img, $userId
    ]);

    $user['username'] = $username;
    $user['role'] = $role;
    $user['status'] = $status;
    $user['first_name'] = $first_name;
    $user['middle_name'] = $middle_name;
    $user['last_name'] = $last_name;
    $user['suffix'] = $suffix;
    $user['sex'] = $sex;
    $user['age'] = $age;
    $user['province'] = $province;
    $user['municipality'] = $municipality;
    $user['barangay'] = $barangay;
    $user['street_address'] = $street_address;
    $user['contact_number'] = $contact_number;
    $user['birthday'] = $birthdate;
    $user['profile_img'] = $profile_img;

    echo '<div class="w3-panel w3-green w3-round-large w3-padding"><p><i class="fa fa-check-circle"></i> User updated successfully.</p></div>';
}
?>

<style>
  .profile-upload img {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #dedede;
    margin-bottom: 10px;
  }
  .profile-upload label, .profile-upload .camera-btn {
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
    margin-bottom: 0;
  }
  .profile-upload label:hover {
    background: #e8f0fe;
    border-color: #4285f4;
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
</style>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Edit User</b></h5>
  </header>

  <!-- Camera Modal -->
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
  <!-- End Camera Modal -->

  <div class="w3-container form-one-col" style="padding-top:22px;max-width:520px;">
    <form method="POST" enctype="multipart/form-data" id="editUserForm" autocomplete="off">
      <div class="w3-row-padding">
        <div>
          <div class="profile-upload">
            <img src="<?php echo !empty($user['profile_img']) && file_exists($user['profile_img']) ? $user['profile_img'] : 'assets/img/default-avatar.png'; ?>" id="profilePreview" alt="Profile Preview">
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
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" class="w3-input w3-round-large w3-border" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="role">Role:</label>
          <select id="role" name="role" class="w3-select w3-round-large w3-border" required>
            <?php
              $roles = $db->query("SELECT DISTINCT role FROM users WHERE role != 'admin'");
              foreach ($roles as $r) {
                echo '<option value="'.htmlspecialchars($r['role']).'" '.($user['role']==$r['role']?'selected':'').'>'.ucwords(htmlspecialchars($r['role'])).'</option>';
              }
              if ($user['role'] == 'admin') echo '<option value="admin" selected>Admin</option>';
            ?>
          </select>
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="status">Status:</label>
          <select id="status" name="status" class="w3-select w3-round-large w3-border" required>
            <option value="1" <?php echo $user['status'] == 1 ? 'selected' : ''; ?>>Active</option>
            <option value="0" <?php echo $user['status'] == 0 ? 'selected' : ''; ?>>Blocked</option>
          </select>
        </div>
      </div>
      <!-- Atomic name fields -->
      <div class="w3-row-padding">
        <div>
          <label for="first_name">First Name:</label>
          <input type="text" id="first_name" name="first_name" class="w3-input w3-round-large w3-border" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="middle_name">Middle Name:</label>
          <input type="text" id="middle_name" name="middle_name" class="w3-input w3-round-large w3-border" value="<?php echo htmlspecialchars($user['middle_name']); ?>">
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="last_name">Last Name:</label>
          <input type="text" id="last_name" name="last_name" class="w3-input w3-round-large w3-border" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="suffix">Suffix:</label>
          <input type="text" id="suffix" name="suffix" class="w3-input w3-round-large w3-border" value="<?php echo htmlspecialchars($user['suffix']); ?>">
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="sex">Gender:</label>
          <select id="sex" name="sex" class="w3-select w3-round-large w3-border" required>
            <option value="male" <?php echo strtolower($user['sex']) == 'male' ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo strtolower($user['sex']) == 'female' ? 'selected' : ''; ?>>Female</option>
            <option value="other" <?php echo strtolower($user['sex']) == 'other' ? 'selected' : ''; ?>>Other</option>
          </select>
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="province">Province:</label>
          <select id="province" name="province" class="w3-select w3-round-large w3-border" required>
            <option value="" disabled>Select province</option>
          </select>
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="municipality">Municipality/City:</label>
          <select id="municipality" name="municipality" class="w3-select w3-round-large w3-border" required>
            <option value="" disabled>Select municipality/city</option>
          </select>
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="barangay">Barangay:</label>
          <select id="barangay" name="barangay" class="w3-select w3-round-large w3-border" required>
            <option value="" disabled>Select barangay</option>
          </select>
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="street_address">Street Address:</label>
          <input type="text" id="street_address" name="street_address" class="w3-input w3-round-large w3-border" value="<?php echo htmlspecialchars($user['street_address']); ?>">
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="contact_number">Contact Number:</label>
          <input type="text" id="contact_number" name="contact_number" class="w3-input w3-round-large w3-border" value="<?php echo htmlspecialchars($user['contact_number']); ?>" required maxlength="13">
          <span id="contact_error" style="font-size:12px;color:#d32f2f;display:none;">Format: 0961 488 9820</span>
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="birthday">Birthday:</label>
          <input type="date" id="birthday" name="birthday" class="w3-input w3-round-large w3-border" value="<?php echo htmlspecialchars($user['birthday']); ?>" required onchange="calculateAge()">
        </div>
      </div>
      <div class="w3-row-padding">
        <div>
          <label for="age">Age:</label>
          <input type="number" id="age" name="age" class="w3-input w3-round-large w3-border" value="<?php echo htmlspecialchars($user['age']); ?>" readonly>
        </div>
      </div>
      <div class="w3-padding-8" style="text-align:center;">
        <button type="submit" name="update" class="w3-button w3-blue w3-round-large"><i class="fa fa-save"></i> Update User</button>
      </div>
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
  function previewProfileImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('profilePreview');
      output.src = reader.result;
    };
    if(event.target.files[0]) {
      reader.readAsDataURL(event.target.files[0]);
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
    canvas.style.display = 'block';
    video.style.display = 'none';
    if (mediaStream) {
      mediaStream.getTracks().forEach(track => track.stop());
      mediaStream = null;
    }
    setTimeout(closeCameraModal, 700);
  }

  // --- Address Dropdowns: Example with static data (Replace with full fetch for production) ---
  function loadProvinces() {
    var addressData = {
      "Metro Manila": {
        "Quezon City": ["Bagong Pag-asa", "Batasan Hills", "Commonwealth"],
        "Manila": ["Sampaloc", "Tondo", "Ermita"],
        "Makati": ["Bel-Air", "San Lorenzo", "Poblacion"],
        "Pasig": ["Bagong Ilog", "Santolan", "Ugong"]
      },
      "Cavite": {
        "Tagaytay City": ["Kaybagal South", "Maharlika East", "Silang Crossing East"],
        "Dasmariñas City": ["Burol", "Paliparan", "Salawag"]
      },
      "Cebu": {
        "Cebu City": ["Banilad", "Lahug", "Mabolo"],
        "Mandaue City": ["Basak", "Cabancalan", "Casili"]
      },
      "Davao del Sur": {
        "Davao City": ["Agdao", "Buhangin", "Talomo"],
        "Digos City": ["Aplaya", "Balabag", "San Jose"]
      },
      "Pampanga": {
        "Angeles City": ["Balibago", "Malabanias", "Pampang"],
        "San Fernando": ["Sindalan", "Dolores", "San Agustin"]
      }
    };
    var provinceSel = document.getElementById("province");
    provinceSel.innerHTML = '<option value="" disabled>Select province</option>';
    for (const prov in addressData) {
      var opt = document.createElement("option");
      opt.value = prov;
      opt.textContent = prov;
      provinceSel.appendChild(opt);
    }
    // Set old value
    if ("<?php echo addslashes($user['province'] ?? ''); ?>") {
      provinceSel.value = "<?php echo addslashes($user['province'] ?? ''); ?>";
    }
    loadMunicipalities(addressData);
  }
  function loadMunicipalities(addressData) {
    var provinceSel = document.getElementById("province");
    var muniSel = document.getElementById("municipality");
    muniSel.innerHTML = '<option value="" disabled>Select municipality/city</option>';
    document.getElementById("barangay").innerHTML = '<option value="" disabled>Select barangay</option>';
    var province = provinceSel.value;
    if (addressData[province]) {
      for(const muni in addressData[province]) {
        var opt = document.createElement("option");
        opt.value = muni;
        opt.textContent = muni;
        muniSel.appendChild(opt);
      }
    }
    if ("<?php echo addslashes($user['municipality'] ?? ''); ?>") {
      muniSel.value = "<?php echo addslashes($user['municipality'] ?? ''); ?>";
    }
    loadBarangays(addressData);
  }
  function loadBarangays(addressData) {
    var provinceSel = document.getElementById("province");
    var muniSel = document.getElementById("municipality");
    var brgySel = document.getElementById("barangay");
    brgySel.innerHTML = '<option value="" disabled>Select barangay</option>';
    var province = provinceSel.value;
    var muni = muniSel.value;
    if (addressData[province] && addressData[province][muni]) {
      addressData[province][muni].forEach(function(brgy){
        var opt = document.createElement("option");
        opt.value = brgy;
        opt.textContent = brgy;
        brgySel.appendChild(opt);
      });
    }
    if ("<?php echo addslashes($user['barangay'] ?? ''); ?>") {
      brgySel.value = "<?php echo addslashes($user['barangay'] ?? ''); ?>";
    }
  }
  document.addEventListener('DOMContentLoaded', function() {
    loadProvinces();
    document.getElementById("province").addEventListener("change", function(){
      loadMunicipalities({
        "Metro Manila": {
          "Quezon City": ["Bagong Pag-asa", "Batasan Hills", "Commonwealth"],
          "Manila": ["Sampaloc", "Tondo", "Ermita"],
          "Makati": ["Bel-Air", "San Lorenzo", "Poblacion"],
          "Pasig": ["Bagong Ilog", "Santolan", "Ugong"]
        },
        "Cavite": {
          "Tagaytay City": ["Kaybagal South", "Maharlika East", "Silang Crossing East"],
          "Dasmariñas City": ["Burol", "Paliparan", "Salawag"]
        },
        "Cebu": {
          "Cebu City": ["Banilad", "Lahug", "Mabolo"],
          "Mandaue City": ["Basak", "Cabancalan", "Casili"]
        },
        "Davao del Sur": {
          "Davao City": ["Agdao", "Buhangin", "Talomo"],
          "Digos City": ["Aplaya", "Balabag", "San Jose"]
        },
        "Pampanga": {
          "Angeles City": ["Balibago", "Malabanias", "Pampang"],
          "San Fernando": ["Sindalan", "Dolores", "San Agustin"]
        }
      });
    });
    document.getElementById("municipality").addEventListener("change", function(){
      loadBarangays({
        "Metro Manila": {
          "Quezon City": ["Bagong Pag-asa", "Batasan Hills", "Commonwealth"],
          "Manila": ["Sampaloc", "Tondo", "Ermita"],
          "Makati": ["Bel-Air", "San Lorenzo", "Poblacion"],
          "Pasig": ["Bagong Ilog", "Santolan", "Ugong"]
        },
        "Cavite": {
          "Tagaytay City": ["Kaybagal South", "Maharlika East", "Silang Crossing East"],
          "Dasmariñas City": ["Burol", "Paliparan", "Salawag"]
        },
        "Cebu": {
          "Cebu City": ["Banilad", "Lahug", "Mabolo"],
          "Mandaue City": ["Basak", "Cabancalan", "Casili"]
        },
        "Davao del Sur": {
          "Davao City": ["Agdao", "Buhangin", "Talomo"],
          "Digos City": ["Aplaya", "Balabag", "San Jose"]
        },
        "Pampanga": {
          "Angeles City": ["Balibago", "Malabanias", "Pampang"],
          "San Fernando": ["Sindalan", "Dolores", "San Agustin"]
        }
      });
    });
  });

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

<?php include 'theme/foot.php'; ?>