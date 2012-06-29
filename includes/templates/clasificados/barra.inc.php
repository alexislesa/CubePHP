<?php
/**
 * Muestra la barra en el listado de artículos
 */
?>
<aside id="sidebar-right" class="sidebar colum">

	<?php include(dirTemplate.'/clasificados/objetos/facetas.inc.php'); ?>

	<?php include(dirTemplate.'/extras/facebook-recomendaciones.inc.php');?>

	<?php include(dirTemplate.'/extras/facebook-likebox.inc.php');?>

	<div class="publicidad">
		
		<?php if ($banner=$adv->process($advZone2)) { ?>
		
			<div class="sidead">
				
				<?php echo $banner;?>
			
			</div>
		
		<?php } ?>
	
	</div>

</aside>