<?php // SolarBolt_Calculator.php
include 'SolarBolt_DesignHeader.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type ="text/javascript" src="val.js"></script>
</head>
<body>
<div id = "bannersolar"> </div>
<div id = "content">

<h3> Using the Solar Energy Calculator </h3>
</br></br>
Calculate the amount of energy from the solar calculator. All fields are required.

<form name = "calculator" form action="SolarBolt_SolarCalculator.php" method="post">
<pre>
<p class = "box"><label for="size">System Size	</label>	
	<img id = "band" src="htdocs/images/size.png" style="block" ><img id = "size" alt "" src "" style="block"/> 
<select name="size"/>
				<option value="1500"> Small (6 - 10 panels) </option>
				<option value="2500"> Medium (10 - 14 panels) </option>
				<option value="3000"> Large (15 - 18 panels) </option>
				<option value="4000"> Extra Large (21 - 22 panels) </option>
				</select></p>
<p class = "box"><label for="orientation">Orientation	</label>
   <img id = "shad" src="htdocs/images/compass.png" style="block" ><img id = "shading" alt "" src "" style="block"/> 
<select name="orientation"/>
				<option value="north"> North </option>
				<option value="northwest/east"> North West/ North East </option>
				<option value="east/west"> East/West </option>
				<option value="south"> South </option>
				<option value="southeast/west"> South East/South West </option>
				</select></p>
<p class = "box"><label for="tilt">Tilt	</label>
	<img id = "band" src="htdocs/images/tilt.png" style="block" ><img id = "tilt" alt "" src "" style="block"/>
<select name="tilt"/>
				<option value="Horizontal"> Horizontal </option>
				<option value="30"> 30 </option>
				<option value="45"> 45 </option>
				<option value="60"> 60 </option>
				<option value="Vertical"> Vertical </option></select></p>			
<p class = "box"><label for="shading"> Shading	</label>		
	<img id = "shad" src="htdocs/images/shading.png" style="block" ><img id = "shading" alt "" src "" style="block"/> 
<select name="shading"/>
				<option value="none"> No shading </option>
				<option value="partial"> Partial shading </option>
				<option value="modest"> Modest shading </option>
				<option value="heavy"> Heavy shading </option></select></p>
<div id = "shading" style="color:#FF0000"></div>
<p class = "box"><label for="band">EPC Band	</label>
	<img id = "band" src="htdocs/images/epcband.png" style="block" ><img id = "epcband" alt "" src "" style="block"/> 
<select name="band"/>
				<option value="A"> A </option>
				<option value="B"> B </option>
				<option value="C"> C </option>
				<option value="D"> D </option>
				<option value="E"> E or lower </option></select></p>
<div id = "band" style="color:#FF0000"></div>
<input type="submit" name='submit' value="Submit" />

</fieldset>
</pre></form>
</div>
</body>
</html>
