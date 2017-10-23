<?php
$title = "Mijn Speeltuinzoeker - bekijk en beheer notificaties";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>

<p><a href="index.php"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Terug</a></p>
<p><a href="message.php?id=0"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Voeg toe</a></p>
<div class="betweenbar"></div>

<div id="pager">
	<span class="pager">
		<ul>
			<li><a href="messages.php?start=0&size=<?php echo $size; ?>">&lt;&lt;</a></li>
			<li><a href="messages.php?start=<?php echo max(0, ($start - $size)); ?>&size=<?php echo $size; ?>">&lt;</a></li>
			<li><?php echo $start + 1; ?> - <?php echo min($totalSize, ($start + $size)); ?> van <?php echo $totalSize; ?></a></li>
			<li><a href="messages.php?start=<?php $newStart = min($totalSize, ($start + $size)); echo ($newStart - ($newStart % $size)); ?>&size=<?php echo $size; ?>">&gt;</a></li>
			<li><a href="messages.php?start=<?php echo ($totalSize - ($totalSize % $size)); ?>&size=<?php echo $size; ?>">&gt;&gt;</a></li>
		</ul>
	</span>
</div>

<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>Bericht</th>
				<th>Aangemaakt op</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($rows as $row): ?>
			<tr>
				<td><?php echo $row["body"]; ?></td>
				<td><?php echo $row["created_on"]; ?></td>
				<td><a href="message.php?id=<?php echo $row["id"]; ?>">Bewerk</a></td>
				<td><a href="message.php?del=1&id=<?php echo $row["id"]; ?>">Verwijder</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php
//include_once "./inc/footer.php";