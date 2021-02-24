<?php

class Category extends DbObject {

  static protected $table_name = "category";
  static protected $db_columns = ['id', 'cat_name', 'gst_percentage', 'addedBy', 'updatedAt', 'createdAt'];

  public $id;
  public $cat_name;
  public $gst_percentage;
  public $addedBy;
  public $updatedAt;
  public $createdAt;

  public function __construct($args=[]) {
    $this->cat_name = $args['cat_name'] ?? '';
    $this->gst_percentage = $args['gst_percentage'] ?? '';
    $this->addedBy = $args['admin_email'] ?? '';
    $this->updatedAt = date("d-m-Y h:i:s a");
    $this->createdAt = date('d-m-Y h:i:s a');
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

    if(is_blank($this->cat_name)) {
      $this->errors[] = "Category name cannot be blank.";
    } elseif (!has_length($this->cat_name, array('min' => 2, 'max' => 255))) {
      $this->errors[] = "Category name must be between 2 and 255 characters.";
    } elseif (!has_unique_cat_name($this->cat_name, $this->id ?? 0)) {
    	$this->errors[] = "No two catagory can have same name.";
    }

    if(is_blank($this->gst_percentage)) {
      $this->errors[] = "Gst_percentage cannot be blank.";
    }

    if(is_blank($this->addedBy)) {
      $this->errors[] = "Added By cannot be blank.";
    } elseif (!has_length($this->addedBy, array('max' => 255))) {
      $this->errors[] = "Added By must be less than 255 characters.";
    } elseif (!has_valid_email_format($this->addedBy)) {
      $this->errors[] = "Added by Email must be a valid format.";
    }

    return $this->errors;
  }

  static public function find_by_cat_name($cat_name) {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE cat_name='" . self::$database->escape_string($cat_name) . "'";
    $obj_array = static::find_by_sql($sql);
    if(!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

}

?>