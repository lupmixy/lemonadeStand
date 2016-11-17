<?php
	//session_start();
	include 'data.php';
	init();
	
	$name = getHtmlParameter( "name" );
    $password = getHtmlParameter( "password" );
	$gameName = getHtmlParameter( "gameName" );
	$gameID =  getHtmlParameter( "gameID" );
	$_SESSION["name"] = $name;
	$_SESSION["password"] = $password;
	$_SESSION["gameID"] = $gameID;
	$_SESSION["gameName"] = $gameName;
	$_SESSION['SubmittedRound'] = -1;
	/*if !isset($_SESSION["name"]) {
		$_SESSION["name"] = "mck";
	}
	if !isset($_SESSION["password"]) {
		$_SESSION["password"] = "lemon";
	}*/	
	$o = array();
	$o["admin"] = ($name==$ADMIN_NAME && $password==$ADMIN_PASSWORD);
	//$o["admin"] = ($_SESSION["name"]==$ADMIN_NAME && $_SESSION["password"]==$ADMIN_PASSWORD);
	if( !$o["admin"] )
		auth();

	printResult( 200, "ok", $o );
	done();
	


