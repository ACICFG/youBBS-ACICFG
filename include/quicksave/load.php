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

	session_start();
	if (isset($_SESSION['markItUp']['data'])) {
		// Do what you want with the data.
		// Here we load it from Session
		echo stripslashes($_SESSION['markItUp']['data']);
	} else {
		echo "MIU:EMPTY";
	}
	session_write_close();
?>