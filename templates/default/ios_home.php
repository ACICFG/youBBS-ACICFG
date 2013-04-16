<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 

echo '
<div class="title">
    <div class="float-left">
        &raquo; 最近更新
    </div>';
if($cur_user && $cur_user['flag']>4 && $newest_nodes){
    echo '<div class="float-right"><a href="/newpost/1" rel="nofollow" class="newpostbtn">+发新帖</a></div>';
}
echo '    <div class="c"></div>
</div>

<div class="main-box home-box-list">';

foreach($articledb as $article){
echo '
<div class="post-list">
    <div class="item-avatar"><a href="/member/',$article['uid'],'">
    <img src="/avatar/mini/',$article['uavatar'],'.png" alt="',$article['author'],'" />
    </a></div>
    <div class="item-content count',$article['comments'],'">
        <h1><a href="/t-',$article['id'],'">',$article['title'],'</a></h1>
        <span class="item-date"><a href="/n-',$article['cid'],'">',$article['cname'],'</a>';
if($article['comments']){
    echo ' • <a href="/member/',$article['ruid'],'">',$article['rauthor'],'</a> ',$article['edittime'],'回复';
}else{
    echo ' • <a href="/member/',$article['uid'],'">',$article['author'],'</a> ',$article['addtime'],'发表';
}
echo '        </span>
    </div>';
if($article['comments']){
    $gotopage = ceil($article['comments']/$options['commentlist_num']);
    if($gotopage == 1){
        $c_page = '';
    }else{
        $c_page = '-'.$gotopage;
    }
    echo '<div class="item-count"><a href="/t-',$article['id'],$c_page,'#reply',$article['comments'],'">',$article['comments'],'</a></div>';
}
echo '    <div class="c"></div>
</div>';

}


if(count($articledb) == $options['home_shownum']){ 
echo '<div class="pagination">';
echo '<a href="/page/2" class="float-right">下一页 &raquo;</a>';
echo '<div class="c"></div>
</div>';
}


echo '</div>';


?>