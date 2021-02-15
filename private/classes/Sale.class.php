<?php

class Sale extends DbObject {
	static protected $table_name = "sale";
	static protected $db_columns = [
		'id', 
		'bill_id', 
		'p_name', 
		'quantity',
		'main_price', 
		'unit_price',
		'tax',
		'addedAt'	
	];

	public $id;
	public $bill_id;
	public $p_name;
	public $quantity;
	public $main_price;
	public $unit_price;
	public $tax;
	public $addedAt;

	public function __construct($args=[]) {
		foreach ($args as $key => $value) {
			$this->$key = $value;
		}
		$this->addedAt = date('m-d-Y h:i:s a');
	}

	protected function validate() {
		$this->errors = [];

		if(is_blank($this->p_name)) {
			$this->errors[] = "Product name can't be blank.";
		}

		if(is_blank($this->main_price)) {
			$this->errors[] = "Price per unit for " . $this->p_name . "can't be blank.";
		} elseif(!preg_match('[^\d+(.{1})\d{2}$]', $this->main_price)) {
			$this->errors[] = "Price per unit for " . $this->p_name . " not is in valid format. Invalid format = .00, .0, 12, 12.0 etc.";
		}

		if(is_blank($this->unit_price)) {
			$this->errors[] = "Price per unit for " . $this->p_name . "can't be blank.";
		} elseif(!preg_match('[^\d+(.{1})\d{2}$]', $this->unit_price)) {
			$this->errors[] = "Price per unit for " . $this->p_name . " not is in valid format. Invalid format = .00, .0, 12, 12.0 etc.";
		}

		if(is_blank($this->tax)) {
			$this->errors[] = "Tax value for ". $this->p_name ." can't be blank.";
		} elseif(!preg_match('[^\d+(.{1})\d{2}$]', $this->tax)) {
			$this->errors[] = "Tax for ". $this->p_name." not is in valid format. Invalid format = .00, .0, 12, 12.0 etc.";
		}

		if( $product = Product::find_by_prod_name($this->p_name)) {
			if(preg_match('~\D~', $this->quantity)) {
				$this->errors[] = "Quantity field for ".$this->p_name." can't have any non-digit character input.";
			} else {
				if(!$product->check_availability((int)$this->quantity)) {
					$this->errors[] = "Inventory has only " . (string)$product->get_stock_val() ." ". $this->p_name . " left now.";
				} else {
					$product->set_stock_val((int)$product->get_stock_val()-(int)$this->quantity);
					$product->save();
				}
			}
		} else {
			if(preg_match('~\D~', $this->quantity)) {
				$this->errors[] = "Quantity field for ".$this->p_name." can't have any non-digit character input.";
			}
		}

		return $this->errors;
	}

	static public function find_by_bill_id($b_id) {
    	$sql = "SELECT * FROM " . static::$table_name . " ";
    	$sql .= "WHERE bill_id='" . self::$database->escape_string($b_id) . "'";
    	$obj_array = static::find_by_sql($sql);
    	if(!empty($obj_array)) {
      		return $obj_array;
    	} else {
      		return false;
    	}
  	}
}

?>