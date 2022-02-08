<?php
require_once ('./MomoApi.php');
$momo = new MomoApi;
$response = $momo->init();
echo '<pre>';
print_r($response);
echo '</pre>';
die('');
