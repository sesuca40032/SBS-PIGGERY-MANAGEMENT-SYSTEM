<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Fetch all sow/gilt records
$all_sows_gilts = $db->query("SELECT * FROM sow_gilt_records ORDER BY id DESC");
$fetch = $all_sows_gilts->fetchAll(PDO::FETCH_OBJ);

// Create an array of dates with notifications (80, 90, or 100 gestation days)
$notification_dates = [];
$upcoming_notifications = [];
$today = new DateTime();

foreach ($fetch as $data) {
    $current_date = new DateTime();
    $labor_date = new DateTime($data->labor_date);
    $interval = $current_date->diff($labor_date);
    $gestation_days = $interval->days;

    if (in_array($gestation_days, [80, 90, 100])) {
        // Store labor dates with notifications
        $notification_dates[] = $data->labor_date; // Store dates as YYYY-MM-DD
        
        // Store upcoming notifications for stats
        if ($labor_date >= $today) {
            $upcoming_notifications[] = [
                'date' => $data->labor_date,
                'gestation_days' => $gestation_days,
                'sow_id' => $data->id
            ];
        }
    }
}

// Get current month and year from the URL, or default to current month/year
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Handle month and year rollover for January and December navigation
if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

// Get the first day of the month
$firstDayOfMonth = new DateTime("$year-$month-01");
$firstDayOfWeek = $firstDayOfMonth->format('w'); // Get the first day of the week (0-6, 0 = Sunday)
$lastDayOfMonth = new DateTime("$year-$month-01");
$lastDayOfMonth->modify('last day of this month'); // Get last day of the month
$lastDay = $lastDayOfMonth->format('d'); // Last day of the month

// Calculate statistics
$total_notifications = count($notification_dates);
$upcoming_count = count($upcoming_notifications);
$this_month_count = 0;
$next_month_count = 0;

foreach ($upcoming_notifications as $notif) {
    $notif_date = new DateTime($notif['date']);
    if ($notif_date->format('Y-m') == "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT)) {
        $this_month_count++;
    } elseif ($notif_date->format('Y-m') == date('Y-m', strtotime("$year-$month-01 +1 month"))) {
        $next_month_count++;
    }
}
?>

<!-- Modern Calendar Notifications Dashboard -->
<div class="modern-dashboard" style="margin-left:280px">
    <!-- Hero Header Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <i class="fa fa-calendar"></i>
                    Pregnancy Notifications Calendar
                </h1>
                <p class="hero-subtitle">Track pregnancy milestones and important dates for sow and gilt management</p>
            </div>
            <div class="hero-stats">
                <div class="stat-badge">
                    <i class="fa fa-calendar"></i>
                    <span><?php echo date('F j, Y'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card primary-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-bell"></i>
                </div>
                <div class="card-title">Total Notifications</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($total_notifications); ?></div>
                <div class="card-subtitle">Active pregnancy alerts</div>
            </div>
        </div>

        <div class="stat-card success-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-clock"></i>
                </div>
                <div class="card-title">Upcoming</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($upcoming_count); ?></div>
                <div class="card-subtitle">Future notifications</div>
            </div>
        </div>

        <div class="stat-card info-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-calendar-check"></i>
                </div>
                <div class="card-title">This Month</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($this_month_count); ?></div>
                <div class="card-subtitle">Notifications in <?php echo date('F', strtotime("$year-$month-01")); ?></div>
            </div>
        </div>

        <div class="stat-card warning-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-arrow-right"></i>
                </div>
                <div class="card-title">Next Month</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($next_month_count); ?></div>
                <div class="card-subtitle">Notifications in <?php echo date('F', strtotime("$year-$month-01 +1 month")); ?></div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="content-container">
        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-calendar-alt"></i>
                    <?php echo date('F Y', strtotime("$year-$month-01")); ?>
                </h2>
                <p class="card-subtitle">Pregnancy milestone calendar with 80, 90, and 100-day gestation alerts</p>
            </div>

            <!-- Calendar Navigation -->
            <div class="calendar-navigation">
                <a href="calendar-notifications.php?month=<?php echo $month - 1; ?>&year=<?php echo $year; ?>" class="nav-btn nav-prev">
                    <i class="fa fa-chevron-left"></i>
                    <span>Previous Month</span>
                </a>
                <div class="current-month">
                    <h3><?php echo date('F Y', strtotime("$year-$month-01")); ?></h3>
                </div>
                <a href="calendar-notifications.php?month=<?php echo $month + 1; ?>&year=<?php echo $year; ?>" class="nav-btn nav-next">
                    <span>Next Month</span>
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>

        <!-- Calendar Display -->
            <div class="calendar-container">
        <div id="calendar"></div>
            </div>

            <!-- Legend -->
            <div class="calendar-legend">
                <div class="legend-item">
                    <div class="legend-color notification-80"></div>
                    <span>80 Days Gestation</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color notification-90"></div>
                    <span>90 Days Gestation</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color notification-100"></div>
                    <span>100 Days Gestation</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationDates = <?php echo json_encode($notification_dates); ?>;
        const upcomingNotifications = <?php echo json_encode($upcoming_notifications); ?>;

        const calendarElement = document.getElementById('calendar');
        
        // Get month and year from PHP
        const currentMonth = <?php echo $month; ?>;
        const currentYear = <?php echo $year; ?>;
        
        function generateCalendar(year, month) {
            const firstDay = new Date(year, month - 1, 1);
            const lastDate = new Date(year, month, 0).getDate();
            const firstDayOfWeek = firstDay.getDay();
            
            let calendarHTML = '<div class="modern-calendar">';
            calendarHTML += '<div class="calendar-header">';
            ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(day => {
                calendarHTML += `<div class="day-header">${day}</div>`;
            });
            calendarHTML += '</div>';
            calendarHTML += '<div class="calendar-body">';
            
            let date = 1;
            for (let i = 0; i < 6; i++) {
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDayOfWeek) {
                        calendarHTML += '<div class="calendar-day empty"></div>';
                    } else if (date <= lastDate) {
                        const currentDate = new Date(year, month - 1, date);
                        const dateStr = currentDate.toISOString().split('T')[0]; // Format as YYYY-MM-DD
                        const isNotification = notificationDates.includes(dateStr);
                        const isToday = currentDate.toDateString() === new Date().toDateString();
                        
                        // Find notification details
                        let notificationInfo = '';
                        if (isNotification) {
                            const notif = upcomingNotifications.find(n => n.date === dateStr);
                            if (notif) {
                                notificationInfo = `<div class="notification-badge notification-${notif.gestation_days}">${notif.gestation_days}d</div>`;
                            }
                        }
                        
                        const dayClass = `calendar-day ${isNotification ? 'has-notification' : ''} ${isToday ? 'today' : ''}`;
                        calendarHTML += `<div class="${dayClass}">
                            <div class="day-number">${date}</div>
                            ${notificationInfo}
                        </div>`;
                        date++;
                    } else {
                        calendarHTML += '<div class="calendar-day empty"></div>';
                    }
                }
                if (date > lastDate) break;
            }
            calendarHTML += '</div></div>';
            
            calendarElement.innerHTML = calendarHTML;
        }

        generateCalendar(currentYear, currentMonth);
    });
</script>

<style>
/* Modern Calendar Notifications Dashboard - Blue Theme */
.modern-dashboard {
  font-family: 'Segoe UI', 'Roboto', 'Inter', Arial, sans-serif;
  min-height: 100vh;
  background: #f7f8fa;
  padding: 0 0 40px 0;
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
  border-radius: 0 0 18px 18px;
  margin-bottom: 34px;
  padding: 30px 38px 20px 38px;
  box-shadow: 0 4px 24px -10px #38598b40;
  border: none;
}

.hero-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
}

.hero-title {
  font-size: 2.2rem;
  font-weight: 800;
  color: #fff;
  margin: 0;
  text-shadow: 0 2px 10px rgba(56,89,139,0.13);
  letter-spacing: -0.5px;
}

.hero-title i {
  margin-right: 15px;
  color: #ffd700;
}

.hero-subtitle {
  font-size: 1.1rem;
  color: #e6e9f2;
  margin: 10px 0 0 0;
  font-weight: 400;
}

.hero-stats {
  display: flex;
  align-items: center;
}

.stat-badge {
  background: #fff;
  color: #38598b;
  font-weight: 700;
  font-size: 1.06rem;
  border-radius: 20px;
  padding: 11px 22px;
  box-shadow: 0 2px 8px -2px #00000018;
  border: none;
}

.stat-badge i {
  margin-right: 10px;
  color: #38598b;
}

/* Statistics Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  margin: 0 50px 50px 50px;
}

.stat-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: #38598b;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px #38598b28;
}

.primary-card::before { background: #38598b; }
.success-card::before { background: #28a745; }
.info-card::before { background: #2c406b; }
.warning-card::before { background: #b4c7e7; }

.primary-card .card-icon { background: #38598b; }
.success-card .card-icon { background: #28a745; }
.info-card .card-icon { background: #2c406b; }
.warning-card .card-icon { background: #b4c7e7; color: #38598b; }

.card-header {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.card-icon {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
  font-size: 1.8rem;
  color: #fff;
  box-shadow: 0 4px 15px #38598b18;
}

.card-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #38598b;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.card-body {
  position: relative;
}

.main-stat {
  font-size: 3rem;
  font-weight: 800;
  color: #38598b;
  margin-bottom: 10px;
  line-height: 1;
}

.card-subtitle {
  font-size: 0.95rem;
  color: #7f8c8d;
  font-weight: 500;
}

/* Content Container */
.content-container {
  margin: 0 50px 30px 50px;
}

.content-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
}

.card-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.card-title i {
  color: #38598b;
}

.card-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0 0 30px 0;
  font-weight: 400;
}

/* Calendar Navigation */
.calendar-navigation {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 30px;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 15px;
  border: 1px solid #e8f5e8;
}

.nav-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  background: #38598b;
  color: #fff;
  text-decoration: none;
  border-radius: 10px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px #38598b30;
}

.nav-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px #38598b40;
  color: #fff;
  text-decoration: none;
}

.current-month h3 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #2c3e50;
  margin: 0;
}

/* Calendar Container */
.calendar-container {
  margin-bottom: 30px;
}

.modern-calendar {
  background: #fff;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 15px #38598b18;
}

.calendar-header {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  background: #38598b;
  color: #fff;
}

.day-header {
  padding: 15px 10px;
  text-align: center;
  font-weight: 600;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.calendar-body {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 1px;
  background: #e8f5e8;
}

.calendar-day {
  background: #fff;
  min-height: 80px;
  padding: 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  position: relative;
  transition: all 0.3s ease;
  border: 1px solid #f1f3f4;
}

.calendar-day:hover {
  background: #f8f9fa;
}

.calendar-day.empty {
  background: #f8f9fa;
  border: none;
}

.calendar-day.today {
  background: #e8f5e8;
  border: 2px solid #38598b;
}

.calendar-day.has-notification {
  background: #fff3e0;
  border: 2px solid #ff9800;
}

.day-number {
  font-weight: 600;
  color: #2c3e50;
  font-size: 1rem;
  margin-bottom: 5px;
}

.notification-badge {
  position: absolute;
  top: 5px;
  right: 5px;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.7rem;
  font-weight: 700;
  color: #fff;
}

.notification-80 {
  background: #ff9800;
}

.notification-90 {
  background: #f44336;
}

.notification-100 {
  background: #9c27b0;
}

/* Calendar Legend */
.calendar-legend {
  display: flex;
  justify-content: center;
  gap: 30px;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 15px;
  border: 1px solid #e8f5e8;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 500;
  color: #2c3e50;
}

.legend-color {
  width: 20px;
  height: 20px;
  border-radius: 50%;
}

.notification-80 {
  background: #ff9800;
}

.notification-90 {
  background: #f44336;
}

.notification-100 {
  background: #9c27b0;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 0 30px 40px 30px;
  }
  
  .hero-content {
    flex-direction: column;
    text-align: center;
  }
  
  .content-container {
    margin-left: 30px;
    margin-right: 30px;
  }
}

@media (max-width: 768px) {
  .modern-dashboard {
    margin-left: 0;
  }
  
  .hero-section {
    padding: 30px 20px 20px 20px;
    margin-bottom: 30px;
  }
  
  .hero-title {
    font-size: 2.2rem;
  }
  
  .stats-grid {
    margin: 0 20px 30px 20px;
    grid-template-columns: 1fr;
  }
  
  .content-container {
    margin-left: 20px;
    margin-right: 20px;
  }
  
  .content-card {
    padding: 20px;
  }
  
  .calendar-navigation {
    flex-direction: column;
    gap: 15px;
  }
  
  .nav-btn {
    width: 100%;
    justify-content: center;
  }
  
  .calendar-legend {
    flex-direction: column;
    gap: 15px;
    align-items: center;
  }
  
  .calendar-day {
    min-height: 60px;
    padding: 5px;
  }
  
  .day-number {
    font-size: 0.9rem;
  }
  
  .notification-badge {
    width: 16px;
    height: 16px;
    font-size: 0.6rem;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.8rem;
  }
  
  .main-stat {
    font-size: 2.5rem;
  }
  
  .calendar-day {
    min-height: 50px;
  }
  
  .day-header {
    padding: 10px 5px;
    font-size: 0.8rem;
  }
}
</style>
    