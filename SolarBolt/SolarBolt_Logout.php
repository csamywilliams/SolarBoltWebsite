<?php //SolarBolt_Logout.php
include 'SolarBolt_DesignHeader.php';

//if username exists in the session variable
if (isset($_SESSION['username']))
{
	//call the cookieSessionDestroyed to destroy
	//the session
	cookieSessionDestroyed();
	
echo <<<_END
	<div id=content>
	<h2>You have now logged out</h2>
	</div>
_END;
	
	//redirect page
	$details = "SolarBolt_Index.php";
	redirectPage($details);									//redirect the page to another php page
} else {
		echo "<div id=content><h2>
		You are not logged in </h2></div>";
}


?>