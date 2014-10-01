<?php // SolarBolt_ReadingInput.php
include 'SolarBolt_DesignHeader.php';
include 'SolarBolt_MissingDays.php';

$genName = 'generatedinput';
$expName = 'exportedinput';
$impName = 'importedinput';

$col = 0;
$user = getSessionUser();

$error = array();
//check if username is empty, redirect page
if (empty($user))
{
	$details = "SolarBolt_Index.php";
	redirectPage($details);
}

//retrieve values from form fields
if (isset($_POST['register'])) 
{
	//sanitize the user input
	$pvgen = sanitizeCheck($_POST['pvgenerated']);			//sanitize the string
	$pvexp = sanitizeCheck($_POST['pvexported']);			//post method used to collect values from form
	$pvimp  = sanitizeCheck($_POST['imported']);

	//get the user and system id
	$user = getSessionUser();
	$systemId = getSystemId($user);

	//check if all fields are empty
	if (empty($pvgen) && empty($pvexp) && empty($pvimp))
	{
		$error[] = "You have empty fields";
	} else {
		if($systemId == 0)
		{
			//redirect page
			$details = "SolarBolt_RegisterSystem.php";
			redirectPage($details);
		}
		validateInput($systemId, $pvgen, $pvexp, $pvimp);
	}
}

function getSystemId($username)
{
	global $col;
	
	//get system ID from username
	$query = "SELECT * FROM users 
				WHERE `username` = '$username' ";
	$res = queryMysql($query);
	$userid = getField($res, $col);
	
	$query = "SELECT `systemId` FROM system 
				WHERE `userid` = '$userid'";
	$res = queryMysql($query);
	$systemId = getField($res, $col);

	return $systemId;
}

function validateInput($systemId, $pvgen, $pvexp, $pvimp)
{
	$code = 1;
	
	/*
	* Check what fields are empty and if all values are digits.
	* In case of errors, call error message function
	* otherwise move to see if data exists
	*/
	if (!empty($pvgen) && !empty($pvexp) && empty($pvimp))
	{
		if (ctype_digit($pvgen) && ctype_digit($pvexp))
		{
			$pvimp = 0;
			$missingField = 'imported';
			checkIfDataExists($systemId, $pvgen, $pvexp, 
				$pvimp, $missingField);
		} else {
			$error[] = "The generated input value or 
						the exported input values 
						is not an integer";
			displayError($error, $code); 
			
		}
	}
	
	if (!empty($pvgen) && empty($pvexp) && !empty($pvimp))
	{
		if (ctype_digit($pvgen) && ctype_digit($pvimp))
		{
			$pvexp = 0;
			$missingField = 'exported';
			checkIfDataExists($systemId, $pvgen, $pvexp, 
				$pvimp, $missingField);
		} else {
			$error[] = "The generated input or 
						imported input is not an integer";
			displayError($error, $code); 
		}
	}
	
	if (!empty($pvgen) && empty($pvexp) && empty($pvimp))
	{
		if (ctype_digit($pvgen))
		{
			$pvimp = 0;
			$pvexp = 0;
			$missingField = 'both';
			checkIfDataExists($systemId, $pvgen, $pvexp, $pvimp, 
				$missingField);
		} else {
			$error[] = "The generated input is not an integer";
			displayError($error, $code); 
		}
	}
	
	if (!empty($pvgen) && !empty($pvexp) && !empty($pvimp))
	{
		if (ctype_digit($pvgen) || ctype_digit($pvexp) || ctype_digit($pvimp))
		{
			$missingField = 'none';
			checkIfDataExists($systemId, $pvgen, $pvexp, 
				$pvimp, $missingField);
		} else {
			$error[] = "Reading must be an integer";
			displayError($error, $code); 
		}
	}

	if(empty($pvgen))
	{
		$error[] = "Generation reading required";
	}
}

function checkIfDataExists($systemId, $pvgen, $pvexp, $pvimp, $missingField)
{	
	global $col;
	$date = date('Y-m-d');			// get today's date	
	
	$query = "SELECT * FROM readings 
				WHERE `systemid` = '$systemId'"; //check if date exists
	$res = checkRows($query);
	
	if ($res == 0)
	{
		$unitsGen = $unitsExp = $unitsImp = 0;
		if (empty($pvgen)
		   || empty($pvexp)
		   || empty($pvimp)
		) {
			$error[] = "First system reading 
							requires all fields to be entered";
		} else {
			insertData($systemId, $pvgen, $pvexp, $pvimp, 
				$unitsGen, $unitsExp, $unitsImp);	
		}
	}

	$query = "SELECT * FROM readings 
				WHERE `systemId` = '$systemId' 
				AND `date` = '$date'"; //check if date exists
	$res = checkRows($query);
	
	if ($res > 0)
	{
		$code = 1;
		$error[] = "Data exists for today's date";
		displayError($error, $code); 
	} else {	
		getLastInsertedValues($systemId, $pvgen, $pvexp, $pvimp, $missingField);
	}	
}

function getLastInsertedValues($systemId, $inputGen, 
	$inputExp, $inputImp, $missingField
) {
	global $genName, $expName, $impName, $col;

	//get maximum date for specific column
	$genMaxValue = getMaxValue($systemId, $genName);
	$expMaxValue = getMaxValue($systemId, $expName);
	$impMaxValue = getMaxValue($systemId, $impName);
	
	$yesterday = date("Y-m-d", strtotime("yesterday"));
		
	//if the maximum date is yesterdays date, simply take the values
	//away otherwise requires working out the missing values
	if ($genMaxValue == $yesterday 
		&& $expMaxValue == $yesterday 
		&& $impMaxValue == $yesterday
	) {
		if ($missingField == 'none')
		{
			//retrieve last inserted data
			$generated = getLastDataInsert($genName, $yesterday,
						$systemId);
			$exported = getLastDataInsert($expName, $yesterday, 
						$systemId);
			$imported = getLastDataInsert($impName,$yesterday, 
						$systemId);
												
			if($generated > $inputGen 
				|| $exported > $inputExp 
				|| $imported > $inputImp
			) {
				$code = 1;
				$error[] = "The readings are less 
					than your previous ones";
					displayError($error, $code);
			}
			//calculate units			
			$gen = $inputGen - $generated;
			$exp = $inputExp - $exported;
			$imp = $inputImp - $imported;
			
			updateMissingData($systemId, $inputGen, $inputExp, $inputImp);
			
			insertData($systemId, $inputGen, $inputExp, $inputImp, 
				$gen, $exp, $imp);	
	
		} else {
			setMissingValues($systemId, $inputGen, 
				$inputExp, $inputImp, $missingField, $yesterday);
		}
	} else {
		calculateMissingFields($systemId, $inputGen, $inputExp, 
				$inputImp, $missingField); 
	}
}

function setMissingValues($systemId, $inputGen, 
	$inputExp, $inputImp, $missingField, $yesterdayDate
) { 

	global $genName, $expName, $impName, $col;
	
	if($missingField == 'both')
	{
		//retrieve last inserted data
		$generated = getLastDataInsert($genName, $yesterdayDate,
						$systemId);
						
		$gen = $inputGen - $generated;
		$exp = $imp = 0;
		
		//insert the data readings
		insertData($systemId, $inputGen, $inputExp, 
			$inputImp, $gen, $exp, $imp);
	
	} elseif ($missingField == 'exported')
	{
		//retrieve last inserted data
		$generated = getLastDataInsert($genName, $yesterdayDate,
					$systemId);
		$imported = getLastDataInsert($impName,$yesterdayDate, 
					$systemId);
						
		$gen = $inputGen - $generated;
		$imp = $inputImp - $imported;
		$exp = 0;
		
		//insert the data readings
		insertData($systemId, $inputGen, $inputExp, 
			$inputImp, $gen, $exp, $imp);
		
	} elseif ($missingField == 'imported')
	{
		//retrieve last inserted data
		$generated = getLastDataInsert($genName, $yesterdayDate,
					$systemId);

		$exported = getLastDataInsert($expName, $yesterdayDate, 
					$systemId);
						
		$gen = $inputGen - $generated;
		$exp = $inputExp - $exported;
		
		$imp = 0;
		
		//insert the data readings
		insertData($systemId, $inputGen, $inputExp, 
			$inputImp, $gen, $exp, $imp);
	}
}


function calculateMissingFields($systemId, $inputGen, $inputExp,
	$inputImp, $missingField
) {
	global $genName, $expName, $impName, $col;
	
	//get the maximum value in database for a specific column
	$genMaxValue = getMaxValue($systemId, $genName);
	$expMaxValue = getMaxValue($systemId, $expName);
	$impMaxValue = getMaxValue($systemId, $impName);
	
	//get the last data insert
	$generated = getLastDataInsert($genName, $genMaxValue, 
					$systemId);
	$exported = getLastDataInsert($expName, $expMaxValue, 
					$systemId);
	$imported = getLastDataInsert($impName, $impMaxValue, 
					$systemId);

	if($missingField == 'none')
	{	
		//calculate the difference in days
		$diffDaysGen = getDifferenceDays($genMaxValue);
			
		$code =2;
		//check if previous reading isnt greater than the inputed reading
		if (($generated > $inputGen) 
			&& ($exported > $inputExp) 
			&& ($imported > $inputImp)
		) {
			//store error message and display
			$error[] = "The generated, imported or exported 
						value is less than the previous 
						value in table";
			displayError($error, $code);
		} else {

			missingDays($systemId, $inputGen, $inputExp, $inputImp, 
				$diffDaysGen, $genMaxValue, 
				$generated, $exported, $imported);
		}
	}

	if ($missingField == 'exported') 
	{		
		//calculate the difference in days
		$diffDaysGen = getDifferenceDays($genMaxValue);
			
		$code =2;
		//check if previous reading isnt greater than the inputed reading
		if (($generated > $inputGen) 
			&& ($imported > $inputImp)
		) {
			//store error message and display
			$error[] = "The generated or imported 
						value is less than the previous 
						value in table";
			displayError($error, $code);
		} else {
			missingExportDays($systemId, $inputGen, $inputExp, $inputImp, 
				$diffDaysGen, $genMaxValue, 
				$generated, $exported, $imported);
		}
	}
	
	if ($missingField == 'imported') 
	{	
		//calculate the difference in days
		$diffDaysGen = getDifferenceDays($genMaxValue);
			
		$code =2;
		//check if previous reading isnt greater than the inputed reading
		if (($generated > $inputGen) 
			&& ($exported > $inputExp) 
		) {
			//store error message and display
			$error[] = "The generated or exported 
						value is less than the previous 
						value in table";
			displayError($error, $code);
		} else {
			missingImportDays($systemId, $inputGen, $inputExp, $inputImp, 
				$diffDaysGen, $genMaxValue, 
				$generated, $exported, $imported);
		}
	}
	
	//if missing fields are both exported and imported
	if ($missingField == 'both') 
	{		
		//calculate the difference in days
		$diffDaysGen = getDifferenceDays($genMaxValue);
			
		$code =2;
		//check if previous reading isnt greater than the inputed reading
		if (($generated > $inputGen) 
		) {
			//store error message and display
			$error[] = "The generated 
						value is less than the previous 
						value in table";
			displayError($error, $code);
		} else {
			missingImpAndExpDays($systemId, $inputGen, $inputExp, $inputImp, 
				$diffDaysGen, $genMaxValue, 
				$generated, $exported, $imported);
		}
	}
}

function insertMissingData($systemid, $date, $genInput, 
	$expInput, $impInput, $unitsGen, $unitsExp,  
	$unitsImp, $used, $total
) {
	//insert missing readings
	$insert = "INSERT INTO readings 
			(systemid, date, pvgenerated, generatedinput, 
			pvexported, exportedinput, imported, 
			importedinput, usedfrompvc,consumption) VALUES".
			"('$systemid','$date','$unitsGen', '$genInput', 
			'$unitsExp','$expInput' ,'$unitsImp','$impInput',
			'$used','$total')"; 

	$result = queryMysql($insert);
	sqlCheck($result);
}

function insertData($systemid, $gen, $exp, $imp,
	$unitsGen, $unitsExp, $unitsImp
) {	
	//calculate usage and consumption
	$date = date('Y-m-d');			// get today's date	
	$usedfrompvc = $unitsGen - $unitsExp;
	$consumption = $unitsImp + $usedfrompvc;

	//insert data into readings
	$insert = "INSERT INTO readings 
			(systemid, date, pvgenerated, generatedinput, 
			pvexported, exportedinput, imported, 
			importedinput, usedfrompvc,consumption) VALUES" .
			"('$systemid','$date','$unitsGen', '$gen', 
			'$unitsExp','$exp' ,'$unitsImp','$imp',
			'$usedfrompvc','$consumption')";
	
	$result = queryMysql($insert);
	sqlCheck($result);
		
	//redirect page
	$details = "SolarBolt_Index.php";
	redirectPage($details);					
}

//get the amount of days different between todays date
//and a previous date
function getDifferenceDays($previousDate)
{
	$date = date('Y-m-d');			// get today's date	

	$dateDiff = abs((strtotime($date)) - (strtotime($previousDate)));
	$diffDays = $dateDiff/86400;  // 86400 seconds in one day
	$diffDays = intval($diffDays);
	
	return $diffDays;
}

//get the maximum value in column and return the date
function getMaxValue($systemId, $colName)
{
	$colNumber = 2;
	$query = "SELECT * FROM readings 
			WHERE `systemId` = '$systemId' 
			AND $colName = 
			(SELECT MAX($colName) 
			FROM readings
			WHERE `systemId` = '$systemId')";
			
	$res = queryMysql($query);
	$previousDate = getField($res, $colNumber);

	//return the date
	return $previousDate;
}

//get the last insert data values
function getLastDataInsert($name, $prevdate, $system)
{	
	global $col;
			
	$sql = "SELECT `$name` FROM readings 
			WHERE `date` = '$prevdate' 
			AND `systemId` = '$system'";
			
	$res = queryMysql($sql);	
	$date = getField($res, $col);
		
	return $date;
}

//display error messages
function displayError($error, $code)
{
	// display error messages in a list
	if (empty($error) == false)
	{
		echo "<div id = 'bannerreadings'></div><div id = 'content'><ui>";
		foreach($error as $fail)
		{
			echo '<li>', $fail ,'</li>';
		}
			echo "</ui>";
		//include this link if code = 2
		if($code == 2)
		{
			echo "<a class='link' href='SolarBolt_ReadingInput.php'> 
			Click here to enter a new reading</a>
			</div>";
		}
	}
	
	die();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type ="text/javascript" src="val.js"></script>
</head>
<body>
<div id = 'bannerreadings'></div>
<div id = "content">

	<?php
		if(empty($error) == false)
		{
			echo '<ui>';
			foreach($error as $fail)
			{
				echo '<li>', $fail ,'</li>';
			}
			echo '</ui>';
		}
	?>

	<form name = "SolarBolt_ReadingInput" form action="SolarBolt_ReadingInput.php" method="post" >
<div id = "styleform" class="theform"><pre>
<div id = "date" style="color:#FF0000"></div>							
<label>Electricity generated (kWh)</label>
<input type="text" size="6" name="pvgenerated" onChange = "validateInt(this,'pvgen');"/>
<div id = "pvgen" style="color:#FF0000"></div>	
<label>Electricity exported (kWh) </label>			
<input type="text" size="6" name="pvexported" onChange = "validateInt(this,'pvexp');"/>
<div id = "pvexp" style="color:#FF0000"></div>
<label>Electricity imported (kWh)</label>			
<input type="text" size="6" name="imported" onChange = "validateInt(this,'pvimp');"/>
<div id = "pvimp" style="color:#FF0000"></div>
<input type="submit" name="register" value="Register" />
</pre>
</form>
</body>
</div>
</html>
