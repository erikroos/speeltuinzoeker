<?php 
$title = "Mijn Speeltuinzoeker";
include_once "./inc/header.php";
?>

<?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1): ?>
	<h1>Welkom beheerder</h1>
	<p>
		<a href="view.php?status=1">Bekijk alle actieve speeltuinen</a><br>
		<a href="view.php?status=0">Bekijk alle voorgestelde speeltuinen</a><br>
		<a href="view.php?status=2">Bekijk alle afgewezen speeltuinen</a><br>
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