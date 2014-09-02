<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 

echo '
<div class="title">
    <div class="float-left fs14">
        &raquo; <a href="/n-',$c_obj['id'],'">',$c_obj['name'],'</a> (',$c_obj['articles'],')
    </div>';
if($cur_user && $cur_user['flag']>4){
    echo '<div class="float-right"><a href="/newpost/',$t_obj['cid'],'" rel="nofollow" class="newpostbtn">+发新帖</a></div>';
}

echo '</script>
<script type="text/javascript" >
function trim(str){ //删除左右两端的空格
     return str.replace(/(^\s*)|(\s*$)/g, "");
 };

 function showImg(url) {
     var imgid = trim(Math.random().toString(10));
     var frameid = trim(\'frameimg\' + imgid);
     var window_id = trim(\'img\'+imgid);
     fake_url=String(trim(url+\'?\'+Math.random()));
     var target_width = trim(document.getElementById(\'topic-content\').clientWidth.toString(10));
     //Screw you IE6
     window[window_id] = trim(\'<img id="\'+window_id+\'" src="\'+fake_url+\'" width=\'+target_width+\' height="100%"  /><script>window.onload = function(){parent.document.getElementById("\'+frameid+\'").width = "\'+target_width+\'px" ;var img_width = document.getElementById("\'+window_id+\'").naturalWidth;var img_height = document.getElementById("\'+window_id+\'").naturalHeight;var asp_r = img_width/img_height;var target_width = window.parent.document.getElementById("topic-content").clientWidth;var scaler = img_width/target_width;var target_height = img_width/asp_r/scaler;parent.document.getElementById("\'+frameid+\'").height =target_height;} <\'+\'/script>\');
     var line = trim(\'<iframe class="img-window" id="\'+frameid+\'" src=javascript:parent["\'+window_id+\'"] frameBorder="0" scrolling="no" width="100%" fake_url="\'+fake_url+\'" /></iframe>\');
     document.write(String(line));
}
</script>';

echo '    <div class="c"></div>
</div>

<div class="main-box">
<div class="topic-title">
    <div class="topic-title-main float-left">
        <h1>',$t_obj['title'],'</h1>
        <div class="topic-title-date">
        <a href="/member/',$t_obj['uid'],'">',$t_obj['author'],'</a> ',$t_obj['addtime'],' • ',$t_obj['views'],'点击';
if($t_obj['favorites']){
    echo ' • ',$t_obj['favorites'],'收藏';
}
if($cur_user && $cur_user['flag']>4){
    if(!$t_obj['closecomment']){
        echo ' • <a href="#new-comment">回复</a>';
    }
    if($in_favorites){
        echo ' • <a href="/favorites?act=del&id=',$t_obj['id'],'" title="点击取消收藏">取消收藏</a>';
    }else{
        echo ' • <a href="/favorites?act=add&id=',$t_obj['id'],'" title="点击收藏">收藏</a>';
    }
    
    if($cur_user['flag']>=99){
        echo ' &nbsp;&nbsp;&nbsp; • <a href="/admin-edit-post-',$t_obj['id'],'">编辑</a>';
    }
    if($cur_user['id']==$t_obj['uid']&&$cur_user['flag']<99){
        echo ' &nbsp;&nbsp;&nbsp; • <a href="/user-edit-post-',$t_obj['id'],'">编辑</a>';
    }
}
echo '        </div>
    </div>
    <div class="detail-avatar"><a href="/member/',$t_obj['uid'],'"><img src="/avatar/normal/',$t_obj['uavatar'],'.png" alt="',$t_obj['author'],'" />    </a></div>
    <div class="c"></div>
</div>
<div class="topic-content" id="topic-content">

<p>',$t_obj['content'],'</p>

</div>

</div>
<!-- post main content end -->';

if($t_obj['comments']){
echo '
<div class="title">
    ',$t_obj['comments'],' 回复  |  直到 ',$t_obj['edittime'],'
</div>
<div class="main-box home-box-list">';

$count_n = ($page-1)*$options['commentlist_num'];
foreach($commentdb as $comment){
$count_n += 1;
echo '
    <div class="commont-item">
        <div class="commont-avatar"><a href="/member/',$comment['uid'],'"><img src="/avatar/mini/',$comment['avatar'],'.png" alt="',$comment['author'],'" /></a></div>
        <div class="commont-data">
            <div class="commont-content">
            <p>',$comment['content'],'</p>
            </div>
            
            <div class="commont-data-date">
                <div class="float-left"><a href="/member/',$comment['uid'],'">',$comment['author'],'</a> at ',$comment['addtime'];
if($cur_user && $cur_user['flag']>=99){
    echo ' &nbsp;&nbsp;&nbsp; • <a href="/admin-edit-comment-',$comment['id'],'">编辑</a>';
}
                echo '</div>
                <div class="float-right">';
if(!$t_obj['closecomment'] && $cur_user && $cur_user['flag']>4 && $cur_user['name'] != $comment['author']){
    echo '&laquo; <a href="#new-comment" onclick="replyto(\'',$comment['author'],'\');">回复</a>'; 
}
echo '                <span class="commonet-count">',$count_n,'</span></div>
                <div class="c"></div>
            </div>
            <div class="c"></div>
        </div>
        <div class="c"></div>
    </div>';
}

if($t_obj['comments'] > $options['commentlist_num']){ 
echo '<div class="pagination">';
if($page>1){
echo '<a href="/t-',$tid,'-',$page-1,'" class="float-left">&laquo; 上一页</a>';
}
if($page<$taltol_page){
echo '<a href="/t-',$tid,'-',$page+1,'" class="float-right">下一页 &raquo;</a>';
}
echo '<div class="c"></div>
</div>';
}

echo '
    
</div>
<!-- comment list end -->

<script type="text/javascript">
function replyto(somebd){
    var con = document.getElementById("id-content").value;
    document.getElementsByTagName(\'textarea\')[0].focus();
    document.getElementById("id-content").value = " @"+somebd+" " + con;
}
</script>';

}else{
    echo '<div class="no-comment">目前尚无回复</div>';
}

if($t_obj['closecomment']){
    echo '<div class="no-comment">该帖评论已关闭</div>';
}else{

if($cur_user && $cur_user['flag']>4){

echo '<a name="new-comment"></a>
<div class="title">
    <div class="float-left">添加一条新回复</div>
    <div class="float-right"><a href="#">↑ 回到顶部</a></div>
    <div class="c"></div>    
</div>
<div class="main-box">';
if($tip){
    echo '<p class="red">',$tip,'</p>';
}
echo '    <form action="',$_SERVER["REQUEST_URI"],'#new-comment" method="post">
<input type="hidden" name="formhash" value="',$formhash,'" />
    <p><textarea id="id-content" name="content" class="comment-text mll wb96">',htmlspecialchars($c_content),'</textarea></p>
    <p><input type="submit" value=" 提 交 " name="submit" class="textbtn wb96" /></p>
    <p class="fs12 grey">请尽量让自己的回复能够对别人有帮助</p>
    </form>
</div>
<!-- new comment end -->';

}else{
    echo '<div class="no-comment">请 <a href="/login" rel="nofollow">登录</a> 后发表评论</div>';
}

}


?>