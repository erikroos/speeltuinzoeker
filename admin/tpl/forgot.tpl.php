<?php
$title = "Mijn Speeltuinzoeker - wachtwoord vergeten";
include_once "./inc/header.php";
?>

<h1>Wachtwoord vergeten?</h1>

<?php if (isset($feedback)): ?>
<p class="notice"><?php echo $feedback; ?></p>
<?php endif; ?>

<?php if ($showForm): ?>
<p>Vul je bij ons bekende e-mailadres in en we sturen je een nieuw (tijdelijk) wachtwoord toe.</p>

<form method="post" action="forgot.php">

	<div class="form-group">
		<label for="email">E-mail</label>
		<input type="text" id="email" name="email" value="" class="form-control" />
	</div>

	<hr>
	<div class="buttonbar">
		<input type="submit" name="Submit" value="Reset" class="btn btn-default" />
	</div>

</form>
<?php endif; ?>

<?php
//include_once "./inc/footer.php";