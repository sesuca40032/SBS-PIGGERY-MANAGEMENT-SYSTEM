<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once './setting/system.php';

$user_id = $_SESSION['id'] ?? 0;
$setting = $db->prepare("SELECT * FROM user_settings WHERE user_id = ?");
$setting->execute([$user_id]);
$user_setting = $setting->fetch(PDO::FETCH_ASSOC);

// Defaults
$theme = $user_setting['theme_mode'] ?? 'normal';
$font = $user_setting['font_style'] ?? 'Baumans';
$bgColor = $theme == 'dark' ? '#1e1e1e' : '#d4d8dd';
$textColor = $theme == 'dark' ? '#fff' : '#000';
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo NAME_; ?></title>
 <link rel="icon" type="image/x-icon" href="img/black-and-white-pig-logo-for-your-elegant-brand-vector.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">

<link rel="stylesheet" href="./plugin/w3.css">
<link rel="stylesheet" href="./plugin/bootstrap.min.css">
<script src="./plugin/jquery-2.2.4.min.js"></script>
<script src="./plugin/bootstrap.min.js"></script>
<link rel="stylesheet" href="./plugin/font-awesome.min.css">

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css">
<link href='https://fonts.googleapis.com/css?family=Baumans|Arial|Verdana|Georgia|Tahoma' rel='stylesheet'>

<style>
body {
    font-family: '<?php echo htmlspecialchars($font); ?>', sans-serif;
    background-color: <?php echo $bgColor; ?>;
    color: <?php echo $textColor; ?>;
}
.w3-white {
    background-color: <?php echo $theme == 'dark' ? '#2e2e2e' : '#fff'; ?> !important;
}
</style>

<script>
 $(document).ready(function(){
    $('#table').DataTable();
    $('#table_pig').DataTable();
 });
</script>

<script>
	$.fn.datepicker.defaults.format = "yyyy-mm-dd";
	$('.datepicker').datepicker();
</script>
</head>
<body>
	
