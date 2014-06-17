<?
	// ----------------------------------------------------------------------------
	// markItUp! Universal MarkUp Engine, JQuery plugin
	// Add-on Load & Save
	// Dual licensed under the MIT and GPL licenses.
	// ----------------------------------------------------------------------------
	// Copyright (C) 2008 Jay Salvat
	// http://markitup.jaysalvat.com/
	// ----------------------------------------------------------------------------
	
	include "config.php";

	if (isset($_REQUEST['data'])) {
		// Do what you want with the data.
		// Here we save it in Session
		session_start();
		$_SESSION['markItUp']['data'] = $_REQUEST['data'];
		session_write_close();
		
		echo "MIU:OK";
	}
?>