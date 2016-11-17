<?php
// define variables and set to empty values
session_start();
$nameErr = $pwdErr = "";
$name = $pwd  = "";
$microtimeID = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime());
$_SESSION["efficiency"] = 'false';
$_SESSION["pricing"] = 'false';
$_SESSION["marketResearch"] = 'false';
$_SESSION["advertising"] = 'false';
$_SESSION["adBudget"] = 0;
$_SESSION["loyalty"] = 'false';
$_SESSION["customerReport"] = 'false';
$_SESSION['SubmittedRound'] = -1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $teamName = test_input($_POST["name"]);
	$_SESSION["teamName"] = $teamName;
  }

  if (empty($_POST["pwd"])) {
    $pwdErr = "Password is required";
  } else {
    $teamPwd = test_input($_POST["pwd"]);
	$_SESSION["teamPassword"] = $teamPwd;
  }
  
  if (empty($_POST["gameID"])) {
  	$gameNameErr = "No Game Set";
  } else {
    $playersgameID = test_input($_POST["gameID"]);
	//echo "playersgame id is " . $playersgameID;
	$_SESSION["gameID"] = $playersgameID;  	
  }
}

	
if (!empty($teamPwd) and !empty($teamName) and !empty($playersgameID)) {
     //$builtURL = getcwd() . '/login.php?name=' . $name . '&password=' . $pwd . '&i=' . $microtimeID;
	$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/login.php?name=' . $teamName . '&password=' . $teamPwd . '&gameID=' . $_SESSION["gameID"] . '&i=' . $microtimeID;
	//echo $builtURL;
	$json =  file_get_contents($builtURL);
	//echo $json;
	if ($json=='{"status":200, "text":"ok", "content":{"admin":false}}') {	
			header('Location: playerOffice.php');			
	} else {
		$message = "Login Failed. Try Again.";
	}
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  $data = urlencode($data);
  return $data;
}


//Connect to the lemonadestand database
require_once "dbTools/db_connect.php";
mysqli_select_db($con,"mcklemonsnew");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<title>Lemondade Stand Player - Login</title>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="bootstrap/favicon.ico">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="bootstrap/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="justified-nav.css" rel="stylesheet">
</head>

<body id=background>
<div class="container">
  <h2>Lemonade Stand Login</h2>
  
<?php
  //Get records from the team table to 
//list available gameNames
$query = "SELECT `gameName`, `gameID` FROM team GROUP BY `gameName` ORDER BY `id` DESC";
//echo $query;
$result = mysqli_query($con, $query);
if (!$result) {
	echo "No Games Available.", mysqli_error($con);
	exit();
}
if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}
?>
  
  <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-group">
        <?php 
			if (strlen($message) > 0) {
				echo '<div class="alert alert-danger"><strong>';
				echo $message;
				echo '</strong>';
				echo '</div>';
		   }
	   ?>    
      <label for="name">Team Name:</label>
      <input type="name" class="form-control" id="name" name="name" placeholder="Enter provided name" value="<?php echo $name;?>">
        <?php 
			if (strlen($nameErr) > 0) {
				echo '<div class="alert alert-danger"><strong>';
				echo $nameErr;
				echo '</strong>';
				echo '</div>';
		   }
	   ?>
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter provided password" value="<?php echo $pwd;?>">     
        <?php 
			if (strlen($pwdErr) > 0) {
				echo '<div class="alert alert-danger"><strong>';
				echo $pwdErr;
				echo '</strong>';
				echo '</div>';
		   }
	   ?>
    </div>
    <div class="form-group">
      <label for="sel1">Select game:</label>
      <select name="gameID" class="form-control" id="gameID">
		<?php 
        while($row = mysqli_fetch_array($result)) {
            echo "<option value='".$row['gameID']."'>".$row['gameName']. "</option>";
        }
        mysqli_close($con);
        ?>   
      </select>
    </div>
 
    
    
    <button type="submit" class="btn btn-warning btn-lg">>> Login</button>
  </form>

<?php
//$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//echo $actual_link;
//echo getcwd();
//echo "<h2>Your Input:</h2>";
//echo $name;
//echo "<br>";
//echo $pwd;
//echo "<br>";
?>  
  
</div>
</body>
</html>
