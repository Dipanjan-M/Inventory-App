<?php
require_once('../../../private/initialize.php');
require_login();
$session->extend_window();

if(is_post_request() && isset($_POST['Offset'])) {
	$offset = $_POST['Offset'];
	$products = Product::lazy_load($offset);
	if(!$products) {
		echo "All products are fetched.";
		exit(0);
	}
	$responce = [];
	foreach ($products as $product) {
		$responce[] = $product;
	}
	echo json_encode($responce, JSON_NUMERIC_CHECK);
} else {
	echo "not a post request";
}
?>