<?php

require_once('../../../private/initialize.php');

require_login();

$session->extend_window();

if(is_post_request()) {
	// print_r($_POST);
	$final_result = [];
	$visitedAt = $_POST['date'];
	$customers = Customer::fetch_by_date_visited($visitedAt);
	// print_r($customers);
	if(!$customers) {
		echo "No customer found.";
		exit(0);
	}
	foreach ($customers as $customer) {
		$order['bill_id'] = $customer->bill_id;
		$order['discount'] = $customer->discount;
		$products = Sale::find_by_bill_id($customer->bill_id);
		$total_sale = 0;
		$total_cost = 0;
		foreach ($products as $product) {
			$total_sale += $product->unit_price * $product->quantity;
			$total_cost += $product->main_price * $product->quantity;
		}
		$order['selling_price'] = $total_sale;
		$order['buying_price'] = $total_cost;
		$final_result[] = $order;
	}
	// echo json_encode($final_result, JSON_PRETTY_PRINT);
	echo json_encode($final_result, JSON_NUMERIC_CHECK);
}

?>