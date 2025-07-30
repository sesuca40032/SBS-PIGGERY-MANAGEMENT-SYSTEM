<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<?php 
if(isset($_POST['submit'])){
    $name = trim($_POST['breed']); // Trim whitespace from the input

    if(!empty($name)) {
        // Use a prepared statement to safely insert data
        $query = $db->prepare("INSERT INTO breed(name) VALUES(:name)");
        $result = $query->execute([':name' => $name]);

        if($result){
            echo "<script>alert('Breed Added. Click OK to close dialogue.');</script>";
            header('refresh: 1.5; url=' . $_SERVER['PHP_SELF']); // Refresh current page
            exit;
        } else {
            echo "<script>alert('Error adding breed. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Breed name cannot be empty.');</script>";
    }
}
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Pig Management</b></h5>
  </header>
 
 <?php include 'inc/data.php'; ?>

 <div class="w3-container" style="padding-top:22px">
	 <div class="w3-row">
	 	<h2>Pig Breeds</h2>
	 	<div class="col-md-6">
	 		<a title="Check to delete from list" data-toggle="modal" data-target="#_removed" id="delete" class="btn btn-danger"><i class="fa fa-trash"></i></a>
	 		<form method="post" action="delete_breed.php">
	 		<table class="table table-hover table-bordered" id="table">
	 			<thead>
	 				<tr>
	 					<th></th>
	 					<th>ID</th>
	 					<th>Name</th>
	 				</tr>
	 			</thead>
	 			<tbody>
	 				<?php
	 				$get = $db->query("SELECT * FROM breed");
	 				$res = $get->fetchAll(PDO::FETCH_OBJ);
	 				foreach($res as $n){ ?>
                         <tr>
                         	<td>
                         		<input type="checkbox" name="selector[]" value="<?php echo $n->id ?>">
                         	</td>
                         	<td> <?php echo $n->id; ?> </td>
                         	<td>  <?php echo $n->name; ?> </td>
                         </tr> 
	 				<?php }
	 				?>
	 			</tbody>
	 		</table>

	 		<?php include('inc/modal-delete.php'); ?>
	 	</form>
	 	</div>

	 	<div class="col-md-6">
	 		<div class="panel panel-primary">
	 			<div class="panel-heading">Add New Breed</div>
	 			<div class="panel-body">
	 				<form method="post">
	 					<div class="form-group">
	 						<label class="control-label">Breed Name</label>
	 						<input type="text" name="breed" class="form-control" placeholder="Enter breed name" required>
	 					</div>

	 					<button class="btn btn-sm btn-default" type="submit" name="submit">Add</button>
	 				</form>
	 			</div>
	 		</div>
	 	</div>
	 </div>
</div>

</div>

<?php include 'theme/foot.php'; ?>
