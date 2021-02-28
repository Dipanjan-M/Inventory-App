<?php

require_once('../../../private/initialize.php');

require_login();

$session->extend_window();

if(is_post_request()){
	$prod_id = $_POST['id'];
	$inc = $_POST['inc_amount'];
	$product = Product::find_by_id($prod_id);
	if(!$product) {
		echo "No product found.";
		exit(0);
	}
	$product->set_stock_val($product->total_stock + $inc);
	$res = $product->save();
	if(!$res) {
		echo json_encode($product->errors, JSON_PRETTY_PRINT);
		exit(0);
	}
	echo "Stock for the product with ID = " . $prod_id . " has been incremented by " . $inc . " units.";
}

?>