<?php
define('IN_SAESPOT', 1);

require(dirname(__FILE__) . '/config.php');
require(dirname(__FILE__) . '/common.php');

if (!$cur_user) exit('error: 401 login please');
if ($cur_user['flag']==0){
    header("content-Type: text/html; charset=UTF-8");
    exit('error: 403 该帐户已被禁用');
}else if($cur_user['flag']==1){
    header("content-Type: text/html; charset=UTF-8");
    exit('error: 401 该帐户还在审核中');
}

$tip1 = '';
$tip2 = '';
$tip3 = '';
$av_time = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $action = $_POST['action'];
    if($action == 'info'){
        $email = addslashes(filter_chr(trim($_POST['email'])));
        $url = char_cv(filter_chr(trim($_POST['url'])));
        $about = addslashes(trim($_POST['about']));
        
        if($DBS->unbuffered_query("UPDATE yunbbs_users SET email='$email', url='$url', about='$about' WHERE id='$cur_uid'")){
            //更新缓存
            $cur_user['email'] = $email;
            $cur_user['url'] = $url;
            $cur_user['about'] = $about;
            $tip1 = '已成功保存';
        }else{
            $tip1 = '数据库更新失败，修改尚未保存，请稍后再试';
        }
    }else if($action == 'avatar'){
        if($_FILES['avatar']['size'] && $_FILES['avatar']['size'] < 301000){
            $img_info = getimagesize($_FILES['avatar']['tmp_name']);
            if($img_info){
                //创建源图片
                if($img_info[2]==1){
                    $img_obj = imagecreatefromgif($_FILES['avatar']['tmp_name']);
                }else if($img_info[2]==2){
                    $img_obj = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
                }else if($img_info[2]==3){
                    $img_obj = imagecreatefrompng($_FILES['avatar']['tmp_name']);
                }
                //如果上传的文件是jpg/gif/png则处理
                if(isset($img_obj)){
                    // 缩略图比例
                    $max_px = max($img_info[0], $img_info[1]);
                    //large
                    if($max_px>73){
                        $percent = 73/$max_px;
                        $new_w = round($img_info[0]*$percent);
                        $new_h = round($img_info[1]*$percent);
                        
                        $new_image = imagecreatetruecolor($new_w, $new_h);
                        $bg = imagecolorallocate ( $new_image, 255, 255, 255 );
                        imagefill ( $new_image, 0, 0, $bg );
                        
                        ////目标文件，源文件，目标文件坐标，源文件坐标，目标文件宽高，源宽高
                        imagecopyresampled($new_image, $img_obj, 0, 0, 0, 0, $new_w, $new_h, $img_info[0], $img_info[1]);
                        imagejpeg($new_image, 'avatar/large/'.$cur_uid.'.png', 95);
                    }else{
                        imagejpeg($img_obj, 'avatar/large/'.$cur_uid.'.png', 95);
                    }
                    //normal
                    if($max_px>48){
                        $percent = 48/$max_px;
                        $new_w = round($img_info[0]*$percent);
                        $new_h = round($img_info[1]*$percent);
                        $new_image = imagecreate($new_w, $new_h);
                        
                        $new_image = imagecreatetruecolor($new_w, $new_h);
                        $bg = imagecolorallocate ( $new_image, 255, 255, 255 );
                        imagefill ( $new_image, 0, 0, $bg );
                        
                        ////目标文件，源文件，目标文件坐标，源文件坐标，目标文件宽高，源宽高
                        imagecopyresampled($new_image, $img_obj, 0, 0, 0, 0, $new_w, $new_h, $img_info[0], $img_info[1]);
                        imagejpeg($new_image, 'avatar/normal/'.$cur_uid.'.png', 95);
                    }else{
                        imagejpeg($img_obj, 'avatar/normal/'.$cur_uid.'.png', 95);
                    }
                    // mini
                    if($max_px>24){
                        $percent = 24/$max_px;
                        $new_w = round($img_info[0]*$percent);
                        $new_h = round($img_info[1]*$percent);
                        $new_image = imagecreate($new_w, $new_h);
                        
                        $new_image = imagecreatetruecolor($new_w, $new_h);
                        $bg = imagecolorallocate ( $new_image, 255, 255, 255 );
                        imagefill ( $new_image, 0, 0, $bg );
                        
                        ////目标文件，源文件，目标文件坐标，源文件坐标，目标文件宽高，源宽高
                        imagecopyresampled($new_image, $img_obj, 0, 0, 0, 0, $new_w, $new_h, $img_info[0], $img_info[1]);
                        imagejpeg($new_image, 'avatar/mini/'.$cur_uid.'.png', 95);
                    }else{
                        imagejpeg($img_obj, 'avatar/mini/'.$cur_uid.'.png', 95);
                    }
                    imagedestroy($img_obj);
                    if(isset($new_image)){
                        imagedestroy($new_image);
                    }
                    //
                    if($cur_user['avatar']!=$cur_user['id']){
                        if($DBS->unbuffered_query("UPDATE yunbbs_users SET avatar='$cur_uid' WHERE id='$cur_uid'")){
                            $cur_user['avatar'] = $cur_user['id'];
                        }else{
                            $tip2 = '数据保存失败，请稍后再试';
                        }
                    }
                    $av_time = $timestamp;
                }else{
                    $tip2 = '图片转换失败，请稍后再试';
                }
            }else{
                $tip2 = '你上传的不是图片文件，只支持jpg/gif/png三种格式';
            }
        }else{
            $tip2 = '图片尚未上传或太大了';
        }
    }else if($action == 'chpw'){
        $password_current = addslashes(trim($_POST['password_current']));
        $password_new = addslashes(trim($_POST['password_new']));
        $password_again = addslashes(trim($_POST['password_again']));
        if($password_current && $password_new && $password_again){
            if($password_new == $password_again){
                if(md5($password_current) == $cur_user['password']){
                    if($password_current != $password_new){
                        $new_md5pw = md5($password_new);
                        
                        if($DBS->unbuffered_query("UPDATE yunbbs_users SET password='$new_md5pw' WHERE id='$cur_uid'")){
                            //更新缓存和cookie
                            $cur_user['password'] = $new_md5pw;
                            $new_ucode = md5($cur_uid.$new_md5pw.$cur_user['regtime'].$cur_user['lastposttime'].$cur_user['lastreplytime']);
                            setcookie("cur_uid", $cur_uid, time()+ 86400 * 365, '/');
                            setcookie("cur_uname", $cur_uname, time()+86400 * 365, '/');
                            setcookie("cur_ucode", $new_ucode, time()+86400 * 365, '/');
                            $tip3 = '密码已成功更改，请记住新密码';
                        }else{
                            $tip3 = '数据保存失败，请稍后再试';
                        }
                    }else{
                        $tip3 = '输入的新密码和原来的密码相同，没修改！';
                    }
                }else{
                    $tip3 = '输入的当前密码不正确';
                }
            }else{
                $tip3 = '新密码、重复新密码不一致';
            }
        }else{
            $tip3 = '请填写完整，当前密码、新密码、重复新密码';
        }
    }else if($action == 'setpw'){
        $password_new = addslashes(trim($_POST['password_new']));
        $password_again = addslashes(trim($_POST['password_again']));
        if($password_new && $password_again){
            if($password_new == $password_again){
                $new_md5pw = md5($password_new);
                
                if($DBS->unbuffered_query("UPDATE yunbbs_users SET password='$new_md5pw' WHERE id='$cur_uid'")){
                    //更新缓存和cookie
                    $cur_user['password'] = $new_md5pw;
                    $new_ucode = md5($cur_uid.$new_md5pw.$cur_user['regtime'].$cur_user['lastposttime'].$cur_user['lastreplytime']);
                    setcookie("cur_uid", $cur_uid, time()+ 86400 * 365, '/');
                    setcookie("cur_uname", $cur_uname, time()+86400 * 365, '/');
                    setcookie("cur_ucode", $new_ucode, time()+86400 * 365, '/');
                    $tip3 = '登录密码已成功设置，请记住登录密码';
                }else{
                    $tip3 = '数据保存失败，请稍后再试';
                }
            }else{
                $tip3 = '登录密码、重复新密码不一致';
            }
        }else{
            $tip3 = '请填写完整，登录密码、重复密码';
        }
    }

}

// 页面变量
$title = '设置';

$newest_nodes = get_newest_nodes();

$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'setting.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');;

?>