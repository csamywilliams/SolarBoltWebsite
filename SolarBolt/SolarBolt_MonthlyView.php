<?php // SolarBolt_MonthView.php
//include scripts to call
include 'SolarBolt_DesignHeader.php';
include 'SolarBolt_RetrieveGraphValues.php';
include 'SolarBolt_GetBarGraphView.php';
include 'SolarBolt_GetSplineGraphView.php';

$col = 0;
$user = getSessionUser();				//store the session
//get username from session
if($_GET['page']) 
{
	$pagenumber = $_GET['page'];
}
else
{
	$pagenumber = "";
}

$choice = getLinkAction($pagenumber);

//if the username is empty, redirect to index page
if($user == "")
{
	$details = "SolarBolt_Index.php";
	redirectPage($details);		
}
else 
{ 
	//if username exists, get the users ID from the username
	$userid = getUserId($user);
	//retrieve the system ID from the users ID
	$systemid = getSystemId($userid);
		
	$sql = getSql($systemid);
	//call function to query the database
	$res = getReadings($sql);
	
	if($res > 0){
		initGraphs($sql, $choice, $systemid);
	}
	else
	{
		echo "<div id = 'bannermonthly'></div><div id = 'content'>
			Error no data for this month </br>
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
*/
function initGraphs($sql, $choice, $systemid)
{
	//column names
	$colGen = 'pvgenerated';
	$colExp = 'pvexported';
	$colImp = 'imported';

	//format date
	$format = "d";
	//get data labels
	$data = getLabels($sql, $format);
		
	$resValues = array();

	if($choice == 'usage')
	{
		//query the database 
		$seriesGen = getData($sql, 'pvgenerated', '#9de24f');
		$seriesExp = getData($sql, 'pvexported', '#9aefda');
		$seriesImp = getData($sql, 'imported', '#f71e1e');
		
		//push results into an array
		array_push($resValues, $seriesGen);
		array_push($resValues, $seriesExp);
		array_push($resValues, $seriesImp);
	} elseif ($choice == 'consumption')
	{	
		//column names
		$colUsed = 'usedfrompvc';
		$colSaving = 'consumption';
		
		//query the database for data
		$sqlGen = getTotalUsed($systemid, $colGen);
		$sqlExp = getTotalUsed($systemid, $colExp);
		$sqlImp = getTotalUsed($systemid, $colImp);
		$sqlUsed = getTotalUsed($systemid, $colUsed);
		$sqlSave = getTotalUsed($systemid, $colSaving);
		
		//retrieve the values from the database
		$seriesGen = sumTotal($sqlGen, 
						'pvgenerated', '#9de24f');
		$seriesExp = sumTotal($sqlExp, 
						'pvexported', '#9aefda');
		$seriesImp = sumTotal($sqlImp, 
						'imported', '#f71e1e');
		$seriesUsed = sumTotal($sqlUsed, 
						'usedfrompvc', '#ffff66');
		$seriesSave = sumTotal($sqlSave, 
						'consumption', '#2aa835');
		
		//push the values into an array
		array_push($resValues, $seriesGen);
		array_push($resValues, $seriesExp);
		array_push($resValues, $seriesImp);
		array_push($resValues, $seriesUsed);
		array_push($resValues, $seriesSave);
	} elseif ($choice == 'fitcredit')
	{
		//calculate the Feed-in tariff rates
		$getGenTariff = calcGenTariff($systemid);
			
		$fitgen = $getGenTariff;
		$fitexp = 0.046;
		$fitimp = 0.13; 
			
		//query the database for relevant data
		$sqlGen = getTotalUsed($systemid, $colGen);
		$sqlExp = getTotalUsed($systemid, $colExp);
		$sqlImp = getTotalUsed($systemid, $colImp);
		
		//get the data from the database
		$seriesGen = getFitTotal($sqlGen, $fitgen, 
						$colGen, '#9de24f');
		$seriesExp = getFitTotal($sqlExp, $fitexp, 
						$colExp, '#9aefda');
		$seriesImp = getFitTotal($sqlImp, $fitimp,
						$colImp, '#f71e1e');
			
		//push the values into an array
		array_push($resValues, $seriesGen);
		array_push($resValues, $seriesExp);
		array_push($resValues, $seriesImp);
	}

	//encode the arrays into json format
	$resValues = json_encode($resValues, JSON_NUMERIC_CHECK);
	$result = json_encode($data, JSON_NUMERIC_CHECK);
	
	//format date labels and declare visualisation title
	$month = date('F');
	$link = 'Monthly';
		
	//depending on the link chosen by user, 
	//display different information on the visualisations
	if ($choice == "fitcredit")
	{
		//tooltip to represent unit
		$tooltip = 'GBP';
		//title of visualtions
		$title = ' Total FIT Credit';
		//call the function to display the graph
		getGraph($resValues, $result, $tooltip, 
			$title, $month, $link);
	} elseif ($choice == "usage")
	{
		//tooltip to represent unit
		$tooltip = 'kWh';
		//title of visualtions
		$title = ' Usage';
		//call the function to display the graph
		getSplineGraph($resValues, $result, $tooltip, 
			$title, $month, $link);
	} else {
		//tooltip to represent unit
		$tooltip = 'kWh';
		//title of visualtions
		$title = ' Consumption';
		//call the function to display the graph
		getGraph($resValues, $result, $tooltip, 
			$title, $month, $link);
	}
}

/*
* function to set a value depending on the link chosen by user
* @param $pageno - number of link
* @return $page - page value
*/
function getLinkAction($pageno)
{
	switch($pageno){
		case 1:
			$page = "usage";
			return $page;
			break;
		case 2:
			$page = "consumption";
			return $page;
			break;
			
		case 3:
			$page = "fitcredit";
			return $page;
			break;
		default: $page = "usage";
	}
}

/*
* function to pass in a SQL statement and check if the rows exist in the table
* @param $sql - SQL statement
* @return $res - result from SQL query
*/
function getReadings($sql)
{
	$res = checkRows($sql);
	return $res;
}

/*
* function to create an SQL statement between a date range
* @param $systemid - the ID of the system
* @return $sql - return the SQL statement
*/
function getSql($systemid)
{
	$sql = ("SELECT * FROM readings 
			WHERE `systemid` = '$systemid' 
			AND `date` >= DATE_FORMAT
			(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m-01') 
			AND `date` >= DATE_FORMAT(
			CURRENT_DATE, '%Y-%m-01') ORDER BY `date`"); 
	return $sql;
}

/*
* function to create an SQL statement
* @param $systemid - the ID of the system
* @param $col - columnname in database
* @return $sql - return the SQL statement
*/
function getTotalUsed($systemid, $col)
{
	$sql = ("SELECT SUM($col) FROM readings 
			WHERE `systemid` = '$systemid' 
			AND `date` >= DATE_FORMAT
			(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m-01') 
			AND `date` >= DATE_FORMAT
			(CURRENT_DATE, '%Y-%m-01') ORDER BY `date`"); 
	return $sql;
}

/*
* function to create an SQL statement
* @param $systemid - the ID of the system
* @param $param - columnname in database
* @return $sql - return the SQL statement
*/
function getSqlTotal($systemid, $param)
{
	$sql = ("SELECT SUM(`$param`) FROM readings 
			WHERE `systemid` = '$systemid' 
			AND `date` >= DATE_FORMAT
			(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m-01') 
			AND `date` >= DATE_FORMAT
			(CURRENT_DATE, '%Y-%m-01') ORDER BY `date`"); 
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
	
	if($res > 0)
	{	
		$systemsize = $res['installationdate'];
		$installDate = $res['epcband'];
		$epcband = $res['systemsize'];
		
		$gentariff = getGenTariff($installDate, 
						$systemsize, $epcband);
	}
	else
	{
		echo "System does not exist";
		$details = "SolarBolt_RegisterSystem.php";
		redirectPage($details);	
	}	

	return $gentariff;
}

?>

