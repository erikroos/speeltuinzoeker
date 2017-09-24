<?php $indexTitle = "Speeltuinzoeker.nl - Laat ze spelen!"; ?>
<?php include "header.tpl.php"; ?>

    <div id="latestbox">
        <?php include "latestbox.tpl.php"; ?>
    </div>

    <div id="searchbar">
    	<form id="searchform">
        	<input type="text" id="locatie_omschrijving" name="locatie_omschrijving" class="form-control" value="<?php echo $defaultLocationString; ?>" />
        	<button id="place-marker" value="Zet marker op omschreven locatie" class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Zoek</button>
    		<input type="submit" style="display: none" />
    	</form>
    </div>

    <div id="map-container">
    	<button id="toggleFilterDiv" class="btn btn-default">Filter&nbsp;<i id="filterToggleIcon" class="fa fa-toggle-right" aria-hidden="true"></i></button>
    	<button id="toggleFilterDiv-resp" class="btn btn-default">Filter&nbsp;<i id="filterToggleIcon-resp" class="fa fa-toggle-down" aria-hidden="true"></i></button>
    	<div id="map-filter-div">
    		<form id="filter-form-type">
    			<label>Type speeltuin</label><br>
    			<?php foreach ($speeltuin->getAllTypes() as $typeOption): ?>
    				<input type="checkbox" name="<?php echo $typeOption; ?>" value="1">&nbsp;<?php echo $typeOption; ?><br>
    			<?php endforeach; ?>
    		</form>
    		<form id="filter-form-agecat">
    			<label>Leeftijdscategorie(&euml;n)</label><br>
    			<?php foreach ($speeltuin->getAllAgecats() as $agecatOptionColname => $agecatOptionName): ?>
    				<input type="checkbox" name="<?php echo $agecatOptionColname; ?>" value="1">&nbsp;<?php echo $agecatOptionName; ?><br>
    			<?php endforeach; ?>
    		</form>
    		<form id="filter-form-access">
    			<label>Toegankelijkheid</label><br>
    			<?php $paidAllowed = false; // TODO true indien er betalende klanten komen ?>
				<?php foreach ($speeltuin->getAllAccessOptions($paidAllowed) as $accessId => $accessName): ?>
					<input type="checkbox" name="<?php echo $accessId; ?>" value="1">&nbsp;<?php echo $accessName; ?><br>
				<?php endforeach; ?>
    		</form>
    		<form id="filter-form-voorzieningen">
    			<label>Voorzieningen</label><br>
    			<?php foreach ($speeltuin->getAllVoorzieningen(1) as $voorzieningId => $voorzieningName): ?>
    				<input type="checkbox" name="<?php echo $voorzieningId; ?>" value="1">&nbsp;<?php echo $voorzieningName; ?><br>
    			<?php endforeach; ?>
    			<a href="#" id="expand_items"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i>&nbsp;Meer</a>
				<div id="nonPopItems">
	    			<?php foreach ($speeltuin->getAllVoorzieningen(0) as $voorzieningId => $voorzieningName): ?>
	    				<input type="checkbox" name="<?php echo $voorzieningId; ?>" value="1">&nbsp;<?php echo $voorzieningName; ?><br>
	    			<?php endforeach; ?>
	    		</div>
    		</form>
    	</div>
    	<div id="map-div"></div>
    </div>
    
    <div id="latestbox-resp">
        <?php include "latestbox.tpl.php"; ?>
    </div>
    
    <div id="footer">
        <div class="footer-column">
            <h4>Sitemap</h4>
            <nav class="sitemap">
                <a href="./index.php">Home</a>
                <a href="./atoz.php">Alle speeltuinen</a>
                <a href="./about.php">Info</a>
                <a href="./join.php">Meedoen</a>
                <a href="./contact.php">Contact</a>
                <a href="./admin/<?php echo (!isset($_SESSION["user_id"]) ? "index.php" : ($_SESSION["admin"] == 1 ? "index.php" : "view.php?user")); ?>">Mijn Speeltuinzoeker</a>
            </nav>
        </div>
        <div class="footer-column right">
        	<h4>Colofon</h4>
            <p><strong>Adverteren?</strong> Jouw (indoor) speeltuin op deze site? Neem <a href="contact.php">contact</a> op!</p>
            <p>Speeltuinzoeker.nl gebruikt <strong>cookies</strong> om de site goed te laten werken.</p>
            <p>We doen ons uiterste best om deze site actueel, snel en veilig te houden. Kom je iets tegen wat niet klopt? Laat het ons weten!</p>
            <p>&copy; <?php echo date("Y"); ?> RO Online Solutions<br><a href="mailto:info@speeltuinzoeker.nl">info@speeltuinzoeker.nl</a></p>
        </div>
        <div class="betweenbar"></div>
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

    function initMap(existingPos, existingZoom) {

    	if (typeof existingZoom === "undefined") {
    		existingZoom = 15;
    	}
        
        map = new google.maps.Map(document.getElementById('map-div'), {
            zoom: existingZoom
        });

        if (typeof existingPos !== "undefined") {
        	map.setCenter(existingPos);
        } else {
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
        }

		// markers for existing speeltuinen
        var lastEvent;
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
				var url = "_markers.php?ne=" + NE + "&sw=" + SW;
				$("form#filter-form-type :checkbox:checked").each(function() {
					var input = $(this);
					url += "&type[]=" + input.attr("name");
				});
				$("form#filter-form-agecat :checkbox:checked").each(function() {
					var input = $(this);
					url += "&agecat[]=" + input.attr("name");
				});
				$("form#filter-form-access :checkbox:checked").each(function() {
					var input = $(this);
					url += "&access[]=" + input.attr("name");
				});
				$("form#filter-form-voorzieningen :checkbox:checked").each(function() {
					var input = $(this);
					url += "&voorziening[]=" + input.attr("name");
				});
				$.get(url, function(data) {
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
		            		"<p>" + (speeltuin.public == 0 ? "Betaald" : (speeltuin.public == 1 ? "Gratis en altijd toegankelijk" : "Gratis maar beperkt toegankelijk")) + "</p>" +
		            		"<p>" + speeltuin.speeltuintype + "</p>" +
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

    $('#place-marker').click(function(event) {
        event.preventDefault();
        searchForPlace();
        $('#locatie_omschrijving').blur();
    });

    $('#searchform').submit(function(event) {
        event.preventDefault();
        searchForPlace();
        $('#locatie_omschrijving').blur();
    });

    $('#locatie_omschrijving').click(function(event) {
        if ($('#locatie_omschrijving').val() == "<?php echo $defaultLocationString; ?>") {
    		$('#locatie_omschrijving').val("");
        }
    });

    $('#toggleFilterDiv').click(function(event) {
    	event.preventDefault();
    	$('#map-filter-div').toggle("slow");
    	$('#filterToggleIcon').toggleClass('fa-toggle-right fa-toggle-left');
    });
    $('#toggleFilterDiv-resp').click(function(event) {
    	event.preventDefault();
    	$('#map-filter-div').toggle("slow");
    	$('#filterToggleIcon-resp').toggleClass('fa-toggle-down fa-toggle-up');
    });

    $('#filter-form-type input').click(function(event) {
    	initMap(map.getCenter(), map.getZoom());
    });
    $('#filter-form-agecat input').click(function(event) {
    	initMap(map.getCenter(), map.getZoom());
    });
    $('#filter-form-access input').click(function(event) {
    	initMap(map.getCenter(), map.getZoom());
    });
    $('#filter-form-voorzieningen input').click(function(event) {
    	initMap(map.getCenter(), map.getZoom());
    });

    $("#expand_items").click(function(event) {
		event.preventDefault();
		$("#nonPopItems").show();
		$("#expand_items").hide();
	});
    
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>

</html>