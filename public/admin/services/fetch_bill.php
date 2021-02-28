<?php

require_once('../../../private/initialize.php');

require_login();
$session->extend_window();

if(is_post_request() && $_POST['bill_id']) {
	$common_id = trim($_POST['bill_id']);
	$db_customer = Customer::find_by_bill_id($common_id);
	if(!$db_customer) {
		echo "No result found.";
		exit(0);
	}
	$db_sales = Sale::find_by_bill_id($common_id);
	foreach ($db_sales as $sale) {
		$db_confirm_bill['billing_customer']['date'] = $sale->addedAt;
	}
	$db_confirm_bill['billing_customer']['id'] = $db_customer->id;
	$db_confirm_bill['billing_customer']['bill_id'] = $db_customer->bill_id;
	$db_confirm_bill['billing_customer']['discount'] = $db_customer->discount;
	$db_confirm_bill['billing_customer']['name'] = $db_customer->cust_name;
	$db_confirm_bill['billing_customer']['phone'] = $db_customer->cust_mobile;
	$db_confirm_bill['billing_customer']['email'] = $db_customer->cust_email;
	$db_confirm_bill['billing_customer']['address'] = $db_customer->get_full_address();
	// $db_confirm_bill['billing_customer']['date'] = date("d-m-Y h:i:s a");

	$db_confirm_bill['bill_details'] = $db_sales;
	echo json_encode($db_confirm_bill, JSON_NUMERIC_CHECK);
}

?>