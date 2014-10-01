<?php // SolarBolt_PvLogin.php
include 'SolarBolt_DesignHeader.php';

//retrieve values from form field
if (isset($_POST['username']) 
	&& isset($_POST['password'])
) {
	//sanitize the users input for malicious intents
	$username  = sanitizeCheck($_POST['username']);				//sanitize the string
	$password  = sanitizeCheck($_POST['password']);

	//check if either username or password is empty and
	//display error message
	if (empty($username)|| empty($password))
	{
		echo "<div id='content' > 
			Not all fields are entered <br /> </div>";
	} else {
		//salt the password to compare with database value
		$salt1 = 's0lar';
		$salt2 = 'pvsav3';
	
		//encrypt the password
		$saltpw = md5("$salt1$password$salt2");

		//query the database to check if username and password matches
		$query = "SELECT username,password FROM users 
				WHERE username= '$username' 
				AND password = '$saltpw'";
			
		//if username and password does not match display error
		//if they do, store username in session and redirect page
		if (checkRows($query)==0)
		{
			echo "<div id = 'content'> Username or 
				Password Invalid, Please try again </div>";
		} else {
			$_SESSION['username'] = $username;

			$details = "SolarBolt_Profile.php";
			redirectPage($details);				
		}
	}
}

echo <<<_END
<div id = "bannerlogin"></div>
<div id = "content">
<form action="SolarBolt_PvLogin.php" method="post">
<div id = "styleform" class="theform"><pre>
<label>Username</label>	<input type="text" name="username" />
<label>Password</label>	<input type="password" name="password" />
<input type="submit" value="Login" />
</pre></form>
</div>
_END;

?>