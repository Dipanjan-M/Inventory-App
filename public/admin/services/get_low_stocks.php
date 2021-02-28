<?php

require_once('../../../private/initialize.php');

require_login();

$session->extend_window();

$low_stock = Analytics::check_for_low_stock();

// echo count($low_stock);

echo json_encode($low_stock, JSON_NUMERIC_CHECK);

?>