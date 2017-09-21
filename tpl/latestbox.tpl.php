<p>
    <?php echo $totalNrOfUsers; ?> actieve gebruikers beheren samen al <strong><?php echo $totalNr; ?></strong> speeltuinen.
    <a href="join.php">Doe mee!</a>
</p>
<?php if ($latestSpeeltuin != null): ?>
    <p>
        De nieuwste: <strong><?php echo $latestSpeeltuin["naam"]; ?></strong><br>
        <?php if (!empty($latestSpeeltuin["locatie_omschrijving"])): ?>
            <?php $locOmschrijving = $latestSpeeltuin["locatie_omschrijving"]; ?>
            <?php if (strlen($locOmschrijving) > 40) $locOmschrijving = substr($locOmschrijving, 0, 37) . "..."; ?>
            <?php echo $locOmschrijving; ?><br>
        <?php endif; ?>
        <a href="detail.php?speeltuin=<?php echo $latestSpeeltuin["id"]; ?>">Bekijk</a>
    </p>
<?php endif; ?>