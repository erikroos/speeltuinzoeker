<?php
$title = "Mijn Speeltuinzoeker - beheer account";
include_once "./inc/header.php";
?>

<h1>Mijn account</h1>

<p><em>We hoeven niet veel van je te weten en we gaan zorgvuldig om met de gegevens die we wel nodig hebben.<br>
Het enige wat we van je opslaan is een zelfgekozen naam, je e-mailadres, het aantal keer en de laatste keer dat je was ingelogd.<br>
Deze gegevens delen we met niemand.</em></p>

<p><strong>E-mail</strong><br><?php echo $email; ?></p>

<p><strong>Aantal keer ingelogd</strong><br><?php echo $nrOfLogins; ?></p>

<p><strong>Laatste keer</strong><br><?php echo $lastLogin; ?></p>

<?php if (isset($feedback)): ?>
<p class="notice"><?php echo $feedback; ?></p>
<?php endif; ?>

<form method="post" action="account.php">

	<div class="form-group">
		<label for="naam">Zelfgekozen naam</label>
		<input type="text" id="naam" name="naam" value="<?php echo $name; ?>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="password">Wachtwoord - laat leeg om niet te wijzigen</label>
		<input type="password" id="password" name="password" value="" class="form-control" />
	</div>
	
	<div class="form-group">
		<label for="password2">Wachtwoord (herhaal) - laat leeg om niet te wijzigen</label>
		<input type="password" id="password2" name="password2" value="" class="form-control" />
	</div>

	<hr>
	<div class="buttonbar">
		<input type="submit" name="Submit" value="Wijzigen" class="btn btn-default" />
		<input type="submit" name="Submit" value="Account opheffen" class="btn btn-default" onclick="return confirm('Weet je het zeker?');" />
	</div>

</form>

<?php
//include_once "./inc/footer.php";