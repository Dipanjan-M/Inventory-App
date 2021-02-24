<?php
require_once('../../private/initialize.php');
require_login();
$session->extend_window();

$all_errors = [];
if(is_post_request() && !empty($_POST['search_key'])) {
	$prod_name = Search_product::key_sanitize($_POST['search_key']);
	$prod_name = "%" . $prod_name . "%";
	// echo $prod_name;
	$sql = "SELECT `products`.`id`,`products`.`p_name`,`products`.`main_price`,`products`.`unit_price`,`products`.`vendor_price`,`products`.`total_stock`,`category`.`gst_percentage` FROM `products`,`category` WHERE `products`.`p_name` LIKE '$prod_name' AND `products`.`total_stock`>0  AND `products`.`category` = `category`.`cat_name`;";
	$response = Search_product::find_by_sql($sql);
	if(!empty($response)) {
		echo json_encode($response, JSON_NUMERIC_CHECK);
	} else {
		$all_errors[] = "No such product found.";
		echo display_errors($all_errors);
	}
}

?>