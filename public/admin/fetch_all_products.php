<?php
require_once('../../private/initialize.php');
require_login();
$session->extend_window();

$products = Product::find_all();
$responce = [];
foreach ($products as $product) {
	$responce[] = $product;
}

echo json_encode($responce, JSON_NUMERIC_CHECK);
?>