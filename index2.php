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
                zoom: 12,
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

        function GetRadiusTimes(lat, lng, radius){
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
                        maghrib: markers[i].getAttribute("Maghrib"),
                        fajradhaan: markers[i].getAttribute("FajrAdhaan"),
                        fajrsalaah: markers[i].getAttribute("FajrSalaah"),
                        dhuhradhaan: markers[i].getAttribute("DhuhrAdhaan"),
                        dhuhrsalaah: markers[i].getAttribute("DhuhrSalaah"),
                        asaradhaan: markers[i].getAttribute("AsarAdhaan"),
                        asarsalaah: markers[i].getAttribute("AsarSalaah"),
                        ishaadhaan: markers[i].getAttribute("IshaAdhaan"),
                        ishasalaah: markers[i].getAttribute("IshaSalaah")
                    });
                }
            }

            TempMarkers.sort(function(a, b){return a.distance- b.distance});
            var html2 = "<br><h3>Masajid within a " + radius + " KM Radius of your marker</h3><br>";
            html2 += '<table class="table table-striped table-sm" cellspacing="20px" cellpadding="20px" style="width: 100%;"><tr><th>Masjid</th><th>Distance</th><th>Fajr</th>' +
                '<th>Dhuhr</th> <th>Asar</th> <th>Maghrib</th> <th>Isha</th></tr>' +
                '<tbody class="list">';

            for (a = 0; a < TempMarkers.length; a++) {
                var url = "gettimes.php?ID=" + TempMarkers[a].id;
                var str = TempMarkers[a].name + ", " + TempMarkers[a].town + ", " + TempMarkers[a].country;
                var result = str.link("viewmasjid.php?ID=" + TempMarkers[a].id);
                html2 += "<tr><td>" + result + "</td>";
                html2 += "<td>" + TempMarkers[a].distance + " KM</td>";

                if(TempMarkers[a].fajradhaan == null){html2 += "<td></td>";}  else {html2 += "<td>" + TempMarkers[a].fajradhaan + "<br>" + TempMarkers[a].fajrsalaah + "</td>";}
                if(TempMarkers[a].dhuhradhaan == null){html2 += "<td></td>";}  else {html2 += "<td>" + TempMarkers[a].dhuhradhaan + "<br>" + TempMarkers[a].dhuhrsalaah + "</td>";}
                if(TempMarkers[a].asaradhaan == null){html2 += "<td></td>";}  else {html2 += "<td>" + TempMarkers[a].asaradhaan + "<br>" + TempMarkers[a].asarsalaah + "</td>";}
                html2 += "<td>" + TempMarkers[a].maghrib + "</td>";
                if(TempMarkers[a].ishaadhaan == null){html2 += "<td></td>";}  else {html2 += "<td>" + TempMarkers[a].ishaadhaan + "<br>" + TempMarkers[a].ishasalaah + "</td>";}

                html2 += "</tr>";
            }

            html2 += "</table>";
            document.getElementById('nearest').style.visibility = 'visible';
            document.getElementById('users').innerHTML = html2;
            /////////////////////////////////////////////////////////////////

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
<div class="service-header">
    <h1>Taqweem Musjid Guide</h1>
    <h3>Select your location on the map</h3>
</div>

        <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
            <tr>
                <td style="width: 70%;"><div id="map" style="height: 600px;"></div></td>
                <td><div class="timetable" id="ptimes" ></div>
            </tr>
            <tr>
                <td></td>
                <td><div id="directionsPanel"  class="Font12BrownTahoma"></div></td>
            </tr>
        </table>


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

</div>
</body>
</html>
