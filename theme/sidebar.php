<?php
// --- Load the profile image for the currently logged-in user ---
$sidebar_avatar = 'assets/img/default-avatar.png';
if (isset($_SESSION['user'])) {
  // Make sure your users table has a profile_img column
  try {
    // Ensure $db is available (it should be, included in system.php or similar)
    $stmt = $db->prepare("SELECT profile_img FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['user']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['profile_img']) && file_exists($row['profile_img'])) {
      $sidebar_avatar = $row['profile_img'];
    }
  } catch (Exception $e) {
    // fallback to default avatar
  }
}
?>

<!-- Add this in your <head> section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Sidebar/menu -->
<nav class="sidebar" id="mySidebar">
  <div class="user-profile">
    <div class="avatar-container">
      <img src="<?php echo htmlspecialchars($sidebar_avatar); ?>" class="avatar" alt="User avatar">
    </div>
    <div class="user-info-settings">
      <div class="user-info">
        <span class="welcome">Welcome back,</span>
        <span class="username"><strong><?php echo ucwords($_SESSION['user']); ?></strong></span>
      </div>
      <a href="settings.php" class="settings-btn" title="Settings">
        <i class="fas fa-cog"></i>
      </a>
    </div>
  </div>
  
  <div class="sidebar-menu">
    <div class="menu-section">
      <h5 class="menu-title">MAIN MENU</h5>
      <ul class="menu-items">
        <!-- Common link for all roles -->
        <li>
          <a href="dashboard.php" class="menu-link">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <?php if ($_SESSION['role'] == 'owner' || $_SESSION['role'] == 'owner'): ?>
          <!-- Admin/Owner Specific Links -->
          <!--<li>
            <a href="manage-pig.php" class="menu-link">
              <i class="fas fa-piggy-bank"></i>
              <span>Manage Pigs</span>
            </a>
          </li> -->
          <li>
            <a href="manage-breed.php" class="menu-link">
              <i class="fas fa-dna"></i>
              <span>Manage Breeds</span>
            </a>
          </li>
          <li>
            <a href="quarantine_dashboard.php" class="menu-link">
              <i class="fas fa-procedures"></i>
              <span>Quarantine</span>
            </a>
          </li>
          <li>
            <a href="feeds_dashboard.php" class="menu-link">
              <i class="fas fa-utensils"></i>
              <span>Feed & Supplies</span>
            </a>
          </li>
          <li>
            <a href="Pregnancy-and-sow-gilts-record.php" class="menu-link">
              <i class="fas fa-female"></i>
              <span>Sow/Gilts Records</span>
            </a>
          </li>
          <li>
            <a href="calendar-notifications.php" class="menu-link">
              <i class="far fa-calendar-alt"></i>
              <span>Calendar</span>
            </a>
          </li>
          <li>
            <a href="Monitor-Sensor.php" class="menu-link">
              <i class="fas fa-microchip"></i>
              <span>Sensor Monitoring</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($_SESSION['role'] == 'admin'): ?>
          <!-- Admin Only Links -->
          <li>
            <a href="add-users.php" class="menu-link">
              <i class="fas fa-user-plus"></i>
              <span>User Management</span>
            </a>
          </li>
           <li>
            <a href="audit_logs.php" class="menu-link">
              <i class="fas fa-clipboard"></i>
              <span>Audit Logs</span>
            </a>
          </li>
             <!--<li>
            <a href="manage-pig.php" class="menu-link">
              <i class="fas fa-piggy-bank"></i>
              <span>Manage Pigs</span>
            </a>
          </li> -->
          <li>
            <a href="pig_batches.php" class="menu-link">
              <i class="fas fa-piggy-bank"></i>
              <span>Manage Pigs Batch</span>
            </a>
          </li>
          <li>
            <a href="pig-batch-history.php" class="menu-link">
              <i class="fas fa-piggy-bank"></i>
              <span>Manage Pigs Batch History</span>
            </a>
          </li>
         
          <li>
            <a href="manage-breed.php" class="menu-link">
              <i class="fas fa-dna"></i>
              <span>Manage Breeds</span>
            </a>
          </li>
          <li>
            <a href="quarantine_dashboard.php" class="menu-link">
              <i class="fas fa-procedures"></i>
              <span>Quarantine</span>
            </a>
          </li>
          <li>
            <a href="feeds_dashboard.php" class="menu-link">
              <i class="fas fa-utensils"></i>
              <span>Feed & Supplies</span>
            </a>
          </li>
          <li>
            <a href="Pregnancy-and-sow-gilts-record.php" class="menu-link">
              <i class="fas fa-female"></i>
              <span>Sow/Gilts Records</span>
            </a>
          </li>
          <li>
            <a href="calendar-notifications.php" class="menu-link">
              <i class="far fa-calendar-alt"></i>
              <span>Calendar</span>
            </a>
          </li>
          
          <li>
            <a href="Monitor-Sensor.php" class="menu-link">
              <i class="fas fa-microchip"></i>
              <span>Sensor Monitoring</span>
            </a>
          </li>
          <li>
            <a href="draw_floorplan.php" class="menu-link">
              <i class="fas fa-pen"></i>
              <span>Floor Plan Drawing</span>
            </a>
          </li>
          <!-- Settings removed here, now in user profile -->
        <?php endif; ?>

        <?php if ($_SESSION['role'] == 'employee'): ?>
          <!-- Employee Specific Links -->
             <li>
            <a href="pig_batches.php" class="menu-link">
              <i class="fas fa-piggy-bank"></i>
              <span>Manage Pigs Batch</span>
            </a>
          </li>
          
            <!--<li>
            <a href="manage-pig.php" class="menu-link">
              <i class="fas fa-piggy-bank"></i>
              <span>Manage Pigs</span>
            </a>
          </li> -->
             <li>
            <a href="feeds_dashboard.php" class="menu-link">
              <i class="fas fa-utensils"></i>
              <span>Feed & Supplies</span>
            </a>
          </li>
          <li>
            <a href="Pregnancy-and-sow-gilts-record.php" class="menu-link">
              <i class="fas fa-female"></i>
              <span>Sow/Gilts Records</span>
            </a>
          </li>
          <li>
            <a href="calendar-notifications.php" class="menu-link">
              <i class="far fa-calendar-alt"></i>
              <span>Calendar</span>
            </a>
          </li>
          <li>
            <a href="Monitor-Sensor.php" class="menu-link">
              <i class="fas fa-microchip"></i>
              <span>Sensor Monitoring</span>
            </a>
          </li>
        <?php elseif ($_SESSION['role'] == 'veterinarian'): ?>
          <!-- Veterinarian Specific Links -->
             <li>
            <a href="pig_batches.php" class="menu-link">
              <i class="fas fa-piggy-bank"></i>
              <span>Manage Pigs Batch</span>
            </a>
          </li>
          
          <li>
            <a href="quarantine_dashboard.php" class="menu-link">
              <i class="fas fa-procedures"></i>
              <span>Quarantine</span>
            </a>
          </li>
          <li>
            <a href="calendar-notifications.php" class="menu-link">
              <i class="far fa-calendar-alt"></i>
              <span>Calendar</span>
            </a>
          </li>
          <li>
            <a href="Pregnancy-and-sow-gilts-record.php" class="menu-link">
              <i class="fas fa-female"></i>
              <span>Sow/Gilts Records</span>
            </a>
          </li>
          <li>
            <a href="Monitor-Sensor.php" class="menu-link">
              <i class="fas fa-microchip"></i>
              <span>Sensor Monitorings</span>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
  
  <div class="sidebar-footer">
    <a href="logout.php" class="logout-link">
      <i class="fas fa-sign-out-alt"></i>
      <span>Log out</span>
    </a>
  </div>
</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="sidebar-overlay" onclick="w3_close()" title="close side menu" id="myOverlay"></div>

<style>
  :root {
    --primary-color: #4a6fa5;
    --secondary-color: #6c8fc7;
    --accent-color: #ff7e5f;
    --text-color: #333;
    --light-text: #f8f9fa;
    --sidebar-width: 280px;
    --sidebar-bg: #222d3b;
    --menu-item-hover: #34495e;
    --menu-item-active: #4a6fa5;
    --transition-speed: 0.3s;
    --settings-hover: #ff7e5f;
  }


  .menu-toggle {
    background: none;
    border: none;
    color: white;
    font-size: 1.3rem;
    cursor: pointer;
    padding: 6px;
    border-radius: 5px;
    transition: background var(--transition-speed);
  }

  .menu-toggle:hover {
    background: rgba(255,255,255,0.1);
    color: var(--accent-color);
  }

  .logo {
    font-weight: bold;
    font-size: 1.15rem;
    letter-spacing: 1px;
  }

  /* Sidebar main */
  .sidebar {
    background: var(--sidebar-bg);
    color: white;
    width: var(--sidebar-width);
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    transform: translateX(0);
    transition: transform var(--transition-speed);
    z-index: 1000;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 10px rgba(0,0,0,0.08);
  }

  .user-profile {
    padding: 22px 22px 17px 22px;
    display: flex;
    align-items: center;
    border-bottom: 1.5px solid rgba(255,255,255,0.09);
    background: linear-gradient(90deg, var(--primary-color) 0%, var(--sidebar-bg) 100%);
  }

  .avatar-container {
    margin-right: 14px;
    display: flex;
    align-items: center;
  }

  .avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2.5px solid var(--accent-color);
    box-shadow: 0 2px 7px rgba(0,0,0,0.10);
  }

  .user-info-settings {
    display: flex;
    align-items: center;
    flex: 1;
    justify-content: space-between;
    min-width: 0;
  }

  .user-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
  }

  .welcome {
    font-size: 0.8rem;
    opacity: 0.8;
    margin-bottom: 2px;
    color: var(--light-text);
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
  }

  .username {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
  }

  .settings-btn {
    background: none;
    border: none;
    color: var(--light-text);
    margin-left: 17px;
    font-size: 1.4rem;
    transition: color var(--transition-speed), background var(--transition-speed);
    display: flex;
    align-items: center;
    border-radius: 50%;
    padding: 8px;
    outline: none;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08);
  }

  .settings-btn:hover, .settings-btn:focus {
    color: var(--settings-hover);
    background: rgba(255,255,255,0.06);
  }

  /* Menu section */
  .sidebar-menu {
    flex: 1;
    overflow-y: auto;
    padding: 12px 0 0 0;
  }

  .menu-section {
    margin-bottom: 22px;
  }

  .menu-title {
    color: rgba(255,255,255,0.65);
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    padding: 0 24px 12px;
    margin: 0;
    font-weight: 500;
  }

  .menu-items {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .menu-link {
    display: flex;
    align-items: center;
    padding: 11px 24px;
    color: rgba(255,255,255,0.87);
    text-decoration: none;
    transition: all var(--transition-speed);
    border-left: 3.5px solid transparent;
    font-size: 1rem;
    border-radius: 0 20px 20px 0;
    margin-bottom: 2px;
    font-weight: 500;
  }

  .menu-link:hover {
    background: linear-gradient(90deg, var(--menu-item-hover) 0%, rgba(74,111,165,0.12) 100%);
    color: var(--accent-color);
    border-left-color: var(--accent-color);
    box-shadow: 0 2px 8px -4px rgba(255,126,95,0.09);
  }

  .menu-link i {
    margin-right: 13px;
    width: 22px;
    text-align: center;
    font-size: 1.12rem;
  }

  .menu-link.active {
    background-color: var(--menu-item-active);
    color: #fff;
    border-left-color: var(--accent-color);
    box-shadow: 0 4px 20px -6px var(--accent-color);
  }

  /* Sidebar footer */
  .sidebar-footer {
    padding: 18px 24px;
    border-top: 1.5px solid rgba(255,255,255,0.09);
    background: rgba(44,62,80,0.96);
  }

  .logout-link {
    display: flex;
    align-items: center;
    color: rgba(255,255,255,0.82);
    text-decoration: none;
    transition: color var(--transition-speed);
    font-size: 1.02rem;
    font-weight: 500;
    letter-spacing: 0.2px;
  }

  .logout-link:hover {
    color: var(--accent-color);
  }

  .logout-link i {
    margin-right: 11px;
    font-size: 1.18rem;
  }

  /* Overlay */
  .sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
    display: none;
    cursor: pointer;
  }

  /* Active state management */
  .menu-link[href="<?php echo basename($_SERVER['PHP_SELF']); ?>"] {
    background-color: var(--menu-item-active);
    color: #fff;
    border-left-color: var(--accent-color);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .sidebar {
      transform: translateX(-100%);
    }
    
    .sidebar-header {
      display: flex;
    }
    
    .sidebar-overlay.active {
      display: block;
    }
    
    .sidebar.active {
      transform: translateX(0);
    }
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Toggle sidebar on mobile
    window.w3_open = function() {
      document.getElementById("mySidebar").classList.add("active");
      document.getElementById("myOverlay").classList.add("active");
    }
    window.w3_close = function() {
      document.getElementById("mySidebar").classList.remove("active");
      document.getElementById("myOverlay").classList.remove("active");
    }

    // Set active menu item based on current page
    const currentPage = window.location.pathname.split('/').pop();
    const menuLinks = document.querySelectorAll('.menu-link');
    
    menuLinks.forEach(link => {
      const linkPage = link.getAttribute('href');
      if (linkPage === currentPage) {
        link.classList.add('active');
      }
      
      link.addEventListener('click', function() {
        menuLinks.forEach(item => item.classList.remove('active'));
        this.classList.add('active');
      });
    });
  });
</script>