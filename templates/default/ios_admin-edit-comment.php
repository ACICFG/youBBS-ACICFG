<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 

echo '
<div class="title">
    <a href="/">',$options['name'],'</a> &raquo; - 修改评论';
echo '
</div>

<div class="main-box">';
if($tip){
    echo '<p class="red">',$tip,'</p>';
}

echo '
<form action="',$_SERVER["REQUEST_URI"],'" method="post">
<p><textarea id="id-content" name="content" class="comment-text mll wb96">',$r_content,'</textarea></p>
<p><input type="submit" value=" 保 存 " name="submit" class="textbtn wb96" /></p>
</form>
<a href="/t-',$r_obj['articleid'],'">查看这条评论所在的帖子</a>
</div>';


?>
