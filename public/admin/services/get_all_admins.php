<?php
require_once('../../../private/initialize.php');

require_login();

$session->extend_window();

$admins = Admin::find_all();
$responce = [];
foreach ($admins as $admin) {
	$responce[] = $admin;
}

echo json_encode($responce, JSON_NUMERIC_CHECK);
?>