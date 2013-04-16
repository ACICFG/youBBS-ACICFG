<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

if($cur_user && $cur_user['flag'] == 0){
    header('location: /');
    exit;
}

//

$errors = array();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = addslashes(trim($_POST["name"]));
    $email = addslashes(trim($_POST["email"]));
    if($name && $email){
        if(strlen($name)<21 && strlen($email)<32){
            if(preg_match('/^[a-zA-Z0-9\x80-\xff]{4,20}$/i', $name)){
                if(preg_match('/^[0-9]{4,20}$/', $name)){
                    $errors[] = '名字不能全为数字';
                }else{
                    if(isemail($email)){
                        $db_user = $DBS->fetch_one_array("SELECT * FROM yunbbs_users WHERE name='".$name."' LIMIT 1");
                        if($db_user){
                            if($email == $db_user['email']){
                                header("content-Type: text/html; charset=UTF-8");
                                exit('请用该邮箱: '.$db_user['email'].' 给管理员（管理员信箱'.$options['admin_email'].'）发送一封密码重设请求，内容只需包含您的用户名“'.$name.'”');
                            }else{
                                $errors[] = '填写的邮箱 和 个人设置里的邮箱 不一致';
                            }
                        }else{
                            $errors[] = '用户名 错误';
                        }
                    }else{
                        $errors[] = '邮箱 格式错误';
                    }
                }
            }else{
                $errors[] = '名字 太长 或 太短 或 包含非法字符';
            }
        }else{
            $errors[] = '用户名 或 email 太长了';
        }
    }else{
       $errors[] = '用户名 和 邮箱 必填'; 
    }
}

// 页面变量
$title = '找回密码';


$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'forgot.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

?>
