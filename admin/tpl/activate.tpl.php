<?php
$title = "Mijn Speeltuinzoeker - activeer aanmelding";
include_once "./inc/header.php";
?>

<h1>Aanmelding activeren</h1>

<?php if ($success): ?>
	<p>Je hebt je aanmelding succesvol geactiveerd!
	<a href="login.php">Log in</a> om te beginnen met het invoeren en beoordelen van speeltuinen.</p>
<?php else: ?>
	<p>Het activeren van je aanmelding is helaas mislukt. Controleer of de link in de adresbalk klopt met die in de
	e-mail die je hebt gekregen. De link staat ook onderin in de e-mail, zodat je hem in de adresbalk van je
	browser kunt plakken.</p>
<?php endif; ?>

<?php
include_once "./inc/footer.php";