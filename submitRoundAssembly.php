<?php
session_start();
$price = $_GET['price'];
if ($_SESSION['efficiency']=="true") {
	$investInEfficiency="true";
}
if ($_SESSION['loyalty']=="true") {
	$investInLoyalty="true";
}
if ($_SESSION['marketResearch']=="true") {
	$buyMarketResearch="true";
}
if ($_SESSION['pricing']=="true") {
	$buyPricingSurvey="true";
}
if ($_SESSION['customerReport']=="true") {
	$buyCustomerReport="true";
}
if ($_SESSION['advertising']=="true") {
	$adBudget=$_SESSION['adBudget'];
}
$teamName = urldecode($_SESSION["teamName"]);
$teamPassword = urldecode($_SESSION["teamPassword"]);
$gameID = $_GET['gameID'];
$microtimeID = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()); 
$_SESSION['SubmittedRound']  = getHtmlParameter( "roundNum");


function getHtmlParameter( $paramName )
{
	if( isset($_GET[$paramName] ) ) {
		return htmlentities( $_GET[ $paramName ] );
	} else {
		return null;
	}
}

//submitRound.php?price=0.55&investInEfficiency=true&investInLoyalty=false&buyMarketResearch=false&buyPricingSurvey=false&buyCustomerReport=false&advertisingBudget=0&name=michael&password=pwd&i=1476761867747
echo 'submitRound.php?price=' . $price . '&investInEfficiency=' . $investInEfficiency . '&investInLoyalty=' . $investInLoyalty . '&buyMarketResearch=' . $buyMarketResearch . '&buyPricingSurvey=' . $buyPricingSurvey . '&buyCustomerReport=' . $buyCustomerReport . '&advertisingBudget='. $adBudget . '&name=' . $teamName . '&password=' . $teamPassword . '&gameID=' . $gameID .'&i=' . $microtimeID;
?>
