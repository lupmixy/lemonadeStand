<?php
	session_start();
	$gameID = getHtmlParameter( "gameID" );
	include 'data.php';
	init();
	
	$round = getHtmlParameter( "round" );
	if( !is_numeric( $round ) )
		error( 400, "Parameter 'round' is not numeric/missing." );
	
	$team = authAdmin();
	
	$p = getProperties($gameID);
	if( $p->currentRound<$round )
		error( 400, "This round hasn't happened yet." );
	if( 0>=$round )
		error( 400, "Round needs to be greater than 0." );
	
	$teams = getAllTeams($gameID);
	$v = array();
	foreach( $teams as $i => $team )
	{
		$tr = getTeamRound( $team->id, $round );
		if( $tr!=null )
		{
			$tr->name = $team->name;
			$v[] = $tr;
		}
	}
	
	printResult( 200, "ok", $v );

	done();
	
	
	class TeamResult
	{
		public $customerBase;
		public $cash;
		public $fixedCost;
		public $variableCost;
		public $currentRound;
	}
