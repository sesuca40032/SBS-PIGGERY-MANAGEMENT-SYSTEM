<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Fetch existing pens
$stmt = $db->query("SELECT * FROM pens");
$existingPens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch current pig count per pen (using batch_pens and pig_batches for up-to-date numbers)
$pigCounts = [];
$q = $db->query("
    SELECT bp.pen_id, SUM(pb.total_pigs) AS pig_count
    FROM batch_pens bp
    JOIN pig_batches pb ON bp.batch_id = pb.batch_id
    WHERE pb.status != 'sold'
    GROUP BY bp.pen_id
");
foreach ($q as $row) {
    $pigCounts[$row['pen_id']] = intval($row['pig_count']);
}

// Calculate statistics
try {
    $total_pens = count($existingPens);
    $total_capacity = array_sum(array_column($existingPens, 'capacity'));
    $total_pigs = array_sum($pigCounts);
    $total_area = array_sum(array_column($existingPens, 'area_sqm'));
} catch (Exception $e) {
    $total_pens = 0;
    $total_capacity = 0;
    $total_pigs = 0;
    $total_area = 0;
}
?>

<style>
/* Modern Floorplan Dashboard - Blue Theme */
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
  color: #ffd700;
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

.primary-card .card-icon { background: #38598b; }
.success-card .card-icon { background: #28a745; }
.info-card .card-icon { background: #2c406b; }
.warning-card .card-icon { background: #b4c7e7; color: #38598b; }

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

/* Canvas Container */
.canvas-container {
  background: #fff;
  border-radius: 15px;
  padding: 25px;
  margin-bottom: 30px;
  box-shadow: 0 4px 15px #38598b18;
  border: 1px solid #e8f5e8;
}

.canvas-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #38598b;
  margin: 0 0 20px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.canvas-title i {
  color: #38598b;
}

#floorCanvas {
  border: 3px solid #e8f5e8;
  border-radius: 15px;
  background: #f8f9fa;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  margin-bottom: 20px;
  transition: all 0.3s ease;
  cursor: crosshair;
}

#floorCanvas:focus, #floorCanvas:active {
  border-color: #38598b;
  box-shadow: 0 0 0 3px #b4c7e750;
}

/* Form Controls */
.form-container {
  background: #f8f9fa;
  border-radius: 15px;
  padding: 25px;
  margin-bottom: 30px;
  border: 1px solid #e8f5e8;
}

.form-title {
  font-size: 1.3rem;
  font-weight: 700;
  color: #38598b;
  margin: 0 0 20px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.form-title i {
  color: #38598b;
}

.pen-form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-label {
  font-weight: 600;
  color: #38598b;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.modern-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e8f5e8;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #fff;
}

.modern-input:focus {
  outline: none;
  border-color: #38598b;
  box-shadow: 0 0 0 3px #b4c7e750;
}

.area-display {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #38598b;
  color: #fff;
  font-weight: 700;
  font-size: 1.1rem;
  border-radius: 10px;
  padding: 12px 16px;
  box-shadow: 0 4px 15px #38598b30;
  min-height: 48px;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 15px;
  margin-top: 20px;
  flex-wrap: wrap;
}

.modern-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-primary {
  background: #38598b;
  color: #fff;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px #38598b40;
}

.btn-danger {
  background: linear-gradient(135deg, #dc3545, #c82333);
  color: #fff;
}

.btn-danger:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
}

/* Instructions */
.instructions {
  background: #e6e9f2;
  border-radius: 10px;
  padding: 20px;
  margin-bottom: 20px;
  border-left: 4px solid #38598b;
}

.instructions h4 {
  color: #2c406b;
  margin: 0 0 10px 0;
  font-size: 1.1rem;
  font-weight: 700;
}

.instructions ul {
  margin: 0;
  padding-left: 20px;
  color: #38598b;
}

.instructions li {
  margin-bottom: 5px;
  font-size: 0.95rem;
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
  
  .pen-form-row {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
  
  .pen-form-row {
    grid-template-columns: 1fr;
  }
  
  .action-buttons {
    flex-direction: column;
  }
  
  .modern-btn {
    width: 100%;
    justify-content: center;
  }
  
  #floorCanvas {
    width: 100%;
    height: 300px;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.8rem;
  }
  
  .main-stat {
    font-size: 2.5rem;
  }
  
  .canvas-container {
    padding: 15px;
  }
  
  .form-container {
    padding: 15px;
  }
}
</style>
<!-- Modern Floorplan Dashboard -->
<div class="modern-dashboard" style="margin-left:280px">
    <!-- Hero Header Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <i class="fa fa-pencil-square-o"></i>
                    Floorplan Designer
                </h1>
                <p class="hero-subtitle">Design and manage pig pen layouts with interactive floorplan tools</p>
            </div>
            <div class="hero-stats">
                <div class="stat-badge">
                    <i class="fa fa-cube"></i>
                    <span>Interactive Design</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card primary-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-home"></i>
                </div>
                <div class="card-title">Total Pens</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($total_pens); ?></div>
                <div class="card-subtitle">Active pens in system</div>
            </div>
        </div>

        <div class="stat-card success-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="card-title">Total Capacity</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($total_capacity); ?></div>
                <div class="card-subtitle">Maximum pigs</div>
            </div>
        </div>

        <div class="stat-card info-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-piggy-bank"></i>
                </div>
                <div class="card-title">Current Pigs</div>
            </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($total_pigs); ?></div>
                <div class="card-subtitle">Currently housed</div>
            </div>
      </div>

        <div class="stat-card warning-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-expand-arrows-alt"></i>
      </div>
                <div class="card-title">Total Area</div>
      </div>
            <div class="card-body">
                <div class="main-stat"><?php echo number_format($total_area, 1); ?>m²</div>
                <div class="card-subtitle">Square meters</div>
      </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="content-container">
        <div class="content-card">
            <!-- Instructions -->
            <div class="instructions">
                <h4><i class="fa fa-info-circle"></i> How to Use the Floorplan Designer</h4>
                <ul>
                    <li><strong>Add Pen:</strong> Fill in the pen details below, then click and drag on the canvas to create a new pen</li>
                    <li><strong>Edit Pen:</strong> Double-click on any pen to edit its properties</li>
                    <li><strong>Move Pen:</strong> Click and drag a pen to move it to a new location</li>
                    <li><strong>Resize Pen:</strong> Click and drag the bottom-right corner of a pen to resize it</li>
                    <li><strong>Delete Pen:</strong> Click the trash icon (🗑) on any pen to delete it</li>
                </ul>
            </div>

            <!-- Canvas Container -->
            <div class="canvas-container">
                <h3 class="canvas-title">
                    <i class="fa fa-draw-polygon"></i>
                    Interactive Floorplan Canvas
                </h3>
                <canvas id="floorCanvas" width="800" height="500" tabindex="0"></canvas>
            </div>

            <!-- Form Container -->
            <div class="form-container">
                <h3 class="form-title">
                    <i class="fa fa-edit"></i>
                    Pen Configuration
                </h3>
                <div class="pen-form-row">
                    <div class="form-group">
                        <label class="form-label">Pen Label</label>
                        <input type="text" id="penLabel" placeholder="Enter pen name" class="modern-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Length (meters)</label>
                        <input type="number" id="penLength" min="1" placeholder="Length in meters" class="modern-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Width (meters)</label>
                        <input type="number" id="penWidth" min="1" placeholder="Width in meters" class="modern-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Capacity</label>
                        <input type="number" id="penCapacity" min="1" placeholder="Max pigs" class="modern-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Area</label>
                        <div id="penArea" class="area-display">0.00 sqm</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button onclick="savePens()" class="modern-btn btn-primary">
                    <i class="fa fa-save"></i>
                    <span>Save Changes</span>
                </button>
                <button onclick="clearCanvas()" class="modern-btn btn-danger">
                    <i class="fa fa-trash"></i>
                    <span>Clear Canvas</span>
                </button>
            </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const canvas = document.getElementById('floorCanvas');
  const ctx = canvas.getContext('2d');
  let pens = [];
  let draggingPen = null, resizingPen = null, resizingDir = '', offsetX, offsetY;
  let selectedPenIdx = null;

  // Get pens and pig counts from PHP
  const existingPens = <?php echo json_encode($existingPens); ?>;
  const penPigCounts = <?php echo json_encode($pigCounts); ?>;

  // Load pens
  existingPens.forEach(pen => {
    pens.push({
      id: pen.id,
      x: parseInt(pen.x),
      y: parseInt(pen.y),
      width: parseInt(pen.width),
      height: parseInt(pen.height),
      label: pen.label,
      length: parseFloat(pen.length_m),
      widthM: parseFloat(pen.width_m),
      area: parseFloat(pen.area_sqm),
      capacity: parseInt(pen.capacity),
      current_pigs: parseInt(penPigCounts[pen.id] || 0)
    });
  });

  function drawPen(pen, idx, highlight=false) {
    ctx.save();
    
    // Draw pen background
    ctx.fillStyle = highlight ? "rgba(86, 171, 47, 0.1)" : "rgba(86, 171, 47, 0.05)";
    ctx.fillRect(pen.x, pen.y, pen.width, pen.height);
    
    // Draw pen border
    ctx.strokeStyle = highlight ? "#ffa500" : "#56ab2f";
    ctx.lineWidth = highlight ? 3 : 2;
    ctx.strokeRect(pen.x, pen.y, pen.width, pen.height);
    
    // Draw pen label
    ctx.font = "bold 14px Arial";
    ctx.fillStyle = "#2c3e50";
    ctx.fillText(pen.label, pen.x + 8, pen.y + 18);
    
    // Draw pen details
    ctx.font = "11px Arial";
    ctx.fillStyle = "#56ab2f";
    ctx.fillText(`Area: ${pen.area || "?"} sqm`, pen.x + 8, pen.y + 35);
    ctx.fillStyle = "#2d5016";
    ctx.fillText(`Cap: ${pen.capacity || "?"}`, pen.x + 8, pen.y + 50);
    ctx.fillStyle = "#e67e22";
    ctx.fillText(`Pigs: ${pen.current_pigs || 0}`, pen.x + 8, pen.y + 65);
    
    // Draw delete icon
    ctx.font = "16px Arial";
    ctx.fillStyle = "#dc3545";
    ctx.fillText("🗑", pen.x + pen.width - 20, pen.y + 20);
    
    ctx.restore();
  }

  function redrawCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    pens.forEach((pen, i) => drawPen(pen, i, selectedPenIdx === i));
  }

  function showPenForm(pen) {
    document.getElementById('penLabel').value = pen.label || '';
    document.getElementById('penLength').value = pen.length || '';
    document.getElementById('penWidth').value = pen.widthM || '';
    document.getElementById('penCapacity').value = pen.capacity || '';
    document.getElementById('penArea').innerText = pen.length && pen.widthM ? (pen.length * pen.widthM).toFixed(2) + " sqm" : '0.00 sqm';
  }

  ['penLength', 'penWidth'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
      const l = parseFloat(document.getElementById('penLength').value);
      const w = parseFloat(document.getElementById('penWidth').value);
      if (!isNaN(l) && !isNaN(w)) {
        document.getElementById('penArea').innerText = (l * w).toFixed(2) + " sqm";
      } else {
        document.getElementById('penArea').innerText = '0.00 sqm';
      }
    });
  });

  let drawing = false, startX, startY;

  canvas.addEventListener('mousedown', (e) => {
    const mx = e.offsetX, my = e.offsetY;
    selectedPenIdx = null;
    // Hit test for delete or select/resize/drag
    for (let i = pens.length - 1; i >= 0; i--) {
      let pen = pens[i];
      // Delete icon
      if (mx >= pen.x + pen.width - 22 && mx <= pen.x + pen.width && my >= pen.y && my <= pen.y + 22) {
        if (confirm("Delete this pen?")) {
          if (pen.id) {
            fetch('delete_pen.php', {method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({id: pen.id})})
              .then(()=>{ pens.splice(i,1); redrawCanvas(); });
          } else {
            pens.splice(i,1); redrawCanvas();
          }
        }
        return;
      }
      // Resize handle (bottom right 12x12 px corner)
      if (mx >= pen.x + pen.width - 12 && mx <= pen.x + pen.width && my >= pen.y + pen.height - 12 && my <= pen.y + pen.height) {
        resizingPen = pen; resizingDir = 'se'; selectedPenIdx = i;
        return;
      }
      // Dragging inside pen
      if (mx >= pen.x && mx <= pen.x + pen.width && my >= pen.y && my <= pen.y + pen.height) {
        draggingPen = pen; offsetX = mx - pen.x; offsetY = my - pen.y; selectedPenIdx = i;
        showPenForm(pen);
        return;
      }
    }
    // Draw new pen
    if (!document.getElementById('penLabel').value.trim()) {
      alert("Please enter a pen label first.");
      return;
    }
    drawing = true;
    startX = mx; startY = my;
  });

  canvas.addEventListener('mousemove', (e) => {
    if (draggingPen) {
      draggingPen.x = e.offsetX - offsetX;
      draggingPen.y = e.offsetY - offsetY;
      redrawCanvas();
      return;
    }
    if (resizingPen) {
      resizingPen.width = Math.max(30, e.offsetX - resizingPen.x);
      resizingPen.height = Math.max(30, e.offsetY - resizingPen.y);
      redrawCanvas();
      return;
    }
    if (drawing) {
      redrawCanvas();
      ctx.setLineDash([6,3]);
      ctx.strokeStyle = "#56ab2f";
      ctx.lineWidth = 2;
      ctx.strokeRect(startX, startY, e.offsetX - startX, e.offsetY - startY);
      ctx.setLineDash([]);
    }
  });

  canvas.addEventListener('mouseup', (e) => {
    if (draggingPen) { draggingPen = null; return; }
    if (resizingPen) { resizingPen = null; resizingDir = ''; return; }
    if (drawing) {
      drawing = false;
      let width = Math.abs(e.offsetX - startX), height = Math.abs(e.offsetY - startY);
      let x = Math.min(startX, e.offsetX), y = Math.min(startY, e.offsetY);
      // get form values
      let label = document.getElementById('penLabel').value.trim();
      let length = parseFloat(document.getElementById('penLength').value);
      let widthM = parseFloat(document.getElementById('penWidth').value);
      let area = (!isNaN(length) && !isNaN(widthM)) ? (length * widthM).toFixed(2) : '';
      let capacity = parseInt(document.getElementById('penCapacity').value) || '';
      // Check overlap
      for (let pen of pens) {
        if (x < pen.x + pen.width && x + width > pen.x && y < pen.y + pen.height && y + height > pen.y) {
          alert("Cannot place pen here. It overlaps with an existing pen.");
          return;
        }
      }
      pens.push({x, y, width, height, label, length, widthM, area, capacity, current_pigs:0});
      redrawCanvas();
    }
  });

  // Select pen by click to edit values
  canvas.addEventListener('dblclick', (e) => {
    const mx = e.offsetX, my = e.offsetY;
    for (let i = pens.length - 1; i >= 0; i--) {
      let pen = pens[i];
      if (mx >= pen.x && mx <= pen.x + pen.width && my >= pen.y && my <= pen.y + pen.height) {
        selectedPenIdx = i;
        showPenForm(pen);
        return;
      }
    }
  });

  // Save/new pen edits to selected pen
  ['penLabel','penLength','penWidth','penCapacity'].forEach(id => {
    document.getElementById(id).addEventListener('input', ()=>{
      if(selectedPenIdx !== null) {
        let pen = pens[selectedPenIdx];
        pen.label = document.getElementById('penLabel').value;
        pen.length = parseFloat(document.getElementById('penLength').value);
        pen.widthM = parseFloat(document.getElementById('penWidth').value);
        pen.area = (!isNaN(pen.length) && !isNaN(pen.widthM)) ? (pen.length * pen.widthM).toFixed(2) : '';
        pen.capacity = parseInt(document.getElementById('penCapacity').value)||'';
        redrawCanvas();
      }
    });
  });

  window.savePens = () => {
    fetch('save_pen.php', {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({pens})
    })
    .then(res=>res.text())
    .then(msg=>{
      alert("Pens saved!");
      location.reload();
    }).catch(e=>{
      alert("Error saving pens!");
    });
  };

  window.clearCanvas = () => {
    if (confirm("Are you sure? This will remove all pens not saved.")) {
      pens = [];
      selectedPenIdx = null;
      redrawCanvas();
    }
  };

  // Initial draw
  redrawCanvas();
});
</script>

<?php include 'theme/foot.php'; ?>