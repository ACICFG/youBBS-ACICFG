<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 

echo '
<a name="1"></a>
<div class="title">
    <a href="/">',$options['name'],'</a> &raquo; - '.$title,'
</div>

<div class="main-box">';
if($tip1){
    echo ' <p class="red">',$tip1,'</p>';
}

echo '
<form action="',$_SERVER["REQUEST_URI"],'#1" method="post">
<input type="hidden" name="action" value="base"/>
<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs12">
    <tbody>
    <tr bgcolor="#EEE" height="24">
        <td width="120" align="right"> » </td>
        <td width="82%" align="left">基本设置</td>
    </tr>
    <tr>
        <td width="120" align="right">网站名称</td>
        <td width="auto" align="left"><input type="text" class="sl wb50" name="name" value="',htmlspecialchars($options['name']),'" /> <a href="http://youbbs.sinaapp.com/t-425" title="查看帮助" target="_blank">?</a></td>
    </tr>
    <tr>
        <td width="120" align="right">网站描述</td>
        <td width="auto" align="left"><textarea class="ml wb80 h60" name="site_des">',str_replace('\\', '', $options['site_des']),'</textarea> <br/>给搜索引擎看的，150字以内</td>
    </tr>
    <tr>
        <td width="120" align="right">备案号</td>
        <td width="auto" align="left"><input type="text" class="sl wb60" name="icp" value="',$options['icp'],'" /> <br/>若有就填，如 京ICP证0603xx号</td>
    </tr>
    <tr>
        <td width="120" align="right">管理员邮箱</td>
        <td width="auto" align="left"><input type="text" class="sl wb60" name="admin_email" value="',$options['admin_email'],'" /> <br/>用来接收密码重设请求，请正确填写</td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value=" 保 存 " name="submit" class="textbtn" /></td>
    </tr>
    <tr bgcolor="#EEE" height="24">
        <td width="120" align="right"> » </td>
        <td width="82%" align="left">微博登录设置（可选，不用则留空） <a href="http://youbbs.sinaapp.com/t-426" title="查看帮助" target="_blank">?</a></td>
    </tr>
    <tr>
        <td width="120" align="right">App Key</td>
        <td width="auto" align="left"><input type="text" class="sl wb60" name="wb_key" value="',$options['wb_key'],'" /></td>
    </tr>
    <tr>
        <td width="120" align="right">App Secret</td>
        <td width="auto" align="left"><input type="text" class="sl wb60" name="wb_secret" value="',$options['wb_secret'],'" /></td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value=" 保 存 " name="submit" class="textbtn" /></td>
    </tr>
    <tr bgcolor="#EEE" height="24">
        <td width="120" align="right"> » </td>
        <td width="82%" align="left">QQ登录设置（可选，不用则留空），参考<a href="http://youbbs.sinaapp.com/t-263" target="_blank">用QQ登录</a></td>
    </tr>
    <tr>
        <td width="120" align="right">scope</td>
        <td width="auto" align="left"><input type="text" class="sl wb60" name="qq_scope" value="',$options['qq_scope'],'" /> <br/>默认get_user_info，可选get_info</td>
    </tr>
    <tr>
        <td width="120" align="right">appid</td>
        <td width="auto" align="left"><input type="text" class="sl wb60" name="qq_appid" value="',$options['qq_appid'],'" /></td>
    </tr>
    <tr>
        <td width="120" align="right">appkey</td>
        <td width="auto" align="left"><input type="text" class="sl wb60" name="qq_appkey" value="',$options['qq_appkey'],'" /></td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value=" 保 存 " name="submit" class="textbtn" /></td>
    </tr>
    <tr bgcolor="#EEE" height="24">
        <td width="120" align="right"> » </td>
        <td width="auto" align="left">附件设置</td>
    </tr>
    <tr>
        <td width="120" align="right">关闭上传附件功能</td>
        <td width="auto" align="left"><input type="text" class="sl wb50" name="close_upload" value="',$options['close_upload'],'" /> <br/>默认0（开放），若关闭上传功能就设为1</td>
    </tr>
    <tr>
        <td width="120" align="right">允许扩展名</td>
        <td width="auto" align="left"><input type="text" class="sl wb60" name="ext_list" value="',$options['ext_list'],'" /> <br/>默认留空，用逗号分隔</td>
    </tr>
    
    <tr>
        <td width="120" align="right">是否添加图片水印</td>
        <td width="auto" align="left"><input type="text" class="sl w50" name="img_shuiyin" value="',$options['img_shuiyin'],'" /> <br/>默认0（不加水印），添加水印则设为1</td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left">附件默认上传到服务器，若要上传到又拍云请正确填写下面三个信息，否则留空</td>
    </tr>
    <tr>
        <td width="120" align="right">空间名称</td>
        <td width="auto" align="left"><input type="text" class="sl wb50" name="upyun_domain" value="',$options['upyun_domain'],'" /> <br/>默认留空，不能包含特殊字符:"\'<></td>
    </tr>
    <tr>
        <td width="120" align="right">操作员</td>
        <td width="auto" align="left"><input type="text" class="sl wb50" name="upyun_user" value="',$options['upyun_user'],'" /> <br/>默认留空，不能包含特殊字符:"\'<></td>
    </tr>
    <tr>
        <td width="120" align="right">操作员密码</td>
        <td width="auto" align="left"><input type="text" class="sl wb50" name="upyun_pw" value="',$options['upyun_pw'],'" /> <br/>默认留空，不能包含特殊字符:"\'<></td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value=" 保 存 " name="submit" class="textbtn" /></td>
    </tr>
    
    <tr bgcolor="#EEE" height="24">
        <td width="120" align="right"> » </td>
        <td width="auto" align="left">扩展设置</td>
    </tr>
    <tr>
        <td width="120" align="right">主分类id</td>
        <td width="auto" align="left"><input type="text" class="sl wb50" name="main_nodes" value="',$options['main_nodes'],'" /> <br/>默认留空，发新帖时可选的主分类id，用逗号隔开 <a href="http://youbbs.sinaapp.com/t-427" title="查看帮助" target="_blank">?</a></td>
    </tr>
    <tr>
        <td width="120" align="right">放在页面头部<br/>head标签里面的<br/>meta或其它信息<br/>(默认留空)</td>
        <td width="auto" align="left"><textarea class="ml wb80" name="head_meta">',str_replace('\\', '', $options['head_meta']),'</textarea> 
        示例:<br/> 
        ',htmlspecialchars('<meta property="qc:admins" content="331146677212163161xxxxxxx" />'),'<br/> 
        ',htmlspecialchars('<meta name="cpalead-verification" content="ymEun344mP9vt-B2idFRxxxxxxx" />'),'
        </td>
    </tr>
    <tr>
        <td width="120" align="right">放在页面底部的<br/>统计代码<br/>(默认留空)</td>
        <td width="auto" align="left"><textarea class="ml wb80" name="analytics_code">',str_replace('\\', '', $options['analytics_code']),'</textarea> 示例： 直接粘贴google 或 百度统计代码</td>
    </tr>
    <tr>
        <td width="120" align="right">垃圾信息关键字<br/>一般是网址(默认留空)</td>
        <td width="auto" align="left"><textarea class="ml wb80" name="spam_words">',$options['spam_words'],'</textarea> 网址格式"www.xxx.com"，用逗号分隔，当用户发布的信息包含某个垃圾关键字时会自动禁用该用户，请勿滥用！</td>
    </tr>
    <tr>
        <td width="120" align="right">安全图床域名<br/>参见 <a href="http://www.saespot.com/t-3-7" target="_blank">外链图片安全</a><br/>(默认留空)</td>
        <td width="auto" align="left"><textarea class="ml wb80" name="safe_imgdomain">',str_replace("|", "\n", $options['safe_imgdomain']),'</textarea> 示例： ww2.sinaimg.cn (注意，不含斜杠，每行一个域名) <a href="http://youbbs.sinaapp.com/t-428" title="查看帮助" target="_blank">?</a></td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value=" 保 存 " name="submit" class="textbtn" /></td>
    </tr>
    <tr bgcolor="#EEE" height="24">
        <td width="120" align="right"> » </td>
        <td width="auto" align="left">定位广告设置</td>
    </tr>
    <tr>
        <td width="120" align="right">文章内容顶部<br/>(默认留空)</td>
        <td width="auto" align="left"><textarea class="ml wb80" name="ad_post_top">',str_replace('\\', '', $options['ad_post_top']),'</textarea> 示例： 直接粘贴google 或 百度广告代码</td>
    </tr>
    <tr>
        <td width="120" align="right">文章内容底部<br/>(默认留空)</td>
        <td width="auto" align="left"><textarea class="ml wb80" name="ad_post_bot">',str_replace('\\', '', $options['ad_post_bot']),'</textarea> 示例： 直接粘贴google 或 百度广告代码</td>
    </tr>
    <tr>
        <td width="120" align="right">侧栏顶部<br/>(默认留空)</td>
        <td width="auto" align="left"><textarea class="ml wb80" name="ad_sider_top">',str_replace('\\', '', $options['ad_sider_top']),'</textarea> 示例： 直接粘贴google 或 百度广告代码</td>
    </tr>
    <tr>
        <td width="120" align="right">手机浏览页面底部<br/>(默认留空)</td>
        <td width="auto" align="left"><textarea class="ml wb80" name="ad_web_bot">',str_replace('\\', '', $options['ad_web_bot']),'</textarea> 示例： 直接粘贴google 或 百度广告代码</td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value=" 保 存 " name="submit" class="textbtn" /></td>
    </tr>
    
    <tr bgcolor="#EEE" height="24">
        <td width="120" align="right"> » </td>
        <td width="auto" align="left">其它设置（下面一般保持默认）</td>
    </tr>
    <tr>
        <td width="120" align="right">首页显示帖子数</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="home_shownum" value="',$options['home_shownum'],'" /> 默认20</td>
    </tr>
    <tr>
        <td width="120" align="right">每页显示帖子数</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="list_shownum" value="',$options['list_shownum'],'" /> 默认20</td>
    </tr>
    <tr>
        <td width="120" align="right">最近添加的分类数</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="newest_node_num" value="',$options['newest_node_num'],'" /> 默认20</td>
    </tr>
    <tr>
        <td width="120" align="right">最热主题数</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="hot_node_num" value="',$options['hot_node_num'],'" /> 默认20（要小于',$options['bot_node_num'],'）</td>
    </tr>
    <tr>
        <td width="120" align="right">首页底部热门分类数</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="bot_node_num" value="',$options['bot_node_num'],'" /> 默认100（要大于',$options['hot_node_num'],'，总分类数大于',$options['newest_node_num'],'底部才会显示）</td>
    </tr>
    <tr>
        <td width="120" align="right">帖子标题最多字数</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="article_title_max_len" value="',$options['article_title_max_len'],'" /> 默认60</td>
    </tr>
    <tr>
        <td width="120" align="right">帖子内容最多字数</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="article_content_max_len" value="',$options['article_content_max_len'],'" /> 默认3000</td>
    </tr>
    <tr>
        <td width="120" align="right">发帖子最小间隔时间</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="article_post_space" value="',$options['article_post_space'],'" />秒 默认60</td>
    </tr>
    <tr>
        <td width="120" align="right">注册最小间隔时间</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="reg_ip_space" value="',$options['reg_ip_space'],'" />秒 默认3600秒</td>
    </tr>
    
    <tr>
        <td width="120" align="right">回复内容最少字数</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="comment_min_len" value="',$options['comment_min_len'],'" /> 默认4</td>
    </tr>
    <tr>
        <td width="120" align="right">回复内容最多字数</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="comment_max_len" value="',$options['comment_max_len'],'" /> 默认1200</td>
    </tr>
    <tr>
        <td width="120" align="right">每页显示回复数</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="commentlist_num" value="',$options['commentlist_num'],'" /> 默认32</td>
    </tr>
    <tr>
        <td width="120" align="right">发表回复最小间隔时间</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="comment_post_space" value="',$options['comment_post_space'],'" /> 默认20</td>
    </tr>
    <tr>
        <td width="120" align="right">网站暂时关闭</td>
        <td width="auto" align="left"><input type="text" class="sl w50" name="close" value="',$options['close'],'" /> 默认0，若要暂时关闭就设为1</td>
    </tr>
    <tr>
        <td width="120" align="right">网站暂时关闭提示</td>
        <td width="auto" align="left"><input type="text" class="sl w200" name="close_note" value="',$options['close_note'],'" /> <br/>默认"数据调整中"，简单写明关闭原因</td>
    </tr>
    <tr>
        <td width="120" align="right">只给登录用户访问</td>
        <td width="auto" align="left"><input type="text" class="sl w50" name="authorized" value="',$options['authorized'],'" /> 默认0（公开），若要只给登录用户访问就设为1（适合内部交流）</td>
    </tr>
    <tr>
        <td width="120" align="right">用户注册需要验证</td>
        <td width="auto" align="left"><input type="text" class="sl w50" name="register_review" value="',$options['register_review'],'" /> 默认0（不用验证），若需要管理员验证就设为1（适合内部交流）</td>
    </tr>
    <tr>
        <td width="120" align="right">关闭用户注册</td>
        <td width="auto" align="left"><input type="text" class="sl w50" name="close_register" value="',$options['close_register'],'" /> 默认0，若停止新用户注册就设为1</td>
    </tr>
    <tr>
        <td width="120" align="right">页底显示调试信息</td>
        <td width="auto" align="left"><input type="text" class="sl w50" name="show_debug" value="',$options['show_debug'],'" /> 显示运行时间和数据库操作次数，默认0（不显示），若想显示就设为1</td>
    </tr>
    <tr>
        <td width="120" align="right">调用jquery库地址</td>
        <td width="auto" align="left"><input type="text" class="sl wb60" name="jquery_lib" value="',$options['jquery_lib'],'" /> <br/>默认/static/js/jquery-1.6.4.js</td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value=" 保 存 " name="submit" class="textbtn" /></td>
    </tr>
    
</tbody></table>
</form>
</div>';


echo '
<a name="2"></a>
<div class="title">清空缓存</div>

<div class="main-box">';
if($tip2){
    echo ' <p class="red">',$tip2,'</p>';
}
echo '

<form action="',$_SERVER["REQUEST_URI"],'#2" method="post">
<input type="hidden" name="action" value="flushmc"/>
<p>当数据更新时，对应的主要缓存会更新，一般不要清空全部缓存</p>
<p><input type="submit" value=" 清空全部缓存 " name="submit" class="textbtn" /></p>
</form>
</div>';


echo '
<a name="3"></a>
<div class="title">清除所有数据并重新安装</div>

<div class="main-box">';
if($tip3){
    echo ' <p class="red">',$tip3,'</p>';
}
echo '

<form action="',$_SERVER["REQUEST_URI"],'#3" method="post" onSubmit="javascript:return window.confirm(\'你确认要删除网站所有数据吗？\')">
<input type="hidden" name="action" value="flushdata"/>
<p class="red">清除所有数据，操作不可恢复。没事别点下面按钮</p>
<p><input type="submit" value=" 清除所有数据 " name="submit" class="textbtn" /></p>
</form>
</div>
';

?>
