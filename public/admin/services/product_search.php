<?php

require_once('../../../private/initialize.php');

require_login();

$session->extend_window();

if(is_post_request() && !empty($_POST['key'])) {
	$key = $_POST['key'];
	$products = Product::search_product_by_name($key);
	if(!$products) {
		echo "No product found like " . $key . ". Make sure you spelled it correctly.";
	} else {
		echo json_encode($products, JSON_NUMERIC_CHECK);
	}
}
?>