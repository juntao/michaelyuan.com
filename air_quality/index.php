<?php
    $file = 'data.json';
    $json_str = file_get_contents($file);
    $json = array ();
    if (!empty($json_str)) {
        $json = json_decode($json_str);
    }

    if (!empty($_REQUEST["d"])) {
        if ($_REQUEST["p"] != 'TNByqaJaXYA5r62Y') {
            exit;
        }
        if (floatval($_REQUEST["d"]) > 90) {
            // This means LPO > 60% Probably noise here ...
            exit;
        }

        $current = array(round(microtime(true) * 1000), floatval($_REQUEST["d"]));
        array_push($json, $current);
        file_put_contents($file, json_encode($json));
        exit;
    } else {
?>
<!DOCTYPE html>
<html>
    <head>
        <script src="https://code.jquery.com/jquery-3.1.1.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script>
$(function () { 
        Highcharts.chart('container', {
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'PM2.5 Readings Over Time'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: 'ug/m^3'
                }
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
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },

            series: [{
                type: 'area',
                name: 'PM2.5',
                data: <?= $json_str ?>
            }]
        });
});
        </script>
    </head>
    <body>
        <div id="container" style="min-width: 910px; height: 400px; margin: 0 auto"></div>
    </body>
</html>
<?php
    }
?>
