<?php // SolarBolt_EditSystemDetails;
include 'SolarBolt_DesignHeader.php';

//get session username
$user = getSessionUser();

//if username is empty, redirect page
if($user == "")
{
	$details = "SolarBolt_Index.php";
	redirectPage($details);		
} else {
    $col = 0;
	
	//get the user ID and system ID based on the user's username
	$queryUser = "SELECT * FROM users WHERE `username` = '$user'";
	$resultUser = queryMysql($queryUser);
	$userid = getField($resultUser, $col);
	
	$queryId = "SELECT * FROM system WHERE `userid` = '$userid'";
	$resultId = checkRows($queryId);
	
	if($resultId > 0) 
	{
		showForm();
	} else {
		echo"<div id = 'content'>
			No system exists </div>";
	}

	if(isset($_POST["save"]))
	{
		//sanitize values for malicious intent
		$name = sanitizeCheck($_POST['systemname']);
		$size = sanitizeCheck($_POST['systemsize']);			
		$panelno = sanitizeCheck($_POST['noofpanels']);				
		$tilt = sanitizeCheck($_POST['tilt']);			
		$orientation = sanitizeCheck($_POST['orientation']);	
		$shading = sanitizeCheck($_POST['shading']);
		$brand = sanitizeCheck($_POST['brand']);
		$installdate = sanitizeCheck($_POST['installationdate']);
		$band = sanitizeCheck($_POST['band']);
		
		//check if any mandatory values are empty
		if (empty($name) || empty($size)|| empty($panelno) 
			|| empty($orientation) || empty($band) || ($tilt = "")
		) {
			//if values are empty, create an error message and store in error array
			$error[] = 'Please enter all fields';			
		} else {
			//regular expression to allow date format
			$datepattern = 
				'/^(0?[1-9]|[12][0-9]|3[01])
				[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/';
			
			//match value from input, to regular expression 
			if(preg_match($datepattern, $installdate, $match) === 0)
			{
				//if the pattern does not match, create an error and store in error array
				$error[] = 'Invalid '.$installdate.'
					You have entered an invalid date format. 
					Must be DD/MM/YYYY or DD-MM-YYYY';
			}
			//if the length of the EPC band is greater than one, create error and store
			if(strlen($band) > 1)
			{
				$error[] = 'EPC band is either A, B, C, D, E, F';
			}
			
			//if system size is not an integer value, create error and store
			if(!ctype_digit($size))
			{
				$error[] = 'System size can only be an Integer e.g. 
					3860, 4200. No doubles or floats please.';
			}
			
			//if panel numbers are not an integer, create error and store
			if(!ctype_digit($panelno))
			{
				$error[] = 'Please enter only an Integer 
					for the number panels i.e. 10 panels.';
			}

			//create an array to check if direction values exists within the accepted values
			$direction = array('north','east','south','west', 
				'south west', 'south east');
			if (!in_array($orientation, $direction))
			{
				$error[] = 'Orientation incorrect';
			}
			
			//create an array to check if shading values exists within the accepted values
			$shade = array('no shading','partial shading',
				'modest shading','heavy shading');
			if (!in_array($shading, $shade))
			{
				$error[] = 'Shading incorrect';
			}
			
			//if the error array is empty, proceed with inserting the data
			if(empty($error))
			{
				insert_info($user, $name, $size, $panelno, $tilt, 
					$orientation, $shading, $brand,	
					$installdate,$band);
			}
		}
	}
}

/*
* Update values into database
* @param $us - userid
* @param $name - systemname
* @param $size - system size
* @param $np - number of panels
* @param $tilt - angle of tilt
* @param $ort - orientation
* @param $shad - shading
* @param $brnd - system brand
* @param $date - installation date
* @param $band - epc band
*/
function updateInfo($us, $name, $size, $np, $tilt, 
			$ort, $shad, $brand, $date, $band
) {
	$col = 0;
	//get the user's id from their username stored in session
	$queryUser = "SELECT * FROM users WHERE `username` = '$us'";
	$result = queryMysql($queryUser);
	$userid = getField($result, $col);
	
	//format the date value to be correct for database insertion
	$newdate = date('Y-m-d', strtotime($date)); 
	
	//SQL statement to insert the values into system table
	$update = "UPDATE system SET `systemname`='$name', 
				`systemsize`='$size', `noofpanels`='$np',
				`tilt`='$tilt', `orientation`='$ort',
				`shading`='$shad', `brand`='$brand', 
				`installationdate`='$newdate', 
				`epcband`='$band' WHERE `userid` = '$userid'";
			
	//check if the result is successful
	$resultUpdate = queryMysql($update);
	sqlCheck($resultUpdate);
		
	//redirect user to different page
	$details = "SolarBolt_Profile.php";
	redirectPage($details);										
}

function showForm(){
echo <<<_END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; 
charset=utf-8" />
<script type ="text/javascript" src="val.js"></script>
</head>
<body>
<div id = "bannerreg"></div>
<div id = "content">
<form name = "SolarBolt_EditSystemDetails" 
form action="SolarBolt_EditSystemDetails.php" method="post" >
<div id = "styleform" class="theform"><pre>
<h2>Edit System Details</h2>
<div id = "error" </div>
<label for = "name">System Name	</label>		
<input type="text" name='systemname'>
<label>System size	</label>		
<input type="text" name="systemsize" 
onChange = "validateInt(this,'size');"/> kw
<div id = "size" style="color:#FF0000"></div>
<label>Number of panels </label>
<input type="text" name="noofpanels" 
onChange = "validateInt(this,'panels');"/>
<div id = "panels" style="color:#FF0000"></div>
<label>Tilt	</label>			
 <select name="tilt"/>
				<option value="0"> 0 </option>
				<option value="15"> 15 </option>
				<option value="30"> 30 </option>
				<option value="45"> 45 </option>
				<option value="60"> 60 </option>
				<option value="75"> 75 </option>
				<option value="90"> 90 </option></select>
<div id = "tilt" style="color:#FF0000"></div>				
<label>Orientation</label>
 <select name="orientation"/>
				<option value="north"> North </option>
				<option value="east"> East </option>
				<option value="west"> West </option>
				<option value="south"> South </option>
				<option value="south east"> South East </option>
				<option value="south west"> South West </option>
</select>
<div id = "orient" style="color:#FF0000"></div>				
<label>Shading</label>		
 <select name="shading"/>
				<option value="no shading"> No shading </option>
				<option value="partial shading"> Partial shading </option>
				<option value="modest shading"> Modest shading </option>
				<option value="heavy shading"> Heavy shading </option></select>
<div id = "shading" style="color:#FF0000"></div>
<label>Brand/Manufacturer </label>		
<input type="text" name="brand"/> 
<label>Installation date </label>
<input type="text" name="installationdate" onChange = "validateDate(this, 'date');" />
<div id = "date" style="color:#FF0000"></div>
<label>EPC Band	</label>		 
 <select name="band"/>
				<option value="A"> A </option>
				<option value="B"> B </option>
				<option value="C"> C </option>
				<option value="D"> D </option>
				<option value="E"> E or lower </option></select>
<div id = "band" style="color:#FF0000"></div>
<input type="submit" name='save' value="Save Details" />

</pre></form>
</div>
</body>
</html>
_END;
}

?>
