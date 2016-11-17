<?php
session_start();
//$_SESSION["gameID"] = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime());
if (is_null($_SESSION["numRounds"])) {
	$_SESSION["numRounds"] = 6;
}
$gameID = $_SESSION["gameID"]; 
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

    <title>Lemonade Stand Administration - Set Options - </title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="bootstrap/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="justified-nav.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="bootstrap/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="bootstrap/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
     <script>
	 	function checkboxTester(checkboxInput) {
			//alert("checkboxInput is: " + checkboxInput);
			if (checkboxInput) {				
				checkboxInput = "true";
				//alert(checkboxInput);
				return checkboxInput;
			} else {
				checkboxInput = "false";
				//alert(checkboxInput);
				return checkboxInput;
			}
		}
	 
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
			  var builtURL = "saveProperties.php?baseFixedCost="  + document.getElementById('baseFixedCost').value + 
			  "&variableCost="  + document.getElementById('variableCost').value + 
			  
			  "&pricingSurveyAvailable=" +
			  <?php 
				for ($x = 0; $x<= ($_SESSION["numRounds"]); $x++) {
					echo "  checkboxTester(document.getElementById('pricingSurveyAvailable".$x."').checked) + ";
					if ($x<($_SESSION["numRounds"])) {
						echo " ',' + \n";
					}
				
                }?>   		  
			  "&pricingSurveyCost="  + document.getElementById('pricingSurveyCost').value + 
			  
			  "&marketResearchAvailable=" +
			  <?php 
				for ($x = 0; $x<= ($_SESSION["numRounds"]); $x++) {
					echo "  checkboxTester(document.getElementById('marketResearchAvailable".$x."').checked) + ";
					if ($x<($_SESSION["numRounds"])) {
						echo " ',' + \n";
					}
				
                }?>   	
			  "&marketShareReportCost="  + document.getElementById('marketShareReportCost').value +   

			  "&customerReportAvailable=" +
			  <?php 
				for ($x = 0; $x<= ($_SESSION["numRounds"]); $x++) {
					echo "  checkboxTester(document.getElementById('customerReportAvailable".$x."').checked) + ";
					if ($x<($_SESSION["numRounds"])) {
						echo " ',' + \n";
					}
				
                }?>  
			  "&customerReportCost="  + document.getElementById('customerReportCost').value + 
			  
			  "&efficiencyAvailable="  +
			  <?php 
				for ($x = 0; $x<= ($_SESSION["numRounds"]); $x++) {
					echo "  checkboxTester(document.getElementById('efficiencyAvailable".$x."').checked) + ";
					if ($x<($_SESSION["numRounds"])) {
						echo " ',' + \n";
					}
				
                }?>  
			  "&newProcessFixCostAdder="  + document.getElementById('newProcessFixCostAdder').value + 
			  "&newProcessVariableCostAdder="  + document.getElementById('newProcessVariableCostAdder').value + 
			  
			  "&advertisingAvailable="  +
			  <?php 
				for ($x = 0; $x<= ($_SESSION["numRounds"]); $x++) {
					echo "  checkboxTester(document.getElementById('advertisingAvailable".$x."').checked) + ";
					if ($x<($_SESSION["numRounds"])) {
						echo " ',' + \n";
					}
				
                }?>  
			  
			  
			  "&loyaltyAvailable=" +
			  <?php 
				for ($x = 0; $x<= ($_SESSION["numRounds"]); $x++) {
					echo "  checkboxTester(document.getElementById('loyaltyAvailable".$x."').checked) + ";
					if ($x<($_SESSION["numRounds"])) {
						echo " ',' + \n";
					}
				
                }?>  			  
			  "&loyaltyBoostPercent="  + document.getElementById('loyaltyBoostPercent').value + 
			  "&loyaltyBoostPricePerCustomer="  + document.getElementById('loyaltyBoostPricePerCustomer').value + 	
			  	  
			  "&percentageOfCustomersWillingToPurchase="  
			  	<?php 
				for ($x = 0; $x<= ($_SESSION["numRounds"]); $x++) {
					echo " + document.getElementById('percentageOfCustomersWillingToPurchase".$x."').value  + ";
					if ($x<($_SESSION["numRounds"])) {
						echo " ',' + \n";
					}
				
                }?>  
				
			  "&numRounds=" + document.getElementById('numRounds').value +
			  "&roundEnd=" + roundEnd +
			  "&gameID=" + document.getElementById('gameID').value + 
			  "&numRounds=" +  document.getElementById('numRounds').value +
			  "&name=mck&password=lemon&i=1470148770750";
			  //alert(builtURL);
			  //document.getElementById("builtURLdisplay").innerHTML = builtURL;			  
			  xmlhttp.open("GET", builtURL ,true);
              xmlhttp.send();
     	}
		
		function spinnerChanged(textValue) {
			if (textValue<5) {
				alert("Minimum number of rounds is 5");
				document.getElementById('numRounds').value = 5;
			} else if (textValue>12) {
				alert("Maximum number of rounds is 12");
				document.getElementById('numRounds').value = 12;			
			}
			 if (window.XMLHttpRequest) {
                  // code for IE7+, Firefox, Chrome, Opera, Safari
                  xmlhttp = new XMLHttpRequest();
              } else {
                  // code for IE6, IE5
                  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
              }
              xmlhttp.onreadystatechange = function() {
                  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				  	  document.getElementById("optionsTable").innerHTML = xmlhttp.responseText;
					  location.reload(true);
                      //alert(xmlhttp.responseText);
                  }
              };
			  builtURL = "dbTools/updateOptionsTable.php?numRounds="+document.getElementById('numRounds').value+
			  "&gameID=<?php echo $gameID ?>&i=<?php echo $microtimeID?>";
			  //alert(builtURL);
			  //document.getElementById("builtURLdisplay").innerHTML = builtURL;
			  xmlhttp.open("GET",builtURL,true);
              xmlhttp.send();			  
			  //document.getElementById("numRounds").value = textValue;			  
		}
		
		function navigateTo(destination) {
			//first, save things
			setParameters();
			//now got to the destination
			window.location.href = destination;
		}

	 </script>
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>  
$( "#spinner" ).spinner({
  change: function( event, ui ) {
  	spinnerChanged(ui.value);
  }
});
	  </script>-->

</head>

  <body id=background>

    <div class="container">

      <!-- The justified navigation menu is meant for single line per list item.
           Multiple lines will require custom code not provided by Bootstrap. -->
      <div class="masthead">
        <h3 class="text-muted">Lemonade Stand Administration - <?php echo urldecode($_SESSION["gameName"]) ?></h3>
        <nav>
          <ul class="nav nav-justified">
            <li><a href="#" onClick="navigateTo('setParameters.php')">Set Parameters</a></li>            
            <li class="active"><a href="#" onClick="navigateTo('stats.php')">Set Options</a></li>
            <li><a href="#" onClick="navigateTo('team.php')">Create Teams</a></li>
          </ul>
       </nav>
      </div>
      
                <!-- Example row of columns -->
      <!--<div class="row">
      	<div align="center">
            <div class="col-md-4"><h1><button type="button" class="btn btn-lg btn-danger">RESTART GAME</button></h1></div>
            <div class="col-md-4"><h1><span class="label label-info">Timer: 3:00</span></h1></div>
            <div class="col-md-4"><h1><button type="button" class="btn btn-lg  btn-primary">Set + Start</button></h1></div>
        </div>
      </div>
      
             <h1 align="center">Waiting in Round 0...</h1>-->
             <div id="builtURLdisplay"></div>
     <div id="optionsTable" style="overflow-x:auto"> 
<form name="form1" method="post" action=""> 
    	<input type="hidden" name="gameID" id="gameID" value="<?php echo $gameID?>">
        <input type="hidden" name="gameName" id="gameName" value="<?php echo $gameName?>">
           <table width="100%" class="table table-striped">
            <thead  class="table table-striped">
              <tr>
              	<th align="center" colspan="3"> <div align="center"><p><label for="numRounds">Number of Rounds:&nbsp;&nbsp;</label><input id="numRounds" maxlength="2" onChange="spinnerChanged(document.getElementById('numRounds').value)" type="number" value="<?php echo $_SESSION["numRounds"]?>" name="quantity" min="5" max="12"></p></div></th>
                <?php 
				for ($x = 0; $x<= $_SESSION["numRounds"]; $x++) {
					echo '<th align="center"><div align="center">&nbsp;</div></th>';
				
                }?>               
              </tr>            
              <tr>
                <th align="right"><div align="right">Round Number</div></th>
                <?php 
				for ($x = 0; $x<= $_SESSION["numRounds"]; $x++) {
					echo '<th align="center"><div align="center">'.$x.'</div></th>';
				
                }?>
                <th align="center"> <div align="center">Cost</div></th>
                <th align="center"><div align="center">Var Cost</div></th>
              </tr>
            </thead>
            <tbody  class="table table-striped">
              <tr>
                <td align="right">Efficiency Process</td>
                <?php 
				for ($x = 0; $x<= $_SESSION["numRounds"]; $x++) {
					if ($parametersObj['content']['efficiencyAvailable'][$x]) {
						$isChecked = 'checked';
					} else {
						$isChecked = '';
					}
					//echo $isChecked . "\n";
					echo '<td align="center"><input name="efficiencyAvailable'.$x.'" type="checkbox" id="efficiencyAvailable'.$x.'" value="1" ' . $isChecked . '></td>';
				
                }?>
                <td align="center"><input name="newProcessFixCostAdder" type="text" id="newProcessFixCostAdder" value="<?php echo $parametersObj['content']["newProcessFixCostAdder"] ?>" size="5"></td>
                <td align="center"><input name="newProcessVariableCostAdder" type="text" id="newProcessVariableCostAdder" value="<?php echo $parametersObj['content']["newProcessVariableCostAdder"] ?>" size="5"></td>
              </tr>                      
              <tr>
                <td align="right">Pricing Survey</td>
                 <?php 
				for ($x = 0; $x<= ($_SESSION["numRounds"]); $x++) {
					if ($parametersObj['content']['pricingSurveyAvailable'][$x]) {
						$isChecked = 'checked';
					} else {
						$isChecked = '';
					}
					//echo $isChecked . "\n";
					echo '<td align="center"><input name="pricingSurveyAvailable'.$x.'" type="checkbox" id="pricingSurveyAvailable'.$x.'" value="1" ' . $isChecked . '></td>';				
                }?>
                <td align="center"><input name="pricingSurveyCost" type="text" id="pricingSurveyCost" value="<?php echo $parametersObj['content']["pricingSurveyCost"] ?>" size="5"></td>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="right">Market Research</td>
                <?php 
				for ($x = 0; $x<= ($_SESSION["numRounds"]); $x++) {
					if ($parametersObj['content']["marketResearchAvailable"][$x]) {
						$isChecked = 'checked';
					} else {
						$isChecked = '';
					}				
					echo '<td align="center"><input name="marketResearchAvailable'.$x.'" type="checkbox" id="marketResearchAvailable'.$x.'" value="1" ' . $isChecked . '></td>';				
                }?>
                <td align="center"><input name="marketShareReportCost" type="text" id="marketShareReportCost" value="<?php echo $parametersObj['content']["marketShareReportCost"] ?>" size="5"></td>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="right">Advertisement</td>
                <?php 
				for ($x = 0; $x<= $_SESSION["numRounds"]; $x++) {
					if ($parametersObj['content']["advertisingAvailable"][$x]) {
						$isChecked = 'checked';
					} else {
						$isChecked = '';
					}					
					echo '<td align="center"><input name="advertisingAvailable'.$x.'" type="checkbox" id="advertisingAvailable'.$x.'" value="1" ' . $isChecked . '></td>';				
                }?>                
                <td align="center"><strong>Boost %:</strong></td>
                <td align="center"><strong>Cost/Customer</strong></td>               
              </tr>
              <tr>
                <td align="right">Loyalty Boost</td>
                <?php 
				for ($x = 0; $x<= $_SESSION["numRounds"]; $x++) {
					if ($parametersObj['content']["loyaltyAvailable"][$x]) {
						$isChecked = 'checked';
					} else {
						$isChecked = '';
					}										
					echo '<td align="center"><input name="loyaltyAvailable'.$x.'" type="checkbox" id="loyaltyAvailable'.$x.'" value="1" ' . $isChecked . '></td>';				
                }?>                       
                <td align="center"><input name="loyaltyBoostPercent" type="text" id="loyaltyBoostPercent" value="<?php echo $parametersObj['content']["loyaltyBoostPercent"] ?>" size="5"></td>
                <td align="center"><input name="loyaltyBoostPricePerCustomer" type="text" id="loyaltyBoostPricePerCustomer" value="<?php echo $parametersObj['content']["loyaltyBoostPricePerCustomer"] ?>" size="5"></td>
              </tr>
              <tr>
                <td align="right">Customer Report</td>
                <?php 
				for ($x = 0; $x<= ($_SESSION["numRounds"]); $x++) {
					if ($parametersObj['content']["customerReportAvailable"][$x]) {
						$isChecked = 'checked';
					} else {
						$isChecked = '';
					}					
					echo '<td align="center"><input name="customerReportAvailable'.$x.'" type="checkbox" id="customerReportAvailable'.$x.'" value="1" ' . $isChecked . '></td>';				
                }?>   
                <td align="center"><input name="customerReportCost" type="text" id="customerReportCost" value="<?php echo $parametersObj['content']["customerReportCost"] ?>" size="5"></td>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
                <?php 
				for ($x = 0; $x<= $_SESSION["numRounds"]; $x++) {
					echo '<td align="center">&nbsp;</td>';				
                }?>   
                <td align="center"><strong>Base Fixed Cost</strong></td>
                <td align="center"><strong>Base Variable Cost</strong></td>
              </tr>
              <tr>
                <td align="right">Market Size in %</td>
                <?php 
				for ($x = 0; $x<= $_SESSION["numRounds"]; $x++) {
					//100, 100, 85, 70, 90, 95, 100, 100, 85, 70, 90, 95, 100, 100
							echo ' <td align="center"><input name="percentageOfCustomersWillingToPurchase'.$x.'" type="text" id="percentageOfCustomersWillingToPurchase'.$x.'" value="' . $parametersObj['content']["percentageOfCustomersWillingToPurchase"][$x] . '" size="5"></td>';		
                }?>   
                <td><div align="center"><input name="baseFixedCost" type="text" id="baseFixedCost" value="<?php echo $parametersObj['content']["baseFixedCost"] ?>" size="5">
                </div></td>
                <td><div align="center"><input name="variableCost" type="text" id="variableCost" value="<?php echo $parametersObj['content']["variableCost"] ?>" size="5">
                </div></td>
              </tr>
            </tbody>
      </table>
      </div>	
        <p align="right"><a class="btn btn-primary" href="#" role="button" onClick="setParameters()">Save Parameters &raquo;</a></p>
	  </form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="bootstrap/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
