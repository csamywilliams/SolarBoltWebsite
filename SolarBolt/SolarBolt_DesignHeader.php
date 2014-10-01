<?php // SolarBolt_DesignHeader.php
include 'SolarBolt_FunctionCheck.php';

session_start(); 		//start cookie session

echo <<<_END
<html>

<head>
    <meta http-equiv="Content-type" content="text/html;
	charset=UTF-8" />
    <title>Solar Bolt</title>
    <meta http-equiv="Content-Language" content="en-us" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta name="MSSmartTagsPreventParsing" content="true" />
    <meta name="description" content="Description" />
    <meta name="keywords" content="Keywords" />
    <meta name="author" content="Amy Jenkins" />
	<link rel="stylesheet" href="SolarBolt_Stylesheet.php"/>
</head>
<body>

_END;

//check if user is logged in
if (isset($_SESSION['username'])) 
{
	$user = $_SESSION['username'];					
	$login = TRUE;										
} else {
	$login = FALSE;
}	

//if user is logged in display these links
if ($login)
{
	echo"<div id = 'outerlogin'>
		<div id = 'login'>
		<a class='link' href='SolarBolt_Logout.php'>
		Logout</a> | 
		<a class='link' href='SolarBolt_ContactForm.php'>
		Contact Us </a></div></div>
		<div id = 'header'></div>
		
		<div id = 'outernavbar'>
		<div id = 'navbar'>
		<a class='link' href='SolarBolt_RegisterSystem.php'>
		Add System</a> <bar> | </bar>
		<a class='link' href='SolarBolt_Profile.php'>
		Profile</a> <bar> | </bar>
		<a class = 'link' href='SolarBolt_ReadingInput.php'> 
		Readings </a> <bar> | </bar>
		<a class = 'link' href='SolarBolt_Stats.php'> 
		Statistics </a> <bar> | </bar>
		<a class = 'link' href='SolarBolt_Help.php'> 
		Help </a></div></div> ";
} else {
	//if not display these links
	echo "<div id = 'outerlogin'>
		<div id = 'login'>	
		<a class='link' href='SolarBolt_PvLogin.php'>
		Login</a> |  
		<a class='link' href='SolarBolt_ContactForm.php'>
		Contact Us </a></div></div>
	
		<div id = 'header'></div>

		<div id = 'outernavbar'>
		<div id = 'navbar'>		
		<a class='link' href='SolarBolt_SolarElectricity.php'> 
		Solar Electricity  </a> <bar> | </bar>
		<a class='link' href='SolarBolt_Calculator.php'>
		Solar Energy Calculator</a>  <bar> | </bar>
		<a class='link' href='SolarBolt_SignUp.php'>
		Register </a>   <bar> | </bar>
		<a class='link' href='SolarBolt_About.php'>
		About </a></div> </div>";
}

?>
</div>
</body>
</html>
