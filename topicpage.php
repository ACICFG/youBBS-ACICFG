<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');


$tid = intval($_GET['tid']);
// 评论页数，默认是1
$page = intval($_GET['page']);

// 获取文章
$query = "SELECT a.id,a.cid,a.uid,a.ruid,a.title,a.content,a.addtime,a.edittime,a.views,a.comments,a.closecomment,a.favorites,a.visible,u.avatar as uavatar,u.name as author
    FROM yunbbs_articles a 
    LEFT JOIN yunbbs_users u ON a.uid=u.id
    WHERE a.id='$tid'";
$t_obj = $DBS->fetch_one_array($query);
if($t_obj){
    if(!$t_obj['visible']){
        if($cur_user && $cur_user['flag']>=99){
            exit('404: <a href="/">Go back HomePage</a> <a href="/admin-edit-post-'.$tid.'">Edit</a>');
        }else{
            header("HTTP/1.0 404 Not Found");
            header("Status: 404 Not Found");
            include(dirname(__FILE__) . '/404.html');
            exit;
            
        }
    }
}else{
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
    include(dirname(__FILE__) . '/404.html');
    exit;
    
}
$t_obj['addtime'] = showtime($t_obj['addtime']);
$t_obj['edittime'] = showtime($t_obj['edittime']);
if($is_spider || $tpl){
    // 手机浏览和搜索引擎访问不用 jquery.lazyload
    $t_obj['content'] = set_content($t_obj['content'], 1);
}else{
    $t_obj['content'] = set_content($t_obj['content']);
}    


// 处理正确的评论页数
$taltol_page = ceil($t_obj['comments']/$options['commentlist_num']);
if($page<0){
    header('location: /t-'.$tid);
    exit;
}else if($page==1){
    header('location: /t-'.$tid);
    exit;
}else{
    if($page>$taltol_page){
        header('location: /t-'.$tid.'-'.$taltol_page);
        exit;
    }
}


// 处理提交评论
$tip = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty($_SERVER['HTTP_REFERER']) || $_POST['formhash'] != formhash() || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) {
    	exit('403: unknown referer.');
    }
    
    $c_content = addslashes(trim($_POST['content']));
    if(($timestamp - $cur_user['lastreplytime']) > $options['comment_post_space']){
        $c_con_len = mb_strlen($c_content,'utf-8');
        if($c_con_len>=$options['comment_min_len'] && $c_con_len<=$options['comment_max_len']){
            // spam_words
            if($options['spam_words'] && $cur_user['flag']<99){
                $spam_words_arr = explode(",", $options['spam_words']);
                $check_con = ' '.$c_content;
                foreach($spam_words_arr as $spam){
                    if(strpos($check_con, $spam)){
                        // has spam word
                        $DBS->unbuffered_query("UPDATE yunbbs_users SET flag='0' WHERE id='$cur_uid'");
                        exit('403: dont post any spam.');
                    }
                }
            }
            
            $c_content = htmlspecialchars($c_content);
            $DBS->query("INSERT INTO yunbbs_comments (id,articleid,uid,addtime,content) VALUES (null,$tid, $cur_uid, $timestamp, '$c_content')");
            $DBS->unbuffered_query("UPDATE yunbbs_articles SET ruid='$cur_uid',edittime='$timestamp',comments=comments+1 WHERE id='$tid'");
            $DBS->unbuffered_query("UPDATE yunbbs_users SET replies=replies+1,lastreplytime='$timestamp' WHERE id='$cur_uid'");
            // 更新u_code
            $new_ucode = md5($cur_uid.$cur_user['password'].$cur_user['regtime'].$cur_user['lastposttime'].$timestamp);
            setcookie("cur_uid", $cur_uid, $timestamp+ 86400 * 365, '/');
            setcookie("cur_uname", $cur_uname, $timestamp+86400 * 365, '/');
            setcookie("cur_ucode", $new_ucode, $timestamp+86400 * 365, '/');
            
            $new_taltol_page = ceil(($t_obj['comments']+1)/$options['commentlist_num']);
            
            // mentions 没有提醒用户的id，等缓存自动过期，提醒有点延迟
            $mentions = find_mentions($c_content.' @'.$t_obj['author'], $cur_uname);
            if($mentions && count($mentions)<=10){
                foreach($mentions as $m_name){
                    $DBS->unbuffered_query("UPDATE yunbbs_users SET notic =  concat('$tid,', notic) WHERE name='$m_name'");
                }
            }
            
            // 跳到评论最后一页
            if($page<$new_taltol_page){
                $c_content = '';
                header('location: /t-'.$tid.'-'.$new_taltol_page);
                exit;
            }else{
                $cur_ucode = $new_ucode;
                $formhash = formhash();
            }

            
            // 若不转向
            $c_content = '';
            $t_obj['edittime'] = showtime($timestamp);
            $t_obj['comments'] += 1;
        }else{
            $tip = '评论内容字数'.$c_con_len.' 太少或太多 ('.$options['comment_min_len'].' - '.$options['comment_max_len'].')';
        }
    }else{
        $tip = '回帖最小间隔时间是 '.$options['comment_post_space'].'秒';
    }
}else{
    $c_content = '';
}

// 获取分类
$c_obj = $DBS->fetch_one_array("SELECT * FROM yunbbs_categories WHERE id='".$t_obj['cid']."'");

// 获取评论
if($t_obj['comments']){
    if($page == 0) $page = 1;
    
    $query_sql = "SELECT c.id,c.uid,c.addtime,c.content,u.avatar as avatar,u.name as author
        FROM yunbbs_comments c 
        LEFT JOIN yunbbs_users u ON c.uid=u.id
        WHERE c.articleid='$tid' ORDER BY c.id ASC LIMIT ".($page-1)*$options['commentlist_num'].",".$options['commentlist_num'];
    $query = $DBS->query($query_sql);
    $commentdb=array();
    while ($comment = $DBS->fetch_array($query)) {
        // 格式化内容
        $comment['addtime'] = showtime($comment['addtime']);
        if($is_spider || $tpl){
            // 手机浏览和搜索引擎访问不用 jquery.lazyload
            $comment['content'] = set_content($comment['content'], 1);
        }else{
            $comment['content'] = set_content($comment['content']);
        }
        $commentdb[] = $comment;
    }
    unset($comment);
    $DBS->free_result($query);
}

// 增加浏览数
$DBS->unbuffered_query("UPDATE yunbbs_articles SET views=views+1 WHERE id='$tid'");

// 如果id在提醒里则清除
if ($cur_user && $cur_user['notic'] && strpos(' '.$cur_user['notic'], $tid.',')){
    $db_user = $DBS->fetch_one_array("SELECT * FROM yunbbs_users WHERE id='".$cur_uid."'");
    
    $n_arr = explode(',', $db_user['notic']);
    foreach($n_arr as $k=>$v){
        if($v == $tid){
            unset($n_arr[$k]);
        }
    }
    $new_notic = implode(',', $n_arr);
    $DBS->unbuffered_query("UPDATE yunbbs_users SET notic = '$new_notic' WHERE id='$cur_uid'");
    
    unset($n_arr);
    unset($new_notic);
}

// 判断文章是不是已被收藏
$in_favorites = '';
if ($cur_user){
    $user_fav = $DBS->fetch_one_array("SELECT * FROM yunbbs_favorites WHERE uid='".$cur_uid."'");
    
    if($user_fav && $user_fav['content']){
        if( strpos(' ,'.$user_fav['content'].',', ','.$tid.',') ){
            $in_favorites = '1';
        }
    }
}

// 页面变量
$title = $t_obj['title'];
$newest_nodes = get_newest_nodes();
$links = get_links();
$meta_des = $c_obj['name'].' - '.$t_obj['author'].' - '.htmlspecialchars(mb_substr($t_obj['content'], 0, 150, 'utf-8'));
// 设置回复图片最大宽度
$img_max_w = 590;
$canonical = '/t-'.$t_obj['id'];

$pagefile = dirname(__FILE__) . '/templates/default/'.$tpl.'postpage.php';

include(dirname(__FILE__) . '/templates/default/'.$tpl.'layout.php');

?>
