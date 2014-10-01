<?php
include 'SolarBolt_DesignHeader.php';

//get username from session
$user = getSessionUser();

//if username is empty, redirect page
if ($user == "")
{
	$details = "SolarBolt_Index.php";
	redirectPage($details);	
} 


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<div id="bannerabout"></div>

<div id = "content">

<h2> Help Documentation </h2>

New user? Check out the help documentation </br></br>

<h3> Starting out </h3></br>
By now you are a official registered user of Solar Bolt.
Before you can insert any data you must register your system.
This can be done by navigation to the register system link or 
<a href='SolarBolt.RegisterSystem'> here. </a></br>
</br>
When the system is registered, you are able to enter readings using
the 'Readings' tab.

Please follow these guidelines for successful reading input:
<ul>
<li>At least one field has to be entered</li>
<li>Input may only be an integer i.e 3000, 2967, 4000.</br>
Double or floating point numbers are not accepted i.e 2.345</li>
<li>The current input value cannot be less than the previous input in the database
</br> i.e. 20/04/2013 Generated Input was 3658, today's reading 3650 (not accepted)</li>
<li>On succession of input you will be redirected to a new page, if not an error mesage 
will be displayed</li>
</ul>

</br> 
Now that there are readings for your system, check out your output under the 'Statistics' tab.
</br> This will show four links to redirect you to different views.
The views available are:
<ul>
<li>Daily</li>
<li>Weekly</li>
<li>Monthly</li>
<li>Yearly</li>
</ul>
</br>
Each view shows the PV output for specific dates, it could be today or for the whole month.
With every view it calculates the Feed-In tariff rates where if you hover over each bar provides
the amount in GBP. It also contains views for the total generated, exported, imported, used from the panels 
and total consumption.

</br>
</br>
<h3> Changing your profile picture </h3>
</br>
On register you have a default picture which you can change using the 'Change profile picture' in the profile tab.
By clicking this will direct you to a page to change your picture.
Requirements
 <ul>
<li>Acceptable formats: .JPG, .JPG, .PNG and .GIF</li>
<li>Picture size: cannot be 3MB or larger</li>
</ul>

</div>
</body>
</html>
