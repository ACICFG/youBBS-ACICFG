<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 
echo '
<a name="4"></a>
<div class="title"><a href="/">',$options['name'],'</a> &raquo; ',$title,'  &raquo; 用户名： <span class="red">',$m_obj['name'],'</span></div>
<div class="main-box">';
if($tip4){
echo '<p class="red">',$tip4,'</p>';
}
echo '

<form method="post" action="',$_SERVER["REQUEST_URI"],'#4">
<input type="hidden" name="action" value="setflag"/>
<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs12">
    <tbody>
    <tr>
        <td width="120" align="right">设置权限</td>
        <td width="82%" align="left"><input type="text" class="sl w100" name="flag" value="',htmlspecialchars($m_obj['flag']),'" /> 输入数字:0~99</td>
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
<div class="main-box">';
if($tip1){
echo '<p class="red">',$tip1,'</p>';
}
echo '
<form method="post" action="',$_SERVER["REQUEST_URI"],'#1">
<input type="hidden" name="action" value="info"/>
<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs12">
    <tbody>
    <tr>
        <td width="120" align="right">电子邮件</td>
        <td width="80%" align="left"><input type="text" class="sl wb80" name="email" value="',htmlspecialchars(stripslashes($m_obj['email'])),'" /> <br/>不公开，仅供取回密码</td>
    </tr>
    <tr>
        <td width="120" align="right">个人网站</td>
        <td width="auto" align="left"><input type="text" class="sl wb80" name="url" value="',htmlspecialchars(stripslashes($m_obj['url'])),'" /></td>
    </tr>
    <tr>
        <td width="120" align="right">个人简介</td>
        <td width="auto" align="left"><textarea class="ml wb80" name="about">',htmlspecialchars(stripslashes($m_obj['about'])),'</textarea></td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value="保存设置" name="submit" class="textbtn" /></td>
    </tr>
    
</tbody></table>
</form>

</div>

<a name="3"></a>
<div class="title">为<span class="red">',$m_obj['name'],'</span>重设密码</div>
<div class="main-box">';
if($tip3){
echo '<p class="red">',$tip3,'</p>';
}
echo '
<form method="post" action="',$_SERVER["REQUEST_URI"],'#3">
<input type="hidden" name="action" value="chpw" />
<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs12">
    <tbody>
    <tr>
        <td width="120" align="right">新密码</td>
        <td width="80%" align="left"><input type="text" class="sl wb80" name="password_new" value="" /></td>
    </tr>
    <tr>
        <td width="120" align="right">再次输入新密码</td>
        <td width="auto" align="left"><input type="text" class="sl wb80" name="password_again" value="" /></td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td width="auto" align="left"><input type="submit" value="重设密码" name="submit" class="textbtn" /></td>
    </tr>
    
</tbody></table>
</form>

</div>';
?>
