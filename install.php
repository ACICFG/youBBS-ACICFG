<?php

define('IN_SAESPOT', 1);
@header("content-Type: text/html; charset=UTF-8");

$sqlfile = dirname(__FILE__) . '/yunbbs_mysql.sql';
if(!is_readable($sqlfile)) {
	exit('数据库文件不存在或者读取失败');
}
$fp = fopen($sqlfile, 'rb');
$sql = fread($fp, 2048000);
fclose($fp);

include (dirname(__FILE__) . '/config.php');
include (dirname(__FILE__) . '/include/mysql.class.php');

$DBS = new DB_MySQL;
$DBS->connect($servername, $dbport, $dbusername, $dbpassword, $dbname);
unset($servername, $dbusername, $dbpassword);

$DBS->select_db($dbname);
if($DBS->geterrdesc()) {
	if(mysql_get_server_info() > '4.1') {
		$DBS->query("CREATE DATABASE $dbname DEFAULT CHARACTER SET $dbcharset");
	} else {
		$DBS->query("CREATE DATABASE $dbname");
	}

	if($DBS->geterrdesc()) {
		exit('指定的数据库不存在, 系统也无法自动建立, 无法安装.<br />');
	} else {
		$DBS->select_db($dbname);
		//成功建立指定数据库
	}
}

$DBS->query("SELECT COUNT(*) FROM yunbbs_settings", 'SILENT');
if(!$DBS->geterrdesc()) {
	header('location: /');
	exit('数据已经装好了， 不能重复安装， 若要重装，先删除mysql 里全部数据。 <a href="/">现在直接进入首页</a><br />');
}

runquery($sql);

$timestamp = time();
$DBS->unbuffered_query("UPDATE yunbbs_settings SET value='$timestamp' WHERE title='site_create'");

$DBS->close();

// '<br /> 顺利安装完成！<br /><a href="/">点击进入首页</a>';

function runquery($sql) {
	global $dbcharset, $DBS;

	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				//echo '创建表 '.$name.' ... 成功<br />';
				$DBS->query(createtable($query, $dbcharset));
			} else {
				$DBS->query($query);
			}
		}
	}
}

function createtable($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}

header('location: /');

?>
