<?php // SolarBolt_ContactForm.php
include 'SolarBolt_DesignHeader.php';

//create array for errors
$error = array();

//check if any fields in form are empty
if (empty($_POST) === false)
{	
	//sanitize users input
    $name = sanitizeCheck($_POST['name']);
	$email  = sanitizeCheck($_POST['email']);
	$message = sanitizeCheck($_POST['message']);
		
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$error[] = 'Incorrect email address format';
	}
	
	//if the error array is empty
	if(empty($error) == true)
	{		
		//get the current date
		$date = date('Y-m-d');
		
		//store values into admin table
		$query = "INSERT INTO admin (name, sentfrom, message,
		date) VALUES('$name', '$email', '$message', '$date')";
	
		//check if query is successful
		$result = queryMysql($query);
		sqlCheck($result);
		
		//redirect page
		$details = "SolarBolt_Index.php";
		redirectPage($details);	
	}
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
<div id = "bannercontact"></div>
<div id = "content">
	<?php
		if(empty($error) == false){
		
			echo '<ui>';
			foreach($error as $fail)
			{
				echo '<li>', $fail ,'</li>';
			}
			echo '</ui>';
		}
	?>
<form method = "post" action="SolarBolt_ContactForm.php">
<div id = "styleform" class="theform"><pre>
<label>Name: </label>
<input type="text" name='name' onChange = "validateName(this, 'name', 'namemsg');"/>
<div id = "namemsg" style="color:#FF0000"></div>
<label>Email</label>
<input type="text" name='email' onChange = "validateEmail(this, 'emailmsg');"/><div id = "emailmsg" style="color:#FF0000"></div>
<label>Message:</label>
<textarea name="message"></textarea>
<input type = "submit" value="Submit">
</pre></div></form>
</div>
</body>
</html>