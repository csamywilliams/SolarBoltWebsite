<?php
	header('Content-type: text/css');			//content of this file is css
?>	

html, body{
	margin: 0;
	padding: 0;
	border: 0;
	background: #fff;
}

body {
	font-family: Arial;
	margin-bottom: 10px;
	margin-left: 75px;
	margin-right: 75px;
	
	font-size: 14px;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}

#outerlogin{
	height: 7%;
	max-width: 750px;
	margin: 0 auto;
	width: 200px;
	position:relative;
	right: -275px;
	background: #FFCC00;
	background-size: contain;
}

#login{
	padding: 5px;
	text-align:center;
}

#header{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;

	background-image:  url('htdocs/images/header.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#navbar {
	padding: 5px;
}

#outernavbar {
	max-width: 750px;
	margin: 0 auto;
	display: block;
	height: 6%;
	font-size: 14px;
	color:#666;
	text-align: center;
}

#content {
	max-width: 650px;
	margin: 0 auto;
	background: #ffffff;
	padding: 10px;
}

.element{
	max-width: 40%;
	background-color: #FFFF66;  
    border: 1px solid #FF6600;  
    color: #000;   
    padding: 4px;  
    text-decoration: none;  
}

.element2{
	max-width: 50%;
	background-color: #FFFF66;  
	float: right;
    border: 1px solid #FF6600;  
    color: #000;   
    padding: 4px;  
    text-decoration: none;  
}

h1, h2, h3 {
	 font-family: Arial;
}

h1 {
	font-size: 2em;
	color: #000000;
}

h1 span {
    border-bottom:solid #ffad00;
}

h2 {
	font-size: 1.5em;
}

h3 {
	 font-size: 1em;
}

bar 
{
	color: #ffad00;
	font-weight:bold;
}

a:link { color: #666; }
a:visited { color: #666; }
a:hover { color: #ffad00; }
a:active { color: #ffad00; }

.link {
	text-align:center;  
	padding: 5px;
	font-weight: bold;
	color: #666666;
	text-decoration: none;
	padding-top: 2px;
}

#content .padding{
	padding: 10px;
}

/* paragraphs */
#content p {
	padding-bottom: 0px;
}

label
{
	width: 5px;
	float: left;
	margin-right: 2em;
	padding: 2px;
	display: block
}

box
{
	border: 1px solid #ffad00;
	width: 30em
}

fieldset
{
	border: 1px solid #ffffff;
	width: 30em
}

legend
{
	color: #fff;
	background: #61991C;
	border: 1px solid #61991C;
	padding: 4px 6px
}

/* begin table styles */  
table {  
    border-collapse: collapse;  
    width: auto;  
}  

/* boxes for solar calculator selection */  
p.box{
	border-style:solid;
	border-width:medium;
	border-color: #FFCC00;
	max-width: 17em;
}

/* banners for different pages */
#bannerelect
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerele.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerfit {
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerfit.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannersolar 
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannersolar.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerresult
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerresult.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerwelcome
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerwelcome.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerprofile
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerprofile.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannercontact
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannercontact.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerreg
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerreg.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerlogin
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerlogin.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerstats
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerstats.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerforum
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerforum.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerabout
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerabout.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerreadings
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerread.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerdaily
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerdaily.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannerweekly
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannerweekly.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#bannermonthly
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/bannermonthly.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

#banneryearly
{
	height: 16%;
	max-width: 750px;
	margin: 0 auto;
	background-image:  url('htdocs/images/banner/banneryearly.png');
	background-repeat:no-repeat;
	background-size: contain;
	background-position:center;
}

/*the form */
.theform
{
	margin: 0 auto;
	width: 500px;
	padding: 15px;
}

/* style the form */
#styleform 
{
	border:solid 2px #ffad00;
	background:#FFFF99;
}

#styleform p
{
	font-size:11px;
	color:#666666;
	border-bottom:solid 1px #ffad00;
	padding-bottom: 10px;
}

#styleform label
{
	display:block;
	font-weight:bold;
	text-align:left;
	width:140px;
	float:left;
}

#styleform input
{
	width:200px;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #ffad00;
	width:200px;
	margin:2px 0 10px 10px;
	color: #0000000;
	background: #FFFFFFF;
}

#styleform textarea
{
	width:200px;
	font-size:12px;
	border:solid 1px #ffad00;
	margin:2px 0 10px 10px;
	background: #FFFFFFF;
}
