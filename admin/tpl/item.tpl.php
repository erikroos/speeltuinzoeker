<?php
$title = "Mijn Speeltuinzoeker - bewerk voorziening";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>

<?php if (isset($feedback)): ?>
<p class="notice"><?php echo $feedback; ?></p>
<?php endif; ?>

<form method="post" action="item.php">

	<div class="form-group">
		<label for="naam">Naam</label>
		<input type="text" id="naam" name="naam" value="<?php echo $name; ?>" class="form-control" />
	</div>

	<hr>
	<div class="buttonbar">
		<input type="submit" name="Submit" value="Opslaan" class="btn btn-default" />
	</div>

</form>

<?php
//include_once "./inc/footer.php";