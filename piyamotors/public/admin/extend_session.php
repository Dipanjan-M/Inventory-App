<?php 
require_once('../../private/initialize.php');

require_login();

$reborn = Admin::find_by_id($session->get_id());

if($session->extend_session($reborn)) {
	echo "Session has been extented for another one hour.";
} else {
	echo "Error occured.";
}

?>