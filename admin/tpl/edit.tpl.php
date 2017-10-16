<?php
$title = "Mijn Speeltuinzoeker - voeg speeltuin toe";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>

<label>Spelregels</label>
<ul>
	<li>Vul de velden zo volledig en correct mogelijk in.</li>
	<li>Later verbeteren en/of aanvullen is natuurlijk altijd mogelijk.</li>
	<li>Na opslaan gaat de speeltuin op inactief totdat deze door de beheerder gecontroleerd is.</li>
	<li>Beledigende teksten of andere uitingen die in strijd zijn met waar deze website voor staat (een veilige omgeving voor en door ouders) zijn niet toegestaan.</li>
	<li>Speeltuinzoeker.nl behoudt zich het recht voor om speeltuinen die niet voldoen aan bovenstaande, te weigeren.</li>
</ul>
		
<form method="post" action="edit.php">

	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" id="start" name="start" value="<?php echo $start; ?>" />
	
	<?php if ($id > 0): ?>
		<p>
			<strong>Status</strong>: <?php if ($status_id == 0) echo "voorgesteld"; elseif ($status_id == 1) echo "actief"; elseif ($status_id == 2) echo "afgewezen"; ?>
		</p>
	<?php endif; ?>

	<div class="form-group">
		<label for="name">Naam van de speeltuin (bijv. Buurtspeeltuin het Haventje)</label>
		<input type="text" id="naam" name="naam" value="<?php echo $name; ?>" class="form-control" />
	</div>
	
	<div class="form-group">
		<label for="speeltuintype">Type</label>
		<select id="speeltuintype" name="speeltuintype" class="form-control">
		<?php foreach ($speeltuin->getAllTypes() as $typeOption): ?>
			<option value="<?php echo $typeOption; ?>" <?php if ($type == $typeOption) echo "selected=\"selected\""; ?>><?php echo $typeOption; ?></option>
		<?php endforeach; ?>
		</select>
	</div>
	
	<div class="form-group">
		<label for="public">Toegankelijkheid</label>
		<select id="public" name="public" class="form-control">
			<?php $paidAllowed = false; // TODO true indien betalende klant ?>
			<?php foreach ($speeltuin->getAllAccessOptions($paidAllowed) as $accessId => $accessName): ?>
				<option value="<?php echo $accessId; ?>" <?php if ($public == $accessId) echo "selected=\"selected\""; ?>><?php echo $accessName; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	
	<div id="public2div">
		<div class="form-group">
			<label for="omschrijving">Openingstijden (optioneel, max. 1000 tekens)</label>
			<textarea id="openingstijden" name="openingstijden" rows="3" maxlength="1000" class="form-control"><?php echo $openingstijden; ?></textarea>
		</div>
		<div class="form-group">
			<label for="omschrijving">Kleine (vrijwillige) vergoeding (optioneel, max. 1000 tekens, bijv. "De speeltuinvereniging stelt het op prijs als je 50 cent per kind doneert.")</label>
			<textarea id="vergoeding" name="vergoeding" rows="3" maxlength="1000" class="form-control"><?php echo $vergoeding; ?></textarea>
		</div>
	</div>
	
	<div class="form-group">
		<label>Leeftijdscategorie(&euml;n)</label>
		<?php foreach ($speeltuin->getAllAgecats() as $agecatOptionColname => $agecatOptionName): ?>
			<div class="checkbox">
				<label>
					<input type="checkbox" id="<?php echo $agecatOptionColname; ?>" name="<?php echo $agecatOptionColname; ?>" value="1" class="form-control" <?php if (($agecatOptionColname == "agecat_1" && $agecat1) || ($agecatOptionColname == "agecat_2" && $agecat2) || ($agecatOptionColname == "agecat_3" && $agecat3)) echo "checked=\"checked\""; ?> />
					<?php echo $agecatOptionName; ?>
				</label>
			</div>
		<?php endforeach; ?>
	</div>
	
	<div class="form-group">
		<label for="link">Link (optioneel, bijv. http://www.speeltuinvereniging.nl/speeltuin)</label>
		<input type="text" id="link" name="link" value="<?php echo $link; ?>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="omschrijving">Korte omschrijving (max. 1000 tekens)</label>
		<textarea id="omschrijving" name="omschrijving" rows="3" maxlength="1000" class="form-control"><?php echo $omschrijving; ?></textarea>
	</div>

	<h2>Locatie</h2>

	<div class="form-group">
		<label for="omschrijving">Omschrijving van de locatie (bijv. Fazantweg, Paterswolde)</label>
		<textarea id="locatie_omschrijving" name="locatie_omschrijving" rows="1" maxlength="1000" class="form-control"><?php echo $locatieOmschrijving; ?></textarea>
	</div>
	
	<?php if ($isUser): ?>
		<button id="place-marker" value="Zet marker op hierboven omschreven locatie" class="btn btn-default">Zet marker op hierboven omschreven locatie</button>
	<?php endif; ?>
	
	<div id="map-div-edit"></div>
	
	<?php if ($isUser): ?>
		<p><em>Staat de groene marker nog niet op de goede plaats?</em> Sleep hem er dan heen.</p>
	<?php endif; ?>
	
	<div class="form-group">
		<label for="lat">Breedtegraad</label>
		<input type="text" id="lat" name="lat" value="<?php echo $lat; ?>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="lon">Lengtegraad</label>
		<input type="text" id="lon" name="lon" value="<?php echo $lon; ?>" class="form-control" />
	</div>

	<h2>Voorzieningen</h2>
	
	<p id="missing-item"><em>Mist er een voorziening?</em>
	Stuur een mailtje met uitleg aan <a href="mailto:info@speeltuinzoeker.nl">info@speeltuinzoeker.nl</a>
	om een voorziening toe te laten voegen.</p>

	<div class="form-group">
		<?php foreach ($selectedVoorzieningen as $voorzieningId => $voorzieningNaam): ?>
			<div class="checkbox">
				<label>
					<input type="checkbox" id="v<?php echo $voorzieningId; ?>" name="v<?php echo $voorzieningId; ?>" value="1" class="form-control" checked="checked" />
					<?php echo $voorzieningNaam; ?>
				</label>
			</div>
		<?php endforeach; ?>
		<?php foreach ($allVoorzieningenPop as $voorzieningId => $voorzieningNaam): ?>
			<div class="checkbox">
				<label>
					<input type="checkbox" id="v<?php echo $voorzieningId; ?>" name="v<?php echo $voorzieningId; ?>" value="1" class="form-control" />
					<?php echo $voorzieningNaam; ?>
				</label>
			</div>
		<?php endforeach; ?>
		<?php if (sizeof($allVoorzieningenNonPop) > 0): ?>
			<a href="#" id="expand_items"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i>&nbsp;Meer</a>
			<div id="nonPopItems">
				<?php foreach ($allVoorzieningenNonPop as $voorzieningId => $voorzieningNaam): ?>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="v<?php echo $voorzieningId; ?>" name="v<?php echo $voorzieningId; ?>" value="1" class="form-control" />
							<?php echo $voorzieningNaam; ?>
						</label>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<h2>Foto's</h2>
	<?php if (isset($photos) && sizeof($photos) > 0): ?>
		<div class="photobar">
			<?php foreach ($photos as $photo): ?>
				<img src="<?php echo $photo; ?>" alt="Foto van deze speeltuin" title="Foto van deze speeltuin" />
			<?php endforeach; ?>
		</div>
		<p id="photo-info">Je kunt foto's toevoegen en verwijderen vanuit het overzicht van je speeltuinen (klik op de link "Foto's" in de meest rechtse kolom).</p>
	<?php else: ?>
		<p id="photo-info">Er zijn nog geen foto's.
		<?php if ($isUser): ?>
			Je kunt deze <?php if ($id ==0): ?>na het opslaan<?php endif; ?> vanuit het overzicht van je speeltuinen toevoegen (klik op de link "Foto's" in meest rechtse kolom).</p>
		<?php endif; ?>
	<?php endif; ?>
	
	<hr>
	<div class="buttonbar">
		<?php if ($isUser): ?>
			<input type="submit" name="Submit" value="Opslaan" class="btn btn-default" />
			<input id="cancel" type="button" value="Annuleren" class="btn btn-default" />
		<?php else: // admin ?>
			<?php if ($status_id == 0): // voorgesteld ?>
				<div class="form-group">
					<input type="submit" name="Submit" value="Keur goed" class="btn btn-default" />
				</div>
				<div class="form-group">
					<input type="submit" name="Submit" value="Keur af" class="btn btn-default" /> met reden:
					<textarea id="afkeur_reden" name="afkeur_reden" rows="1" maxlength="1000" class="form-control"></textarea>
				</div>
				<div class="form-group">
					<input id="cancel" type="button" value="Terug" class="btn btn-default" />
				</div>
			<?php else: ?>
				<input id="cancel" type="button" value="Terug" class="btn btn-default" />
			<?php endif; ?>
		<?php endif; ?>
	</div>

</form>

<script>

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
		<?php if ($id == 0 && $lat == 0.0 && $lon == 0.0): // Nieuw? Dan in eerste instantie op huidige locatie zetten ?>
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
				$.get("../_markers.php?ne=" + NE + "&sw=" + SW, function(data) {
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

				if (speeltuin.id == <?php echo $id; ?>) {
					continue;
				}
				
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

	    $("#cancel").click(function(event) {
	    	event.preventDefault();
	    	<?php if ($isUser): ?>
				window.location = './view.php?user';
			<?php else: // admin ?>
				window.location = './view.php?status=<?php echo $status_id; ?>';
			<?php endif; ?>
		});

		$("#expand_items").click(function(event) {
			event.preventDefault();
			$("#nonPopItems").show();
			$("#expand_items").hide();
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

		$("#public").change(function(event) {
			if ($("#public").val() == 0 || $("#public").val() == 1) {
				$("#public2div").hide();
			} else if ($("#public").val() == 2) {
				$("#public2div").show();
			}
		});

		<?php if ($public == 2): ?>
			$("#public2div").show();
		<?php endif; ?>
	});
    
</script>
<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>

<?php
//include_once "./inc/footer.php";