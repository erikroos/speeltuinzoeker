<?php
$title = "Mijn Speeltuinzoeker";
include_once "./inc/header.php";
?>

<?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1): ?>
<h1>Welkom beheerder</h1>
<p>
	<a href="view.php?status=1">Actieve speeltuinen (<?php echo $nrActive; ?>)</a><br>
	<a href="view.php?status=0">Voorgestelde speeltuinen (<?php echo $nrProposed; ?>)</a><br>
	<a href="view.php?status=2">Afgewezen speeltuinen (<?php echo $nrRejected; ?>)</a><br>
</p>
<p>
	<a href="users.php">Gebruikers (<?php echo $nrOfUsers; ?>)</a><br>
	<a href="items.php">Voorzieningen (<?php echo $nrOfItems; ?>)</a><br>
</p>
<?php else: ?>
<h1>Welkom <?php echo $_SESSION["user_name"]; ?></h1>
<p>
	<a href="edit.php?id=0">Voeg speeltuin toe</a><br>
	<a href="view.php?user">Bekijk mijn speeltuinen</a>
</p>
<?php endif; ?>

<?php
include_once "./inc/footer.php";