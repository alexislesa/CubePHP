<aside id="sidebar-right" class="sidebar colum">

	<?php include(dirTemplate.'/extras/tabs-mas-leido.inc.php');?>

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