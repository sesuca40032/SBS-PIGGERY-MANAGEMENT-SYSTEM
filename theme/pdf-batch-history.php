<?php
// $batch_data must be provided: ['batch' => $batch, 'health_records' => $health_records]
// Used for PDF and print output. Inline CSS for dompdf compatibility.
$batch = $batch_data['batch'];
$health_records = $batch_data['health_records'];

$birthDate = new DateTime($batch->birth_date);
$today = new DateTime();
$age = $today->diff($birthDate)->days;

if (!empty($batch->nursery_transfer_date)) {
    $nurseryDate = new DateTime($batch->nursery_transfer_date);
    $age_in_nursery = $today->diff($nurseryDate)->days;
    $stage = "Nursery";
    $progress = ($age_in_nursery / 21) * 100;
    if ($progress > 100) $progress = 100;
} else if ($age <= 21) {
    $stage = "Farrowing";
    $progress = ($age / 21) * 100;
} elseif ($age <= 42) {
    $stage = "Nursery";
    $progress = (($age - 21) / 21) * 100;
} elseif ($age <= 70) {
    $stage = "Grower";
    $progress = (($age - 42) / 28) * 100;
} else {
    $stage = "Finisher";
    $progress = min(100, (($age - 70) / 110) * 100);
}
if ($batch->total_pigs > 0) {
    $male_percentage = ($batch->male_count / $batch->total_pigs) * 100;
    $female_percentage = ($batch->female_count / $batch->total_pigs) * 100;
} else {
    $male_percentage = 0;
    $female_percentage = 0;
}

function safe($v) { return htmlspecialchars($v ?? '', ENT_QUOTES); }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Pig Batch History Report</title>
  <style>
    body { font-family: 'Segoe UI', 'Roboto', Arial, sans-serif; background: #fff; color: #222; font-size: 13.5px; }
    h1 { font-size: 2.1em; color: #38598b; margin-bottom: 0.1em; }
    h2 { font-size: 1.45em; color: #e91e63; margin-top: 1.5em; margin-bottom: 0.2em; }
    table { border-collapse: collapse; width: 100%; margin-bottom: 1em; }
    th, td { border: 1px solid #b4c7e7; padding: 7px 10px; text-align: left; vertical-align: top; }
    th { background: #f3f6fb; color: #38598b; font-weight: 700; }
    tr:nth-child(even) { background: #f7f8fa; }
    .progress-bar { background: #f0f0f0; border-radius: 7px; overflow: hidden; display: flex; height: 18px; margin-top: 2px; }
    .progress-male { background: #38598b; color: #fff; text-align: center; font-size: .98em; }
    .progress-female { background: #e91e63; color: #fff; text-align: center; font-size: .98em; }
    .badge { padding: 2px 10px; border-radius: 12px; font-weight: bold; display: inline-block; margin-right: 4px; font-size: 0.95em;}
    .badge-green { background: #4caf50; color: #fff; }
    .badge-orange { background: #ff9800; color: #fff; }
    .badge-grey { background: #aaa; color: #fff; }
    .badge-purple { background: #7c43bd; color: #fff; }
    .badge-blue { background: #2196f3; color: #fff; }
    .badge-teal { background: #009688; color: #fff; }
    .badge-pink { background: #e91e63; color: #fff; }
    .badge-red { background: #d32f2f; color: #fff; }
    .badge-small { font-size: 0.93em; padding: 2px 7px; }
    .photo-frame, .qrcode-frame { width: 60px; height: 60px; border-radius: 8px; border: 2px solid #dedede; background: #f0f0f0; overflow: hidden; display: inline-block; vertical-align: middle; margin-right: 7px; }
    .photo-frame img, .qrcode-frame img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }
    .section { margin-bottom: 28px; }
    .subtitle { font-weight: bold; color: #38598b; margin-top: 14px; margin-bottom: 6px; }
    .no-records { color: #d32f2f; font-style: italic; margin: 14px 0; }
    .page-footer { margin-top: 45px; text-align: center; color: #aaa; font-size: 0.95em; }
  </style>
</head>
<body>
  <h1>Pig Batch History Report</h1>
  <div class="section">
    <span class="subtitle">Batch Information</span>
    <table>
      <tr><th>Batch ID</th><td><?=safe($batch->batch_id)?></td></tr>
      <tr>
        <th>Photo / QR</th>
        <td>
          <span class="photo-frame">
            <img src="<?=safe($batch->photo ?: 'assets/default_batch.jpg')?>" alt="Batch Photo">
          </span>
          <?php if (!empty($batch->qr_code) && file_exists($batch->qr_code)): ?>
          <span class="qrcode-frame">
            <img src="<?=safe($batch->qr_code)?>" alt="QR Code">
          </span>
          <?php endif; ?>
        </td>
      </tr>
      <tr><th>Source</th><td><?=ucfirst(safe($batch->source))?></td></tr>
      <tr><th>Sow ID</th><td><?=safe($batch->sow_id ?: 'N/A')?></td></tr>
      <tr><th>Total Pigs</th><td><?=safe($batch->total_pigs)?></td></tr>
      <tr>
        <th>Gender Ratio</th>
        <td>
          <div class="progress-bar">
            <div class="progress-male" style="width:<?=$male_percentage?>%"><?=safe($batch->male_count)?>M</div>
            <div class="progress-female" style="width:<?=$female_percentage?>%"><?=safe($batch->female_count)?>F</div>
          </div>
        </td>
      </tr>
      <tr>
        <th>Age</th>
        <td>
            <?=$age?> days (Born: <?=$birthDate->format('M d, Y')?>)
        </td>
      </tr>
      <tr>
        <th>Status</th>
        <td>
          <span class="badge 
            <?php
              echo $batch->status == 'active' ? 'badge-green' : 
                   ($batch->status == 'quarantined' ? 'badge-orange' : 'badge-grey'); 
            ?>">
            <?=ucfirst(safe($batch->status))?>
          </span>
        </td>
      </tr>
      <tr>
        <th>Progress</th>
        <td>
          <span class="badge
            <?php
              echo $stage == 'Farrowing' ? 'badge-purple' : 
                   ($stage == 'Nursery' ? 'badge-blue' : 
                   ($stage == 'Grower' ? 'badge-teal' : 'badge-green')); 
            ?>"><?=safe($stage)?> (<?=round($progress)?>%)</span>
          <?php
            if (!empty($batch->nursery_transfer_date)) {
              echo '<span class="badge badge-small" style="background:#eee;color:#38598b;">Transferred ' . date('M d, Y', strtotime($batch->nursery_transfer_date)) . '</span>';
              if ($batch->nursery_transfer_type == 'early') {
                echo '<span class="badge badge-orange badge-small">Early</span>';
              } elseif ($batch->nursery_transfer_type == 'late') {
                echo '<span class="badge badge-red badge-small">Late</span>';
              } else {
                echo '<span class="badge badge-green badge-small">On Schedule</span>';
              }
            }
          ?>
        </td>
      </tr>
    </table>
  </div>
  <div class="section">
    <span class="subtitle">Recorded Piglet Health Information</span>
    <?php if (count($health_records)): ?>
    <table>
      <thead>
        <tr>
          <th>Date Recorded</th>
          <th>History / Notes</th>
          <th>Died</th>
          <th>Mortality Rate (%)</th>
          <th>Deformities</th>
          <th>Deformity Kind(s)</th>
          <th>Unhealthy Piglets</th>
          <th>Symptoms / Observations</th>
          <th>Cured?</th>
          <th>Date Cured</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($health_records as $record): ?>
        <tr>
          <td><?=date('M d, Y', strtotime($record->record_date))?></td>
          <td><?=safe($record->history)?></td>
          <td><?=safe($record->deceased_count)?></td>
          <td><?=safe($record->mortality_rate)?></td>
          <td><?=safe($record->deformities)?></td>
          <td><?=safe($record->deformity_kind)?></td>
          <td><?=safe($record->unhealthy_pigs)?></td>
          <td><?=safe($record->symptoms)?></td>
          <td><?=($record->cured ? 'Yes' : 'No')?></td>
          <td><?=($record->cure_date ? date('M d, Y', strtotime($record->cure_date)) : '-')?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="no-records">No piglet health records found for this batch.</div>
    <?php endif; ?>
  </div>
  <div class="page-footer">
    Generated on <?=date('F j, Y, H:i')?> | Batch: <?=safe($batch->batch_id)?>
  </div>
</body>
</html>