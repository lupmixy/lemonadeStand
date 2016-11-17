<?php
	include 'data.php';
	init();
	$gameID = getHtmlParameter( "gameID" );
	if( authAdmin() ){
		$p = getProperties($gameID);

		printResult( 200, "ok", $p);
	}
	else
		error( 400, "Illegal access" );
	done();
