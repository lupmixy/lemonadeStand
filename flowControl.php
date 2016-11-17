<?php
	session_start();	
	include 'data.php';
	$from = getHtmlParameter( "from" );
	
	if ($from=="login") {
		if ($_SESSION["returnMSG"] == "Unknown username/password") {
			header("Location: master.php");
		} else {
			$microtimeID = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime());
			$builtURL = 'Location: reset.php?name=' . $_SESSION["name"] . '&password=' . $_SESSION["password"] . '&i=' . $microtimeID;
			header($builtURL);
		}				
	}
?>