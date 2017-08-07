<?php
$title = "Mijn Speeltuinzoeker - maak account aan";
include_once "./inc/header.php";
?>

<h1>Maak een account aan</h1>

<?php if (isset($feedback)): ?>
<p class="notice"><?php echo $feedback; ?></p>
<?php endif; ?>

<?php if ($showForm): ?>
<form method="post" action="register.php">

	<div class="form-group">
		<label for="naam">Naam</label> <input type="text" id="naam"
			name="naam" value="<?php echo $name; ?>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="email">E-mail</label> <input type="text" id="email"
			name="email" value="<?php echo $email; ?>" class="form-control" />
	</div>

	<div class="form-group">
		<label for="password">Wachtwoord</label> <input type="password"
			id="password" name="password" value="<?php echo $password; ?>"
			class="form-control" />
	</div>

	<hr>
	<div class="buttonbar">
		<input type="submit" name="Submit" value="Aanmaken"
			class="btn btn-default" />
	</div>

</form>
<?php endif; ?>

<?php
include_once "./inc/footer.php";