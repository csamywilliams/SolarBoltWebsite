<?php // SolarBolt_FunctionCheck.php
require_once 'SolarBolt_Login.php';								

//connect to database
$mysqli = new mysqli($db_hostname,$db_username,
				$db_password,$db_database);

//if connection fails display error
if (mysqli_connect_errno()) 
{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/*
*Function to check if statement is successful
*@param $name - table name
*@param $sqlquery - table columns and attributes
*/
function createTable($name, $sqlquery)
{		
	// if table exists, show it exists
	if (tableExists($name))
	{							
		echo "Table '$name' already exists < br/>";		
	} else {
		//if tables doesn't exist, create table
		queryMysql("CREATE TABLE $name($sqlquery)");	
		echo "Table '$name' created < /br>";			
	}
}

/*
*Function to check if statement is successful
*@param $name - table name
*@return number of rows in query
*/
function tableExists($name)
{							 
	$query = queryMysql("SHOW TABLES LIKE '$name'");	
	return mysqli_num_rows($query);
}

/*
*Function to check if statement is successful
*@param $query - SQL statement
*@param $result - return the result
*/
function queryMysql($query)
{							
	global $mysqli;
	$result = mysqli_query($mysqli, $query) 
			or die (mysqli_error($mysqli)); 
	
	return $result;
}

/*
*Function to check if statement is successful
*@param $sql - SQL statement
*/
function sqlCheck($sql)
{
	if(!$sql) die ("Failed: ".mysqli_error());
}

/*
*Function to fetch array from database
*@param $sql - SQL statement
*@param $row - result
*/
function sqlArray($sqlarray)
{
	$row = mysqli_fetch_array($sqlarray);
	return $row;
}

/*
*Function to fetch row from database
*@param $sql - SQL statement
*@param $row - result
*/
function fetchRow($sql)
{
	$row = mysqli_fetch_row(queryMysql($sql));
	return $row;
}

/*
*Function to fetch assoc array from database
*@param $sql - SQL statement
*@param $row - result
*/
function sqlFetchAssoc($sql)
{
	$row = mysqli_fetch_assoc($sql);
	return $row;
}

/*
*Function to check number of rows
*@param $sql - SQL statement
*@param $row - result
*/
function checkRows($sql)
{
	$row = mysqli_num_rows(queryMysql($sql));							//if the query is true it
	return $row;
}

/*
*Function to check for empty rows
*@param $sql - SQL statement
*@param $row - result
*/
function emptyRows($sql)
{
	$row = mysqli_num_rows($sql);							//if the query is true it
	return $row;
}

/*
*Function to sanitize users input
*@param $variable - value of variable
*@param sanitized variable
*/
function sanitizeCheck($variable)
{															//function to sanitize input
	global $mysqli;
	$variable = strip_tags($variable);						//removes the tags from the variable
	$variable = htmlentities($variable);					//stips the html entities from the variable
	$variable = stripslashes($variable);					//strips the lashes from the variable
	return mysqli_real_escape_string($mysqli, $variable);			//prevent sql injections
}

/*
*Function to get username from session
*@return $_SESSION['username'] - username in session
*/
function getSessionUser()
{
	return $_SESSION['username'];
}

/*
*Function to destroy session
*/
function cookieSessionDestroyed()
{
	$_SESSION=array();
	
	if(session_id() != " " || isset($_COOKIE[session_name()]))
		setcookie(session_name(), '',time()-2592000,'/');
		
	session_destroy();
}

/*
*Function to redirect page
*@param $page - location
*/
function redirectPage($page)
{							//redirect page function
	  if (!@header("Location: ".$page))
	  exit;
}

/*
*Function to get a specific field from a column in a table
*@param $query - SQL statement
*@param $col - column value
*/
function getField($query, $col)
{	
	$res = emptyRows($query);
	
	if($res > 0)
	{
		while($row = sqlArray($query))
		{
			$field = $row[$col];
		}	
		return $field;
	}
	else
	{
		echo "<div id = 'content'> 
			Error no entry found </div>";
	}
}
?>