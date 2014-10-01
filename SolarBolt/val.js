function inputValidation(user,min,max,alerttext,msg)
{
	var input = user.value.length;
	//get element by ID
	document.getElementById(msg).innerHTML="";
	
	if (input == 0 || input >= max || input < min)
	{
		//get element by ID
		document.getElementById(msg).innerHTML=(alerttext 
			+ " must be between 4 and 12 characters");
		user.focus();
		return false;
	}	
return true;
}
//function to validate names
function validateName(name,alerttext, msg)
{ 
	//get element by ID
	document.getElementById(msg).innerHTML="";
	//regular expression
	var letters = /^[A-Za-z ]+$/;
	//if value matches pattern
	if(name.value.match(letters))
	{
		return true;
	}
	else
	{
		//get element by ID
		document.getElementById(msg).innerHTML = 
			(alerttext + " must have alphabet characters only");
		name.focus();
		return false;
	}
}

//function to validdate postcodes
function validatePostcode(pc, msg)
{ 
	//get element by ID
	document.getElementById(msg).innerHTML="";
	//regular expression
	var postcode = /^[a-zA-Z0-9]{3,9}$/; 
	//if value matches pattern
	if(pc.value.match(postcode))
	{
		return true;
	}
	else
	{
		//get element by ID
		document.getElementById(msg)
			.innerHTML="Postcode format not valid. Please ensure no spaces ";
		pc.focus();
		return false;
	}
}

//validate a place name
function validatePlace(place, alerttext, msg)
{
	//get element by ID
	document.getElementById(msg).innerHTML="";
	//regular expression
	var letters = /^[A-Za-z ]+$/;
	//if value matches pattern
	if(place.value.match(letters))
	{
		return true;
	}
	else
	{
		//get element by ID
		document.getElementById(msg).innerHTML=
			(alerttext + " must only contain alphabet characters only");
		place.focus();
		return false;
	}
}

//validate email input
function validateEmail(email, msg)
{
	//get element by ID
	document.getElementById(msg).innerHTML="";
	//regular expression
	var format = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	//if value matches pattern
	if(email.value.match(format))
	{
		return true;
	}
	else
	{
		//get element by ID
		document.getElementById(msg)
			.innerHTML="You have entered an invalid email address!";
		email.focus();
		return false;
	}
}

//validate date input
function validateDate(date, msg)
{
	//get element by ID
	document.getElementById(msg).innerHTML="";
	//regular expression
	var format = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
	//if value matches pattern
	if(date.value.match(format))
	{
		return true;
	}
	else
	{
		//get element by ID
		document.getElementById(msg)
			.innerHTML="Date must be DD/MM/YYYY or DD-MM-YYYY format";
		date.focus();
		return false;
	}	
}	

//validate an integer input
function validateInt(integer, msg)
{		
	//get element by ID
	document.getElementById(msg).innerHTML="";
	//regular expression
	var format = /^[0-9]+$/;
	//if value matches pattern
	if(integer.value.match(format))
	{
		return true;
	}
	else
	{
		//get element by ID
		document.getElementById(msg)
			.innerHTML="Error: must be integer value";
		integer.focus();
		return false;
	}	
}	





