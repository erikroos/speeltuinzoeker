<?php
$title = "Mijn Speeltuinzoeker - bekijk en beheer voorzieningen";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>

<p><a href="index.php"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Terug</a></p>

<p><a href="item.php?id=0"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Voeg toe</a></p>

<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Populair</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($rows as $row): ?>
			<tr>
				<td><?php echo $row["naam"]; ?></td>
				<td><?php echo $row["popular"] == 1 ? "Ja" : "Nee"; ?></td>
				<td><a href="item.php?id=<?php echo $row["id"]; ?>">Bewerk</a></td>
				<td><a href="item.php?del=1&id=<?php echo $row["id"]; ?>">Verwijder</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php
//include_once "./inc/footer.php";