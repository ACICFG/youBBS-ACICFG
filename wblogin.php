<?php
define('IN_SAESPOT', 1);

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/common.php');

include( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( $options['wb_key'] , $options['wb_secret'] );

$code_url = $o->getAuthorizeURL( 'http://'.$_SERVER['HTTP_HOST'].'/wbcallback' );

header("Location:$code_url");
exit;

?>
