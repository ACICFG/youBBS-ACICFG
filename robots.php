<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

header("content-Type: text/plain");
echo 'User-agent: *
Disallow: /login
Disallow: /newpost/
Disallow: /setting
Disallow: /notifications
Disallow: /sigin
Disallow: /wbsetname
Disallow: /qqsetname
Disallow: /admin
Disallow: /forgot
Disallow: /favorites

';

$table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_articles'");
$post_num = $table_status['Auto_increment'] -1;

$max_num = 39000;
$taltol_page = ceil($post_num/$max_num);
$base_url = 'http://'.$_SERVER['HTTP_HOST'];

for($i = 1; $i <= $post_num; $i+=$max_num){
    echo 'Sitemap: ',$base_url,'/sitemap-',$i,"\n";
}

?>
