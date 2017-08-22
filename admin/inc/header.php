<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title><?php echo $title; ?></title>
	
	<link rel="icon" type="image/x-icon" href="../favicon.ico">

	<link href="../css/bootstrap.min.css" rel="stylesheet">
	
	<link href="../font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
	
	<link href="../css/speeltuinzoeker.css" rel="stylesheet">
	<link href="../css/speeltuinzoeker-responsive.css" rel="stylesheet">

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="../js/bootstrap.min.js"></script>
    
    <?php if (isset($extraHeaders)) echo $extraHeaders; ?>
</head>

<body>
	<div class="container">
	
		<nav>
		  <div class="navWide">
		  	<div class="wideDiv">
			  	<a href="../index.php"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Speeltuinzoeker.nl</a>
		    	<a href="<?php if ($_SESSION["admin"]): ?>index.php<?php else: ?>view.php?user<?php endif; ?>"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;Home</a>
				<?php if (isset($_SESSION["user_id"])): ?>
					<a href="account.php"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Mijn account</a>
					<a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Uitloggen</a>
				<?php endif; ?>
			  </div>
		  </div>
		  <div class="navNarrow">
		  	<i class="fa fa-bars fa-2x"></i>
		    <div class="narrowLinks hidden">
		    	<a href="../index.php"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Speeltuinzoeker.nl</a>
		    	<a href="<?php if ($_SESSION["admin"]): ?>index.php<?php else: ?>view.php?user<?php endif; ?>"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;Home</a>
				<?php if (isset($_SESSION["user_id"])): ?>
					<a href="account.php"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Mijn account</a>
					<a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Uitloggen</a>
				<?php endif; ?>
		    </div>
		  </div>
		</nav>
		<script type="text/javascript">
			navLinks = document.querySelector('.navNarrow');
			narrowLinks = document.querySelector('.narrowLinks');
			navLinks.addEventListener('click', toggle);
			function toggle() {
			    narrowLinks.classList.toggle('hidden');
			};
		</script>