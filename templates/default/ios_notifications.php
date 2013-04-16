<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 

echo '
<div class="title">
    站内提醒  &raquo;  有人在下面的文章回复或提起了你
</div>

<div class="main-box home-box-list">';

if($articledb){

foreach($articledb as $article){
echo '
<div class="post-list">
    <div class="item-avatar"><a href="/member/',$article['uid'],'"><img src="/avatar/mini/',$article['uavatar'],'.png" alt="',$article['author'],'" /></a></div>
    <div class="item-content count',$article['comments'],'">
        <h1><a href="/goto-t-',$article['id'],'">',$article['title'],'</a></h1>
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
    echo '<div class="item-count"><a href="/goto-t-',$article['id'],'">',$article['comments'],'</a></div>';
}
echo '    <div class="c"></div>
</div>';

}

}else{
    echo '<p>&nbsp;&nbsp;&nbsp;暂无未读提醒</p>';
}

echo '</div>';

?>