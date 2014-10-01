<?php
$data = array();
$res=array();
$series1 = array();
$series2 = array();
$series3 = array();

/*
* Function to get the users ID from database
* @param $user - username from session
* @return $userid - ID of user
*/
function getUserId($user)
{
	$col = 0;
	//Get the userid from the user table
	$query = "SELECT * FROM users 
			WHERE `username` = '$user' ";
	$res = queryMysql($query);
	$userid = getField($res, $col);
	
	return $userid;
}

/*
* Function to get the users systen ID from database
* @param $userid - ID of user
* @return $userid - system ID
*/
function getSystemId($userid)
{
	$col = 0;
	//Get the systemid from the user table
	$query = "SELECT `systemid` FROM system 
			WHERE `userid` = '$userid'";
	$res = queryMysql($query);
	$systemid = getField($res, $col);
	return $systemid;
}


/*
* Function to get data from database and store for graphs
* @param $sql - sql statement
* @param $date - date 
* @param $color - color of chart
* @return $series - array of data
*/
function sumTotal($sql, $date, $color)
{
	$sqlQuery = queryMysql($sql);
	$row = sqlArray($sqlQuery);

	$series['name'] = $date;
	$series['color'] = $color;
	$series['data'][] = $row[0];
		
	return $series;
}

/*
* Function to get data labels from database and store for graphs
* @param $sql - sql statement
* @return $series - array of data labels
*/
function getTotals($sql)
{	 		
	$sql1 = queryMysql($sql);
	$row = sqlArray($sql1);

	$series['categories'][] = $row[0];
	
	return $series;
}

/*
* Function to get data labels from database and store for graphs
* @param $sql - sql statement
* @param $format - date format
* @return $series - array of data labels
*/
function getLabels($sql, $format)
{
	$querySql = queryMysql($sql);

	while ($row = sqlArray($querySql))
	{
		$row['date'] = date($format, strtotime($row['date']));
		$data['categories'][] = $row['date'];
	}
	return $data;
}

/*
* Function to get data from database and store for graphs
* @param $sql - sql statement
* @param $date - date 
* @param $color - color of chart
* @return $series - array of data
*/
function getData($sql, $name, $color)
{
	$querySql = queryMysql($sql);
	
	$series['name'] = $name;
	$series['color'] = $color;
	while ($r = sqlArray($querySql))
	{
		$series['data'][] =$r[$name];
	}	
	return $series;
}

/*
* Function to get data labels from database and store for graphs
* @param $sql - sql statement
* @return $series - array of data labels
*/
function getYearLabels($sql)
{
	$querySql = queryMysql($sql);

	while ($row = sqlArray($querySql))
	{
		$data['categories'][] = $row[0];
	}
		
	return $data;
}

/*
* Function to get data from database and store for graphs
* @param $sql - sql statement
* @param $date - date 
* @param $color - color of chart
* @return $series - array of data
*/
function getYearData($sql, $name, $color)
{
	$querySql = queryMysql($sql);
	
	$series['name'] = $name;
	$series['color'] = $color;
	while ($r = sqlArray($querySql))
	{
		$series['data'][] =$r[1];
	}	
	return $series;
}

/*
* Function to get data from database and store for graphs
* @param $sql - sql statement
* @param $fittariff - feed in tariff rate
* @param $date - date 
* @param $color - color of chart
* @return $series - array of data
*/
function getFit($sql, $fittariff, $name, $color)
{
	$querySql = queryMysql($sql);
	
	//depending on the name of the column
	//dictates the feed in tariff rates
	if ($name == 'pvgenerated')
	{
		$series['name'] = $name;
		$series['color'] = $color;
		while ($r = sqlArray($querySql)) 
		{
			$cal = $r[$name];
			$tariff = number_format(
						$cal * $fittariff,2,'.','');
			$series['data'][] = $tariff;
		}	
		
		return $series;
		
	} elseif ($name == 'pvexported') {
	
		$series['name'] = $name;
		$series['color'] = $color;
		
		while ($r = sqlArray($querySql)) 
		{
			$cal = $r[$name];
			$tariff = number_format
						($cal * $fittariff,2,'.','');
			$series['data'][] = $tariff;
		}	
		
		return $series;
		
	} elseif ($name == 'imported') {
	
		$series['name'] = $name;
		$series['color'] = $color;
		
		while ($r = sqlArray($querySql)) 
		{
			$cal = $r[$name];
			$tariff = number_format
						($cal * $fittariff,2,'.','');
			$series['data'][] = $tariff;
		}
		
		return $series;
	}
}

function getFitTotal($sql, $fittariff, $name, $color)
{
	$sqlQuery = queryMysql($sql);
	$r = sqlArray($sqlQuery);
	
	$series['name'] = $name;
	$series['color'] = $color;
	$cal = $r[0];
	$tariff =  number_format($cal * $fittariff,2,'.','');
	$series['data'][] = $tariff;
		
	return $series;
}

function getGenTariff($installDate, $systemsize, $epcband)
{
	$eligGenBeginDateOne = date('2012-08-01');
	$eligGenEndDateOne = date('2012-10-31');
	$eligGenBeginDateTwo = date('2012-11-01');
	$eligGenEndDateTwo = date('2013-06-30');
	$eligGenBeginDateThree = date('2012-08-01');
	$eligGenEndDateThree = date('2012-06-30');
		
	$maxSizeOne = 4000;
	$maxSizeTwo = 10000;
	$maxSizeThree = 500000;
		
	if ($installDate >= $eligGenBeginDateOne 
		&& $installDate <= $eligGenBeginDateOne
		&& $systemsize < $maxSizeOne 
		&& $epcband != 'e' 
		|| $epcband != 'f')
	{
		return 0.1600;
	}
	if ($installDate >= $eligGenBeginDateTwo 
		&& $installDate <= $eligGenBeginDateTwo
		&& $systemsize < $maxSizeOne 
		&& $epcband != 'e'
		|| $epcband != 'f')
	{
		return 0.1544;
	}
	if ($installDate >= $eligGenBeginDateOne 
	&& $installDate <= $eligGenBeginDateOne
		&& $systemsize < $maxSizeTwo 
		&& $systemsize > $maxSizeOne 
		&& $epcband != 'e'
		|| $epcband != 'f'
	) {
		return 0.1405;
	}
	if ($installDate >= $eligGenBeginDateTwo 
		&& $installDate <= $eligGenBeginDateTwo
		&& $systemsize < $maxSizeTwo 
		&& $systemsize > $maxSizeOne 
		&& $epcband != 'e' 
		|| $epcband != 'f'
	) {
		return 0.1399;
	}
	if ($installDate >= $eligGenBeginDateOne 
		&& $installDate <= $eligGenBeginDateOne
		&& $systemsize < $maxSizeThree 
		&& $systemsize > $maxSizeTwo 
		&& $epcband != 'e' 
		|| $epcband != 'f'
	) {
		return 0.1305;
	}
	if ($installDate >= $eligGenBeginDateTwo 
		&& $installDate <= $eligGenBeginDateTwo
		&& $systemsize < $maxSizeThree 
		&& $systemsize > $maxSizeTwo 
		&& $epcband != 'e' 
		|| $epcband != 'f'
	) {
		return 0.1303;
	}
	if ($installDate >= $eligGenBeginDateThree 
		&& $installDate <= $eligGenBeginDateThree
		&& $epcband == 'e' 
		|| $epcband == 'f'
	) {
		return 0.071;
	}
}

?>

