<?php

$is_home_page = false;

$opt = ['page' => 0];
$opt = array_merge($opt, $GLOBALS['pageopt']);

extract($opt);

// Paging
$total = $DBS->fetch_one("SELECT COUNT(*) FROM `yunbbs_articles`");
$perpage = $options['list_shownum'];

$totalpage = ceil($total / $perpage);

if($page < 0 || $page == 1){
    header('location: /');
    exit;
}
if($page > $totalpage){
    header('location: /page/'.$totalpage);
    exit;
}
if($page == 0) $page = 1;

$query_limit = $page == 1 ? $perpage : (($page - 1) * $perpage) . ',' . $perpage;

$query_sql = "SELECT a.*,c.name as cname,u.avatar as uavatar,u.name as author,ru.name as rauthor,u.flag as flag
    FROM yunbbs_articles a  
    LEFT JOIN yunbbs_categories c ON c.id=a.cid
    LEFT JOIN yunbbs_users u ON a.uid=u.id
    LEFT JOIN yunbbs_users ru ON a.ruid=ru.id
        WHERE `visible` != '0'
    ORDER BY `top` DESC ,`edittime` DESC LIMIT " . $query_limit;
$query = $DBS->query($query_sql);
$articledb=array();
while ($article = $DBS->fetch_array($query)) {
     if($article['isred'] == '1'){
         $article['title'] = $article['title']."<img src=\"/static/default/img/newisred.GIF\" alt=\"精品\" class=\"topic-title-img\">";
     }
     if($article['top'] == '1'){
         $article['title'] = $article['title']."<img src=\"/static/default/img/newistop.GIF\" alt=\"置顶\" class=\"topic-title-img\">";
     }
     if($article['cid'] == '3'){
         $article['title'] = $article['title']."<img src=\"/static/default/img/newrelease.jpg\" alt=\"发布\" class=\"topic-title-img\">";
     }


    $article['addtime'] = showtime($article['addtime']);
    $article['edittime'] = showtime($article['edittime']);
    $articledb[] = $article;
}
unset($article);
$DBS->free_result($query);


// 页面变量
$title = $options['name'];
if($page > 1)
    $title .= ' - page '.$page;

$site_infos = get_site_infos();
$newest_nodes = get_newest_nodes();
if(count($newest_nodes)==$options['newest_node_num']){
    $bot_nodes = get_bot_nodes();
}

if($page > 1)
    $show_sider_ad = true;
$links = get_links();

if($options['site_des']){
    $meta_des = htmlspecialchars(mb_substr($options['site_des'], 0, 150, 'utf-8')).' - page '.$page;
}

if($page == 1)
$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'home.php';
else
$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'indexpage.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

