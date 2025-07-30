<!-- Quarantine Modal with Floor Plan Pen Selection -->
<div id="quarantineModal" class="w3-modal">
  <div class="w3-modal-content w3-animate-top w3-card-4" style="max-width:700px;">
    <header class="w3-container w3-orange">
      <span onclick="closeQuarantineModal()" class="w3-button w3-display-topright">&times;</span>
      <h3>Move Batch to Quarantine</h3>
    </header>
    <form id="quarantineForm" class="w3-container" method="POST" action="process_quarantine.php">
      <input type="hidden" id="quarantine_batch_id" name="batch_id">
      <input type="hidden" id="quarantine_pen_id" name="pen_id">
      <div class="w3-section">
        <label>Batch ID</label>
        <input class="w3-input w3-border" type="text" id="quarantine_batch_id_display" disabled>
      </div>
      <div class="w3-section">
        <label>Move whole batch?</label>
        <select class="w3-input w3-border" name="is_full_batch" id="is_full_batch">
          <option value="0">No, specify number</option>
          <option value="1">Yes, move all pigs in batch</option>
        </select>
      </div>
      <div id="quarantine_numbers_section">
        <div class="w3-section">
          <label>Number of pigs</label>
          <input class="w3-input w3-border" type="number" name="num_quarantined" id="num_quarantined" min="1" required>
        </div>
        <div class="w3-section" style="display:flex;gap:12px;">
          <div style="width:50%;">
            <label>How many Male?</label>
            <input class="w3-input w3-border" type="number" name="num_male" id="num_male_quarantine" min="0" required>
          </div>
          <div style="width:50%;">
            <label>How many Female?</label>
            <input class="w3-input w3-border" type="number" name="num_female" id="num_female_quarantine" min="0" required>
          </div>
        </div>
      </div>
      <div class="w3-section">
        <label>Choose Quarantine Pen (click on the floor plan)</label>
        <div id="quarantinePenError" class="w3-panel w3-red" style="display:none;"></div>
        <canvas id="quarantineFloorCanvas" width="700" height="400" style="border:1px solid #ccc;background:#f9f9f9"></canvas>
        <div id="chosenQuarantinePenInfo" style="margin-top:8px;" class="w3-tag w3-blue w3-small"></div>
      </div>
      <div class="w3-section">
        <label>Symptoms</label>
        <textarea class="w3-input w3-border" name="symptoms" id="symptoms"></textarea>
      </div>
      <div class="w3-section">
        <label>Additional Notes</label>
        <textarea class="w3-input w3-border" name="notes" id="quarantine_notes"></textarea>
      </div>
      <button class="w3-button w3-orange" type="submit">Move to Quarantine</button>
    </form>
  </div>
</div>

<script>
let quarantinePenData = [];
let quarantinePenPigCounts = {};
let quarantineChosenPenId = null;
let quarantinePigCount = 0;

function drawQuarantineFloorPlan() {
  const canvas = document.getElementById('quarantineFloorCanvas');
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  quarantinePenData.forEach(pen => {
    ctx.save();
    let current = quarantinePenPigCounts[pen.id] || 0;
    let isChosen = quarantineChosenPenId == pen.id;
    ctx.strokeStyle = isChosen ? "#ff6600" : "#008000";
    ctx.lineWidth = isChosen ? 4 : 2;
    ctx.strokeRect(pen.x, pen.y, pen.width, pen.height);

    ctx.font = "13px Arial";
    ctx.fillStyle = "black";
    ctx.fillText(pen.label, pen.x + 5, pen.y + 15);
    ctx.font = "12px Arial";
    ctx.fillText("Cap:" + pen.capacity, pen.x + 5, pen.y + 32);
    ctx.fillText("Curr:" + current, pen.x + 5, pen.y + 48);
    if (isChosen) {
      ctx.fillStyle = "#ff6600";
      ctx.fillText("Selected", pen.x + 5, pen.y + 68);
      ctx.strokeStyle = "#ff6600";
      ctx.strokeRect(pen.x-3, pen.y-3, pen.width+6, pen.height+6);
    }
    ctx.restore();
  });
}

function setupQuarantineFloorPlanSelect() {
  const canvas = document.getElementById('quarantineFloorCanvas');
  canvas.onclick = function(e) {
    const rect = canvas.getBoundingClientRect();
    const mx = e.clientX - rect.left, my = e.clientY - rect.top;
    for (let pen of quarantinePenData) {
      if (mx >= pen.x && mx <= pen.x + pen.width && my >= pen.y && my <= pen.y + pen.height) {
        quarantineChosenPenId = pen.id;
        document.getElementById('quarantine_pen_id').value = pen.id;
        document.getElementById('quarantinePenError').style.display = "none";
        document.getElementById('chosenQuarantinePenInfo').innerText =
          `Quarantine pen: ${pen.label} (Current: ${quarantinePenPigCounts[pen.id] || 0} / ${pen.capacity})`;
        drawQuarantineFloorPlan();
        return;
      }
    }
  };
}

function fetchQuarantinePensAndCounts() {
  fetch('load_pens.php') // endpoint returns [{id, label, x, y, width, height, capacity}]
    .then(res => res.json())
    .then(pens => {
      quarantinePenData = pens;
      return fetch('pen_pig_counts_by_id.php');
    })
    .then(res => res.json())
    .then(counts => {
      quarantinePenPigCounts = counts;
      drawQuarantineFloorPlan();
    });
}

// When opening modal, set counts and fetch floor plan:
function openQuarantineModal(batchObj) {
  quarantineChosenPenId = null;
  document.getElementById('quarantine_batch_id').value = batchObj.batch_id;
  document.getElementById('quarantine_batch_id_display').value = batchObj.batch_id;
  document.getElementById('num_quarantined').value = batchObj.total_pigs;
  document.getElementById('num_male_quarantine').value = batchObj.male_count;
  document.getElementById('num_female_quarantine').value = batchObj.female_count;
  document.getElementById('is_full_batch').value = "1";
  document.getElementById('symptoms').value = '';
  document.getElementById('quarantine_notes').value = '';
  document.getElementById('chosenQuarantinePenInfo').innerText = "(No pen selected)";
  document.getElementById('quarantine_pen_id').value = '';
  document.getElementById('quarantineModal').style.display = 'block';
  document.getElementById('quarantine_numbers_section').style.display = "none";
  fetchQuarantinePensAndCounts();
  setupQuarantineFloorPlanSelect();
}

function closeQuarantineModal() {
  document.getElementById('quarantineModal').style.display = 'none';
  quarantineChosenPenId = null;
}

document.getElementById('is_full_batch').addEventListener('change', function() {
  if(this.value === "1") {
    document.getElementById('quarantine_numbers_section').style.display = "none";
  } else {
    document.getElementById('quarantine_numbers_section').style.display = "";
  }
});

// Validate pen selection before submit
document.getElementById('quarantineForm').addEventListener('submit', function(e) {
  if (!document.getElementById('quarantine_pen_id').value) {
    document.getElementById('quarantinePenError').innerText = "Please select a pen from the floor plan!";
    document.getElementById('quarantinePenError').style.display = "block";
    e.preventDefault();
    return false;
  }
});
</script>