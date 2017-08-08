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
	<?php include "tpl/topbar.tpl.php"; ?>
	
    <div class="container">
		<h1>Speeltuinzoeker.nl</h1>
		<h2>Laat ze spelen!</h2>

        <?php include "_latest.php"; ?>
        <div id="latestbox">
            <p>Er zijn al <?php echo $totalNr; ?> speeltuinen!</p>
            <?php if ($latestSpeeltuin != null): ?>
                <p>
                    De nieuwste: <?php echo $latestSpeeltuin["naam"]; ?><br>
                    <?php echo $latestSpeeltuin["locatie_omschrijving"]; ?><br>
                    <a href="detail.php?speeltuin=<?php echo $latestSpeeltuin["id"]; ?>">Meer</a>
                </p>
            <?php endif; ?>
        </div>

		<div id="searchbar">
			<textarea id="locatie_omschrijving" name="locatie_omschrijving"
				rows="1" maxlength="1000" class="form-control"></textarea>
			<button id="place-marker" value="Zet marker op omschreven locatie"
				class="btn btn-default">Zoek locatie</button>
		</div>

		<div id="map-div"></div>
	</div>

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
	<?php include_once "./_map.php"; ?>
	<script>
        var map;
        
        function initMap() {
            var infoWindow;

            map = new google.maps.Map(document.getElementById('map-div'), {
                zoom: 15
            });

            <?php $markerNr = 0; ?>
            <?php foreach ($speeltuinen as $speeltuin): ?>
	            var marker<?php echo $markerNr; ?> = new google.maps.Marker({
	                map: map
	            });
	            var pos<?php echo $markerNr; ?> = {
	                    lat: <?php echo $speeltuin["lat"]; ?>,
	                    lng: <?php echo $speeltuin["lon"]; ?>
	                };
	            marker<?php echo $markerNr; ?>.setPosition(pos<?php echo $markerNr; ?>);
	            var contentString<?php echo $markerNr; ?> = "<h4><?php echo $speeltuin["naam"]; ?></h4>" +
	    	            "<p><?php echo $speeltuin["omschrijving"]; ?></p>" +
	    	            "<p><a href='detail.php?speeltuin=<?php echo $speeltuin["id"]; ?>'>Meer</a>";
	            var infowindow<?php echo $markerNr; ?> = new google.maps.InfoWindow({
	                content: contentString<?php echo $markerNr; ?>
	            });
	            marker<?php echo $markerNr; ?>.addListener('click', function() {
	                infowindow<?php echo $markerNr; ?>.open(map, marker<?php echo $markerNr; ?>);
	            });
	            <?php $markerNr++; ?>
            <?php endforeach; ?>

            //map.addListener('bounds_changed', function() {
            //	var newBounds = map.getBounds();
            //	var NE = newBounds.getNorthEast();
            //	var SW = newBounds.getSouthWest();
            //	document.getElementById('map-info').innerHTML = "Bounding box: NE " + NE.toString() + " SW " + SW.toString();
            //});

            infoWindow = new google.maps.InfoWindow;

            <?php if ($fromSpeeltuin != null): ?>
	            var fromPos = {
	                lat: <?php echo $fromSpeeltuin->getLatitude(); ?>,
                    lng: <?php echo $fromSpeeltuin->getLongitude(); ?>
	            };
	            map.setCenter(fromPos);
            <?php else: ?>
	            // In eerste instantie op huidige locatie zetten. Try HTML5 geolocation.
	            if (navigator.geolocation) {
	                navigator.geolocation.getCurrentPosition(function(currentPosition) {
	                    var pos = {
	                        lat: currentPosition.coords.latitude,
	                        lng: currentPosition.coords.longitude
	                    };
	                    map.setCenter(pos);
	                }, function() {
	                    handleLocationError(true, infoWindow, map.getCenter());
	                });
	            } else {
	                // Browser doesn't support Geolocation
	                handleLocationError(false, infoWindow, map.getCenter());
	            }
            <?php endif; ?>
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
//             infoWindow.setPosition(pos);
//             infoWindow.setContent(browserHasGeolocation ?
//                 'Fout: geolocatie is mislukt.' :
//                 'Fout: uw browser ondersteunt geen geolocatie.');
//             infoWindow.open(map);
			// Silent decay
        	var backupPos = { // Grote Markt Grunnen
                    lat: 53.218721,
                    lng: 6.567633
            };
            map.setCenter(backupPos);
        }

        $('#place-marker').click(function() {
            event.preventDefault();
            $.get(
                "https://maps.googleapis.com/maps/api/geocode/json?address=" + $('#locatie_omschrijving').val() + "&key=AIzaSyCXVNGEew5BT-iv9th2jqc4-QejCJxhoRk",
                function(data) {
                    var pos = {
                        lat: data.results[0].geometry.location.lat,
                        lng: data.results[0].geometry.location.lng
                    };
                    map.setCenter(pos);
                }
            );
        });
    </script>
	<script async defer
		src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>

</body>
</html>