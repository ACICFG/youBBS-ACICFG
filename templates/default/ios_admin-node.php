<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 

echo '
<a name="add"></a>
<div class="title">
    <a href="/">',$options['name'],'</a> &raquo; - 添加分类
</div>

<div class="main-box">';
if($tip1){
    echo '<p class="red">',$tip1,'</p>';
}

echo '
<form action="',$_SERVER["REQUEST_URI"],'#add" method="post">
<input type="hidden" name="action" value="add"/>
<p>输入新分类名： <br/><input type="text" class="sl wb40" name="name" value="" /><br/>
分类简介： (255个字节以内)<br/>
<textarea class="ml wb96" name="about"></textarea><br/>
<input type="submit" value=" 添 加 " name="submit" class="textbtn" /></p>
<p class="grey fs12">注：分类添加后不能删除，只能修改。</p>
</form>
</div>';

echo '
<a name="edit"></a>
<div class="title">修改分类</div>

<div class="main-box">';
if($tip2){
    echo '<p class="red">',$tip2,'</p>';
}

echo '
<form action="',$_SERVER["REQUEST_URI"],'#edit" method="post">';
if($c_obj){
echo '
<input type="hidden" name="action" value="edit"/>
<p>分类名： <input type="text" class="sl wb40" name="name" value="',htmlspecialchars($c_obj['name']),'" /><br/>
分类简介： (255个字节以内)<br/>
<textarea class="ml w500" name="about">',htmlspecialchars(stripslashes($c_obj['about'])),'</textarea><br/>
<input type="submit" value=" 保 存 " name="submit" class="textbtn" /></p>';

}else{
echo '
<input type="hidden" name="action" value="find"/>
<p>输入分类id查找： 如红色部分：n-<span class="red">1</span><br/><input type="text" class="sl wb40" name="findid" value="" /> 
<input type="submit" value=" 查 找 " name="submit" class="textbtn" /></p>';

}

echo '</form>
</div>';

?>
