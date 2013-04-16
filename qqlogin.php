<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

error_reporting(0);
session_start();

$_SESSION["scope"] = $options['qq_scope'];
$_SESSION["appid"]    = $options['qq_appid'];
$_SESSION["appkey"]   = $options['qq_appkey'];
$_SESSION["callback"] = 'http://'.$_SERVER['HTTP_HOST'].'/qqcallback';

function qq_login($appid, $scope, $callback)
{
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
    $login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" 
        . $appid . "&redirect_uri=" . urlencode($callback)
        . "&state=" . $_SESSION['state']
        . "&scope=".$scope;
    header("Location:$login_url");
}

//用户点击qq登录按钮调用此函数
qq_login($_SESSION["appid"], $_SESSION["scope"], $_SESSION["callback"]);
?>
