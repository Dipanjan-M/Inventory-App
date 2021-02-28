<?php
require_once('../../../private/initialize.php');

require_login();

$session->extend_window();

$status = [];

if(is_post_request() && !empty($_POST['id'])) {
	$id = $_POST['id'];
	$cat = Category::find_by_id($id);

	if($cat == false) {
		$error_string = '';
		$error_string .= '<div class="alert alert-warning alert-dismissible fade show">';
    	$error_string .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    	$error_string .= '<strong>Error!!</strong> We are unable to find the category.</div>';
		$status[] = $error_string;
		echo json_encode($status, JSON_NUMERIC_CHECK);
	} else {
		$result = $cat->delete();
		$success_string = '';
		$success_string .= '<div class="alert alert-success alert-dismissible fade show">';
    	$success_string .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    	$success_string .= '<strong>Success !</strong> Selected category deleted successfully.</div>';
    	$status[] = $success_string;
    	echo json_encode($status, JSON_NUMERIC_CHECK);
	}
}

?>