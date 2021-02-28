<?php
require_once('../../../private/initialize.php');
require_login();
$session->extend_window();

$categories = Category::find_all();
$responce = [];
foreach ($categories as $category) {
	$responce[] = $category;
}

echo json_encode($responce, JSON_NUMERIC_CHECK);
?>