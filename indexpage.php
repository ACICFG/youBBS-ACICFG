<?php

$opt = ['cid' => 0, 'page' => 0, 'cat' => []];
$opt = array_merge($opt, $GLOBALS['pageopt']);

extract($opt);

if($cid)
{
    // Check if category exists
    $cat = $DBS->fetch_one_array("SELECT * FROM `yunbbs_categories` WHERE `id` = $cid");
    if(!$cat)
    {
        header('location: /');
        exit;
    }
}


$query_where = " `visible` != '0' ";
if($cid)
    $query_where .= " AND cid = '$cid' ";

// Paging
$total = $DBS->fetch_one("SELECT COUNT(*) FROM `yunbbs_articles`
    WHERE $query_where
    ");
$perpage = $options['list_shownum'];

$totalpage = ceil($total / $perpage);

if($page < 0 || $page == 1){
    if($cid)
        header('location: /n-' . $cid);
    else
        header('location: /');
    exit;
}
if($page > $totalpage){
    if($cid)
        header('location: /n-' . $cid . '-' . $totalpage);
    else
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
    WHERE $query_where
    ORDER BY `top` DESC ,`edittime` DESC LIMIT " . $query_limit;
$query = $DBS->query($query_sql);
$articledb = [];
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
    if($cid)
        $meta_des = $cat['name'] . ' - ' . mb_substr($cat['about'], 0, 150, 'utf-8');
    else
        $meta_des = mb_substr($options['site_des'], 0, 150, 'utf-8');
    $meta_des = htmlspecialchars($meta_des) .' - page '.$page;
}

$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'indexpage.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

