<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- Main Content -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px;padding-bottom:22px;">
    <div class="w3-row">
      <div class="w3-col m8">
        <h2><b><i class="fa fa-dashboard"></i> Dashboard Overview</b></h2>
      </div>
      <div class="w3-col m4 w3-right">
        <span class="w3-badge w3-large w3-blue"><?php echo date('F j, Y'); ?></span>
      </div>
    </div>
  </header>
  
  <?php include 'inc/data.php'; ?>
  
  <!-- Summary Cards -->
  <div class="w3-row-padding w3-margin-bottom">
    <?php
    // Count total pigs
    $pig_count = $db->query("SELECT COUNT(*) FROM pigs")->fetchColumn();
    // Count breeds
    $breed_count = $db->query("SELECT COUNT(*) FROM breed")->fetchColumn();
    // Count active users
    $user_count = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    ?>
    
    <div class="w3-col m3">
      <div class="w3-card w3-round-large w3-white w3-padding-16 w3-center">
        <div class="w3-container">
          <i class="fa fa-paw w3-xxlarge w3-text-deep-purple"></i>
          <h3><?php echo $pig_count; ?></h3>
          <p class="w3-opacity">Total Pigs</p>
        </div>
      </div>
    </div>
    
    <div class="w3-col m3">
      <div class="w3-card w3-round-large w3-white w3-padding-16 w3-center">
        <div class="w3-container">
          <i class="fa fa-list-alt w3-xxlarge w3-text-blue"></i>
          <h3><?php echo $breed_count; ?></h3>
          <p class="w3-opacity">Pig Breeds</p>
        </div>
      </div>
    </div>
    
    <div class="w3-col m3">
      <div class="w3-card w3-round-large w3-white w3-padding-16 w3-center">
        <div class="w3-container">
          <i class="fa fa-users w3-xxlarge w3-text-green"></i>
          <h3><?php echo $user_count; ?></h3>
          <p class="w3-opacity">System Users</p>
        </div>
      </div>
    </div>
    
    <div class="w3-col m3">
      <div class="w3-card w3-round-large w3-white w3-padding-16 w3-center">
        <div class="w3-container">
          <i class="fa fa-line-chart w3-xxlarge w3-text-orange"></i>
          <h3>0</h3>
          <p class="w3-opacity">Today's Activity</p>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Recent Pigs Section -->
  <div class="w3-container">
    <div class="w3-card w3-round-large w3-white w3-padding">
      <div class="w3-row w3-margin-bottom">
        <div class="w3-col m6">
          <h3><i class="fa fa-paw"></i> Recent Pigs</h3>
        </div>
        <div class="w3-col m6 w3-right-align">
          <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search pigs..." class="w3-input w3-border w3-round" style="width:auto;display:inline-block;">
          <button class="w3-button w3-blue w3-round"><i class="fa fa-filter"></i> Filter</button>
        </div>
      </div>
      
      <div class="table-responsive">
        <table class="w3-table-all w3-hoverable w3-small" id="pigsTable">
          <thead>     
            <tr class="w3-light-grey">
              <th>S/N</th>
              <th>Pig No.</th>
              <th>Breed</th>
              <th>Weight (kg)</th>
              <th>Gender</th>
              <th>Arrived</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $qpi = $db->query("SELECT * FROM pigs ORDER BY id DESC LIMIT 15");
            $result = $qpi->fetchAll(PDO::FETCH_OBJ);
            $sn = 1;

            foreach ($result as $j) {
              $pigname = $j->pigno;
              $b_id = $j->breed_id;
              $weight = $j->weight;
              $gender = $j->gender;
              $remark = $j->remark;
              $arr = $j->arrived;
              $status = $j->status ?? 'Active'; // Default to Active if status not set

              $k = $db->query("SELECT * FROM breed WHERE id = '$b_id' ");
              $ks = $k->fetchAll(PDO::FETCH_OBJ);
              foreach ($ks as $r) {
                $bname = $r->name;
            ?>
              <tr>
                <td><?php echo $sn++; ?></td>
                <td><strong><?php echo $pigname; ?></strong></td>
                <td><?php echo $bname; ?></td>
                <td><?php echo $weight; ?></td>
                <td>
                  <?php if($gender == 'Male'): ?>
                    <span class="w3-tag w3-blue w3-round"><?php echo $gender; ?></span>
                  <?php else: ?>
                    <span class="w3-tag w3-pink w3-round"><?php echo $gender; ?></span>
                  <?php endif; ?>
                </td>
                <td><?php echo date('M j, Y', strtotime($arr)); ?></td>
                <td>
                  <?php if($status == 'Active'): ?>
                    <span class="w3-tag w3-green w3-round"><?php echo $status; ?></span>
                  <?php else: ?>
                    <span class="w3-tag w3-red w3-round"><?php echo $status; ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="w3-button w3-tiny w3-blue w3-round" title="View"><i class="fa fa-eye"></i></button>
                  <button class="w3-button w3-tiny w3-orange w3-round" title="Edit"><i class="fa fa-edit"></i></button>
                </td>
              </tr>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>
      
      <div class="w3-center w3-padding-16">
        <div class="w3-bar">
          <a href="#" class="w3-bar-item w3-button w3-hover-blue">&laquo;</a>
          <a href="#" class="w3-bar-item w3-button w3-blue">1</a>
          <a href="#" class="w3-bar-item w3-button w3-hover-blue">2</a>
          <a href="#" class="w3-bar-item w3-button w3-hover-blue">3</a>
          <a href="#" class="w3-bar-item w3-button w3-hover-blue">&raquo;</a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Charts Section -->
  <div class="w3-row-padding w3-margin-top">
    <div class="w3-col m6">
      <div class="w3-card w3-round-large w3-white w3-padding" style="min-height:350px;">
        <h4><i class="fa fa-bar-chart"></i> Pigs by Breed</h4>
        <div class="chart-container" style="position:relative; height:250px;">
          <canvas id="breedChart"></canvas>
        </div>
        <div class="w3-center w3-padding" id="breedLegend"></div>
      </div>
    </div>
    
    <div class="w3-col m6">
      <div class="w3-card w3-round-large w3-white w3-padding" style="min-height:350px;">
        <h4><i class="fa fa-pie-chart"></i> Gender Distribution</h4>
        <div class="chart-container" style="position:relative; height:250px;">
          <canvas id="genderChart"></canvas>
        </div>
        <div class="w3-center w3-padding" id="genderLegend"></div>
      </div>
    </div>
  </div>
</div>

<?php include 'theme/foot.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Search function for the table
function searchTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("pigsTable");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    // Skip the header row
    if (i === 0) continue;
    
    var found = false;
    // Search in columns 1 (Pig No.), 2 (Breed), and 4 (Gender)
    for (var j = 1; j <= 4; j++) {
      if (j === 3) continue; // Skip weight column
      td = tr[i].getElementsByTagName("td")[j];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          found = true;
          break;
        }
      }
    }
    tr[i].style.display = found ? "" : "none";
  }
}

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
  // Get actual data from PHP (you'll need to replace these with actual database queries)
  <?php
  // Get breed distribution data
  $breedData = [];
  $breedLabels = [];
  $breedQuery = $db->query("SELECT b.name, COUNT(p.id) as count FROM pigs p JOIN breed b ON p.breed_id = b.id GROUP BY p.breed_id");
  $breedResults = $breedQuery->fetchAll(PDO::FETCH_OBJ);
  foreach ($breedResults as $breed) {
    $breedLabels[] = $breed->name;
    $breedData[] = $breed->count;
  }
  
  // Get gender distribution data
  $maleCount = $db->query("SELECT COUNT(*) FROM pigs WHERE gender = 'Male'")->fetchColumn();
  $femaleCount = $db->query("SELECT COUNT(*) FROM pigs WHERE gender = 'Female'")->fetchColumn();
  ?>
  
  // Breed Distribution Chart
  const breedCtx = document.getElementById('breedChart').getContext('2d');
  const breedChart = new Chart(breedCtx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($breedLabels); ?>,
      datasets: [{
        label: 'Number of Pigs',
        data: <?php echo json_encode($breedData); ?>,
        backgroundColor: [
          'rgba(255, 99, 132, 0.7)',
          'rgba(54, 162, 235, 0.7)',
          'rgba(255, 206, 86, 0.7)',
          'rgba(75, 192, 192, 0.7)',
          'rgba(153, 102, 255, 0.7)',
          'rgba(255, 159, 64, 0.7)'
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });

  // Gender Distribution Chart
  const genderCtx = document.getElementById('genderChart').getContext('2d');
  const genderChart = new Chart(genderCtx, {
    type: 'pie',
    data: {
      labels: ['Male', 'Female'],
      datasets: [{
        data: [<?php echo $maleCount; ?>, <?php echo $femaleCount; ?>],
        backgroundColor: [
          'rgba(54, 162, 235, 0.7)',
          'rgba(255, 99, 132, 0.7)'
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
          'rgba(255, 99, 132, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            boxWidth: 12,
            padding: 20
          }
        }
      }
    }
  });
});
</script>