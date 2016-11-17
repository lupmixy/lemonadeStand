<?php
session_start();
$gameID = $_SESSION["gameID"]; 
$gameName = $_SESSION["gameName"]; 
$name = $_SESSION["name"];
$password = $_SESSION["password"]; 
$microtimeID = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()); 
//require_once "doorway.php" ;

/*******************************************************
 *
 *     LEMONADE STAND pricing Simulation
 *
 *     by Michael Colombo, 2016
 *
 *     team.php  Display a list of participating teams that
 *     can be updated or deleted.
 *     Called by setParameters.php, setOptions.php, results.php
 *
 *     calls addTeam.php, editTeam.php
 *
 ********************************************************/

//Get first team (row)from team table

//here's how the falsh swf created teams
//creating team team1/pwd
//creating team team2/pwd
//creating team team3/pwd
///createTeam.php?name=team2&password=pwd&i=1469747360908 result status:200
//{ "status":200, "text":"ok" }
//team team2 created.
///createTeam.php?name=team1&password=pwd&i=1469747360908 result status:200
//{ "status":200, "text":"ok" }
//team team1 created.
///createTeam.php?name=team3&password=pwd&i=1469747360908 result status:200
//{ "status":200, "text":"ok" }
//team team3 created.

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

    <title>Lemonade Stand Administration - Create Teams</title>

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
          function addTeam(teamName,teamPassword) {
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
				  	  document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                      //alert(xmlhttp.responseText);
                  }
              };
			  builtURL = "dbTools/addTeam.php?teamName="+teamName+"&teamPassword="+teamPassword+
			  "&gameName=<?php echo $gameName ?>&gameID=<?php echo $gameID ?>&i=<?php echo $microtimeID?>";
			  //alert(builtURL);
			  xmlhttp.open("GET",builtURL,true);
              xmlhttp.send();
          }
          function editTeam(teamID) {
              if (window.XMLHttpRequest) {
                  // code for IE7+, Firefox, Chrome, Opera, Safari
                  xmlhttp = new XMLHttpRequest();
              } else {
                  // code for IE6, IE5
                  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
              }
              xmlhttp.onreadystatechange = function() {
                  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                      document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                  }
              };
              xmlhttp.open("GET","dbTools/editTeam.php?teamID="+teamID,true);
			  xmlhttp.send();
          }
          function saveTeam(teamID,teamName,teamPassword) {
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
                      document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                  }
              };
              xmlhttp.open("GET","dbTools/saveTeam.php?teamID="+teamID+"&teamName="+teamName+"&teamPassword="+teamPassword,true);
              xmlhttp.send();
          }
          function deleteTeam(teamID) {
              if (confirm('Are you sure you want to delete this team?')) {
                  if (window.XMLHttpRequest) {
                      // code for IE7+, Firefox, Chrome, Opera, Safari
                      xmlhttp = new XMLHttpRequest();
                  } else {
                      // code for IE6, IE5
                      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                  }
                  xmlhttp.onreadystatechange = function () {
                      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                          document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                      }
                  };
                  xmlhttp.open("GET", "dbTools/deleteTeam.php?teamID=" + teamID, true);
                  xmlhttp.send();
              }
          }
          function stoppedTyping(){
		  	  alert("instoppedtyping");
			  alert(document.getElementById("newname").value.length +  " " +  document.getElementById("newpassword").value.length>0);
              if (document.getElementById("newname").value.length>0 && document.getElementById("newpassword").value.length>0) {
			  //if(this.value.length > 0) {
                  document.getElementById('addTeamBtn').disabled = false;
              } else {
                  document.getElementById('addTeamBtn').disabled = true;
              }
          }
          function verify(){
              if (document.getElementById('newname').value.length == 0) {
                  alert("Team name cannot be blank.");
                  return;
              }
          else{
                  //alert(document.getElementById('newname').value+", "+document.getElementById('newpassword').value);
                  //alert("adding: " + document.getElementById('newname').value + " & " + document.getElementById('newpassword').value);
				  addTeam(document.getElementById('newname').value,document.getElementById('newpassword').value);
              }
          }
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
				  alert(builtURL);
                  xmlhttp.open("GET", builtURL, true);
                  xmlhttp.send();
              }
          }
		  function setStart(gameID, gameName) {
              if (confirm('Are you sure you want to start the game (' + gameName + ')?')) {
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
						  if (xmlhttp.responseText == '{ "status":200, "text":"ok" }') {
							window.location = "roundsRunner.php";
						} else {
							alert(xmlhttp.responseText);
						}
                      }
                  };
				  builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/finishRound.php?round=0&name=' + '<?php echo $name?>' + '&password=' + '<?php echo $password?>' + '&gameID=' + gameID + '&i=' +  '<?php echo $microtimeID?>';	
				  //alert(builtURL);
                  xmlhttp.open("GET", builtURL, true);
                  xmlhttp.send();
              }
          }
		 function setStartMIN(gameID, gameName) {
              if (confirm('Are you sure you want to start the game (' + gameName + ')?')) {
              	window.location = "roundsRunner.php";
              }
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
            <li><a href="setParameters.php">Set Parameters</a></li>            
            <li><a href="stats.php">Set Options</a></li>
            <li class="active"><a href="team.php">Create Teams</a></li>
          </ul>
       </nav>
      </div>
      
                <!--  row of columns -->
      <!--<div class="row">
      	<div align="center">
            <div class="col-md-4"><h1><button type="button" class="btn btn-lg btn-danger">RESTART GAME</button></h1></div>
            <div class="col-md-4"><h1><span class="label label-info">Timer: 3:00</span></h1></div>
            <div class="col-md-4"><h1><button type="button" class="btn btn-lg  btn-primary">Set + Start</button></h1></div>
        </div>
      </div>
      
        <h1 align="center">Waiting in Round 0...</h1>-->
      
        <div id="txtHint" style="background-color:#FFFFFF">
            <div class="row">
                <div class="col-sm-4"><h3><small>TEAM NAME</small></h3></div>
              <div class="col-sm-4"><h3><small>TEAM PASSWORD</small></h3></div>
              <div class="col-sm-4"><h3><small>ACTION</small></h3></div>
          </div>
            <?php
			//Connect to the lemonadestand database
			require_once "dbTools/db_connect.php";
			mysqli_select_db($con,"mcklemonsnew");
			//Get records from the team table
			$query = "SELECT * From team WHERE gameID='". $gameID . "' ORDER BY id";
			//echo $query;
			$result = mysqli_query($con, $query);
			if (!$result) {
				echo "Select from team failed. ", mysqli_error($con);
				exit();
			}
			//$classrow = mysqli_fetch_assoc($result);
            //shoudl already be connected via db_connect at the top
			//$con = mysqli_connect("mysql.mcolombo.com", "mcklemonsuser", "lemons2k16");
            //$con = mysqli_connect("localhost", "mckLemons", "lemonadestand", "lemonadestand");
            if (!$con) {
                die('Could not connect: ' . mysqli_error($con));
            }
            
            //$sql="SELECT * from team WHERE gameID='".$gameID."' ORDER by id";
			//echo $sql;
            //$result = mysqli_query($con,$sql);
			//should already have $result from quesry at the top
            // update grid, with div row for each existing team (with edit button)
            // and row for next team, with "add team" button
            while($row = mysqli_fetch_array($result)) {
                echo "<div class=\"row\">";
                echo "<div class=\"col-sm-4\">".$row['name']. "</div>";
                echo "<div class=\"col-sm-4\">".$row['password']."</div>";
                echo "<div class=\"col-sm-4\"><div class=\"btn-group\"><input type=\"button\" class=\"btn btn-default btn-xs\" value=\"Edit Team Name\" onclick=\"editTeam(".$row['id'].")\">";
                echo "<input type=\"button\" class=\"btn btn-danger btn-xs\" value=\"Remove Team\" onclick=\"deleteTeam(".$row['id'].")\">";
                echo "</div>";
                echo "</div>";
                echo  "</div>";
            }
            mysqli_close($con);
            ?>
            <div class="row">
            	<input type="hidden" name="gameID" id="gameID" value="<?php echo $gameID?>">
                <input type="hidden" name="gameName" id="gameName" value="<?php echo $gameName?>">
                <div class="col-sm-4"><input name="newname" type="text" id="newname" onKeyUp="if (document.getElementById('newname').value.length>0 && document.getElementById('newpassword').value.length>0) document.getElementById('addTeamBtn').disabled = false; else document.getElementById('addTeamBtn').disabled = true;" value="" size="20"></div>
              <div class="col-sm-4"><input name="newpassword" type="text" id="newpassword" onKeyUp="if (document.getElementById('newname').value.length>0 && document.getElementById('newpassword').value.length>0) document.getElementById('addTeamBtn').disabled = false; else document.getElementById('addTeamBtn').disabled = true;" value="" size="20"></div>
              <div class="col-sm-4"><input type="button" class="btn btn-default btn-xs" id="addTeamBtn" value="Add Team" disabled="disabled" onClick="verify()"></div>
          </div>
          
        </div>
        
        <div class="row">
        	<div align="center">
            	<div class="col-md-4"><h1><a href="#" role="button" class="btn btn-lg btn-danger" onClick="resetRestart('<?php echo $gameID?>', '<?php echo urldecode($_SESSION["gameName"]) ?>')">RESTART GAME</a></h1></div>
            	<div class="col-md-4"><h1><span class="label label-info">Waiting in Round 0</span></h1></div>
                <!--<div class="col-md-4"><h1><a href="#" role="button" class="btn btn-lg btn-primary disabled">SET + START</a></h1></div>-->
                <div class="col-md-4"><h1><a href="#" role="button" class="btn btn-lg btn-primary" onClick="setStart('<?php echo $gameID?>', '<?php echo urldecode($_SESSION["gameName"]) ?>')">SET + START</a></h1></div>
        	</div>
    	</div>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="bootstrap/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
