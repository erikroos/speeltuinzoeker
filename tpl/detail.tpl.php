<?php include "header.tpl.php"; ?>

    <div id="details">
        <a href="index.php?speeltuin=<?php echo $id; ?>"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Terug</a>
        <h3><?php echo $speeltuin->getName(); ?></h3>
        <p>Aangemaakt door <?php echo $speeltuin->getAuthorName(); ?>, laatst bewerkt: <?php echo $speeltuin->getLastModified(); ?></p>
        <p class="request">Klopt er iets niet?
        <?php if (isset($_SESSION["user_id"])): ?>
        	Vraag een <a href="request_change.php?id=<?php echo $id; ?>">wijziging</a> aan bij de aanmaker van deze speeltuin.
        <?php else: ?>
        	<a href="admin/login.php">Log in</a> om een wijziging aan te vragen bij de aanmaker van deze speeltuin.
        <?php endif; ?>
        </p>
        <p><?php echo $speeltuin->getPublic(); ?></p>
        <p><?php echo $speeltuin->getDescription(); ?></p>
    </div>

    <div class="detail-photobar">
        <?php foreach ($photos as $photo): ?>
            <div><img src="<?php echo $photo; ?>" alt="Foto van deze speeltuin" /></div>
        <?php endforeach; ?>
    </div>

    <div class="voorzieningen">
        <h4>Wat is hier te doen?</h4>
        <ul>
        <?php foreach ($speeltuin->getVoorzieningen() as $voorziening): ?>
            <li><?php echo $voorziening; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>

    <div class="locatie">
        <h4>Waar is het precies?</h4>
        <p><?php echo $speeltuin->getLocationDescription(); ?></p>
        <div id="mini-map"></div>
    </div>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="slick/slick.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.detail-photobar').slick({
				centerMode: true,
				centerPadding: '60px',
				variableWidth: true,
				dots: true,
				arrows: true,
				infinite: true,
				slidesToShow: 1<?php //echo sizeof($photos); ?>,
				slidesToScroll: 1<?php //echo sizeof($photos); ?>,
				responsive: [
				    {
				    	breakpoint: 800,
				    	settings: {
					    	centerMode: false,
					    	arrows: false,
				      		slidesToShow: 1,
				      		slidesToScroll: 1
				    	}
				    }
				    // You can unslick at a given breakpoint now by adding:
				    // settings: "unslick"
				    // instead of a settings object
			  ]
	  		});
		});
	</script>

<?php include "footer.tpl.php"; ?>

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
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>

</html>