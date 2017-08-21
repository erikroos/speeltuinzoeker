<?php
$title = "Mijn Speeltuinzoeker - wachtwoord vergeten";
include_once "./inc/header.php";
?>

<h1>Gebruiker <?php echo $user->getName(); ?> deactiveren</h1>

<?php if (isset($feedback)): ?>
<p class="notice"><?php echo $feedback; ?></p>
<?php endif; ?>

<form method="post" action="deactivate.php">

	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />

	<div class="form-group">
		<label for="reason">Reden</label>
		<input type="text" id="reason" name="reason" value="" class="form-control" />
	</div>

	<hr>
	<div class="buttonbar">
		<input type="submit" name="Submit" value="Deactiveer" class="btn btn-default" />
	</div>

</form>

<?php
//include_once "./inc/footer.php";