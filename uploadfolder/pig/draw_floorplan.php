<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Fetch existing pens from the database
$existingPens = [];
$stmt = $db->query("SELECT * FROM pens");
$existingPens = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="w3-main" style="margin-left:300px; margin-top:43px;">
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-pencil-square-o"></i> Draw Floorplan</b></h5>
  </header>

  <div class="w3-container">
    <canvas id="floorCanvas" width="800" height="500" style="border:1px solid #ccc;"></canvas>
    <br>
    <input type="text" id="penLabel" placeholder="Pen Label" class="w3-input w3-border" style="width:200px;">
    <div class="w3-section">
      <button onclick="savePen()" class="w3-button w3-green w3-margin-top">Save Pen</button>
      <button onclick="clearCanvas()" class="w3-button w3-red w3-margin-top">Clear</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const canvas = document.getElementById('floorCanvas');
  const ctx = canvas.getContext('2d');
  let pens = [];
  let draggingPen = null;
  let offsetX, offsetY;

  const existingPens = <?php echo json_encode($existingPens); ?>;

  const drawPen = (pen) => {
    ctx.strokeStyle = "green";
    ctx.lineWidth = 2;
    ctx.strokeRect(pen.x, pen.y, pen.width, pen.height);
    ctx.font = "14px Arial";
    ctx.fillStyle = "black";
    ctx.fillText(pen.label, pen.x + 5, pen.y + 15);
  };

  const redrawCanvas = () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    pens.forEach(drawPen);
  };

  // Load and draw existing pens
  existingPens.forEach(pen => {
    const penObj = {
      id: pen.id,
      x: parseInt(pen.x),
      y: parseInt(pen.y),
      width: parseInt(pen.width),
      height: parseInt(pen.height),
      label: pen.label
    };
    pens.push(penObj);
    drawPen(penObj);
  });

  let drawing = false;
  let startX, startY;

  canvas.addEventListener('mousedown', (e) => {
    const mx = e.offsetX;
    const my = e.offsetY;

    // Check for dragging
    for (let pen of pens) {
      if (mx >= pen.x && mx <= pen.x + pen.width && my >= pen.y && my <= pen.y + pen.height) {
        draggingPen = pen;
        offsetX = mx - pen.x;
        offsetY = my - pen.y;
        return;
      }
    }

    // Drawing a new pen
    if (!document.getElementById('penLabel').value.trim()) {
      alert("Please enter a pen label first.");
      return;
    }
    drawing = true;
    startX = mx;
    startY = my;
  });

  canvas.addEventListener('mousemove', (e) => {
    if (draggingPen) {
      draggingPen.x = e.offsetX - offsetX;
      draggingPen.y = e.offsetY - offsetY;
      redrawCanvas();
    }
  });

  canvas.addEventListener('mouseup', (e) => {
    if (draggingPen) {
      draggingPen = null;
      return;
    }

    if (!drawing) return;
    drawing = false;

    const endX = e.offsetX;
    const endY = e.offsetY;
    const width = endX - startX;
    const height = endY - startY;
    const label = document.getElementById('penLabel').value;

    const newPen = { x: startX, y: startY, width, height, label };

    // Simple overlap check
    for (let pen of pens) {
      if (
        newPen.x < pen.x + pen.width &&
        newPen.x + newPen.width > pen.x &&
        newPen.y < pen.y + pen.height &&
        newPen.y + newPen.height > pen.y
      ) {
        alert("Cannot place pen here. It overlaps with an existing pen.");
        return;
      }
    }

    pens.push(newPen);
    drawPen(newPen);
  });

  window.savePen = () => {
    const newPens = pens.filter(p => !p.id); // Only new pens (no ID)
    if (newPens.length === 0) {
      alert("No new pens to save.");
      return;
    }

    fetch('save_pen.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ pens: newPens })
    })
    .then(res => res.text())
    .then(msg => {
      alert("Pens saved successfully!");
      location.reload();
    })
    .catch(err => {
      console.error(err);
      alert("Error saving pens.");
    });
  };

  window.clearCanvas = () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    pens = [];
  };
});
</script>

<?php include 'theme/foot.php'; ?>
