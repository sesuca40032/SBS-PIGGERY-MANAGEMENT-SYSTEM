<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-map"></i> Select a Pen Location</b></h5>
  </header>

  <div class="w3-container">
    <div class="w3-card-4 w3-white" style="padding:20px;">
      <canvas id="floorplanCanvas" width="800" height="600" style="border:1px solid #ccc;"></canvas>
    </div>
  </div>
</div>

<script>
const canvas = document.getElementById('floorplanCanvas');
const ctx = canvas.getContext('2d');

let pens = [
  { x: 50, y: 50, w: 120, h: 80, label: 'Pen A', color: '#8BC34A', selected: false },
  { x: 200, y: 50, w: 120, h: 80, label: 'Pen B', color: '#FF9800', selected: false },
  { x: 50, y: 180, w: 120, h: 80, label: 'Pen C', color: '#03A9F4', selected: false },
  { x: 200, y: 180, w: 120, h: 80, label: 'Pen D', color: '#9C27B0', selected: false },
];

let dragging = null;
let offsetX = 0, offsetY = 0;
let hovered = null;

function drawPens() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  pens.forEach(pen => {
    ctx.fillStyle = pen.selected ? '#2196F3' : pen.color;
    ctx.fillRect(pen.x, pen.y, pen.w, pen.h);

    // Highlight border
    ctx.strokeStyle = hovered === pen ? '#000' : '#fff';
    ctx.lineWidth = 2;
    ctx.strokeRect(pen.x, pen.y, pen.w, pen.h);

    ctx.fillStyle = '#fff';
    ctx.font = 'bold 16px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(pen.label, pen.x + pen.w/2, pen.y + pen.h/2);
  });
}

function getPenAt(x, y) {
  return pens.find(p => x >= p.x && x <= p.x + p.w && y >= p.y && y <= p.y + p.h);
}

canvas.addEventListener('mousedown', e => {
  const rect = canvas.getBoundingClientRect();
  const mouseX = e.clientX - rect.left;
  const mouseY = e.clientY - rect.top;
  const pen = getPenAt(mouseX, mouseY);

  if (pen) {
    dragging = pen;
    offsetX = mouseX - pen.x;
    offsetY = mouseY - pen.y;
  }
});

canvas.addEventListener('mousemove', e => {
  const rect = canvas.getBoundingClientRect();
  const mouseX = e.clientX - rect.left;
  const mouseY = e.clientY - rect.top;
  hovered = getPenAt(mouseX, mouseY);

  if (dragging) {
    const newX = mouseX - offsetX;
    const newY = mouseY - offsetY;

    // Collision check
    const temp = { ...dragging, x: newX, y: newY };
    const collides = pens.some(p => p !== dragging && isColliding(temp, p));
    if (!collides) {
      dragging.x = newX;
      dragging.y = newY;
    }
  }

  drawPens();
});

canvas.addEventListener('mouseup', () => {
  dragging = null;
});

canvas.addEventListener('click', e => {
  const rect = canvas.getBoundingClientRect();
  const mouseX = e.clientX - rect.left;
  const mouseY = e.clientY - rect.top;
  const clicked = getPenAt(mouseX, mouseY);

  if (clicked) {
    pens.forEach(p => p.selected = false);
    clicked.selected = true;
    drawPens();

    // Send selected pen label to parent
    window.opener.postMessage({ penLabel: clicked.label }, "*");
  }
});

function isColliding(a, b) {
  return a.x < b.x + b.w &&
         a.x + a.w > b.x &&
         a.y < b.y + b.h &&
         a.y + a.h > b.y;
}

drawPens();
</script>

<?php include 'theme/foot.php'; ?>
