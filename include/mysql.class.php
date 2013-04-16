<?php

if(!defined('IN_SAESPOT')) exit('Access Denied');

class DB_MySQL  {

    var $querycount = 0;
    var $link;

    function connect($servername, $dbport, $dbusername, $dbpassword, $dbname) {
        
        if(!$this->link = @mysql_connect($servername.':'.$dbport, $dbusername, $dbpassword)) {
            $this->halt('Can not connect to MySQL server');
        }

        if($this->version() > '4.1') {
            global $charset, $dbcharset;
            if(!$dbcharset && in_array(strtolower($charset), array('gbk', 'big5', 'utf-8'))) {
                $dbcharset = str_replace('-', '', $charset);
            }

            if($dbcharset) {
                mysql_query("SET character_set_connection=$dbcharset, character_set_results=$dbcharset, character_set_client=binary", $this->link);
            }

            if($this->version() > '5.0.1') {
                mysql_query("SET sql_mode=''", $this->link);
            }
        }

        if($dbname) {
            mysql_select_db($dbname, $this->link);
        }
    }


    function geterrdesc() {
        return (($this->link) ? mysql_error($this->link) : mysql_error());
    }

    function geterrno() {
        return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
    }

    function insert_id() {
        return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
    }

    function fetch_array($query, $result_type = MYSQL_ASSOC) {
        return mysql_fetch_array($query, $result_type);
    }

    function query($sql, $type = '') {
        $func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
        if(!($query = $func($sql)) && $type != 'SILENT') {
            $this->halt('MySQL Query Error', $sql);
        }
        $this->querycount++;
        return $query;
    }

    function unbuffered_query($sql) {
        $query = $this->query($sql, 'UNBUFFERED');
        return $query;
    }

    function select_db($dbname) {
        return mysql_select_db($dbname, $this->link);
    }

    function fetch_row($query) {
        $query = mysql_fetch_row($query);
        return $query;
    }

    function fetch_one_array($query) {
        $result = $this->query($query);
        $record = $this->fetch_array($result);
        return $record;
    }

    function num_rows($query) {
        $query = mysql_num_rows($query);
        return $query;
    }

    function num_fields($query) {
        return mysql_num_fields($query);
    }

    function result($query, $row) {
        $query = @mysql_result($query, $row);
        return $query;
    }

    function free_result($query) {
        $query = mysql_free_result($query);
        return $query;
    }

    function version() {
        return mysql_get_server_info($this->link);
    }

    function close() {
        return mysql_close($this->link);
    }

    function halt($msg ='', $sql=''){
        $message = "<html>\n<head>\n";
        $message .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">\n";
        $message .= "<style type=\"text/css\">\n";
        $message .=  "body,p,pre {\n";
        $message .=  "font:12px Verdana;\n";
        $message .=  "}\n";
        $message .=  "</style>\n";
        $message .= "</head>\n";
        $message .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";

        $message .= "<p>数据库出错:</p><pre><b>".htmlspecialchars($msg)."</b></pre>\n";
        $message .= "<b>Mysql error description</b>: ".htmlspecialchars($this->geterrdesc())."\n<br />";
        $message .= "<b>Mysql error number</b>: ".$this->geterrno()."\n<br />";
        $message .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br />";
        $message .= "<b>Script</b>: http://".$_SERVER['HTTP_HOST'].getenv("REQUEST_URI")."\n<br />";

        $message .= "</body>\n</html>";
        @header("content-Type: text/html; charset=UTF-8");
        echo $message;
        exit;
    }
}
?>