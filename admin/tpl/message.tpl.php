<?php
$title = "Mijn Speeltuinzoeker - bewerk notificatie";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>

<?php if (isset($feedback)): ?>
<p class="notice"><?php echo $feedback; ?></p>
<?php endif; ?>

<form method="post" action="message.php">

	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />

	<div class="form-group">
		<label for="naam">Bericht</label>
		<textarea id="body" name="body" rows="3" maxlength="1000" class="form-control"><?php echo $body; ?></textarea>
	</div>
	
	<hr>
	<div class="buttonbar">
		<input type="submit" name="Submit" value="Opslaan" class="btn btn-default" />
	</div>

</form>

<?php
//include_once "./inc/footer.php";