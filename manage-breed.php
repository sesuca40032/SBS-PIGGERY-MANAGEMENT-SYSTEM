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

<!-- Modern Pig Breed Management Dashboard -->
<div class="modern-dashboard" style="margin-left:280px">
    <!-- Hero Header Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <i class="fa fa-dna"></i>
                    Manage Pig Breeds
                </h1>
                <p class="hero-subtitle">Add, manage, and organize pig breed information</p>
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
        <?php
        // Get breed statistics
        try {
            $totalBreeds = $db->query("SELECT COUNT(*) FROM breed")->fetchColumn();
            $breedsWithPigs = $db->query("SELECT COUNT(DISTINCT breed_id) FROM pigs WHERE breed_id IS NOT NULL")->fetchColumn() ?: 0;
            $activeBreeds = $breedsWithPigs; // Same as breeds with pigs for now
            $totalPigsByBreed = $db->query("SELECT COUNT(*) FROM pigs WHERE breed_id IS NOT NULL")->fetchColumn() ?: 0;
        } catch (Exception $e) {
            // Fallback values if there are any database issues
            $totalBreeds = 0;
            $breedsWithPigs = 0;
            $activeBreeds = 0;
            $totalPigsByBreed = 0;
        }
        ?>
        
        <div class="stat-card primary-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-dna"></i>
                </div>
                <div class="card-title">Total Breeds</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($totalBreeds); ?></div>
                <div class="card-subtitle">Registered breeds</div>
            </div>
        </div>

        <div class="stat-card success-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-paw"></i>
                </div>
                <div class="card-title">Active Breeds</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($activeBreeds); ?></div>
                <div class="card-subtitle">Currently in use</div>
            </div>
        </div>

        <div class="stat-card info-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-chart-bar"></i>
                </div>
                <div class="card-title">Breed Usage</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($breedsWithPigs); ?></div>
                <div class="card-subtitle">Breeds with pigs</div>
            </div>
        </div>

        <div class="stat-card warning-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-piggy-bank"></i>
                </div>
                <div class="card-title">Total Pigs</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($totalPigsByBreed); ?></div>
                <div class="card-subtitle">With breed assigned</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Breed List -->
        <div class="content-card breed-list-card">
            <div class="card-header">
                <div class="card-title-section">
                    <h2 class="card-title">
                        <i class="fa fa-list"></i>
                        Pig Breed List
                    </h2>
                    <p class="card-subtitle">Manage and organize all registered pig breeds</p>
                </div>
                <div class="card-actions">
                    <a title="Bulk delete breeds" data-toggle="modal" data-target="#_removed" id="delete" class="btn-danger-modern">
                        <i class="fa fa-trash"></i>
                        <span>Delete Selected</span>
                    </a>
                </div>
            </div>
            
            <div class="table-container">
                <form method="post" action="delete_breed.php">
                    <div class="table-wrapper">
                        <table class="modern-table" id="breedTable">
                            <thead>
                                <tr>
                                    <th style="width:50px;">
                                        <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()">
                                    </th>
                                    <th style="width:80px;">ID</th>
                                    <th>Breed Name</th>
                                    <th style="width:100px;">Usage Count</th>
                                    <th style="width:120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $get = $db->query("SELECT b.*, COUNT(p.id) as usage_count FROM breed b LEFT JOIN pigs p ON b.id = p.breed_id GROUP BY b.id ORDER BY b.name");
                                    $res = $get->fetchAll(PDO::FETCH_OBJ);
                                } catch (Exception $e) {
                                    // Fallback to simple breed query if join fails
                                    $get = $db->query("SELECT b.*, 0 as usage_count FROM breed b ORDER BY b.name");
                                    $res = $get->fetchAll(PDO::FETCH_OBJ);
                                }
                                foreach($res as $n){ ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selector[]" value="<?php echo $n->id ?>" class="breed-checkbox">
                                        </td>
                                        <td>
                                            <span class="breed-id"><?php echo $n->id; ?></span>
                                        </td>
                                        <td>
                                            <div class="breed-name">
                                                <i class="fa fa-dna breed-icon"></i>
                                                <span><?php echo htmlspecialchars($n->name); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="usage-badge <?php echo $n->usage_count > 0 ? 'active' : 'inactive'; ?>">
                                                <?php echo $n->usage_count; ?> pigs
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" class="btn-edit" onclick="editBreed(<?php echo $n->id; ?>, '<?php echo htmlspecialchars($n->name); ?>')" title="Edit Breed">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn-delete" onclick="deleteBreed(<?php echo $n->id; ?>)" title="Delete Breed">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr> 
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php include('inc/modal-delete.php'); ?>
                </form>
            </div>
        </div>

        <!-- Add New Breed -->
        <div class="content-card add-breed-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa fa-plus"></i>
                    Add New Breed
                </h2>
                <p class="card-subtitle">Register a new pig breed in the system</p>
            </div>
            
            <form method="post" class="breed-form">
                <div class="form-group">
                    <label class="form-label" for="breed">
                        <i class="fa fa-dna"></i>
                        Breed Name
                    </label>
                    <input type="text" name="breed" id="breed" class="form-input" placeholder="Enter breed name (e.g., Yorkshire, Duroc, Hampshire)" required>
                    <div class="form-help">Enter a descriptive name for the pig breed</div>
                </div>
                
                <div class="form-actions">
                    <button class="btn-primary-modern" type="submit" name="submit">
                        <i class="fa fa-plus"></i>
                        <span>Add Breed</span>
                    </button>
                    <button type="reset" class="btn-secondary-modern">
                        <i class="fa fa-refresh"></i>
                        <span>Clear</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>

<style>
/* Modern Pig Breed Management Styling - Blue Theme */
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

/* Content Grid */
.content-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 30px;
  margin: 0 50px 30px 50px;
}

.content-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
}

.content-card .card-header {
  margin-bottom: 30px;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 15px;
}

.card-title-section {
  flex: 1;
}

.content-card .card-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.content-card .card-title i {
  color: #38598b;
}

.content-card .card-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0;
  font-weight: 400;
}

.card-actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Modern Table */
.table-container {
  margin-top: 20px;
}

.table-wrapper {
  overflow-x: auto;
  border-radius: 15px;
  box-shadow: 0 4px 15px #38598b18;
}

.modern-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 15px;
  overflow: hidden;
}

.modern-table thead {
  background: #38598b;
  color: #fff;
}

.modern-table th {
  padding: 15px 12px;
  text-align: left;
  font-weight: 600;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.modern-table td {
  padding: 15px 12px;
  border-bottom: 1px solid #f1f3f4;
  font-size: 0.9rem;
  vertical-align: middle;
}

.modern-table tbody tr {
  transition: background 0.3s ease;
}

.modern-table tbody tr:hover {
  background: #f8f9fa;
}

/* Breed Specific Styles */
.breed-id {
  font-weight: 600;
  color: #38598b;
  font-size: 0.9rem;
}

.breed-name {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 500;
  color: #2c3e50;
}

.breed-icon {
  color: #38598b;
  font-size: 1.1rem;
}

.usage-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 12px;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.usage-badge.active {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.usage-badge.inactive {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.action-buttons {
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-edit, .btn-delete {
  width: 32px;
  height: 32px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.btn-edit {
  background: #e3f2fd;
  color: #1976d2;
}

.btn-edit:hover {
  background: #bbdefb;
  transform: translateY(-1px);
}

.btn-delete {
  background: #ffebee;
  color: #d32f2f;
}

.btn-delete:hover {
  background: #ffcdd2;
  transform: translateY(-1px);
}

/* Form Styles */
.breed-form {
  margin-top: 20px;
}

.form-group {
  margin-bottom: 25px;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  color: #38598b;
  margin-bottom: 8px;
  font-size: 1rem;
}

.form-label i {
  color: #38598b;
}

.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e9ecef;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #fff;
}

.form-input:focus {
  outline: none;
  border-color: #38598b;
  box-shadow: 0 0 0 3px #b4c7e750;
}

.form-help {
  font-size: 0.85rem;
  color: #6c757d;
  margin-top: 5px;
  font-style: italic;
}

.form-actions {
  display: flex;
  gap: 15px;
  margin-top: 30px;
}

/* Modern Buttons */
.btn-primary-modern, .btn-secondary-modern, .btn-danger-modern {
  padding: 12px 24px;
  border: none;
  border-radius: 25px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  min-width: 120px;
  justify-content: center;
}

.btn-primary-modern {
  background: #38598b;
  color: #fff;
  box-shadow: 0 4px 15px #38598b30;
}

.btn-primary-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px #38598b40;
}

.btn-secondary-modern {
  background: #6c757d;
  color: #fff;
  box-shadow: 0 4px 15px #6c757d30;
}

.btn-secondary-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px #6c757d40;
}

.btn-danger-modern {
  background: linear-gradient(135deg, #dc3545, #c82333);
  color: #fff;
  box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.btn-danger-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
}

/* Checkbox Styles */
#selectAll, .breed-checkbox {
  width: 18px;
  height: 18px;
  accent-color: #38598b;
  cursor: pointer;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 0 30px 40px 30px;
  }
  
  .content-grid {
    grid-template-columns: 1fr;
    gap: 20px;
    margin: 0 30px 30px 30px;
  }
  
  .hero-content {
    flex-direction: column;
    text-align: center;
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
  
  .content-grid {
    margin: 0 20px 30px 20px;
  }
  
  .content-card {
    padding: 20px;
  }
  
  .modern-table {
    font-size: 0.8rem;
  }
  
  .modern-table th, .modern-table td {
    padding: 10px 8px;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .btn-primary-modern, .btn-secondary-modern, .btn-danger-modern {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.8rem;
  }
  
  .main-stat {
    font-size: 2.5rem;
  }
  
  .action-buttons {
    flex-direction: column;
    gap: 5px;
  }
  
  .btn-edit, .btn-delete {
    width: 100%;
    height: 36px;
  }
}
</style>

<script>
// JavaScript for breed management functionality
function toggleAllCheckboxes() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.breed-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function editBreed(id, name) {
    const newName = prompt('Edit breed name:', name);
    if (newName && newName.trim() !== '' && newName !== name) {
        // Create a form to submit the edit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'edit_breed.php';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;
        
        const nameInput = document.createElement('input');
        nameInput.type = 'hidden';
        nameInput.name = 'name';
        nameInput.value = newName.trim();
        
        form.appendChild(idInput);
        form.appendChild(nameInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteBreed(id) {
    if (confirm('Are you sure you want to delete this breed? This action cannot be undone.')) {
        // Create a form to submit the deletion
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete_breed.php';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'selector[]';
        idInput.value = id;
        
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-refresh page after successful breed addition
<?php if(isset($_POST['submit']) && !empty($_POST['breed'])): ?>
setTimeout(function() {
    window.location.reload();
}, 2000);
<?php endif; ?>
</script>
