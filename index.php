<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

// 获取最近文章列表
$query_sql = "SELECT a.id,a.cid,a.uid,a.ruid,a.title,a.addtime,a.edittime,a.comments,a.visible,a.top,c.name as cname,u.avatar as uavatar,u.name as author,ru.name as rauthor
    FROM yunbbs_articles a  
    LEFT JOIN yunbbs_categories c ON c.id=a.cid
    LEFT JOIN yunbbs_users u ON a.uid=u.id
    LEFT JOIN yunbbs_users ru ON a.ruid=ru.id
    
    WHERE `visible` != '0'
    
    ORDER BY `top` DESC ,`edittime` DESC LIMIT ".$options['home_shownum'];



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
$title = $options['name'];

$site_infos = get_site_infos();
$newest_nodes = get_newest_nodes();
if(count($newest_nodes)==$options['newest_node_num']){
    $bot_nodes = get_bot_nodes();
}

$links = get_links();
if($options['site_des']){
    $meta_des = htmlspecialchars(mb_substr($options['site_des'], 0, 150, 'utf-8'));
}

$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'home.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

?>
