<?php
	session_start();
	$name = $_SESSION["name"];
	$password = $_SESSION["password"]; 
	$gameID = $_SESSION["gameID"]; 
	$gameName = $_SESSION["gameName"]; 
	$teamName =  test_input($_GET['teamName']);
	$teamPassword = test_input($_GET['teamPassword']);
	$microtimeID = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime());
	$chartToShow = $_GET["chartToShow"];
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  $data = urlencode($data);
	  return $data;
	}
	require_once "dbTools/db_connect.php";
		
		//create the teams again and reset the round to 0	
		$sql = "SELECT * from team WHERE gameID='" . $gameID . "' ORDER by team.name";
		$result = mysqli_query($con,$sql);
		$numReturnedTeams = mysqli_num_rows($result);		
		$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/reset.php?name=' . $name . '&password=' .  $password . '&gameID=' . $gameID .'&i=' . $microtimeID;
		$json =  file_get_contents($builtURL);
		if ($json=='{ "status":200, "text":"ok" }') {
			while($row = mysqli_fetch_array($result)) {
				$builtURL = "http://mcolombo.com/flashLemonadeStand/public/web/createTeam.php?name=mck&password=lemon&teamname=" . $row['name'] . "&teampassword=" . $row['password'] . "&gameName=". $row['gameName'] . "&gameID=" . $row['gameID'] . "&i=". $microtimeID;
				$json =  file_get_contents($builtURL);
			}
			if ($json=='{ "status":200, "text":"ok" }') {
				//$builtURL = 'http://mcolombo.com/flashLemonadeStand/public/web/finishRound.php?round=0&name=' . $name .  '&password=' . $password .  '&gameID=' . $gameID . '&i=' .  $microtimeID;
				//$json =  file_get_contents($builtURL);
				//echo $builtURL;
				//if ($json=='{ "status":200, "text":"ok" }') {
					header('location: http://mcolombo.com/flashLemonadeStand/public/web/roundsRunner.php');
				//} else {
					//echo "error in finishround" . $json;
				//}
			} else {
				echo "error in create team" . $json;
			}
		} else {
			echo "error in reset" . $json;
		}	
?>	


