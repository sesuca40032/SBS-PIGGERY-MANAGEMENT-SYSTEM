<?php 
include 'setting/system.php'; 
include 'session.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = $db->prepare("SELECT sg.*, b.name as breed_name FROM sow_gilt_records sg 
                          LEFT JOIN breed b ON sg.breed_id = b.id 
                          WHERE sg.id = ?");
    $query->execute([$id]);
    $data = $query->fetch(PDO::FETCH_OBJ);
    
    if(!$data) {
        echo "Record not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}
?>

<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-paw"></i> Sow/Gilt Details</b></h5>
    </header>

    <div class="w3-container">
        <div class="w3-row">
            <div class="w3-col m4">
                <img width="200" height="200" src="<?php echo $data->picture; ?>" class="img img-responsive thumbnail">
                <br><br>
                <img width="200" height="200" src="qrcodes/sow_gilt_<?php echo $data->id; ?>.png" class="img img-responsive thumbnail">
            </div>
            
            <div class="w3-col m8">
                <table class="w3-table w3-striped w3-bordered">
                    <tr>
                        <th>Sow/Gilt ID</th>
                        <td><?php echo $data->id ?></td>
                    </tr>
                    <tr>
                        <th>Breed</th>
                        <td><?php echo $data->breed_name ?></td>
                    </tr>
                    <tr>
                        <th>Age</th>
                        <td><?php echo $data->age ?></td>
                    </tr>
                    <tr>
                        <th>Mating Date</th>
                        <td><?php echo $data->mating_date ?></td>
                    </tr>
                    <tr>
                        <th>Labor Date</th>
                        <td><?php echo $data->labor_date ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?php echo nl2br($data->description) ?></td>
                    </tr>
                </table>
                
                <br>
                <a href="edit-sow-gilt.php?id=<?php echo $data->id ?>" class="w3-button w3-blue">Edit</a>
                <a href="sow-gilt.php" class="w3-button w3-gray">Back to List</a>
            </div>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>