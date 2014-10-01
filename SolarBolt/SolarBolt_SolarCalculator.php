<?php // SolarBolt_SolarCalculator.php
include 'SolarBolt_DesignHeader.php';

function getResults()
{
	//get form field values
	if (isset($_POST['submit']))
	{
		//sanitizes the values
		$size = sanitizeCheck($_POST['size']);			//sanitize the string
		$tilt = sanitizeCheck($_POST['tilt']);			
		$orientation = sanitizeCheck($_POST['orientation']);	
		$shading = sanitizeCheck($_POST['shading']);
		$band = sanitizeCheck($_POST['band']);
	
		//checks if the EPC band is E or lower, it so cannot calculate FIT
		if ($band == 'E')
		{
			echo "<div id = 'content'> I'm sorry you're system is
			not eligible for the Feed-In Tariff incentive, 
			due to it's EPC band. 
			Therefore the calculation could not be perfomed.</br>
			For more information on Eligibility please visit 
			Energy Saving Trust website. <a href=
			'http://www.energysavingtrust.org.uk/Generating-energy/
			Getting-money-back/Feed-In-Tariffs-scheme-FITs/
			Energy-Performance-Certificates-and-the-Feed-in-Tariff'>
			Click Here </a> </div";
			
		} else {
			//check if fields are empty
			if(empty($size) || empty($tilt) 
				|| empty($orientation) 
				|| empty($shading) || empty($band)
			) {
				echo "<div id = 'content'>
					Please enter all fields
					</div>";
			} else {
				//get solar irradiance values and shading factor value
				$si = getSolarIrradiance($tilt, $orientation);
				$zpv = getShadingFactor($shading);
				//set reducing factor 0.8
				$rf = 0.8;
				//calculate size of system
				$kwp = $size/1000;

				echo "<div id = 'content'>";
				//calculate SAP calculation, format to 2.dp
				$sapcalc = round($kwp * $rf * $si * $zpv, 2);
				$sap = number_format($sapcalc, 2, '.', '');
		
				//feed in tariff rate
				$fit = 0.1544;
				$feedintariff = $sapcalc * $fit;
				$feedintariff = number_format($feedintariff, 2, '.', '');	

				//calculate usage
				$use = 0.14;
				$save = number_format($sapcalc * $use,2,'.','');
	
				//calculate year saving
				$yield = number_format($feedintariff + $save, 2, '.','');
	
				//calculate 25 year saving
				$lifetime = 25;
				$twentyfiveyield = number_format($yield * $lifetime, 2, '.','');
	
				echo "<ul>
				<li> Feed In Tariff rate (whether the 
				electricity is used or not) is : <b> 
				&pound$feedintariff </b> per year </li>
				<li> Assuming all electricity is 
				used, the amount you save 
				per year is : <b> &pound$save </b></li>
				<li> Total Yield per year:
				<b> &pound$yield </b> </li>
				<li> In 25 years you could save: 
				<b> &pound$twentyfiveyield </b> </li>
				<ul></div>";
							
				//show output in visualisations
				getFitSaving($feedintariff, $save);
				getYield($yield, $twentyfiveyield);
						
			}
		}
	} else {
		echo "<div id = 'content'> I'm sorry but you will have 
									to resubmit the form please 
			<a href='SolarBolt_Calculator.php'> Click Here </a> </div>";
	}
}
	
/*
* Function to get shading factor from database
* @param $shading - shading value
* @return $zpv - return the shading value
*/
function getShadingFactor($shading)
{
	$col = 'overshadingfactor';
	$shading = "SELECT `overshadingfactor` FROM shadingfactor 
				WHERE `overshading` = '$shading'";
	$res = queryMysql($shading);
	$zpv = getField($res, $col);
	return $zpv;
}
	
/*
* Function to get solar irradiance value
* @param $tilt - tilt angle
* @param $orientation - household orientation
* @return $si- return solar irradiance value
*/
function getSolarIrradiance($tilt, $orientation)
{
	$id = 0;

	$tiltId = "SELECT `tiltid` FROM tilt 
				WHERE `tilt` = '$tilt'";
	$res = queryMysql($tiltId);	
	$tiltGetId = getField($res, $id);
		
	$orientId = "SELECT `orientid` FROM orientation 
				WHERE `orientation` = '$orientation'";
	$res1 = queryMysql($orientId);
	$orientGetId = getField($res1, $id);
	
	$rad = "SELECT `radiation` FROM radiation 
			WHERE `tiltid` = '$tiltGetId'
			AND `orientid` = '$orientGetId'";
	$res2 = queryMysql($rad);
	$si = getField($res2, $id);
	
	return $si;
}

/*
* Function to get data values for the saving and fit values
* @param $fit - fit rate
* @param $save - amount saved
*/
function getFitSaving($fit, $save)
{
	$resValues = array();
	$result = array();
	
	$colone = 'Savings';
	$data = getGraphLabels($colone);
	array_push($result, $data);
			
	$series = getGraphData($fit, 'Feed In Tariff', '#9de24f');
	$series1 = getGraphData($save, 'Saving ', '#9aefda');
				
	array_push($resValues, $series);
	array_push($resValues, $series1);
				
	$resValues = json_encode($resValues, JSON_NUMERIC_CHECK);
	$result = json_encode($data, JSON_NUMERIC_CHECK);
	
	$title = 'Feed In Tariff and Saving Amount';
	$renderTo = 'container';		
	showGraph($resValues, $result, $renderTo, $title);
}


/*
* Function to get data values for the yield
* @param $yield - one year yield
* @param $totalyield - twenty five year yield
*/
function getYield($yield, $totalyield)
{
	$resValues = array();
	$result = array();
	
	$colone = 'Yield';
	$data = getGraphLabels($colone);
	array_push($result, $data);
			
	$series = getGraphData($yield, '1 Year Yield', '#66ccff');
	$series1 = getGraphData($totalyield, '25 Year Yield', '#cc0099');
				
	array_push($resValues, $series);
	array_push($resValues, $series1);
				
	$resValues = json_encode($resValues, JSON_NUMERIC_CHECK);
	$result = json_encode($data, JSON_NUMERIC_CHECK);
	
	$renderTo = 'contain';
	$title = 'Total Saving in 1 and 25 Years';
	showGraph($resValues, $result, $renderTo, $title);
}


/*
* Function to get data values
* @param $value - data values
* @param $name - value name
* @param $color - colour for chart
* @return $series - return array of data
*/
function getGraphData($value, $name, $color)
{
	$series['name'] = $name;
	$series['color'] = $color;
	$series['data'][] = $value; 
	
	return $series;
}

/*
* Function to get data labels 
* @param $col - column name
* @return $series - return array of data
*/
function getGraphLabels($col)
{
	$series['categories'][] = $col;
	
	return $series;
}

function showGraph($res, $result, $renderTo, $title)
{

echo <<<_END

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
	<meta http-equiv="Content-Type" content="text/html; 
	charset=utf-8"/> 
	<title>Results</title>

<script type="text/javascript" src="js/jquery-1.8.3.min.js">
</script>
<script type="text/javascript" src="js/highcharts.js">
</script>

<script type="text/javascript">

$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: $renderTo,
                type: 'column'

            },
            title: {
                text: '$title'
            },
            xAxis: $result,
			 
	
            yAxis: {
                min: 0,
                title: {
                    text: 'GBP'
                }
            },
            legend: {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                align: 'right',
                verticalAlign: 'top',
                x: 0,
                y: 30,
                floating: true,
                shadow: true
            },
            tooltip: {
                formatter: function() {
                    return ''+ this.series.name + 
                     ': '+ this.y + ' GBP';
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
					events: {
						legendItemClick: function () {
							return false; 
					}
				}
            }
            },
                series: $res,
        });
    });
    
});

</script>
</head>
	<body>
		<div id = "content">
			<div id="$renderTo" style="width: 100%; height: 50%;
					margin: 0 auto"></div>
		</div>
	</body>
</html>
_END;


}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<div id = "bannerresult"> </div>

<div id ="content">

<h2> How Much Can You Save? </h2>

<?php echo getResults(); ?>

</br></br>
<h3><i> Disclaimer </i> </h3>
<i>
The performance of solar pv systems is impossible to predict with certainty due to the the diverse amount of sunlight from different locations.
This estimate is based upon the Government's official calculation methodoly called SAP. It stands for 'standard assessment procedure'. It takes
in account of system size, orientation, shading and tilt but does not take in account of latitude therefore it is only a estimate and should not be considered
as a guarantee of performance.
</i>
</div>


</body>

</html>