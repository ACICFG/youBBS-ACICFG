<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 

echo '
<form action="',$_SERVER["REQUEST_URI"],'" method="post">
<input type="hidden" name="formhash" value="',$formhash,'" />
<div class="title">
    &raquo; ';
if($options['main_nodes']){
    echo '<select name="select_cid">';
    foreach($main_nodes_arr as $n_id=>$n_name){
        if($cid == $n_id){
            $sl_str = ' selected="selected"';
        }else{
            $sl_str = '';
        }
        echo '<option value="',$n_id,'"',$sl_str,'>',$n_name,'</option>';
    }
    echo '</select>';
}else{    
    echo '    <a href="/n-',$c_obj['id'],'">',$c_obj['name'],'</a> (',$c_obj['articles'],') ';
}
echo '- 发新帖
</div>

<div class="main-box">';
if($tip){
    echo '<p class="red">',$tip,'</p>';
}
echo '
<p>
<input type="text" name="title" value="',htmlspecialchars($p_title),'" class="sll wb96" />
</p>
<p><textarea id="id-content" name="content" class="mll wb96 tall">',htmlspecialchars($p_content),'</textarea></p>
<p><input type="submit" value=" 发 表 " name="submit" class="textbtn wb96" /></p>
</form>

<p class="fs12 c666">发帖指南：</p>
<p class="fs12 c666">
字数限制： 标题最多字数',$options['article_title_max_len'],'，内容最多字数：',$options['article_content_max_len'],'<br/>
纯文本格式，不支持html 或 ubb 代码<br/>
贴图： 可直接粘贴图片地址，如 http://www.baidu.com/xxx.gif （支持jpg/gif/png后缀名），也可直接上传<br/>
贴视频： 可直接视频地址栏里的网址，如 http://www.tudou.com/programs/view/PAH86KJNoiQ/ （仅支持土豆/优酷/QQ）<br/>
</p>
<div class="c"></div>
</div>';


?>