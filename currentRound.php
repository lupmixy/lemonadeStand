<?php
	include 'data.php';
	$gameID = getHtmlParameter( "gameID" );
	init();

	$team = auth();

	$p = getProperties($gameID);
	
	$r = array();
	$r["round"] = $p->currentRound;
	$r["roundEndSeconds"] = $p->roundEnd;
	$r["roundEndSecondsLeft"] = $p->roundEnd-time();
	$r["roundEnd"] = date( "Y-m-d H:i:s", $p->roundEnd ) ;
	
	printResult( 200, "ok", $r );

	done();
