<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

if($options['authorized'] || $options['close']){
    exit('error: 403 Access Denied');
}

// 获取最近文章列表
$query_sql = "SELECT a.id,a.cid,a.uid,a.ruid,a.title,a.content,a.addtime,a.edittime,a.comments,c.name as cname,u.name as author
    FROM yunbbs_articles a 
    LEFT JOIN yunbbs_categories c ON c.id=a.cid
    LEFT JOIN yunbbs_users u ON a.uid=u.id
    ORDER BY id DESC LIMIT 10";
$query = $DBS->query($query_sql);
$articledb=array();
while ($article = $DBS->fetch_array($query)) {
    // 格式化内容
    $article['addtime'] = gmdate('Y-m-dTH:M:SZ',$article['addtime']);
    $article['edittime'] = gmdate('Y-m-dTH:M:SZ',$article['edittime']);
    $articledb[] = $article;
}
unset($article);
$DBS->free_result($query);

$base_url = 'http://'.$_SERVER['HTTP_HOST'];


ob_start();
echo '<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>',htmlspecialchars($options['name']),'</title>
  <link rel="self" type="application/atom+xml" href="',$base_url,'/feed"/>
  <link rel="hub" href="http://pubsubhubbub.appspot.com"/>
  <updated>',gmdate('Y-m-dTH:M:SZ',$timestamp),'</updated>
  <id>',$_SERVER["REQUEST_URI"],'</id>
  <author>
    <name>',htmlspecialchars($options['name']),'</name>
  </author>
';

foreach($articledb as $article){
echo '
  <entry>
    <title>',htmlspecialchars($article['title']),'</title>
    <id>t-',$article['id'],'</id>
	<link rel="alternate" type="text/html" href="',$base_url,'/t-',$article['id'],'" />
    <published>',$article['addtime'],'</published>
    <updated>',$article['edittime'],'</updated>
    <content type="html">
      ',htmlspecialchars($article['cname']),' - ',htmlspecialchars($article['author']),' - ',htmlspecialchars(mb_substr($article['content'], 0, 150, 'utf-8')),'
    </content>
  </entry>';

}

echo '</feed>';

$_output = ob_get_contents();
ob_end_clean();

header("content-Type: application/atom+xml");

echo $_output;

?>
