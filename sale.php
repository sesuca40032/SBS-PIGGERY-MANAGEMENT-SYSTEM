<?php
include 'setting/system.php';
include 'theme/head.php';
include 'theme/sidebar.php';
include 'session.php';

// --- Fetch batches for selection ---
$batch_query = $db->query("SELECT * FROM pig_batches WHERE status='active' ORDER BY batch_date DESC");
$batches = $batch_query->fetchAll(PDO::FETCH_OBJ);

// --- Prepare a batch_id to pigs map for validation ---
$batch_pig_map = [];
foreach ($batches as $b) {
    $batch_pig_map[$b->batch_id] = $b->total_pigs;
}

// --- Handle Sale Submission ---
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_sale'])) {
    $sale_date = $_POST['sale_date'];
    $buyer = $_POST['buyer'];
    $remarks = $_POST['remarks'] ?? '';
    $sale_items = $_POST['sale_items'];

    $total_pigs = 0;
    $total_liveweight = 0;
    $total_income = 0;
    $batch_details = [];

    foreach ($sale_items as $idx => $item) {
        $batch_id = $item['batch_id'];
        $pigs_sold = (int)$item['pigs_sold'];
        $weights = array_map('floatval', $item['weights']); // array of floats
        $liveweight_price = floatval($item['liveweight_price']);
        $feed_sacks = (int)$item['feed_sacks'];
        $feed_price = floatval($item['feed_price']);
        $med_expenses = floatval($item['med_expenses']);

        // Validation: batch exists, pigs sold <= total pigs in batch, weights count matches pigs_sold, weights > 0
        if (!isset($batch_pig_map[$batch_id])) {
            $errors[] = "Batch $batch_id does not exist.";
            continue;
        }
        $max_pigs = $batch_pig_map[$batch_id];
        if ($pigs_sold < 1 || $pigs_sold > $max_pigs) {
            $errors[] = "Batch $batch_id: Number of pigs sold ($pigs_sold) must be between 1 and $max_pigs.";
            continue;
        }
        if (count($weights) != $pigs_sold) {
            $errors[] = "Batch $batch_id: Number of weights (" . count($weights) . ") must match pigs sold ($pigs_sold).";
            continue;
        }
        foreach ($weights as $w) {
            if ($w <= 0) {
                $errors[] = "Batch $batch_id: All weights must be greater than 0.";
                break;
            }
        }
        if ($liveweight_price <= 0) {
            $errors[] = "Batch $batch_id: Liveweight price must be greater than 0.";
        }
        if ($feed_sacks < 0 || $feed_price < 0 || $med_expenses < 0) {
            $errors[] = "Batch $batch_id: Feed sacks, feed price, and medication expenses must not be negative.";
        }

        if ($errors) continue;

        $batch_liveweight = array_sum($weights);
        $income = $batch_liveweight * $liveweight_price;

        $batch_details[] = [
            'batch_id' => $batch_id,
            'pigs_sold' => $pigs_sold,
            'weights' => $weights,
            'liveweight_price' => $liveweight_price,
            'feed_sacks' => $feed_sacks,
            'feed_price' => $feed_price,
            'med_expenses' => $med_expenses,
            'income' => $income
        ];
        $total_pigs += $pigs_sold;
        $total_liveweight += $batch_liveweight;
        $total_income += $income;
    }

    if (!$errors) {
        // Save sale record
        $db->query("CREATE TABLE IF NOT EXISTS pig_sales (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sale_date DATE,
            buyer VARCHAR(255),
            remarks TEXT,
            total_pigs INT,
            total_liveweight FLOAT,
            total_income FLOAT,
            batch_details TEXT
        )");
        $stmt = $db->prepare("INSERT INTO pig_sales (sale_date, buyer, remarks, total_pigs, total_liveweight, total_income, batch_details)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $sale_date, $buyer, $remarks,
            $total_pigs, $total_liveweight, $total_income,
            json_encode($batch_details)
        ]);

        // Update pig_batches: subtract pigs sold
        foreach ($batch_details as $bd) {
            $batch_id = $bd['batch_id'];
            $pigs_sold = $bd['pigs_sold'];
            $update_stmt = $db->prepare("UPDATE pig_batches SET total_pigs = total_pigs - ? WHERE batch_id = ?");
            $update_stmt->execute([$pigs_sold, $batch_id]);
        }

        // Record liveweight price trend
        $db->query("CREATE TABLE IF NOT EXISTS liveweight_trends (
            id INT AUTO_INCREMENT PRIMARY KEY,
            trend_date DATE,
            price FLOAT
        )");
        foreach ($batch_details as $bd) {
            $trend_stmt = $db->prepare("INSERT INTO liveweight_trends (trend_date, price) VALUES (?, ?)");
            $trend_stmt->execute([$sale_date, $bd['liveweight_price']]);
        }

        // For receipt
        $last_sale_id = $db->lastInsertId();
        header("Location: sale.php?sale_id=$last_sale_id&success=1");
        exit;
    }
}

// --- If viewing a sale receipt ---
$sale = null;
if (isset($_GET['sale_id'])) {
    $sale_id = $_GET['sale_id'];
    $sale_stmt = $db->prepare("SELECT * FROM pig_sales WHERE id=?");
    $sale_stmt->execute([$sale_id]);
    $sale = $sale_stmt->fetch(PDO::FETCH_OBJ);
    if ($sale) {
        $sale->batch_details = json_decode($sale->batch_details, true);
    }
}

// --- History ---
$sales_history = $db->query("SELECT * FROM pig_sales ORDER BY sale_date DESC")->fetchAll(PDO::FETCH_OBJ);
foreach ($sales_history as $sh) {
    $sh->batch_details = json_decode($sh->batch_details, true);
}
?>

<style>
.dashboard-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(30,40,60,.08);
    padding: 28px 28px;
    margin-bottom: 24px;
}
.w3-table-all {
    border-radius: 10px;
    overflow: hidden;
}
.receipt-actions {
    display: flex;
    justify-content: center;
    gap: 18px;
    margin-top: 18px;
}
.official-receipt-header {
    text-align: center;
    margin-bottom: 8px;
}
.official-receipt-title {
    font-size: 2em;
    font-weight: bold;
    letter-spacing: 2px;
    color: #38598b;
    margin-bottom: 0;
}
.official-receipt-farm {
    font-size: 1.18em;
    font-weight: 500;
    letter-spacing: 1px;
}
.official-receipt-location {
    font-size: 1.09em;
    color: #444;
    margin-bottom: 14px;
}
.official-receipt-line {
    border: none;
    border-top: 2px solid #38598b;
    margin: 12px 0 20px 0;
}
.official-receipt-info {
    margin-bottom: 18px;
    font-size: 1.12em;
}
.official-receipt-table th {
    background: #38598b;
    color: #fff;
    font-size: 1.04em;
}
.official-receipt-table td {
    font-size: 1.04em;
    text-align: center;
    padding: 7px 6px;
}
.official-receipt-summary {
    font-size: 1.10em;
    margin-top: 18px;
    text-align: right;
}
@media print {
    .receipt-actions,
    .dashboard-analytics,
    .dashboard-history {
        display: none !important;
    }
    .dashboard-card {
        box-shadow: none !important;
        border: none !important;
        margin: 0 !important;
    }
}
</style>

<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
    <header class="dashboard-header">
        <div class="dashboard-row">
            <div class="dashboard-col dashboard-title">
                <h2><b><i class="fa fa-dollar"></i> Pig Sales</b></h2>
            </div>
        </div>
    </header>

    <div class="dashboard-card" style="margin:38px 38px 0 38px;">
        <?php if ($errors && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="alert alert-danger">
                <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['success']) && $sale): ?>
            <div class="alert alert-success"><b>Sale successfully recorded!</b></div>
        <?php endif; ?>

        <?php if ($sale): ?>
            <!-- Receipt Section -->
            <div id="saleReceipt" class="dashboard-card" style="max-width:760px;margin:30px auto;">
                <div class="official-receipt-header">
                    <div class="official-receipt-title">OFFICIAL RECEIPT</div>
                    <div class="official-receipt-farm">SESUCA PIGGERY INCORPORATION</div>
                    <div class="official-receipt-location">philippines, ilocos sur, cabugao, quezon</div>
                </div>
                <hr class="official-receipt-line">
                <div class="official-receipt-info">
                    <div><b>Date:</b> <?= htmlspecialchars($sale->sale_date) ?></div>
                    <div><b>Buyer:</b> <?= htmlspecialchars($sale->buyer) ?></div>
                    <?php if ($sale->remarks): ?>
                    <div><b>Remarks:</b> <?= htmlspecialchars($sale->remarks) ?></div>
                    <?php endif; ?>
                </div>
                <table class="w3-table-all w3-small official-receipt-table" style="margin-bottom:10px;">
                    <thead>
                        <tr>
                            <th>Batch ID</th>
                            <th>Pigs Sold</th>
                            <th>Weights (kg)</th>
                            <th>Liveweight Price (₱/kg)</th>
                            <th>Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sale->batch_details as $bd): ?>
                        <tr>
                            <td><?= htmlspecialchars($bd['batch_id']) ?></td>
                            <td><?= htmlspecialchars($bd['pigs_sold']) ?></td>
                            <td>
                                <?php foreach ($bd['weights'] as $w) echo htmlspecialchars($w).", "; ?>
                            </td>
                            <td>₱<?= number_format($bd['liveweight_price'],2) ?></td>
                            <td>₱<?= number_format($bd['income'],2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="official-receipt-summary">
                    <strong>Total Pigs Sold:</strong> <?= $sale->total_pigs ?><br>
                    <strong>Total Liveweight Sold:</strong> <?= number_format($sale->total_liveweight,2) ?> kg<br>
                    <strong>Grand Total Amount:</strong> <span style="color:#38598b;font-weight:bold;">₱<?= number_format($sale->total_income,2) ?></span>
                </div>
                <div style="margin-top:16px;text-align:right;font-style:italic;font-size:0.99em;">
                    Thank you for your business!<br>
                    SESUCA PIGGERY INCORPORATION
                </div>
                <div class="receipt-actions">
                    <button onclick="window.print();" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
                    <button onclick="downloadPDF('saleReceipt');" class="btn btn-success"><i class="fa fa-file-pdf"></i> Download PDF</button>
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
            <script>
            function downloadPDF(elementId) {
                const { jsPDF } = window.jspdf;
                var doc = new jsPDF('p','pt','a4');
                var content = document.getElementById(elementId);
                doc.html(content, {
                    callback: function (doc) {
                        doc.save("sale_receipt.pdf");
                    },
                    margin: [20,20,20,20],
                    autoPaging: true,
                    width: 750
                });
            }
            </script>
        <?php else: ?>
            <!-- Sale Form -->
            <h3>Record Sale</h3>
            <form method="POST" id="saleForm">
                <input type="hidden" name="submit_sale" value="1">
                <div style="margin-bottom:12px;">
                    <label><b>Date of Sale:</b></label>
                    <input type="date" name="sale_date" required class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div style="margin-bottom:12px;">
                    <label><b>Buyer:</b></label>
                    <input type="text" name="buyer" class="form-control" required>
                </div>
                <div style="margin-bottom:12px;">
                    <label><b>Remarks:</b></label>
                    <textarea name="remarks" class="form-control"></textarea>
                </div>
                <h4 style="margin-top:22px;">Sale Details (You can add multiple batches)</h4>
                <div id="saleItemsContainer"></div>
                <button type="button" class="btn btn-secondary" onclick="addSaleItem()">Add Batch to Sale</button>
                <div style="margin-top:22px;">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Sale</button>
                </div>
            </form>
            <script>
            var batches = <?php echo json_encode($batches); ?>;
            var batchPigMap = <?php echo json_encode($batch_pig_map); ?>;
            var saleItemIndex = 0;
            var selectedBatch = "<?php echo isset($_GET['batch_id']) ? $_GET['batch_id'] : "";?>";

            function addSaleItem() {
                var container = document.getElementById('saleItemsContainer');
                var idx = saleItemIndex++;
                var options = batches.map(b => `<option value="${b.batch_id}" ${selectedBatch==b.batch_id ? "selected":""}>${b.batch_id}</option>`).join('');
                var html = `
                <div class="dashboard-card" style="margin-bottom:8px;" id="saleItem_${idx}">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <b>Batch:</b>
                        <select name="sale_items[${idx}][batch_id]" class="form-control batch-select" required data-idx="${idx}" style="width:160px;" onchange="updateWeightFields(this)">
                            <option value="">Select batch</option>
                            ${options}
                        </select>
                        <button type="button" onclick="document.getElementById('saleItem_${idx}').remove();" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Remove</button>
                    </div>
                    <div style="margin-top:9px;display:flex;flex-wrap:wrap;gap:16px;">
                        <div>
                            <label>Pigs to Sale:</label>
                            <input type="number" name="sale_items[${idx}][pigs_sold]" min="1" required class="form-control pigs-sold" style="width:100px;" data-idx="${idx}" onchange="updateWeightFields(this)">
                            <span class="error-pigs" style="color:red;display:none;font-size:0.96em;"></span>
                        </div>
                        <div>
                            <label>Weights per Pig (kg):</label>
                            <div id="weightsInput_${idx}"></div>
                            <span class="error-weights" style="color:red;display:none;font-size:0.96em;"></span>
                        </div>
                        <div>
                            <label>Liveweight Price (₱/kg):</label>
                            <input type="number" step="0.01" name="sale_items[${idx}][liveweight_price]" min="0.01" required class="form-control" style="width:120px;">
                        </div>
                        <div>
                            <label>Feed Sacks (50kg):</label>
                            <input type="number" name="sale_items[${idx}][feed_sacks]" min="0" required class="form-control" style="width:80px;">
                        </div>
                        <div>
                            <label>Feed Price (₱/sack):</label>
                            <input type="number" step="0.01" name="sale_items[${idx}][feed_price]" min="0" required class="form-control" style="width:100px;">
                        </div>
                        <div>
                            <label>Medication Expenses (₱):</label>
                            <input type="number" step="0.01" name="sale_items[${idx}][med_expenses]" min="0" required class="form-control" style="width:100px;">
                        </div>
                    </div>
                </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
                updateWeightFields(document.querySelector(`#saleItem_${idx} .batch-select`));
            }

            function updateWeightFields(el) {
                var idx = el.getAttribute('data-idx');
                var batchSelect = document.querySelector(`#saleItem_${idx} .batch-select`);
                var pigsInput = document.querySelector(`#saleItem_${idx} .pigs-sold`);
                var errorPigs = document.querySelector(`#saleItem_${idx} .error-pigs`);
                var errorWeights = document.querySelector(`#saleItem_${idx} .error-weights`);
                var weightsDiv = document.getElementById(`weightsInput_${idx}`);

                var batchId = batchSelect.value;
                var maxPigs = batchPigMap[batchId] ? batchPigMap[batchId] : 0;
                var pigsCount = pigsInput.value ? parseInt(pigsInput.value) : 0;

                if (pigsCount > maxPigs) {
                    errorPigs.textContent = "Cannot exceed total pigs in batch (" + maxPigs + ").";
                    errorPigs.style.display = "inline";
                    pigsInput.value = maxPigs;
                    pigsCount = maxPigs;
                } else {
                    errorPigs.style.display = "none";
                }
                weightsDiv.innerHTML = "";
                for (var i = 0; i < pigsCount; i++) {
                    weightsDiv.innerHTML += `<input type="number" step="0.01" name="sale_items[${idx}][weights][${i}]" min="0.01" required class="form-control" style="width:80px;display:inline-block;margin-right:2px;" placeholder="Pig ${i+1}">`;
                }
            }

            document.getElementById('saleForm')?.addEventListener('submit', function(e){
                var valid = true;
                var cards = document.querySelectorAll('#saleItemsContainer > .dashboard-card');
                cards.forEach(function(card){
                    var batchSelect = card.querySelector('.batch-select');
                    var pigsInput = card.querySelector('.pigs-sold');
                    var errorPigs = card.querySelector('.error-pigs');
                    var errorWeights = card.querySelector('.error-weights');
                    var weights = card.querySelectorAll('[name^="sale_items"][name*="[weights]"]');
                    var batchId = batchSelect.value;
                    var maxPigs = batchPigMap[batchId] ? batchPigMap[batchId] : 0;
                    var pigsCount = pigsInput.value ? parseInt(pigsInput.value) : 0;
                    if (pigsCount < 1 || pigsCount > maxPigs) {
                        errorPigs.textContent = "Pigs to sale must be between 1 and " + maxPigs + ".";
                        errorPigs.style.display = "inline";
                        valid = false;
                    } else {
                        errorPigs.style.display = "none";
                    }
                    if (weights.length != pigsCount) {
                        errorWeights.textContent = "Number of weights must match pigs sold.";
                        errorWeights.style.display = "inline";
                        valid = false;
                    } else {
                        var weightsValid = true;
                        weights.forEach(function(w){
                            if (parseFloat(w.value) <= 0 || isNaN(parseFloat(w.value))) weightsValid = false;
                        });
                        if (!weightsValid) {
                            errorWeights.textContent = "All weights must be greater than 0.";
                            errorWeights.style.display = "inline";
                            valid = false;
                        } else {
                            errorWeights.style.display = "none";
                        }
                    }
                });
                if (!valid) e.preventDefault();
            });

            // Auto add batch if passed in URL
            if (selectedBatch) addSaleItem();
            </script>
        <?php endif; ?>
    </div>
    <div class="dashboard-card dashboard-history" style="margin:38px;">
        <h3><i class="fa fa-history"></i> Sales History</h3>
        <table class="w3-table-all w3-small">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Buyer</th>
                    <th>Remarks</th>
                    <th>Total Pigs Sold</th>
                    <th>Total Liveweight</th>
                    <th>Total Income</th>
                    <th>Batches Sold</th>
                    <th>Print</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales_history as $sh): ?>
                <tr>
                    <td><?= htmlspecialchars($sh->sale_date) ?></td>
                    <td><?= htmlspecialchars($sh->buyer) ?></td>
                    <td><?= htmlspecialchars($sh->remarks) ?></td>
                    <td><?= $sh->total_pigs ?></td>
                    <td><?= number_format($sh->total_liveweight,2) ?> kg</td>
                    <td>₱<?= number_format($sh->total_income,2) ?></td>
                    <td>
                        <?php foreach ($sh->batch_details as $bd): ?>
                            Batch <?= htmlspecialchars($bd['batch_id']) ?>: <?= $bd['pigs_sold'] ?> pigs, Weights: <?= implode(", ", $bd['weights']) ?>,
                            Price: ₱<?= number_format($bd['liveweight_price'],2) ?> <br>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="printSaleReceipt('historyReceipt<?= $sh->id ?>')"><i class="fa fa-print"></i></button>
                        <button class="btn btn-success btn-sm" onclick="downloadPDF('historyReceipt<?= $sh->id ?>')"><i class="fa fa-file-pdf"></i></button>
                        <div id="historyReceipt<?= $sh->id ?>" style="display:none;">
                            <div class="dashboard-card" style="max-width:760px;margin:0 auto;">
                            <div class="official-receipt-header">
                                <div class="official-receipt-title">OFFICIAL RECEIPT</div>
                                <div class="official-receipt-farm">SESUCA PIGGERY INCORPORATION</div>
                                <div class="official-receipt-location">philippines, ilocos sur, cabugao, quezon</div>
                            </div>
                            <hr class="official-receipt-line">
                            <div class="official-receipt-info">
                                <div><b>Date:</b> <?= htmlspecialchars($sh->sale_date) ?></div>
                                <div><b>Buyer:</b> <?= htmlspecialchars($sh->buyer) ?></div>
                                <?php if ($sh->remarks): ?>
                                <div><b>Remarks:</b> <?= htmlspecialchars($sh->remarks) ?></div>
                                <?php endif; ?>
                            </div>
                            <table class="w3-table-all w3-small official-receipt-table" style="margin-bottom:10px;">
                                <thead>
                                    <tr>
                                        <th>Batch ID</th>
                                        <th>Pigs Sold</th>
                                        <th>Weights (kg)</th>
                                        <th>Liveweight Price (₱/kg)</th>
                                        <th>Income</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sh->batch_details as $bd): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($bd['batch_id']) ?></td>
                                        <td><?= htmlspecialchars($bd['pigs_sold']) ?></td>
                                        <td>
                                            <?php foreach ($bd['weights'] as $w) echo htmlspecialchars($w).", "; ?>
                                        </td>
                                        <td>₱<?= number_format($bd['liveweight_price'],2) ?></td>
                                        <td>₱<?= number_format($bd['income'],2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="official-receipt-summary">
                                <strong>Total Pigs Sold:</strong> <?= $sh->total_pigs ?><br>
                                <strong>Total Liveweight Sold:</strong> <?= number_format($sh->total_liveweight,2) ?> kg<br>
                                <strong>Grand Total Amount:</strong> <span style="color:#38598b;font-weight:bold;">₱<?= number_format($sh->total_income,2) ?></span>
                            </div>
                            <div style="margin-top:16px;text-align:right;font-style:italic;font-size:0.99em;">
                                Thank you for your business!<br>
                                SESUCA PIGGERY INCORPORATION
                            </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function downloadPDF(elementId) {
    const { jsPDF } = window.jspdf;
    var doc = new jsPDF('p','pt','a4');
    var content = document.getElementById(elementId);
    content.style.display = 'block';
    doc.html(content, {
        callback: function (doc) {
            doc.save("sale_receipt.pdf");
            content.style.display = 'none';
        },
        margin: [20,20,20,20],
        autoPaging: true,
        width: 750
    });
}
function printSaleReceipt(elementId) {
    var printContent = document.getElementById(elementId).innerHTML;
    var printWindow = window.open('', '', 'height=700,width=1000');
    printWindow.document.write('<html><head><title>Print Receipt</title>');
    printWindow.document.write('<style>.dashboard-card{background:#fff;border-radius:14px;box-shadow:0 4px 24px rgba(30,40,60,.08);padding:28px 28px;margin-bottom:24px;}table{width:100%;border-collapse:collapse;}th,td{border:1px solid #ddd;padding:8px;}th{background:#38598b;color:#fff;}.receipt-actions, .dashboard-analytics, .dashboard-history{display:none !important;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(function(){printWindow.print();printWindow.close();}, 300);
}
</script>
<?php include 'theme/foot.php'; ?>