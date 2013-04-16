<?php 
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

error_reporting(0);
session_start();

$_SESSION["appid"]    = $options['qq_appid'];
$_SESSION["appkey"]   = $options['qq_appkey'];

$_SESSION["callback"] = 'http://'.$_SERVER['HTTP_HOST'].'/qqcallback';


include(dirname(__FILE__) . "/api/qq_utils.php");

function qq_callback()
{
    //debug
    //print_r($_REQUEST);
    //print_r($_SESSION);

    if($_REQUEST['state'] == $_SESSION['state']) //csrf
    {
        $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
            . "client_id=" . $_SESSION["appid"]. "&redirect_uri=" . urlencode($_SESSION["callback"])
            . "&client_secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];

        $response = get_url_contents($token_url);
        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error))
            {
                echo "<h3>error:</h3>" . $msg->error;
                echo "<h3>msg  :</h3>" . $msg->error_description;
                exit;
            }
        }

        $params = array();
        parse_str($response, $params);

        //debug
        //print_r($params);

        //set access token to session
        $_SESSION["access_token"] = $params["access_token"];

    }
    else 
    {
        echo("The state does not match. You may be a victim of CSRF.");
    }
}

function get_openid()
{
    $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" 
        . $_SESSION['access_token'];

    $str  = get_url_contents($graph_url);
    if (strpos($str, "callback") !== false)
    {
        $lpos = strpos($str, "(");
        $rpos = strrpos($str, ")");
        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
    }

    $user = json_decode($str);
    if (isset($user->error))
    {
        echo "<h3>error:</h3>" . $user->error;
        echo "<h3>msg  :</h3>" . $user->error_description;
        echo '<h3><a href="/qqlogin">尝试再登录</a></h3>';
        exit;
    }

    //set openid to session
    $_SESSION["openid"] = $user->openid;
}

function get_user_info()
{
    $get_user_info = "https://graph.qq.com/user/get_user_info?"
        . "access_token=" . $_SESSION['access_token']
        . "&oauth_consumer_key=" . $_SESSION["appid"]
        . "&openid=" . $_SESSION["openid"]
        . "&format=json";

    $info = get_url_contents($get_user_info);
    $arr = json_decode($info, true);

    return $arr;
}

function get_info()
{
    $get_info = "https://graph.qq.com/user/get_info?"
        . "access_token=" . $_SESSION['access_token']
        . "&oauth_consumer_key=" . $_SESSION["appid"]
        . "&openid=" . $_SESSION["openid"]
        . "&format=json";

    $info = get_url_contents($get_info);
    $arr = json_decode($info, true);

    return $arr;
}

//QQ登录成功后的回调地址,主要保存access token
qq_callback();

//获取用户标示id
get_openid();

$openid = $_SESSION["openid"];

$db_openid = $DBS->fetch_one_array("SELECT id,uid FROM yunbbs_qqweibo WHERE openid='".$openid."'");

if($db_openid && $db_openid['uid']){
    // 直接登录
    $cur_uid = $db_openid['uid'];
    $db_user = $DBS->fetch_one_array("SELECT * FROM yunbbs_users WHERE id='".$cur_uid."' LIMIT 1");
    if($db_user){
        $db_ucode = md5($db_user['id'].$db_user['password'].$db_user['regtime'].$db_user['lastposttime'].$db_user['lastreplytime']);
        //设置cookie
        $u_key = 'u_'.$cur_uid;
        
        $timestamp = time();
        setcookie('cur_uid', $cur_uid, $timestamp+ 86400 * 365, '/');
        setcookie('cur_uname', $db_user['name'], $timestamp+86400 * 365, '/');
        setcookie('cur_ucode', $db_ucode, $timestamp+86400 * 365, '/');
        $cur_user = $db_user;
        unset($db_user);
    }
    
    header("Location:/");
    exit;    
}

///
if(strpos(' '.$_SESSION["scope"], 'get_info')){
    $user_info = get_info();
    
    /**
     * $user_info['data']['head'] 头像 /100
     * $user_info['data']['name'] 微博地址 http://t.qq.com/#{name}
     * $user_info['data']['nick'] 网站名字
     * $user_info['data']['regtime'] 判断是否是新用户，至少三个月
     */
    
    $regtime = intval($user_info['data']['regtime']);
    if(!$regtime || ($timestamp - $regtime)<7776000){
        echo '<h3>抱歉，您还没开通腾讯微博，或者开通未达到3个月，请先去开通 <a href="http://t.qq.com" target="_blank">http://t.qq.com</a></h3>';
        echo '<h3><a href="/qqlogin">尝试再登录</a></h3>';
        echo '<h3><a href="/">返回首页</a></h3>';
        exit;
    }
    
    $name = $user_info['data']['name'];
    
    $_SESSION["nick"] = $user_info['data']['nick'];
    if($user_info['data']['head']){
        $_SESSION["avatar"] = $user_info['data']['head'].'/100';
    }
    
}else{
    $user_info = get_user_info();
    
    /**
     * $user_info['figureurl_2'] 头像 100px
     * $user_info['nickname'] 
     */    
    
    $name = "";
    $_SESSION["nick"] = $user_info['nickname'];
    $_SESSION["avatar"] = $user_info['figureurl_2'];
    
}



if($db_openid){
    if($db_openid['uid']){
        // pass
    }else{
        header("Location:/qqsetname");
        exit;
    }
}else{
    $DBS->query("INSERT INTO yunbbs_qqweibo (id,uid,name,openid) VALUES (null,'0','$name', '$openid')");
    header("Location:/qqsetname");
    exit;
}

?>
