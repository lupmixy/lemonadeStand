<?php
// define variables and set to empty values
session_start();
$nameErr = $pwdErr = "";
$name = $pwd  = "";
$microtimeID = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime());
$_SESSION["gameName"] = "Lemonade Stand Game";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
	$_SESSION["name"] = $name;
  }

  if (empty($_POST["pwd"])) {
    $pwdErr = "Password is required";
  } else {
    $pwd = test_input($_POST["pwd"]);
	$_SESSION["password"] = $pwd;
  }
  
  if (empty($_POST["gameName"])) {
  	$gameNameErr = "Game Name is Required";
  } else {
    $gameName = test_input($_POST["gameName"]);
	$_SESSION["gameName"] = $gameName;  	
  }
}

	
if (!empty($pwd) and !empty($name) and !empty($gameName)) {
	$_SESSION["gameID"] = $microtimeID;
     //$builtURL = getcwd() . '/login.php?name=' . $name . '&password=' . $pwd . '&i=' . $microtimeID;
	$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/login.php?name=' . $name . '&password=' . $pwd . '&gameName=' . $gameName . '&gameID=' . $_SESSION["gameID"] . '&i=' . $microtimeID;
	//echo $builtURL;
	$json =  file_get_contents($builtURL);
	//echo $json;
	if ($json=='{"status":200, "text":"ok", "content":{"admin":true}}') {
		// login successfull reset session and move on
		$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/reset.php?name=' . $name . '&password=' . $pwd . '&gameName=' . $gameName . '&gameID=' . $_SESSION["gameID"] . '&i=' . $microtimeID;
		//echo $builtURL;
		$json =  file_get_contents($builtURL);
		//echo $json;
		if ($json=='{ "status":200, "text":"ok" }') {
			// reset successful move to  get properties			
			header('Location: team.php');
		}			
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
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<title>Lemonade Stand Administration - Login</title>
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
  <h2>Lemonade Admin Tool</h2>
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
      <label for="name">Name:</label>
      <input type="name" class="form-control" id="name" name="name" placeholder="Enter name" value="<?php echo $name;?>">
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
      <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter password" value="<?php echo $pwd;?>">     
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
      <label for="gameID">Game Name:</label>
      <input type="name" class="form-control" id="gameName" name="gameName" placeholder="Lemonade Stand Game Name" value="<?php echo $_SESSION["gameName"] ?>">     
        <?php 
			if (strlen($gameNameErr) > 0) {
				echo '<div class="alert alert-danger"><strong>';
				echo $gameNameErr;
				echo '</strong>';
				echo '</div>';
		   }
	   ?>
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
