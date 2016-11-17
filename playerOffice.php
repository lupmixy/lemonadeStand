<?php
session_start();
$gameID = $_SESSION["gameID"]; 
//$name = $_SESSION["name"];
//$password = $_SESSION["password"]; 
$name= "mck";
$password = "lemon";
$_SESSION["name"] = $name;
$_SESSION["password"] = $password; 
$microtimeID = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()); 
$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/getProperties.php?name=' . $_SESSION["name"] . '&password=' .$_SESSION["password"]  . '&gameID=' . $_SESSION["gameID"] . '&i=' . $microtimeID;
$json =  file_get_contents($builtURL);
//var_dump(json_decode($json, true));
$parametersObj = json_decode($json, true);
$timeAtLoad = time();
$roundEnd = $parametersObj['content']["roundEnd"];
$currentRoundNum = $parametersObj['content']["currentRound"];
//if (is_null($currentRoundNum)) {
	//$currentRoundNum = 0;
//}
$teamName = urldecode($_SESSION["teamName"]);
$teamPassword = urldecode($_SESSION["teamPassword"]);
$_SESSION["numRounds"] = $parametersObj['content']['numRounds'];
$numRounds = $_SESSION["numRounds"];
$gameName = $parametersObj['content']['gameName'];
$_SESSION["gameName"] = $gameName; 

//echo $numRounds;
//reset the session options variables
if (($_SESSION['efficiency']=="true")&&($currentRoundNum>1)) {
	$_SESSION['efficiency']="true";
} else {
	$_SESSION['efficiency']="false";
}
$_SESSION['loyalty']="false";
$_SESSION['marketResearch']="false";
$_SESSION['pricing']="false";
$_SESSION['customerReport']="false";
$_SESSION['advertising']='false';
$_SESSION['adBudget']=0;
//echo $builtURL;
//$roundFromQuery = getHtmlParameter( "round" );
if ($currentRoundNum==0) {
	//$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/finishRound.php?round=0&name=' . $name  . '&password=' . $password . '&gameID=' . $gameID . '&i=' . $microtimeID;
	//echo $builtURL;
	//$json =  file_get_contents($builtURL);
	
	//var_dump(json_decode($json, true));
	//echo "<p>". var_dump(json_decode($json, true, 512, JSON_BIGINT_AS_STRING)) ."</p>";
	//$finishRoundObj = json_decode($json, true);	
}



$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/getAllTeams.php?round='. $currentRoundNum . '&name=' . $name . '&password=' . $password  . '&gameID=' . $gameID . '&i=' . $microtimeID;
//echo $builtURL;
$json2 =  file_get_contents($builtURL);
$teamInfoObj = json_decode($json2, true);
//echo($json);
//var_dump(json_decode($json, true));
//echo "<p>". var_dump(json_decode($json, true, 512, JSON_BIGINT_AS_STRING)) ."</p>";
//$roundInfoObj = json_decode($json, true);
//require_once "doorway.php" ;

/*******************************************************
 *
 *     LEMONADE STAND pricing Simulation
 *
 *     by Michael Colombo, 2016
 *
 *     playerOffice.php  
 *     main interface for ongoing game
 *     for players/teans
 *     input spinner to set price 
 *     display results of decisions for prior rounds
 *     present options to purchase reports/efficiencies/ad budget
 *     display results of those purchases
 *     Called by player.php, login.php, stats.php, finishRound.php,
 *
 *     calls currentRound.php, getParamters.php
 *
 ********************************************************/
 
 //Connect to the lemonadestand database
require_once "dbTools/db_connect.php";
mysqli_select_db($con,"mcklemonsnew");
//Get records from the team table
$roundForQuery = $currentRoundNum-1;
// get information about the current team for the current round
$query = "SELECT * FROM `teamRound_withTeamName` WHERE gameID = '" .$gameID . "' AND name='" . urldecode($_SESSION["teamName"]) . "' ORDER BY round";
//echo $query;
$result = mysqli_query($con, $query);
$_SESSION["numOfRounds"] = mysqli_num_rows($result);
if (!$result) {
	echo "Select from teamRound_withTeamName failed. ", mysqli_error($con);
	exit();
}
if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}
$rowCounter=0;
$currentCash=65;
while($row = mysqli_fetch_array($result)) {
	$valueRow[$rowCounter]['price'] = $row['price'];
	$valueRow[$rowCounter]['customerBase'] = $row['customerBase'];
	//price x customer = revenue
	//$row[$rowCounter]['revenue'] = $row['revenue'];
	$valueRow[$rowCounter]['revenue'] = ($row['customerBase'] * $row['price']);
	$valueRow[$rowCounter]['variableCost'] = $row['variableCost'];
	//cost of goods sold = varableCost*customerBase
	$valueRow[$rowCounter]['COGS'] = ($row['variableCost']*$row['customerBase']);
	//gross margin = revenue-COGS
	$valueRow[$rowCounter]['grossMargin'] = ($valueRow[$rowCounter]['revenue']-$valueRow[$rowCounter]['COGS']);
	$valueRow[$rowCounter]['fixedCost'] = $row['fixedCost'];
	//net profit = gross margin- fixed costs	
	//net profit is returned as the calculated value "revenue" in the database
	//$row[$rowCounter]['netProfit'] = ($row[$rowCounter]['grossMargin']-$row['fixedCost']);
	$valueRow[$rowCounter]['netProfit'] = $row['revenue'];
	$valueRow[$rowCounter]['cash'] = $row['cash'];
	//get the info about the option purchase decisions
	$valueRow[$rowCounter]['buyMarketResearch'] = $row ['buyMarketResearch'];
	$valueRow[$rowCounter]['buyPricingSurvey'] = $row ['buyPricingSurvey'];
	$valueRow[$rowCounter]['buyCustomerReport'] = $row ['buyCustomerReport'];
	++$rowCounter;
}
$buyMarketResearch = $valueRow[$currentRoundNum-1]['buyMarketResearch'];
$buyPricingSurvey = $valueRow[$currentRoundNum-1]['buyPricingSurvey'];
$buyCustomerReport = $valueRow[$currentRoundNum-1]['buyCustomerReport'];
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="bootstrap/favicon.ico">

    <title>Lemonade Stand - Game Underway</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="bootstrap/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="justified-nav.css?i=<?php echo $microtimeID ?>" rel="stylesheet">

    <script src="bootstrap/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="https://www.google.com/jsapi?autoload= 
{'modules':[{'name':'visualization','version':'1.1','packages':
['corechart']}]}"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
	//google.charts.load('current', {'packages':['corechart']});
	
      var end = new Date();
		end.setTime(<?php echo $roundEnd?>);
		//alert('end time is'+ end);

        var _second = 1000;
        var _minute = _second * 60;
        var _hour = _minute * 60;
        var _day = _hour * 24;
        var timer;
		var currentServerTime = <?php echo $timeAtLoad ?>;
		var currentClientRound = <?php echo $currentRoundNum ?>;

        function showRemaining() {
			var now = new Date();			
			now.setTime(currentServerTime);
			//alert('now time is ' + now);
            var distance = end-now;			
			//alert('distance is ' + distance);
            if (distance < 0) {
				submitRoundAssembly(<?php echo '\''. $teamName . '\',\'' . $teamPassword . '\',\'' . $gameID . '\',' . $currentRoundNum  ?>);		
				//location.reload(true);
                return;
            }
            var days = Math.floor(distance / _day);
            var hours = Math.floor((distance % _day) / _hour);
            var minutes = Math.floor((distance % _hour) / _minute);
            var seconds = Math.floor((distance % _minute) / _second);

            document.getElementById('countdown').innerHTML = 'Round Ends in ';
            if (minutes==0) {
				document.getElementById('countdown').innerHTML += ' 0:';
			} else {
				document.getElementById('countdown').innerHTML += minutes + ':';
			}
            if (seconds<10) {
				document.getElementById('countdown').innerHTML += '0' + seconds;
			} else {
				document.getElementById('countdown').innerHTML += seconds;
			}			
			currentServerTime = currentServerTime + 1000;
			checkServer(<?php echo "'" . $teamName . "','" . $teamPassword . "','" . $gameID . "'"?>);
        }


		timer = setInterval(showRemaining, 1000);


       	
	
	function checkServer(teamName,teamPassword,gameID) {		
		if (window.XMLHttpRequest) {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  var xmlhttp = new XMLHttpRequest();
		  } else {
			  // code for IE6, IE5
			  var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp.onreadystatechange = function() {
			  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			     //alert(xmlhttp.responseText);		
				 var currentRoundResultInfo = JSON.parse(xmlhttp.responseText);
				 //alert(currentRoundResultInfo['content']['round']);
				 //alert('roundAtload: ' + <?php echo $currentRoundNum; ?> + " round from server: " + currentRoundResultInfo['content']['round']);			  
				 if (currentRoundResultInfo['content']['round'] > <?php echo $numRounds; ?>) {
				 	window.location = "presentPlayerResults.php";				 
				 } else if (currentRoundResultInfo['content']['round'] != <?php echo $currentRoundNum ?>) {
				 	//submitRoundAssembly(<?php echo '\''. $teamName . '\',\'' . $teamPassword . '\',\'' . $gameID . '\',' . $currentRoundNum  ?>);
					location.reload(true);
				 }	 			 
			  }
		  };
		  //price = document.getElementById("roundPrice").value;
		  //currentRound.php?name=michael&password=pwd&i=1476761786199 result status:200 {"status":200, "text":"ok", "content":{"round":1,"roundEndSeconds":1476761898,"roundEndSecondsLeft":111,"roundEnd":"2016-10-17 20:38:18"}}
		  builtURL = "currentRound.php?name="+teamName+"&password="+teamPassword+"&gameID="+gameID+"&i=<?php echo $microtimeID?>";
		  //alert(builtURL);
		  xmlhttp.open("GET",builtURL,true);
		  xmlhttp.send();	  
	}	
	function submitRound(builtURLtoSubmit) {
	  if (window.XMLHttpRequest) {
		  // code for IE7+, Firefox, Chrome, Opera, Safari
		  var xmlhttp = new XMLHttpRequest();
	  } else {
		  // code for IE6, IE5
		  var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  xmlhttp.onreadystatechange = function() {
		  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			 //alert(xmlhttp.responseText);		
			 var submitRoundResponse = JSON.parse(xmlhttp.responseText);					  
			 //alert(submitRoundResponse); 				 
		  }
	  };
	  builtURL = builtURLtoSubmit;
	  //alert(builtURL);
	  xmlhttp.open("GET",builtURL,true);
	  xmlhttp.send();
	  document.getElementById("betweenRounds").style.height = "100%";
	  document.getElementById("optionsNav").innerHTML = "";	
	}
	
	
	function submitRoundAssembly (teamName, teamPassword, gameID, roundNum) {
 		//alert(teamName+teamPassword+gameID+roundNum);
		if (window.XMLHttpRequest) {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  var xmlhttp = new XMLHttpRequest();
		  } else {
			  // code for IE6, IE5
			  var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp.onreadystatechange = function() {
			  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			     //alert(xmlhttp.responseText);		
				 var builtURLtoSubmit = xmlhttp.responseText;					  
				 //alert(builtURLtoSubmit); 
				 submitRound(builtURLtoSubmit);				 
			  }
		  };
		  price = document.getElementById("roundPrice").value;
		  //alert(price);
		  builtURL = "submitRoundAssembly.php?price="+price+"teamName="+teamName+"&teamPassword="+teamPassword+"&gameID="+gameID+"&roundNum="+roundNum+"&i=<?php echo $microtimeID?>";
		  //alert(builtURL);
		  xmlhttp.open("GET",builtURL,true);
		  xmlhttp.send();	  

	}
	
   
	function displayOptions(optionChoice, teamName, gameID, roundNum) {
		  //alert(optionChoice+", "+teamName+", roundNum is"+roundNum);
		  if (window.XMLHttpRequest) {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  var xmlhttp = new XMLHttpRequest();
		  } else {
			  // code for IE6, IE5
			  var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp.onreadystatechange = function() {
			  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				  document.getElementById("optionsDisplay").innerHTML = xmlhttp.responseText;
				  //alert(xmlhttp.responseText);
			  }
		  };
		  builtURL = "dbTools/updateRoundOptions.php?optionChoice="+optionChoice+"&teamName="+teamName+"&gameID="+gameID+"&roundNum="+roundNum+"&i=<?php echo $microtimeID?>";
		  //alert(builtURL);
		  xmlhttp.open("GET",builtURL,true);
		  xmlhttp.send();
	  }
	  
	  function setTwoNumberDecimal(newPrice) {	  	
		document.getElementById('roundPrice').value = parseFloat(newPrice).toFixed(2);
		
	  }
	  
	  function getParticipantChartData(chartToShow, teamName, gameID, roundNum) {
		  if (window.XMLHttpRequest) {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  var xmlhttp = new XMLHttpRequest();
		  } else {
			  // code for IE6, IE5
			  var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp.onreadystatechange = function() {
			  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			     //alert(xmlhttp.responseText);		
				 if (chartToShow!="customerReport") {
				 	var chartDataArray = JSON.parse(xmlhttp.responseText);
				 } else {
				 	var chartDataArray = xmlhttp.responseText;
				 }
				 					  
				 //alert(chartDataArray); 
				 //renderChart(chartDataArray);
				 renderChart(chartToShow, chartDataArray);				 
			  }
		  };
		  builtURL = "dbTools/getParticipantChartData.php?chartToShow="+chartToShow+"&teamName="+teamName+"&gameID="+gameID+"&roundNum="+roundNum+"&i=<?php echo $microtimeID?>";
		  //alert(builtURL);
		  xmlhttp.open("GET",builtURL,true);
		  xmlhttp.send();	  
	  }
	  
	  function renderChart(chartToShow, arrayfordataTable) {
	  	//alert(chartToShow);
	  	if (chartToShow=="teamCash") {
			var jsonData = google.visualization.arrayToDataTable(arrayfordataTable);			
			var options = {
					  title : 'Team <?php echo $teamName ?> Cash Holdings By Round',
					  colors : ['#094509'],
					  vAxis: {title: 'Cash', format: "currency"},
					  hAxis: {title: 'Round'},
					  seriesType: 'bars',
					  series: {1: {type: 'line'}},
					  legend: 'bottom',
					  pointsVisible: true
					};
			
					var chart = new google.visualization.ColumnChart(document.getElementById('cashChart'));
					//document.getElementById('dataTable').style.width='900px';
					document.getElementById('cashChart').style.height='350px';
					//chart.draw(data, options);
					chart.draw(jsonData, options);
		} else if (chartToShow=="pricingSurvey") {
			var jsonData = google.visualization.arrayToDataTable(arrayfordataTable);			
			var options = {
					  title : 'Pricing Survey',
					  vAxis: {title: 'Price', format: "currency"},
					  hAxis: {title: 'Low & High Prices Last Round'},
					  seriesType: 'bars',
					  series: {2: {type: 'line'}},
					  legend: 'right',
					  pointsVisible: true
					};
			
					var chart = new google.visualization.ColumnChart(document.getElementById('pricingSurvey'));
					//document.getElementById('pricingSurvey').style.width='900px';
					//document.getElementById('pricingSurvey').style.height='350px';
					//chart.draw(data, options);
					chart.draw(jsonData, options);
		} else if (chartToShow=="marketResearch") {
			//alert(arrayfordataTable[1][1]);
			//var jsonData = JSON.parse(arrayfordataTable);	
			if  (<?php echo $currentRoundNum ?> !=0) {
				var allOthersRevenue = (arrayfordataTable[1][2]- <?php if (is_null($valueRow[($currentRoundNum-1)]['revenue'])) { echo "0"; } else { echo $valueRow[($currentRoundNum-1)]['revenue']; } ?>);
				var allOthersCustomers = (arrayfordataTable[1][1]-<?php if (is_null($valueRow[($currentRoundNum-1)]['customerBase'])) { echo "0"; } else { echo $valueRow[($currentRoundNum-1)]['customerBase']; } ?>);
			
			//alert('total customers:'+arrayfordataTable[1][1]+'totalrevenue'+	arrayfordataTable[1][2]);
			//alert("team customers:"+	<?php echo $valueRow[($currentRoundNum-1)]['customerBase'] ?> + "team revenue" + <?php echo $valueRow[($currentRoundNum-1)]['revenue'] ?>);
			//alert("alllotherCustomers:" + allOthersCustomers + "allotherRevenue:"+allOthersRevenue);
				
				
				var revData = google.visualization.arrayToDataTable([
				  ['Who', 'Revenue'],
				  ['<?php echo urldecode($_SESSION["teamName"]) ?>', <?php echo $valueRow[($currentRoundNum-1)]['revenue'] ?> ],
				  ['All Other Teams', allOthersRevenue]
				]);
				var formatter = new google.visualization.NumberFormat(
				{prefix: '$'});
				formatter.format(revData, 1);
				
				var revOptions = {
				  title : 'Your Revenue Share',
				  is3D: true,
				  pieSliceTextStyle: {color: 'black'}
				};
				
				
				var volData = google.visualization.arrayToDataTable([
				  ['Who', 'Volume'],
				  ['<?php echo urldecode($_SESSION["teamName"]) ?>', <?php echo $valueRow[($currentRoundNum-1)]['customerBase'] ?> ],
				  ['All Other Teams', allOthersCustomers]
				]);
				var volOptions = {
				  title : 'Your Volume Share (Customers)',
				  is3D: true,
				  pieSliceTextStyle: {color: 'black'}
				};
					
				var chart = new google.visualization.PieChart(document.getElementById('MarketResearchRevenueShare'));
				chart.draw(revData, revOptions);
				var chart = new google.visualization.PieChart(document.getElementById('MarketResearchVolumeShare'));
				chart.draw(volData, volOptions);
			} 
		} else if (chartToShow=="customerReport") {
			//alert(arrayfordataTable);
			document.getElementById('customerReport').innerHTML = arrayfordataTable;
		}			
	  }
	  
	  function showReport(reportToShow) {
		if (reportToShow=="pricingSurvey") {
			//alert('in the if block');
			$("pricingSurveyDisplay").modal('show');
			$("pricingSurveyDisplay").on('shown.bs.modal', function() {
				//alert('the modal has shown! now trying to load' + reportToShow);
				getParticipantChartData(reportToShow, teamName, gameID, roundNum);
			})
		} else if (reportToShow=="marketResearch") {
			$("MarketResearchDisplay").modal("show");
			$("MarketResearchDisplay").on("shown.bs.modal", function() {
				//alert('the modal has shown! now trying to load' + reportToShow);
				getParticipantChartData(reportToShow, teamName, gameID, roundNum);
			})
		} else if (reportToShow=="customerReport") {
			$("customerReportDisplay").modal("show");
			$("customerReportDisplay").on("shown.bs.modal", function() {
				//alert('the modal has shown! now trying to load' + reportToShow);
				getParticipantChartData(reportToShow, teamName, gameID, roundNum);
			})
		}				
	  }
	  
	  function buyOption(optionPurchased) {
		if (window.XMLHttpRequest) {
		  // code for IE7+, Firefox, Chrome, Opera, Safari
		  var xmlhttp = new XMLHttpRequest();
		} else {
		  // code for IE6, IE5
		  var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
		  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	
			  document.getElementById(optionPurchased).innerHTML = xmlhttp.responseText;
		  }
		};
		if (optionPurchased=='advertising') {
			adBudget = document.getElementById('adBudget').value;
			document.getElementById('adBudget').disabled=true;
		} else {
			//adBudget = 0;
		}
		//alert(adBudget);
		if (optionPurchased=='advertising') {
				xmlhttp.open("GET","buyOption.php?optionPurchased="+optionPurchased+"&adBudget="+adBudget,true);
		} else {
			xmlhttp.open("GET","buyOption.php?optionPurchased="+optionPurchased,true);
		} 
		xmlhttp.send();	  
	  
	  	//optionPurchased='efficiency';
	  	//alert(optionPurchased);
		//$_SESSION[optionPurchased] = 'true';
		//alert($_SESSION[optionPurchased]);
		//newButtonText = '<input type="button" class="btn btn-success btn-sm disabled" value="Purchased">';
		//newButtonText = '<a href="#" role="button" class="btn btn-success btn-sm disabled">Purchased</a>';
		//oldButtonText = '<a href="#" role="button" class="btn btn-success btn-sm" onClick"buyOption(\'efficiency\');">Buy It!</a>';
		//document.getElementById(optionPurchasaed).class  = 'btn btn-success btn-sm disabled';
		//document.getElementById(optionPurchased).innerHTML = newButtonText;		
	  }
	  
	  function populatePage(teamName, gameID, roundNum) {
	  	//alert('team name is ' + teamname);
		//alert('gameID is ' + gameID);
		//alert('roundNum is ' + roundNum);
	  	// draw cash chart
		// populate options to buy
		//  populate display mechanism 
		
		//check to see if team has already submitted this round
		// if they have, send to lobby to wait
		<?php 
		//echo "//session submitted round is: " . $_SESSION['SubmittedRound'] . "currentRoundNum is: " . $currentRoundNum . "\n";
		if  (isset($_SESSION['SubmittedRound'])) {
			if ($_SESSION['SubmittedRound']>=$currentRoundNum) {
			//echo "alert('session submitted round is: " . $_SESSION['SubmittedRound'] . "<br>currentRoundNum is: " . $currentRoundNum . "');"; 
				//echo "document.getElementById('mainContainer').innerHTML = '';	" . "\n";
				echo "document.getElementById('betweenRounds').style.height = '100%'". "\n";
			}
		}
		?>
		
		// customer report needs to include 'historical demand variablility'
		if (roundNum!=0) {
			getParticipantChartData("teamCash", teamName, gameID, roundNum);			
			<?php if ($parametersObj['content']["efficiencyAvailable"][$currentRoundNum]) {
				echo 'displayOptions("efficiency", teamName, gameID, roundNum);';
			} elseif ($parametersObj['content']["pricingSurveyAvailable"][$currentRoundNum]) {
				echo 'displayOptions("pricing", teamName, gameID, roundNum);';
			} elseif ($parametersObj['content']["marketResearchAvailable"][$currentRoundNum]) {
				echo 'displayOptions("marketResearch", teamName, gameID, roundNum);';
			} elseif ($parametersObj['content']["advertisingAvailable"][$currentRoundNum]) {
				echo 'displayOptions("advertising", teamName, gameID, roundNum);';
			} elseif ($parametersObj['content']["loyaltyAvailable"][$currentRoundNum]) {
				echo 'displayOptions("loyalty", teamName, gameID, roundNum);';
			} elseif ($parametersObj['content']["customerReportAvailable"][$currentRoundNum]) {
				echo 'displayOptions("customerReport", teamName, gameID, roundNum);';
			}
			// display any reports the team has purchased (can likely do with "getalleteams" call above
			if ($buyCustomerReport=="Y") {
				echo 'getParticipantChartData("customerReport", teamName, gameID, roundNum);';			
			}
			if ($buyMarketResearch=="Y") {
				echo 'getParticipantChartData("marketResearch", teamName, gameID, roundNum);';
			}
			if ($buyPricingSurvey=="Y") {
				echo 'getParticipantChartData("pricingSurvey", teamName, gameID, roundNum);';		
			}			
			?>						
		}	
	  }

</script>

<style>
body {
    margin: 0;
    font-family: 'Lato', sans-serif;
}

.overlay {
    height: 0%;
    width: 100%;
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0, 0.9);
    overflow-y: hidden;
    transition: 0.5s;
}

.overlay-content {
    position: relative;
    top: 25%;
    width: 100%;
    text-align: center;
    margin-top: 30px;
}

.overlay a {
    padding: 8px;
    text-decoration: none;
    font-size: 36px;
    color: #818181;
    display: block;
    transition: 0.3s;
}

.overlay a:hover, .overlay a:focus {
    color: #f1f1f1;
}

.overlay .closebtn {
    position: absolute;
    top: 20px;
    right: 45px;
    font-size: 60px;
}

@media screen and (max-height: 450px) {
  .overlay {overflow-y: auto;}
  .overlay a {font-size: 20px}
  .overlay .closebtn {
    font-size: 40px;
    top: 15px;
    right: 35px;
  }
}
</style>
</head>

  <body id=background onLoad="populatePage(<?php echo '\''. $teamName . '\',' . $gameID . ',' . $currentRoundNum  ?>);">
  	<div id="betweenRounds" class="overlay">
      	<div class="overlay-content">
            <div id="interstitialContent"><h2 style="color:#CCCCCC">Price and Options for this round have been received.</h3><br><h3 style="color:#CCCCCC">Please wait for the next round.<br>Once the faciliator has started the round, this screen will automatically update.</h3></div>
  		</div>
	</div>

    <div class="container" id="mainContainer">

      <!-- The justified navigation menu is meant for single line per list item.
           Multiple lines will require custom code not provided by Bootstrap. -->
      <div class="masthead">
        <h3 class="text-muted">Lemonade Stand Game  - <?php echo urldecode($_SESSION["gameName"]) ?> - Team  <?php echo urldecode($_SESSION["teamName"]) ?></h3>

      </div>
        <div class="row" style="background-color:#ffffff">
        	<div class="col-sm-8"  style="background-color:#cccccc">
            	<div id="priceTable" style="background-color:#cccccc; overflow-x:auto">
                    <table align="left" class="table table-bordered table-hover table-condensed" style="width:95%">
                    <thead>
                      <tr>
                        <th class="col-sm-2">&nbsp;</th>
                        <?php 
						for ($x = 0; $x<= $currentRoundNum; $x++) {
							echo '<th class="col-sm-1"><div align="center">Round '.$x.'</div></th>';				
                		}?>
                      </tr>
                     </thead>
                     <tr>
                        <td align="right"><span class="glyphicon glyphicon-plus"></span> Price/Cup</td>
                        <?php 
						for ($x = 0; $x<= $currentRoundNum-1; $x++) {
							echo '<td><div align="right">' . money_format('$%i', $valueRow[$x]['price']) . '</div></td>';				
                		}?>                       
                        <td align="right">
							<?php 
							if ($currentRoundNum!=0) { 
								echo '<input onChange="setTwoNumberDecimal(this.value)" name="roundPrice" id="roundPrice" type="number" min=".01" max="100" value=".5" step=".01" /></td>';
                            }
							?>
                      </tr>
                     <tr>
                       <td align="right" style="border:none; text-decoration: underline"><span class="glyphicon glyphicon-remove"></span> Customers</td>
                        <?php 
						for ($x = 0; $x<= $currentRoundNum-1; $x++) {
							echo '<td><div align="right">' . number_format($valueRow[$x]['customerBase']) . '</div></td>';				
                		}?>                          
                       <td>&nbsp;</td>
                      </tr>
                     <tr>
                       <td align="right" >= Revenue</td>
                        <?php 
						for ($x = 0; $x<= $currentRoundNum-1; $x++) {
							echo '<td><div align="right">' . money_format('$%i', $valueRow[$x]['revenue']) . '</div></td>';				
                		}?>  
                       <td>&nbsp;</td>
                      </tr>
                     
                     <tr>
                       <td  align="right" ><span class="glyphicon glyphicon-minus"></span> Cost of goods Sold (COGS)</td>
                        <?php 
						for ($x = 0; $x<= $currentRoundNum-1; $x++) {
							echo '<td><div align="right">' . money_format('$%i', $valueRow[$x]['COGS']) . '</div></td>';				
                		}?> 
                       <td>&nbsp;</td>
                      </tr>
                     <tr>
                       <td  align="right" style="border:none; text-decoration: underline"> Gross Margin</td>
                        <?php 
						for ($x = 0; $x<= $currentRoundNum-1; $x++) {
							echo '<td><div align="right">' . money_format('$%i', $valueRow[$x]['grossMargin']) . '</div></td>';				
                		}?>                       
                       <td>&nbsp;</td>
                      </tr>
                     <tr>
                       <td  align="right" ><span class="glyphicon glyphicon-minus"></span>Fixed Costs</td>
						<?php
                        for ($x = 0; $x<= $currentRoundNum-1; $x++) {
							echo '<td><div align="right">' . money_format('$%i', $valueRow[$x]['fixedCost']) . '</div></td>';				
                		}?>                       
                       <td>&nbsp;</td>
                      </tr>
                     <tr>
                       <td  align="right" style="border:none; text-decoration: underline">=Net profit</td>
						<?php
                        for ($x = 0; $x<= $currentRoundNum-1; $x++) {
							echo '<td><div align="right">' . money_format('$%i', $valueRow[$x]['netProfit']) . '</div></td>';				
                		}?>                        
                       <td>&nbsp;</td>
                      </tr>
                     
                     <tr>
                       <td  align="right" ><span style="border-bottom: 3px double">Ending Cash</span></td>
						<?php
                        for ($x = 0; $x<= $currentRoundNum-1; $x++) {
							echo '<td><div align="right">' . money_format('$%i', $valueRow[$x]['cash']) . '</div></td>';				
                		}?>                           
                       <td>&nbsp;</td>
                      </tr>
           		  </table>
            	</div>
            
            </div>
            <div class="col-sm-4">
            	<div id="cashChart">
            		<div class="panel panel-default">
  						<div class="panel-heading">Waiting to begin</div>
  						<div class="panel-body">Waiting for the administrator to begin the game.</div>
					</div>
            	</div>
			</div>           
        </div>         
            <div class="row" style="background-color:#FFFFFF">
                <div class="col-md-7" id="optionsDisplay">
                    <!-- this portion gets updated by displayOptions()-->
                    <div class="panel panel-default">
                    	<div class="panel-heading">Your Options for Next Round:</div>
  						<div class="panel-body"> 
                        	<div class="media">
                                <div class="media-body">
                                	<h3>Options Display</h3>                                  
                                    When available, options you can purchase to improve your Lemonade Stand's performance will be displayed here.
                                </div>
                           </div>                        	
                        </div>
                        <div align="center" id="optionsNav">
                        <ul class="pagination">
                        <?php  if ($parametersObj['content']["efficiencyAvailable"][$currentRoundNum]) { ?>                         	
                            <li <?php if ($optionChoice=="efficiency") { echo 'class="active"'; }?>><a href="#" onClick="displayOptions('efficiency',<?php echo '\''. $teamName . '\',' . $gameID . ',' . $currentRoundNum  ?>);">Efficiency</a></li>
                        <?php } if ($parametersObj['content']["pricingSurveyAvailable"][$currentRoundNum]) { ?>    
                            <li <?php if ($optionChoice=="pricing") { echo 'class="active"'; }?>><a href="#" onClick="displayOptions('pricing',<?php echo '\''. $teamName . '\',' . $gameID . ',' . $currentRoundNum  ?>);">Pricing Survey</a></li>
                        <?php } if ($parametersObj['content']["marketResearchAvailable"][$currentRoundNum]) { ?>    
                            <li <?php if ($optionChoice=="marketResearch") { echo 'class="active"'; }?>><a href="#" onClick="displayOptions('marketResearch',<?php echo '\''. $teamName . '\',' . $gameID . ',' . $currentRoundNum  ?>);">Market Research</a></li>
                        <?php } if ($parametersObj['content']["advertisingAvailable"][$currentRoundNum]) { ?>    
                            <li <?php if ($optionChoice=="advertising") { echo 'class="active"'; }?>><a href="#" onClick="displayOptions('advertising',<?php echo '\''. $teamName . '\',' . $gameID . ',' . $currentRoundNum  ?>);">Advertisment</a></li>
                        <?php } if ($parametersObj['content']["loyaltyAvailable"][$currentRoundNum]) { ?>    
                            <li <?php if ($optionChoice=="loyalty") { echo 'class="active"'; }?>><a href="#" onClick="displayOptions('loyalty',<?php echo '\''. $teamName . '\',' . $gameID . ',' . $currentRoundNum  ?>);">Loyalty</a></li>
                        <?php } if ($parametersObj['content']["customerReportAvailable"][$currentRoundNum]) { ?>    
                            <li <?php if ($optionChoice=="customerReport") { echo 'class="active"'; }?>><a href="#" onClick="displayOptions('customerReport',<?php echo '\''. $teamName . '\',' . $gameID . ',' . $currentRoundNum  ?>);">Customer Report</a></li>
                        <?php }?> 
                        </ul>
                        </div>                                                
					</div>               
                    <!-- end of portion that gets replaced by displayOptions() -->               
                </div>
            	<div class="col-md-5">
                	<div class="panel panel-default">
                      <div class="panel-heading">Available Reports</div>
                      <div class="panel-body">
                       <?php
					  	if ($currentRoundNum==0) {
							echo "When purchased reports are available, they will display in this section.  You will be able to display them by clicking on icons here.";
						}					  
					  	//bring up a teamaround record from the previous round, where values have "Y", team purchased the report
						echo '<div class="row">';
						if ($buyCustomerReport=="Y") {
							echo '<div class="col-md-4"><a href="#" data-toggle="modal" data-target="#customerReportDisplay"><img src="images/customerReport.gif"></a></div>';
						}
						if ($buyMarketResearch=="Y") {
							echo '<div class="col-md-4"><a href="#"  data-toggle="modal" data-target="#marketResearchDisplay"><img src="images/marketResearchChart116x116.png" height="93px">Market Research</a></div>';
						}
						if ($buyPricingSurvey=="Y") {
							echo '<div class="col-md-4"><a href="#" data-toggle="modal" data-target="#pricingSurveyDisplay"><img src="images/pricingSurvey93x93.gif">Pricing Survey</a></div>';
						}
						echo '</div>';
					  ?>
                      </div>
                    </div>
                    <div align="center">
                    	<h3><span id="infoLabel" class="label label-default">Round <?php echo $currentRoundNum ?> underway</span>&nbsp;&nbsp;<span id="countdown" class="label label-info">Round Ends in 3:00</span></h3>                    	<h1 id="blueButton"><a href="#" id="navButton" role="button" class="btn btn-lg  btn-primary " onClick="submitRoundAssembly(<?php echo '\''. $teamName . '\',\'' . $teamPassword . '\',\'' . $gameID . '\',' . $currentRoundNum  ?>);">Submit Price</a></h1>
                    </div>
                </div>                
            </div>
          </div>
        </div>
        
          <!-- Modal market research-->
          <div class="modal fade" id="marketResearchDisplay" role="dialog">
            <div class="modal-dialog">
              <!-- Modal market research survey content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Market Research Report</h4>
                </div>
                <div class="modal-body">
                  <div align="center" id="MarketResearchRevenueShare">&nbsp;</div>
                  <div align="center" id="MarketResearchVolumeShare">&nbsp;</div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
              
            </div>
          </div>
          <!-- Modal pricing survey -->
          <div class="modal fade" id="pricingSurveyDisplay" role="dialog">
            <div class="modal-dialog">
              <!-- Modal pricing survey content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Pricing Survey</h4>
                </div>
                <div class="modal-body">
                  <div align="center" id="pricingSurvey"><p>Pricing Survey Here</p></div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
              
            </div>
          </div>
          <!-- Modal customer report-->
          <div class="modal fade" id="customerReportDisplay" role="dialog">
            <div class="modal-dialog">
              <!-- Modal customer report-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Customer Report</h4>
                </div>
                <div class="modal-body" >
                  <div align="center" id="customerReport"><p>Customer Report Here.</p></div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
              
            </div>
          </div>
    </div> <!-- /container -->



    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="bootstrap/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
