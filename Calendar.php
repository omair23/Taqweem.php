<?php
require_once('Connections/SQL.php');

$uid=$_GET['ID'];

if ( is_numeric( $uid ) )	{
    $uid= $uid;
}
// return False if we don't get a number
else
{
    echo "<script type='text/javascript'>
	window.location = 'index.php';
                                    </script>";
}

$sql = "SELECT * FROM MASJID WHERE ID=" . $_GET['ID'];
$result = $conn->query($sql);
if ($result->num_rows > 0) {

    while ($row = @mysqli_fetch_assoc($result)){
        $NAME = $row["NAME"];
        $LATITUDE = $row["LATITUDE"];
        $LONGITUDE = $row["LONGITUDE"];
        $TIMEZONE = $row["TIMEZONE"];
        $HASL = $row["HASL"];
        $JURISTIC_METHOD = $row["JURISTIC_METHOD"];
    }
} else {
    echo "0 results";
}
//$conn->close();
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Taqweem Masajid</title>
	<?php include_once("menu.php");?>
	
<?php
	$FajrPerpetual = [[]];;
    include('SalaahTime.php');
    $ParamDayNumber = 0;
    $RealDate;
    $PrevDate = "2015-12-31";
    for ($i = 1; $i <= 366; $i++){
        $ParamDayNumber += 1;
        $Date =  "2015-12-31";
        $RealDate = date('Y-m-d', strtotime($Date. ' + ' . $ParamDayNumber .' days'));
        $Date += (($ParamDayNumber - 1) * 24 * 60 * 60);
        $salaahTime = new SalaahTime();
        $salaahTime->SetData($ParamDayNumber, $Date, $LATITUDE, $LONGITUDE, $TIMEZONE,$HASL, $JURISTIC_METHOD);
        $Zawaal = $salaahTime->CalcZawaal();
        $Dhuhr =  $salaahTime->CalcDhuhr();
        $Sunrise = $salaahTime->CalcSunrise();
        $Sunset = $salaahTime->CalcSunset();
        $Ishraaq = $salaahTime->CalcIshraaq();
        $Maghrib = $salaahTime->CalcMaghrib();
        $SehriEnds = $salaahTime->CalcSehriEnds();
        $Asar1 = $salaahTime->CalcAsar1();
        $Asar2 = $salaahTime->CalcAsar2();
        $Fajr = $salaahTime->CalcFajr();
        $Isha = $salaahTime->CalcIsha();

		$LoopMonth = date("m", strtotime($RealDate)) - 1 ;
		$LoopDay = date("j", strtotime($RealDate)) ;
		
		$Asar2 = (explode(":",$Asar2));
		$Asar2Perpetual[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$Asar2Perpetual[$i][1] = "Date.UTC(2016, 0 , 1, $Asar2[0], $Asar2[1],0)" ;
		$Asar2Perpetual[$i][2] =  "[" . $Asar2Perpetual[$i][0] . ", " . $Asar2Perpetual[$i][1] ."],";
		
		$Asar1 = (explode(":",$Asar1));
		$Asar1Perpetual[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$Asar1Perpetual[$i][1] = "Date.UTC(2016, 0 , 1, $Asar1[0], $Asar1[1],0)" ;
		$Asar1Perpetual[$i][2] =  "[" . $Asar1Perpetual[$i][0] . ", " . $Asar1Perpetual[$i][1] ."],";
		
		$Maghrib = (explode(":",$Maghrib));
		$MaghribPerpetual[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$MaghribPerpetual[$i][1] = "Date.UTC(2016, 0 , 1, $Maghrib[0], $Maghrib[1],0)" ;
		$MaghribPerpetual[$i][2] =  "[" . $MaghribPerpetual[$i][0] . ", " . $MaghribPerpetual[$i][1] ."],";
		
		$Sunset = (explode(":",$Sunset));
		$SunsetPerpetual[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$SunsetPerpetual[$i][1] = "Date.UTC(2016, 0 , 1, $Sunset[0], $Sunset[1],0)" ;
		$SunsetPerpetual[$i][2] =  "[" . $SunsetPerpetual[$i][0] . ", " . $SunsetPerpetual[$i][1] ."],";
		
		
		$Zawaal = (explode(":",$Zawaal));
		$ZawaalPerpetual[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$ZawaalPerpetual[$i][1] = "Date.UTC(2016, 0 , 1, $Zawaal[0], $Zawaal[1],0)" ;
		$ZawaalPerpetual[$i][2] =  "[" . $ZawaalPerpetual[$i][0] . ", " . $ZawaalPerpetual[$i][1] ."],";
		
	    $Sunrise = (explode(":",$Sunrise));
		$SunrisePerpetual[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$SunrisePerpetual[$i][1] = "Date.UTC(2016, 0 , 1, $Sunrise[0], $Sunrise[1],0)" ;
		$SunrisePerpetual[$i][2] =  "[" . $SunrisePerpetual[$i][0] . ", " . $SunrisePerpetual[$i][1] ."],";
		
		$Fajr = (explode(":",$Fajr));
		$FajrPerpetual[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$FajrPerpetual[$i][1] = "Date.UTC(2016, 0 , 1, $Fajr[0], $Fajr[1],0)" ;
		$FajrPerpetual[$i][2] =  "[" . $FajrPerpetual[$i][0] . ", " . $FajrPerpetual[$i][1] ."],";
	
		$Isha = (explode(":",$Isha));
		$IshaPerpetual[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$IshaPerpetual[$i][1] = "Date.UTC(2016, 0 , 1, $Isha[0], $Isha[1],0)" ;
		$IshaPerpetual[$i][2] =  "[" . $IshaPerpetual[$i][0] . ", " . $IshaPerpetual[$i][1] ."],";
		
		
		///////////// SALAAH TIMES SECTION ////////
		$sql = "SELECT * FROM MASJID_TIME WHERE MASJID_ID='$uid' AND DAY_NUMBER='$i'";
        $result = $conn->query($sql);
		
		$HasSalaahTimes = false;
		if ($result->num_rows > 0) {
			$HasSalaahTimes = true;
			while($row = $result->fetch_assoc()) {
			
				$FAJR_ADHAAN = date('H:i',strtotime($row["FAJR_ADHAAN"]));
				$FAJR_SALAAH = date('H:i',strtotime($row["FAJR_SALAAH"]));
				$DHUHR_ADHAAN = date('H:i',strtotime($row["DHUHR_ADHAAN"]));
				$DHUHR_SALAAH = date('H:i',strtotime($row["DHUHR_SALAAH"]));
				$ASAR_ADHAAN = date('H:i',strtotime($row["ASAR_ADHAAN"]));
				$ASAR_SALAAH = date('H:i',strtotime($row["ASAR_SALAAH"]));
				$ISHA_ADHAAN = date('H:i',strtotime($row["ISHA_ADHAAN"]));
				$ISHA_SALAAH = date('H:i',strtotime($row["ISHA_SALAAH"]));
			}
		}
		
		$FAJR_ADHAAN = (explode(":",$FAJR_ADHAAN));
		$FajrAdhaan[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$FajrAdhaan[$i][1] = "Date.UTC(2016, 0 , 1, $FAJR_ADHAAN[0], $FAJR_ADHAAN[1],0)" ;
		$FajrAdhaan[$i][2] =  "[" . $FajrAdhaan[$i][0] . ", " . $FajrAdhaan[$i][1] ."],";
		
		$FAJR_SALAAH = (explode(":",$FAJR_SALAAH));
		$FajrSalaah[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$FajrSalaah[$i][1] = "Date.UTC(2016, 0 , 1, $FAJR_SALAAH[0], $FAJR_SALAAH[1],0)" ;
		$FajrSalaah[$i][2] =  "[" . $FajrSalaah[$i][0] . ", " . $FajrSalaah[$i][1] ."],";
		
		$DHUHR_ADHAAN = (explode(":",$DHUHR_ADHAAN));
		$DhuhrAdhaan[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$DhuhrAdhaan[$i][1] = "Date.UTC(2016, 0 , 1, $DHUHR_ADHAAN[0], $DHUHR_ADHAAN[1],0)" ;
		$DhuhrAdhaan[$i][2] =  "[" . $DhuhrAdhaan[$i][0] . ", " . $DhuhrAdhaan[$i][1] ."],";
		
		$DHUHR_SALAAH = (explode(":",$DHUHR_SALAAH));
		$DhuhrSalaah[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$DhuhrSalaah[$i][1] = "Date.UTC(2016, 0 , 1, $DHUHR_SALAAH[0], $DHUHR_SALAAH[1],0)" ;
		$DhuhrSalaah[$i][2] =  "[" . $DhuhrSalaah[$i][0] . ", " . $DhuhrSalaah[$i][1] ."],";
		
		$ASAR_ADHAAN = (explode(":",$ASAR_ADHAAN));
		$AsarAdhaan[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$AsarAdhaan[$i][1] = "Date.UTC(2016, 0 , 1, $ASAR_ADHAAN[0], $ASAR_ADHAAN[1],0)" ;
		$AsarAdhaan[$i][2] =  "[" . $AsarAdhaan[$i][0] . ", " . $AsarAdhaan[$i][1] ."],";
		
		$ASAR_SALAAH = (explode(":",$ASAR_SALAAH));
		$AsarSalaah[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$AsarSalaah[$i][1] = "Date.UTC(2016, 0 , 1, $ASAR_SALAAH[0], $ASAR_SALAAH[1],0)" ;
		$AsarSalaah[$i][2] =  "[" . $AsarSalaah[$i][0] . ", " . $AsarSalaah[$i][1] ."],";
		
		$ISHA_ADHAAN = (explode(":",$ISHA_ADHAAN));
		$IshaAdhaan[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$IshaAdhaan[$i][1] = "Date.UTC(2016, 0 , 1, $ISHA_ADHAAN[0], $ISHA_ADHAAN[1],0)" ;
		$IshaAdhaan[$i][2] =  "[" . $IshaAdhaan[$i][0] . ", " . $IshaAdhaan[$i][1] ."],";
		
		$ISHA_SALAAH = (explode(":",$ISHA_SALAAH));
		$IshaSalaah[$i][0] = "Date.UTC(2016, $LoopMonth , $LoopDay)" ;
		$IshaSalaah[$i][1] = "Date.UTC(2016, 0 , 1, $ISHA_SALAAH[0], $ISHA_SALAAH[1],0)" ;
		$IshaSalaah[$i][2] =  "[" . $IshaSalaah[$i][0] . ", " . $IshaSalaah[$i][1] ."],";
		
		
        }
		
					foreach ($FajrAdhaan as &$value) {
				$FajrAdhaanGraph .= $value[2] ;}
			$FajrAdhaanG = "{name: 'Fajr Adhaan',data: [" . rtrim($FajrAdhaanGraph, ',') . "]}";
			foreach ($FajrSalaah as &$value) {
				$FajrSalaahGraph .= $value[2] ;}
			$FajrSalaahG = "{name: 'Fajr Salaah',data: [" . rtrim($FajrSalaahGraph, ',') . "]}";
			foreach ($DhuhrAdhaan as &$value) {
				$DhuhrAdhaanGraph .= $value[2] ;}
			$DhuhrAdhaanG = "{name: 'Dhuhr Adhaan',data: [" . rtrim($DhuhrAdhaanGraph, ',') . "]}";
			foreach ($DhuhrSalaah as &$value) {
				$DhuhrSalaahGraph .= $value[2] ;}
			$DhuhrSalaahG = "{name: 'Dhuhr Salaah',data: [" . rtrim($DhuhrSalaahGraph, ',') . "]}";
									foreach ($AsarAdhaan as &$value) {
				$AsarAdhaanGraph .= $value[2] ;}
			$AsarAdhaanG = "{name: 'Asr Adhaan',data: [" . rtrim($AsarAdhaanGraph, ',') . "]}";
			foreach ($AsarSalaah as &$value) {
				$AsarSalaahGraph .= $value[2] ;}
			$AsarSalaahG = "{name: 'Asr Salaah',data: [" . rtrim($AsarSalaahGraph, ',') . "]}";
			foreach ($IshaAdhaan as &$value) {
				$IshaAdhaanGraph .= $value[2] ;}
			$IshaAdhaanG = "{name: 'Isha Adhaan',data: [" . rtrim($IshaAdhaanGraph, ',') . "]}";
			foreach ($IshaSalaah as &$value) {
				$IshaSalaahGraph .= $value[2] ;}
			$IshaSalaahG = "{name: 'Isha Salaah',data: [" . rtrim($IshaSalaahGraph, ',') . "]}";
	
	
?>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Perpetual and Salaah Times - <?php echo $NAME ?>'
        },
        subtitle: {
            text: 'Source: Taqweem - Rapidsoft'
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Date'
            }
        },
        yAxis: {
			type: 'datetime',
			dateTimeLabelFormats: { // don't display the dummy year
                Hour: '%H',
                Minute: '%M',
				day: '.'
            },
            title: {
                text: 'Time'
            },
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%b %e}: {point.y:%H:%M}'
        },

        plotOptions: {
            spline: {
                marker: {
                    enabled: true
                }
            }
        },

        series: [{
            name: 'Sunrise',
			data: [
			<?php 		foreach ($SunrisePerpetual as &$value) {
			$SunriseGraph .= $value[2] ;}
			echo rtrim($SunriseGraph, ',');?>
            ]
        },{
            name: 'Fajr Perpetual',
			data: [
			<?php 		foreach ($FajrPerpetual as &$value) {
			$FajrGraph .= $value[2] ;}
			echo rtrim($FajrGraph, ',');?>
            ]
        },{
            name: 'Zawaal',
			data: [
			<?php 		foreach ($ZawaalPerpetual as &$value) {
			$ZawaalGraph .= $value[2] ;}
			echo rtrim($ZawaalGraph, ',');?>
            ]
        },{
            name: 'Asr Shaafi',
			data: [
			<?php 		foreach ($Asar1Perpetual as &$value) {
			$Asar1Graph .= $value[2] ;}
			echo rtrim($Asar1Graph, ',');?>
            ]
        },{
            name: 'Asr Hanafi',
			data: [
			<?php 		foreach ($Asar2Perpetual as &$value) {
			$Asar2Graph .= $value[2] ;}
			echo rtrim($Asar2Graph, ',');?>
            ]
        },{
            name: 'Sunset',
			data: [
			<?php 		foreach ($SunsetPerpetual as &$value) {
			$SunsetGraph .= $value[2] ;}
			echo rtrim($SunsetGraph, ',');?>
            ]
        },{
            name: 'Maghrib',
			data: [
			<?php 		foreach ($MaghribPerpetual as &$value) {
			$MaghribGraph .= $value[2] ;}
			echo rtrim($MaghribGraph, ',');?>
            ]
        },{
            name: 'Isha Perpetual',
			data: [
			<?php 		foreach ($IshaPerpetual as &$value) {
			$IshaGraph .= $value[2] ;}
			echo rtrim($IshaGraph, ',');?>
            ]
        }<?php if ($HasSalaahTimes == true) { 
			echo "," . $FajrAdhaanG;
			echo "," . $FajrSalaahG;
			echo "," . $DhuhrAdhaanG;
			echo "," . $DhuhrSalaahG;
			echo "," . $AsarAdhaanG;
			echo "," . $AsarSalaahG;
			echo "," . $IshaAdhaanG;		
			echo "," . $IshaSalaahG;
		}?>]
    });
});
		</script>

	</head>
	<body>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 600px; margin: 0 auto"></div>

	</body>
</html>
