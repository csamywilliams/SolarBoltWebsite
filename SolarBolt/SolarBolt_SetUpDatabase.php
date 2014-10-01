<?php //SolarBolt_SetUpDatabase.php
include_once 'SolarBolt_FunctionCheck.php';

	
echo '<h3> Setting up </h3>';
	
//create tables in the database, declare the column names
//and properties
createTable('users',
				'userid INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(userid),
				username VARCHAR(25)NOT NULL, 
				UNIQUE(username),
				password VARCHAR(35) NOT NULL, 
				fname VARCHAR(40)NOT NULL,
				lname VARCHAR(40)NOT NULL,
				addressline1 VARCHAR(30),
				addressline2 VARCHAR(30),
				postcode VARCHAR(8)NOT NULL,
				city VARCHAR(30),
				country VARCHAR(30),
				email VARCHAR(50),
				image VARCHAR (4096)');
									
createTable('system', 
				'systemid INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(systemid), 
				userid INT NOT NULL, 
				FOREIGN KEY(userid) REFERENCES users(userid),
				systemname VARCHAR (30),
				systemsize INT (10) NOT NULL,
				noofpanels INT(11) NOT NULL,
				tilt INT (11),
				orientation VARCHAR(15),
				shading VARCHAR(15),
				brand VARCHAR(30),
				installationdate DATE,
				epcband VARCHAR(1)');
							
createTable('readings', 
				'readingid INT NOT NULL AUTO_INCREMENT,
				PRIMARY KEY(readingid),
				systemid INT NOT NULL, 
				FOREIGN KEY(systemid) REFERENCES system(systemid),
				date DATE NOT NULL,
				pvgenerated INT (11),
				generatedinput INT (11),
				pvexported INT (11),
				exportedinput INT(11),
				imported INT(11),
				importedinput INT(11),
				usedfrompvc INT(11),
				consumption INT(11)');
															
createTable('admin', 
				'msgid INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(msgid),
				name VARCHAR(15) NOT NULL,
				date DATE NOT NULL,
				sentfrom VARCHAR(30) NOT NULL,
				message VARCHAR (250) NOT NULL');
				
createTable('shadingfactor',
				'shadingid INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(shadingid),
				overshading VARCHAR(15),
				overshadingfactor DOUBLE');
										
createTable('tilt',
				'tiltid INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY (tiltid),
				tilt VARCHAR (15) NOT NULL');
							
createTable('orientation',
				'orientid INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY (orientid),
				orientation VARCHAR (15) NOT NULL');
			
createTable('radiation',
				'solarid INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY (solarid),
				orientid INT NOT NULL, 
				FOREIGN KEY(tiltid) REFERENCES tilt(tiltid),
				tiltid INT NOT NULL,
				radiation INT NOT NULL,
				FOREIGN KEY(orientid) REFERENCES orientation(orientid)');
						
//insert values into the shadingfactor table for the solar energy tool						
$insert = "INSERT INTO shadingfactor 
			(overshading, overshadingfactor) VALUES" .
			"('none', 1.0), ('partial', 0.8), 
			('modest', 0.65), ('heavy', 0.5)";

//check if the SQL statement is successful			
$result = queryMysql($insert);
sqlCheck($result);

//insert values into the orientation table for the solar energy tool	
$orient = "INSERT INTO orientation(orientation) VALUES" .
			"('north'), ('northwest/east') , ('east/west'), 
			('south'), ('southeast/west')";
	$res1 = queryMysql($orient);
	sqlCheck($res1);
	
//insert values into the tilt table for the solar energy tool	
$tilt = "INSERT INTO tilt(tilt) VALUES" .
	"('horizontal'), ('vertical'), ('30'), ('45'), ('60')";
	$res2 = queryMysql($tilt);
	sqlCheck($res2);
	
//insert values into the radiation table for the solar energy tool	
$rad = "INSERT INTO radiation (radiation, tiltid, orientid) 
		VALUES" .
		"(961,1,5),(961,1,4), (961,1,3), (961,1,2), (961,1,1),
		(1073,3,4), (1054,4,4), (989,5,4), (746,2,4),
		(1027,3,5), (997,4,5), (927,5,5), (705,2,5), (913,3,3),
		(854,4,3), (776,5,3), (582,2,3), (785,3,2), (686,4,2),
		(597,5,2), (440,2,2), (730,3,1), (640,4,1), (500,5,1),
		(371,2,1)";

	$res = queryMysql($rad);
	sqlCheck($res);					 					   
?>