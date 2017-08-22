<?php
$title = "Mijn Speeltuinzoeker - beheer account";
include_once "./inc/header.php";
?>

<h1>Mijn account</h1>

<p><strong>E-mail</strong><br><?php echo $email; ?></p>

<?php if (isset($feedback)): ?>
<p class="notice"><?php echo $feedback; ?></p>
<?php endif; ?>

<form method="post" action="account.php">

	<div class="form-group">
		<label for="naam">Naam</label>
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
	</div>

</form>

<?php
//include_once "./inc/footer.php";