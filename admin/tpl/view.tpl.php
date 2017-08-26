<?php
$title = "Mijn Speeltuinzoeker - bekijk en beheer speeltuinen";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>

<?php if ($isAdmin): ?>
<p><a href="./index.php"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Terug</a></p>
<?php endif; ?>

<?php if (isset($_SESSION["feedback"])): ?>
<p class="notice"><?php echo $_SESSION["feedback"]; ?></p>
<?php unset($_SESSION["feedback"]); ?>
<?php endif; ?>

<?php if ($_SESSION["password_generated"] == 1): ?>
<p class="notice">Je gebruikt een automatisch gegenereerd wachtwoord.
We raden je aan (weer) een <a href="account.php">eigen wachtwoord in te stellen</a>.</p>
<?php endif; ?>

<?php if ($isUser): ?>
<p><a href="edit.php?id=0&start=<?php echo $start; ?>"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Voeg speeltuin toe</a></p>
<?php endif; ?>

<div class="search">
	<form method="get" action="view.php">
		<input type="hidden" id="start" name="start" value="<?php echo $start; ?>" />
		<input type="hidden" id="size" name="size" value="<?php echo $size; ?>" />
		<?php if (isset($_GET["user"])): ?>
			<input type="hidden" id="user" name="user" value="1" />
		<?php endif; ?>
		<?php if (isset($_GET["status"])): ?>
			<input type="hidden" id="status" name="status" value="<?php echo $status; ?>" />
		<?php endif; ?>
		<input type="text" id="q" name="q" value="<?php echo $q; ?>" class="form-control" />
		<button id="search-btn" value="Zoek" class="btn btn-default"><i class='fa fa-search' aria-hidden='true'></i>&nbsp;Zoek</button>
		<button id="clear-search-btn" value="Maak leeg" class="btn btn-default"><i class='fa fa-ban' aria-hidden='true'></i>&nbsp;Maak leeg</button>
	</form>
</div>
<div class="betweenbar"></div>

<?php if (empty($q)): ?>
<div id="pager">
	<span class="pager">
		<ul>
			<li><a href="view.php?<?php echo $isUser ? "user" : "status=" . $status; ?>&start=0&size=<?php echo $size; ?>">&lt;&lt;</a></li>
			<li><a href="view.php?<?php echo $isUser ? "user" : "status=" . $status; ?>&start=<?php echo max(0, ($start - $size)); ?>&size=<?php echo $size; ?>">&lt;</a></li>
			<li><?php echo $start + 1; ?> - <?php echo min($totalSize, ($start + $size)); ?> van <?php echo $totalSize; ?></a></li>
			<li><a href="view.php?<?php echo $isUser ? "user" : "status=" . $status; ?>&start=<?php $newStart = min($totalSize, ($start + $size)); echo ($newStart - ($newStart % $size)); ?>&size=<?php echo $size; ?>">&gt;</a></li>
			<li><a href="view.php?<?php echo $isUser ? "user" : "status=" . $status; ?>&start=<?php echo ($totalSize - ($totalSize % $size)); ?>&size=<?php echo $size; ?>">&gt;&gt;</a></li>
		</ul>
	</span>
</div>
<?php endif; ?>

<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Omschrijving</th>
				<th>Locatieomschrijving</th>
				<th>Aantal voorzieningen</th>
				<th>Aantal foto's</th>
				<th>Auteur</th>
				<th>Status</th>
				<th></th>
				<?php if ($isUser): ?>
				<th></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($rows as $row): ?>
			<tr>
				<td><a href="./edit.php?id=<?php echo $row["id"]; ?>&start=<?php echo $start; ?>"><?php echo $row["naam"]; ?></a></td>
				<td><?php echo strlen($row["omschrijving"]) > 50 ? substr($row["omschrijving"], 0, 50) . "..." : $row["omschrijving"]; ?></td>
				<td><?php echo $row["locatie_omschrijving"]; ?></td>
				<td><?php echo $row["aantalVoorzieningen"]; ?></td>
				<td><?php echo $row["aantalBestanden"]; ?></td>
				<td><?php echo $row["userNaam"]; ?></td>
				<td><?php echo $row["status"]; ?></td>
				<td>
					<a href="./edit.php?id=<?php echo $row["id"]; ?>&start=<?php echo $start; ?>">
					<?php if ($isUser): ?>
						Bewerk
					<?php elseif ($isAdmin): ?>
						<?php if ($status == 0): // nieuw ?>
							Keur goed of af
						<?php else: // goed- of afgekeurd ?>
							Bekijk
						<?php endif; ?>
					<?php endif; ?>
					</a>
				</td>
				<?php if ($isUser): ?>
				<td><a href="photo.php?id=<?php echo $row["id"]; ?>&start=<?php echo $start; ?>">Foto's</a></td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
$(document).on('ready', function() {
	$("#clear-search-btn").click(function() {
		$("#q").val("");
	});
});
</script>

<?php
//include_once "./inc/footer.php";