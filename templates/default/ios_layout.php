<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 
ob_start();

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>',$title,'</title>
<meta content="True" name="HandheldFriendly" />
<meta name="viewport" content="maximum-scale=1.0,width=device-width,initial-scale=1.0" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<link href="/static/default/style_ios.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="top" title="Back to Top" href="#" />
';
if($options['head_meta']){
    echo $options['head_meta'];
}

if(isset($meta_des) && $meta_des){
    echo '<meta name="description" content="',$meta_des,'" />';
}
if(isset($canonical)){
    echo '<link rel="canonical" href="http://',$_SERVER['HTTP_HOST'],$canonical,'" />';
}

echo '
</head>
<body>
<div class="header-wrap">
    <div class="header">
        <div class="logo"><a href="/" name="top">',htmlspecialchars($options['name']),'</a></div>
        <div class="banner">';
        
if($cur_user){
    echo '<a href="/member/',$cur_user['id'],'"><img src="/avatar/mini/',$cur_user['avatar'],'.png" alt="',$cur_user['name'],'"/></a>&nbsp;&nbsp;<a href="/favorites">★</a>&nbsp;&nbsp;&nbsp;<a href="/setting">设置</a>&nbsp;&nbsp;<a href="/logout">退出</a>';
}else{
    if($options['wb_key'] && $options['wb_secret']){
        echo '<a href="/wblogin" rel="nofollow"><img src="/static/weibo_login_55_24.png" alt="微博登录"/></a>';
    }
    if($options['qq_appid'] && $options['qq_appkey']){
        echo '<a href="/qqlogin" rel="nofollow"><img src="/static/qq_logo_55_24.png" alt="QQ登录"/></a>';
    }
    echo '&nbsp;<a href="/login" rel="nofollow">登录</a>';
    if(!($options['wb_key'] && $options['wb_secret']) && !($options['qq_appid'] && $options['qq_appkey'])){
        if(!$options['close_register']){
            echo '&nbsp;&nbsp;&nbsp;<a href="/sigin">注册</a>';
        }
    }
}
echo '       </div>
        <div class="c"></div>
    </div>
    <!-- header end -->
</div>

<div class="main-wrap">
    <div class="main">
        <div class="main-content">';

if($cur_user){
    if($cur_user['flag'] == 0){
        echo '<div class="tiptitle">站内提醒 &raquo; <span style="color:yellow;">帐户已被管理员禁用</span></div>';
    }else if($cur_user['flag'] == 1){
        echo '<div class="tiptitle">站内提醒 &raquo; <span style="color:yellow;">帐户在等待管理员审核</span></div>';
    }else{
        if(!$cur_user['password']){
            echo '<div class="tiptitle">站内提醒 &raquo; <a href="/setting#3" style="color:yellow;">设置登录密码</a></div>';
        }
        if($cur_user['notic']){
            $notic_n = count(array_unique(explode(',', $cur_user['notic'])))-1;
            echo '<div class="tiptitle">站内提醒 &raquo; <a href="/notifications" style="color:yellow;">',$notic_n,'条提醒</a></div>';
        }
    }
}

if($options['close']){
echo '
<div class="tiptitle">网站暂时关闭公告 &raquo; 
<span style="color:yellow;">';
if($options['close_note']){
    echo $options['close_note'];
}else{
    echo '数据调整中。。。';
}
echo '</span>
</div>';
}

if($cur_user && $cur_user['flag']>=99){
echo '
<div class="title">管理员面板 <a href="http://youbbs.sinaapp.com/" target="_blank">youbbs官方支持</a></div>
<div class="main-box main-box-node">
<div class="btn">
<a href="/admin-node">分类管理</a><a href="/admin-setting">网站设置</a><a href="/admin-user-list">用户管理</a><a href="/admin-link-list">链接管理</a>
<div class="c"></div>
</div>

</div>';
}

include($pagefile);

if(isset($newest_nodes) && $newest_nodes){
echo '
<div class="title">最近添加的分类</div>
<div class="main-box main-box-node">
<div class="btn">';
foreach( $newest_nodes as $k=>$v ){
    echo '<a href="/',$k,'">',$v,'</a>';
}
echo '
<div class="c"></div>
</div>

</div>';
}


if(isset($bot_nodes)){
echo '
<div class="title">热门分类</div>
<div class="main-box main-box-node">
<div class="btn">';
foreach( $bot_nodes as $k=>$v ){
    echo '<a href="/',$k,'">',$v,'</a>';
}
echo '
<div class="c"></div>
</div>

</div>';
}

echo '       </div>
        <!-- main-content end -->
        <div class="c"></div>
    </div>
    <!-- main end -->
    <div class="c"></div>
</div>

<div class="footer-wrap">
    <div class="footer">
    <p class="float-left">&copy; <a href="/">',$options['name'],'</a>';
    
if($is_mobie){
    echo ' • <a href="/viewat-desktop">桌面模式</a>';
}
    
    echo '</p>
    <p class="float-right">by <a href="http://youbbs.sinaapp.com/" target="_blank">YouBBS v',SAESPOT_VER,'</a></p>
    <div class="c"></div>';

echo '    </div>
    <!-- footer end -->
</div>';

if($options['ad_web_bot']){
    echo $options['ad_web_bot'];
}

if($options['analytics_code']){
    echo $options['analytics_code'];
}

echo '
</body>
</html>';

$_output = ob_get_contents();
ob_end_clean();

// 304
$etag = md5($_output);
if($_SERVER['HTTP_IF_NONE_MATCH'] == $etag){
    header("HTTP/1.1 304 Not Modified");
    header("Status: 304 Not Modified");
    header("Etag: ".$etag);
    exit;    
}else{
    header("Etag: ".$etag);
}

echo $_output;

?>