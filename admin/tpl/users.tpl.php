<?php
$title = "Mijn Speeltuinzoeker - bekijk en beheer gebruikers";
include_once "./inc/header.php";
?>

<h1><?php echo $pageTitle; ?></h1>

<p><a href="./index.php"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Terug</a></p>

<p>
<?php if ($active == 1): ?>
	<a href="users.php?active=0">Schakel over naar <strong>inactieve</strong> gebruikers</a>
<?php else: ?>
	<a href="users.php?active=1">Schakel over naar <strong>actieve</strong> gebruikers</a>
<?php endif; ?>
</p>

<div>
	<span class="pager">
		<ul>
			<li><a href="users.php?active=<?php echo $active; ?>&start=0&size=<?php echo $size; ?>">&lt;&lt;</a></li>
			<li><a href="users.php?active=<?php echo $active; ?>&start=<?php echo max(0, ($start - $size)); ?>&size=<?php echo $size; ?>">&lt;</a></li>
			<li><?php echo $start + 1; ?> - <?php echo min($totalSize, ($start + $size)); ?> van <?php echo $totalSize; ?></a></li>
			<li><a href="users.php?active=<?php echo $active; ?>&start=<?php $newStart = min($totalSize, ($start + $size)); echo ($newStart - ($newStart % $size)); ?>&size=<?php echo $size; ?>">&gt;</a></li>
			<li><a href="users.php?active=<?php echo $active; ?>&start=<?php $newStart = ($totalSize - $size); echo ($newStart - ($newStart % $size)); ?>&size=<?php echo $size; ?>">&gt;&gt;</a></li>
		</ul>
	</span>
</div>

<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>Naam</th>
				<th>E-mail</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($rows as $row): ?>
			<tr>
				<td><?php echo $row["naam"]; ?></td>
				<td><?php echo $row["email"]; ?></td>
				<td></td>
				<td></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php
//include_once "./inc/footer.php";