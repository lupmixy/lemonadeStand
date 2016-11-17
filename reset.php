<?php
	//session_start();
	include 'data.php';
	$gameID = getHtmlParameter( "gameID" );
	init();

	authAdmin();

	resetAllData($gameID);
	//session_unset();
	//session_destroy();

	printResult( 200, "ok", null );
		
	done();
