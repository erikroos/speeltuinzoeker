<?php 
if (!isset($indexDescription)) {
	$indexDescription = "De (mobiele) website om snel en makkelijk een speeltuin in jouw buurt te vinden. Speeltuinzoeker laat kinderen spelen!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $indexTitle; ?></title>
    
    <meta name="description" content="<?php echo $indexDescription; ?>">
	<meta name="keywords" content="speeltuin,speelplein,speelplaats,gratis,zoeken,vinden,kind,kinderen,spelen">
	<meta name="author" content="<?php echo AUTHOR; ?>">
	
	<meta property="og:title" content="<?php echo $indexTitle; ?>">
	<meta property="og:description" content="<?php echo $indexDescription; ?>">
    <?php if (isset($indexImage)): ?>
    <meta property="og:image" content="<?php echo $indexImage; ?>">
    <meta property="og:image:alt" content="<?php echo $indexImageAlt; ?>">
    <?php else: ?>
	<meta property="og:image" content="<?php echo BASE_URL; ?>img/logo_groot.png">
    <meta property="og:image:alt" content="Logo van Speeltuinzoeker">
    <?php endif; ?>
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo substr(BASE_URL, 0, -1); ?><?php echo parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); ?>">
    <meta property="fb:app_id" content="1959895767582132">

    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>favicon.ico">

    <link href="<?php echo BASE_URL; ?>css/bootstrap.min.css" rel="stylesheet">
    
    <link href="<?php echo BASE_URL; ?>font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>slick/slick.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>slick/slick-theme.css"/>

    <link href="<?php echo BASE_URL; ?>css/speeltuinzoeker.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>css/speeltuinzoeker-responsive.css" rel="stylesheet">

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

<?php if ($indexTitle == "Speeltuinzoeker.nl - Laat ze spelen!"): // home ?>
    <div id="locationTip" class="tip"><strong>Tip</strong> voor een optimale werking: zet de Locatie (GPS) van je device aan en sta deze website toe deze uit te lezen.</div>
	<div id="jsTip" class="tip">Zet <strong>JavaScript</strong> aan in je browser om deze website te laten werken.</div>
<?php endif; ?>

<div class="container">

	<nav id="mainMenu">
	  <div class="navWide">
	  	<div class="wideDiv">
		  	<a href="<?php echo BASE_URL; ?>"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Home</a>
		  	<a href="<?php echo BASE_URL; ?>atoz"><i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>&nbsp;Alle speeltuinen</a>
		    <a href="<?php echo BASE_URL; ?>over"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Info</a>
		    <a href="<?php echo BASE_URL; ?>meedoen"><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;Meedoen</a>
            <a href="<?php echo BASE_URL; ?>privacy"><i class="fa fa-user-secret" aria-hidden="true"></i>&nbsp;Privacy</a>
		    <a href="<?php echo BASE_URL; ?>contact"><i class="fa fa-comments" aria-hidden="true"></i>&nbsp;Contact</a>
		    <a href="<?php echo BASE_URL; ?>admin/index.php" class="admin"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Mijn Speeltuinzoeker</a>
		  </div>
	  </div>
	  <div class="navNarrow">
	  	<i class="fa fa-bars fa-2x"></i>
	    <div class="narrowLinks hidden">
	    	<a href="<?php echo BASE_URL; ?>"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Home</a>
		    <a href="<?php echo BASE_URL; ?>atoz"><i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>&nbsp;Alle speeltuinen</a>
		    <a href="<?php echo BASE_URL; ?>over"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Info</a>
		    <a href="<?php echo BASE_URL; ?>meedoen"><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;Meedoen</a>
            <a href="<?php echo BASE_URL; ?>privacy"><i class="fa fa-user-secret" aria-hidden="true"></i>&nbsp;Privacy</a>
		    <a href="<?php echo BASE_URL; ?>contact"><i class="fa fa-comments" aria-hidden="true"></i>&nbsp;Contact</a>
		    <a href="<?php echo BASE_URL; ?>admin/index.php" class="admin"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Mijn Speeltuinzoeker</a>
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

    <div class="logo">
    	<img src="<?php echo BASE_URL; ?>img/logo_compleet.png" />
    </div>
    <div class="logo-resp">
    	<img src="<?php echo BASE_URL; ?>img/logo_klein.png" />
    </div>