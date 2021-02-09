<?php

class Product extends DbObject {

  static protected $table_name = "products";
  static protected $db_columns = ['id', 'p_name', 'unit_price', 'total_stock', 'category', 'updatedAt', 'createdAt'];

  public $id;
  public $p_name;
  public $unit_price;
  public $total_stock;
  public $category;
  public $updatedAt;
  public $createdAt;

  public function __construct($args=[]) {
    $this->p_name = $args['p_name'] ?? '';
    $this->unit_price = $args['unit_price'] ?? '';
    $this->total_stock = $args['total_stock'] ?? '';
    $this->category = $args['category'] ?? '';
    $this->updatedAt = date("d-m-Y h:i:s a");
    $this->createdAt = date("d-m-Y h:i:s a");
  }

  // public function __destruct() {
  //   echo "Destructor called.";
  // }

  public function check_availability($query) {
    return ($this->total_stock >= $query);
  }

  public function get_stock_val() {
    return $this->total_stock;
  }

  public function set_stock_val($val) {
    $this->total_stock = $val;
    $this->updatedAt = date("d-m-Y h:i:s a");
  }

  protected function create() {
    return parent::create();
  }

  protected function update() {
    return parent::update();
  }

  protected function validate() {
    $this->errors = [];

    if(is_blank($this->p_name)) {
      $this->errors[] = "Product name cannot be blank.";
    } elseif (!has_length($this->p_name, array('min' => 4, 'max' => 255))) {
      $this->errors[] = "Product name must be between 4 and 255 characters.";
    } elseif (!has_unique_prod_name($this->p_name, $this->id ?? 0)) {
    	$this->errors[] = "No two product can have same name.";
    }

    if(is_blank($this->unit_price)) {
      $this->errors[] = "Unit price cannot be blank.";
    } elseif((float)$this->unit_price <= 0.00) {
      $this->errors[] = "Unit price must be greater than 0.";
    }

    if(is_blank($this->category)) {
      $this->errors[] = "Category cannot be blank.";
    }

    return $this->errors;
  }

  static public function find_by_prod_name($p_name) {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE p_name='" . self::$database->escape_string($p_name) . "'";
    $obj_array = static::find_by_sql($sql);
    if(!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

}

?>