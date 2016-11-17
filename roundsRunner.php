<?php
session_start();
$gameID = $_SESSION["gameID"]; 
$gameName = $_SESSION["gameName"]; 
$name = $_SESSION["name"];
$password = $_SESSION["password"]; 
$numRounds = $_SESSION["numRounds"];
$microtimeID = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()); 
$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/getProperties.php?name=' . $_SESSION["name"] . '&password=' .$_SESSION["password"]  . '&gameID=' . $_SESSION["gameID"] . '&i=' . $microtimeID;
//echo $builtURL;
$json =  file_get_contents($builtURL);
$parametersObj = json_decode($json, true);
$timeAtLoad = time();
$roundEnd = $parametersObj['content']["roundEnd"];
$currentRoundNum = $parametersObj['content']["currentRound"];
//$roundFromQuery = getHtmlParameter( "round" );
if ($currentRoundNum==0) {
	$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/finishRound.php?round=0&name=' . $name  . '&password=' . $password . '&gameID=' . $gameID . '&i=' . $microtimeID;
	
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

/*******************************************************
 *
 *     LEMONADE STAND pricing Simulation
 *
 *     by Michael Colombo, 2016
 *
 *     roundsRunner.php  
 *     Display data of all relevant teams during active game
 *     display master game timer
 *     provide button to CLOSE ROUND NOW
 *     enable admin to see prices from past rounds
 *     Called by team.php, setParameters.php, stats.php, finishRound.php,
 *
 *     calls finishRound.php, getAllTeams.php
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

    <title>Lemonade Stand Administration - Game Underway</title>

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
      <script>
	  	  //if you get here and it is still round zero, call finishround
		function roundZeroCheck(gameID) {			
		  	if (<?php echo $currentRoundNum?>==0)  {
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
					 window.location = "roundsRunner.php";
				  }
			  };
			  builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/finishRound.php?round=0&name=' + '<?php echo $name?>' + '&password=' + '<?php echo $password?>' + '&gameID=' + gameID + '&i=' +  '<?php echo $microtimeID?>';	
			  //alert(builtURL);
			  xmlhttp.open("GET", builtURL, true);
			  xmlhttp.send();
			}
		}
		window.onload = roundZeroCheck(<?php echo $gameID ?>);
	  
	  
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
		  function startNextRound(gameID, curRound) {
		  		  //document.getElementById('blueButton') = null;
                  //alert("gameID is" + gameID + "curRound is " + curRound);
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
						  window.location = "roundsRunner.php";
                      }
                  };
				  roundToFinish = curRound-1;
				  builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/finishRound.php?round=' + roundToFinish + '&name=' + '<?php echo $name?>' + '&password=' + '<?php echo $password?>' + '&gameID=' + gameID + '&i=' +  '<?php echo $microtimeID?>';	
				  //alert(builtURL);
                  xmlhttp.open("GET", builtURL, true);
                  xmlhttp.send();
		  }
		  function presentResults(gameID, curRound) {
                  //alert("gameID is" + gameID + "curRound is " + curRound);
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
						  window.location = "presentResults.php";
                      }
                  };
				  roundToFinish = curRound-1;
				  builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/finishRound.php?round=' + roundToFinish + '&name=' + '<?php echo $name?>' + '&password=' + '<?php echo $password?>' + '&gameID=' + gameID + '&i=' +  '<?php echo $microtimeID?>';	
				  //alert(builtURL);
                  xmlhttp.open("GET", builtURL, true);
                  xmlhttp.send();
		  }

		  
		  //timer
	//alert('roundEnd is ' + <?php echo $roundEnd?> + 'time at load is ' + <?php echo $timeAtLoad?>);	  
    //CountDownTimer(<?php echo $roundEnd?>, 'countdown');
	///load in server time via something like on window(LOAD)
	//var now = new Date();
		///	now.setTime(<?php echo time()?>);

    //function CountDownTimer(dt, id)
    //{
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
				closeRound(<?php echo $gameID . ',' . $currentRoundNum ?>);
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
        }

       timer = setInterval(showRemaining, 1000);
    //}
	function closeRound(gameID, curRound) {
		clearInterval(timer);
		curRound++;
		document.getElementById('countdown').innerHTML = '';		
		if (curRound><?php echo $_SESSION["numRounds"]?>) {
			document.getElementById('infoLabel').innerHTML = '';
			newButtonText = '<a href="#" id="navButton" role="button" class="btn btn-lg  btn-primary" onClick="presentResults(\'' + gameID + '\',' + curRound + ')">Present Results</a>';
		} else {
			document.getElementById('infoLabel').innerHTML = 'Prepare for round ' + curRound;
			newButtonText = '<a href="#" id="navButton" role="button" class="btn btn-lg  btn-primary" onClick="startNextRound(\'' + gameID + '\',' + curRound + ')">Start Round ' +  curRound +  '</a>';
		}
		//alert(newButtonText);
		document.getElementById('blueButton').innerHTML = newButtonText;  	
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
				  document.getElementById("dataTable").innerHTML = xmlhttp.responseText;
				  //alert(xmlhttp.responseText);
			  }
		  };
		  builtURL = "dbTools/showRoundData.php?roundToShow="+roundToSee+"&i=<?php echo $microtimeID?>";
		  //alert(builtURL);
		  xmlhttp.open("GET",builtURL,true);
		  xmlhttp.send();
	  }

</script>
  </head>

  <body id=background>

    <div class="container">

      <!-- The justified navigation menu is meant for single line per list item.
           Multiple lines will require custom code not provided by Bootstrap. -->
      <div class="masthead">
        <h3 class="text-muted">Lemonade Stand Administration  - <?php echo urldecode($_SESSION["gameName"]) ?></h3>
        <nav>
          <ul class="nav nav-justified">
            <li class="disabled"><a data-toggle="tooltip" title="Parameter Setting Disabled During Game">Set Parameters</a></li>
            <li class="disabled"><a data-toggle="tooltip" title="Parameter Setting Disabled During Game">Set Options</a></li>
            <li class="active"><a>Game Underway</a></li>
          </ul>
       </nav>
      </div>
      
        <div id="txtHint" style="background-color:#FFFFFF">
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
                <td align="right" style="width:12%; text-align:right" class="vert-bottom">Customers</th>
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
                    <div class="col-md-3"><h1><a href="#" role="button" class="btn btn-lg btn-danger" onClick="resetRestart('<?php echo $gameID?>', '<?php echo urldecode($_SESSION["gameName"]) ?>')">RESTART GAME</a></h1></div>
                    <div class="col-md-3"><h1><span id="infoLabel" class="label label-default">Round <?php echo $currentRoundNum ?> running</span></h1></div>
                    <div class="col-md-3"><h1><span id="countdown" class="label label-info">Round Ends in 3:00</span></h1></div>
                    <div class="col-md-3"><h1 id="blueButton"><a href="#" id="navButton" role="button" class="btn btn-lg  btn-primary" onClick="closeRound('<?php echo $gameID?>', <?php echo $currentRoundNum ?>)">Close Round NOW</a></h1></div>
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
