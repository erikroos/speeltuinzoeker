<?php $indexTitle = "Speeltuinzoeker.nl - Alle speeltuinen"; ?>
<?php include "header.tpl.php"; ?>

<div id="atoz">
	<?php foreach ($allSpeeltuinen as $firstLetter => $speeltuinArray): ?>
		<p><strong><?php echo strtoupper($firstLetter); ?></strong></p>
		<ul>
		<?php foreach ($speeltuinArray as $speeltuin): ?>
			<li>
				<a href="<?php echo BASE_URL; ?>speeltuinen/<?php echo $speeltuin["seo_url"]; ?>"><?php echo $speeltuin["naam"]; ?></a>
				<?php if (!empty($speeltuin["locatie_omschrijving"])): ?>
					<?php echo "- " . $speeltuin["locatie_omschrijving"]; ?>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
</div>

<?php include "footer.tpl.php"; ?>

</html>