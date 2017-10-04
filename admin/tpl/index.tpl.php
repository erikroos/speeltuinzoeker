<?php
$title = "Mijn Speeltuinzoeker";
include_once "./inc/header.php";
?>

<?php if ($_SESSION["password_generated"] == 1): ?>
<p class="notice">Je gebruikt een automatisch gegenereerd wachtwoord.
We raden je aan (weer) een <a href="account.php">eigen wachtwoord in te stellen</a>.</p>
<?php endif; ?>

<?php if ($msgBody != null): ?>
<p class="notice"><?php echo $msgBody; ?></p>
<?php endif; ?>

<?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1): ?>
<h1>Welkom beheerder</h1>
<p>
	<a href="view.php?status=0">Voorgestelde speeltuinen (<?php echo $nrProposed; ?>)</a><br>
	<a href="view.php?status=1">Actieve speeltuinen (<?php echo $nrActive; ?>)</a><br>
	<a href="view.php?status=2">Afgewezen speeltuinen (<?php echo $nrRejected; ?>)</a><br>
</p>
<p>
	<a href="review.php?status=0">Voorgestelde beoordelingen (<?php echo $nrProposedReviews; ?>)</a><br>
	<a href="review.php?status=1">Actieve beoordelingen (<?php echo $nrActiveReviews; ?>)</a><br>
	<a href="review.php?status=2">Afgewezen beoordelingen (<?php echo $nrRejectedReviews; ?>)</a><br>
</p>
<p>
	<a href="users.php">Gebruikers (<?php echo $nrOfUsers; ?>)</a><br>
	<a href="items.php">Voorzieningen (<?php echo $nrOfItems; ?>)</a><br>
</p>
<?php else: ?>
<h1>Welkom <?php echo $_SESSION["user_name"]; ?></h1>
<p>
	<a href="edit.php?id=0"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Voeg speeltuin toe</a><br>
	<a href="view.php?user"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;Bekijk mijn speeltuinen</a><br>
	<a href="review.php?user"><i class="fa fa-comments" aria-hidden="true"></i>&nbsp;Bekijk mijn beoordelingen</a><br>
</p>
<?php endif; ?>

<?php
include_once "./inc/footer.php";