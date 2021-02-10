<?php

require_once('../../private/initialize.php');

require_login();

$session->extend_window();

echo json_encode(Analytics::last_7_days_sale(), JSON_NUMERIC_CHECK);

?>