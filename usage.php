<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Taqweem Masajid</title>
	<?php include_once("menu.php");?>
	
	<?php require_once('Connections/SQL.php');?>
	<?php
$sql = "SELECT COUNTRY, COUNT(COUNTRY) AS CountryTotal FROM MASJID GROUP BY COUNTRY";
$result = $conn->query($sql);

$sql = "SELECT (SELECT COUNT( * ) FROM MASJID) AS MasjidCount, (SELECT COUNT( * ) FROM USER) AS UserCount, (SELECT COUNT(DISTINCT(MASJID_ID)) FROM MASJID_TIME) AS MTime FROM dual";
$result2 = $conn->query($sql);

$conn->close();
?>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		
		<script type="text/javascript">
$(function () {

    $(document).ready(function () {

        // Build the chart
        $('#chart-A').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Total Masjid Per Country'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> - <b>{point.y}</b> Masajid'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Masajid',
                colorByPoint: true,
                data: [
				<?php
				while($row = $result->fetch_assoc()) {
				$result_names .= "{ name: '" .  $row["COUNTRY"] . "', y:" . $row["CountryTotal"] . " }," ;
				} echo rtrim($result_names, ',');; ?>
				]
            }]
        });
    });
});
		</script>
		
				<script type="text/javascript">
$(function () {
    $('#chart-B').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Website Usage'
        },
        xAxis: {
            categories: [
                'Usage',
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [<?php
			$result_names = null;
				while($row = $result2->fetch_assoc()) {
				$result_names .= "{ name: 'Masjid', data:[" . $row["MasjidCount"] . "] }," ;
				$result_names .= "{ name: 'Users', data:[" . $row["UserCount"] . "] }," ;
				$result_names .= "{ name: 'Masjid Times', data:[" . $row["MTime"] . "] }," ;
				} echo rtrim($result_names, ',');; ?>
		
		]
    });
});
		</script>
		
		
	</head>
	<body>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="chart-A" style="min-width: 600px; height: 400px; max-width: 600px; margin: 0 auto"></div>
<div class="spacer"></div>
<div id="chart-B" style="min-width: 600px; height: 400px; max-width: 600px; margin: 0 auto"></div>
	</body>
</html>
