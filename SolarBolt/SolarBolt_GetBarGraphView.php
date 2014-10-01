<?php //SolarBolt_GetGraphView.php

/*
* display a bar graph visualisation
* @param $res - data samples for the graph
* @param $result - data labels for the grap
* @param $tip - tooltip value
* @param $subtitle - subtitle for graph
* @param $title - for graph
* @param $link - link name information
*/
function getGraph($res, $result, $tip, 
			$subtitle, $title, $link
) {
echo <<<_END

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
	<meta http-equiv="Content-Type" content="text/html; 
	charset=utf-8"/> 
	<title>$link</title>

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
                renderTo: 'container',
                type: 'column'

            },
            title: {
                text: '$title $subtitle'
            },
            xAxis: $result,
			 
	
            yAxis: {
                min: 0,
                title: {
                    text: '$tip'
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
                    return ''+ this.series.name + ' : ' +
                        this.x +': '+ this.y +' $tip';
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
		<div id = "banner$link"></div>
		<div id = "content">
			<a class='link' href='SolarBolt_Stats.php'>Back</a>
			<center>
			<div id="container" style="width: 100%; height: 50%;
					margin: 0 auto"></div>
			<a class='link' href='?page=1'>
					$link Usage</a> <bar> | </bar>
			<a class='link' href='?page=2'>
					$link Consumption</a> <bar> | </bar>
			<a class='link' href='?page=3'>
					$link Feed In Tariff Credit</a></center>
		</div>
	</body>
</html>
_END;
}

?>
