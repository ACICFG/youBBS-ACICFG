<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');


if($cur_user){
    header('location: /');
    exit;
}else{
    if($options['close_register']){
        header('location: /login');
        exit;
    }
}

error_reporting(0);
session_start();

$name = $_SESSION["nick"];
$openid = $_SESSION["openid"];

if(!$openid) exit('error: 403 Access Denied');

$errors = array();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $action = $_POST['action'];
    $name = addslashes(strtolower(trim($_POST["name"])));
    
    if($action == 'newuser'){
        // 新增
        if($name){
            if(strlen($name)<21){
                //检测字符
                if(preg_match('/^[a-zA-Z0-9\x80-\xff]{4,20}$/i', $name)){
                    if(preg_match('/^[0-9]{4,20}$/', $name)){
                        $errors[] = '名字不能全为数字';
                    }else{
                        // 检测重名
                        $db_user = $DBS->fetch_one_array("SELECT id FROM yunbbs_users WHERE name='".$name."'");
                        if($db_user){
                            $errors[] = '这名字太火了，已经被抢注了，换一个吧！';
                        }
                    }
                }else{
                    $errors[] = '名字 太长 或 太短 或 包含非法字符';
                }
            }else{
                $errors[] = '用户名 太长了';
            }
        }else{
            $errors[] = '用户名 必填'; 
        }
        //
        if(!$errors){
            if($options['register_review']){
                $flag = 1;
            }else{
                $flag = 5;
            }
            $DBS->query("INSERT INTO yunbbs_users (id,name,flag,password,regtime) VALUES (null,'$name', $flag, '', $timestamp)");
            $new_uid = $DBS->insert_id();
            
            // update qqweibo
            $DBS->unbuffered_query("UPDATE yunbbs_qqweibo SET uid = '$new_uid' WHERE openid='$openid'");
            
            //设置cookie
            $db_ucode = md5($new_uid.''.$timestamp.'00');
            $cur_uid = $new_uid;
            setcookie("cur_uid", $cur_uid, $timestamp+ 86400 * 365, '/');
            setcookie("cur_uname", $name, $timestamp+86400 * 365, '/');
            setcookie("cur_ucode", $db_ucode, $timestamp+86400 * 365, '/');
            $gotohome = "1";
            $getavatar = "1";
            //header('location: /');
            //exit;
            
        }
    }else if($action == 'bind'){
        // 绑定
        $pw = addslashes(trim($_POST["pw"]));
        if($name && $pw){
            if(strlen($name)<21 && strlen($pw)<32){
                //检测字符
                if(preg_match('/^[a-zA-Z0-9\x80-\xff]{4,20}$/i', $name)){
                    if(preg_match('/^[0-9]{4,20}$/', $name)){
                        $errors[] = '名字不能全为数字';
                    }else{
                        $db_user = $DBS->fetch_one_array("SELECT * FROM yunbbs_users WHERE name='".$name."'");
                        if($db_user){
                            $pwmd5 = md5($pw);
                            if($pwmd5 == $db_user['password']){
                                // update qqweibo
                                $userid = $db_user['id'];
                                $DBS->unbuffered_query("UPDATE yunbbs_qqweibo SET uid = '$userid' WHERE openid='$openid'");
                                
                                //设置缓存和cookie
                                $db_ucode = md5($db_user['id'].$db_user['password'].$db_user['regtime'].$db_user['lastposttime'].$db_user['lastreplytime']);
                                $cur_uid = $db_user['id'];
                                
                                setcookie("cur_uid", $cur_uid, time()+ 86400 * 365, '/');
                                setcookie("cur_uname", $name, time()+86400 * 365, '/');
                                setcookie("cur_ucode", $db_ucode, time()+86400 * 365, '/');
                                $cur_user = $db_user;
                                unset($db_user);
                                $gotohome = "1";
                                if($db_user['avatar'] != $db_user['id']){
                                    $getavatar = "1";
                                }
                                //header('location: /');
                                //exit('logined');
                            }else{
                                // 用户名和密码不匹配
                                $errors[] = '用户名 或 密码 错误';
                            }
                        }else{
                            // 没有该用户名
                            $errors[] = '用户名 或 密码 错误';
                        }
                    }
                }else{
                    $errors[] = '名字 太长 或 太短 或 包含非法字符';
                }
            }else{
                $errors[] = '用户名 或 密码 太长了';
            }
            
        }else{
            $errors[] = '用户名、密码  必填'; 
        }
    }
}


/////
if(isset($gotohome)){
    // 获取用户微博头像
    if($_SESSION["avatar"] && isset($getavatar)){
        $imgurl = $_SESSION["avatar"];
        $opts = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-language: en\r\n" .
                      "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6\r\n".
                      "Referer: ".$imgurl."\r\n"
          )
        );
        
        $context = stream_context_create($opts);
        
        $avatardata = file_get_contents($imgurl, false, $context);
        
        $img_obj = imagecreatefromstring($avatardata);
        
        if($img_obj !== false){
            // 头像 large
            $new_w = 73;
            $new_h = 73;
            
            $new_image = imagecreatetruecolor($new_w, $new_h);
            $bg = imagecolorallocate ( $new_image, 255, 255, 255 );
            imagefill ( $new_image, 0, 0, $bg );
            
            ////目标文件，源文件，目标文件坐标，源文件坐标，目标文件宽高，源宽高
            imagecopyresampled($new_image, $img_obj, 0, 0, 0, 0, $new_w, $new_h, 100, 100);
            // 保存头像
            imagejpeg($new_image, 'avatar/large/'.$cur_uid.'.png', 95);
            
            // 头像 normal
            $new_w = 48;
            $new_h = 48;
            
            $new_image = imagecreatetruecolor($new_w, $new_h);
            $bg = imagecolorallocate ( $new_image, 255, 255, 255 );
            imagefill ( $new_image, 0, 0, $bg );
            
            ////目标文件，源文件，目标文件坐标，源文件坐标，目标文件宽高，源宽高
            imagecopyresampled($new_image, $img_obj, 0, 0, 0, 0, $new_w, $new_h, 100, 100);
            // 保存头像
            imagejpeg($new_image, 'avatar/normal/'.$cur_uid.'.png', 95);
            
            // 头像 mini
            $new_w = 24;
            $new_h = 24;
            
            $new_image = imagecreatetruecolor($new_w, $new_h);
            $bg = imagecolorallocate ( $new_image, 255, 255, 255 );
            imagefill ( $new_image, 0, 0, $bg );
            
            ////目标文件，源文件，目标文件坐标，源文件坐标，目标文件宽高，源宽高
            imagecopyresampled($new_image, $img_obj, 0, 0, 0, 0, $new_w, $new_h, 100, 100);
            // 保存头像
            imagejpeg($new_image, 'avatar/mini/'.$cur_uid.'.png', 95);
            
            imagedestroy($img_obj);
            imagedestroy($new_image);
            
            // 
            $DBS->unbuffered_query("UPDATE yunbbs_users SET avatar='$cur_uid' WHERE id='$cur_uid'");
        }
        
    }
    header('location: /');
    exit;
}


/////
// 页面变量
$title = '设置名字';
$logintype = "QQ";

$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'qqsetname.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

?>
