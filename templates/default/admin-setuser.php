<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 
echo '
<a name="4"></a>
<div class="title"><a href="/">',$options['name'],'</a> &raquo; ',$title,'  &raquo; 用户名： <span class="red">',$m_obj['name'],'</span></div>
<div class="main-box">
<p class="red">',$tip4,'</p>
<form method="post" action="',$_SERVER["REQUEST_URI"],'#4">
<input type="hidden" name="action" value="setflag"/>
<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs12">
    <tbody>
    <tr>
        <td width="120" align="right">设置权限</td>
        <td width="auto" align="left"><input type="text" class="sl w100" name="flag" value="',htmlspecialchars($m_obj['flag']),'" /> 输入数字:0~99</td>
    </tr>
    <tr>
        <td width="120" align="right">权限说明</td>
            <td width="auto" align="left">
0: 禁用，不能发帖子、回复；<br/>
1: 等待审核，当开启注册用户审核才有效；<br/>
5: 一般用户，可发帖子、回复；<br/>
99： 管理员。
</td>
        </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value="保存设置" name="submit" class="textbtn" /></td>
    </tr>
    
</tbody></table>
</form>

</div>

<a name="1"></a>
<div class="title">',$title,' <span class="red">',$m_obj['name'],'</span></div>
<div class="main-box">
<p class="red">',$tip1,'</p>
<form method="post" action="',$_SERVER["REQUEST_URI"],'#1">
<input type="hidden" name="action" value="info"/>
<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs12">
    <tbody>
    <tr>
        <td width="120" align="right">电子邮件</td>
        <td width="auto" align="left"><input type="text" class="sl" name="email" value="',htmlspecialchars(stripslashes($m_obj['email'])),'" /> 不公开，仅供取回密码</td>
    </tr>
    <tr>
        <td width="120" align="right">个人网站</td>
        <td width="auto" align="left"><input type="text" class="sl" name="url" value="',htmlspecialchars(stripslashes($m_obj['url'])),'" /></td>
    </tr>
    <tr>
        <td width="120" align="right">个人简介</td>
        <td width="auto" align="left"><textarea class="ml" name="about">',htmlspecialchars(stripslashes($m_obj['about'])),'</textarea></td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value="保存设置" name="submit" class="textbtn" /></td>
    </tr>
    
</tbody></table>
</form>

</div>

<a name="2"></a>
<div class="title">为<span class="red">',$m_obj['name'],'</span>设置头像</div>
<div class="main-box">
<p class="red">',$tip2,'</p>
<form action="',$_SERVER["REQUEST_URI"],'#2" enctype="multipart/form-data" method="post">
<input type="hidden" name="action" value="avatar" />
<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs12">
    <tbody><tr>
        <td width="120" align="right">当前头像</td>
        <td width="auto" align="left">
        <img src="/avatar/large/',$m_obj['avatar'],'.png?',$av_time,'" class="avatar" border="0" align="default" auto=""> &nbsp; 
        <img src="/avatar/normal/',$m_obj['avatar'],'.png?',$av_time,'" class="avatar" border="0" align="default" auto=""> &nbsp; 
        <img src="/avatar/mini/',$m_obj['avatar'],'.png?',$av_time,'" class="avatar" border="0" align="default" auto="">
        </td>
    </tr>
    <tr>
        <td width="120" align="right">选择头像图片</td>
        <td width="auto" align="left"><input name="avatar" type="file" /> (最大300K)</td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value="更新头像" name="submit" class="textbtn" /></td>
    </tr>
    
</tbody></table>
</form>

</div>

<a name="3"></a>
<div class="title">为<span class="red">',$m_obj['name'],'</span>重设密码</div>
<div class="main-box">
<p class="red">',$tip3,'</p>
<form method="post" action="',$_SERVER["REQUEST_URI"],'#3">
<input type="hidden" name="action" value="chpw" />
<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs12">
    <tbody>
    <tr>
        <td width="120" align="right">新密码</td>
        <td width="auto" align="left"><input type="text" class="sl" name="password_new" value="" /></td>
    </tr>
    <tr>
        <td width="120" align="right">再次输入新密码</td>
        <td width="auto" align="left"><input type="text" class="sl" name="password_again" value="" /></td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value="重设密码" name="submit" class="textbtn" /></td>
    </tr>
    
</tbody></table>
</form>

</div>';
?>
