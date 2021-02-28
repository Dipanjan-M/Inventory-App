<?php

class Session {

  private $id;
  public $first_name;
  public $last_name;
  public $email;
  private $last_login;

  public const MAX_LOGIN_AGE = 60*60*1; // 1 hour

  public function __construct() {
    session_start();
    $this->check_stored_login();
  }

  public function login($user) {
    if($user) {
      session_regenerate_id();
      $this->id = $_SESSION['id'] = $user->id;
      $this->first_name = $_SESSION['first_name'] = $user->f_name;
      $this->last_name = $_SESSION['last_name'] = $user->l_name;
      $this->email = $_SESSION['email'] = $user->admin_email;
      $this->last_login = $_SESSION['last_login'] = time();
    }
    return true;
  }

  public function is_logged_in() {
    return isset($this->id) && $this->last_login_is_recent();
  }

  public function logout() {
    unset($_SESSION['id']);
    unset($_SESSION['first_name']);
    unset($_SESSION['last_name']);
    unset($_SESSION['email']);
    unset($_SESSION['last_login']);
    unset($this->id);
    unset($this->first_name);
    unset($this->last_name);
    unset($this->email);
    unset($this->last_login);
    session_unset();
    session_destroy();
    return true;
  }

  private function check_stored_login() {
    if(isset($_SESSION['id'])) {
      $this->id = $_SESSION['id'];
      $this->first_name = $_SESSION['first_name'];
      $this->last_name = $_SESSION['last_name'];
      $this->email = $_SESSION['email'];
      $this->last_login = $_SESSION['last_login'];
    }
  }

  private function last_login_is_recent() {
    if(!isset($this->last_login)) {
      return false;
    } elseif(($this->last_login + self::MAX_LOGIN_AGE) < time()) {
      return false;
    } else {
      return true;
    }
  }

  public function message($msg="") {
    if(!empty($msg)) {
      // Then this is a "set" message
      $_SESSION['message'] = $msg;
      return true;
    } else {
      // Then this is a "get" message
      return $_SESSION['message'] ?? '';
    }
  }

  public function clear_message() {
    unset($_SESSION['message']);
  }

  public function get_full_name() {
    return $this->first_name . " " . $this->last_name;
  }

  public function get_id() {
    return $this->id;
  }

  public function extend_session($reborn_admin) {
    if($this->last_login_is_recent()) {
      session_unset();
      return $this->login($reborn_admin);
    } else {
      return false;
    }
  }

  public function extend_window() {
    $this->last_login = $_SESSION['last_login'] = time();
  }
}

?>
