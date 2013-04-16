<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

// 
$via = $_GET['via'];
if($via && $is_mobie){
    setcookie('vtpl', $via, $timestamp+86400 * 365, '/');
}
header('location: /');
exit;

?>
