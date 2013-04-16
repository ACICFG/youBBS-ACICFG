<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

if (!$cur_user) exit('error: 401 login please');
if ($cur_user['flag']==0){
    header("content-Type: text/html; charset=UTF-8");
    exit('error: 403 该帐户已被禁用');
}else if($cur_user['flag']==1){
    header("content-Type: text/html; charset=UTF-8");
    exit('error: 401 该帐户还在审核中');
}


// 获取提醒文章列表
$cur_user = $DBS->fetch_one_array("SELECT * FROM yunbbs_users WHERE id='".$cur_uid."' LIMIT 1");

if($cur_user['notic']){
    $ids = implode(',', array_unique(explode(',', substr($cur_user['notic'], 0, -1))));
    
    $query_sql = "SELECT a.id,a.uid,a.cid,a.ruid,a.title,a.addtime,a.edittime,a.comments,c.name as cname,u.avatar as uavatar,u.name as author,ru.name as rauthor
        FROM yunbbs_articles a 
        LEFT JOIN yunbbs_categories c ON c.id=a.cid
        LEFT JOIN yunbbs_users u ON a.uid=u.id
        LEFT JOIN yunbbs_users ru ON a.ruid=ru.id
        WHERE a.id in(".$ids.")";
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

// 页面变量
$title = '站内提醒';
$newest_nodes = get_newest_nodes();

$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'notifications.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

?>
