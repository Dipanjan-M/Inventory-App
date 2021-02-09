<?php

class Search_product extends DbObject {
	public $id;
	public $p_name;
	public $unit_price;
	public $total_stock;
	public $gst_percentage;

	public function key_sanitize($key) {
		return self::$database->real_escape_string($key);
	}
}

?>