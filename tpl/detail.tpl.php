<?php $indexTitle = $speeltuin->getName() . " - Speeltuinzoeker.nl"; ?>
<?php $indexDescription = $speeltuin->getDescription(); ?>
<?php $indexDescription = empty($indexDescription) ? $indexTitle : $indexDescription; ?>
<?php if (sizeof($photos) > 0): ?>
    <?php $indexImage = $photos[0]; ?>
    <?php $indexImageAlt = "Foto van " . $speeltuin->getName(); ?>
<?php endif; ?>
<?php include "header.tpl.php"; ?>

<div class="morelink">
	<p><a href="<?php echo BASE_URL; ?>?speeltuin=<?php echo $id; ?>"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Startscherm</a></p>
</div>

<div id="affiliate-bol">
	<a href="https://partnerprogramma.bol.com/click/click?p=1&t=url&s=53838&url=https%3A//www.bol.com/nl/m/sport-vrije-tijd/outdoor/index.html&f=BAN&name=Sport algemeen" target="_blank">
		<img src="https://www.bol.com/nl/upload/banners/sport_141108_728x90.jpg" width="728" height="90" alt="Sport algemeen"  />
	</a>
	<img src="https://partnerprogramma.bol.com/click/impression?p=1&s=53838&t=url&f=BAN&name=Sport algemeen" width="1" height="1" />
</div>

<div id="postFeedback">
	<?php if (isset($reviewed)): ?>
        <?php if ($reviewed): ?>
        	<p class="notice">Beoordeling succesvol verstuurd! De beoordeling is nog niet zichtbaar tot deze gecontroleerd is. We streven ernaar dit binnen 24 uur te doen.</p>
        <?php else: ?>
        	<p class="error">Beoordeling versturen mislukt! Niet (correct) ingelogd en/of geen beoordeling of toelichting ingevuld.</p>
        <?php endif; ?>
    <?php endif; ?>
        
    <?php if (isset($sent)): ?>
        <?php if ($sent): ?>
        	<p class="notice">Verzoek succesvol verstuurd! Er staat een kopie (cc) in je mailbox.</p>
        <?php else: ?>
        	<p class="error">Verzoek versturen mislukt! Niet (correct) ingelogd en/of geen verzoek opgegeven.</p>
        <?php endif; ?>
	<?php endif; ?>
</div>

<div id="details">
	<h1><?php echo $speeltuin->getName(); ?></h1>
	<p>Aangemaakt door <?php echo $speeltuin->getAuthorName(); ?>, laatst bewerkt: <?php echo $speeltuin->getLastModified(); ?></p>
	
	<?php if (isset($_SESSION["user_id"])): ?>
	    <?php if (!isset($reviewed)): ?>
	        <p class="request">Ben je hier geweest? Laat een <a id="review" href="#">beoordeling</a> achter!</p>
	    <?php endif; ?>
	    <?php if (!isset($sent)): ?>
	        <p class="request">Klopt er iets niet? Wil je iets aanvullen? Vraag een <a id="requestChange" href="#">wijziging</a> aan bij de aanmaker van deze speeltuin.</p>
		<?php endif; ?>
	<?php else: ?>
		<p class="request">Wil je een beoordeling achterlaten of een wijziging aanvragen? <a href="<?php echo BASE_URL; ?>admin/login.php">Log dan eerst in</a>.</p>
	<?php endif; ?>
	<div class="betweenbar"></div>
	
	<?php if ($rating != null): ?>
        <span class="ratingLabel"><strong>Beoordeling</strong></span><span class="stars"><?php echo $rating["avg_rating"]; ?></span>
        <p id="times_rated">(<?php echo $rating["times_rated"]; ?> x)</p>
        <?php if ($rating["times_rated"] > 0): ?>
        	<?php setlocale(LC_TIME, "nl_NL"); ?>
	        <button id="toggleReviewsDiv" class="btn btn-default"><i id="reviewsToggleIcon" class="fa fa-caret-square-o-down" aria-hidden="true"></i>&nbsp;Lees</button>
	        <div id="reviews">
	        	<?php foreach ($reviews as $reviewRow): ?>
                    <p class="reviewRowTop"><?php echo $reviewRow["naam"]; ?> op <?php echo strftime("%A %#d %B %Y om %H:%M", strtotime($reviewRow["rated_on"])); ?></p>
                    <span class="stars"><?php echo $reviewRow["rating"]; ?></span>
                    <p class="reviewRowBottom">"<?php echo $reviewRow["comment"]; ?>"</p>
	        	<?php endforeach; ?>
	        </div>
        <?php endif; ?>
	<?php endif; ?>
	
	<p id="speeltuinProperties"><strong>Wat voor soort speeltuin is het?</strong></p>
	<ul>
        <li><?php echo $speeltuin->getPublic(); ?></li>
        <li><?php echo $speeltuin->getType(); ?></li>
        <li><?php echo $speeltuin->getAgecatString(); ?></li>
	</ul>

    <?php if ($speeltuin->getPublic() == "Beperkt toegankelijk"): ?>
        <?php $openingstijden = $speeltuin->getOpeningstijden(); ?>
        <?php if (!empty($openingstijden)): ?>
            <p><strong>Openingstijden</strong><br><?php echo $openingstijden; ?></p>
        <?php endif; ?>
        <?php $vergoeding = $speeltuin->getVergoeding(); ?>
        <?php if (!empty($vergoeding)): ?>
            <p><strong>Kleine (vrijwillige) vergoeding</strong><br><?php echo $vergoeding; ?></p>
        <?php endif; ?>
    <?php endif; ?>
	
	<?php $description = $speeltuin->getDescription(); ?>
	<?php if (!empty($description)): ?>
		<p><strong>Beschrijving</strong><br><?php echo $description; ?></p>
	<?php endif; ?>

	<?php $link = $speeltuin->getLink(); ?>
	<?php if ($link != null): ?>
        <p><strong>Link</strong><br><a href="<?php echo $link; ?>"><?php echo $link; ?></a></p>
	<?php endif; ?>
	
	<p><strong>(Adv.)</strong> Buitenspelen is nog leuker met het juiste <a href="https://partnerprogramma.bol.com/click/click?p=1&t=url&s=53838&f=TXL&url=https%3A%2F%2Fwww.bol.com%2Fnl%2Fm%2Fspeelgoed%2Fbuitenspeelgoed%2FsuggestionType%2Fcategory%2FsuggestedFor%2Fbuiten%2ForiginalSearchContext%2Fmedia_all%2ForiginalSection%2Fmain%2Findex.html%3F_requestid%3D1994306&name=buitenspeelgoed">buitenspeelgoed</a>!</p>
</div>

<div class="voorzieningen">
	<h4>Wat is hier te doen?</h4>
	<?php $voorzieningen = $speeltuin->getVoorzieningen(); ?>
	<?php if (sizeof($voorzieningen) > 0): ?>
		<ul>
	        <?php foreach ($voorzieningen as $voorziening): ?>
	            <li><?php echo $voorziening; ?></li>
	        <?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>Het lijkt erop dat er nog geen voorzieningen bekend zijn bij deze speeltuin!
		Ken je deze speeltuin en weet je wat er te doen is?
		Vraag een <a id="requestChange2" href="#">wijziging</a> aan bij de aanmaker van deze speeltuin.</p>
	<?php endif; ?>
</div>

<div class="locatie">
	<h4>Waar is het precies?</h4>
    <p><?php echo $speeltuin->getLocationDescription(); ?></p>
    <p>Geef me een <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $speeltuin->getLatitude(); ?>,<?php echo $speeltuin->getLongitude(); ?>">routebeschrijving</a></p>
    <div id="mini-map"></div>
</div>

<div class="betweenbar"></div>
    
<?php if (sizeof($photos) > 0): ?>
    <h4>Kijk eens rond</h4>
    <div class="detail-photobar">
        <?php foreach ($photos as $photo): ?>
            <div><img src="<?php echo $photo; ?>" alt="Foto van deze speeltuin" title="Foto van deze speeltuin" /></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
    
<div id="requestChangeFormDiv">
    <form id="requestChangeForm" method="post" action="detail.php">
        <input type="hidden" id="speeltuin" name="speeltuin" value="<?php echo $id; ?>" />
        <input type="hidden" id="userId" name="userId" value="<?php echo $_SESSION["user_id"]; ?>" />
        <div class="form-group">
			<label for="comment">Opmerking(en) (max. 1000 tekens)</label>
        	<textarea id="comment" name="comment" class="form-control" maxlength="1000" rows="4"></textarea>
        </div>
        <input type="submit" value="Verstuur" id="submitRequestChange" class="btn btn-default" />
        <p><em>Er gaat een kopie naar jezelf en de beheerder van deze site.</em></p>
	</form>
</div>
    
<div id="reviewFormDiv">
    <form id="reviewForm" method="post" action="detail.php">
        <input type="hidden" id="speeltuin" name="speeltuin" value="<?php echo $id; ?>" />
        <input type="hidden" id="userId" name="userId" value="<?php echo $_SESSION["user_id"]; ?>" />
        <div class="form-group">
			<label for="rating">Beoordeling (kies 0-5 sterren)</label>
			<input type="number" name="rating" data-clearable  class="rating" />
        </div>
        <div class="form-group">
			<label for="comment">Toelichting (max. 1000 tekens)</label>
        	<textarea id="comment" name="comment" class="form-control" maxlength="1000" rows="4"></textarea>
        </div>
        <input type="submit" value="Verstuur" class="btn btn-default" />
	</form>
</div>
    
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo BASE_URL; ?>js/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo BASE_URL; ?>js/bootstrap.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/bootstrap-rating-input.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>slick/slick.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
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

		$.fn.scrollView = function () {
		    return this.each(function () {
		        $('html, body').animate({
		            scrollTop: $(this).offset().top
		        }, 1000);
		    });
		}

  		$("#requestChange").click(function(){
  			$("#requestChangeForm").show();
  			$('#requestChangeFormDiv').scrollView();
	  	});

  		$("#requestChange2").click(function(){
  			$("#requestChangeForm").show();
  			$('#requestChangeFormDiv').scrollView();
	  	});

  		$("#review").click(function(){
  			$("#reviewForm").show();
  			$('#reviewFormDiv').scrollView();
	  	});

  		$('#toggleReviewsDiv').click(function(event) {
  	    	event.preventDefault();
  	    	$('#reviews').toggle("slow");
  	    	$('#reviewsToggleIcon').toggleClass('fa-caret-square-o-down fa-caret-square-o-up');
  	    });

  		$.fn.stars = function() {
  		    return $(this).each(function() {
  		        // Get the value
  		        var val = parseFloat($(this).html());
  		        // Make sure that the value is in 0 - 5 range, multiply to get width
  		        var size = Math.max(0, (Math.min(5, val))) * 16;
  		        // Create stars holder
  		        var $span = $('<span />').width(size);
  		        // Replace the numerical value with stars
  		        $(this).html($span);
  		    });
  		};

  		$('span.stars').stars();
	});
</script>

<?php include "footer.tpl.php"; ?>

<!-- Map -->
<script type="text/javascript">
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
            map: map,
            icon: "<?php echo BASE_URL; ?>img/marker_green.png"
        });
        marker.setPosition(pos);
    };
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>

</html>