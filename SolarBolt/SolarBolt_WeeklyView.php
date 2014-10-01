<?php // SolarBolt_WeeklyView.php
include 'SolarBolt_DesignHeader.php';
include 'SolarBolt_RetrieveGraphValues.php';
include 'SolarBolt_GetBarGraphView.php';
include 'SolarBolt_GetSplineGraphView.php';

$col = 0;
$user = getSessionUser();					//store the session

if ($_GET['page']) 
{
	$pagenumber = $_GET['page'];
} else {
	$pagenumber = "";
}

//get value for the link chosen
$choice = getLinkAction($pagenumber);
//if the username is empty, redirect to index page
if ($user == "")
{
	$details = 'SolarBolt_Index.php';
	redirectPage($details);		
} else { 
	//if username exists, get the users ID from the username
	$userid = getUserId($user);
	$systemid = getSystemId($userid);

	//get date range for start and end of week
	$beginWeek = date('y-m-d', strtotime('- 6 days'));
	$endWeek = date('y-m-d');
	
	//call function to query the database
	$sql = getSql($beginWeek, $endWeek, $systemid);
	$res = getReadings($sql);
	
	if ($res > 0)
	{
		//call a function to populate the graphs
		initGraphs($sql, $choice, $systemid, 
			$beginWeek, $endWeek);
	} else {
		//if results do not exist display an error message.
			echo "<div id = 'bannerweekly'></div><div id = 'content'>
			Error no data for this week </br>
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
* @param $beginWeek - the start date of week
* @param $endWeek - end week date
*/
function initGraphs($sql, $choice, $systemid, 
	$beginWeek, $endWeek
) {
	//column names
	$colGen = 'pvgenerated';
	$colExp = 'pvexported';
	$colImp = 'imported';
			
	$format = 'd-m-Y';
	
	//get data labels
	$data = getLabels($sql, $format);
		
	$resValues = array();

	//depending on the link chosen by user, get different data
	if ($choice == 'usage')
	{
		//query the database
		$series = getData($sql, 'pvgenerated', '#9de24f');
		$series1 = getData($sql, 'pvexported', '#9aefda');
		$series2 = getData($sql, 'imported', '#f71e1e');
		
		//push results into an array		
		array_push($resValues, $series);
		array_push($resValues, $series1);
		array_push($resValues, $series2);
			
	} elseif ($choice == 'consumption') {
		
		$colUsed = 'usedfrompvc';
		$colSaving = 'consumption';
		
		//query the database
		$sqlGen = getSqlTotal($beginWeek, $endWeek, 
					$systemid, $colGen);
		$sqlExp = getSqlTotal($beginWeek, $endWeek, 
					$systemid, $colExp);
		$sqlImp = getSqlTotal($beginWeek, $endWeek, 
					$systemid, $colImp);
		$sqlUsed = getSqlTotal($beginWeek, $endWeek, 
					$systemid, $colUsed);
		$sqlSave = getSqlTotal($beginWeek, $endWeek, 
					$systemid, $colSaving);
		
		//get data from database
		$seriesGen = sumTotal($sqlGen, 'pvgenerated', '#9de24f');
		$seriesExp = sumTotal($sqlExp, 'pvexported', '#9aefda');
		$seriesImp = sumTotal($sqlImp, 'imported', '#f71e1e');
		$seriesUsed = sumTotal($sqlUsed, 'usedfrompvc', '#ffff66');
		$seriesSave = sumTotal($sqlSave, 'consumption', '#2aa835');
			
		//push results into an array
		array_push($resValues, $seriesGen);
		array_push($resValues, $seriesExp);
		array_push($resValues, $seriesImp);
		array_push($resValues, $seriesUsed);
		array_push($resValues, $seriesSave);
		
	} elseif ($choice == "fitcredit") {
	
		//calculate the Feed-in tariff rates
		$getGenTariff = calcGenTariff($systemid);
			
		$fitgen = $getGenTariff;
		$fitexp = 0.046;
		$fitimp = 0.13; 
			
		$sqlGen = getSqlTotal($beginWeek, $endWeek, 
					$systemid, $colGen);
		$sqlExp = getSqlTotal($beginWeek, $endWeek, 
					$systemid, $colExp);
		$sqlImp = getSqlTotal($beginWeek, $endWeek, 
					$systemid, $colImp);

		//get data samples
		$series = getFitTotal($sqlGen, $fitgen, $colGen, '#9de24f');
		$series1 = getFitTotal($sqlExp, $fitexp, $colExp, '#9aefda');
		$series2 = getFitTotal($sqlImp, $fitimp,$colImp, '#f71e1e');
		
		//push results into an array		
		array_push($resValues, $series);
		array_push($resValues, $series1);
		array_push($resValues, $series2);
	}
	
	//encode the arrays into json format
	$resValues = json_encode($resValues, JSON_NUMERIC_CHECK);
	$result = json_encode($data, JSON_NUMERIC_CHECK);
	
	//visualisation titles
	$link = 'Weekly';
	$week = date($format, strtotime($beginWeek)) .' to ' 
			. date($format, strtotime($endWeek)) ;

	//depending on the link chosen by user, 
	//display different information on the visualisations
	if ($choice == 'fitcredit')
	{
		$tooltip = 'GBP';
		$title = ' Total FIT Credit';
		getGraph($resValues, $result, $tooltip, $title, 
			$week, $link);
			
	} elseif($choice == 'usage') {
			$tooltip = 'kWh';
			$title = ' Usage';
			//call the function to display the graph
			getSplineGraph($resValues, $result, $tooltip, 
				$title, $week, $link);
			
	} else {
			$tooltip = 'kWh';
			$title = ' Consumption';
			//call the function to display the graph
			getGraph($resValues, $result, $tooltip, 
				$title, $week, $link);
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
			$page = 'usage';
			return $page;
			break;
			
		case 2:
			$page = 'consumption';
			return $page;
			break;
		case 3:
			$page = 'fitcredit';
			return $page;
			break;
			
		default: $page = 'usage';
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
* function to create an SQL statement
* @param $startWeek - the start week date
* @param $endWeek - the end week date
* @param $systemid - the ID of the system
* @return $sql - return the SQL statement
*/
function getSql($beginWeek, $endWeek, $systemid)
{
	$sql = "SELECT * FROM readings 
		WHERE `systemid` = '$systemid' 
		AND `date` BETWEEN '$beginWeek' AND '$endWeek' 
		ORDER BY `date`";
	return $sql;
}

/*
* function to create an SQL statement
* @param $systemid - the ID of the system
* @param $col - columnname in database
* @param $startWeek - date 
* @param $endWeek - date
* @return $sql - return the SQL statement
*/
function getSqlTotal($beginWeek, $endWeek, $systemid, $col)
{
	$sql = "SELECT SUM($col) FROM readings 
		WHERE `systemid` = '$systemid' 
		AND `date` BETWEEN '$beginWeek' AND '$endWeek' 
		ORDER BY `date`";
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
	
	if ($res > 0)
	{	
		$systemsize = $res['installationdate'];
		$installDate = $res['epcband'];
		$epcband = $res['systemsize'];
		
		$gentariff = getGenTariff($installDate, 
						$systemsize, $epcband);
	} else {
		echo "System does not exist";
		$details = 'SolarBolt_RegisterSystem.php';
		redirectPage($details);	
	}	

	return $gentariff;
}
?>

