<?php
session_start();
$optionPurchased = $_GET['optionPurchased'];
$_SESSION[$optionPurchased] = "true";
if (isset($_GET['adBudget'])) {
	$_SESSION["adBudget"] = $_GET['adBudget'];
}
//} else {
	//$_SESSION["adBudget"] = 0;
//}
//echo 'ad budget is ' . $_SESSION["adBudget"]; 
echo '<input type="button" class="btn btn-success btn-sm disabled" value="Purchased">';
?>
