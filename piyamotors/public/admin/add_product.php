<?php
require_once('../../private/initialize.php');

require_login();

$session->extend_window();

if(is_post_request() && !empty($_POST['product'])) {
	// print_r($_POST);
	$args = $_POST['product'];
	$product = new Product($args);
	$result = $product->save();
	if($result === true) {
		$success_string = '';
		$success_string .= '<div class="alert alert-success alert-dismissible fade show">';
    	$success_string .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    	$success_string .= '<strong>Success!</strong> New product added successfully.</div>';
    	$status = [];
    	$status[] = $success_string;
    	echo json_encode($status, JSON_NUMERIC_CHECK);
	} else {
		$all_errors = [];
		$all_errors[] = display_errors($product->errors);
		echo json_encode($all_errors, JSON_NUMERIC_CHECK);
	}
}
?>