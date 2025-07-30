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
foreach ($fetch as $data) {
    $current_date = new DateTime();
    $labor_date = new DateTime($data->labor_date);
    $interval = $current_date->diff($labor_date);
    $gestation_days = $interval->days;

    if (in_array($gestation_days, [80, 90, 100])) {
        // Store labor dates with notifications
        $notification_dates[] = $data->labor_date; // Store dates as YYYY-MM-DD
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
?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-calendar"></i> Calendar Notifications</b></h5>
    </header>

    <div class="w3-container" style="padding-top:22px">
        <h2>Pregnancy Notifications Calendar</h2>
        
        <!-- Month Navigation Links -->
        <a href="calendar-notifications.php?month=<?php echo $month - 1; ?>&year=<?php echo $year; ?>" class="btn btn-primary">Previous Month</a>
        <a href="calendar-notifications.php?month=<?php echo $month + 1; ?>&year=<?php echo $year; ?>" class="btn btn-primary">Next Month</a>
        
        <h4><?php echo date('F Y', strtotime("$year-$month-01")); ?></h4>

        <!-- Calendar Display -->
        <div id="calendar"></div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationDates = <?php echo json_encode($notification_dates); ?>;

        const calendarElement = document.getElementById('calendar');
        
        // Get month and year from PHP
        const currentMonth = <?php echo $month; ?>;
        const currentYear = <?php echo $year; ?>;
        
        function generateCalendar(year, month) {
            const firstDay = new Date(year, month - 1, 1);
            const lastDate = new Date(year, month, 0).getDate();
            const firstDayOfWeek = firstDay.getDay();
            
            let calendarHTML = '<table class="table table-bordered">';
            calendarHTML += '<thead><tr>';
            ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(day => {
                calendarHTML += `<th>${day}</th>`;
            });
            calendarHTML += '</tr></thead><tbody>';
            
            let date = 1;
            for (let i = 0; i < 6; i++) {
                calendarHTML += '<tr>';
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDayOfWeek) {
                        calendarHTML += '<td></td>';
                    } else if (date <= lastDate) {
                        const currentDate = new Date(year, month - 1, date);
                        const dateStr = currentDate.toISOString().split('T')[0]; // Format as YYYY-MM-DD
                        const highlightClass = notificationDates.includes(dateStr) ? 'bg-warning' : '';
                        calendarHTML += `<td class="${highlightClass}">${date}</td>`;
                        date++;
                    } else {
                        calendarHTML += '<td></td>';
                    }
                }
                calendarHTML += '</tr>';
                if (date > lastDate) break;
            }
            calendarHTML += '</tbody></table>';
            
            calendarElement.innerHTML = calendarHTML;
        }

        generateCalendar(currentYear, currentMonth);
    });
</script>

<style>
    .table-bordered td {
        text-align: center;
        padding: 10px;
        vertical-align: middle;
    }
    .bg-warning {
        background-color: yellow !important;
    }
</style>
    