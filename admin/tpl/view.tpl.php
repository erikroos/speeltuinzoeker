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

<?php if ($isUser): ?>
<p><a href="edit.php?id=0">Voeg speeltuin toe</a></p>
<?php endif; ?>

<div>
	<span class="pager">
		<ul>
			<li><a href="view.php?<?php echo $isUser ? "user" : "status=" . $status; ?>&start=0&size=<?php echo $size; ?>">&lt;&lt;</a></li>
			<li><a href="view.php?<?php echo $isUser ? "user" : "status=" . $status; ?>&start=<?php echo max(0, ($start - $size)); ?>&size=<?php echo $size; ?>">&lt;</a></li>
			<li><?php echo $start + 1; ?> - <?php echo min($totalSize, ($start + $size)); ?> van <?php echo $totalSize; ?></a></li>
			<li><a href="view.php?<?php echo $isUser ? "user" : "status=" . $status; ?>&start=<?php $newStart = min($totalSize, ($start + $size)); echo ($newStart - ($newStart % $size)); ?>&size=<?php echo $size; ?>">&gt;</a></li>
			<li><a href="view.php?<?php echo $isUser ? "user" : "status=" . $status; ?>&start=<?php $newStart = ($totalSize - $size); echo ($newStart - ($newStart % $size)); ?>&size=<?php echo $size; ?>">&gt;&gt;</a></li>
		</ul>
	</span>
</div>

<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>Naam</th>
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
				<td><?php echo $row["naam"]; ?></td>
				<td><?php echo $row["locatie_omschrijving"]; ?></td>
				<td><?php echo $row["aantalVoorzieningen"]; ?></td>
				<td><?php echo $row["aantalBestanden"]; ?></td>
				<td><?php echo $row["userNaam"]; ?></td>
				<td><?php echo $row["status"]; ?></td>
				<td>
					<?php if ($isUser): ?>
						<a href="./edit.php?id=<?php echo $row["id"]; ?>">Bewerk</a>
					<?php elseif ($isAdmin): ?>
						<?php if ($status == 0): ?>
							<a href="./edit.php?id=<?php echo $row["id"]; ?>">Keur goed of af</a>
						<?php elseif ($status == 1): ?>
							<a href="./edit.php?id=<?php echo $row["id"]; ?>">Bekijk</a>
						<?php elseif ($status == 2): ?>
							<a href="./edit.php?id=<?php echo $row["id"]; ?>">Bekijk</a>
						<?php endif; ?>
					<?php endif; ?>
				</td>
				<?php if ($isUser): ?>
				<td><a href="photo.php?id=<?php echo $row["id"]; ?>">Foto's</a></td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php
//include_once "./inc/footer.php";