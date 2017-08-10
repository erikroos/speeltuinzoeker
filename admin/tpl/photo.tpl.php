<?php
$title = "Mijn Speeltuinzoeker - foto's";
$extraHeaders = <<<EOH
	<!-- Voor fileinput -->
    <link href="../css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
	<!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
	<!-- link href="path/to/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css" /-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<!-- piexif.min.js is only needed for restoring exif data in resized images and when you 
	      wish to resize images before upload. This must be loaded before fileinput.min.js -->
	<script src="../js/plugins/piexif.min.js" type="text/javascript"></script>
	<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
	     This must be loaded before fileinput.min.js -->
	<script src="../js/plugins/sortable.min.js" type="text/javascript"></script>
	<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files.
	     This must be loaded before fileinput.min.js -->
	<script src="../js/plugins/purify.min.js" type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="../js/fileinput.min.js"></script>
	<!-- optionally if you need translation for your language then include 
	    locale file as mentioned below -->
	<script src="../js/locales/nl.js"></script>
EOH;
include_once "./inc/header.php";
?>

<h1>Voeg foto's toe aan speeltuin <?php echo $name; ?></h1>

<form method="post" action="photo.php" enctype="multipart/form-data">

	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" /> <label>Spelregels</label>
	<ul>
		<li>Bestandsformaat JP(E)G of PNG.</li>
		<li>Foto's worden tijdens het uploaden indien nodig verkleind totdat de langste kant <?php echo MAX_PHOTO_DIM; ?> pixels is.</li>
		<li>Foto's worden tijdens het uploaden omgezet naar PNG.</li>
		<li>Maximaal <?php echo MAX_NR_OF_PHOTOS; ?> foto's per speeltuin.</li>
		<li>Er mogen g&eacute;&eacute;n personen op de foto staan!</li>
	</ul>

	<label>Bestaande foto's</label>
	<div class="photobar" id="photobar"></div>

	<div class="form-group">
		<label for="fotos">Upload foto('s)</label> <input id="fotos"
			name="fotos[]" type="file" multiple class="file-loading" />
		<p>Foto's kunnen in de preview gekanteld lijken. Dit wordt tijdens het uploaden gecorrigeerd.</p>
	</div>

	<hr>
	<div class="buttonbar">
		<input type="submit" name="Submit" value="Opslaan"
			class="btn btn-default" /> <input id="cancel" type="button"
			value="Terug" class="btn btn-default" />
	</div>

</form>

<script>
$(document).on('ready', function() {

	$.get("_del_photo.php?id=<?php echo $id; ?>", function(data) {
		$("#photobar").html(data);
    });
	
	$("#fotos").fileinput({
		language: "nl",
		showUpload: false,
		maxFileCount: 3,
		mainClass: "input-group-lg",
		allowedFileExtensions: ["jpg", "jpeg", "png"]
	});

	$("#cancel").click(function() {
		event.preventDefault();
    	window.location = './view.php?user';
	});

});

var deletePhoto = function(photoNr) {
	photoNr = photoNr.replace("del_photo_", "");
    $.get("_del_photo.php?id=<?php echo $id; ?>&photoNr=" + photoNr, function(data) {
    	$("#photobar").html(data);
	});
}

</script>

<?php
include_once "./inc/footer.php";