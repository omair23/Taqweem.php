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

$sql = "SELECT * FROM MASJID WHERE ID='$uid'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $NAME = $row["NAME"];
        $TOWN = $row["TOWN"];
        $COUNTRY = $row["COUNTRY"];
        $LATITUDE = $row["LATITUDE"];
        $LONGITUDE = $row["LONGITUDE"];
        $TIMEZONE = $row["TIMEZONE"];
        $HASL = $row["HASL"];
        $JURISTIC_METHOD = $row["JURISTIC_METHOD"];
        $LADIES_FACILITY = $row["LADIES_FACILITY"];
        $PHYSICAL_ADDRESS = $row["PHYSICAL_ADDRESS"];
        $CONTACT = $row["CONTACT"];
        $GENERAL_INFO = $row["GENERAL_INFO"];
        $LAST_ACTIVITY = $row["LAST_ACTIVITY"];
        $ALLOW_REG = $row["ALLOW_REG"];
        $JUMMAH = $row["JUMMAH"];
    }
}

$ParamDayNumber = date("z") + 1;
$sql = "SELECT * FROM MASJID_TIME WHERE MASJID_ID='$uid' AND DAY_NUMBER='$ParamDayNumber'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $FAJR_ADHAAN = $row["FAJR_ADHAAN"];
        $FAJR_SALAAH = $row["FAJR_SALAAH"];
        $DHUHR_ADHAAN =$row["DHUHR_ADHAAN"];
        $DHUHR_SALAAH =$row["DHUHR_SALAAH"];
        $ASAR_ADHAAN =$row["ASAR_ADHAAN"];
        $ASAR_SALAAH =$row["ASAR_SALAAH"];
        $ISHA_ADHAAN =$row["ISHA_ADHAAN"];
        $ISHA_SALAAH =$row["ISHA_SALAAH"];
    }
}
$conn->close();

function rad($x){ return $x *  4 * atan(1) / 180; }
$lat1 = $LATITUDE;
$lon1 = $LONGITUDE;
$lat2 = 21.421111111111113 ;
$lon2 = 39.82472222222223 ;
$R = 6371 ; // km
$dLat = rad($lat2 - $lat1);
$dLon = rad($lon2 - $lon1);
$lat1 = rad($lat1);
$lat2 = rad($lat2);
$y = sin($dLon) * cos($lat2);
$x = cos($lat1)*sin($lat2) - sin($lat1)*cos($lat2)*cos($dLon);
$brng = atan2($y, $x) * 180 / (4 * atan(1));
$Q = $brng;
$a = sin($dLat/2) * sin($dLat/2) + sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2);
$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
$da = $R * $c;
$d = floor($da);
$NM = intval($da * 0.539957);
$SM = intval($da * 0.621371);

//$Date =   date('Y-m-d', strtotime($Date. ' + ' . date("z") + 0 . ' days'));
$ParamDayNumber = date("z") + 1;
include('SalaahTime.php');
$Date =  "2015-12-31";
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?PHP echo $NAME. ", " . $TOWN .", " . $COUNTRY ?></title>
    <?php include_once "menu.php" ?>
	 <meta name="description" content="Masjid: <?PHP echo $NAME. ", " . $TOWN .", " . $COUNTRY ?> - Salaah Times - Perpetual Times - Salaah Calendar - Perpetual Calendar - Adhaan - Salaah - Masjid - Map - Fajr - Dhuhr - Asr - Maghrib - Isha" />
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="PrayTimes.js"></script>
    <script type="text/javascript">
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        var map;
        var markers;
        //<![CDATA[

        var customIcons = {
            Yes: {
                icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
            },
            No: {
                icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
            }
        };

        function load() {
            var map = new google.maps.Map(document.getElementById("map"), {
                center: new google.maps.LatLng(<?php echo $LATITUDE; ?> , <?php echo $LONGITUDE; ?>),
                zoom: 15,
                mapTypeId: 'roadmap'
            });
            var infoWindow = new google.maps.InfoWindow;
            geocoder = new google.maps.Geocoder();
            directionsDisplay = new google.maps.DirectionsRenderer();

            // Change this depending on the name of your PHP file
            downloadUrl("phpsqlajax_genxml3.php", function(data) {
                var xml = data.responseXML;
                markers = xml.documentElement.getElementsByTagName("marker");
                for (var i = 0; i < markers.length; i++) {
                    var name = markers[i].getAttribute("Name");
                    var id = markers[i].getAttribute("ID");
                    var town = markers[i].getAttribute("Town");
                    var country = markers[i].getAttribute("Country");
                    var address = markers[i].getAttribute("PhysicalAddress");
                    var type = markers[i].getAttribute("LadiesFacility");
                    var point = new google.maps.LatLng(
                        parseFloat(markers[i].getAttribute("Latitude")),
                        parseFloat(markers[i].getAttribute("Longitude")));
                    var html = "<b>" + name + "</b> <br/>" + town +"<br>" + country + "<br>" + address + "<br> <a href='viewmasjid.php?ID=" + id + "'>View Masjid</a>" ;
                    var icon = customIcons[type] || {};
                    var marker = new google.maps.Marker({
                        map: map,
                        position: point,
                        icon: icon.icon
                    });
                    bindInfoWindow(marker, map, infoWindow, html);
                }
            });

            var contentString2 = "Pointer Masjid";
            var infowindow2 = new google.maps.InfoWindow({
                content: contentString2				});
            var myLatlng2 = new google.maps.LatLng(-28.732617,24.754);


            google.maps.event.addListener(map, 'click', function(event) {
                marker = new google.maps.Marker({position: event.latLng, map: map});

                var lat = event.latLng.lat();
                var lng = event.latLng.lng();
                //alert(lat);
                //alert(lng);

                //get perpetual times

                var str = (event.latLng);
                str = str+''
                str = str.replace('(', '');
                str = str.replace(')', '');
                GPS = str.split(",");
                prayTimes.setMethod('Karachi');
                prayTimes.adjust( {asr: 'Hanafi'} );
                var date = new Date(); // today
                var times = prayTimes.getTimes(date, [GPS[0],GPS[1]], 0);
                var list = ['Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha', 'Midnight'];
                prayTimes.adjust( {asr: 'Standard'} );
                var date = new Date(); // today
                var times2 = prayTimes.getTimes(date, [GPS[0],GPS[1]], 0);
                var list2 = ['Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha', 'Midnight'];
                var html = '<table id="timetable" >';
                html += '<tr><th colspan="2" class="timetable" style ="font-size:14px;">'+ date.toLocaleDateString()+ '</th></tr>';
                html += '<tr><th colspan="2" class="timetable" style ="font-size:14px;">Perpetual Salaah Times For Your Selected Location</th></tr>';
                html += '<tr><th colspan="2" style ="font-weight:normal; padding:5px;">Please note, times are given for GMT(+0) so adjustments have to be made to suit local times</th></tr>';
               
				html += '<tr><th colspan="2" class="timetable " style ="font-weight:normal; text-align:left;">Fajr  '+ times[list[0].toLowerCase()] + '</th></tr>';
                html += '<tr><th colspan="2" class="timetable " style ="font-weight:normal; text-align:left;">Sunrise '+ times[list[1].toLowerCase()] + '</th></tr>';
                html += '<tr><th colspan="2" class="timetable " style ="font-weight:normal; text-align:left;">Dhuhr '+ times[list[2].toLowerCase()] + '</th></tr>';
                html += '<tr><th colspan="2" class="timetable" style ="font-weight:normal; text-align:left;">Asar Shafi '+ times2[list2[3].toLowerCase()] + '</th></tr>';
                html += '<tr><th colspan="2" class="timetable" style ="font-weight:normal; text-align:left;">Asar Hanafi '+ times[list[3].toLowerCase()] + '</th></tr>';
                html += '<tr><th colspan="2" class="timetable" style ="font-weight:normal; text-align:left;">Maghrib '+ times[list[4].toLowerCase()] + '</th></tr>';
                html += '<tr><th colspan="2" class="timetable" style ="font-weight:normal;text-align:left;">Isha '+ times[list[5].toLowerCase()] + '</th></tr>';
                html += '</table>';
				
                document.getElementById('ptimes').innerHTML = html;
                directionsDisplay.setMap(map);
                directionsDisplay.setPanel(document.getElementById("directionsPanel"));

                var pointb;
                pointb = '(';
                pointb += <?php echo $LATITUDE?>;
                pointb += ',';
                pointb += <?php echo $LONGITUDE?>;
                pointb += ')';
                calcRoute(event.latLng,pointb);
            });
        }

        function calcRoute(pointa,pointb) {
            var request = {
                origin: pointa,
                destination: pointb,
                travelMode: google.maps.TravelMode.DRIVING
            };
            directionsService.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                }
            });
        }

        function rad(x) { return x * Math.PI / 180; }

        function bindInfoWindow(marker, map, infoWindow, html) {
            google.maps.event.addListener(marker, 'click', function() {
                infoWindow.setContent(html);
                infoWindow.open(map, marker);
            });
        }

        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    request.onreadystatechange = doNothing;
                    callback(request, request.status);
                }
            };

            request.open('GET', url, true);
            request.send(null);
        }

        function doNothing() {}

        //]]>

    </script>

    <style>
        a.one:link {color:#777;}
        a.one:visited {color:#777;}
        a.one:hover {color:#777;}
        
        }
    </style>
</head>

<body onload="load()">
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">

            <center><h3><?PHP echo $NAME. ", " . $TOWN .", " . $COUNTRY ?></h3></center>
            <center><h4><strong><?php echo date("d - M - Y"); ?></strong></h4></center>
            <div id="currenttime" style="text-align:center; font-weight:bold; font-size:18px;"></div>
            <center><h5>Last Updated: <?php echo $LAST_ACTIVITY ?></h5></center>
        </div>

        <script>
            (function () {
                function checkTime(i) {
                    return (i < 10) ? "0" + i : i;
                }

                function startTime() {
                    var today = new Date(),
                        h = checkTime(today.getHours()),
                        m = checkTime(today.getMinutes()),
                        s = checkTime(today.getSeconds());
                    var n = today.getTimezoneOffset();
                    n = n / -60;
                    document.getElementById('currenttime').innerHTML = h + ":" + m + ":" + s +
                        " </br>" + "Time zone- Masjid: <?php echo number_format($TIMEZONE,2);?> Device: " + n;
                    t = setTimeout(function () {
                        startTime()
                    }, 500);
                }
                startTime();
            })();
        </script>

        <div class="panel-body perpetual-times" >
            <h4 style= "text-align:center;">PERPETUAL TIMES</h4>
            <table class="table table-striped table-condensed " style="background-color: White; border-color: rgb(204, 204, 204); border-width: 1px; border-style: none; border-collapse: collapse; color: Black;">
                <tr><td>SEHRI ENDS</td><td><img src="icons/Suhoor.jpg" width="50px"></td><td><?php echo $SehriEnds; ?></td></tr>
                <tr><td>FAJR</td><td><img src="icons/Fajr.jpg" width="50px"></td><td><?php echo $Fajr ; ?></td></tr>
                <tr><td>SUNRISE</td><td><img src="icons/Sunrise.jpg" width="50px"></td><td><?php echo $Sunrise ; ?></td></tr>
                <tr><td>ISHRAAQ</td><td><img src="icons/Ishraaq.jpg" width="50px"></td><td><?php echo $Ishraaq ; ?></td></tr>
                <tr><td>ZAWAAL</td><td><img src="icons/Dhuhr.jpg" width="50px"></td><td><?php echo $Zawaal ; ?></td></tr>
                <tr><td>DHUHR</td><td><img src="icons/Dhuhr.jpg" width="50px"></td><td><?php echo $Dhuhr ; ?></td></tr>
                <tr><td>ASR SHAFI</td><td><img src="icons/Asar1.jpg" width="50px"></td><td><?php echo $Asar1 ; ?></td></tr>
                <tr><td>ASR HANAFI</td><td><img src="icons/Asar2.jpg" width="50px"></td><td><?php echo $Asar2 ; ?></td></tr>
                <tr><td>SUNSET</td><td><img src="icons/Sunset.jpg" width="50px"></td><td><?php echo $Sunset ; ?></td></tr>
                <tr><td>ISHA</td><td><img src="icons/Isha.jpg" width="50px"></td><td><?php echo $Isha ; ?></td></tr>


            </table>
        </div>

        <div class="panel-body salaah-times" >
            <h4 style= "text-align:center">SALAAH TIMES</h4>
            <table class="table table-striped table-condensed " style="background-color: White; border-color: rgb(204, 204, 204); border-width: 1px; border-style: none; border-collapse: collapse; color: Black; ">
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th style ="text-align:left;">ADHAAN</th>
                    <th style ="text-align:left;">SALAAH</th>
                </tr>
                <tr><td>FAJR</td><td><img src="icons/Fajr.jpg" width="50px"></td><td><?php if (isset($FAJR_ADHAAN)){ echo date ('H:i',strtotime($FAJR_ADHAAN));}  ?></td><td><?php if (isset($FAJR_SALAAH)){ echo date ('H:i',strtotime($FAJR_SALAAH));}  ?></td></tr>
                <tr><td>
                        <?php  $dw = date("w");
								if ($dw=="5"){
                                    if ($JUMMAH == "1"){
                                        echo "JUMMAH";
									}else {
                                    echo "DHUHR";
                                }}else {
                                    echo "DHUHR";
                                }
                                 ?>
                </td><td><img src="icons/Dhuhr.jpg" width="50px"></td><td><?php if (isset($DHUHR_ADHAAN)){ echo date ('H:i',strtotime($DHUHR_ADHAAN));}  ?></td><td><?php if (isset($DHUHR_SALAAH)){ echo date ('H:i',strtotime($DHUHR_SALAAH));}  ?></td></tr>
                <tr><td>ASR</td><td><img src="icons/Asar2.jpg" width="50px"></td><td><?php if (isset($ASAR_ADHAAN)){ echo date ('H:i',strtotime($ASAR_ADHAAN));}  ?></td><td><?php if (isset($ASAR_SALAAH)){ echo date ('H:i',strtotime($ASAR_SALAAH));}  ?></td></tr>
                <tr><td>MAGHRIB</td><td><img src="icons/Sunset.jpg" width="50px"></td><td colspan="2"><?php echo $Maghrib ; ?></td></tr>
                <tr><td>ISHA</td><td><img src="icons/Isha.jpg" width="50px"></td><td><?php if (isset($ISHA_ADHAAN)){ echo date('H:i',strtotime($ISHA_ADHAAN));}  ?></td><td><?php if (isset($ISHA_SALAAH)){ echo date ('H:i',strtotime($ISHA_SALAAH));}  ?></td></tr>
            </table>
        </div>


        <div class="panel-body masjid-information" >
			<table align="center" class="table table-striped table-condensed masjid-information" >
			 <h4 style= "color:#88E0AC;">MASJID INFORMATION</h4>
			 
			 <tr><td style ="text-align:left"><a href="report_perpetual.php?ID=<?php echo $uid ?>">View Perpetual Calendar</a></td>
			 <td ><a href="report_salaah.php?ID=<?php echo $uid ?>">View Salaah Calendar</a></td></tr>
				<tr><td style ="text-align:left">NAME</td><td ><?php echo $NAME ?></td></tr>
				   
				<tr><td style ="text-align:left">TOWN</td><td ><?php echo $TOWN ?></td>
					<td></td></tr>
				<tr><td style ="text-align:left">COUNTRY</td><td ><?php echo $COUNTRY ?></td>
					<td></td></tr>
				<tr> <td style ="text-align:left">QIBLA DISTANCE</td><td><?php echo $d ; ?> KM - <?php echo $NM ;?> Nautical miles - <?php echo $SM ;?> Statute miles</td></tr>
				   <tr><td style ="text-align:left">QIBLA BEARING FROM TRUE NORTH</td><td><?php echo number_format((float)$Q, 2, '.', '');  ?> Degrees</td></tr>
				<tr><td style ="text-align:left">LATITUDE</td><td><?php echo $LATITUDE ?></td></tr>
					<tr><td style ="text-align:left">LONGITUTE</td><td><?php echo $LONGITUDE ?></td></tr>
				<tr><td style ="text-align:left">JURISTIC METHOD</td>
					<td><?php if ($JURISTIC_METHOD == '1')
							echo 'University of Islamic Sciences, Karachi';
						elseif ($JURISTIC_METHOD == '2')
							echo 'Muslim World League';
						elseif ($JURISTIC_METHOD == '3')
							echo 'Islamic Society of North America';
						elseif ($JURISTIC_METHOD == '4')
							echo 'Umm al-Qura University, Makkah';
						elseif ($JURISTIC_METHOD == '5')
							echo 'Egyptian General Authority of Survey ';?></td></tr>


					<tr><td style ="text-align:left">LADIES FACILITY</td><td><?php echo $LADIES_FACILITY ?></td></tr>
                <tr><td style ="text-align:left">JUMMAH FACILITY</td><td><?php if ($JUMMAH == "0") {echo "No";} else {echo "Yes";} ?></td></tr>
				<tr><td style ="text-align:left">CONTACT</td><td><?php echo $CONTACT ?></td></tr>
					<tr><td style ="text-align:left">PHYSICAL ADDRESS</td><td><?php echo $PHYSICAL_ADDRESS ?></td></tr>
				<tr><td colspan="2"><?php echo $GENERAL_INFO ?></td></tr>

                <?php if ($ALLOW_REG == "1") {
                    echo '
<form method="post" action="register.php">
<input name="inputID" type="hidden" id="inputID" value="' . $_GET['ID'] .'">
<tr><td  style ="text-align:left" colspan="3"><button class="btn btn-lg btn-primary btn-block" type="submit">Register</button></td></tr>
</form>'; }?>

			</table>
		</div>
		
		<div class="map" > 

		
			<h4 style ="color:#88E0AC; "> MAP </h4>
			<h6 >Select your location on the map to receive directions</h6>
			<div id="map" align = "center"></div>
			<div  class="timetable " id="ptimes"  ></div>
			<div id="directionsPanel"  class="Font12BrownTahoma"></div>
		</div>
    </div>
</div>
</body>
</html>
