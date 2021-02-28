<?php

class Customer extends DbObject {
	static protected $table_name = "customer";
  	static protected $db_columns = [	
  		'id', 
  		'bill_id',
      'discount', 
  		'cust_name', 
  		'cust_mobile', 
  		'cust_email', 
  		'hfsl', 
  		'avt', 
  		'district', 
  		'zip', 
  		'state', 
  		'country',
      'visitedAt'
  	];

  	public $id;
  	public $bill_id;
    public $discount;
  	public $cust_name;
  	public $cust_mobile;
  	public $cust_email;
  	public $hfsl;
  	public $avt;
  	public $district;
  	public $zip;
  	public $state;
  	public $country;
    public $visitedAt;

  public function __construct($args=[]) {
  	foreach ($args as $key => $value) {
  		$this->$key = $value ?? '';
  	}
  }

  public function get_full_address() {
  	return $this->hfsl . ', ' . $this->avt . '<br>' . $this->district . '-' . $this->zip . ', ' . $this->state . ', ' .$this->country;
  }

  // public function __destruct() {
  //   echo "Destructor called.";
  // }

  protected function create() {
    return parent::create();
  }

  protected function update() {
    return parent::update();
  }

  protected function validate() {
    $this->errors = [];

    if(is_blank($this->bill_id)) {
    	$this->errors[] = "Bill ID can't be blank.";
    } elseif(!has_unique_bill_id($this->bill_id, $this->id ?? 0)) {
    	$this->errors[] = "Bill ID isn't unique.";
    }

    if(!preg_match('[^\d+(.{1})\d{2}$]' ,$this->discount)) {
      $this->errors[] = "Discount not is in valid format. Invalid format = .00, .0, 12, 12.0, 0.0. 10e1 etc.";
    }

    if(!is_blank($this->cust_email)) {
    	if(!has_valid_email_format($this->cust_email)) {
    		$this->errors[] = "Customer email is not in a valid format.";
    	}
    }

    if(is_blank($this->cust_name)) {
    	$this->errors[] = "Customer name can't be blank.";
    }

    if(is_blank($this->cust_mobile)) {
    	$this->errors[] = "Customer mobile number can't be blank.";
    } elseif(preg_match('~\D~' ,$this->cust_mobile)) {
    	$this->errors[] = "Mobile number must not contain any non-digit character.";
    }

    if(is_blank($this->hfsl)) {
    	$this->errors[] = "We need any of these following : House No./ Flat No./ Street/ Landmark.";
    }

    if(is_blank($this->avt)) {
    	$this->errors[] = "We need any of these following : Area/ Village/ Town.";
    }

    if(is_blank($this->district)) {
    	$this->errors[] = "Please enter district name in corresponding address field.";
    }

    if(is_blank($this->zip)) {
    	$this->errors[] = "ZIP code can't be blank.";
    } elseif(preg_match('~\D~', $this->zip)) {
    	$this->errors[] = "ZIP code must not contain any non-digit character.";
    }

    if(is_blank($this->state)) {
    	$this->errors[] = "Please enter state name in corresponding address field.";
    }

    if(is_blank($this->country)) {
    	$this->errors[] = "Please enter country name in corresponding address field.";
    }

    return $this->errors;
  }

  static public function find_by_bill_id($b_id) {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE bill_id='" . self::$database->escape_string($b_id) . "'";
    $obj_array = static::find_by_sql($sql);
    if(!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  static public function fetch_by_date_visited($date) {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE visitedAt='" . self::$database->escape_string($date) . "'";
    $obj_array = static::find_by_sql($sql);
    if(!empty($obj_array)) {
      return $obj_array;
    } else {
      return false;
    }
  }

  static public function lazy_load($offset) {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "ORDER BY `id` DESC LIMIT 5 OFFSET " . self::$database->escape_string($offset) . ";";
    $obj_array = static::find_by_sql($sql);
    if(!empty($obj_array)) {
      return $obj_array;
    } else {
      return false;
    }
  }
}

?>