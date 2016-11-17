<?php
	session_start();
	//$gameID = $_SESSION["gameID"];
	include 'data.php';
	init();

	$price = floatval(getHtmlParameter( "price" ));
	$investInEfficiency = getHtmlParameter( "investInEfficiency" )=="true";
	$investInLoyalty = getHtmlParameter( "investInLoyalty" )=="true";
	$buyMarketResearch = getHtmlParameter( "buyMarketResearch" )=="true";
	$buyPricingSurvey = getHtmlParameter( "buyPricingSurvey" )=="true";
	$buyCustomerReport = getHtmlParameter( "buyCustomerReport" )=="true";
	$advertisingBudget = floatval(getHtmlParameter( "advertisingBudget" ));
	$gameID = getHtmlParameter( "gameID" );
	
	$team = auth();
	$p = getProperties($gameID);
	// if( $p->roundEnd < time() )
	// 	error( 400, "Round is over" );
	
	if( !is_numeric( $price ))
		error( 400, "Price not numeric" );
	if( $advertisingBudget == null )
		$advertisingBudget = 0;
	if( !is_numeric( $advertisingBudget ))
		error( 400, "AdvertisingBudget not numeric / missing" );
	if( $p->currentRound==0 )
		error( 400, "Please wait for the session to start." );
	try
	{
		submitRound( $team, $price, $investInEfficiency, $investInLoyalty, $buyMarketResearch, $buyPricingSurvey, $buyCustomerReport, $advertisingBudget, $gameID );
	}
	catch( Exception $e )
	{
		error( 400, $e->getMessage() );
	}
	
		
	printResult( 200, "ok", null );

	done();
