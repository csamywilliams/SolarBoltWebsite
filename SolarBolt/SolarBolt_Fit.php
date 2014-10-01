<?php //SolarBolt_Fit.php
include 'SolarBolt_DesignHeader.php';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery-1.8.3.min.js" ></script>

<script type ="text/javascript">

	var gallery = ['htdocs/images/hint/hint.gif',
					'htdocs/images/hint/hint1.gif',
					'htdocs/images/hint/hint2.gif',
					'htdocs/images/hint/hint3.gif',
					'htdocs/images/hint/hint4.gif',
					'htdocs/images/hint/hint5.gif'];
					
	var counter = gallery.length;
	
	var fitgallery = ['htdocs/images/fit/exporttariff.gif',
					  'htdocs/images/fit/importtariff.gif',
					  'htdocs/images/fit/gentariff.gif'];
					
	var fitcounter = fitgallery.length;
					
	$(function() {
		setInterval(hidePic, 7050);
		setInterval(showGallery, 7000);
		setInterval(fitGallery, 7000);
	});
	
	function hidePic(){
		document.getElementById("gentariff").style.display="none";
	}
		
	function showGallery(){	
		document.getElementById("imageGallery").style.display="block";
		$('#imageGallery').fadeOut("slow", function(){
			$(this).attr('src',gallery[(gallery.length++) % counter]).fadeIn("slow");
		});
	}
	
	function fitGallery(){	
		document.getElementById("fitGallery").style.visibility = "block";	
		$('#fitGallery').fadeOut("slow", function(){
			$(this).attr('src', fitgallery[(fitgallery.length++) % fitcounter]).fadeIn("slow");
		});
	}
		
</script>

</head>
<body>

<div id="bannerfit"></div>

<div id = "content">

In April 2010, the Government introduced the Feed In Tariff Scheme
which encourages people to start generating and using their own renewable energy. 
In this section you will be able to find out how the Feed in Tariff works, 
the tariff rates and the energy performance certificates (EPC). </br></br>

<h2> How do FITs Work? </h2>
The solar panel's fitted create electricity from the sun's rays. Your energy supplier will pay you a set rate for every unit generated. 
In your household you can use the electricity you have generated or export it back to the electricity grid if you have any excess.
Your energy supplier will also give you an export tariff rate for each unit exported. 
If any additional electricity is required then you will need to import it from the grid at additional costs. </br></br>


<img id = "gentariff" src="htdocs/images/fit/gentariff.gif" style="block" ><img id = "fitGallery" alt "" src "" style="block"/> 

</br></br>
	<h3> 1. A Generation Tariff: </h3>
	For each unit generated from your solar PV system, your energy supplier will pay you a set rate for every unit. 
	It is based on the total amount of electricity generated and the energy type. </br>
	</br>
	<h3> 2. An Export Tariff: </h3>
	For any excess electricity that is imported back to the grid, your energy supplier will also give 4.5p/kWh per unit. </br>
	</br>
	<h3> 3. An Import Tariff: </h3>
	If the owner requires more electricity then it can be imported from the electricity grid at a given rate depending on your electricity supplier. </br>
	</br>
	<h3> 4. Savings on Energy Bills: </h3>
	by using this incentive you will save money because you will not have to import as much electricity from your energy supplies to power some of  your appliances.</br></br>
	
<h2> Feed In Tariff Rates </h2>

When the system is installed and you are eligible for the feed-in tariff, you will then get paid a certain rate depending on the date and 
size of your system. The table below summarises the latest tariffs available. For the full list of feed-in tariff rates please visit the official
Ofgem website for more information.
 <a class = 'ofgem' href="http://www.ofgem.gov.uk/Sustainability/Environment/fits/tariff-tables/Pages/index.aspx"> Click Here </a>


<img id = "fitrates" src="htdocs/images/fitrates.png" style="block" ><img id = "fitrates" alt "" src "" style="block"/> 

<h2> Eligibility & Energy Performance Certificate (EPC) </h2>

To be eligible for the Feed-in Tariff incentive your property has to have a Energy Performance Certificate (EPC).  </br>
Depending on the band of your EPC it will mean that either your property is successful for the incentive or you will need to do
additional energy improvements before you can apply for a FIT. These new rules were brought out on the 1st April 2012.  </br>

If your EPC is band E,F or G it will mean you need to carry out some energy improvements.  
Please note even if energy efficient improvements are carried out you are not guaranteed the standard 
rate but could receive the lower rate. </br>

If the EPC band is D or higher then you can apply for a FIT at the standard rate. For more information on EPC bands I recommend this website
<a class = 'energysavingtrust' href="http://www.energysavingtrust.org.uk/Generating-energy/Getting-money-back/Feed-In-Tariffs-scheme-FITs/Energy-Performance-Certificates-and-the-Feed-in-Tariff"> Click Here </a>

</br>
<img id = "imageGallery" alt "" src "" style = "block"/> 

</br></div>

</body>


</html>
