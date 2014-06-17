<?php
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied');

if ($_COOKIE['lastrequest']) {
    list($lastrequest,$lastpath) = explode("\t",$_COOKIE['lastrequest']);
    $onlinetime = $timestamp - $lastrequest;
} else {
    $lastrequest = $lastpath = '';
}
//$REQUEST_URI  = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
$REQUEST_URI  = $_SERVER["REQUEST_URI"];
if ($REQUEST_URI == $lastpath && $onlinetime < 2) {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Refresh" content="2;url=<?php echo $REQUEST_URI;?>">
<title>Refresh Limitation Enabled</title>
</head>
<body style="table-layout:fixed; word-break:break-all">
<center>
<div style="margin-top:100px;background-color:#f1f1f1;text-align:center;width:600px;padding:20px;margin-right: auto;margin-left: auto;font-family: Verdana, Tahoma; color: #666666; font-size: 12px">
  <p><b>Refresh Limitation Enabled</b></p>
  <p>The time between your two requests is smaller than 2 seconds, please do NOT refresh and wait for automatical forwarding ...</p>
</div>
</center>
</body>
</html>
<?
    exit;
}

setcookie('lastrequest', $timestamp."\t".$REQUEST_URI, $timestamp + 6, '/');

?>
