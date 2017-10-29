<?php $indexTitle = "Speeltuinzoeker.nl - Meedoen"; ?>
<?php include "header.tpl.php"; ?>

<?php if (isset($feedback)): ?>
	<div id="suggestionFeedback" class="notice"><?php echo $feedback; ?></div>
	<div class="betweenbar"></div>
<?php endif; ?>

<div id="details">
	<p>
		<strong>Doe mee(r) met Speeltuinzoeker!</strong><br>
		Deze website is voor en door de gebruikers zelf. We helpen elkaar door speeltuinen aan te maken, zodat de site altijd zo volledig en actueel mogelijk is.<br>
		Maak een <a href="<?php echo BASE_URL; ?>admin/register.php">account</a> aan en krijg toegang tot Mijn Speeltuinzoeker.<br>
		Daar kun je zelf speeltuinen aanmaken, bewerken en foto's toevoegen.
		Ook kun je, als ingelogde gebruiker, andere speeltuinen beoordelen.<br>
		Maak vandaag nog snel en eenvoudig je <a href="<?php echo BASE_URL; ?>admin/register.php">account</a> aan!<br>
		En wees niet bang: we gaan je niet spammen met nieuwsbrieven, tevredenheidsonderzoeken en andere ellende :)
	</p>
	<p>
		<strong>Liever geen account aanmaken?</strong><br>
		Heb je geen zin om een account aan te maken maar wil je wel een speeltuin voorstellen?<br>
		Dat kan! <a id="quickformOpenLink" href="#">Open het snelle formulier.</a>
	</p>
</div>

<div id="quickform">

	<label>Spelregels</label>
	<ul>
		<li>Vul de velden zo volledig en correct mogelijk in.</li>
		<li>Na opslaan staat de speeltuin op inactief totdat deze door de beheerder gecontroleerd en waar nodig aangevuld is.</li>
		<li>Beledigende teksten of andere uitingen die in strijd zijn met waar deze website voor staat (een veilige omgeving voor en door ouders) zijn niet toegestaan.</li>
		<li>Speeltuinzoeker.nl behoudt zich het recht voor om speeltuinen die niet voldoen aan bovenstaande, te weigeren.</li>
	</ul>

	<form method="post" action="join.php" enctype="multipart/form-data">
		<div class="form-group">
			<label for="name">Naam van de speeltuin (bijv. Buurtspeeltuin het Haventje)</label>
			<input type="text" id="naam" name="naam" value="" class="form-control" />
		</div>
		
		<div class="form-group">
			<label for="omschrijving">Zo volledig mogelijke omschrijving (max. 1000 tekens)</label>
			<textarea id="omschrijving" name="omschrijving" rows="3" maxlength="1000" class="form-control"></textarea>
		</div>
		
		<div class="form-group">
			<label for="omschrijving">Omschrijving van de locatie (bijv. Fazantweg, Paterswolde)</label>
			<textarea id="locatie_omschrijving" name="locatie_omschrijving" rows="1" maxlength="1000" class="form-control"></textarea>
		</div>
		<button id="place-marker" value="Zet marker op hierboven omschreven locatie" class="btn btn-default">Zet marker op hierboven omschreven locatie</button>
		<div id="map-div-edit"></div>
		<p><em>Staat de groene marker nog niet op de goede plaats?</em> Sleep hem er dan heen.</p>
		<input type="hidden" id="lat" name="lat" value="0.0" />
		<input type="hidden" id="lon" name="lon" value="0.0" />
		
		<div class="form-group">
			<label for="photo">Foto (formaat JP(E)G of PNG; er mogen g&eacute;&eacute;n personen opstaan)</label>
			<input type="file" id="photo" name="photo" class="form-control" />
		</div>
		
		<hr>
		<input type="submit" name="Submit" value="Voorstellen" class="btn btn-default" />
	</form>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo BASE_URL; ?>js/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->

<script type="text/javascript">
var map;
var marker;
var existingMarkers = [];
var infowindow = null;

function initMap() {
	map = new google.maps.Map(document.getElementById('map-div-edit'), {
	  zoom: 15
	});

	// the draggable marker for the current speeltuin
	marker = new google.maps.Marker({
		map: map,
		draggable: true,
		icon: "<?php echo BASE_URL; ?>img/marker_green.png"
	});
	marker.addListener('dragend', function() {
		var lat = marker.getPosition().lat();
		var lng = marker.getPosition().lng();
		$('#lat').val(lat);
		$('#lon').val(lng);
	});

	// position the map
	setDefaultPos();
	// Try HTML5 geolocation.
    if (navigator.geolocation) {
    	navigator.geolocation.getCurrentPosition(function(currentPosition) {
	        var pos = {
	            lat: currentPosition.coords.latitude,
	            lng: currentPosition.coords.longitude
	        };
	        map.setCenter(pos);
			marker.setPosition(pos);
			$('#lat').val(currentPosition.coords.latitude);
			$('#lon').val(currentPosition.coords.longitude);
        }, function() {
          	setDefaultPos();
        });
    } else {
    	// Browser doesn't support Geolocation, leave on defaultpos
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
						"<p>" + speeltuin.omschrijving + "</p>" //+
						//"<p><a href='speeltuinen/" + speeltuin.seo_url + "'>Meer</a>"
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
    marker.setPosition(backupPos);
}

$(document).on('ready', function() {
	$("#quickformOpenLink").click(function(event) {
		event.preventDefault();
		$("#quickform").show();
		initMap();
	});

	$('#place-marker').click(function(event) {
		event.preventDefault();

		if ($('#locatie_omschrijving').val() == "") {
            return;
        }
		
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
				$('#lat').val(lat);
				$('#lon').val(lng);
			}
		);
	});
});
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>

<?php include "footer.tpl.php"; ?>

</html>