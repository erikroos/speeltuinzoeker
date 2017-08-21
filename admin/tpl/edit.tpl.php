<?php
$title = "Mijn Speeltuinzoeker - voeg speeltuin toe";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>

<form method="post" action="edit.php">

	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" id="start" name="start" value="<?php echo $start; ?>" />
	
	<?php if ($id > 0): ?>
	<p>
		<strong>Status</strong>: <?php if ($status_id == 0) echo "voorgesteld"; elseif ($status_id == 1) echo "actief"; elseif ($status_id == 2) echo "afgewezen"; ?></p>
	<?php endif; ?>

	<div class="form-group">
		<label for="name">Naam</label>
		<input type="text" id="naam" name="naam" value="<?php echo $name; ?>" class="form-control" />
	</div>
	
	<div class="form-group">
		<label for="public">Toegankelijkheid</label>
		<select id="public" name="public" class="form-control">
			<!--option value="0" <?php //if ($public == 0) echo "selected=\"selected\""; ?>>Betaald</option-->
			<option value="1" <?php if ($public == 1) echo "selected=\"selected\""; ?>>Gratis en altijd toegankelijk</option>
			<option value="2" <?php if ($public == 2) echo "selected=\"selected\""; ?>>Gratis maar beperkt toegankelijk</option>
		</select>
	</div>

	<div class="form-group">
		<label for="omschrijving">Korte omschrijving van de speeltuin (max. 1000 tekens)</label>
		<textarea id="omschrijving" name="omschrijving" rows="3" maxlength="1000" class="form-control"><?php echo $omschrijving; ?></textarea>
	</div>

	<h2>Locatie op de kaart</h2>

	<div class="form-group">
		<label for="omschrijving">Omschrijving van de locatie (bijv. Fazantweg, Paterswolde)</label>
		<textarea id="locatie_omschrijving" name="locatie_omschrijving"
			rows="1" maxlength="1000" class="form-control"><?php echo $locatieOmschrijving; ?></textarea>
	</div>
	
	<?php if ($isUser): ?>
		<button id="place-marker" value="Zet marker op omschreven locatie"
		class="btn btn-default">Zet marker op omschreven locatie</button>
	<?php endif; ?>
	
	<div id="map-div-edit"></div>
	
	<?php if ($isUser): ?>
		<p>Staat de marker nog niet op de goede plaats? Sleep hem er dan heen.</p>
	<?php endif; ?>
	
	<div class="form-group">
		<label for="lat">Breedtegraad</label> <input type="text" id="lat"
			name="lat" value="<?php echo $lat; ?>" />
	</div>

	<div class="form-group">
		<label for="lon">Lengtegraad</label> <input type="text" id="lon"
			name="lon" value="<?php echo $lon; ?>" />
	</div>

	<h2>Voorzieningen</h2>
	
	<p id="missing-item"><em>Mist er een voorziening?</em>
	Stuur een mailtje met uitleg aan <a href="mailto:info@speeltuinzoeker.nl">info@speeltuinzoeker.nl</a>
	om een voorziening toe te laten voegen.</p>

	<div class="form-group">
		<?php foreach ($allVoorzieningen as $voorzieningId => $voorzieningNaam): ?>
			<div class="checkbox">
				<label>
					<input type="checkbox" id="v<?php echo $voorzieningId; ?>" name="v<?php echo $voorzieningId; ?>" value="1" class="form-control" <?php if (in_array($voorzieningId, $selectedVoorzieningen)) echo "checked=\"checked\""; ?>>
					<?php echo $voorzieningNaam; ?>
				</label>
			</div>
		<?php endforeach; ?>
	</div>

	<h2>Foto's</h2>
	<?php if (isset($photos) && sizeof($photos) > 0): ?>
		<div class="photobar">
			<?php foreach ($photos as $photo): ?>
				<img src="<?php echo $photo; ?>" alt="Foto van deze speeltuin" title="Foto van deze speeltuin" />
			<?php endforeach; ?>
		</div>
		<p id="photo-info">Je kunt foto's toevoegen en verwijderen vanuit het overzicht van je speeltuinen.</p>
	<?php else: ?>
		<p id="photo-info">Er zijn nog geen foto's.
		<?php if ($isUser): ?>
			Je kunt deze <?php if ($id ==0): ?>na het opslaan<?php endif; ?> vanuit het overzicht van je speeltuinen toevoegen.</p>
		<?php endif; ?>
	<?php endif; ?>
	
	<hr>
	<div class="buttonbar">
		<?php if ($isUser): ?>
			<input type="submit" name="Submit" value="Opslaan"
			class="btn btn-default" /> <input id="cancel" type="button"
			value="Annuleren" class="btn btn-default" />
		<?php else: // admin ?>
			<?php if ($status_id == 0): // voorgesteld ?>
				<div class="form-group">
			<input type="submit" name="Submit" value="Keur goed"
				class="btn btn-default" />
		</div>
		<div class="form-group">
			<input type="submit" name="Submit" value="Keur af"
				class="btn btn-default" /> met reden:
			<textarea id="afkeur_reden" name="afkeur_reden" rows="1"
				maxlength="1000" class="form-control"></textarea>
		</div>
		<div class="form-group">
			<input id="cancel" type="button" value="Terug"
				class="btn btn-default" />
		</div>
			<?php else: ?>
				<input id="cancel" type="button" value="Terug"
			class="btn btn-default" />
			<?php endif; ?>
		<?php endif; ?>
	</div>

</form>

<script>

	var map;
	var marker;

	function initMap() {
		var infoWindow;
		
		map = new google.maps.Map(document.getElementById('map-div-edit'), {
		  zoom: 15
		});
		
		marker = new google.maps.Marker({
		  map: map,
		  draggable: true
		});

		marker.addListener('dragend', function() {
			var lat = marker.getPosition().lat();
			var lng = marker.getPosition().lng();
			$('#lat').val(lat);
			$('#lon').val(lng);
		});
		
		//map.addListener('bounds_changed', function() {
		//	var newBounds = map.getBounds();
		//	var NE = newBounds.getNorthEast();
		//	var SW = newBounds.getSouthWest();
		//	document.getElementById('map-info').innerHTML = "Bounding box: NE " + NE.toString() + " SW " + SW.toString();
		//});
		
		infoWindow = new google.maps.InfoWindow;

		<?php if ($id == 0 && $lat == 0.0 && $lon == 0.0): // Nieuw? Dan in eerste instantie op huidige locatie zetten ?>
        // Try HTML5 geolocation.
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
			$('#lat').val(currentPosition.coords.latitude);
			$('#lon').val(currentPosition.coords.longitude);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
		<?php else: ?>
		var pos = {
              lat: <?php echo $lat; ?>,
              lng: <?php echo $lon; ?>
        };
		map.setCenter(pos);
		marker.setPosition(pos);
		$('#lat').val(<?php echo $lat; ?>);
		$('#lon').val(<?php echo $lon; ?>);
		<?php endif; ?>
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
				
				$('#lat').val(lat);
				$('#lon').val(lng);
			}
		);
	});

	$(document).on('ready', function() {
	    $("#cancel").click(function() {
	    	event.preventDefault();
	    	<?php if ($isUser): ?>
				window.location = './view.php?user';
			<?php else: // admin ?>
				window.location = './view.php?status=<?php echo $status_id; ?>';
			<?php endif; ?>
		});
	});
    
</script>
<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>

<?php
//include_once "./inc/footer.php";