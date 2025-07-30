<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>

<div class="container">
    <div class="row" style="margin-top: 10%">

        <h1 class="text-center"><?php echo NAME_X; ?> - Register</h1><br>
        <div class="col-md-2 col-md-offset-2">
            <img src="img/pig.png" class="img img-responsive">
        </div>
        <div class="col-md-4">
            <form method="post" autocomplete="off">
                <div class="form-group">
                    <label class="control-label">Admin username</label>
                    <input type="text" name="username" class="form-control input-sm" required>
                </div>

                <div class="form-group">
                    <label class="control-label">Admin Password</label>
                    <input type="password" name="password" class="form-control input-sm" required>
                </div>

                <button name="register" type="submit" class="btn btn-md btn-success">Register</button>
                <a href="index.php" class="btn btn-md btn-dark" style="margin-left: 10px;">Back to Login</a>
            </form>

            <?php
            if (isset($_POST['register'])) {
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $hash = sha1($password);  // Hash the password for security

                // Check if username already exists
                $check_query = $db->query("SELECT * FROM admin WHERE username = '$username' LIMIT 1");
                if ($check_query->rowCount() > 0) {
                    $error = 'Username already exists!';
                } else {
                    // Insert new admin user into the database
                    $insert_query = $db->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
                    $insert_query->execute([$username, $hash]);

                    $success = 'Registration successful! You can now log in.';
                }
            }

            if (isset($error)) { ?>
                <br><br>
                <div class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong><?php echo $error; ?>.</strong>
                </div>
            <?php }

            if (isset($success)) { ?>
                <br><br>
                <div class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong><?php echo $success; ?>.</strong>
                </div>
            <?php }
            ?>
        </div>
    </div>
</div>

<?php include 'theme/foot.php'; ?>
