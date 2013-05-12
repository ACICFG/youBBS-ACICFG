<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

# This would be the router of the system.

$path = $_SERVER['REQUEST_URI'];

// Filter any possible args
if(strpos($path, '?') !== false)
    $path = strstr($path, '?', true);

$pagefile = 'indexpage';
$pageopt = array();
$viewopt = array();

// match static address
$static_pages = array('/notifications', '/favorites', '/qqlogin', '/qqcallback', '/qqsetname', '/feed', '/robots', '/forgot');
if(in_array($path, $static_pages))
{
    $pagefile = substr($path, 1);
}
// match dynamic address
elseif(preg_match('~^/(?:page/(\d+))?$~', $path, $m))
{
    $pageopt['page'] = isset($m[1]) ? intval($m[1]) : 0;
    $pagefile = 'indexpage';
}
elseif(preg_match('~^/n-(\d+)(?:-(\d+))?$~', $path, $m))
{
    $pageopt['cid'] = isset($m[1]) ? intval($m[1]) : 0;
    $pageopt['page'] = isset($m[2]) ? intval($m[2]) : 0;
    $pagefile = 'indexpage';
}
elseif(preg_match('~^/t-(\d+)(?:-(\d+))?$~', $path, $m))
{
    $pageopt['tid'] = isset($m[1]) ? intval($m[1]) : 0;
    $pageopt['page'] = isset($m[2]) ? intval($m[2]) : 0;
    $pagefile = 'topicpage';
}


if(empty($pagefile))
    $pagefile = 'indexpage';

if(file_exists($pagefile . '.php'))
{
    include $pagefile . '.php';
    die;
}

header('HTTP/1.1 405 Module not found');

die('Module not found');

