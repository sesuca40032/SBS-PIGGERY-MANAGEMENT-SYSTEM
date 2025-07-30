<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php 
if(isset($_POST['submit'])){
    $name = trim($_POST['breed']);
    if(!empty($name)) {
        $query = $db->prepare("INSERT INTO breed(name) VALUES(:name)");
        $result = $query->execute([':name' => $name]);
        if($result){
            echo "<script>alert('Breed Added. Click OK to close dialogue.');</script>";
            header('refresh: 1.5; url=' . $_SERVER['PHP_SELF']); 
            exit;
        } else {
            echo "<script>alert('Error adding breed. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Breed name cannot be empty.');</script>";
    }
}
?>

<!-- Main Content (Dashboard UI style) -->
<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">

  <!-- Header -->
  <header class="dashboard-header">
    <div class="dashboard-row">
      <div class="dashboard-col dashboard-title">
        <h2><b><i class="fa fa-dna"></i> Manage Pig Breeds</b></h2>
      </div>
      <div class="dashboard-col dashboard-date">
        <span class="dashboard-badge"><?php echo date('F j, Y'); ?></span>
      </div>
    </div>
  </header>

  <div class="dashboard-charts-row">
    <!-- Breed List -->
    <div class="dashboard-chart-col" style="max-width:650px;flex:2;">
      <div class="dashboard-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
          <h4><i class="fa fa-list"></i> Pig Breed List</h4>
          <a title="Bulk delete breeds" data-toggle="modal" data-target="#_removed" id="delete" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
        </div>
        <form method="post" action="delete_breed.php">
          <div style="overflow-x:auto;margin-top:10px;">
            <table class="table table-hover table-bordered" id="table">
              <thead>
                <tr style="background:#f3f6fb;">
                  <th style="width:40px;"></th>
                  <th style="width:80px;">ID</th>
                  <th>Name</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $get = $db->query("SELECT * FROM breed");
                $res = $get->fetchAll(PDO::FETCH_OBJ);
                foreach($res as $n){ ?>
                  <tr>
                    <td>
                      <input type="checkbox" name="selector[]" value="<?php echo $n->id ?>">
                    </td>
                    <td><?php echo $n->id; ?></td>
                    <td><?php echo htmlspecialchars($n->name); ?></td>
                  </tr> 
                <?php }
                ?>
              </tbody>
            </table>
          </div>
          <?php include('inc/modal-delete.php'); ?>
        </form>
      </div>
    </div>

    <!-- Add New Breed -->
    <div class="dashboard-chart-col" style="max-width:350px;flex:1;">
      <div class="dashboard-card">
        <h4><i class="fa fa-plus"></i> Add New Breed</h4>
        <form method="post" style="margin-top:18px;">
          <div class="form-group">
            <label class="control-label" for="breed">Breed Name</label>
            <input type="text" name="breed" id="breed" class="form-control" placeholder="Enter breed name" required style="margin-top:7px;">
          </div>
          <button class="btn btn-primary btn-block" type="submit" name="submit" style="margin-top:14px;"><i class="fa fa-plus"></i> Add</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'theme/foot.php'; ?>

<style>
/* Dashboard Modern UI Styles (copied from dashboard.php for consistency) */
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
.dashboard-chart-col {
  flex: 1 1 350px;
  min-width: 320px;
  max-width: 48vw;
  margin-bottom: 28px;
}
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 350px;
}
.dashboard-card h4 {
  font-size: 1.22rem;
  color: #38598b;
  margin-bottom: 20px;
  font-weight: 700;
  letter-spacing: 0.2px;
}
.table thead th {
  font-weight: 700;
  color: #38598b;
  background: #f3f6fb;
  border-bottom: 2px solid #b4c7e7;
}
.table tr td {
  font-size: 1.07rem;
  vertical-align: middle;
}
.table-hover tbody tr:hover {
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