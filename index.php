<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Speeltuinzoeker.nl - Laat ze spelen!</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
	<link href="css/speeltuinzoeker.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<!-- AdSense ACTIVATE WHEN READY -->
	<!--script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
	  (adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-6261352840965150",
		enable_page_level_ads: true
	  });
	</script-->
	
  </head>
  <body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">	
			<a href="./admin/index.php">Mijn Speeltuinzoeker</a>
		</div>
	</nav>
	
    <div class="container">
        <h1>Speeltuinzoeker.nl</h1>
		<h2>Laat ze spelen!</h2>
		<p>Hier komt <strong>speeltuinzoeker.nl</strong><br>
		dé (mobiele) website om snel en makkelijk een speeltuin in de buurt te kunnen vinden.</p>
		<p>Bij speeltuinzoeker.nl:</p>
		<ul>
			<li>zoek je makkelijk op de kaart</li>
			<li>werkt alles snel</li>
			<li>staan de gebruikers centraal: voor elkaar, door elkaar</li>
		</ul>
	</div>

    <div id="map-div"></div>
    <label for="omschrijving">Zoek</label>
    <textarea id="locatie_omschrijving" name="locatie_omschrijving" rows="1" maxlength="1000" class="form-control"></textarea>
    <button id="place-marker" value="Zet marker op omschreven locatie" class="btn btn-default">Zet marker op omschreven locatie</button>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	
	<!-- Google Analytics -->
	<script>
		(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
		function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
		e=o.createElement(i);r=o.getElementsByTagName(i)[0];
		e.src='https://www.google-analytics.com/analytics.js';
		r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
		ga('create','UA-102474826-1','auto');ga('send','pageview');
	</script>

    <!-- Map -->
    <script>
        var map;
        var marker;

        function initMap() {
            var infoWindow;

            map = new google.maps.Map(document.getElementById('map-div'), {
                zoom: 15
            });

            marker = new google.maps.Marker({
                map: map,
                draggable: true
            });

            marker.addListener('dragend', function() {
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();
                //$('#lat').val(lat);
                //$('#lon').val(lng);
            });

            //map.addListener('bounds_changed', function() {
            //	var newBounds = map.getBounds();
            //	var NE = newBounds.getNorthEast();
            //	var SW = newBounds.getSouthWest();
            //	document.getElementById('map-info').innerHTML = "Bounding box: NE " + NE.toString() + " SW " + SW.toString();
            //});

            infoWindow = new google.maps.InfoWindow;

            // In eerste instantie op huidige locatie zetten. Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(currentPosition) {
                    var pos = {
                        lat: currentPosition.coords.latitude,
                        lng: currentPosition.coords.longitude
                    };
                    //infoWindow.setPosition(pos);
                    //infoWindow.setContent('Locatie gevonden.');
                    //infoWindow.open(map);
                    map.setCenter(pos);
                    marker.setPosition(pos);
                    //$('#lat').val(currentPosition.coords.latitude);
                    //$('#lon').val(currentPosition.coords.longitude);
                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                'Fout: Geolocatie is mislukt.' :
                'Fout: Uw browser ondersteunt geen geolocatie.');
            infoWindow.open(map);
        }

        $('#place-marker').click(function() {
            event.preventDefault();
            $.get(
                "https://maps.googleapis.com/maps/api/geocode/json?address=" + $('#locatie_omschrijving').val() + "&key=AIzaSyCXVNGEew5BT-iv9th2jqc4-QejCJxhoRk",
                function(data) {
                    var lat = data.results[0].geometry.location.lat;
                    var lng = data.results[0].geometry.location.lng;
                    var pos = {
                        lat: lat,
                        lng: lng
                    };
                    map.setCenter(pos);
                    marker.setPosition(pos);
                    //$('#lat').val(lat);
                    //$('#lon').val(lng);
                }
            );
        });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>
  </body>
</html>