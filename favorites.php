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


$act = $_GET['act'];
$tid = $_GET['id'];
$page = intval($_GET['page']);

// 获取收藏数据
$user_fav = $DBS->fetch_one_array("SELECT * FROM yunbbs_favorites WHERE uid='".$cur_uid."'");

// 处理收藏操作
if($act && $tid){
    if($act == 'add'){
        // 添加
        if($user_fav){
            if($user_fav['content']){
                $ids_arr = explode(",", $user_fav['content']);
                if(!in_array($tid, $ids_arr)){
                    array_unshift($ids_arr, $tid);
                    $articles = count($ids_arr);
                    $content = implode(',', $ids_arr);
                    $user_fav['content'] = $content;
                    $user_fav['articles'] = $articles;
                    
                    $DBS->unbuffered_query("UPDATE yunbbs_favorites SET articles='$articles',content='$content' WHERE uid='$cur_uid'");
                    $DBS->unbuffered_query("UPDATE yunbbs_articles SET favorites=favorites+1 WHERE id='$tid'");
                }
                unset($ids_arr);
            }else{
                $user_fav['content'] = $tid;
                $user_fav['articles'] = 1;
                $DBS->unbuffered_query("UPDATE yunbbs_favorites SET articles='1',content='$tid' WHERE uid='$cur_uid'");
                $DBS->unbuffered_query("UPDATE yunbbs_articles SET favorites=favorites+1 WHERE id='$tid'");
            }
        }else{
            $user_fav= array('id'=>'','uid'=>$cur_uid, 'articles'=>1, 'content' => $tid);
            $DBS->query("INSERT INTO yunbbs_favorites (id,uid,articles,content) VALUES (null,'$cur_uid','1','$tid')");
            $DBS->unbuffered_query("UPDATE yunbbs_articles SET favorites=favorites+1 WHERE id='$tid'");
        }
        
    }else if($act == 'del'){
        // 删除
        if($user_fav){
            if($user_fav['content']){
                $ids_arr = explode(",", $user_fav['content']);
                if(in_array($tid, $ids_arr)){
                    foreach($ids_arr as $k=>$v){
                        if($v == $tid){
                            unset($ids_arr[$k]);
                            break;
                        }
                    }
                    $articles = count($ids_arr);
                    $content = implode(',', $ids_arr);
                    $user_fav['content'] = $content;
                    $user_fav['articles'] = $articles;
                    
                    $DBS->unbuffered_query("UPDATE yunbbs_favorites SET articles='$articles',content='$content' WHERE uid='$cur_uid'");
                    $DBS->unbuffered_query("UPDATE yunbbs_articles SET favorites=favorites-1 WHERE id='$tid'");
                }
                unset($ids_arr);
            }
        }
    }
}

// 处理正确的页数
// 第一页是1
if($user_fav && $user_fav['articles']){
    $taltol_page = ceil($user_fav['articles']/$options['list_shownum']);
    if($page<0){
        header('location: /favorites');
        exit;
    }else if($page==1){
        header('location: /favorites');
        exit;
    }else{
        if($page>$taltol_page){
            header('location: /favorites?page='.$taltol_page);
            exit;
        }
    }
}else{
    $page = 0;
}

// 获取收藏文章列表
if($user_fav['articles']){
    if($page == 0) $page = 1;
    $from_i = $options['list_shownum']*($page-1);
    $to_i = $from_i + $options['list_shownum'];
    
    if($user_fav['articles'] > 1){
        $id_arr = array_slice( explode(',', $user_fav['content']), $from_i, $to_i);
    }else{
        $id_arr = array($user_fav['content']);
    }
    $ids = implode(',', $id_arr);
    //exit($ids);
    $query_sql = "SELECT a.id,a.uid,a.cid,a.ruid,a.title,a.addtime,a.edittime,a.comments,c.name as cname,u.avatar as uavatar,u.name as author,ru.name as rauthor
        FROM yunbbs_articles a 
        LEFT JOIN yunbbs_categories c ON c.id=a.cid
        LEFT JOIN yunbbs_users u ON a.uid=u.id
        LEFT JOIN yunbbs_users ru ON a.ruid=ru.id
        WHERE a.id in(".$ids.")";
    $query = $DBS->query($query_sql);
    $articledb=array();
    // 按收藏顺序排列
    foreach($id_arr as $aid){
        $articledb[$aid] = '';
    }
    
    while ($article = $DBS->fetch_array($query)) {
        // 格式化内容
        $article['addtime'] = showtime($article['addtime']);
        $article['edittime'] = showtime($article['edittime']);
        $articledb[$article['id']] = $article;
    }
    unset($article);
    $DBS->free_result($query);
}

// 页面变量
$title = '个人收藏';
$newest_nodes = get_newest_nodes();

$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'favorites.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

?>
