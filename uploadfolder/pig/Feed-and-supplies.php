<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Feed and Supplies Management</b></h5>
  </header>

  <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
      <h2>Manage Feed and Supplies</h2>
      <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#addModal">
        <i class="fa fa-plus"></i> Add New Feed/Supply
      </button>
      <br><br>

      <div class="table-responsive">
        <table class="table table-hover table-striped" id="table">
          <thead>
            <tr>
              <th>S/N</th>
              <th>Name</th>
              <th>Type</th>
              <th>Quantity</th>
              <th>Unit</th>
              <th>Date Added</th>
              <th>Description</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $all_supplies = $db->query("SELECT * FROM feed_and_supplies ORDER BY id DESC");
            $fetch = $all_supplies->fetchAll(PDO::FETCH_OBJ);
            foreach ($fetch as $data) {
            ?>
              <tr>
                <td><?php echo $data->id ?></td>
                <td><?php echo $data->name ?></td>
                <td><?php echo $data->type ?></td>
                <td><?php echo $data->quantity ?></td>
                <td><?php echo $data->unit ?></td>
                <td><?php echo $data->date_added ?></td>
                <td><?php echo wordwrap($data->description, 300, '<br>'); ?></td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cog"></i> Option
                      <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li><a href="edit-supply.php?id=<?php echo $data->id ?>"><i class="fa fa-edit"></i> Edit</a></li>
                      <li><a onclick="return confirm('Continue delete feed/supply?')" href="delete-supply.php?id=<?php echo $data->id ?>"><i class="fa fa-trash"></i> Delete</a></li>
                    </ul>
                  </div>
                </td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Add Feed/Supply Modal -->
  <div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Feed/Supply</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="add-supply-handler.php">
            <div class="form-group">
              <label>Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Type</label>
              <input type="text" name="type" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Quantity</label>
              <input type="number" name="quantity" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Unit</label>
              <input type="text" name="unit" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>

<?php include 'theme/foot.php'; ?>
