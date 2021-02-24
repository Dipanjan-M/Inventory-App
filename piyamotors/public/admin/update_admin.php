<?php 
require_once('../../private/initialize.php');

require_login();

$session->extend_window();

if(is_post_request() && !empty($_POST['update'])){
	$args['id'] = $session->get_id();
	foreach ($_POST['update'] as $key => $value) {
		if(!empty($value)) {
			$args[$key] = $value;
		}
	}
	$all_errors = [];
	$admin = Admin::find_by_id($args['id']);
	if($admin == false) {
		echo json_encode($admin->errors, JSON_NUMERIC_CHECK);
	} else {
		$admin->merge_attributes($args);
		if(!$admin->verify_password($admin->password)){
			$admin->errors[] = "You entered wrong old password.";
			$all_errors[] = display_errors($admin->errors);
			echo json_encode($all_errors, JSON_NUMERIC_CHECK);
		} else {
			$admin->password = $args['new_pass'];
			$admin->confirm_password = $args['confirm_new_pass'];

			$result = $admin->save();
			if($result == true) {
				$success_string = '';
				$success_string .= '<div class="alert alert-success alert-dismissible fade show">';
    			$success_string .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    			$success_string .= '<strong>Success!</strong> Admin details updated successfully.</div>';
				$success[] = $success_string;
				echo json_encode($success, JSON_NUMERIC_CHECK);
			} else {
				$all_errors[] = display_errors($admin->errors);
				echo json_encode($all_errors, JSON_NUMERIC_CHECK);
			}
		}
	}
}

?>