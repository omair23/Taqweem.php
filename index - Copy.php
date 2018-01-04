<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Taqweem Map</title>
    <?php include_once "menu.php" ?>

    <style>
        
        .timetable {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #000;
            text-align:center;
        }
    </style>

    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="PrayTimes.js"></script>
    <script type="text/javascript">
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        var map;
        var markers;
        var Location;
        //<![CDATA[

        var customIcons = {
            Yes: {
                icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
            },
            No: {
                icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
            }
        };

        navigator.geolocation.getCurrentPosition(GetLocation);
        function GetLocation(location) {
            //location.coords.latitude;
            Location = {lat: location.coords.latitude, lng: location.coords.longitude};
            //alert(location.coords.longitude);
        }

        function load() {
            //center: new google.maps.LatLng(-25 , 27), center: new google.maps.LatLng(Location.lat , Location.lng),
			var UserLatitude;
			var UserLongitude;
			var ZoomLength = 6;
			UserLatitude = -25;
			UserLongitude = 27;
			if (Location.lat != undefined){
				UserLatitude = Location.lat;
				UserLongitude = Location.lng;
				ZoomLength = 12;
			}
            var map = new google.maps.Map(document.getElementById("map"), {
                center: new google.maps.LatLng(UserLatitude , UserLongitude),
                zoom: ZoomLength,
                mapTypeId: 'roadmap'
            });
            marker = new google.maps.Marker({position: Location, map: map});

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

                //get closeset musjid//////
                var lat = event.latLng.lat();
                var lng = event.latLng.lng();
                var R = 6371;
                var distances = [];
                var closest = -1;

                for (i = 0; i < markers.length; i++) {
                    var mlat = markers[i].getAttribute("Latitude");
                    var mlng =  parseFloat(markers[i].getAttribute("Longitude"));
                    var dLat = rad(mlat - lat);
                    var dLong = rad(mlng - lng);
                    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(rad(lat)) * Math.cos(rad(lat)) * Math.sin(dLong / 2) * Math.sin(dLong / 2);
                    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    var d = R * c;
                    distances[i] = d;
                    if (closest == -1 || d < distances[closest]) {
                        closest = i;
                    }
                }
                var closestMusjid = (markers[closest].getAttribute("Name")) + ", " + (markers[closest].getAttribute("Town"))+ ", " + (markers[closest].getAttribute("Country"))
                var closestID = (markers[closest].getAttribute("ID"));

                geocoder.geocode({'latLng':event.latLng},function(results, status) {
                    var area = results[0].formatted_address;
                    document.getElementById('area').innerHTML = area;
                });
                //get perpetual times
                var str = (event.latLng);
                str = str+''
                str = str.replace('(', '');
                str = str.replace(')', '');
                GPS = str.split(",");

                var todayd = new Date();
                var n = todayd.getTimezoneOffset();
                n = n / -60;

                prayTimes.setMethod('Karachi');
                prayTimes.adjust( {asr: 'Hanafi'} );
                var date = new Date(); // today
                var times = prayTimes.getTimes(date, [GPS[0],GPS[1]], n);
                var list = ['Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha', 'Midnight'];
                prayTimes.adjust( {asr: 'Standard'} );
                var date = new Date(); // today
                var times2 = prayTimes.getTimes(date, [GPS[0],GPS[1]], n);
                var list2 = ['Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha', 'Midnight'];
                var str = "View Times";
                var result = str.link("viewmasjid.php?ID=" + closestID );
                var html = '<table id="timetable">';
                html += '<tr><th colspan="2" class="timetable">'+ date.toLocaleDateString()+ '</th></tr>';
                html += '<tr><th colspan="2" class="timetable">Perpetual Salaah Times For Your Selected Location</th></tr>';
                html += '<tr><th style="text-align:center" colspan="2">_____________________________</th></tr>';
                html += '<tr><th class="timetable">Fajr</th><th> '+ times[list[0].toLowerCase()] + '</th></tr>';
                html += '<tr><th class="timetable">Sunrise</th><th> '+ times[list[1].toLowerCase()] + '</th></tr>';
                html += '<tr><th class="timetable">Dhuhr</th><th> '+ times[list[2].toLowerCase()] + '</th></tr>';
                html += '<tr><th class="timetable">Asr Shafi</th><th> '+ times2[list2[3].toLowerCase()] + '</th></tr>';
                html += '<tr><th class="timetable">Asr Hanafi</th><th> '+ times[list[3].toLowerCase()] + '</th></tr>';
                html += '<tr><th class="timetable">Maghrib</th><th> '+ times[list[4].toLowerCase()] + '</th></tr>';
                html += '<tr><th class="timetable">Isha</th><th> '+ times[list[5].toLowerCase()] + '</th></tr>';
                html += '<tr><th style="text-align:center" colspan="2">_____________________________</th></tr>';
                html += '<tr><th colspan="2" class="timetable">Nearest Masjid : '+ closestMusjid +'</th></tr>';
                html += '<tr><th colspan="2" class="timetable">' + result + '</th></tr>';
                html += '<tr><th style="text-align:center" colspan="2">_____________________________</th></tr>';
                html += '</table>';
                document.getElementById('ptimes').innerHTML = html;


                directionsDisplay.setMap(map);
                directionsDisplay.setPanel(document.getElementById("directionsPanel"));

                var pointb;
                pointb = '(';
                pointb += markers[closest].getAttribute("Latitude");
                pointb += ',';
                pointb += markers[closest].getAttribute("Longitude");
                pointb += ')';

                calcRoute(event.latLng,pointb);
                var Radius = document.getElementById('rangeInput').value;
                document.getElementById('Latitude').value = lat;
                document.getElementById('Longitude').value = lng;
                GetRadiusTimes(lat, lng, Radius);
				

            });
        }

        function GetRadiusTimes(lat,lng, radius){
            /////////////////////////////// GET RADIUS TIMES /////////////////
            var myLatlng2 = new google.maps.LatLng(-28.732617, 24.754);
            var R = 6371;
            var distances = [];
            var TempMarkers = [];

            for (i = 0; i < markers.length; i++) {
                var mlat = markers[i].getAttribute("Latitude");
                var mlng = parseFloat(markers[i].getAttribute("Longitude"));
                var dLat = rad(mlat - lat);
                var dLong = rad(mlng - lng);
                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(rad(lat)) * Math.cos(rad(lat)) * Math.sin(dLong / 2) * Math.sin(dLong / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                var d = R * c; //distance in KM
                distances[i] = d;
                if (d < radius) {
                    markers[i].setAttribute("Distance", parseFloat(Math.round(d * 100) / 100).toFixed(2));
                    TempMarkers.push({
                        name: markers[i].getAttribute("Name"),
                        town: markers[i].getAttribute("Town"),
                        country: markers[i].getAttribute("Country"),
                        distance: markers[i].getAttribute("Distance"),
                        id: markers[i].getAttribute("ID"),
						jummah: markers[i].getAttribute("Jummah"),
                        maghrib: markers[i].getAttribute("Maghrib"),
                        fajradhaan: markers[i].getAttribute("FajrAdhaan"),
                        fajrsalaah: markers[i].getAttribute("FajrSalaah"),
                        dhuhradhaan: markers[i].getAttribute("DhuhrAdhaan"),
                        dhuhrsalaah: markers[i].getAttribute("DhuhrSalaah"),
                        asaradhaan: markers[i].getAttribute("AsarAdhaan"),
                        asarsalaah: markers[i].getAttribute("AsarSalaah"),
                        ishaadhaan: markers[i].getAttribute("IshaAdhaan"),
                        ishasalaah: markers[i].getAttribute("IshaSalaah"),
                        ladies: markers[i].getAttribute("LadiesFacility")
                    });
                }
            }

            TempMarkers.sort(function(a, b){return a.distance- b.distance});
            var html2 = "<br><h3>Masajid within a " + radius + " KM Radius of your marker</h3><br>";
            html2 += '<table border="1px" cellspacing="20px" cellpadding="20px" style="width: 100%;"><tr><th>Masjid</th><th>Distance</th><th>Next Salaah</th>' +
                '<th>Countdown</th><th>Salaah Time</th><th>Ladies Facility</th></tr>' +
                '<tbody class="list">';

            for (a = 0; a < TempMarkers.length; a++) {

                if(TempMarkers[a].fajradhaan == null){"<tr></tr>";}
                else{
                    var url = "gettimes.php?ID=" + TempMarkers[a].id;
                    var str = TempMarkers[a].name + ", " + TempMarkers[a].town + ", " + TempMarkers[a].country;
                    var result = str.link("viewmasjid.php?ID=" + TempMarkers[a].id);
                    html2 += "<tr><td>" + result + "</td>";
                    html2 += "<td>" + TempMarkers[a].distance + " KM</td>";

                    var Times = [];
                    Times[0] = TempMarkers[a].fajrsalaah;
                    Times[1] = TempMarkers[a].dhuhrsalaah;
                    Times[2] = TempMarkers[a].asarsalaah;
                    Times[3] = TempMarkers[a].maghrib;
                    Times[4] = TempMarkers[a].ishasalaah;
                    var NextSalaah = 0;
                    var today = new Date();

                    for (r=0; r<=4; r++){
                        var Time = Times[r].split(":");
                        var Current = new Date(today.getFullYear(), today.getMonth(), today.getDate(), Time[0], Time[1], 0, 0);
                        if (Current > today){
                            NextSalaah = r;
                            break;
                        }
                    }

                    if (NextSalaah == 0){html2 += "<td>Fajr</td>";}
					
					var d = new Date();
					var n = d.getDay();
					
                    if (NextSalaah == 1){
						if (TempMarkers[a].jummah == "1" && n == "5"){
							html2 += "<td>Jummah</td>";}
						else{
							html2 += "<td>Dhuhr</td>";
						}
						}
                    if (NextSalaah == 2){html2 += "<td>Asr</td>";}
                    if (NextSalaah == 3){html2 += "<td>Maghrib</td>";}
                    if (NextSalaah == 4){html2 += "<td>Isha</td>";}

                    var Time = Times[NextSalaah].split(":");
                    var Current = new Date(today.getFullYear(), today.getMonth(), today.getDate(), Time[0], Time[1], 0, 0);
                    var Difference = Current - today;

                    var msec = Difference;
                    var hh = Math.floor(msec / 1000 / 60 / 60);
                    msec -= hh * 1000 * 60 * 60;
                    var mm = Math.floor(msec / 1000 / 60);
                    msec -= mm * 1000 * 60;
                    var ss = Math.floor(msec / 1000);
                    msec -= ss * 1000;

					//if (mm < 10){mm += "0" ;}
					
                    html2 += "<td>" + hh + ":" + mm + "</td>";
                    html2 += "<td>" + Times[NextSalaah] + "</td>";
                    html2 += "<td>" + TempMarkers[a].ladies; + "</td>";
                    html2 += "</tr>";
                }
            }

            html2 += "</table>";
            document.getElementById('nearest').style.visibility = 'visible';
            document.getElementById('users').innerHTML = html2;
            /////////////////////////////////////////////////////////////////
        }

        function checkTime(i) {
            return (i < 10) ? "0" + i : i;
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
</head>

<body onload="load()">
<div class="container">

	<div class="panel-heading">

            <center><h4><strong><?php echo date("d - M - Y"); ?></strong></h4></center>
            <div id="currenttime" style="text-align:center; font-weight:bold; font-size:18px;"></div>
        </div>

        <script>
            (function () {
                function checkTime(i) {
                    return (i < 10) ? "0" + i : i;
                }

                function startTime() {
				
			//google.maps.event.trigger(marker, 'click', {latLng: new google.maps.LatLng(0, 0)});
                    var today = new Date(),
                        h = checkTime(today.getHours()),
                        m = checkTime(today.getMinutes()),
                        s = checkTime(today.getSeconds());
                    var n = today.getTimezoneOffset();
                    n = n / -60;
                    document.getElementById('currenttime').innerHTML = h + ":" + m + ":" + s +
                        " </br>" + "Device Timezone: " + n;
                    t = setTimeout(function () {
                        startTime()
                    }, 500);
                }
                startTime();
            })();
        </script>

    <div id="nearest" style="width: 70%; visibility: hidden " >
        <form>
            <input type="range" id="rangeInput" name="rangeInput" value="7" min="3" max="300" onchange="updateTextInput(this.value);">
            <input type="text" id="textInput" value="7 KM" readonly>
            <input type="hidden" id="Latitude">
            <input type="hidden" id="Longitude">
        </form>
		
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="js/owl.carousel.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script type="text/javascript">
            function updateTextInput(val) {
                document.getElementById('textInput').value = val + "KM";
                GetRadiusTimes(document.getElementById('Latitude').value, document.getElementById('Longitude').value, val);
            }
        </script>

        <div id="users">

        </div>
    </div>
    <br><br>


<div class="service-header">
    <h1>Taqweem Musjid Guide</h1>
    <h3>Select your location on the map</h3>
</div>
<div id="map" style="height: 600px; width:100%"></div>

        <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td><div class="timetable" id="ptimes" ></div>
                <td><div id="directionsPanel"  class="Font12BrownTahoma"></div></td>
            </tr>
        </table>




</div>
</body>
</html>
