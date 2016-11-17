<?php
	include 'data.php';
	init();

	function testAndGetNumericParameter( $name )
	{
		$value = getHtmlParameter( $name );
		if( $value==null )
			return null;
		if( !is_numeric( $value ) ) 
		{
			error( 400, "Parameter '$name' is not numeric." );
			die();
		}
		return $value;
	}


	if( authAdmin() )
	{
		$passedGameID = getHtmlParameter( "gameID" );
		$p = getProperties($passedGameID);

		$v = testAndGetNumericParameter( "baseFixedCost" );
		if( $v!=null )
			$p->baseFixedCost = (float)$v;
			
 		$v = testAndGetNumericParameter( "variableCost" ); 
		if( $v!=null)
			$p->variableCost = (float)$v;
		
		$v= testAndGetNumericParameter( "totalCustomers" ); 
		if( $v!=null)
			$p->totalCustomers = (float)$v;

		$v = testAndGetNumericParameter( "zoiMin" );
		if( $v!=null )
			$p->zoiMin = (float)$v;
			
		$v = testAndGetNumericParameter( "zoiMode" ); 
		if( $v!=null )
			$p->zoiMode = (float)$v;
			
		$v = testAndGetNumericParameter( "zoiMax" ); 
		if( $v!=null )
			$p->zoiMax = (float)$v;
		
		$v = testAndGetNumericParameter( "reservationPriceMin" );
		if( $v!=null )
			$p->reservationPriceMin = (float)$v;

		$v = testAndGetNumericParameter( "reservationPriceMode" );
		if( $v!=null )
			$p->reservationPriceMode = (float)$v;
		
		$v = testAndGetNumericParameter( "reservationPriceMax" );
		if( $v!=null )
			$p->reservationPriceMax = (float)$v;

		$v = testAndGetNumericParameter( "reservationIncreaseMin" );
		if( $v!=null )
			$p->reservationIncreaseMin = (float)$v;
			
		$v = testAndGetNumericParameter( "reservationIncreaseMode" );
		if( $v!=null )
			$p->reservationIncreaseMode =  (float)$v;
			
		$v = testAndGetNumericParameter( "reservationIncreaseMax" );
		if( $v!=null )
			$p->reservationIncreaseMax = (float)$v;

		$v = testAndGetNumericParameter( "loyaltyOddsMin" );
		if( $v!=null )
			$p->loyaltyOddsMin = (float)$v;
			
		$v = testAndGetNumericParameter( "loyaltyOddsMode" );
		if( $v!=null )
			$p->loyaltyOddsMode = (float)$v;
			
		$v = testAndGetNumericParameter( "loyaltyOddsMax" );
		if( $v!=null )
			$p->loyaltyOddsMax = (float)$v;

		$v = testAndGetNumericParameter( "marketShareReportCost" );
		if( $v!=null )
			$p->marketShareReportCost = (float)$v;
			
		$v = testAndGetNumericParameter( "customerReportCost" );
		if( $v!=null )
			$p->customerReportCost = (float)$v;

		$v = testAndGetNumericParameter( "pricingSurveyCost" );
		if( $v!=null )
			$p->pricingSurveyCost = (float)$v;

		$v = testAndGetNumericParameter( "newProcessFixCostAdder" );
		if( $v!=null )
			$p->newProcessFixCostAdder = (float)$v;
			
		$v = testAndGetNumericParameter( "newProcessVariableCostAdder" );
		if( $v!=null )
			$p->newProcessVariableCostAdder = (float)$v;

		$v = testAndGetNumericParameter( "loyaltyBoostPercent" );
		if( $v!=null )
			$p->loyaltyBoostPercent = (float)$v;
			
		$v = testAndGetNumericParameter( "loyaltyBoostPricePerCustomer" );
		if( $v!=null )
			$p->loyaltyBoostPricePerCustomer = (float)$v;

		$v = testAndGetNumericParameter( "initialPrice" );
		if( $v!=null )
			$p->initialPrice = (float)$v;
		
		// shop around percentages 
		$shopAroundPercentages = getHtmlParameter( "shopAroundPercentages" );		
		if( $shopAroundPercentages!=null )
		{
			$p->shopAroundPercentages =  explode( ",", $shopAroundPercentages ); 
			$sum=0;
			$fail = false;
			$count = 0;
			foreach( $p->shopAroundPercentages as $perc )
			{
				if( !is_numeric( $perc ) || 0>$perc || $perc>100 )
					$fail = true;
				else
				{
					$sum += $perc;
					$count ++;
				}
			}
			if( $sum!=100 || $count!=4 )
				$fail = true;
			if( $fail )
			{
				error( 400, "Shop around percentages need to be a comma separated list of 4 numbers between 0 and 100 that add up to 100." );
				die();
			}
		}
		
		$percentageOfCustomersWillingToPurchase = getHtmlParameter( "percentageOfCustomersWillingToPurchase" );
		if( $percentageOfCustomersWillingToPurchase!=null )
		{
			$p->percentageOfCustomersWillingToPurchase =  explode( ",", $percentageOfCustomersWillingToPurchase ); 
			$fail = false;
			$count = 0;
			foreach( $p->percentageOfCustomersWillingToPurchase as $perc )
			{
				if( !is_numeric( $perc ) || 0>$perc || $perc>100 )
					$fail = true;
				else
					$count++;
			}
			if( $count != $ROUND_COUNT )
				$fail = true;
			if( $fail )
			{
				error( 400, "'percentageOfCustomersWillingToPurchase' needs to be a comma separated list of $ROUND_COUNT numbers between 0 and 100 ." );
				die();
			}
		}
		
		$efficiencyAvailable = getHtmlParameter( "efficiencyAvailable");
		if( $efficiencyAvailable!=null )
		{
			$efa = stringToBoolArray( $efficiencyAvailable );
			if( count( $efa )!=$ROUND_COUNT )
				error( 400, "Parameter 'efficiencyAvailable' needs to be a comma separated list of $ROUND_COUNT true/false values." );
			$p->efficiencyAvailable = $efa;
		}
		$advertisingAvailable = getHtmlParameter( "advertisingAvailable");
		if( $advertisingAvailable!=null )
		{
			$efa = stringToBoolArray( $advertisingAvailable );
			if( count( $efa )!=$ROUND_COUNT )
				error( 400, "Parameter 'advertisingAvailable' needs to be a comma separated list of $ROUND_COUNT true/false values." );
			$p->advertisingAvailable = $efa;
		}
		$loyaltyAvailable = getHtmlParameter( "loyaltyAvailable");
		if( $loyaltyAvailable!=null )
		{
			$la = stringToBoolArray( $loyaltyAvailable );
			if( count( $la )!=$ROUND_COUNT )
				error( 400, "Parameter 'loyaltyAvailable' needs to be a comma separated list of $ROUND_COUNT true/false values." );
			$p->loyaltyAvailable = $la;
		}

		$customerReportAvailable = getHtmlParameter( "customerReportAvailable");
		if( $customerReportAvailable!=null )
		{
			$cra = stringToBoolArray( $customerReportAvailable );
			if( count( $cra )!=$ROUND_COUNT )
				error( 400, "Parameter 'customerReportAvailable' needs to be a comma separated list of $ROUND_COUNT true/false values." );
			$p->customerReportAvailable = $cra;
		}

		$marketResearchAvailable = getHtmlParameter( "marketResearchAvailable");
		if( $marketResearchAvailable!=null )
		{
			$mra = stringToBoolArray( $marketResearchAvailable );
			if( count( $mra )!=$ROUND_COUNT )
				error( 400, "Parameter 'marketResearchAvailable' needs to be a comma separated list of $ROUND_COUNT true/false values." );
			$p->marketResearchAvailable = $mra;
		}

		$pricingSurveyAvailable = getHtmlParameter( "pricingSurveyAvailable");
		if( $pricingSurveyAvailable!=null )
		{
			$psa = stringToBoolArray( $pricingSurveyAvailable );
			if( count( $psa )!=$ROUND_COUNT )
				error( 400, "Parameter 'pricingSurveyAvailable' needs to be a comma separated list of $ROUND_COUNT true/false values." );
			$p->pricingSurveyAvailable = $psa;
		}

		updateProperties( $p );
		
//		$p = getProperties();

		printResult( 200, "ok", $p );
	}
	else
		error( 400, "Illegal access" );
	done();
	
