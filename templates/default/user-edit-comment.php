<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');
$rid = intval($_GET['rid']);

$uid = $cur_user['id']
if (!$cur_user || $cur_user['flag']<4 ||  $cur_user['name'] == $comment['author']) exit('error: 403 Access Denied');


$query = "SELECT id,articleid,content,uid FROM yunbbs_comments WHERE id='$rid' AND uid=$uid";
$r_obj = $DBS->fetch_one_array($query);
if(!$r_obj){
    exit('404');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $r_content = addslashes(trim($_POST['content']));
    
    if($r_content){
        $r_content = htmlspecialchars($r_content);
        $DBS->unbuffered_query("UPDATE yunbbs_comments SET content='$r_content' WHERE id='$rid'");
        $tip = '评论已成功修改';
    }else{
        $tip = '内容 不能留空';
    }
}else{
    $r_content = $r_obj['content'];
    $tip = '';
}

// 页面变量
$title = '修改评论';
// 设置回复图片最大宽度
$img_max_w = 590;


$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'user-edit-comment.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

?>
