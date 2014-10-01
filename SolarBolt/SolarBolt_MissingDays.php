<?php //SolarBolt_MissingDays.php

//function calculate the missing days
function missingImportDays($systemid, $gen, $exp, $imp, 
	$diffDays, $prevdate, $generated, $exported, $imported
) {

	//calculate the average units
	$pvgen = $gen - $generated;
	$avggen = $pvgen/$diffDays;
		
	$pvexp = $exp - $exported;
	$avgexp = $pvexp/$diffDays;
	
	//set missing values to 0
	$pvimp = 0;
	$avgimp = 0;
			
	$calGen = $generated;
	$calExp = $exported;
	$calImp = 0;
	
	$startdate = $prevdate;

	for ($i = 0; $i < $diffDays-1; $i++)
	{
		//Increment day by one
		$nextdate = date('Y-m-d', 
						strtotime("$startdate + 1day"));
				
		//add avg value onto generated input
		$genInput = $calGen + $avggen;
		$expInput = $calExp + $avgexp;
		$impInput = $calImp + $avgimp;
		
		//calculate what is used and consumed
		$usedfrompvc = $avggen - $avgexp;
		$consumption = $avgimp + $usedfrompvc;
		
		//insert missing data
		insertMissingData($systemid, $nextdate, $genInput, 
			$expInput, $impInput, $avggen, $avgexp, $avgimp, 
			$usedfrompvc, $consumption);

		$calGen = $genInput;
		$calExp = $expInput;
		$calImp = 0;
		$startdate = $nextdate;
		$usedfrompvc = 0;
		$consumption = 0;
	}
	//insert today reading
	insertData($systemid, $gen, $exp, $imp, $avggen, 
		$avgexp, $avgimp);	
}

//function for missing export data
function missingExportDays($systemid, $gen, $exp, $imp, 
	$diffDays, $prevdate, $generated, $exported, $imported
) {	
	//calculate average units from missing dates
	$pvgen = $gen - $generated;
	$avggen = $pvgen/$diffDays;
		
	$pvimp = $imp - $imported;
	$avgimp = $pvimp/$diffDays;
			
	$pvexp = 0;
	$avgexp = 0;
	$calGen = $generated;
	$calImp = $imported;
	$calExp = 0;
	
	$startdate = $prevdate;

	for ($i = 0; $i < $diffDays-1; $i++)
	{
		//Increment day by one
		$nextdate = date('Y-m-d', 
						strtotime("$startdate + 1day"));
				
		//add avg value onto generated input
		$genInput = $calGen + $avggen;
		$expInput = $calExp + $avgexp;
		$impInput = $calImp + $avgimp;
						
		$usedfrompvc = $avggen - $avgexp;
		$consumption = $avgimp + $usedfrompvc;
		
		//insert missing data
		insertMissingData($systemid, $nextdate, $genInput, 
			$expInput, $impInput, $avggen, $avgexp, $avgimp, 
			$usedfrompvc, $consumption);
					
		$calGen = $genInput;
		$calExp = 0;
		$calImp = $impInput;
		$startdate = $nextdate;
		$usedfrompvc = 0;
		$consumption = 0;
			
	}
	//insert today reading
	insertData($systemid, $gen, $exp, $imp, $avggen, 
		$avgexp, $avgimp);
}

//function for missing import and export days
function missingImpAndExpDays($systemid, $gen, $exp, $imp, 
	$diffDays, $prevdate, $generated, $exported, $imported
) {		
	$pvgen = $gen - $generated;
	$avggen = $pvgen/$diffDays;
		
	$pvimp = $pvexp = 0;
	$avgimp = $avgexp = 0;
			
	$calGen = $generated;
	$calExp = 0;
	$calImp = 0;
	
	$startdate = $prevdate;

	for ($i = 0; $i < $diffDays-1; $i++)
	{
		//Increment day by one
		$nextdate = date('Y-m-d', 
						strtotime("$startdate + 1day"));
				
		//add avg value onto generated input
		$genInput = $calGen + $avggen;
		$expInput = $calExp + $avgexp;
		$impInput = $calimp + $avgimp;
						
		$usedfrompvc = $avggen - $avgexp;
		$consumption = $avgimp + $usedfrompvc;
		
		//insert missing data 
		insertMissingData($systemid, $nextdate, $genInput, 
			$expInput, $impInput, $avggen, $avgexp, $avgimp, 
			$usedfrompvc, $consumption);
					
		$calGen = $genInput;
		$calExp = 0;
		$calImp = 0;
		$startdate = $nextdate;
		$usedfrompvc = 0;
		$consumption = 0;
			
	}
	//insert today reading
	insertData($systemid, $gen, $exp, $imp, 
		$avggen, $avgexp, $avgimp);
}

function missingDays($systemid, $gen, $exp, $imp, 
	$diffDays, $prevdate, $generated, $exported, $imported
) {

	//Average generation units between two dates the current 
	//day and previous day
	$avggen = $gen - $generated;
	$avggen = $avggen/$diffDays;
			
	//Average imported units between two dates the current
	//day and previous day
	$avgimp = $imp - $imported;
	$avgimp = $avgimp/$diffDays;
			
	//Average exported units between two dates the current 
	//day and previous day
	$avgexp = $exp - $exported;
	$avgexp = $avgexp/$diffDays;
			 
	$calGen = $generated;
	$calExp = $exported;
	$calImp = $imported;
	$startdate = $prevdate;

	for ($i = 0; $i < $diffDays-1; $i++)
	{
		//Increment day by one
		$nextdate = date('Y-m-d', 
			strtotime("$startdate + 1day"));
				
		//add avg value onto generated input
		$genInput = $calGen + $avggen;
		$expInput = $calExp + $avgexp;
		$impInput = $calImp + $avgimp;
						
		$usedfrompvc = $avggen - $avgexp;
		$consumption = $usedfrompvc + $avgimp;

		//insert missing data
		insertMissingData($systemid, $nextdate, $genInput, 
			$expInput, $impInput, $avggen, $avgexp, $avgimp, 
			$usedfrompvc, $consumption);

		$startdate = $nextdate;
		$usedfrompvc = 0;
		$consumption = 0;	
		$calGen = $genInput;
		$calExp = $expInput;
		$calImp = $impInput;
	}
	//update missing data
	updateMissingData($systemid, $gen, $exp, $imp);
	
	//insert today reading
	insertData($systemid, $gen, $exp, $imp, $avggen, 
		$avgexp, $avgimp);	
}

function updateMissingData($systemId, $gen, 
	$exp, $imp
) {
	global $genName, $expName, $impName, $col;
	
	$genMaxValue = getMaxValue($systemId, $genName);
	$expMaxValue = getMaxValue($systemId, $expName);
	$impMaxValue = getMaxValue($systemId, $impName);
	
	//get last insert data
	$generated = getLastDataInsert($genName, $genMaxValue, 
					$systemId);
	$exported = getLastDataInsert($expName, $expMaxValue, 
					$systemId);
	$imported = getLastDataInsert($impName, $impMaxValue, 
					$systemId);

	//work out the difference in days
	$diffDaysGen = getDifferenceDays($genMaxValue);
	$diffDaysExp = getDifferenceDays($expMaxValue);
	$diffDaysImp = getDifferenceDays($impMaxValue);
		
	//calculate the average units
	$avggen = $gen - $generated;
	$avggen = round($avggen/$diffDaysGen);
	
	$avgexp = $exp - $exported;
	$avgexp = round($avgexp/$diffDaysExp);
	
	$avgimp = $imp - $imported;
	$avgimp = round($avgimp/$diffDaysImp);
	
	//ensure values don't override each other
	$startGen = $diffDaysGen;
	$startExp = $diffDaysExp;
	$startImp = $diffDaysImp;
	$calculateGen = $generated;
	$calculateExp = $exported;
	$calculateImp = $imported;
		
	for($i = 0; $i < $diffDaysGen - 1; $i++)
	{
		$colGen = 'pvgenerated';
	
		//Increment day by one
		$nextGen = date('Y-m-d', 
				strtotime("$genMaxValue + 1day"));
		
		$genValue = $calculateGen + $avggen;
		//update reading
		updateReading($systemId, $nextGen, $genValue, $avggen, $colGen, $genName);	
		$startGen = $nextGen;
		$calculateGen = $genValue;
	}
	
	for($i = 0; $i < $diffDaysExp - 1; $i++)
	{
		$colExp = 'pvexported';
		//Increment day by one
		$nextExp = date('Y-m-d', 
				strtotime("$expMaxValue + 1day"));
				
		$expValue = $calculateExp + $avgexp;
		//update reading		
		updateReading($systemId, $nextExp, $expValue, $avgexp, $colExp, $expName);	
		$startExp = $nextExp;
		$calculateExp = $expValue;
	}
	
	for($i = 0; $i < $diffDaysImp - 1; $i++)
	{
		$colImp = 'imported';
		//Increment day by one
		$nextImp = date('Y-m-d', 
				strtotime("$impMaxValue + 1day"));
					
		$impValue = $calculateImp + $avgimp;			
		//update reading			
		updateReading($systemId, $nextImp, $impValue, $avgimp, $colImp, $impName);
		$startImp = $nextImp;
		$calculateImp = $impValue;
	}
}

//function to update previous reading in database
function updateReading($systemId, $date, $value, $avgvalue, $colNameUnits, $colName)
{
	//update reading
	$update = "UPDATE readings SET `$colName`='$value', 
				`$colNameUnits`='$avgvalue' 
				WHERE `date` = '$date' 
				AND `systemid` = '$systemId'";
			
	$resultUpdate = queryMysql($update);
	sqlCheck($resultUpdate);
	
	updateConsumption($systemId, $date);
}
//issues with this method
function updateConsumption($systemId, $date)
{	
	//update reading
	$query = "SELECT * FROM readings 
		WHERE `systemid` = '$systemId' 
		AND `date` = '$date'";
		
	$res = queryMysql($query);
	echo "is it coming here";
	while($row = sqlArray($res))
	{
		$generate = $row[3];
		$export = $row[5];
		$import = $row[7];
	
		$usedfrompvc = $generate - $export;
		$consumption = $import + $usedfrompvc;

		$update = "UPDATE readings SET `usedfrompvc`='$usedfrompvc', 
				`consumption`='$consumption' 
				WHERE `date` = '$date' 
				AND `systemid` = '$systemId'";
			
		$resultUpdate = queryMysql($update);
		sqlCheck($resultUpdate);
	
	}
}
?>