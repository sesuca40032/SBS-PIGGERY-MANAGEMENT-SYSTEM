<?php
// Include the database connection and session management
include 'setting/system.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the form data
    $name = $_POST['name'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $description = $_POST['description'];

    // Validate required fields
    if (!empty($name) && !empty($type) && !empty($quantity) && !empty($unit)) {
        try {
            // Prepare the SQL query to insert data
            $stmt = $db->prepare("
                INSERT INTO feed_and_supplies (name, type, quantity, unit, description) 
                VALUES (:name, :type, :quantity, :unit, :description)
            ");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':unit', $unit);
            $stmt->bindParam(':description', $description);

            // Execute the query
            $stmt->execute();

            // Redirect back to the feed and supplies management page
            header("Location: Feed-and-supplies.php?success=1");
            exit;
        } catch (PDOException $e) {
            // Handle database errors
            die("Error: " . $e->getMessage());
        }
    } else {
        // Redirect back with an error if required fields are missing
        header("Location: Feed-and-supplies.php?error=1");
        exit;
    }
}
 if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Feed/Supply added successfully!</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Please fill in all required fields!</div>
<?php endif; ?>
