<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

$cid = intval($_GET['cid']);
$page = intval($_GET['page']);

$c_obj = $DBS->fetch_one_array("SELECT * FROM yunbbs_categories WHERE id='".$cid."'");
if(!$c_obj){
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
    include(dirname(__FILE__) . '/404.html');
    exit;
    
};

// 处理正确的页数
$taltol_page = ceil($c_obj['articles']/$options['list_shownum']);
if($page<0){
    header('location: /n-'.$cid);
    exit;
}else if($page==1){
    header('location: /n-'.$cid);
    exit;
}else{
    if($page>$taltol_page){
        header('location: /n-'.$cid.'-'.$taltol_page);
        exit;
    }
}


// 获取最近文章列表
if($page == 0) $page = 1;

$query_sql = "SELECT a.id,a.uid,a.ruid,a.title,a.addtime,a.edittime,a.comments,u.avatar as uavatar,u.name as author,ru.name as rauthor
    FROM yunbbs_articles a 
    LEFT JOIN yunbbs_users u ON a.uid=u.id
    LEFT JOIN yunbbs_users ru ON a.ruid=ru.id
    WHERE a.cid='".$cid."' ORDER BY edittime DESC LIMIT ".($page-1)*$options['list_shownum'].",".$options['list_shownum'];
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


// 页面变量
$title = $c_obj['name'];
$newest_nodes = get_newest_nodes();
$links = get_links();
$meta_des = $c_obj['name'].' - '.htmlspecialchars(mb_substr($c_obj['about'], 0, 150, 'utf-8')).' - page '.$page;

$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'node.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

?>
