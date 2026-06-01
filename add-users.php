<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>
<style>
/* Modern User Management Styling - Blue Color Scheme */
/* Reduced gaps to fit textboxes on one screen view */
.modern-dashboard {
  font-family: 'Segoe UI', 'Roboto', 'Inter', Arial, sans-serif;
  min-height: 100vh;
  background: #f7f8fa;
  padding: 0 0 24px 0; /* reduced bottom padding */
  position: relative;
  overflow-x: hidden;
}

.modern-dashboard::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: none;
  opacity: 0;
  pointer-events: none;
}

/* Hero Section */
.hero-section {
  background: #38598b;
  color: #fff;
  border-radius: 0 0 12px 12px; /* slightly smaller radius */
  margin-bottom: 20px; /* reduced space under hero */
  padding: 22px 28px 14px 28px; /* reduced vertical and horizontal padding */
  box-shadow: 0 4px 20px -10px #38598b40;
  border: none;
}

.hero-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px; /* reduced gap */
}

.hero-title {
  font-size: 2.0rem; /* slightly smaller to save space */
  font-weight: 800;
  color: #fff;
  margin: 0;
  text-shadow: 0 2px 8px rgba(56,89,139,0.13);
  letter-spacing: -0.5px;
}

.hero-title i {
  margin-right: 10px;
  color: #ffd700;
}

.hero-subtitle {
  font-size: 1rem;
  color: #e6e9f2;
  margin: 6px 0 0 0;
  font-weight: 400;
}

.hero-stats {
  display: flex;
  align-items: center;
  gap: 10px;
}

.stat-badge {
  background: #fff;
  color: #38598b;
  font-weight: 700;
  font-size: 1rem;
  border-radius: 18px;
  padding: 8px 16px; /* reduced padding */
  box-shadow: 0 2px 8px -2px #00000018;
  border: none;
}

.stat-badge i {
  margin-right: 8px;
  color: #38598b;
}

/* Tab Navigation */
.tab-navigation {
  margin: 0 28px 18px 28px; /* reduced side margins and bottom */
}

.tab-container {
  display: flex;
  background: #e6e9f2;
  border-radius: 12px;
  padding: 6px; /* reduced padding */
  border: 1px solid #b4c7e7;
}

.tab-button {
  flex: 1;
  background: transparent;
  border: none;
  color: #38598b;
  padding: 10px 16px; /* reduced padding */
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.25s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.tab-button:hover {
  background: #b4c7e7;
  color: #fff;
}

.tab-button.active {
  background: #38598b;
  color: #fff;
  box-shadow: 0 4px 12px #38598b18;
}

/* Tab Content */
.tab-content {
  display: none;
  margin: 0 28px; /* reduced side margins */
}

.tab-content.active {
  display: block;
}

/* Form Container */
.form-container {
  display: flex;
  justify-content: center;
  margin-bottom: 20px; /* reduced */
}

.form-card {
  background: #fff;
  border-radius: 14px;
  padding: 28px; /* reduced padding to fit more on screen */
  box-shadow: 0 8px 28px #38598b18;
  border: 1px solid #b4c7e7;
  width: 100%;
  max-width: 980px; /* slightly smaller max width */
}

.form-header {
  text-align: center;
  margin-bottom: 28px; /* reduced */
}

.form-title {
  font-size: 1.75rem; /* smaller title */
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
}

.form-title i {
  color: #38598b;
}

.form-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0;
  font-weight: 400;
}

/* Profile Upload */
.profile-section {
  display: flex;
  justify-content: center;
  margin-bottom: 18px; /* reduced */
}

.profile-upload-modern {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px; /* reduced gap */
}

.profile-preview-container {
  position: relative;
  width: 100px; /* slightly smaller avatar */
  height: 100px;
}

.profile-preview-container img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #e9ecef;
  box-shadow: 0 8px 20px #38598b18;
  transition: all 0.25s ease;
}

.profile-preview-container img:hover {
  transform: scale(1.03);
  box-shadow: 0 12px 30px #38598b18;
}

.profile-preview-label {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0,0,0,0.7);
  color: #fff;
  border-radius: 50%;
  font-size: 0.85rem;
  font-weight: 600;
  opacity: 0;
  transition: opacity 0.25s ease;
}

.profile-preview-container:hover .profile-preview-label {
  opacity: 1;
}

.profile-actions {
  display: flex;
  gap: 10px; /* reduced gap */
}

.upload-btn, .camera-btn-modern {
  background: #38598b;
  color: #fff;
  border: none;
  padding: 10px 16px; /* reduced padding */
  border-radius: 22px;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.25s ease;
  display: flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 4px 12px #38598b30;
}

.upload-btn:hover, .camera-btn-modern:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 18px #38598b40;
}

.upload-btn input[type="file"] {
  display: none;
}

/* Form Grid */
.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px; /* significantly reduced gap between columns/rows */
  margin-bottom: 18px; /* reduced */
}

.form-column {
  display: flex;
  flex-direction: column;
  gap: 10px; /* reduced gap between stacked fields */
}

.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 0; /* ensure no extra space */
}

.form-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: #38598b;
  margin-bottom: 6px; /* reduced label spacing */
  display: flex;
  align-items: center;
  gap: 6px;
}

.form-label i {
  color: #38598b;
  width: 16px;
}

.form-input, .form-select {
  padding: 10px 12px; /* reduced input padding */
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.25s ease;
  background: #fff;
}

.form-input:focus, .form-select:focus {
  outline: none;
  border-color: #38598b;
  box-shadow: 0 0 0 3px #b4c7e740;
}

.password-input-group {
  position: relative;
  display: flex;
  align-items: center;
}

.password-toggle {
  position: absolute;
  right: 8px; /* closer to input */
  background: none;
  border: none;
  color: #7f8c8d;
  cursor: pointer;
  font-size: 1rem;
  padding: 4px;
  border-radius: 4px;
  transition: color 0.25s ease;
}

.password-toggle:hover {
  color: #38598b;
}

.password-strength {
  margin-top: 8px;
}

.strength-bar {
  height: 4px;
  background: #e9ecef;
  border-radius: 2px;
  overflow: hidden;
  margin-bottom: 6px;
}

.strength-fill {
  height: 100%;
  width: 0%;
  transition: all 0.25s ease;
  border-radius: 2px;
}

.strength-text {
  font-size: 0.82rem;
  font-weight: 600;
}

.password-policy {
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  padding: 12px;
  margin-top: 12px;
}

.policy-title {
  font-weight: 600;
  color: #38598b;
  margin-bottom: 8px;
  font-size: 0.85rem;
}

.policy-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.policy-item {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 4px;
  font-size: 0.82rem;
}

.policy-item i {
  font-size: 0.68rem;
  transition: color 0.25s ease;
}

.policy-item.pass-ok i {
  color: #28a745;
}

.policy-item.pass-bad i {
  color: #dc3545;
}

.error-message {
  color: #dc3545;
  font-size: 0.78rem;
  margin-top: 4px;
  display: none;
}

/* Form Actions */
.form-actions {
  display: flex;
  gap: 10px; /* reduced gap */
  justify-content: center;
  margin-top: 18px; /* reduced top margin */
}

.btn-primary, .btn-secondary, .btn-danger, .btn-warning {
  padding: 10px 22px; /* reduced padding */
  border: none;
  border-radius: 22px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.25s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.btn-primary {
  background: #38598b;
  color: #fff;
  box-shadow: 0 4px 12px #38598b30;
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 18px #38598b40;
}

.btn-secondary {
  background: #6c757d;
  color: #fff;
  box-shadow: 0 4px 12px #6c757d30;
}

.btn-secondary:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 18px #6c757d40;
}

.btn-danger {
  background: linear-gradient(135deg, #dc3545, #c82333);
  color: #fff;
  box-shadow: 0 4px 12px #dc354530;
}

.btn-danger:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 18px #dc354540;
}

.btn-warning {
  background: linear-gradient(135deg, #ffc107, #e0a800);
  color: #212529;
  box-shadow: 0 4px 12px #ffc10730;
}

.btn-warning:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 18px #ffc10740;
}

/* Table Container */
.table-container {
  margin-bottom: 20px; /* reduced */
}

.table-card {
  background: #fff;
  border-radius: 14px;
  padding: 20px; /* reduced */
  box-shadow: 0 8px 28px #38598b18;
  border: 1px solid #b4c7e7;
}

.table-header {
  margin-bottom: 20px; /* reduced */
}

.table-title {
  font-size: 1.6rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 6px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.table-title i {
  color: #38598b;
}

.table-subtitle {
  font-size: 0.95rem;
  color: #7f8c8d;
  margin: 0;
  font-weight: 400;
}

/* Table Actions */
.table-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 18px; /* reduced */
  flex-wrap: wrap;
  gap: 10px; /* reduced gap */
}

.action-buttons {
  display: flex;
  gap: 8px;
}

.search-section {
  display: flex;
  align-items: center;
}

.search-input-group {
  display: flex;
  align-items: center;
  background: #fff;
  border: 2px solid #e9ecef;
  border-radius: 22px;
  overflow: hidden;
  transition: border-color 0.25s ease;
}

.search-input-group:focus-within {
  border-color: #38598b;
}

.search-input {
  border: none;
  padding: 8px 12px;
  font-size: 0.88rem;
  outline: none;
  width: 220px; /* slightly smaller */
}

.search-btn {
  background: #38598b;
  color: #fff;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  transition: background 0.25s ease;
}

.search-btn:hover {
  background: #2c406b;
}

/* Modern Table */
.table-wrapper {
  overflow-x: auto;
  border-radius: 12px;
  box-shadow: 0 4px 12px #38598b18;
}

.modern-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  font-size: 0.9rem;
}

.modern-table thead {
  background: #38598b;
  color: #fff;
}

.modern-table th {
  padding: 12px 10px; /* reduced padding */
  text-align: left;
  font-weight: 600;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.modern-table td {
  padding: 10px 10px; /* reduced padding */
  border-bottom: 1px solid #f1f3f4;
  font-size: 0.85rem;
  vertical-align: middle;
}

.modern-table tbody tr {
  transition: background 0.25s ease;
}

.modern-table tbody tr:hover {
  background: #f8f9fa;
}

.checkbox-col { width: 5%; }
.avatar-col { width: 8%; }
.name-col { width: 12%; }
.username-col { width: 12%; }
.status-col { width: 10%; }
.location-col { width: 12%; }
.date-col { width: 15%; }
.actions-col { width: 16%; }

.select-all-checkbox, .user-checkbox {
  width: 16px;
  height: 16px;
  cursor: pointer;
}

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e9ecef;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 10px; /* reduced horizontal padding */
  border-radius: 18px;
  font-size: 0.78rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-badge.active {
  background: #d4edda;
  color: #155724;
}

.status-badge.inactive {
  background: #f8d7da;
  color: #721c24;
}

.status-badge.unknown {
  background: #e2e3e5;
  color: #6c757d;
}

.action-buttons {
  display: flex;
  gap: 6px;
}

.action-btn {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 0.85rem;
  transition: all 0.25s ease;
}

.edit-btn {
  background: #007bff;
  color: #fff;
}

.edit-btn:hover {
  background: #0056b3;
  transform: scale(1.08);
}

.view-btn {
  background: #28a745;
  color: #fff;
}

.view-btn:hover {
  background: #1e7e34;
  transform: scale(1.08);
}

.no-data {
  text-align: center;
  padding: 30px; /* reduced */
  color: #6c757d;
  font-style: italic;
}

.no-data i {
  font-size: 1.6rem;
  margin-bottom: 8px;
  display: block;
}

/* Pagination */
.pagination-container {
  display: flex;
  justify-content: center;
  margin-top: 20px; /* reduced */
}

.pagination {
  display: flex;
  gap: 5px;
  background: #e6e9f2;
  padding: 6px; /* reduced */
  border-radius: 12px;
  border: 1px solid #b4c7e7;
}

.pagination-btn {
  padding: 6px 10px;
  background: transparent;
  color: #38598b;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.25s ease;
  text-decoration: none;
  font-weight: 600;
  min-width: 34px;
  text-align: center;
  font-size: 0.85rem;
}

.pagination-btn:hover {
  background: #b4c7e7;
  color: #fff;
}

.pagination-btn.active {
  background: #38598b;
  color: #fff;
}

/* Alerts */
.alert {
  padding: 12px 18px; /* reduced padding */
  border-radius: 8px;
  margin: 14px 28px; /* reduced margins */
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
}

.alert-success {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .form-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }
  
  .hero-content {
    flex-direction: column;
    text-align: center;
  }
  
  .tab-navigation, .tab-content, .alert {
    margin-left: 18px;
    margin-right: 18px;
  }
}

@media (max-width: 768px) {
  .modern-dashboard {
    margin-left: 0;
  }
  
  .hero-section {
    padding: 20px 14px 14px 14px;
    margin-bottom: 20px;
  }
  
  .hero-title {
    font-size: 1.9rem;
  }
  
  .tab-navigation, .tab-content, .alert {
    margin-left: 14px;
    margin-right: 14px;
  }
  
  .form-card {
    padding: 18px;
  }
  
  .table-actions {
    flex-direction: column;
    align-items: stretch;
    gap: 8px;
  }
  
  .search-input {
    width: 100%;
  }
  
  .modern-table {
    font-size: 0.82rem;
  }
  
  .modern-table th,
  .modern-table td {
    padding: 8px 6px;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.6rem;
  }
  
  .form-title {
    font-size: 1.25rem;
  }
  
  .profile-actions {
    flex-direction: column;
    width: 100%;
  }
  
  .upload-btn, .camera-btn-modern {
    justify-content: center;
  }

  /* Further compact for very small screens */
  .form-grid {
    gap: 8px;
  }

  .form-column {
    gap: 8px;
  }

  .form-input, .form-select {
    padding: 8px 10px;
  }

  .form-card {
    padding: 12px;
  }
}
</style>
<div class="modern-dashboard" style="margin-left:280px">
  <!-- Hero Header Section -->
  <div class="hero-section">
    <div class="hero-content">
      <div class="hero-text">
        <h1 class="hero-title">
          <i class="fa fa-user-plus"></i>
          User Management
        </h1>
        <p class="hero-subtitle">Manage system users and their access permissions</p>
      </div>
      <div class="hero-stats">
        <div class="stat-badge">
          <i class="fa fa-users"></i>
          <span>
          <?php
          $stmt = $db->query("SELECT COUNT(*) AS total_users FROM users");
          $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
            echo number_format($totalUsers) . " Total Users";
          ?>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Navigation Tabs -->
  <div class="tab-navigation">
    <div class="tab-container">
      <button class="tab-button active" onclick="openTab(event,'addUser')">
        <i class="fa fa-plus-circle"></i>
        <span>Add New User</span>
      </button>
      <button class="tab-button" onclick="openTab(event,'manageUsers')">
        <i class="fa fa-users"></i>
        <span>Manage Users</span>
      </button>
    </div>
    </div>

  <!-- Add User Section -->
  <div id="addUser" class="tab-content active">
    <div class="form-container">
      <div class="form-card">
        <div class="form-header">
          <h2 class="form-title">
            <i class="fa fa-user-plus"></i>
            Create New User
          </h2>
          <p class="form-subtitle">Fill in the form below to add a new system user</p>
        </div>

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

        <form method="POST" enctype="multipart/form-data" id="addUserForm" autocomplete="off" class="modern-form">
          <!-- Profile Photo Section -->
          <div class="form-section">
            <div class="profile-section">
              <div class="profile-upload-modern">
                <div class="profile-preview-container">
                  <img src="assets/img/default-avatar.png" id="profilePreview" alt="Profile Preview">
                  <span class="profile-preview-label" id="profilePreviewText">Profile Preview</span>
                </div>
                <div class="profile-actions">
                  <label for="profile_img" class="upload-btn" title="Upload Photo">
                  <i class="fa fa-upload"></i>
                    <span>Upload</span>
                </label>
                  <button type="button" class="camera-btn-modern" onclick="openCameraModal()" title="Take Photo">
                  <i class="fa fa-camera"></i>
                    <span>Camera</span>
                </button>
                </div>
                <input type="file" id="profile_img" name="profile_img" accept="image/*" onchange="previewProfileImage(event)">
                <input type="file" accept="image/*" capture="user" id="cameraInput" name="cameraInput" style="display:none" onchange="previewProfileImage(event)">
                <input type="hidden" id="cameraPhotoData" name="cameraPhotoData">
              </div>
            </div>
          </div>

          <!-- Form Fields in Two Columns -->
          <div class="form-grid">
            <!-- Left Column -->
            <div class="form-column">
              <div class="form-group">
                <label for="lastname" class="form-label">
                  <i class="fa fa-user"></i>
                  Last Name
                </label>
                <input type="text" id="lastname" name="lastname" class="form-input" placeholder="Doe" required maxlength="40" pattern="^[a-zA-Z\s\-\.]+$">
            </div>

              <div class="form-group">
                <label for="firstname" class="form-label">
                  <i class="fa fa-user"></i>
                  First Name
                </label>
                <input type="text" id="firstname" name="firstname" class="form-input" placeholder="John" required maxlength="40" pattern="^[a-zA-Z\s\-\.]+$">
          </div>

              <div class="form-group">
                <label for="middlename" class="form-label">
                  <i class="fa fa-user"></i>
                  Middle Name
                </label>
                <input type="text" id="middlename" name="middlename" class="form-input" placeholder="A." maxlength="40" pattern="^[a-zA-Z\s\-\.]+$">
            </div>

              <div class="form-group">
                <label for="username" class="form-label">
                  <i class="fa fa-at"></i>
                  Username
                </label>
                <input type="text" id="username" name="username" class="form-input" placeholder="johndoe" required maxlength="40" pattern="^[a-zA-Z0-9_\-\.]+$" autocomplete="off">
          </div>

              <div class="form-group">
                <label for="password" class="form-label">
                  <i class="fa fa-lock"></i>
                  Password
                </label>
                <div class="password-input-group">
                  <input type="password" id="password" name="password" class="form-input" required autocomplete="new-password">
                  <button type="button" class="password-toggle" onclick="togglePassword()">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
                <div class="password-strength">
                  <div class="strength-bar">
                    <div id="password-strength-bar" class="strength-fill"></div>
              </div>
                  <span id="password-strength-text" class="strength-text"></span>
                </div>
              <div class="password-policy" id="password-policy">
                  <div class="policy-title">Password requirements:</div>
                  <ul class="policy-list">
                    <li id="pass-length" class="policy-item">
                      <i class="fa fa-circle"></i>
                      <span>Minimum 8–12 characters</span>
                    </li>
                    <li id="pass-complex" class="policy-item">
                      <i class="fa fa-circle"></i>
                      <span>At least 3 of these: uppercase, lowercase, number, special character</span>
                    </li>
                    <li id="pass-common" class="policy-item">
                      <i class="fa fa-circle"></i>
                      <span>Not a common password</span>
                    </li>
                    <li id="pass-repeat" class="policy-item">
                      <i class="fa fa-circle"></i>
                      <span>No sequential or repeating characters</span>
                    </li>
                </ul>
              </div>
            </div>

              <div class="form-group">
                <label for="sex" class="form-label">
                  <i class="fa fa-venus-mars"></i>
                  Gender
                </label>
                <select id="sex" name="sex" class="form-select" required>
                <option value="" disabled selected>Select gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>

            <!-- Right Column -->
            <div class="form-column">
              <div class="form-group">
                <label for="province" class="form-label">
                  <i class="fa fa-map-marker"></i>
                  Province
                </label>
                <select id="province" name="province" class="form-select" required>
                <option value="" disabled selected>Select province</option>
              </select>
            </div>

              <div class="form-group">
                <label for="municipality" class="form-label">
                  <i class="fa fa-building"></i>
                  Municipality/City
                </label>
                <select id="municipality" name="municipality" class="form-select" required>
                <option value="" disabled selected>Select municipality/city</option>
              </select>
            </div>

              <div class="form-group">
                <label for="barangay" class="form-label">
                  <i class="fa fa-home"></i>
                  Barangay
                </label>
                <select id="barangay" name="barangay" class="form-select" required>
                <option value="" disabled selected>Select barangay</option>
              </select>
            </div>

              <div class="form-group">
                <label for="street_address" class="form-label">
                  <i class="fa fa-road"></i>
                  Street Address
                </label>
                <input type="text" id="street_address" name="street_address" class="form-input" placeholder="123 Main St" maxlength="120">
          </div>

              <div class="form-group">
                <label for="contact_number" class="form-label">
                  <i class="fa fa-phone"></i>
                  Contact Number
                </label>
                <input type="text" id="contact_number" name="contact_number" class="form-input" placeholder="0961 488 9820" required maxlength="13">
                <span id="contact_error" class="error-message">Format: 0961 488 9820</span>
            </div>

              <div class="form-group">
                <label for="birthday" class="form-label">
                  <i class="fa fa-calendar"></i>
                  Date of Birth
                </label>
                <input type="date" id="birthday" name="birthday" class="form-input" required onchange="calculateAge()">
          </div>

              <div class="form-group">
                <label for="age" class="form-label">
                  <i class="fa fa-birthday-cake"></i>
                  Age
                </label>
                <input type="number" id="age" name="age" class="form-input" readonly>
            </div>

              <div class="form-group">
                <label for="role" class="form-label">
                  <i class="fa fa-user-tag"></i>
                  Role
                </label>
                <select id="role" name="role" class="form-select" required>
                <option value="" disabled selected>Select role</option>
                <option value="owner">Owner</option>
                <option value="veterinarian">Veterinarian</option>
                <option value="employee">Employee</option>
              </select>
            </div>
          </div>
          </div>

          <!-- Form Actions -->
          <div class="form-actions">
            <button type="submit" name="submit" class="btn-primary">
              <i class="fa fa-save"></i>
              <span>Save User</span>
            </button>
            <button type="reset" class="btn-secondary">
              <i class="fa fa-undo"></i>
              <span>Reset</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Manage Users Section -->
  <div id="manageUsers" class="tab-content">
    <div class="table-container">
      <div class="table-card">
        <div class="table-header">
          <h2 class="table-title">
            <i class="fa fa-users"></i>
            User Management
          </h2>
          <p class="table-subtitle">Manage existing system users</p>
        </div>

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

          $address = $street_address . ', ' . $barangay . ', ' . $municipality . ', ' . $province;

          if (!isPasswordValid($password)) {
            echo '<div class="w3-panel w3-red w3-round-large w3-padding"><p><i class="fa fa-exclamation-triangle"></i> Password does not meet the policy requirements.</p></div>';
          } elseif (!validPHMobile($contact_number)) {
            echo '<div class="w3-panel w3-red w3-round-large w3-padding"><p><i class="fa fa-exclamation-triangle"></i> Invalid contact number format. Use: 0961 488 9820</p></div>';
          } elseif (!in_array($role, ['employee', 'owner', 'veterinarian'])) {
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
  // Username already exists - handle as needed
} else {
  $hashed_password = sha1($password); // Now the hash matches your example
  $stmt = $db->prepare("INSERT INTO users (first_name, middle_name, last_name, username, password, role, sex, age, province, municipality, barangay, street_address, contact_number, birthday, profile_img) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $firstname, $middlename, $lastname, $username, $hashed_password, $role, $sex, $age,
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

        <form method="POST" class="user-management-form">
          <div class="table-actions">
            <div class="action-buttons">
              <button type="submit" name="delete_selected" class="btn-danger" 
                    onclick="return confirm('Are you sure you want to delete the selected users? This action cannot be undone.')">
                <i class="fa fa-trash"></i>
                <span>Delete Selected</span>
            </button>
              <button type="submit" name="toggle_status" class="btn-warning">
                <i class="fa fa-refresh"></i>
                <span>Toggle Status</span>
            </button>
            </div>
            <div class="search-section">
              <div class="search-input-group">
                <input type="text" id="userSearch" class="search-input" placeholder="Search users...">
                <button type="button" class="search-btn">
                  <i class="fa fa-search"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="table-wrapper">
            <table class="modern-table">
             <thead>
                <tr>
                  <th class="checkbox-col">
                    <input type="checkbox" id="select-all" class="select-all-checkbox">
                  </th>
                  <th class="avatar-col">Avatar</th>
                  <th class="name-col">First Name</th>
                  <th class="name-col">Last Name</th>
                  <th class="name-col">Middle Name</th>
                  <th class="username-col">Username</th>
                  <th class="status-col">Status</th>
                  <th class="location-col">Province</th>
                  <th class="location-col">Municipality</th>
                  <th class="location-col">Barangay</th>
                  <th class="date-col">Last Login</th>
                  <th class="actions-col">Actions</th>
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
                    echo '<tr class="table-row">';
                    echo '<td class="checkbox-cell"><input type="checkbox" name="user_ids[]" value="' . $user['id'] . '" class="user-checkbox"></td>';
      $avatar = !empty($user['profile_img']) && file_exists($user['profile_img']) ? $user['profile_img'] : 'assets/img/default-avatar.png';
                    echo '<td class="avatar-cell"><img src="'.$avatar.'" alt="avatar" class="user-avatar"></td>';
                    echo '<td class="name-cell">' . htmlspecialchars($user['first_name'] ?? '') . '</td>';
                    echo '<td class="name-cell">' . htmlspecialchars($user['last_name'] ?? '') . '</td>';
                    echo '<td class="name-cell">' . htmlspecialchars($user['middle_name'] ?? '') . '</td>';
                    echo '<td class="username-cell">' . htmlspecialchars($user['username']) . '</td>';
                    echo '<td class="status-cell">';
                    if (isset($user['status'])) {
                      echo $user['status'] == 1 ? 
                           '<span class="status-badge active"><i class="fa fa-check"></i> Active</span>' : 
                           '<span class="status-badge inactive"><i class="fa fa-times"></i> Inactive</span>';
                    } else {
                      echo '<span class="status-badge unknown"><i class="fa fa-question"></i> Unknown</span>';
                    }
                    echo '</td>';
                    echo '<td class="location-cell">' . htmlspecialchars($user['province']) . '</td>';
                    echo '<td class="location-cell">' . htmlspecialchars($user['municipality']) . '</td>';
                    echo '<td class="location-cell">' . htmlspecialchars($user['barangay']) . '</td>';
                    echo '<td class="date-cell">' . (isset($user['last_login']) ? date('M d, Y h:i A', strtotime($user['last_login'])) : 'Never') . '</td>';
                    echo '<td class="actions-cell">';
                    echo '<div class="action-buttons">';
                    echo '<a href="edit_user.php?id=' . $user['id'] . '" class="action-btn edit-btn"><i class="fa fa-edit"></i></a>';
                    echo '<a href="viewuser.php?id=' . $user['id'] . '" class="action-btn view-btn"><i class="fa fa-eye"></i></a>';
                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                  }
                } else {
                  echo '<tr><td colspan="12" class="no-data"><i class="fa fa-exclamation-circle"></i> No users found</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>

          <div class="pagination-container">
            <div class="pagination">
              <?php
              $stmt = $db->query("SELECT COUNT(id) AS total FROM users");
              $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
              $pages = ceil($totalUsers / $limit);
              if ($page > 1) {
                echo '<a href="?page='.($page - 1).'#manageUsers" class="pagination-btn prev"><i class="fa fa-chevron-left"></i></a>';
              }
              for ($i = 1; $i <= $pages; $i++) {
                $active = ($i == $page) ? 'active' : '';
                echo '<a href="?page='.$i.'#manageUsers" class="pagination-btn '.$active.'">'.$i.'</a>';
              }
              if ($page < $pages) {
                echo '<a href="?page='.($page + 1).'#manageUsers" class="pagination-btn next"><i class="fa fa-chevron-right"></i></a>';
              }
              ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
        
        <?php
        if (isset($_POST['delete_selected'])) {
            if (!empty($_POST['user_ids'])) {
                $placeholders = implode(',', array_fill(0, count($_POST['user_ids']), '?'));
                $stmt = $db->prepare("DELETE FROM users WHERE id IN ($placeholders)");
                $stmt->execute($_POST['user_ids']);
        echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> Selected users deleted successfully.</div>';
                echo '<script>setTimeout(function(){ window.location.href = window.location.href + "#manageUsers"; }, 1500);</script>';
            } else {
        echo '<div class="alert alert-error"><i class="fa fa-exclamation-triangle"></i> No users selected for deletion.</div>';
            }
        }
        if (isset($_POST['toggle_status'])) {
            if (!empty($_POST['user_ids'])) {
                $placeholders = implode(',', array_fill(0, count($_POST['user_ids']), '?'));
                $stmt = $db->prepare("UPDATE users SET status = NOT status WHERE id IN ($placeholders)");
                $stmt->execute($_POST['user_ids']);
        echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> Selected users status toggled successfully.</div>';
                echo '<script>setTimeout(function(){ window.location.href = window.location.href + "#manageUsers"; }, 1500);</script>';
            } else {
        echo '<div class="alert alert-error"><i class="fa fa-exclamation-triangle"></i> No users selected for status change.</div>';
            }
        }
        ?>

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
    "Vigan City": ["Ayusan Norte", "Ayusan Sur", "Bantay", "Barraca", "Bayubay Norte", "Bayubay Sur", "Beddeng Laud", "Beddeng Daya", "Bigaa", "Bulala", "Capangpangan", "Culliat", "Kabaroan", "Mindoro", "Pantay Daya", "Pantay Laud", "Pantay Fatima", "Paoa", "Paratong", "Poblacion I", "Poblacion II", "Poblacion III", "Poblacion IV", "Poblacion V", "Poblacion VI", "Poblacion VII", "Poblacion VIII", "Pudoc", "Raois", "Salindeg", "San Jose", "San Julian Norte", "San Julian Sur", "San Pedro", "Tamag", "Tamag Norte", "Pantay Tamag", "VII-A"],
    "Candon City": ["Acat", "Bagani Campo", "Bagani Gabor", "Bagani Tocgo", "Balingaoan", "Baringcucurong", "Bonifacio", "Buenavista", "Cabisuculan", "Calingcuan", "Camindoroan", "Darapidap", "Gattaran", "Inmamoting", "Ipil", "Langgan", "Libtong", "Madalaem", "Otto", "Paras", "Patpata", "Paypayad", "Pob. I", "Pob. II", "Pob. III", "Pob. IV", "Pob. V", "Pob. VI", "Pob. VII", "Pob. VIII", "R.A. Padilla", "San Andres", "San Antonio", "San Isidro", "San Jose", "San Juan", "San Nicolas", "Santa Lucia", "Santo Tomas", "Tammocalao", "Tapat"],
    "Alilem": ["Amilongan", "An-anaao", "Apang", "Apaya", "Balidbid", "Casilagan", "Daday", "Dadaew", "Dalawa", "Kiat", "Poblacion", "Sagsagat", "Anaao"],
    "Banayoyo": ["Bagbagotot","Banbanaal","Bisangol","Cadanglaan","Casilagan Norte","Casilagan Sur","Elefante","Guardia","Lintic","Lopez","Montero","Naguimba","Pila","Poblacion"],
    "Bantay": ["Aggay", "Balaleng", "Banbanaba", "Bulala", "Bungro", "Cabaroan", "Cabusligan", "Daclapan", "Lingsat", "Lucban", "Lusong", "Mameltac", "Nagtenga", "Nansuagao", "Ora Este", "Ora Centro", "Ora West", "Piedras", "Pudoc", "Quimmarayan", "Sagpat", "Salindeg", "San Isidro", "San Julian Norte", "San Julian Sur", "San Silvestre", "San Vicente", "Santo Cristo", "Suyo", "Tamag", "Tay-ac", "Zone I", "Zone II", "Zone III", "Zone IV"],
    "Burgos": ["Balugang", "Banban", "Bobon", "Caoayan", "Dalayap", "Dalimag", "Kinmarin", "Lucaban", "Lusong", "Madapoy", "Mambug", "Poblacion Norte", "Poblacion Sur", "Sabangan", "Sagsagat", "San Antonio", "Santo Tomas", "Tangday", "Ubbog", "Upper Bitalag", "Lower Bitalag", "Pa-o", "Pideg", "Pila", "Resurreccion", "Sinabaan"],
    "Cabugao": ["Alinaay", "Baclig", "Burao", "Caellayan", "Daclapan", "Dardarat", "Kinagdanan", "Laoingen", "Lussoc", "Maradodon", "Pila", "Pug-os", "Sabang", "Salapasap", "Sallacapo", "San Agustin", "San Andres", "San Antonio", "San Isidro", "San Juan", "San Pedro", "Santo Cristo", "Santo Tomas", "Sardeng", "Subec", "Sucoc", "Turod", "Ucang", "Zapat", "Barangobong", "Garitan", "Pideg", "Namruangan"],
    "Caoayan": ["Annapolis", "Bungro", "Cabaroan", "Cadanglaan", "Callaguip", "Camanggaan", "Camindoroan", "Carusipan", "Catayagan", "Darapidap", "Inlaud", "Man-atong", "Nagsangalan", "Poblacion", "Pulu", "Surnget", "Tamurong"],
"Cervantes": ["Aluling", "Comillas North", "Comillas South", "Concepcion", "Dinwede East", "Dinwede West", "Remedios", "Malaya", "Rosario", "San Juan", "San Luis", "Taleb", "Tumalip"],
"Galimuyod": ["Abaya", "Balite", "Bato", "Bidbiday", "Calimugtong", "Calombaya", "Capangdanan", "Daldagan", "Daldalayap", "Daniw", "Daramuangan", "Labut", "Lallayug", "Laureta", "Legleg", "Mabugtot", "Magsingal", "Napo", "Nataraki", "Patac", "Sabangan", "Salvacion", "Santo Rosario", "Turod"],
    "Gregorio del Pilar": ["Alfonso (Tangaoan)","Bussot","Concepcion","Dapdappig (Mabatano)","Matue‑Butarag","Poblacion Norte","Poblacion Sur"],
    "Lidlidda": ["Banucal", "Bequi-Walin", "Bugui", "Calungbuyan", "Carcarabasa", "Labut", "Luba", "Poblacion", "San Vicente", "Suagayan", "Tambugan"],
"Magsingal": ["Alangan", "Baliw", "Bato", "Baybayabas", "Bellang", "Bulbulala", "Bungro", "Buratpat", "Butubut Norte", "Butubut Sur", "Cadanglaan", "Caduangdaan", "Dardarat", "Labut", "Maratudo", "Mira", "Naglaoa-an", "Namnama", "Napo", "Pagsanaan Norte", "Pagsanaan Sur", "Panay Norte", "Panay Sur", "Patong", "Poblacion", "Puccao", "San Basilio", "San Clemente", "San Juan", "San Ramon"],
"Nagbukel": ["Banqued", "Baracbac", "Bungro", "Caellayan", "Cambaly", "Kinpatubbog", "Lucban", "Nagbukel", "Patiacan", "Poblacion", "San Isidro", "Turod"],
"Narvacan": ["Aggay", "Bagani Campo", "Bagani Gabor", "Banglayan", "Bessang", "Biag", "Bugas", "Bulanos", "Cabarita", "Cadanglaan", "Calawaan", "Campo", "Dasay", "Estancia", "Lalong", "Lanipao", "Legaspi", "Marzan", "Nagpanaoan", "Nagrebcan", "Orence", "Padut", "Panday", "Paratong", "Paria", "Poblacion I", "Poblacion II", "Quinarayan", "San Antonio", "San Jose", "San Pedro", "Santa Lucia", "Suso", "Tabucbuc"],
"Quirino": ["Banoen", "Basa", "Cadanglaan", "Culdalig", "Lam-ag", "Legleg", "Malideg", "Namitpit", "Patiacan"],
    "Salcedo": ["Atabay", "Baluarte", "Baybayading", "Cabisilan", "Calangcuasan", "Calomot", "Calungbuyan", "Daniw", "Kinmarin", "Lucbuban", "Maligaya", "Manzante", "Poblacion Norte", "Poblacion Sur", "Sabel", "Sabangan", "San Isidro", "Sasaba", "Suerte", "Taleb", "Tubbeng"],
"San Emilio": ["Balioeg", "Bida", "Cadanglaan", "Kalumsing", "Lancuas", "Matibuey", "Paltoc", "San Miliano"],
"San Esteban": ["Ammatong", "Apanay", "Batu", "Bitalag", "Cabangtalan", "Capaquian", "Caroan", "Casilagan", "San Pablo", "Turod"],
"San Ildefonso": ["Bungro", "Busiing Norte", "Busiing Sur", "Cabaritan", "Camanggaan", "Casilagan", "Gabon", "Gana", "Mabugbug", "Poblacion", "Rang-ay", "Rizal", "Santa Lucia", "Santo Domingo", "Talingaan"],
"San Juan": ["Abaccan", "Aguing", "Bimmanga", "Camanggaan", "Camindoroan", "Casiber", "Dardarat", "Guimod", "Immayos Norte", "Immayos Sur", "Lam-ag", "Libtong", "Lubong", "Nagsupot", "Namruangan", "Napu", "Naria", "Narra", "Pagsanaan", "Pantay Laua-an", "Pantay Tamorong", "Pudoc", "Puro", "Quimmarayan", "Rang-ay", "Sabangan Pinggan", "Sabangan Vicente", "Salapasap", "San Isidro", "Santo Cristo", "Sarmiento", "Subadi Norte", "Subadi Sur"],
    "San Vicente": ["Bayubay Norte", "Bayubay Sur", "Lubong", "Poblacion", "San Sebastian", "Sapang", "Tamag"],
"Santa": ["Banaoang", "Calungbuyan", "Camposanto", "Darapidap", "Langaoan", "Mabilbila Norte", "Mabilbila Sur", "Maruray", "Oribi", "Panay", "Poblacion Norte", "Poblacion Sur", "Quinarayan", "Rancho", "Rimos I", "Rimos II", "Rimos III", "Rimos IV", "Rimos V", "Rimos VI", "Sabangan", "Sublangon", "Tabucolan", "Tamac", "Tubigay", "Villa Hermosa"],
"Santa Catalina": ["Balingaoan", "Bangculabo", "Cabittaogan", "Caburao", "Cabalitocan", "San Rafael", "Sinabaan", "Suyo", "Tamorong"],
"Santa Cruz": ["Ambasador", "Annapolis", "Bangcusay", "Basil", "Batang", "Bayabas", "Besalan", "Bisangol", "Bulbulala", "Burgos", "Caaoacan", "Cadanglaan", "Camanggaan", "Canaam", "Catayagan", "Comcomlo-Ong", "Daligan", "Dasay", "Dardarat", "Gabao", "Lalong", "Langaoan", "Laslasong Norte", "Laslasong Sur", "Lawang", "Libtong", "Lingsat", "Macatcatud", "Nagrebcan", "Nagpandayan", "Nagteng", "Namatting", "Nanerman", "Pantay", "Patac", "Pilar", "Poblacion East", "Poblacion North", "Poblacion South", "Poblacion West", "Rizal", "San Antonio", "San Isidro", "San Pedro", "Sibul", "Suyo", "Tamorong", "Tubtuba"],
"Santa Lucia": ["Arangin", "Banoen", "Bani", "Barangobong", "Bayubay", "Buliclic", "Cabaroan", "Cabugao", "Casilagan", "Conconig East", "Conconig West", "Damacuag", "Damacuag East", "Damacuag West", "Langaoan", "Laslasong", "Lipay", "Nagpanaoan", "Nagrebcan", "Nangalisan", "Panay", "Paoc Norte", "Paoc Sur", "Pideg", "Poblacion Norte", "Poblacion Sur", "San Cristobal", "San Pedro", "San Vicente", "Santa Ana", "Santa Maria", "Simbaan", "Suyo", "Tamurong", "Tucuc"],
    "Santa Maria": ["Ag‑agrao","Ampuagan","Baballasioan","Baliw Daya (San Gelacio)","Baliw Laud (Simbuok)","Bia‑o","Butir","Cabaroan","Danuman East","Danuman West","Dunglayan","Gusing","Langaoan","Laslasong Norte","Laslasong Sur","Laslasong West","Lesseb","Lingsat","Lubong","Maynganay Norte","Maynganay Sur (San Ignacio)","Nagsayaoan","Nagtupacan","Nalvo","Pacang","Penned","Poblacion Norte (San Gregorio)","Poblacion Sur (San Francisco)","Silag","Sumagui","Suso","Tangaoan","Tinaan"],
    "Santiago": ["Ambucao", "Baybayabas", "Bimmanga", "Bulbulala", "Cadanglaan", "Cabayogan", "Calaoa-an", "Capan-ayan", "Guimod", "Immayos Norte", "Immayos Sur", "Lubong", "Nagsupot", "Namruangan", "Napu", "Pangada", "Poblacion Norte", "Poblacion Sur", "Puro", "Sabangan", "Sabangan Pinggan", "San Antonio", "San Roque", "Santa Rosa"],
"Santo Domingo": ["Bungsot", "Cabittaogan", "Calay-ab", "Calioet-Libong", "Candarabat", "Fugu", "Lagatit", "Lussoc", "Nalasin", "Naglaoa-an", "Nagpanaoan", "Paguraper", "Pila", "Poblacion", "Pudoc", "Quimmarayan", "Reppaac", "Sabangan Pinggan", "Sabangan", "Salapasap", "San Juan", "San Pascual", "Sanna", "Suso", "Tammocalao", "Turod", "Ubbog", "Zapat", "Zone I", "Zone II", "Zone III", "Zone IV", "Zone V", "Zone VI"],
"Sigay": ["Mabileg", "Matallucod", "Pangot", "Pangotan", "Patiacan", "San Elias", "Suyo"],
"Sinait": ["Aggub", "Balaoi", "Baracbac", "Bimmanga", "Binacud", "Cadanglaan", "Cabarambanan", "Calanutian", "Camanggaan", "Canaan", "Caoayan", "Katipunan", "Duyay-yat", "Guimod", "Masadag", "Nagcullooban", "Nagbalioartian", "Nagongburan", "Namagbagan", "Nalasin", "Namnama", "Nasangayan", "Paduguran", "Paratong", "Patpata", "Pila", "Pudoc", "Purag", "Quinarayan", "Rang-ay", "Ricudo", "Rizal", "Sabangan", "San Isidro", "San Jose", "San Nicolas", "Santa Cruz", "Santo Domingo", "Santo Tomas", "Sarnap", "Sinapangan", "Turod", "Zapat"],
"Sugpon": ["Anongan", "Banga", "Banucal", "Licungan", "Madallam", "Manzante", "Poblacion", "Silet"],
    "Suyo": ["Baringcucurong", "Bulunao", "Cabugao", "Cabaritan", "Man-atong", "Patoc-ao", "Pili", "Poblacion", "Sasaba", "Suagayan", "Uso"],
"Tagudin": ["Abaca", "Ambalayat", "Bacani", "Balbalayang", "Baracbac", "Bitalag", "Cabulanglangan", "Dardarat", "Del Pilar", "Garitan", "Guerrero", "Iboy", "Lantag", "Lettac Norte", "Lettac Sur", "Lubing", "Lusong", "Magsaysay", "Malacañang Norte", "Malacañang Sur", "Pacac", "Pangpangdan", "Pula", "Puspus", "Quirino", "Rizal", "San Antonio", "San Eugenio", "San Isidro", "San Jose", "San Miguel", "Sapang", "Siksiklat", "Socio", "Suso", "Tablac", "Tampugo", "Tandoc", "Tapat", "Tarangotong", "Tococ East", "Tococ West", "Turod", "Zapat"]

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