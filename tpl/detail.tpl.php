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

</head>

<body>
	<?php include "tpl/topbar.tpl.php"; ?>
	
    <div class="container">
		<h1>Speeltuinzoeker.nl</h1>
		<h2>Laat ze spelen!</h2>

		<span class="back-btn"><a
			href="index.php?speeltuin=<?php echo $id; ?>">Terug</a></span>
		<h3><?php echo $speeltuin->getName(); ?></h3>
		<p><?php echo $speeltuin->getDescription(); ?></p>

		<div class="detail-photobar"
			<?php foreach ($speeltuin->getPhotos() as $photo): ?>
			
			<img src="<?php echo $photo; ?>" alt="Foto van deze speeltuin" />
		<?php endforeach; ?>
		</div>

		<div class="voorzieningen">
			<h4>Voorzieningen</h4>
			<ul>
			<?php foreach ($speeltuin->getVoorzieningen() as $voorziening): ?>
				<li><?php echo $alleVoorzieningen[$voorziening]; ?></li>
			<?php endforeach; ?>
			</ul>
		</div>

		<div class="locatie">
			<h4>Locatie</h4>
			<p><?php echo $speeltuin->getLocationDescription(); ?></p>
			<div id="mini-map"></div>
		</div>

	</div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>

	<!-- Map -->
	<script>
        var map;
        
        function initMap() {
            map = new google.maps.Map(document.getElementById('mini-map'), {
                zoom: 17
            });
            var pos = {
            	lat: <?php echo $speeltuin->getLatitude(); ?>,
            	lng: <?php echo $speeltuin->getLongitude(); ?>
            };
            map.setCenter(pos);
            var marker = new google.maps.Marker({
                map: map
            });
            marker.setPosition(pos);   
        }
    </script>
	<script async defer
		src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>

</body>
</html>