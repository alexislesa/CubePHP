<?php
/**
 * Muestra el Bredcrums de la página
 */
if (!empty($breds) && is_array($breds)) { ?>

	<div class="breadcrumb">

		<span class="ea">Estas en:</span>

		<a href="/">Inicio</a>

		<?php 
		$breadn = 1;
		$breadTotal = count($breds);
		foreach ($breds as $bName => $bLink) { 
			$breadClase = ($breadTotal == ($breadn-1)) ? 'active' : '';
			?>

			<span class="sep">>></span>

			<?php if ($bLink == '' || $bLink == '#') { ?>

				<span class="bread b-<?php echo $breadn++;?> <?php echo $breadClase;?>"><?php echo $bName;?></span>

			<?php } else { ?>

				<a href="<?php echo $bLink;?>" class="bread-<?php echo $breadn++;?> <?php echo $breadClase;?>" title="<?php echo $bName;?>"><?php echo $bName;?></a> 

			<?php } ?>

		<?php } ?>

	</div>

<?php } ?>