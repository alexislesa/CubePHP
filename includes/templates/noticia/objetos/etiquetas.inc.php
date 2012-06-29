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
 * Muestra el listado de etiquetas asociadas al artículo
 * 
 *	- $total_tags		Devuelve la cantidad de etiquetas del artículo
 *
 * @changelog:
 */
if (!empty($v["noticia_tags"]) && is_array($v["noticia_tags"])) { 
	$total_tags = $v["noticia_tags"];
	?>
	
	<?php
	/**
	 * Esta información se muestra una sola vez si existe al menos una etiqueta
	 */
	?>
	
	<section class="article-block" id="tags">
	
		<h4>Etiquetas: </h4>
	
		<?php
		/**
		 * Esta parte repite el bloque completo por cada etiqueta devuelta por el artículo
		 */
		foreach ($v["noticia_tags"] as $j => $tag) { 
			$separador_tags = ($j) ? ", " : ""; ?>
	
			<?php echo $separador_tags; ?>
			
			<a href="/extras/notas/tags.php?tag=<?php echo $tag;?>" title="<?php echo $tag;?>"><?php echo $tag;?></a>
	
		<?php } ?>
	
	</section>

<?php } ?>