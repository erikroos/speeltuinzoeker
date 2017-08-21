<?php include "header.tpl.php"; ?>

    <div id="latestbox">
        <p>Er zijn al <strong><?php echo $totalNr; ?></strong> speeltuinen!</p>
        <?php if ($latestSpeeltuin != null): ?>
            <p>
                De nieuwste: <strong><?php echo $latestSpeeltuin["naam"]; ?></strong><br>
                <?php if (!empty($latestSpeeltuin["locatie_omschrijving"])) echo $latestSpeeltuin["locatie_omschrijving"] . "<br>"; ?>
                <a href="detail.php?speeltuin=<?php echo $latestSpeeltuin["id"]; ?>">Meer</a>
            </p>
        <?php endif; ?>
    </div>

    <div id="searchbar">
    	<form id="searchform">
        	<input type="text" id="locatie_omschrijving" name="locatie_omschrijving" class="form-control" value="<?php echo $defaultLocationString; ?>" />
        	<button id="place-marker" value="Zet marker op omschreven locatie" class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Zoek</button>
    		<input type="submit" style="display: none" />
    	</form>
    </div>

    <div id="map-div"></div>
    
    <div id="latestbox-resp">
        <p>Er zijn al <strong><?php echo $totalNr; ?></strong> speeltuinen!</p>
        <?php if ($latestSpeeltuin != null): ?>
            <p>
                De nieuwste: <strong><?php echo $latestSpeeltuin["naam"]; ?></strong><br>
                <?php if (!empty($latestSpeeltuin["locatie_omschrijving"])) echo $latestSpeeltuin["locatie_omschrijving"] . "<br>"; ?>
                <a href="detail.php?speeltuin=<?php echo $latestSpeeltuin["id"]; ?>">Meer</a>
            </p>
        <?php endif; ?>
    </div>

<?php include "footer.tpl.php"; ?>

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

    var searchForPlace = function() {
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
    }

    $('#place-marker').click(function() {
        event.preventDefault();
        searchForPlace();
        $('#locatie_omschrijving').blur();
    });

    $('#searchform').submit(function() {
        event.preventDefault();
        searchForPlace();
        $('#locatie_omschrijving').blur();
    });

    $('#locatie_omschrijving').click(function() {
        if ($('#locatie_omschrijving').val() == "<?php echo $defaultLocationString; ?>") {
    		$('#locatie_omschrijving').val("");
        }
    });
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>

</html>