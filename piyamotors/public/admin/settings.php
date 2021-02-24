<?php
require_once('../../private/initialize.php');

require_login();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Piyamotors | Settings</title>

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
  <link rel="stylesheet" href="assets/CSS/style_settings.css">
</head>
<body>
  <div id="status-area"></div>
	<?php 
    include("core/menu.php"); 
    include("core/header.php");
  ?>

  <div class="container-fluid pt-3 p-3">
    <div class="row pt-3">
      <div class="col-sm-4">
        <div class="text-center">
          <h2><i class="fas fa-tools"></i> Settings</h2>
        </div><br>
        <div class="text-center">
          <button class="btn big-btn-info" onclick="open_update_form();">
            <h4>Update Info <i class="fas fa-cloud-upload-alt"></i></h4>
          </button>
        </div>
        <br>
        <div class="text-center">
          <button class="btn big-btn-add" onclick="open_add_admin_form();">
            <h4>Add new admin <i class="fas fa-user-plus"></i></h4>
          </button>
        </div>
        <br>
        <div class="text-center">
          <button class="btn big-btn-all-admins" onclick="open_all_admin_table('<?php echo $session->get_id(); ?>');">
            <h4>All Admins <i class="fas fa-user-friends"></i></h4>
          </button>
        </div>
      </div>

      <div class="col-sm-8">
        <!-- Div for update info form -->
        <div class="update-form" style="padding: 0vw 4vw 0vw 4vw;">
          <div style="text-align: right;font-size: 20px;">
            <span style="float: left;"><h4 style="padding-left: 22vw;">Update Info</h4></span>
            <span style="cursor: pointer;" onclick="$('.update-form').css('display','none');">
              <i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            </span>
          </div><br>
          <form action="update_admin.php" method="post" id="update-info-form">
            <div class="row">
              <div class="col">
                <label for="first_name">First Name<sup class="text-danger">*</sup></label><br>
                <input type="text" name="update[f_name]" class="form-control" value="<?php echo $session->first_name; ?>" required="">
              </div>
              <div class="col">
                <label for="last_name">Last Name<sup class="text-danger">*</sup></label><br>
                <input type="text" name="update[l_name]" class="form-control" value="<?php echo $session->last_name; ?>" required="">
              </div>
            </div><br>
            <label for="email">Email <small class="text-danger"> *Emails are unique and can't be modified.</small></label><br>
            <input type="email" name="update[admin_email]" class="form-control" value="<?php echo $session->email; ?>" readonly="true" required=""><br>
            <label for="old_password">Enter Old Password <sup class="text-danger">*</sup> </label>
            <i class="fa fa-eye" id="pass-1" onclick="toggle_password('pass-1','pass-inp-1');"></i><br>
            <input type="password" name="update[password]" class="form-control" required="" id="pass-inp-1"><br>
            <label for="new_password">Enter New Password <sup class="text-danger">*</sup> </label>
            <i class="fa fa-eye" id="pass-2" onclick="toggle_password('pass-2','pass-inp-2');"></i><br>
            <input type="password" name="update[new_pass]" class="form-control" required="" id="pass-inp-2"><br>
            <label for="conf_new_password">Confirm new Password <sup class="text-danger">*</sup> </label>
            <i class="fa fa-eye" id="pass-3" onclick="toggle_password('pass-3','pass-inp-3');"></i><br>
            <input type="password" name="update[confirm_new_pass]" class="form-control" required="" id="pass-inp-3"><br>
            <div class="text-center">
              <button id="btn-info-updt" class="btn btn-primary" type="submit" value="submit" name="update[push]">
                Update <i class="fas fa-cloud-upload-alt"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Div for add new admin form -->
        <div class="add-admin-form" style="padding: 0vw 4vw 0vw 4vw;">
          <div style="text-align: right;font-size: 20px;">
            <span style="float: left;"><h4 style="padding-left: 22vw;">Add Admin</h4></span>
            <span style="cursor: pointer;" onclick="$('.add-admin-form').css('display','none');">
              <i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            </span>
          </div><br>
          <form action="add_new_admin.php" method="post" id="add-admin-form">
            <div class="row">
              <div class="col">
                <label for="f_name">Enter First name <sup class="text-danger">*</sup></label><br>
                <input type="text" name="admin[f_name]" class="form-control" placeholder="First Name" required="">
              </div>
              <div class="col">
                <label for="l_name">Enter Last name <sup class="text-danger">*</sup></label><br>
                <input type="text" name="admin[l_name]" class="form-control" placeholder="Last Name" required="">
              </div>
            </div><br>
            <label for="email">Enter email <sup class="text-danger">*</sup></label><br>
            <input type="email" name="admin[email]" class="form-control" placeholder="example@email.com" required=""><br>
            <label for="password">Enter password <sup class="text-danger">*</sup></label>
            <i class="fa fa-eye" id="pass-4" onclick="toggle_password('pass-4','pass-inp-4');"></i><br>
            <input type="password" name="admin[password]" class="form-control" id="pass-inp-4" required=""><br>
            <label for="password-repeat">Confirm Password <sup class="text-danger">*</sup></label>
            <i class="fa fa-eye" id="pass-5" onclick="toggle_password('pass-5','pass-inp-5');"></i><br>
            <input type="password" name="admin[confirm_password]" class="form-control" id="pass-inp-5" required=""><br>
            <div class="text-center">
              <button id="btn-add-admin" class="btn btn-primary" type="submit" value="Create" name="admin[submit]">
                Add <i class="fas fa-user-plus"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Div for all admin listing -->
        <div class="all-admin-table p-3">
          <div style="text-align: right;font-size: 20px;">
            <span style="float: left;"><h4 style="padding-left: 22vw;">All Admins</h4></span>
            <span style="cursor: pointer;" onclick="$('.all-admin-table').css('display','none');">
              <i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            </span>
          </div><br>
          <table border="1" align="center" width="100%" id="all-admins"></table>
        </div>
      </div>
    </div>
  </div>

	<script src="assets/JS/common_js.js"></script>
  <script src="assets/JS/settings.js"></script>
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