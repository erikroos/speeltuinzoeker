<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title><?php echo $title; ?></title>
	
	<link rel="icon" type="image/x-icon" href="../favicon.ico">

	<link href="<?php echo BASE_URL; ?>css/bootstrap.min.css" rel="stylesheet">
	
	<link href="<?php echo BASE_URL; ?>font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>slick/slick.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>slick/slick-theme.css"/>
	
	<link href="<?php echo BASE_URL; ?>css/speeltuinzoeker.css" rel="stylesheet">
	<link href="<?php echo BASE_URL; ?>css/speeltuinzoeker-responsive.css" rel="stylesheet">

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="<?php echo BASE_URL; ?>js/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="<?php echo BASE_URL; ?>js/bootstrap.min.js"></script>
    
    <?php if (isset($extraHeaders)) echo $extraHeaders; ?>
</head>

<body>
	<div class="container">
	
		<nav id="mainMenu">
		  <div class="navWide">
		  	<div class="wideDiv">
			  	<a href="index.php"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;Home</a>
				<?php if (isset($_SESSION["user_id"])): ?>
					<a href="account.php"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Mijn account</a>
					<a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Uitloggen</a>
				<?php endif; ?>
				<a href="../index.php" class="admin"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Speeltuinzoeker.nl</a>
			  </div>
		  </div>
		  <div class="navNarrow">
		  	<i class="fa fa-bars fa-2x"></i>
		    <div class="narrowLinks hidden">
		    	<a href="index.php"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;Home</a>
				<?php if (isset($_SESSION["user_id"])): ?>
					<a href="account.php"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Mijn account</a>
					<a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Uitloggen</a>
				<?php endif; ?>
				<a href="../index.php" class="admin"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Speeltuinzoeker.nl</a>
		    </div>
		  </div>
		</nav>
		<script type="text/javascript">
            navLinks = document.querySelector('.navNarrow');
            narrowLinks = document.querySelector('.narrowLinks');
            mainMenu = document.querySelector('#mainMenu');
            navLinks.addEventListener('click', toggle);
            function toggle() {
                narrowLinks.classList.toggle('hidden');
                mainMenu.classList.toggle('open');
            };
		</script>