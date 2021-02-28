<?php
  date_default_timezone_set('Asia/Kolkata');
  ob_start();
  define("FORWARD_SLASH", "/");
  $site_root = explode(FORWARD_SLASH, $_SERVER['PHP_SELF']);
  $www = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . FORWARD_SLASH . $site_root[1];
  define("WWW_ROOT", $www);
  define("PRIVATE_PATH", WWW_ROOT . "/private" . FORWARD_SLASH);
  define("PUBLIC_PATH", WWW_ROOT . "/public" . FORWARD_SLASH);

  require_once('db_credentials.php');
  require_once('db_functions.php');
  require_once('functions.php');
  require_once('error_functions.php');
  require_once('validation_functions.php');

  foreach(glob('classes/*.class.php') as $file) {
    require_once($file);
  }

  function my_autoload($class) {
    if(preg_match('/\A\w+\Z/', $class)) {
      include('classes/' . $class . '.class.php');
    }
  }
  spl_autoload_register('my_autoload');

  $database = db_connect();
  DbObject::set_database($database);

  $session = new Session;

?>


