<?php
$title = "Mijn Speeltuinzoeker - bekijk/bewerk beoordeling";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>
		
<form method="post" action="edit_review.php">

	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" id="start" name="start" value="<?php echo $start; ?>" />
	
	<p>
		<strong>Status</strong>: <?php if ($status == 0) echo "voorgesteld"; elseif ($status == 1) echo "actief"; elseif ($status == 2) echo "afgewezen"; ?>
	</p>

	<div class="form-group">
		<label for="speeltuinId">Speeltuin</label>
		<select id="speeltuinId" name="speeltuinId" class="form-control">
		<?php foreach ($speeltuin->getAllSpeeltuinen() as $speeltuinOption): ?>
			<option value="<?php echo $speeltuinOption["id"]; ?>" <?php if ($speeltuinId == $speeltuinOption["id"]) echo "selected=\"selected\""; ?>><?php echo $speeltuinOption["naam"]; ?></option>
		<?php endforeach; ?>
		</select>
	</div>
	
	<div class="form-group">
		<label for="rating">Beoordeling</label>
		<input type="text" id="rating" name="rating" value="<?php echo $rating; ?>" class="form-control" />
	</div>
	

	<div class="form-group">
		<label for="comment">Toelichting (max. 1000 tekens)</label>
		<textarea id="comment" name="comment" rows="3" maxlength="1000" class="form-control"><?php echo $comment; ?></textarea>
	</div>
	
	<hr>
	<div class="buttonbar">
		<?php if ($isUser): ?>
			<input type="submit" name="Submit" value="Opslaan" class="btn btn-default" />
			<input id="cancel" type="button" value="Annuleren" class="btn btn-default" />
		<?php else: // admin ?>
			<?php if ($status == 0): // voorgesteld ?>
				<div class="form-group">
					<input type="submit" name="Submit" value="Keur goed" class="btn btn-default" />
				</div>
				<div class="form-group">
					<input type="submit" name="Submit" value="Keur af" class="btn btn-default" /> met reden:
					<textarea id="afkeur_reden" name="afkeur_reden" rows="1" maxlength="1000" class="form-control"></textarea>
				</div>
				<div class="form-group">
					<input id="cancel" type="button" value="Terug" class="btn btn-default" />
				</div>
			<?php else: ?>
				<input id="cancel" type="button" value="Terug" class="btn btn-default" />
			<?php endif; ?>
		<?php endif; ?>
	</div>

</form>

<script type="text/javascript">
$(document).on('ready', function() {
    $("#cancel").click(function(event) {
    	event.preventDefault();
    	<?php if ($isUser): ?>
			window.location = './review.php?user';
		<?php else: // admin ?>
			window.location = './review.php?status=<?php echo $status; ?>';
		<?php endif; ?>
	});
});
</script>

<?php
//include_once "./inc/footer.php";