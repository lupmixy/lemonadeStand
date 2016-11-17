<?php
	include 'data.php';
	init();
	$p = getProperties();
	if( !$p )
	{
		
		$p = new Properties();
		$p->initialCustomerCount = 1000;
		$p->reserverationPriceMin = 0.3;
		$p->reserverationPriceMode = 0.95;
		$p->reserverationPriceMax = 2.0;
	
		$p->reservationIncreaseMin = 0;
		$p->reservationIncreaseMode = 0.25;
		$p->reservationIncreaseMax = 1.00;
	
		$p->loyaltyOddsMin = 0;
		$p->loyaltyOddsMode = 0.45;
		$p->loyaltyOddsMax = 1.0;

		$p->zoiMin = 0;
		$p->zoiMode = 0.02;
		$p->zoiMax = 0.04;
		$p->shopAroundPercentages = array( 10, 25, 15, 50 );
		$p->percentageOfCustomersWillingToPurchase = array( 95, 95, 95, 95, 95, 95 );
		createProperties( $p );
	}
	$pp = getProperties();
	echo "!!!".$pp->reservationPriceMin;
	deleteAllTeams();

		for( $i=0; $i<5; $i++ )
		{
			$t = new Team();
			$t->name = "Team $i";
			createTeam( $t );
		}
	done();
