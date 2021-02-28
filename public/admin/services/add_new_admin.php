<?php 
require_once('../../../private/initialize.php');

require_login();

$session->extend_window();

if(is_post_request() && !empty($_POST['admin'])) {
	$success_string = '';

  	$args = $_POST['admin'];
  	$admin = new Admin($args);
  	$result = $admin->save();

  	if($result === true) {
    	$session->message('The admin was created successfully at '.date("d-m-Y h:i:s a"));
    	$success_string .= '<div class="alert alert-success alert-dismissible fade show">';
    	$success_string .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    	$success_string .= '<strong>Success!</strong>New Admin added successfully.</div>';
    	$status = [];
    	$status[] = $success_string;
    	echo json_encode($status, JSON_NUMERIC_CHECK);
  	} else {
  		$all_errors = [];
      $all_errors[] = display_errors($admin->errors);
    	echo json_encode($all_errors, JSON_NUMERIC_CHECK);
  	}

} else {
	echo "Not a valid post request.";
}

?>