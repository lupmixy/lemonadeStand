<?php
session_start();
$gameID = $_SESSION["gameID"];
$name = $_SESSION["name"];
$password = $_SESSION["password"]; 
$microtimeID = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()); 
$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/getProperties.php?name=' . $_SESSION["name"] . '&password=' .$_SESSION["password"]  . '&gameID=' . $_SESSION["gameID"] . '&i=' . $microtimeID;
//echo $builtURL;
$json =  file_get_contents($builtURL);
//var_dump(json_decode($json, true));
//echo "<p>". var_dump(json_decode($json, true, 512, JSON_BIGINT_AS_STRING)) ."</p>";
$parametersObj = json_decode($json, true);
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

    <title>Lemonade Stand Administration - Set Parameters</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="bootstrap/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="justified-nav.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
     <script>
	 
	          function setParameters() {
				  if (window.XMLHttpRequest) {
					  // code for IE7+, Firefox, Chrome, Opera, Safari
					  xmlhttp = new XMLHttpRequest();
				  } else {
					  // code for IE6, IE5
					  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				  }
				  xmlhttp.onreadystatechange = function() {
                  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                      //document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
					  //alert(xmlhttp.responseText);
                  }
			  	 };
			  var d = new Date();
			  var roundEnd = d.getTime();
			  var builtURL = "saveProperties.php?totalCustomers="  + document.getElementById('totalCustomers').value + 
			  "&initialPrice="  + document.getElementById('initialPrice').value + 
			  "&reservationPriceMin="  + document.getElementById('reservationPriceMin').value + 
			  "&reservationPriceMode="  + document.getElementById('reservationPriceMode').value + 
			  "&reservationPriceMax="  + document.getElementById('reservationPriceMax').value + 
			  "&reservationIncreaseMin="  + document.getElementById('reservationIncreaseMin').value + 
			  "&reservationIncreaseMode="  + document.getElementById('reservationIncreaseMode').value + 
			  "&reservationIncreaseMax="  + document.getElementById('reservationIncreaseMax').value + 
			  "&loyaltyOddsMin="  + document.getElementById('loyaltyOddsMin').value + 
			  "&loyaltyOddsMode="  + document.getElementById('loyaltyOddsMode').value + 
			  "&loyaltyOddsMax="  + document.getElementById('loyaltyOddsMax').value + 
			  "&zoiMin="  + document.getElementById('zoiMin').value +
			  "&zoiMode="  + document.getElementById('zoiMode').value + 
			  "&zoiMax="  + document.getElementById('zoiMax').value + 
			  "&shopAroundPercentages="  + document.getElementById('shopAroundPercentages0').value + 
			  "," + document.getElementById('shopAroundPercentages1').value + 
			  "," + document.getElementById('shopAroundPercentages2').value + 
			  "," + document.getElementById('shopAroundPercentages3').value  + 				  				  
			  "&roundEnd=" + roundEnd +
			  "&gameID=" + document.getElementById('gameID').value + 
			  "&name=mck&password=lemon&i=1470148770750";
			  //alert(builtURL);			  
			  xmlhttp.open("GET", builtURL ,true);
              xmlhttp.send();
          }
		  
		  function resetRestart(gameID, gameName) {
              if (confirm('Are you sure you want to end this game, delete all teams and start over?')) {
                  if (window.XMLHttpRequest) {
                      // code for IE7+, Firefox, Chrome, Opera, Safari
                      xmlhttp = new XMLHttpRequest();
                  } else {
                      // code for IE6, IE5
                      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                  }
                  xmlhttp.onreadystatechange = function () {
                      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                          alert(xmlhttp.responseText);
						  window.location = "team.php";
                      }
                  };
				  builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/reset.php?name=' + '<?php echo $name?>' + '&password=' + '<?php echo $password?>' + '&gameID=' + gameID + '&i=' +  '<?php echo $microtimeID?>';	
				  alert(builtURL);
                  xmlhttp.open("GET", builtURL, true);
                  xmlhttp.send();
              }
          }
	 </script>
</head>

<body id=background>

<div class="container">

    <!-- The justified navigation menu is meant for single line per list item.
         Multiple lines will require custom code not provided by Bootstrap. -->
    <div class="masthead">
        <h3 class="text-muted">Lemonade Stand Administration - <?php echo urldecode($_SESSION["gameName"]) ?></h3>
        <nav>
            <ul class="nav nav-justified">
                <li class="active"><a href="#">Set Parameters</a></li>
                <li><a href="stats.php?results=no">Set Options</a></li>
                <li><a href="team.php?results=no">Create Teams</a></li>                
            </ul>
        </nav>
    </div>
    <form name="parameters" method="post"  action="setParameters.php">
    	<input type="hidden" name="gameID" id="gameID" value="<?php echo $gameID?>">
        <table class="table table-striped"><tbody><tr><td align="center"><table class="table table-striped">
                        <thead>
                        <tr>
                            <th align="right"><div align="right">Parameter</div></th>
                            <th align="center"><div align="center">Round 0 Initial Values</div></th>
                        </tr>
                        </thead>
                        <tbody>
                        <!--<tr>
                            <td align="right">Number of Rounds</td>
                            <td align="center"><input name="numberOfRounds" type="text" id="numberOfRounds" value="" size="5"></td>
                        </tr>-->
                        <tr>
                            <td align="right">Initial Price/Cup</td>
                            <td align="center"><input name="initialPrice" type="text" id="initialPrice" value="<?php echo $parametersObj['content']["initialPrice"] ?>" size="5"></td>
                        </tr>
                        <tr>
                            <td align="right">Customer Base (Total Customers)</td>
                            <td align="center"><input name="totalCustomers" type="text" id="totalCustomers" value="<?php echo $parametersObj['content']["totalCustomers"] ?>" size="5"></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th align="right"><div align="right">Parameter</div></th>
                            <th align="center"><div align="center">Min</div></th>
                            <th align="center"><div align="center">Mode</div></th>
                            <th align="center"><div align="center">Max</div></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td align="right">Reservation Price</td>
                            <td align="center"><input name="reservationPriceMin" type="text" id="reservationPriceMin" value="<?php echo "{$parametersObj['content']['reservationPriceMin']}" ?>" size="5"></td>
                            <td align="center"><input name="reservationPriceMode" type="text" id="reservationPriceMode" value="<?php echo "{$parametersObj['content']['reservationPriceMode']}" ?>" size="5"></td>
                            <td align="center"><input name="reservationPriceMax" type="text" id="reservationPriceMax" value="<?php echo "{$parametersObj['content']['reservationPriceMax']}" ?>" size="5"></td>
                        </tr>
                        <tr>
                            <td align="right">Reservation Increase</td>
                            <td align="center"><input name="reservationIncreaseMin" type="text" id="reservationIncreaseMin" value="<?php echo "{$parametersObj['content']['reservationIncreaseMin']}" ?>" size="5"></td>
                            <td align="center"><input name="reservationIncreaseMode" type="text" id="reservationIncreaseMode" value="<?php echo "{$parametersObj['content']['reservationIncreaseMode']}" ?>" size="5"></td>
                            <td align="center"><input name="reservationIncreaseMax" type="text" id="reservationIncreaseMax" value="<?php echo "{$parametersObj['content']['reservationIncreaseMax']}" ?>" size="5"></td>
                        </tr>
                        <tr>
                            <td align="right">Loyalty Odds</td>
                            <td align="center"><input name="loyaltyOddsMin" type="text" id="loyaltyOddsMin" value="<?php echo "{$parametersObj['content']['loyaltyOddsMin']}" ?>" size="5"></td>
                            <td align="center"><input name="loyaltyOddsMode" type="text" id="loyaltyOddsMode" value="<?php echo "{$parametersObj['content']['loyaltyOddsMode']}" ?>" size="5"></td>
                            <td align="center"><input name="loyaltyOddsMax" type="text" id="loyaltyOddsMax" value="<?php echo "{$parametersObj['content']['loyaltyOddsMax']}" ?>" size="5"></td>
                        </tr>
                        <tr>
                            <td align="right">Zone of Indifference</td>
                            <td align="center"><input name="zoiMin" type="text" id="zoiMin" value="<?php echo "{$parametersObj['content']['zoiMin']}" ?>" size="5"></td>
                            <td align="center"><input name="zoiMode" type="text" id="zoiMode" value="<?php echo "{$parametersObj['content']['zoiMode']}" ?>" size="5"></td>
                            <td align="center"><input name="zoiMax" type="text" id="zoiMax" value="<?php echo "{$parametersObj['content']['zoiMax']}" ?>" size="5"></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th align="right"><div align="right">&nbsp;</div></th>
                            <th align="center"><div align="center">1</div></th>
                            <th align="center"><div align="center">2</div></th>
                            <th align="center"><div align="center">3</div></th>
                            <th align="center"><div align="center">4</div></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td align="right">Shop Around Count (Percentages)</td>
                            <td align="center"><input name="shopAroundPercentages0" type="text" id="shopAroundPercentages0" value="<?php echo "{$parametersObj['content']['shopAroundPercentages'][0]}" ?>" size="5"></td>
                            <td align="center"><input name="shopAroundPercentages1" type="text" id="shopAroundPercentages1" value="<?php echo "{$parametersObj['content']['shopAroundPercentages'][1]}" ?>" size="5"></td>
                            <td align="center"><input name="shopAroundPercentages2" type="text" id="shopAroundPercentages2" value="<?php echo "{$parametersObj['content']['shopAroundPercentages'][2]}" ?>" size="5"></td>
                            <td align="center"><input name="shopAroundPercentages3" type="text" id="shopAroundPercentages3" value="<?php echo "{$parametersObj['content']['shopAroundPercentages'][3]}" ?>" size="5"></td>
                        </tr>
                        </tbody>
                    </table>
                    <p align="right"><a class="btn btn-primary" href="#" role="button" onClick="setParameters()">Save Parameters &raquo;</a></p>
    </form>
    
        <!-- Example row of columns -->
    <!--<div class="row">
        <div align="center">
            <div class="col-md-4"><h1><a href="#" role="button" class="btn btn-lg btn-danger" onClick="resetRestart('<?php echo $gameID?>', '<?php echo urldecode($_SESSION["gameName"]) ?>')">RESTART GAME</a></h1></div>
            <div class="col-md-4"><h1><span class="label label-info">Timer: 3:00</span></h1></div>
            <div class="col-md-4"><h1><button type="button" class="btn btn-lg  btn-primary">Set + Start</button></h1></div>
        </div>
    </div><h1 align="center">Waiting in Round 0...</h1>-->

    
    
</div> 
<div id="txtHint"></div><!-- /container -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="bootstrap/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
