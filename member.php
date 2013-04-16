<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

$g_mid = $_GET['mid'];
// mid 可能id或用户名，用户注册时要限制名字不能为全数字
if(preg_match('/^[a-zA-Z0-9\x80-\xff]{1,20}$/i', $g_mid)){
    $mid = intval($g_mid);
    if($mid){
        $query = "SELECT id,name,flag,avatar,url,articles,replies,regtime,about FROM yunbbs_users WHERE id='$mid'";
    }else{
        $query = "SELECT id,name,flag,avatar,url,articles,replies,regtime,about FROM yunbbs_users WHERE name='$g_mid'";
    }
}else{
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
    include(dirname(__FILE__) . '/404.html');
    exit;
    
}

$m_obj = $DBS->fetch_one_array($query);
if($m_obj){
    if(!$mid){
        // 可以重定向到网址 /member/id 为了减少请求，下面用 $canonical 来让SEO感觉友好
        //header('location: /member/'.$m_obj['id']);
        //exit;
        $mid = $m_obj['id'];
    }
    if($m_obj['flag'] == 0){
        if(!$cur_user || ($cur_user && $cur_user['flag']<99)){
            //header("content-Type: text/html; charset=UTF-8");
            //exit('该用户已被禁用');
        }
    }
    $openid_user = $DBS->fetch_one_array("SELECT name FROM yunbbs_qqweibo WHERE uid='".$mid."'");
    $weibo_user = $DBS->fetch_one_array("SELECT `openid` FROM `yunbbs_weibo` WHERE `uid`='".$mid."'");
}else{
    exit('404');
}

$m_obj['regtime'] = showtime($m_obj['regtime']);

// 获取用户最近文章列表
if($m_obj['articles']){
    
    $query_sql = "SELECT a.id,a.cid,a.ruid,a.title,a.addtime,a.edittime,a.comments,c.name as cname,ru.name as rauthor
        FROM yunbbs_articles a 
        LEFT JOIN yunbbs_categories c ON c.id=a.cid
        LEFT JOIN yunbbs_users ru ON a.ruid=ru.id
        WHERE a.uid='".$mid."' ORDER BY id DESC LIMIT 10";
    $query = $DBS->query($query_sql);
    $articledb=array();
    while ($article = $DBS->fetch_array($query)) {
        // 格式化内容
        $article['addtime'] = showtime($article['addtime']);
        $article['edittime'] = showtime($article['edittime']);
        $articledb[] = $article;
    }
    unset($article);
    $DBS->free_result($query);

}

// 用户最近回复文章列表不能获取
// 若想实现则在users 表里添加一列来保存最近回复文章的id


// 页面变量
$title = '会员: '.$m_obj['name'];
$newest_nodes = get_newest_nodes();
$canonical = '/member/'.$m_obj['id'];
$meta_des = $m_obj['name'].' - '.htmlspecialchars(mb_substr($m_obj['about'], 0, 150, 'utf-8'));

$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'member.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

?>
