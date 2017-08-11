<?php include "header.tpl.php"; ?>

    <div id="details">
        <span class="back-btn"><a href="index.php?speeltuin=<?php echo $id; ?>">Terug</a></span>
        <h3><?php echo $speeltuin->getName(); ?></h3>
        <p><?php echo $speeltuin->getDescription(); ?></p>
    </div>

    <div class="detail-photobar">
        <?php foreach ($speeltuin->getPhotos() as $photo): ?>
            <img src="<?php echo $photo; ?>" alt="Foto van deze speeltuin" />
        <?php endforeach; ?>
    </div>

    <div class="voorzieningen">
        <h4>Wat is hier te doen?</h4>
        <ul>
        <?php foreach ($speeltuin->getVoorzieningen() as $voorziening): ?>
            <li><?php echo $alleVoorzieningen[$voorziening]; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>

    <div class="locatie">
        <h4>Waar is het precies?</h4>
        <p><?php echo $speeltuin->getLocationDescription(); ?></p>
        <div id="mini-map"></div>
    </div>

<?php include "footer.tpl.php"; ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initMap"></script>

</html>