<?php
$title = "Mijn Speeltuinzoeker - maak account aan";
include_once "./inc/header.php";
?>

<h1>Maak een account aan</h1>

<p><em>We hoeven niet veel van je te weten en we gaan zorgvuldig om met de gegevens die we wel nodig hebben.<br>
Het enige wat we van je opslaan is een zelfgekozen naam, je e-mailadres, het aantal keer en de laatste keer dat je was ingelogd.<br>
Deze gegevens delen we met niemand. Je mag ze altijd inzien en je kunt op elk moment je account weer verwijderen.<br>
En wees niet bang: we gaan je nooit spammen met nieuwsbrieven, tevredenheidsonderzoeken en andere ellende :)</em></p>

<?php if (isset($feedback)): ?>
<p class="notice"><?php echo $feedback; ?></p>
<?php endif; ?>

<?php if ($showForm): ?>
<form method="post" action="register.php">

	<div class="form-group">
		<label for="naam">Zelfgekozen naam</label>
		<input type="text" id="naam" name="naam" value="<?php echo $name; ?>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="email">E-mail</label>
		<input type="text" id="email" name="email" value="<?php echo $email; ?>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="password">Wachtwoord</label>
		<input type="password" id="password" name="password" value="" class="form-control" />
	</div>
	
	<div class="form-group">
		<label for="password2">Wachtwoord (herhaal)</label>
		<input type="password" id="password2" name="password2" value="" class="form-control" />
	</div>
	
	<div class="form-group">
		<label for="password">Anti-spamvraag: 1 + 1 =</label>
		<input type="text" id="antispam" name="antispam" value="" class="form-control" />
	</div>

	<hr>
	<div class="buttonbar">
		<input type="submit" name="Submit" value="Aanmaken" class="btn btn-default" />
	</div>

</form>
<?php endif; ?>

<?php
//include_once "./inc/footer.php";