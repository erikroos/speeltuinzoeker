<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Speeltuinzoeker.nl - Laat ze spelen!</title>

    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <link href="font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">

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
		  	<a href="./index.php"><i class="fa fa-home" aria-hidden="true"></i>&nbsp; Home</a>
		    <a href="./about.php">Info</a>
		    <a href="./join.php">Meedoen</a>
		    <a href="./contact.php">Contact</a>
		    <a href="./admin/index.php" class="admin">Mijn Speeltuinzoeker</a>
		  </div>
	  </div>
	  <div class="navNarrow">
	  	<i class="fa fa-bars fa-2x"></i>
	    <div class="narrowLinks hidden">
	    	<a href="./index.php"><i class="fa fa-home" aria-hidden="true"></i>&nbsp; Home</a>
	    	<a href="./about.php">Info</a>
	        <a href="./join.php">Meedoen</a>
	        <a href="./contact.php">Contact</a>
	        <a href="./admin/index.php" class="admin">Mijn Speeltuinzoeker</a>
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

    <div class="logo"></div>