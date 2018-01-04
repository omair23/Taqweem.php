<!DOCTYPE html>
<head>
    <title>Taqweem - Add Musjid</title>
    <?php include_once("menu.php");?>

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
                center: new google.maps.LatLng(0 , 0),
                zoom: 1,
                mapTypeId: 'roadmap'
            });
            var infoWindow = new google.maps.InfoWindow;
            geocoder = new google.maps.Geocoder();
            directionsDisplay = new google.maps.DirectionsRenderer();

            var contentString2 = "Pointer Masjid";
            var infowindow2 = new google.maps.InfoWindow({
                content: contentString2				});
            var myLatlng2 = new google.maps.LatLng(-28.732617,24.754);

            google.maps.event.addListener(map, 'click', function(event) {
                marker = new google.maps.Marker({position: event.latLng, map: map});

                var lat = event.latLng.lat();
                var lng = event.latLng.lng();
                document.getElementById('inputLatitude').value = lat;
                document.getElementById('inputLongitude').value = lng;
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

</head>


<body onload="load()">

<div class="container">

    <form class="form-signin" style="width:70%" method="post" action="insert_masjid.php">
        <h2 class="form-signin-heading">My Musjid</h2>

        <label for="inputName" class="sr-only">Musjid Name</label>
        <input name="inputName" type="text" id="inputName" class="form-control" placeholder="Masjid Name" required autofocus>

        <label for="inputTown" class="sr-only">Town</label>
        <input  name="inputTown" type="text" id="inputTown" class="form-control" placeholder="Town" required autofocus>

        <label for="inputCountry" class="sr-only">Country</label>
        <input  name="inputCountry" type="text" id="inputCountry" class="form-control" placeholder="Country" required autofocus>

        <label for="inputTimeZone" class="sr-only">Time-Zone</label>
        <input  name="inputTimeZone" type="number" id="inputTimeZone" class="form-control" placeholder="Time Zone" required autofocus>

        <label for="inputAddress" class="sr-only">Physical Address</label>
        <input  name="inputAddress" type="text" id="inputAddress" class="form-control" placeholder="Physical Address" required autofocus>

        <label for="inputContact" class="sr-only">Contact</label>
        <input  name="inputContact" type="text" id="inputContact" class="form-control" placeholder="Contact" autofocus>

        <label for="inputGeneral" class="sr-only">General Information</label>
        <input  name="inputGeneral" type="text" id="inputGeneral" class="form-control" placeholder="General Information" autofocus>

        <label><input  type="checkbox" name="inputLadies" id="inputLadies"> Ladies Facility</label><br>

        <div>
            <label for="inputLatitude" class="sr-only">Latitude</label>
            <input  name="inputLatitude" type="text" id="inputLatitude" class="form-control" placeholder="Latitude" required autofocus>

            <label for="inputLongitude" class="sr-only">Longitude</label>
            <input  name="inputLongitude" type="text" id="inputLongitude" class="form-control" placeholder="Longitude" required autofocus>
            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Add Musjid</button>
            <br>
            <div id="map"></div>

        </div>

    </form>
    <br><br><br>
</div>

</body>
</html>
