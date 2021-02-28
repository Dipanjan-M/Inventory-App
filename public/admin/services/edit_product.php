<?php
require_once('../../../private/initialize.php');
require_login();
$session->extend_window();

$all_errors = [];
if(is_post_request() && !empty($_POST['edit_product'])){
	// print_r($_POST);
	$args['id'] = $_POST['edit_product']['id'];
	foreach ($_POST['edit_product'] as $key => $value) {
		if($key == 'id') {continue;}
		$args[$key] = $value;
	}

	$args['updatedAt'] = date("d-m-Y h:i:s a");

	$product = Product::find_by_id($args['id']);

	if($product == false) {
		$error_string = '';
		$error_string .= '<div class="alert alert-danger alert-dismissible fade show">';
		$error_string .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		$error_string .= '<strong>Error !!</strong> Can\'t find the product requested.</div>';
		$all_errors[] = $error_string;
		echo json_encode($all_errors, JSON_NUMERIC_CHECK);
	} else {
		$product->merge_attributes($args);
		$result = $product->save();
		if($result == true) {
			$success_string = '';
			$success_string .= '<div class="alert alert-success alert-dismissible fade show">';
    		$success_string .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    		$success_string .= '<strong>Success!</strong> Product details updated successfully.</div>';
			$success[] = $success_string;
			echo json_encode($success, JSON_NUMERIC_CHECK);
		} else {
			$all_errors[] = display_errors($product->errors);
			echo json_encode($all_errors, JSON_NUMERIC_CHECK);
		}
	}
}
?>