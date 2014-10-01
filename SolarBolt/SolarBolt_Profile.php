<?php // SolarBolt_Profile.php
include 'SolarBolt_DesignHeader.php';

//if username exists
if(isset($_SESSION['username']))
{
	//get username 
	$us = getSessionUser();
		
	//retrieve all information from the users table 
	//where the username is equivalent
	$query = "SELECT * FROM users WHERE `username` = '$us'";
	$res = queryMysql($query);
	
	//loop through the results and store in variables
	while($row = sqlArray($res))
	{
		$userid = $row[0];
		$fname = $row[3];
		$lname = $row[4];
		$add1 = $row[5];
		$add2 = $row[6];
		$pc = $row[7];
		$city = $row[8];
		$country = $row[9];
		$email = $row[10];
		$image = $row[11];

		//check if file exists, else set to defaul image
		if(file_exists($image))
		{
			$imageok = $image;
		} else {
			$imageok = 'profilepics/defaultpic.jpg';
		}
	
		//show profile
		echo <<<_END
		<div id = 'bannerprofile'></div>
		<div id = 'content'>
	
		<h2> $us HomePage </h2> 
		<h3> User Details </h3> </br>

		<div class = 'element2'> 
		<img src='$imageok' style="width: 100%;" 
		style="height: 100%;"></br>
		<a href='SolarBolt_ProfilePicture.php'>
		Change profile picture</a> </br> 
		<a href='SolarBolt_EditSystemDetails.php'>
		Edit System Details</a> </br> 
		</div>

		<div class = 'element'> Firstname:  $fname  </div>
		<div class = 'element'> Lastname:	$lname  </div>
		<div class= 'element'> Email:	$email  </div>
		</br>
		<h3> Address  </h3></br>
		<div class = 'element'> Address Line 1:	$add1  </div>
		<div class = 'element'> Address Line 2:	$add2  </div>
		<div class = 'element'> City:	$city  </div>
		<div class = 'element'> Country:	$country  </div>
		<div class = 'element'> PostCode:	$pc  </div>	
		
		</br>	
_END;

	}

	//select system details from users ID
	$system = "SELECT * FROM system 
				WHERE `userid` = '$userid'";
	$result = queryMysql($system);
	
	//loop through results and store in variables
	while($row = sqlArray($result))
	{
		$name = $row[2];
		$size = $row[3];
		$panels = $row[4];
		$tilt = $row[5];
		$orient = $row[6];
		$shading = $row[7];
		$brand = $row[8];
		$instaldate = $row[9];
		$epcband = $row[10];
	
		//display information
		echo <<<_END

		<h3> System Details </h3> </br>
	
		<div class = 'element'> System Size:	$size  </div>
		<div class = 'element'> Number of Panels:	$panels  </div>
		<div class= 'element'> Tilt:	$tilt  </div>
		<div class = 'element'> Orientation:	$orient  </div>
		<div class = 'element'> Shading:	$shading  </div>
		<div class = 'element'> System Brand:	$brand  </div>
		<div class = 'element'> Date System Installed:	$instaldate  </div>
		<div class = 'element'> EPC Band:	$epcband  </div>		
		</div>

_END;
	}
}
?>