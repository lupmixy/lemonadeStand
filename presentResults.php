<?php
session_start();
$gameID = $_SESSION["gameID"]; 
$gameName = $_SESSION["gameName"]; 
$name = $_SESSION["name"];
$password = $_SESSION["password"]; 
$microtimeID = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()); 
$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/getProperties.php?name=' . $_SESSION["name"] . '&password=' .$_SESSION["password"]  . '&gameID=' . $_SESSION["gameID"] . '&i=' . $microtimeID;
$json =  file_get_contents($builtURL);
$parametersObj = json_decode($json, true);
$timeAtLoad = time();
$roundEnd = $parametersObj['content']["roundEnd"];
$currentRoundNum = $parametersObj['content']["currentRound"];
//$roundFromQuery = getHtmlParameter( "round" );
if ($currentRoundNum==0) {
	$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/finishRound.php?round=0&name=' . $name  . '&password=' . $password . '&gameID=' . $gameID . '&i=' . $microtimeID;
	echo $builtURL;
	$json =  file_get_contents($builtURL);
	//echo($json);
	//var_dump(json_decode($json, true));
	//echo "<p>". var_dump(json_decode($json, true, 512, JSON_BIGINT_AS_STRING)) ."</p>";
	//$finishRoundObj = json_decode($json, true);	
}

$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/getAllTeams.php?round='. $currentRoundNum . '&name=' . $name . '&password=' . $password  . '&gameID=' . $gameID . '&i=' . $microtimeID;
//echo $builtURL;
$json =  file_get_contents($builtURL);
//echo($json);
//var_dump(json_decode($json, true));
//echo "<p>". var_dump(json_decode($json, true, 512, JSON_BIGINT_AS_STRING)) ."</p>";
//$roundInfoObj = json_decode($json, true);
//require_once "doorway.php" ;

$preLoadtheData = file_get_contents('http://mcolombo.com/flashLemonadeStand/public/web/dbTools/getChartData.php?chartToShow=pricePerCup');


/*******************************************************
 *
 *     LEMONADE STAND pricing Simulation
 *
 *     by Michael Colombo, 2016
 *
 *     presentResults.php  
 *     Display all post-game informaion
 *     data tables, charts, etc
 *     
 *     
 *     Called by roundsRunner.asp
 *
 *     calls charting.php, getAllTeams.php
 *
 ********************************************************/
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

    <title>Lemonade Stand Administration - Present Results</title>

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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
				google.charts.load('current', {'packages':['corechart']});

		  function resetRestart(gameID, gameName) {
              if (confirm('Are you sure you want to end the game (' + gameName + '), delete all teams and start over?')) {
                  if (window.XMLHttpRequest) {
                      // code for IE7+, Firefox, Chrome, Opera, Safari
                      xmlhttp = new XMLHttpRequest();
                  } else {
                      // code for IE6, IE5
                      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                  }
                  xmlhttp.onreadystatechange = function () {
                      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                          //alert(xmlhttp.responseText);
						  window.location = "team.php";
                      }
                  };
				  builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/reset.php?name=' + '<?php echo $name?>' + '&password=' + '<?php echo $password?>' + '&gameID=' + gameID + '&i=' +  '<?php echo $microtimeID?>';	
				  //alert(builtURL);
                  xmlhttp.open("GET", builtURL, true);
                  xmlhttp.send();
              }
          }
		  
		  function startWithSameTeams(gameID, gameName) {
              if (confirm('Are you sure you want to clear all data, restart the game (' + gameName + '), start over (with the same teams)?')) {
					window.location = 'startAgain.php?gameID=' + gameID + '&name=' + '<?php echo $name?>' + '&password=' + '<?php echo $password?>' + '&i=' +  '<?php echo $microtimeID?>';
              }
          }
		  

	function displayRoundData(roundToSee) {
		  //alert(teamName+", "+teamPassword);
		  if (window.XMLHttpRequest) {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp = new XMLHttpRequest();
		  } else {
			  // code for IE6, IE5
			  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp.onreadystatechange = function() {
			  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			  	  document.getElementById("dataTable").style.height = "initial";
				  document.getElementById("dataTable").innerHTML = xmlhttp.responseText;
				  //alert(xmlhttp.responseText);
			  }
		  };
		  builtURL = "dbTools/showRoundData.php?roundToShow="+roundToSee+"&i=<?php echo $microtimeID?>";
		  //alert(builtURL);
		  xmlhttp.open("GET",builtURL,true);
		  xmlhttp.send();
	  }
	  function getChartData(chartToShow) {
		  if (window.XMLHttpRequest) {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp = new XMLHttpRequest();
		  } else {
			  // code for IE6, IE5
			  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp.onreadystatechange = function() {
			  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			     //alert(xmlhttp.responseText);		
				 if (chartToShow=="extras") {
 					document.getElementById("dataTable").style.height = "initial";
				  	document.getElementById("dataTable").innerHTML = xmlhttp.responseText;				 	
				 } else {
				 	var chartDataArray = JSON.parse(xmlhttp.responseText);	
				 } 
				 //alert(chartDataArray); 
				 //renderChart(chartDataArray);
				 renderChart(chartToShow, chartDataArray);				 
			  }
		  };
		  builtURL = "dbTools/getChartData.php?chartToShow="+chartToShow+"&i=<?php echo $microtimeID?>";
		  //alert(builtURL);
		  xmlhttp.open("GET",builtURL,true);
		  xmlhttp.send();	  
	  }
	  
	  function renderChart(chartToShow, arrayfordataTable) {
	  	//alert(chartToShow);
	  	if (chartToShow=="pricePerCup") {
			var jsonData = google.visualization.arrayToDataTable(arrayfordataTable);
			
			var options = {
					  title : 'Price/Cup by Team',
					  vAxis: {title: 'Price', format: "currency"},
					  hAxis: {title: 'Round'},
					  seriesType: 'bars',
					  series: {<?php echo $_SESSION["numOfTeams"] ?>: {type: 'line'}},
					  legend: 'bottom',
					  pointsVisible: true
					};
			
					var chart = new google.visualization.ComboChart(document.getElementById('dataTable'));
					//document.getElementById('dataTable').style.width='900px';
					document.getElementById('dataTable').style.height='500px';
					//chart.draw(data, options);
					chart.draw(jsonData, options);
			} else if (chartToShow=="market") {
				var data = google.visualization.arrayToDataTable(arrayfordataTable);
		
				var options = {
				  title: 'Total Number of Customers',
				  hAxis: {title: 'Round',  titleTextStyle: {color: '#333'}},
				  vAxis: {minValue: 0},
				  legend: 'bottom',
				  pointsVisible: true
				};
		
				var chart = new google.visualization.AreaChart(document.getElementById('dataTable'));
				document.getElementById('dataTable').style.height='500px';
				chart.draw(data, options);
			} else if (chartToShow=="cash") {
				//alert(arrayfordataTable);
				var jsonData = google.visualization.arrayToDataTable(arrayfordataTable);
			
				var options = {
				  title : 'Cash Holdings by Team',
				  vAxis: {title: 'Cash', format: 'currency'},
				  hAxis: {title: 'Round'},
				  seriesType: 'bars',
				  series: {<?php echo $_SESSION["numOfTeams"] ?>: {type: 'line'}},
				  legend: 'bottom',
				  pointsVisible: true
				};
			
				var chart = new google.visualization.ComboChart(document.getElementById('dataTable'));
				document.getElementById('dataTable').style.height='500px';
				chart.draw(jsonData, options);		
			}	else if (chartToShow=="revenue") {
			
				var jsonData = google.visualization.arrayToDataTable(arrayfordataTable);
				var options = {
				  title : 'Revenue by Team',
				  vAxis: {title: 'Revenue', format: "currency"},
				  hAxis: {title: 'Round'},
				  seriesType: 'bars',
				  series: {<?php echo $_SESSION["numOfTeams"] ?>: {type: 'line'}},
				  legend: 'bottom',
				  pointsVisible: true
				};
			
				var chart = new google.visualization.ComboChart(document.getElementById('dataTable'));
				document.getElementById('dataTable').style.height='500px';
				chart.draw(jsonData, options);
			}  	else if (chartToShow=="extras") {
				//build the table showing what options were purchased by who and when
			}
	  }	  
	  
</script>
  </head>

  <body id=background>

    <div class="container">

      <!-- The justified navigation menu is meant for single line per list item.
           Multiple lines will require custom code not provided by Bootstrap. -->
      <div class="masthead">
        <h3 class="text-muted">Results  - <?php echo urldecode($_SESSION["gameName"]) ?></h3>
        <nav>
          <ul class="nav nav-justified">
            <li class="disabled"><a data-toggle="tooltip" title="Parameter Setting Disabled During Game">Set Parameters</a></li>
            <li class="disabled"><a data-toggle="tooltip" title="Parameter Setting Disabled During Game">Set Options</a></li>
            <li class="active"><a>Results</a></li>
            <?php
              if (isset($_GET["results"])) {
                $showResults = htmlspecialchars($_GET["results"]);
              } else {
                  $showResults = "no";
              }
              if ($showResults=="yes") {
                  echo '<li><a href="results.php?results=yes">Results</a></li>';
              }
              ?>
          </ul>
       </nav>
      </div>
      
        <div id="txtHint" style="background-color:#FFFF99">
          <div align="center">
             <nav>
                 <ul class="pagination">
                 	<li><a href="#" onClick="displayRoundData(0)">Round 0</a></li>
                 	<?php 
					for ($x = 1; $x<= $_SESSION["numRounds"]; $x++) {
						echo '<li ';
						if ($currentRoundNum==$x) { 
							echo 'class="pulsate disabled"';
						}
						if ($currentRoundNum<=$x) { 
							echo 'class="disabled"';
						}
						echo '><a href="#" onClick="displayRoundData('.$x.')">Round '.$x.'</a></li>';		
                	}?>   
                </ul>
			</nav>
          </div>
<div id="dataTable">
          <table align="center" class="table table-bordered table-hover table-condensed" style="width:95%">
        	<thead>
              <tr>
                <td style="border:none"><strong>Round <?php echo $currentRoundNum-1 ?> Results</strong></td>
                <td style="border:none">&nbsp;</td>
                <td colspan="6" bordercolor="#000000"><div align="center">Investments</div></td>
                <td style="border:none">&nbsp;</td>
                <td style="border:none">&nbsp;</td>
              </tr>
              <tr>
                <td style="width:26%" class="vert-bottom">Team</th>
                <td style="width:8%" class="vert-bottom">Price/Cup</th>
                <td style="width:7%" class="vert-bottom">Efficiency</th>
                <td style="width:7%" class="vert-bottom">Loyalty</th>
                <td style="width:7%" class="vert-bottom">Ad Budget</th>
                <td style="width:7%"  class="vert-bottom">Pricing Survey</th>
                <td style="width:7%"  class="vert-bottom">Market Report</th>
                <td style="width:7%"  class="vert-bottom">Customer Report</th>
                <td align="right" style="width:12%" class="vert-bottom">Customers</th>
                <td style="width:12%"  class="vert-bottom">Cash</th>              
              </tr>
             </thead>
            


            <?php
			//Connect to the lemonadestand database
			require_once "dbTools/db_connect.php";
			mysqli_select_db($con,"mcklemonsnew");
			//Get records from the team table
			$roundForQuery = $currentRoundNum-1;
			$query = "SELECT teamRound.*, team.name FROM team INNER JOIN teamRound ON teamRound.teamId = team.id WHERE teamRound.gameID = '" .$gameID . "' AND round=" . $roundForQuery . " ORDER BY team.name";
			//echo $query;
			$result = mysqli_query($con, $query);
			$_SESSION["numOfTeams"] = mysqli_num_rows($result);
			if (!$result) {
				echo "Select from teamRounds failed. ", mysqli_error($con);
				exit();
			}
            if (!$con) {
                die('Could not connect: ' . mysqli_error($con));
            }
            while($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>".$row['name']. "</td>";
				echo "<td align='center'>".money_format('$%i',$row['price']). "</td>";
				echo "<td align='center'>".$row['investInEfficiency']. "</td>";
				echo "<td align='center'>".$row['investInLoyalty']. "</td>";
				echo "<td align='center'>".money_format('$%i',$row['advertisingBudget']). "</td>";
				echo "<td align='center'>".$row['buyPricingSurvey']. "</td>";
				echo "<td align='center'>".$row['buyMarketResearch']. "</td>";
				echo "<td align='center'>".$row['buyCustomerReport']. "</td>";
				echo "<td align='right'>".$row['customerBase']. "</td>";
				echo "<td>".money_format('$%i',$row['cash']). "</td>";
                echo "</tr>";
            }
            mysqli_close($con);
            ?>
   	  </table>
          </div>
          	<div class="row">             	
            	<div align="center">               	
                     <nav>
                         <ul class="pagination">
                            <li><a href="#" onClick="getChartData('pricePerCup')">Price/Cup</a></li>
                            <li><a href="#" onClick="getChartData('market')">Market</a></li>
                            <li><a href="#" onClick="getChartData('extras')">Extras</a></li>
                            <li><a href="#" onClick="getChartData('cash')">Cash</a></li>
                            <li><a href="#" onClick="getChartData('revenue')">Revenue</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="row">             	
            	<div align="center">  
            		<div class="col-md-12">
            		  <h1><a href="#" role="button" class="btn btn-lg btn-danger" onClick="resetRestart('<?php echo $gameID?>', '<?php echo urldecode($_SESSION["gameName"]) ?>')">NEW GAME</a></h1>
            		</div>
                    <!-- <div class="col-md-6">
                      <h1 id="blueButton"><a href="#" id="navButton" role="button" class="btn btn-lg  btn-primary" onClick="startWithSameTeams('<?php echo $gameID?>',' <?php echo urldecode($_SESSION["gameName"])?>')">Replay Game</a></h1>
                  </div>-->
                </div>
            </div>
           
          </div>
           </div>
          
        </div>


    </div> <!-- /container -->



    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="bootstrap/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
