<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 

echo '
	<script src="/static/js/jquery-1.6.4.js" type="text/javascript"></script>
	<script src="/static/js/jquery.markitup.js" type="text/javascript"></script>
	<script src="/static/js/set.js" type="text/javascript"></script>
	<script type="text/javascript" >
	   $(document).ready(function() {
	      $("#markdown").markItUp(mySettings);
	   });
	</script>
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
<p><textarea id="markdown" name="content" class="comment-text mll">',$r_content,'</textarea></p>';

if(!$options['close_upload']){
    echo '<iframe name="imageupload" height="100px" width="350px" src="/imageupload.html" frameborder="0"></iframe>';
}

echo '
<p><input type="submit" value=" 保 存 " name="submit" id="id-comment-submit" class="textbtn" /></p>
</form>
<div class="float-right fs12 grey">支持Ctrl+Enter提交    请尽量让自己的回复能够对别人有帮助</div>
<a href="/t-',$r_obj['articleid'],'">查看这条评论所在的帖子</a>
</div>
<script type="text/javascript" >
document.getElementById("markdown").addEventListener("keypress",function (e){
  if (e.keyCode === 10 || (e.ctrlKey && e.keyCode === 13)) {
    document.getElementById("id-comment-submit").click()
  };
});
</script>
';


?>
