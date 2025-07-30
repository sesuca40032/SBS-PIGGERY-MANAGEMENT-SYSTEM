<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// Get batch ID from URL
$batch_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$batch_id) die("Batch ID required.");
// Get pen assignments for batch
$stmt = $db->prepare("SELECT pen_id, pigs_assigned FROM batch_pens WHERE batch_id=?");
$stmt->execute([$batch_id]);
$assignedPens = [];
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) $assignedPens[$row['pen_id']] = $row['pigs_assigned'];

// Get all pens
$pens = $db->query("SELECT * FROM pens")->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="w3-main" style="margin-left:300px; margin-top:43px;">
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-map"></i> Batch Floorplan Location</b></h5>
  </header>
  <div class="w3-container">
    <canvas id="floorCanvas" width="800" height="500" style="border:1px solid #ccc;"></canvas>
    <br>
    <span>Orange pens are assigned to this batch.</span>
  </div>
</div>
<script>
const pens = <?php echo json_encode($pens); ?>;
const assigned = <?php echo json_encode($assignedPens); ?>;
const canvas = document.getElementById('floorCanvas');
const ctx = canvas.getContext('2d');
pens.forEach(pen => {
  ctx.save();
  ctx.strokeStyle = assigned[pen.id] ? "#ffa500" : "#008000";
  ctx.lineWidth = 2;
  ctx.strokeRect(pen.x, pen.y, pen.width, pen.height);
  ctx.font = "13px Arial";
  ctx.fillStyle = "black";
  ctx.fillText(pen.label, pen.x + 5, pen.y + 15);
  ctx.fillText("Cap:" + pen.capacity, pen.x + 5, pen.y + 32);
  if (assigned[pen.id]) {
    ctx.fillStyle = "#ffa500";
    ctx.fillText("Batch: "+assigned[pen.id], pen.x + 5, pen.y + 48);
  }
  ctx.restore();
});
</script>
<?php include 'theme/foot.php'; ?>