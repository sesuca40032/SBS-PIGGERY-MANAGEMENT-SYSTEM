<?php
// Database queries remain the same
$pCount = $uCount = $bCount = $qCount = '';

$query = $db->query("SELECT * FROM pigs");
$pCount = $query->rowCount();

$quer = $db->query("SELECT * FROM breed");
$bCount = $quer->rowCount();

$que = $db->query("SELECT * FROM quarantine");
$qCount = $que->rowCount();

$qu = $db->query("SELECT * FROM admin");
$uCount = $qu->rowCount();
?>

<div class="dashboard-counters">
    <div class="counter-card pig-counter">
        <div class="counter-icon">
            <i class="fas fa-piggy-bank"></i>
        </div>
        <div class="counter-content">
            <div class="counter-value"><?php echo number_format($pCount); ?></div>
            <div class="counter-label">Pigs</div>
        </div>
        <div class="counter-footer">
            <a href="manage-pig.php">View All <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    <div class="counter-card quarantine-counter">
        <div class="counter-icon">
            <i class="fas fa-shield-virus"></i>
        </div>
        <div class="counter-content">
            <div class="counter-value"><?php echo number_format($qCount); ?></div>
            <div class="counter-label">Quarantine</div>
        </div>
        <div class="counter-footer">
            <a href="manage-quarantine.php">View All <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    <div class="counter-card breed-counter">
        <div class="counter-icon">
            <i class="fas fa-dna"></i>
        </div>
        <div class="counter-content">
            <div class="counter-value"><?php echo number_format($bCount); ?></div>
            <div class="counter-label">Breeds</div>
        </div>
        <div class="counter-footer">
            <a href="manage-breed.php">View All <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    <div class="counter-card user-counter">
        <div class="counter-icon">
            <i class="fas fa-users-cog"></i>
        </div>
        <div class="counter-content">
            <div class="counter-value"><?php echo number_format($uCount); ?></div>
            <div class="counter-label">Users</div>
        </div>
        <div class="counter-footer">
            <a href="add-user.php">View All <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<style>
.dashboard-counters {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    padding: 20px 0;
}

.counter-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
}

.counter-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.counter-icon {
    padding: 20px;
    font-size: 2.5rem;
    color: white;
    text-align: center;
}

.counter-content {
    padding: 20px;
    text-align: center;
    flex-grow: 1;
}

.counter-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.counter-label {
    font-size: 1.1rem;
    color: #666;
    font-weight: 500;
}

.counter-footer {
    background: rgba(0, 0, 0, 0.03);
    padding: 12px 20px;
    text-align: center;
}

.counter-footer a {
    color: #555;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.counter-footer a:hover {
    color: #000;
}

.counter-footer i {
    margin-left: 5px;
    font-size: 0.8rem;
}

/* Specific counter colors */
.pig-counter .counter-icon { background: linear-gradient(135deg, #FF9A9E 0%, #FAD0C4 100%); }
.quarantine-counter .counter-icon { background: linear-gradient(135deg, #A1C4FD 0%, #C2E9FB 100%); }
.breed-counter .counter-icon { background: linear-gradient(135deg, #84FAB0 0%, #8FD3F4 100%); }
.user-counter .counter-icon { background: linear-gradient(135deg, #FFB347 0%, #FFCC33 100%); }

@media (max-width: 768px) {
    .dashboard-counters {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 480px) {
    .dashboard-counters {
        grid-template-columns: 1fr;
    }
}
</style>