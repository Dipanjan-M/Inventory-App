<?php

require_once('../../private/initialize.php');

require_login();

$session->extend_window();

$orders_to_return = [];

$customers = Customer::find_all();

foreach ($customers as $customer) {
	$joined_result['customer_details']['name'] = $customer->cust_name;
	$joined_result['customer_details']['email'] = $customer->cust_email;
	$joined_result['customer_details']['mobile'] = $customer->cust_mobile;
	$joined_result['customer_details']['address'] = $customer->get_full_address();
	$joined_result['customer_details']['bill_id'] = $customer->bill_id;
	$joined_result['customer_details']['date'] = $customer->visitedAt;
	$orders = Sale::find_by_bill_id($customer->bill_id);
	$joined_result['customer_details']['orders'] = $orders;
	$orders_to_return[] = $joined_result;
}

echo json_encode($orders_to_return, JSON_NUMERIC_CHECK);

// echo "<pre>";
// print_r(json_encode($orders_to_return, JSON_PRETTY_PRINT));
// echo "</pre>";

?>