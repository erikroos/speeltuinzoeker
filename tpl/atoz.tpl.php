<?php $indexTitle = "Speeltuinzoeker.nl - Alle speeltuinen"; ?>
<?php include "header.tpl.php"; ?>

<div id="atoz">
	<?php foreach ($allSpeeltuinen as $firstLetter => $speeltuinArray): ?>
        <button class="letterButton btn btn-default" id="<?php echo $firstLetter; ?>"><?php echo strtoupper($firstLetter); ?></button>
	<?php endforeach; ?>
    <div class="betweenbar"></div>
    <div class="search">
        <input type="text" id="q" name="q" value="<?php echo $q; ?>" class="form-control" />
        <button id="search-btn" value="Zoek" class="btn btn-default"><i class='fa fa-search' aria-hidden='true'></i>&nbsp;Zoek</button>
    </div>
</div>

<div class="betweenbar"></div>
<?php if ($results): ?>
    <div id="results">
        <ul>
            <?php foreach ($speeltuinen as $speeltuin): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>speeltuinen/<?php echo $speeltuin["seo_url"]; ?>"><?php echo $speeltuin["naam"]; ?></a>
                    <?php if (!empty($speeltuin["locatie_omschrijving"])): ?>
                        <?php echo "- " . $speeltuin["locatie_omschrijving"]; ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <p>Zoek in alle speeltuinen! Kies eerst een beginletter of zoek op (een deel van) de naam en (locatie)omschrijving.</p>
<?php endif; ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo BASE_URL; ?>js/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo BASE_URL; ?>js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(".letterButton").click(function() {
            window.location = "atoz.php?letter=" + this.id;
        });

        $("#search-btn").click(function() {
            window.location = "atoz.php?q=" + $("#q").val();
        });
    });
</script>

<?php include "footer.tpl.php"; ?>

</html>