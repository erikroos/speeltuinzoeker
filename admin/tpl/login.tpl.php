<?php
$title = "Mijn Speeltuinzoeker - log in";
include_once "./inc/header.php";
?>

<h1>Log in</h1>

<?php if (isset($feedback)): ?>
<p class="notice"><?php echo $feedback; ?></p>
<?php endif; ?>

<form method="post" action="login.php">

	<div class="form-group">
		<label for="name">E-mail</label> <input type="text" id="login"
			name="login" value="" class="form-control" />
	</div>

	<div class="form-group">
		<label for="name">Wachtwoord</label> <input type="password"
			id="password" name="password" value="" class="form-control" />
	</div>

	<hr>
	<div class="buttonbar">
		<input type="submit" name="Submit" value="Inloggen"
			class="btn btn-default" />
	</div>

</form>

<p>
	<a href="register.php">Ik heb nog geen account en wil er graag een
		aanmaken.</a>
</p>

<?php
include_once "./inc/footer.php";