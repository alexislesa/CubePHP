<?php
/**
 * ***************************************************************
 * @package		GUI-WebSite
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory
 * @licence 	Comercial
 * @version 	1.0
 * @revision 	24.08.2010
 * *************************************************************** 
 *
 * Muestra un bloque con los enlaces relacionados del artículo
 * 
 * Array de datos que es posible devolver:
 *	- gal_file			Url del enlace
 *	- gal_nombre		Titulo/Nombre del enlace
 *	- gal_descripcion	Descripción del enlace
 *
 *	- $total_link		Devuelve la cantidad de enlaces del artículo
 *
 * @changelog:
 */
if (!empty($v["links"]) && is_array($v["links"])) { 
	$total_link = count($v["links"]);
	?>

	<section class="article-block" id="links">
	
		<?php
		/**
		 * Esta información se muestra una sola vez si existe al menos un enlace
		 */
		?>

		<h4>Enlaces</h4>

		<ul>
			
			<?php 
			/**
			 * Esta parte repite el bloque completo por cada enlace devuelto por el artículo
			 */
			foreach ($v["links"] as $j => $m) { ?>

				<li>
					
					<?php if ($m["gal_descripcion"] != "") { ?>
						
						<div class="link-desc"><?php echo $m["gal_descripcion"];?></div>
					
					<?php } ?>
					
					<span class="ico"></span><a href="<?php echo $m["gal_file"];?>" target="_blank" title="<?php echo $m["gal_nombre"];?>"><?php echo $m["gal_nombre"];?></a>

				</li>
			
			<?php } ?>
			
		</ul>
		
	</section>

<?php } ?>