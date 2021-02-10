<?php

class Analytics extends DbObject {

	public static function get_stock_value() {
		$sql = "SELECT SUM(`products`.`unit_price`*`products`.`total_stock`) AS `stock_val` FROM `products`;";
		$result = Self::$database->query($sql);
		if(!$result) {
			$this->errors[] = "No data found in sale.";
			exit(0);
		} else{
			$row = $result->fetch_assoc();
			$result->free();
			$stock_val = $row['stock_val'];
			return $stock_val;
		}
	}

	public static function get_total_sale() {
		$sql = "SELECT SUM(`sale`.`quantity`*`sale`.`unit_price`) AS `total_sale` FROM `sale`;";
		$result = Self::$database->query($sql);
		if(!$result) {
			$this->errors[] = "No data found in sale.";
			exit(0);
		} else{
			$row = $result->fetch_assoc();
			$result->free();
			$total_sale = $row['total_sale'];
			return $total_sale;
		}
	}

	public static function last_7_days_sale() {
		$dates = [];

		for ($i=7; $i > 0; $i--) { 
			$str = '-' . $i . 'days';
			$dates[] = date("Y-m-d", strtotime($str));
		}

		$array_to_return['labels'] = $dates;
		$datas = [];
		foreach ($dates as $date) {
			$total = 0;
			$sql = "SELECT `customer`.`bill_id` FROM `customer` WHERE `customer`.`visitedAt`='$date';";
			$result = Self::$database->query($sql);
			if($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$bill_id = $row['bill_id'];
					$qry1 = "SELECT SUM(`sale`.`quantity`*`sale`.`unit_price`) AS `total_bill` FROM `sale` WHERE `sale`.`bill_id` = '$bill_id';";
					$res = Self::$database->query($qry1)->fetch_assoc();
					$total += $res['total_bill'];
				}
			}
			array_push($datas, $total);
		}
		$array_to_return['data'] = $datas;
		return $array_to_return;
	}
}

?>