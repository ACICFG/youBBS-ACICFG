<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

if($cur_user){
    setcookie("cur_uid", '', $timestamp-86400 * 365, '/');
    setcookie("cur_uname", '', $timestamp-86400 * 365, '/');
    setcookie("cur_ucode", '', $timestamp-86400 * 365, '/');
    header('location: /');
}else{
    header('location: /');
}
exit;

?>
