<?php
	session_start();	
	include 'data.php';
	init();
	$gameID = getHtmlParameter( "gameID" );
	$round = getHtmlParameter( "round" );
	if( !is_numeric( $round ) )
		error( 400, "Parameter 'round' is not numeric/missing." );
	try
	{
		authAdmin();
		$p = getProperties($gameID);

		if( $p->currentRound==0 )
			startGame($gameID);
		else
			finishRound($gameID);
			
		printResult( 200, "ok", null );
	}
	catch( Exception $e )
	{
		error( 400, "An error happened:".$e->getMessage() );
	}
	done();
