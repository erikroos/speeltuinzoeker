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
<script src="js/jquery.min.js"></script>
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
    var existingMarkers = [];
    var infowindow = null;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map-div'), {
            zoom: 15
        });

     	// position the map
        setDefaultPos();
        <?php if ($fromSpeeltuin != null): ?>
        	// Terug van speeltuin? Dan daarop centreren
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
	            	setDefaultPos();
	            });
	        } else {
	            // Browser doesn't support Geolocation, leave on defaultpos
	        }
		<?php endif; ?>

		// markers for existing speeltuinen
		function fireIfLastEvent() { 
			// always remove existing markers
			for (var i = 0; i < existingMarkers.length; i++) {
				existingMarkers[i].setMap(null);
			}
			existingMarkers = [];
			
		    if (lastEvent.getTime() + 100 <= new Date().getTime()) { 
		    	var newBounds = map.getBounds();
				var NE = newBounds.getNorthEast();
				NE = NE.toString().replace(" ", "").replace("(", "").replace(")", "");
				var SW = newBounds.getSouthWest();
				SW = SW.toString().replace(" ", "").replace("(", "").replace(")", "");
				$.get("_markers.php?ne=" + NE + "&sw=" + SW, function(data) {
					placeMarkers(data);
			    });
		    } 
		}
		function placeMarkers(data) {
			var speeltuinen = JSON.parse(data);
			
			infowindow = new google.maps.InfoWindow({
				disableAutoPan: true,
				content: "Wacht op klik..."
			});

			for (var i = 0; i < speeltuinen.length; i++) {
				var speeltuin = speeltuinen[i];
				var existingMarker = new google.maps.Marker({
		            map: map,
		            icon: "<?php echo BASE_URL; ?>img/marker_" + (speeltuin.public == 0 ? "blue" : (speeltuin.public == 1 ? "red" : "yellow")) + ".png",
		            animation: google.maps.Animation.DROP,
		            html:	"<h4>" + speeltuin.naam + "</h4>" +
							"<p>" + speeltuin.omschrijving + "</p>" +
							"<p><a href='detail.php?speeltuin=" + speeltuin.id + "'>Meer</a>"
			    });
				existingMarker.setPosition({
		            lat: parseFloat(speeltuin.lat),
		            lng: parseFloat(speeltuin.lon)
		        });
				existingMarker.addListener("click", function() {
			        infowindow.setContent(this.html);
			        infowindow.open(map, this);
		        });
		        
				existingMarkers.push(existingMarker);
			} 
		}
		function scheduleDelayedCallback() { 
		    lastEvent = new Date(); 
		    setTimeout(fireIfLastEvent, 100); 
		} 
		map.addListener("bounds_changed", scheduleDelayedCallback);
    }

    function setDefaultPos() {
        var backupPos = { // Grote Markt Grunnen
            lat: 53.218721,
            lng: 6.567633
        };
        map.setCenter(backupPos);
    }

    var searchForPlace = function() {
		if ($('#locatie_omschrijving').val() == "") {
            return;
        }
        
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
    };

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