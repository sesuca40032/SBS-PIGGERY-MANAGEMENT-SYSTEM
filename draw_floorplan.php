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
?>

<style>
/* Modern dashboard style for consistency */
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
  padding: 28px 34px 18px 34px;
  box-shadow: 0 4px 24px -10px #38598b40;
}
.dashboard-title h2 {
  font-size: 2.0rem;
  font-weight: 800;
  letter-spacing: 0.2px;
  margin-bottom: 0;
}
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 0;
  margin-bottom: 0;
}
.form-group label {
  font-weight: 600;
  color: #38598b;
  font-size: 1.05rem;
}
.form-control, .w3-input, .w3-select {
  border-radius: 7px !important;
  border: 1px solid #b4c7e7 !important;
  box-shadow: 0 1px 4px -2px #38598b18 !important;
  font-size: 1.12rem !important;
  padding: 10px 14px !important;
}
.w3-button.w3-green, .w3-button.w3-red {
  font-size: 1.07rem !important;
  font-weight: 600 !important;
  border-radius: 8px !important;
  box-shadow: 0 2px 8px -2px #38598b18 !important;
  margin-right: 6px;
}
.w3-button.w3-green { background: #44b78b !important; color: #fff !important; }
.w3-button.w3-green:hover { background: #319b70 !important; }
.w3-button.w3-red { background: #e74c3c !important; color: #fff !important; }
.w3-button.w3-red:hover { background: #b93222 !important; }
.w3-tag.w3-green {
  background: #44b78b !important;
  color: #fff !important;
  font-weight: 700;
  font-size: 1.05rem;
  border-radius: 14px;
  padding: 6px 18px;
  box-shadow: 0 2px 8px -2px #44b78b18;
}
#floorCanvas {
  border: 2px solid #b4c7e7;
  border-radius: 14px;
  background: #f3f6fb;
  box-shadow: 0 4px 22px -8px #38598b18;
  margin-bottom: 18px;
  transition: box-shadow 0.2s;
}
#floorCanvas:focus, #floorCanvas:active {
  box-shadow: 0 2px 12px -2px #44b78b30;
}
.pen-form-row {
  display: flex;
  gap: 14px;
  margin-bottom: 4px;
}
.pen-form-row > div {
  flex: 1 1 120px;
  min-width: 80px;
}
@media (max-width: 1100px) {
  #floorCanvas { max-width: 99vw; }
  .dashboard-card { min-width: unset; max-width: 99vw; }
}
@media (max-width: 768px) {
  .dashboard-main { margin-left: 0; padding: 0 0 10px 0; }
  .dashboard-header { padding: 21px 8px 14px 8px; }
}
</style>

<div class="dashboard-main" style="margin-left:320px; margin-top:50px;">
  <header class="dashboard-header">
    <div class="dashboard-title">
      <h2><b><i class="fa fa-pencil-square-o"></i> Draw Floorplan</b></h2>
    </div>
  </header>

  <div class="dashboard-card" style="margin:38px 38px 0 38px;">
    <canvas id="floorCanvas" width="800" height="500" tabindex="0"></canvas>
    <div class="pen-form-row" style="margin-bottom:20px;">
      <div>
        <input type="text" id="penLabel" placeholder="Pen Label" class="form-control" style="margin-bottom:7px;">
      </div>
      <div>
        <input type="number" id="penLength" min="1" placeholder="Length (m)" class="form-control" style="margin-bottom:7px;">
      </div>
      <div>
        <input type="number" id="penWidth" min="1" placeholder="Width (m)" class="form-control" style="margin-bottom:7px;">
      </div>
      <div>
        <input type="number" id="penCapacity" min="1" placeholder="Capacity" class="form-control" style="margin-bottom:7px;">
      </div>
      <div>
        <span id="penArea" class="w3-tag w3-green" style="margin-top:8px;display:block;"></span>
      </div>
    </div>
    <div class="w3-section">
      <button onclick="savePens()" class="w3-button w3-green w3-round"><i class="fa fa-save"></i> Save Changes</button>
      <button onclick="clearCanvas()" class="w3-button w3-red w3-round"><i class="fa fa-trash"></i> Clear</button>
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
    ctx.strokeStyle = highlight ? "#ffa500" : "#44b78b";
    ctx.lineWidth = highlight ? 3 : 2;
    ctx.strokeRect(pen.x, pen.y, pen.width, pen.height);
    ctx.font = "bold 13px Arial";
    ctx.fillStyle = "#38598b";
    ctx.fillText(pen.label, pen.x + 5, pen.y + 15);
    ctx.font = "12px Arial";
    ctx.fillStyle = "#2299dd";
    ctx.fillText(`Area: ${pen.area || "?"} sqm`, pen.x + 5, pen.y + 32);
    ctx.fillStyle = "#38598b";
    ctx.fillText(`Cap: ${pen.capacity || "?"}`, pen.x + 5, pen.y + 48);
    ctx.fillStyle = "#e67e22";
    ctx.fillText(`Pigs: ${pen.current_pigs || 0}`, pen.x + 5, pen.y + 64);
    // Draw delete icon
    ctx.font = "18px Arial";
    ctx.fillStyle = "#e74c3c";
    ctx.fillText("🗑", pen.x + pen.width - 22, pen.y + 22);
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
    document.getElementById('penArea').innerText = pen.length && pen.widthM ? (pen.length * pen.widthM).toFixed(2) + " sqm" : '';
  }

  ['penLength', 'penWidth'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
      const l = parseFloat(document.getElementById('penLength').value);
      const w = parseFloat(document.getElementById('penWidth').value);
      if (!isNaN(l) && !isNaN(w)) {
        document.getElementById('penArea').innerText = (l * w).toFixed(2) + " sqm";
      } else {
        document.getElementById('penArea').innerText = '';
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
      ctx.strokeStyle = "#888";
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