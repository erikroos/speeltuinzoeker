<p>
    <?php echo $totalNrOfUsers; ?> actieve gebruikers beheren samen al <strong><?php echo $totalNr; ?></strong> speeltuinen.
    <a href="<?php echo BASE_URL; ?>meedoen">Doe mee!</a>
</p>
<?php if ($latestSpeeltuin != null): ?>
    <p>
        De nieuwste: <strong><?php echo $latestSpeeltuin["naam"]; ?></strong><br>
        <?php if (!empty($latestSpeeltuin["locatie_omschrijving"])): ?>
            <?php $locOmschrijving = $latestSpeeltuin["locatie_omschrijving"]; ?>
            <?php if (strlen($locOmschrijving) > 40) $locOmschrijving = substr($locOmschrijving, 0, 37) . "..."; ?>
            <?php echo $locOmschrijving; ?><br>
        <?php endif; ?>
        <a href="<?php echo BASE_URL; ?>speeltuinen/<?php echo $latestSpeeltuin["seo_url"]; ?>">Bekijk</a>
    </p>
<?php endif; ?>