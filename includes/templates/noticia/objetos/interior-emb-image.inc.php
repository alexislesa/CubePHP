<?php
/**
 * Muestra un bloque de fotos embebida en el HTML del texto del artículo
 * El artículo carga este bloque para cada una de las imagenes embebidas en el HTML
 */
$m = $dataToSkin;
?>

<figure class="embed">
	
	<a href="<?php echo $m['url']['o'];?>" class="thickbox"><img src="<?php echo $m['url']['o'];?>" alt="<?php echo $m['texto'];?>" title="<?php echo $m['texto'];?>" width="640" /></a>

	<?php if ($m['texto'] != '') {?>
		
		<figcaption>
			<strong>Foto: </strong><?php echo $m['texto'];?>
		</figcaption>

	<?php } ?>

</figure>