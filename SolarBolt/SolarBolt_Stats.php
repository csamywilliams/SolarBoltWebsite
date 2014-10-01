<?php //SolarBolt_Stats.php
include 'SolarBolt_DesignHeader.php';

//gets the user id calls the function from the function check script
$user = getSessionUser();		

//if the username variable is empty, the session is not stored
if($user == "")
{
	//if true, redirect user to index page.
	$details = 'SolarBolt_Index.php';
	redirectPage($details);	
} else { 
	//query the database to select user with the equivalent username
	$query = "SELECT * FROM users WHERE `username` = '$user'";
	$res = queryMysql($query);
	
	//for all the rows
	while($row = sqlArray($res))
	{
		//get the ID and store in userid variable
		$userid = $row[0];
	}
	
	//select the system that corresponds to the user
	$system = "SELECT * FROM system 
				WHERE `userid` = '$userid'";
	$result = checkRows($system);
	
	//if no system exists redirect to register a system
	if($result == 0)
	{
		$details = 'SolarBolt_RegisterSystem.php';
		redirectPage($details);	
	} else {
		//if system exists
		$res = queryMysql($system);
		while($row = sqlArray($res))
		{
			//get the name of system and store in variable
			$name = $row[2];
		}
		//convert the name to upper case characters
		$name = strtoupper($name);
	
		//show the links for the visualisations
		showStats($name);
	}
}

/*
	Function to show the visualisations
	@param $name - name of system
*/
function showStats($name)
{
echo <<<_END
	<html>
	<body>
	<div id = "bannerstats"></div>
	<div id = "content">

		<h3> $name	Statistics	</h3> </br>
		
		<p>Please click on the link you would like to view... </p>
		<a class='content' href='SolarBolt_DailyView.php?page=1'>Daily View</a></br>
		<a class = 'content' href='SolarBolt_WeeklyView.php?page=1''>Weekly View</a></br>
		<a class = 'content' href='SolarBolt_MonthlyView.php?page=1'>Monthly View</a></br>
		<a class='content' href='SolarBolt_YearlyView.php?page=1'>Year View</a></br>

	</p>
	</div>
	<body>
	</html>
_END;
}
?>

