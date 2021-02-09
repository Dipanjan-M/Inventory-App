<?php
require_once('../../private/initialize.php');

require_login();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Piyamotors | Reports</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

	<!-- Fontawesome 5.7.0 -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

	<!-- Local external style sheet -->
	<link rel="stylesheet" href="assets/CSS/style_index.css">
</head>
<body>
	<?php 
    	include("core/menu.php"); 
    	include("core/header.php");
  	?>

  	<div class="container-fluid">
		<div class="row pt-3">
			<div class="col-sm-4">
				<div class="text-center">
					<h2><i class="fas fa-flag-checkered text-danger"></i> Reports</h2>
				</div><br>
			</div>
			<div class="col-sm-8"></div>
		</div>
	</div>

	<script src="assets/JS/common_js.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
    		$('[data-toggle="tooltip"]').tooltip();
    		var session_msg = '<?php echo $session->message(); ?>';
    		if (session_msg != '') {
        		alert("System says : " + session_msg);
        		<?php $session->clear_message(); ?>
    		}
		});
	</script>
</body>
</html>