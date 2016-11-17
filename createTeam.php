<?php
	include 'data.php';
	init();

	$name = getHtmlParameter( "teamname" );
	$password = getHtmlParameter( "teampassword" );
	$gameName = getHtmlParameter( "gameName" );
	$gameID =  getHtmlParameter( "gameID" );

	try
	{
		//$team = registerTeam( $name, $password );
		$team = registerTeam( $name, $password, $gameName, $gameID );
		printResult( 200, "ok", null );
	}
	catch( Exception $e )
	{
		error( 400, $e->getMessage() );
	}


	done();
