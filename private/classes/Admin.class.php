<?php

class Admin extends DbObject {

  static protected $table_name = "admins";
  static protected $db_columns = ['id', 'f_name', 'l_name', 'admin_email', 'enc_password', 'createdAt'];

  public $id;
  public $f_name;
  public $l_name;
  public $admin_email;
  protected $enc_password;
  public $createdAt;
  public $password;
  public $confirm_password;
  protected $password_required = true;

  public function __construct($args=[]) {
    $this->f_name = $args['f_name'] ?? '';
    $this->l_name = $args['l_name'] ?? '';
    $this->admin_email = $args['email'] ?? '';
    $this->password = $args['password'] ?? '';
    $this->confirm_password = $args['confirm_password'] ?? '';
    $this->createdAt = date('d-m-Y h:i:s a');
  }

  // public function __destruct() {
  //   echo "Destructor called.";
  // }

  public function full_name() {
    return $this->f_name . " " . $this->l_name;
  }

  protected function set_hashed_password() {
    $this->enc_password = password_hash($this->password, PASSWORD_BCRYPT);
  }

  public function verify_password($password) {
    return password_verify($password, $this->enc_password);
  }

  protected function create() {
    $this->set_hashed_password();
    return parent::create();
  }

  protected function update() {
    if($this->password != '') {
      $this->set_hashed_password();
      // validate password
    } else {
      // password not being updated, skip hashing and validation
      $this->password_required = false;
    }
    return parent::update();
  }

  protected function validate() {
    $this->errors = [];

    if(is_blank($this->f_name)) {
      $this->errors[] = "First name cannot be blank.";
    } elseif (!has_length($this->f_name, array('min' => 2, 'max' => 255))) {
      $this->errors[] = "First name must be between 2 and 255 characters.";
    }

    if(is_blank($this->l_name)) {
      $this->errors[] = "Last name cannot be blank.";
    } elseif (!has_length($this->l_name, array('min' => 2, 'max' => 255))) {
      $this->errors[] = "Last name must be between 2 and 255 characters.";
    }

    if(is_blank($this->admin_email)) {
      $this->errors[] = "Email cannot be blank.";
    } elseif (!has_length($this->admin_email, array('max' => 255))) {
      $this->errors[] = "Email must be less than 255 characters.";
    } elseif (!has_valid_email_format($this->admin_email)) {
      $this->errors[] = "Email must be a valid format.";
    } elseif (!has_unique_email($this->admin_email, $this->id ?? 0)) {
      $this->errors[] = "This email id already been registered. Try another.";
    }

    if($this->password_required) {
      if(is_blank($this->password)) {
        $this->errors[] = "Password cannot be blank.";
      } elseif (!has_length($this->password, array('min' => 8))) {
        $this->errors[] = "Password must contain 8 or more characters";
      } elseif (!preg_match('/[A-Z]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 uppercase letter";
      } elseif (!preg_match('/[a-z]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 lowercase letter";
      } elseif (!preg_match('/[0-9]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 number";
      } elseif (!preg_match('/[^A-Za-z0-9\s]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 symbol";
      }

      if(is_blank($this->confirm_password)) {
        $this->errors[] = "Confirm password cannot be blank.";
      } elseif ($this->password !== $this->confirm_password) {
        $this->errors[] = "Password and confirm password must match.";
      }
    }

    return $this->errors;
  }

  static public function find_by_email($email) {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE admin_email='" . self::$database->escape_string($email) . "'";
    $obj_array = static::find_by_sql($sql);
    if(!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

}

?>
