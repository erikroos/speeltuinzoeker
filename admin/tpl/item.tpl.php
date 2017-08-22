<?php
$title = "Mijn Speeltuinzoeker - bewerk voorziening";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>

<?php if (isset($feedback)): ?>
<p class="notice"><?php echo $feedback; ?></p>
<?php endif; ?>

<form method="post" action="item.php">

	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />

	<div class="form-group">
		<label for="naam">Naam</label>
		<input type="text" id="naam" name="naam" value="<?php echo $name; ?>" class="form-control" />
	</div>
	
	<div class="form-group">
		<div class="checkbox">
			<label>
				<input type="checkbox" id="popular" name="popular" value="1" class="form-control" <?php if ($popular == 1) echo "checked=\"checked\""; ?>>
				Populair (toon direct in bewerkscherm)
			</label>
		</div>
	</div>
	

	<hr>
	<div class="buttonbar">
		<input type="submit" name="Submit" value="Opslaan" class="btn btn-default" />
	</div>

</form>

<?php
//include_once "./inc/footer.php";