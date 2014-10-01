<?php //getSplineGraphView.php

/*
* display a splibe graph visualisation
* @param $res - data samples for the graph
* @param $result - data labels for the grap
* @param $tip - tooltip value
* @param $subtitle - subtitle for graph
* @param $title - for graph
* @param $link - link name information
*/
function getSplineGraph($res, $result, $tip, 
			$subtitle, $title, $link
) {
echo <<<_END

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 
<title>$link</title>

<script type="text/javascript" src="js/jquery-1.8.3.min.js" ></script>
<script type="text/javascript" src="js/highcharts.js" ></script>

<script type="text/javascript">

$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
				type: 'spline',
            },
            title: {
                text: '$title $subtitle'
            },
            xAxis:  $result,
            yAxis: {
                title: {
                    text: '$tip'
                },
                showFirstLabel: false
            },
            tooltip: {
                shared: true
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: { 
							x1: 0,
							y1: 0, 
							x2: 0, 
							y2: 1
						},
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, 'rgba(2,0,0,0)']
                        ]
                    },
                    lineWidth: 0.5,
                    marker: {
                        enabled: false,
                        states: {
                            hover: {
                                enabled: true,
                                radius: 3
                            }
                        }
                    },
                    shadow: false,
                    states: {
                        hover: {
                            lineWidth: 0.5
                        }
                    },
                    threshold: null
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
<center><div id="container" style="width: 100%; height: 50%; margin: 0 auto"></div>
<a class='link' href='?page=1'>$link Usage</a> <bar> | </bar>
<a class='link' href='?page=2'>$link Consumption</a> <bar> | </bar>
<a class='link' href='?page=3'>$link Feed In Tariff Credit</a></center>
</div>
</body>
</html>
_END;
}

?>
