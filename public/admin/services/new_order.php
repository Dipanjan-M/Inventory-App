<?php
require_once('../../../private/initialize.php');

require_login();

$session->extend_window();

if(is_post_request()) {
	$cust_args['bill_id'] = generateRandomString(5) . '-' . time();
	$common_id = $cust_args['bill_id'];
	// $cust_args['bill_id'] = gen_uuid();
	$cust_args['discount'] = $_POST['order']['discount'];
	$cust_args['cust_name'] = $_POST['order']['customer_name'];
	$cust_args['cust_mobile'] = $_POST['order']['customer_mobile'];
	$cust_args['cust_email'] = $_POST['order']['customer_email'];
	$cust_args['hfsl'] = $_POST['order']['address']['hfsl'];
	$cust_args['avt'] = $_POST['order']['address']['avt'];
	$cust_args['district'] = $_POST['order']['address']['district'];
	$cust_args['zip'] = $_POST['order']['address']['zip'];
	$cust_args['state'] = $_POST['order']['address']['state'];
	$cust_args['country'] = $_POST['order']['address']['country'];
	$cust_args['visitedAt'] = date("Y-m-d");
	// print_r($cust_args);
	$products = $_POST['order']['product'];
	// print_r($products);

	$customer = new Customer($cust_args);

	$result = $customer->save();

	if(!$result) {
		echo display_errors($customer->errors);
		exit(0);
	} else {
		$all_errors = [];
		$all_sale = [];
		foreach ($products as $product) {
			$sale_args['bill_id'] = $cust_args['bill_id'];
			$sale_args['p_name'] = $product['name'];
			$sale_args['quantity'] = $product['quantity'];
			$sale_args['main_price'] = $product['main_price'];
			$sale_args['unit_price'] = $product['unit_price'];
			$sale_args['tax'] = $product['tax'];
			$all_sale[] = new Sale($sale_args);
			$result = $all_sale[count($all_sale)-1]->save();
			if(!$result) {
				$all_errors[] = $all_sale[count($all_sale)-1]->errors;
				array_pop($all_sale);
				foreach ($all_sale as $sale) {
					$sale->delete();
				}
				$customer->delete();
				$err_string = '';
				foreach ($all_errors as $each_error) {
					$err_string .= display_errors($each_error);
				}
				echo $err_string;
				exit(0);
			} else {
				continue;
			}
		}
		$db_customer = Customer::find_by_bill_id($common_id);
		$db_sales = Sale::find_by_bill_id($common_id);
		$db_confirm_bill['billing_customer']['id'] = $db_customer->id;
		$db_confirm_bill['billing_customer']['bill_id'] = $db_customer->bill_id;
		$db_confirm_bill['billing_customer']['discount'] = $db_customer->discount;
		$db_confirm_bill['billing_customer']['name'] = $db_customer->cust_name;
		$db_confirm_bill['billing_customer']['phone'] = $db_customer->cust_mobile;
		$db_confirm_bill['billing_customer']['email'] = $db_customer->cust_email;
		$db_confirm_bill['billing_customer']['address'] = $db_customer->get_full_address();
		$db_confirm_bill['billing_customer']['date'] = date("d-m-Y h:i:s a");
		$db_confirm_bill['bill_details'] = $db_sales;
		echo json_encode($db_confirm_bill, JSON_NUMERIC_CHECK);
	}
} else {
	exit(0);
}
?>