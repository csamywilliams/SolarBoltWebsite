<?php // SolarBolt_ProfilePicture.php
include 'SolarBolt_DesignHeader.php';

//get user's username from session
$user = getSessionUser();

//if the username is empty redirect page
if ($user == "")
{
	$details = "SolarBolt_Index.php";
	redirectPage($details);	
} else {
	//get value from file form field
	if (isset($_FILES['image']))
	{
		//create an error array
		$error = array();
		//create an array for allowed file extensions
		$extallowed = array('jpg','jpeg','png','gif', 'pjpeg');
		//create a variable to store maximum file size
		$maxfilesize = 3145728;
		
		//name of file
		$file_name = strtolower($_FILES['image']['name']);	
	
		//file extensions exploded to extract the elements to 
		//get the extension end takes last element of the array
		//strtolower turns the extension to lower case letters
		$file_ext = strtolower(end(explode('.', $file_name)));	
		
		//temp location
		$tmp_name = $_FILES['image']['tmp_name'];
				//file size
		$file_size = $_FILES['image']['size'];

		//if it does not appear in array then it is not allowed
		if (!in_array($file_ext, $extallowed))
		{
			$error[] = 'Extension not allowed';
		}
		//if file size is equal to 0, store error in array
		if ($file_size == 0)
		{
			$error[] = "Error file upload only accept 
						'.jpeg','.png','.gif' ";
		}
		
		//if file size exceeds maximum amount, store error in array
		if ($file_size >  $maxfilesize)
		{
			$error[] = 'Exceed maximum file size, 
						must be under 3MB';
		}
		//on form submit
		if (isset($_POST['submit']))
		{
			//check if error is empty, it true upload the image
			if (empty($error))
			{	
				//declare the path for the images to be move to
				$location = "profilepics/$user-$file_name";
				//move the file
				move_uploaded_file($tmp_name, $location);
			
				//update the image column in database which 
				//corresponds to the correct user 
				$query = ("UPDATE users SET image='$location' 
						WHERE `username` = '$user'");
				$res = queryMysql($query);	

				//on succession of updation redirect page
				$details = "SolarBolt_Profile.php";
				redirectPage($details);	
			}	
		} else {
			//if errors exist in array, display the array to the user
			foreach ($error as $fail)
			{
				echo "<div id = 'content'>";
				echo $fail, '</br>';
				echo "</div>";
			}
		}
	}	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>	
	<div id = 'bannerprofile'></div>
	<div id = "content">
	<form method = "post" action="SolarBolt_ProfilePicture.php" enctype="multipart/form-data">
	<div id = "styleform" class="theform"><pre>	
<h3> Change Profile Picture </h3>
<label>Image: </label> <input type='file' name='image'/> 
<input type='submit' name='submit' value="Upload" />

	</pre></form>
	</div>
</div>
</body>
</html>