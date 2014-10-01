<?php // SolarBolt_YearlyView.php
include 'SolarBolt_DesignHeader.php';
include 'SolarBolt_RetrieveGraphValues.php';
include 'SolarBolt_GetBarGraphView.php';
include 'SolarBolt_YearViewGraph.php';

$col = 0;
//retrieve username from session
$user = getSessionUser();					

//get link number clicked
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
	//retrieve the system ID from the users ID
	$systemid = getSystemId($userid);
		
	//get the year from today's date
	$year = date('Y');	
	//create a SQL statement
	$sql = getSql($systemid, $year);
	//call function to query the database
	$res = getReadings($sql);
	
	//if results exist
	if($res > 0)
	{
		//call a function to populate the graphs
		initGraphs($sql, $choice, $systemid, $year);
	} else {
		//if results do not exist display an error message.
			echo "<div id = 'banneryearly'></div><div id = 'content'>
			Error no data for this year </br>
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
* @param $year - the current year
*/

function initGraphs($sql, $choice, $systemid, $year)
{
	//create a new array for the data 
	$resValues = array();
	//format for the data for labels
	$format = 'm';
	
	//column names in database
	$colGen = 'pvgenerated';
	$colExp = 'pvexported';
	$colImp = 'imported';
	$colUsed = 'usedfrompvc';
	$colSaving = 'consumption';

	//depending on the link chosen by user, get different data
	if ($choice == 'compare')
	{
		//to compare the data from previous years
		//create a variable to get last year value
		$prevYear = date('Y', strtotime('-1 year'));
		
		//query the database and retrieve the results
		$getData = compareYear($systemid, $year, $prevYear);
		$res = getReadings($getData);
		
		//if the rows exist
		if($res > 0)
		{
			//retrieve the labels for the visualisation
			$data = getTotals($getData);
			
			$colConsume = 'consumption';
		
			//query the database for the total amount consumed for 
			//this year and previous year
			$sqlYear = getTotalUsed($systemid, $colConsume, $year);
			$sqlPrev = previousYear($systemid, $colConsume, $prevYear);
		
			//get the data and store array in a variable
			$seriesThisYear = sumTotal($sqlYear, $year, '#9de24f');
			$seriesLastYear = sumTotal($sqlPrev, $prevYear, '#9aefda');
			
			//push both into a single array
			array_push($resValues, $seriesLastYear);
			array_push($resValues, $seriesThisYear);
		}
		
	} else if ($choice == 'usage') {
		//database column name
		$colUse = 'usedfrompvc';
			
		//query the database 
		$sqlGen = showUsage($systemid, $year, $colGen);
		$sqlExp = showUsage($systemid, $year, $colExp);
		$sqlImp = showUsage($systemid, $year, $colImp);
		$sqlUse = showUsage($systemid, $year, $colUsed);

		//get data labels
		$data = getYearLabels($sqlGen);

		//collect the information from the database
		$series = getYearData($sqlGen, $colGen, '#9de24f');
		$series1 = getYearData($sqlExp, $colExp, '#9aefda');
		$series2 = getYearData($sqlImp, $colImp, '#f71e1e');
		$series3 = getYearData($sqlUse, $colUsed, '#a71444');
				
		//push results into an array
		array_push($resValues, $series);
		array_push($resValues, $series1);
		array_push($resValues, $series2);
		array_push($resValues, $series3);

	} elseif ($choice == 'consumption') {
	
		//get data labels
		$data = getLabels($sql, $format, $year);
		
		//query the database for data
		$sqlGen = getTotalUsed($systemid, $colGen, $year);
		$sqlExp = getTotalUsed($systemid, $colExp, $year);
		$sqlImp = getTotalUsed($systemid, $colImp, $year);
		$sqlUsed = getTotalUsed($systemid, $colUsed, $year);
		$sqlSave = getTotalUsed($systemid, $colSaving, $year);
			
		//retrieve the values from the database
		$seriesGen = sumTotal($sqlGen, 'pvgenerated', '#9de24f');
		$seriesExp = sumTotal($sqlExp, 'pvexported', '#9aefda');
		$seriesImp = sumTotal($sqlImp, 'imported', '#f71e1e');
		$seriesUsed = sumTotal($sqlUsed, 'usedfrompvc', '#ffff66');
		$seriesSave = sumTotal($sqlSave, 'consumption', '#2aa835');
			
		//push the data into an array
		array_push($resValues, $seriesGen);
		array_push($resValues, $seriesExp);
		array_push($resValues, $seriesImp);
		array_push($resValues, $seriesUsed);
		array_push($resValues, $seriesSave);
		
	} elseif ($choice == 'fitcredit') {	
	
		//get the labels
		$data = getLabels($sql, $format, $year);
		
		//calculate the Feed-in tariff rates
		$getGenTariff = calcGenTariff($systemid);			
		$fitgen = $getGenTariff;
		$fitexp = 0.046;
		$fitimp = 0.13; 
						
		//query the database for relevant data
		$sqlGen = getTotalUsed($systemid, $colGen, $year);
		$sqlExp = getTotalUsed($systemid, $colExp, $year);
		$sqlImp = getTotalUsed($systemid, $colImp, $year);
			
		//get the data from the database
		$series = getFitTotal($sqlGen, $fitgen, $colGen, '#9de24f');
		$series1 = getFitTotal($sqlExp, $fitexp, $colExp, '#9aefda');
		$series2 = getFitTotal($sqlImp, $fitimp,$colImp, '#f71e1e');
			
		//push the values into an array
		array_push($resValues, $series);
		array_push($resValues, $series1);
		array_push($resValues, $series2);
	}
	
	//encode the arrays into json format
	$resValues = json_encode($resValues, JSON_NUMERIC_CHECK);
	$result = json_encode($data, JSON_NUMERIC_CHECK);
		
	$year = date('Y');
	//title of view
	$link = 'Yearly';
	//variable to render the visualisation to
	$renderTo = 'container';
	
	//depending on the link chosen by user, 
	//display different information on the visualisations
	if ($choice == 'fitcredit')
	{
		//tooltip to represent unit
		$tooltip = 'GBP';
		//title of visualtions
		$title = ' Total FIT Credit';
		//call the function to display the graph
		getYearView($resValues, $result, $tooltip, 
			$title, $year, $link, $renderTo);
			
	} elseif ($choice == 'usage') {
		//tooltip to represent unit
		$tooltip = 'kWh';
		//title of visualtions
		$title = ' Usage';
		//call the function to display the graph
		getYearView($resValues, $result, $tooltip, 
			$title, $year, $link, $renderTo);
	
	} elseif ($choice == 'compare') {
		$tooltip = 'kWh';
		$title = ' Comparing Yearly Amount Consumed';
		$year = "";	
		//call the function to display the graph
		getYearView($resValues, $result, $tooltip, 
			$title, $year, $link, $renderTo);
			
	} else {
		//tooltip to represent unit
		$tooltip = 'kWh';
		//title of visualtions
		$title = ' Consumption';
		//call the function to display the graph
		getYearView($resValues, $result, $tooltip, 
			$title, $year, $link, $renderTo);
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
		
		//if page number is equal to 4		
		case 4:
			//set the value to compare
			$page = 'compare';
			return $page;
			break;
			
		default: $page = 'usedfrompvc';
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
* @param $systemid - the ID of the system
* @param $year - the year
* @return $sql - return the SQL statement
*/
function getSql($systemid, $year)
{
	$sql = ("SELECT * FROM readings 
			WHERE `systemid` = '$systemid' 
			AND YEAR(`date`) = '$year'"); 
	return $sql;
}

/*
* function to create an SQL statement
* @param $systemid - the ID of the system
* @param $col - columnname in database
* @param $date - date 
* @return $sql - return the SQL statement
*/
function getTotalUsed($systemid, $col, $year)
{
	$sql = ("SELECT SUM($col) FROM readings 
			WHERE `systemid` = '$systemid' 
			AND YEAR(`date`) = '$year'"); 
	return $sql;
}

/*
* function to create an SQL statement to get data
* between a specific date range
* @param $systemid - the ID of the system
* @param $thisYear - current year
* @param $prevYear - previous year
* @return $sql - return the SQL statement
*/
function compareYear($ystemid, $thisYear, $prevYear)
{
	$sql = ("SELECT YEAR(`date`) FROM readings 
	WHERE YEAR(`date`) >= '$thisYear' 
	AND YEAR(`date`) >= '$prevYear'"); 
	
	return $sql;
}

/*
* function to create an SQL statement by adding together specific
* values based on the column
* @param $systemid - the ID of the system
* @param $col - column name
* @param $prevYear - previous year
* @return $sql - return the SQL statement
*/
function previousYear($systemid, $col, $prevYear)
{
	$sql = ("SELECT SUM($col) FROM readings 
			WHERE `systemid` = '$systemid' 
			AND YEAR(`date`) = '$prevYear'"); 
	return $sql;
}

/*
* function to create an SQL statement, 
* between a date range, grouped by the year
* @param $systemid - the ID of the system
* @param $year - the year
* @param $col - column name
* @return $sql - return the SQL statement
*/
function showUsage($systemid, $year, $col)
{
	$sql = ("SELECT DATE_FORMAT(`date`, '%m-%Y'), SUM($col) FROM readings 
			WHERE `systemid` = '$systemid' 
			AND YEAR(`date`) = '$year'
			GROUP BY MONTH(`date`), YEAR(`date`)");
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
	$sql = "SELECT `installationdate`, `epcband`, 
			`systemsize` FROM `system` 
			WHERE `systemid` = '$systemid'";
	$res = checkRows($sql);
	
	// if information exists, set the $gentariff 
	// to the feed in tariff rate
	// else redirect the page.
	if ($res > 0)
	{	
		$systemsize = $res['installationdate'];
		$installDate = $res['epcband'];
		$epcband = $res['systemsize'];
		
		$gentariff = getGenTariff($installDate, $systemsize, $epcband);
	} else {
		echo 'System does not exist';
		$details = "SolarBolt_RegisterSystem.php";
		redirectPage($details);	
	}	
	return $gentariff;
}
?>

