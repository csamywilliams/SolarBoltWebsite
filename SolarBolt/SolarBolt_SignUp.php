<?php // SolarBolt_SignUp.php
include 'SolarBolt_DesignHeader.php';

//check if no session exists, if it does destroy the session
if (isset($_SESSION['username'])) 
	cookieSessionDestroyed();

//get values from form fields
if (isset($_POST['username'])) 
{
	//sanitize the values to check for malicious intent
	$username = sanitizeCheck($_POST['username']);				
	$password = sanitizeCheck($_POST['password']);					
	$fname = sanitizeCheck($_POST['fname']);				
	$lname = sanitizeCheck($_POST['lname']);					
	$address1 = sanitizeCheck($_POST['addressline1']);
	$address2 = sanitizeCheck($_POST['addressline2']);
	$postcode = sanitizeCheck($_POST['postcode']);
	$city = sanitizeCheck($_POST['city']);
	$country = sanitizeCheck($_POST['country']);
	$email = sanitizeCheck($_POST['email']);

	//check if mandatory fields are empty, if true store error in array
	if (empty($username)|| empty($password) 
		|| empty($fname) || empty($lname)
		||empty($postcode) || empty($email)
	) {							
		$error[] = "Please enter all fields";	
		
	} else {
		//if fields exists
		//check if username is already taken
		$sql = "SELECT * FROM users WHERE username='$username'";
		
		if (checkRows($sql))
		{							
			$error[] = "<font color= red> Sorry username taken </font>"; 
		} else {
			
			//if username is not taken
			//validate the username, check if it between 4 and 12 characters
			if(strlen($username) <= 4
				|| strlen($username) >= 12
			) {
				$error[] = 'Please ensure username or password is
							between 4 and 12 characters';
			}
			
			//validate the password
			if(strlen($password) <= 4
				|| strlen($password) >= 12
			) {
				$error[] = 'Please ensure username or password is
							between 4 and 12 characters';
			}

			//validate the email
			if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$error[] = 'Incorrect email address format';
			}
			
			//pattern match the mandatory fields, if errors persist store in array
			$pattern = "/[a-zA-Z ]+$/";
			if(preg_match($pattern, $fname, $match) === 0)
			{
				$error[] = 'Invalid '.$fname.' 
					only allowed alphabetic characters';
			}
			if(preg_match($pattern, $lname, $match) === 0)
			{
				$error[] = 'Invalid '.$lname.' 
					only allowed alphabetic characters';
			}
			if(preg_match($pattern, $city, $match) === 0)
			{
				$error[] = 'Invalid '.$city.' 
					only allowed alphabetic characters';
			}
			if(preg_match($pattern, $country, $match) === 0)
			{
				$error[] = 'Invalid '.$country.' 
					only allowed alphabetic characters';
			}
			
			//pattern match postcode
			$postcodepattern = '/^[a-zA-Z0-9]{3,9}$/';
			if(preg_match($postcodepattern, $postcode, $match) === 0)
			{
				$error[] = 'Invalid '.$postcode.' 
					not in correct format. Do not include spaces';
			}
			
			//if error is empty
			if(empty($error))
			{
				//salt the password
				$salt1 = "s0lar";
				$salt2 = "pvsav3";
	
				//encrypt the password
				$saltpw = md5("$salt1$password$salt2");
			
				//call function to insert values
				insertUser($username, $saltpw, $fname, 
					$lname, $address1, $address2, $postcode, 
					$city, $country, $email);
			}
		}
	}
}

function insertUser($un, $pw, $fn, $ln, $ad1, 
	$ad2, $pc, $cty, $cntry, $em
) {	
	//default image for user
	$image = 'profilepics/defaultpic.jpg';
	
	//insert values into database
	$query = "INSERT INTO users 
			(username,password,fname,lname,addressline1,
			addressline2, postcode,city,country,email, image)
			VALUES" . "('$un', '$pw', '$fn', '$ln','$ad1',
			'$ad2','$pc','$cty','$cntry','$em', '$image')";
	
	//check if successful
	$result = queryMysql($query);
	sqlCheck($result);
	
	//redirect page
	$details = "SolarBolt_Index.php";
	redirectPage($details);									//redirect the page to another php page
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type ="text/javascript" src="val.js"></script>
</head>
<body>
<div id = "bannerreg"></div>
<div id = "content">
	<?php
		if(empty($error) == false)
		{
			echo '<ui>';
			foreach($error as $fail)
			{
				echo '<li>', $fail ,'</li>';
			}
			echo '</ui></br>';
		}
	?>
<form name = "SolarBolt_SignUp" form action="SolarBolt_SignUp.php" method="post">	
<div id = "styleform" class="theform"><pre>
<h2>Please Register</h2>
<div id = "errormsg" style="color:#FF0000"></div>	
<label>Username </label><input type="text" name="username" onChange = "inputValidation(this,4,12,'Username','usermsg');"/>
<div id = "usermsg" style="color:#FF0000"></div>	
<label>Password </label>
<input type="password" name="password" onChange = "inputValidation(this,5,12,'Password','passmsg');"/>
<div id = "passmsg" style="color:#FF0000"></div>
<label>Firstname</label>
<input type="text" name='fname' onChange = "validateName(this, 'Firstname', 'fnamemsg');"/>
<div id = "fnamemsg" style="color:#FF0000"></div>
<label>Lastname </label>
<input type="text" name='lname' onChange = "validateName(this, 'Lastname', 'lnamemsg');"/>
<div id = "lnamemsg" style="color:#FF0000"></div>
<label>Address Line 1</label>
<input type="text" name='addressline1'/>	
<label>Address Line 2</label>
<input type="text" name='addressline2'/>
<label>PostCode </label>
<input type="text" name='postcode' onChange = "validatePostcode(this, 'pcmsg');"/>
<div id = "pcmsg" style="color:#FF0000"></div>
<label>City</label>
<input type="text" name='city' onChange = "validatePlace(this, 'City', 'citymsg');"/>
<div id = "citymsg" style="color:#FF0000"></div>
<label>Country </label>
<input type="text" name='country' onChange = "validatePlace(this, 'Country', 'countrymsg');"/>
<div id = "countrymsg" style="color:#FF0000"></div>
<label>Email	</label>
<input type="text" name='email' onChange = "validateEmail(this, 'emailmsg');"/>
<div id = "emailmsg" style="color:#FF0000"></div>
		
<input type="submit" value="Register" />
</pre></form></div>

</div>
</body>
</html>

