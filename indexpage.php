<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

$page = intval($_GET['page']);

// 处理正确的页数
$table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_articles'");
$taltol_article = $table_status['Auto_increment'] -1;
$taltol_page = ceil($taltol_article/$options['list_shownum']);
if($page<0){
    header('location: /');
    exit;
}else if($page==1){
    header('location: /');
    exit;
}else{
    if($page>$taltol_page){
        header('location: /page/'.$taltol_page);
        exit;
    }
}

// 获取最近文章列表
if($page == 0) $page = 1;

$query_sql = "SELECT a.id,a.uid,a.ruid,a.title,a.top,a.addtime,a.isred,a.edittime,a.comments,a.visible,u.avatar as uavatar,u.name as author,ru.name as rauthor,u.flag as flag
    FROM yunbbs_articles a 
    LEFT JOIN yunbbs_users u ON a.uid=u.id
    LEFT JOIN yunbbs_users ru ON a.ruid=ru.id
    WHERE `visible` != '0'
	
    ORDER BY `top` DESC ,`edittime` DESC LIMIT ".($page-1)*$options['list_shownum'].",".$options['list_shownum'];
$query = $DBS->query($query_sql);
$articledb=array();
while ($article = $DBS->fetch_array($query)) {
    // 格式化内容
    //这几行代码我自己感觉写的糟透了，但是没想出来怎么改。。。谁动手重构一下？
     if($article['isred'] == '1' && $article['cid'] == '3' && $article['top'] == '1'){
         $article['title'] = $article['title']."<img src=\"/static/default/img/newrelease.jpg\" alt=\"发布\" class=\"topic-title-img\"><img src=\"/static/default/img/newistop.GIF\" alt=\"置顶\" class=\"topic-title-img\"><img src=\"/static/default/img/newisred.GIF\" alt=\"精品\" class=\"topic-title-img\">";
     }elseif($article['isred'] == '1' && $article['cid'] == '3'){
         $article['title'] = $article['title']."<img src=\"/static/default/img/newrelease.jpg\" alt=\"发布\" class=\"topic-title-img\"><img src=\"/static/default/img/newistop.GIF\" alt=\"置顶\" class=\"topic-title-img\">";
     }elseif($article['isred'] == '1' && $article['top'] == '1'){
         $article['title'] = $article['title']."<img src=\"/static/default/img/newistop.GIF\" alt=\"置顶\" class=\"topic-title-img\"><img src=\"/static/default/img/newisred.GIF\" alt=\"精品\" class=\"topic-title-img\">";
     }elseif($article['cid'] == '3' && $article['top'] == '1'){
         $article['title'] = $article['title']."<img src=\"/static/default/img/newrelease.jpg\" alt=\"发布\" class=\"topic-title-img\"><img src=\"/static/default/img/newistop.GIF\" alt=\"置顶\" class=\"topic-title-img\">";
     }elseif($article['isred'] == '1'){
         $article['title'] = $article['title']."<img src=\"/static/default/img/newisred.GIF\" alt=\"精品\" class=\"topic-title-img\">";
     }elseif($article['cid'] == '3'){
         $article['title'] = $article['title']."<img src=\"/static/default/img/newrelease.jpg\" alt=\"发布\" class=\"topic-title-img\">";
     }elseif($article['top'] == '1'){
         $article['title'] = $article['title']."<img src=\"/static/default/img/newistop.GIF\" alt=\"置顶\" class=\"topic-title-img\">";
     }



    $article['addtime'] = showtime($article['addtime']);
    $article['edittime'] = showtime($article['edittime']);
    $articledb[] = $article;
}
unset($article);
$DBS->free_result($query);


// 页面变量
$title = $options['name'].' - page '.$page;

$site_infos = get_site_infos();
$newest_nodes = get_newest_nodes();
if(count($newest_nodes)==$options['newest_node_num']){
    $bot_nodes = get_bot_nodes();
}

$show_sider_ad = "1";
$links = get_links();

if($options['site_des']){
    $meta_des = htmlspecialchars(mb_substr($options['site_des'], 0, 150, 'utf-8')).' - page '.$page;
}

$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'indexpage.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

?>
