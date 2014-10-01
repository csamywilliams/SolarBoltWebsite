<?php // SolarBolt_DailyView.php
//include scripts to call
include 'SolarBolt_DesignHeader.php';
include 'SolarBolt_RetrieveGraphValues.php';
include 'SolarBolt_GetBarGraphView.php';

$col = 0;
//get username from session
$user = getSessionUser();		

//get link number clicked
if($_GET['page'])
{
	$pagenumber = $_GET['page'];
} else {
	$pagenumber = "";
}

//get value for the link chosen
$choice = getLinkAction($pagenumber);

//if the username is empty, redirect to index page
if($user == "")
{
	$details = "SolarBolt_Index.php";
	redirectPage($details);		
} else { 
	//if username exists, get the users ID from the username
	$userid = getUserId($user);
	//retrieve the system ID from the users ID
	$systemid = getSystemId($userid);
	//get current date
	$date = date("Y-m-d");
	
	$sql = getSql($date, $systemid);
	//call function to query the database
	$res = getReadings($sql);
	
	//if results exist
	if($res > 0)
	{
		//call a function to populate the graphs
		initGraphs($sql, $choice, $systemid, $date);
	} else {
		//if results do not exist display an error message.
		echo "<div id = 'bannerdaily'></div><div id = 'content'>
			Error no data for today's date </br>
			<a class='link' href='SolarBolt_ReadingInput.php'> 
			Click here to enter a new reading</a>
			</div>";
	}
}

/*
*initGraphs function to get the data based up on the link chosen
* @param $sql - SQL statement to query the database
* @param $choice - the link chosen by user
* @param $systemid - ID of the system
* @param $date - the current day
*/
function initGraphs($sql, $choice, $systemid, $date)
 {
	//formats the date 
	$format = 'd-m-Y';
	//gets the data for the labels
	$data = getLabels($sql, $format);
	//create a new array for the data
	$resValues = array();

	//depending on the link chosen by user, get different data
	if($choice == 'usage')
	{
		//get the data and store array in a variable
		$series = getData($sql, 'pvgenerated', '#9de24f');
		$series1 = getData($sql, 'pvexported', '#9aefda');
		$series2 = getData($sql, 'imported', '#f71e1e');
			
		//push all into a single array
		array_push($resValues, $series);
		array_push($resValues, $series1);
		array_push($resValues, $series2);
		
	} elseif ($choice == 'consumption') {	
		//get the data and store array in a variable
		$series = getData($sql, 'usedfrompvc', '#9de24f');
		$series1 = getData($sql, 'consumption', '#9aefda');
		
		//push all into a single array
		array_push($resValues, $series);
		array_push($resValues, $series1);
		
	} elseif ($choice == 'fitcredit') {
		//change SQL statements
		$sql = getSql($date, $systemid);
		//get the labels
		$res = getReadings($sql);
			
		//calculate the Feed-in tariff rates
		$getGenTariff = calcGenTariff($systemid);
		$fitgen = $getGenTariff;
		$fitexp = 0.046;
		$fitimp = 0.13; 

		//get the data from the database
		$series = getFit($sql, $fitgen, 'pvgenerated', '#9de24f');
		$series1 = getFit($sql, $fitexp, 'pvexported', '#9aefda');
		$series2 = getFit($sql, $fitimp, 'imported', '#f71e1e');
			
		//push the values into an array
		array_push($resValues, $series);
		array_push($resValues, $series1);
		array_push($resValues, $series2);
		
	}
	
	//encode the arrays into json format
	$resValues = json_encode($resValues, JSON_NUMERIC_CHECK);
	$result = json_encode($data, JSON_NUMERIC_CHECK);

	//format date and set link title
	$day = date('l'); 
	$link = 'Daily';
		
	//depending on the link chosen by user, 
	//display different information on the visualisations
	if($choice == 'fitcredit')
	{
		//tooltip to represent unit
		$tooltip = 'GBP';
		//title of visualtions
		$title = ' FIT Credit';
		
		getGraph($resValues, $result, $tooltip, 
			$title, $day, $link);
			
	} elseif($choice == 'usage') {
		//tooltip to represent unit
		$tooltip = 'kWh';
		//title of visualtions
		$title = ' Usage';
		//call the function to display the graph
		getGraph($resValues, $result, $tooltip, 
			$title, $day, $link);
			
	} else {
		//tooltip to represent unit
		$tooltip = 'kWh';
		$title = ' Consumption';
		//call the function to display the graph
		getGraph($resValues, $result, $tooltip, 
			$title, $day, $link);
	}
}

/*
* function to set a value depending on the link chosen by user
* @param $pageno - number of link
* @return $page - page value
*/
function getLinkAction($pageno)
{
	switch ($pageno) {
		//if page number is equal to 1
		case 1:
			//set the value to usage
			$page = 'usage';
			return $page;
			break;
			
		//if page number is equal to 2
		case 2:
			//set the value to consumption
			$page = 'consumption';
			return $page;
			break;
		
		//if page number is equal to 3
		case 3:
			//set the value to fitcredit
			$page = 'fitcredit';
			return $page;
			break;
		
		//set default page to usage
		default: 
			$page = 'usage';
	}
}

/*
* function to pass in a SQL statement and check if the rows exist in the table
* @param $sql - SQL statement
* @return $res - result
*/
function getReadings($sql)
{
	$res = checkRows($sql);
	return $res;
}

/*
* function to pass in a SQL statement and check if the rows exist in the table
* @param $date - current date
* @param $systemid - system id
* @return $sql - SQL statement
*/
function getSql($date, $systemid)
{
	$sql = "SELECT * FROM `readings` WHERE `date` = '$date' 
			AND `systemid` = '$systemid'";
	return $sql;
}

/*
* function to retrieve the feed-in tariff rate depending on the 
* user's system information in the 'system' table.
* @param $systemid - the ID of the system
* @return $gentariff - value for the generation tariff
*/
function calcGenTariff($systemid)
{
	$sql = "SELECT `installationdate`, `epcband`, `systemsize` 
			FROM `system` WHERE `systemid` = '$systemid'";
	$res = checkRows($sql);
	
	// if information exists, set the $gentariff 
	// to the feed in tariff rate
	// else redirect the page.
	
	if($res > 0
	) {	
		$systemsize = $res['installationdate'];
		$installDate = $res['epcband'];
		$epcband = $res['systemsize'];
		$gentariff = getGenTariff($installDate, $systemsize, 
								$epcband);
	} else {
		echo "System does not exist";
		$details = "SolarBolt_RegisterSystem.php";
		redirectPage($details);	
	}	
	return $gentariff;
}

?>

