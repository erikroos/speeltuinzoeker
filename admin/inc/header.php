<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title><?php echo $title; ?></title>

	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/speeltuinzoeker.css" rel="stylesheet">

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="../js/bootstrap.min.js"></script>
    
    <?php if (isset($extraHeaders)) echo $extraHeaders; ?>
</head>

<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<a href="../index.php">Home</a>
			<?php if (isset($_SESSION["user_id"])): ?>
				<a href="logout.php">Log uit</a>
			<?php endif; ?>
		</div>
	</nav>

	<div class="container">