<?php
$title = "Mijn Speeltuinzoeker - bekijk en beheer gebruikers";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>

<p><a href="./index.php"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Terug</a></p>

<p>
<?php if ($active == 1): ?>
	<a href="users.php?active=0"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;Schakel over naar <strong>inactieve</strong> gebruikers</a>
<?php else: ?>
	<a href="users.php?active=1"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;Schakel over naar <strong>actieve</strong> gebruikers</a>
<?php endif; ?>
</p>

<div class="search">
	<form method="get" action="users.php">
		<input type="hidden" id="start" name="start" value="<?php echo $start; ?>" />
		<input type="hidden" id="size" name="size" value="<?php echo $size; ?>" />
		<input type="hidden" id="active" name="active" value="<?php echo $active; ?>" />
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
			<li><a href="users.php?active=<?php echo $active; ?>&start=0&size=<?php echo $size; ?>">&lt;&lt;</a></li>
			<li><a href="users.php?active=<?php echo $active; ?>&start=<?php echo max(0, ($start - $size)); ?>&size=<?php echo $size; ?>">&lt;</a></li>
			<li><?php echo $start + 1; ?> - <?php echo min($totalSize, ($start + $size)); ?> van <?php echo $totalSize; ?></a></li>
			<li><a href="users.php?active=<?php echo $active; ?>&start=<?php $newStart = min($totalSize, ($start + $size)); echo ($newStart - ($newStart % $size)); ?>&size=<?php echo $size; ?>">&gt;</a></li>
			<li><a href="users.php?active=<?php echo $active; ?>&start=<?php echo ($totalSize - ($totalSize % $size)); ?>&size=<?php echo $size; ?>">&gt;&gt;</a></li>
		</ul>
	</span>
</div>
<?php endif; ?>

<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>Naam</th>
				<th>E-mail</th>
				<th>Aantal logins</th>
				<th>Laatste login</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($rows as $row): ?>
			<tr>
				<td><?php echo $row["naam"]; ?></td>
				<td><?php echo $row["email"]; ?></td>
				<td><?php echo $row["nr_of_logins"]; ?></td>
				<td><?php echo $row["last_login"]; ?></td>
				<?php if ($active == 0): ?>
					<td><a href="users.php?del=<?php echo $row["id"]; ?>&start=<?php echo $start; ?>&active=<?php echo $active;?>">Verwijder</a></td>
				<?php else: ?>
					<td><a href="deactivate.php?id=<?php echo $row["id"]; ?>&start=<?php echo $start; ?>&active=<?php echo $active;?>">Deactiveer</a></td>
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