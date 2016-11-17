<?
	include 'util.php';
	include 'classes/Properties.php';
	include 'classes/Team.php';
	include 'classes/TeamRound.php';
	include 'classes/Customer.php';


// ----------------------------------------------------------------------
// ----------------------------------------------------------------------
// ----------------------------------------------------------------------

function resetAllData($passedGameID)
{
	deleteAllTeams($passedGameID);
	$p = getProperties($passedGameID);
	$p->currentRound = 0;
	updateProperties( $p );
}

function registerTeam( $name, $password, $gameName, $gameID )
{
	$team = getTeam( $name, $gameID );
	if( $team )
		throw new Exception(  "This team already exists.");
	else
	{
		$p = getProperties($gameID);
		if( $p->currentRound > 0 )
			throw new Exception( "The session has already started.");
		else
		{
			$team = new Team();
			$team->name = $name;
			$team->password = $password;
			$team->gameName = $gameName;
			$team->gameID = $gameID;
			$createdTeam = createTeam( $team );
			return $createdTeam;
		}
	}
}



function calculateTeamCashFlow( $team, $properties, $round, $cashOfLastRound )
{
	// base fixed costs always happen
	$round->fixedCost = $properties->baseFixedCost;
	
	// if the team has invested in efficiency then the fixed costs go up
	if( $round->investInEfficiency || $team->efficiencyPurchased )
		$round->fixedCost += $properties->newProcessFixCostAdder;
	
	// if the team has bought the customer report then the price is added to the fixed costs	
	if( $round->buyCustomerReport )
		$round->fixedCost += $properties->customerReportCost;
	
	// if the team has bought the pricing survey add it's fixed costs
	if( $round->buyPricingSurvey )
		$round->fixedCost += $properties->pricingSurveyCost;
		
	// if the team has bought the market research add it's fixed costs
	if( $round->buyMarketResearch )
		$round->fixedCost += $properties->marketShareReportCost;
		
	// add the advertising budget to the fixed cost
	$round->fixedCost += $round->advertisingBudget;

	// variable costs
 	$round->variableCost = $properties->variableCost;

	// pay for candies
	if( $round->investInLoyalty )
		$round->variableCost += $properties->loyaltyBoostPricePerCustomer;
		
	// apply variable efficiency modifier
	if( $round->investInEfficiency || $team->efficiencyPurchased )
		$round->variableCost += $properties->newProcessVariableCostAdder;

	// this many people have bought yummy lemonade at this teams stand
	$round->customerBase = getCustomerBase( $team );

	// ... so this is the variable costs we had for all of them
	$variableCostTotal = $round->customerBase * $round->variableCost;

	// now the good part: we made this much money
	$income = $round->customerBase * $round->price;

	// calculate the revenue
	$round->revenue = $income - $round->fixedCost - $variableCostTotal;

	// this much cash does this team have after this round
	$round->cash = $cashOfLastRound + $round->revenue;
 }

/*
 * returns how many customers have bought lemonade at the given team last.
 */
function getCustomerBase( $team )
{
	$sql = "select count(*) as c, price from customer where supplierId=".$team->id." and purchases='Y'";
	$result = db_query( $sql );
	return db_result( $result, 0, "c" );
}

// more math ... 
function triangularDistribution( $min, $mode, $max )
{
	$u=mt_rand(0,1000000)/1000000;
	if( $u <= (($mode-$min)/($max-$min)) )
	  return $min+sqrt($u*($max-$min)*($mode-$min));
	else
	  return $max-sqrt((1-$u)*($max-$min)*($max-$mode));
}

function getAddRanking( $teams, $rounds )
{
	$advertisingTotal = 0;
	$calcAdvertisingBudget = array();
	$remainingTeams = array();
	foreach( $teams as $i => $team )
	{
		$advertisingTotal += $rounds[$team->id]->advertisingBudget;
		$remainingTeams[$i] = $team;
	}
	
	if( $advertisingTotal==0 )
		$advertisingTotal = 1;
	$advertisingAverage = $advertisingTotal / count($teams);

	foreach( $teams as $i => $team )
		$calcAdvertisingBudget[$team->id] = $rounds[$team->id]->advertisingBudget + $advertisingAverage;

	$result = array();
	while( count($remainingTeams)>0 )
	{
		$remainingSum = 0;
		foreach( $remainingTeams as $i => $team )
			$remainingSum += $calcAdvertisingBudget[$team->id];

		$r = $remainingSum*mt_rand(0,100000)/100000;
		$sum = 0;
		foreach( $remainingTeams as $i => $team )
		{
			$sum += $calcAdvertisingBudget[$team->id];
			if( $r <= $sum )
			{
				unset( $remainingTeams[$i] );
				$result[] = $team;
				break;
			}
		}
	}
	return $result;
}

function matchesReservations( $customer, $newPrice )
{
	// the new price is below the customers upper threshold and does not rise by more than what they find acceptable ("reservationIncrease")
	return $customer->reservationPrice >= $newPrice && ( $customer->price==0 || $customer->reservationIncrease >= ($newPrice/$customer->price - 1) );
}


/**
 * This function calculates where the customers shop based on there price reservations, shop around chances and whether they have been given candies.
 * All of this needs a lot more documentation.
 */
function executeCustomerBehavior( $teams, $rounds, $p, $gameID )
{	
	$allCustomers = getAllCustomers($gameID);
	
	foreach( $allCustomers as $i => $customer )
	{
		$addRanking = getAddRanking( $teams, $rounds );
		if( $customer->seed <= $p->percentageOfCustomersWillingToPurchase[$p->currentRound]/100 )
		{
			$loyalRandom = rnd0to1();
			$loyaltyBoost = 0;
			
			if( $customer->supplierId!=0 && $rounds[$customer->supplierId]->investInLoyalty )
			 	$loyaltyBoost = $p->loyaltyBoostPercent;
			
			$isLoyal = $loyalRandom < ($customer->loyaltyOdds + $loyaltyBoost);
			$winningSupplier = null;
			if( $isLoyal && $customer->supplierId!=0 )
			{
				$winningSupplier = $teams[$customer->supplierId];
			}
			else // not loyal
			{
				//
				( $rounds );
				$winningSupplier = $addRanking[0];
				$suppliersToCompare = array_slice( $addRanking, 1, $customer->shopAroundCount-1 ); 
				$lowestPrice = $rounds[$winningSupplier->id]->price - $customer->zoi;
				foreach( $suppliersToCompare as $i => $supplier )
				{
					if( $lowestPrice > $rounds[$supplier->id]->price )
					{
						$winningSupplier = $supplier;
						$lowestPrice = $rounds[$supplier->id]->price;
					}
				}
			}
			if( matchesReservations( $customer, $rounds[$winningSupplier->id]->price ) )
			{
				// customer switches to new suplier
				$customer->supplierId = $winningSupplier->id;
				$customer->purchases = true;
				$customer->price = $rounds[$winningSupplier->id]->price;
			}
			else
			{
				$customer->purchases = false;
			}
		}
		else
		{
			$customer->purchases = false;
		}
		updateCustomer( $customer );
	}
}

function startGame($passedGameID)
{
	global $ROUND_TIME;

	$p = getProperties($passedGameID);
	$teams = getAllTeams($passedGameID);

	if( count($teams)<2 )
	{
		throw new Exception( "Not enough teams.");
	}
	
	
	foreach( $teams as $i => $team )
	{
		generateCustomers( $team->id, $p, $passedGameID );
		$teamRound = new TeamRound();
		$teamRound->teamId = $team->id;
		$teamRound->round = 0;
		$teamRound->price = $p->initialPrice;
		
		calculateTeamCashFlow( $team, $p, $teamRound, 0 );
		
		createTeamRound( $teamRound, $passedGameID );
	}
	$p->currentRound++;
	$p->roundEnd = time()+$ROUND_TIME;
	updateProperties( $p );
}

/**
 * This is called by the admin when a round ends. (Not when the game is started, because then startGame() is called.)
 * It does all game logic: customers shopping, cash calculations, specials ... 
 */
function finishRound($passedGameID)
{
	global $ROUND_TIME;

	$p = getProperties($passedGameID);
	$teams = getAllTeams($passedGameID);
	if( $p->currentRound==0 )
	{
		throw new Exception( "Game not started yet." );
	}
	else
	{
		// generating turns for teams that haven't submitted something yet.
		generateDefaultTeamRounds( $p, $teams, $passedGameID );
		$rounds = getAllTeamRounds( $p->currentRound );
		
		// customers buy lemonade
		executeCustomerBehavior( $teams, $rounds, $p, $passedGameID );

		// calculate revenue, cash, cost and all other money related things
		foreach( $teams as $i => $team )
		{
			$lastRound = getTeamRound( $team->id, $p->currentRound-1 );
			$round = $rounds[$team->id];
			
			calculateTeamCashFlow( $team, $p, $round, $lastRound->cash );
			updateTeamRound( $round );
		}
		// add reports and specials to the teams round if they bought the special.
		executeSpecials( $teams, $rounds ); 
	}
	$p->currentRound++;
	$p->roundEnd = time()+$ROUND_TIME;
	updateProperties( $p );
}

/**
 * This function performs the specials : 
 *			Market research
 * 			Customer Survey
 * 			Pricing survey
 * 			Efficiency upgrade (the new mixer)
 *
 * Not handled here: Invest in Loyalty. That is handled in executeCustomerBehavior(..) and during the calculateTeamCashFlow().
 */
function executeSpecials( $teams, $rounds )
{
	$totalRevenue = 0;
	$lowestPrice = -1;
	$highestPrice = -1;
	$totalVolume = 1;
	
	// calculating total revenue, total customer base, lowest and highest price
	foreach( $teams as $i => $team )
	{
		$round = $rounds[$team->id];
		
		$totalVolume += $round->customerBase;
		
		if( $round->revenue>0 )
			$totalRevenue += $round->revenue;
		if( $lowestPrice == -1 || $lowestPrice>$round->price )
			$lowestPrice = $round->price;
		if( $highestPrice == -1 || $highestPrice < $round->price )
			$highestPrice = $round->price;
	}


	// reports / specials
	foreach( $teams as $i => $team )
	{
		$round = $rounds[$team->id];


		if( $round->buyMarketResearch )
		{
			$round->volumeShare = $totalVolume!=0 ? $round->customerBase / $totalVolume : 0;

			if( $round->revenue>0 && $totalRevenue!=0 )
				$round->revenueShare = $round->revenue / $totalRevenue;
			else
				$round->revenueShare = 0;
		}

		if( $round->buyPricingSurvey )
		{
			$round->lowestPrice = $lowestPrice;
			$round->highestPrice = $highestPrice;
		}
		
		// purchasing the customer report
		if( $round->buyCustomerReport )
			$team->customerReportPurchased = true;

		// purchasing the efficient mixer
		if( $round->investInEfficiency )
			$team->efficiencyPurchased = true;

		updateTeam( $team );
 		updateTeamRound( $rounds[$team->id] );
	}
}

/**  This function validates if the players purchases are allowed in the current 
 *   round or in case of efficiency and customer report if the team has purchased that option in a previous round and it is still valid .
 *   if not it sets the buy option to false thereby preventing the player from purchasing that option. 
 */
function validateTeamRound( $team, $round, $gameID )
{
	$p = getProperties($gameID);
	
	// round 0 is always good.
	if( $p->currentRound==0 )
		return;

	// efficiency already purchased?
	if( $team->efficiencyPurchased )
		$round->investInEfficiency = false;

	// is efficiency available this round?
	if( $round->investInEfficiency && !$p->efficiencyAvailable[$p->currentRound-1] )
		$round->investInEfficiency = false;

	// is loyalty available this round?
	if( $round->investInLoyalty && !$p->loyaltyAvailable[$p->currentRound-1] )
		$round->investInLoyalty = false;
	
	// is advertising available this round?
	if( $round->advertisingBudget!=0 && !$p->advertisingAvailable[$p->currentRound-1] )
		$round->advertisingBudget = 0;

	// customer report already purchased?
	if( $team->customerReportPurchased )
		$round->buyCustomerReport = false;
		
	// is the customer report available this round?
	if( $round->buyCustomerReport && !$p->customerReportAvailable[$p->currentRound-1] )
		$round->buyCustomerReport = false; 
		
	// is the market research available this round?
	if( $round->buyMarketResearch && !$p->marketResearchAvailable[$p->currentRound-1] )
		$round->buyMarketResearch = false; 

	// is the pricing survey available this round?
	if( $round->buyPricingSurvey && !$p->pricingSurveyAvailable[$p->currentRound-1] )
		$round->buyPricingSurvey = false; 
}

/**
 *   This function generates default team rounds for those teams who have not submitted a price.
 */
function generateDefaultTeamRounds( $p, $teams, $gameID )
{
	foreach( $teams as $i => $team )
	{
		$round = getTeamRound( $team->id, $p->currentRound );
		if( $round==null ) // team has not submitted any values
		{
			$teamRound = new TeamRound();
			$teamRound->teamId = $team->id;
			$teamRound->round = $p->currentRound;
			if( $p->currentRound==0 )
				$teamRound->price = $p->initialPrice;
			else
			{
				$previousRound = getTeamRound( $team->id, $p->currentRound-1 );
				$teamRound->price = $previousRound->price;
			}
			createTeamRound( $teamRound, $gameID );
		}
	}
}

/**
 *   This function writes a set of customers for a team. It is called at the beginning of the game.
 */
function generateCustomers( $teamId, $p, $gameID )
{
	for( $i=0; $i < $p->totalCustomers; $i++ )
	{
		$c = new Customer();
		$c->supplierId = $teamId;
		$c->price = $p->initialPrice;
		$c->reservationPrice = triangularDistribution( $p->reservationPriceMin, $p->reservationPriceMode, $p->reservationPriceMax );
		$c->reservationIncrease = triangularDistribution( $p->reservationIncreaseMin, $p->reservationIncreaseMode, $p->reservationIncreaseMax );
		$c->loyaltyOdds = triangularDistribution( $p->loyaltyOddsMin, $p->loyaltyOddsMode, $p->loyaltyOddsMax );
		$c->shopAroundCount = getShopAroundCount( $p );
		$c->seed = mt_rand(0,1000000)/1000000;
		$c->zoi = triangularDistribution( $p->zoiMin, $p->zoiMode, $p->zoiMax );
		$c->purchases = true;
		createCustomer( $c, $gameID );
	}
}
// This is math magic that has something to do with how customers deviate from one lemon-stand to the next.
function getShopAroundCount( $p )
{
	$r=mt_rand(0,100);
	$sum = 0;
	for( $i=0; $i<count($p->shopAroundPercentages); $i++ )
	{
		$sum += $p->shopAroundPercentages[$i];
		if( $r <= $sum )
			return $i+1;
	}
	return count($p->shopAroundPercentages)+1;
}
error_reporting(E_ALL);

// FS#6314

/**
 * Used when a team submits it's turn.
 */
function submitRound( $team, $price, $investInEfficiency, $investInLoyalty, $buyMarketResearch, $buyPricingSurvey, $buyCustomerReport, $advertisingBudget, $gameID )
{
	$p = getProperties($gameID);
	$teamRound = getTeamRound( $team->id, $p->currentRound );
	if( $teamRound==null )
	{
		$teamRound = new TeamRound();
		$teamRound->teamId = $team->id;
		$teamRound->round = $p->currentRound;
		$teamRound->price = $price;
		$teamRound->investInEfficiency = $investInEfficiency;
		$teamRound->investInLoyalty = $investInLoyalty;
		$teamRound->buyMarketResearch = $buyMarketResearch;
		$teamRound->buyPricingSurvey = $buyPricingSurvey;
		$teamRound->buyCustomerReport = $buyCustomerReport;
		$teamRound->advertisingBudget = $advertisingBudget;
		//validateTeamRound( $team, $teamRound, $gameID );
		createTeamRound( $teamRound, $gameID );
	}
	else
		throw new Exception( "Round already created" );
}


