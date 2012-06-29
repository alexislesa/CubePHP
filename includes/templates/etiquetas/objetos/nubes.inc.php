<?php
/**
 * Nube de etiquetas
 */
?>

<div class="inner nubetags2">

	<?php foreach ($dataToSkin as $kTag => $vTag) { ?>

		<a href="/extras/notas/tags.php?tag=<?php echo $vTag['tag'];?>" class="tags<?php echo $vTag['peso'];?>" title="Etiqueta: <?php echo $vTag['tag'];?> (<?php echo $vTag['cantidad'];?> apariciones)"><?php echo $vTag['tag'];?></a>

	<?php } ?>

</div>