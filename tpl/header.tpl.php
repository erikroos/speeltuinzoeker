<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $indexTitle; ?></title>
    
    <meta name="description" content="De (mobiele) website om snel en makkelijk een speeltuin in jouw buurt te vinden. Speeltuinzoeker laat kinderen spelen!">
	<meta name="keywords" content="speeltuin,speelplein,speelplaats,gratis,zoeken,vinden,kind,kinderen,spelen">
	<meta name="author" content="Erik Roos">
	
	<meta name="og:title" content="Speeltuinzoeker.nl - Laat ze spelen!">
	<meta name="og:description" content="De (mobiele) website om snel en makkelijk een speeltuin in jouw buurt te vinden. Speeltuinzoeker laat kinderen spelen!">
	<meta name="og:image" content="https://www.speeltuinzoeker.nl/img/logo_compleet.png">

    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <link href="font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="slick/slick.css">
    <link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>

    <link href="css/speeltuinzoeker.css" rel="stylesheet">
    <link href="css/speeltuinzoeker-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- AdSense ACTIVATE WHEN READY -->
    <!--script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
	  (adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-6261352840965150",
		enable_page_level_ads: true
	  });
	</script-->

</head>
<body>

<div class="container">

	<nav>
	  <div class="navWide">
	  	<div class="wideDiv">
		  	<a href="./index.php"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Home</a>
		    <a href="./about.php"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Info</a>
		    <a href="./join.php"><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;Meedoen</a>
		    <a href="./contact.php"><i class="fa fa-comments" aria-hidden="true"></i>&nbsp;Contact</a>
		    <a href="./admin/<?php echo (!isset($_SESSION["user_id"]) ? "index.php" : ($_SESSION["admin"] == 1 ? "index.php" : "view.php?user")); ?>" class="admin"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Mijn Speeltuinzoeker</a>
		  </div>
	  </div>
	  <div class="navNarrow">
	  	<i class="fa fa-bars fa-2x"></i>
	    <div class="narrowLinks hidden">
	    	<a href="./index.php"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Home</a>
		    <a href="./about.php"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Info</a>
		    <a href="./join.php"><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;Meedoen</a>
		    <a href="./contact.php"><i class="fa fa-comments" aria-hidden="true"></i>&nbsp;Contact</a>
		    <a href="./admin/<?php echo (!isset($_SESSION["user_id"]) ? "index.php" : ($_SESSION["admin"] == 1 ? "index.php" : "view.php?user")); ?>" class="admin"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Mijn Speeltuinzoeker</a>
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

    <div class="logo">
    	<img src="img/logo_compleet.png" />
    </div>
    <div class="logo-resp">
    	<img src="img/logo_klein.png" />
    </div>