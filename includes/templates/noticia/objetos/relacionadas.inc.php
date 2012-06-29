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
 * Muestra el listado de las noticias relacionadas al art�culo
 * 
 * Array de datos que es posible devolver:
 *	- noticia_id		Id del art�culo relacionado
 *	- noticia_titulo	Titulo del art�culo relacionado
 *	- noticia_bajada	Bajada del art�culo relacionado
 *	- noticia_texto		Texto del art�culo relacionado
 *	- seccion_nombre	Secci�n del art�culo relacionado
 *
 * @changelog:
 */

if (!empty($v["relacionadas"]) && is_array($v["relacionadas"])) { ?>
	
	<?php
	/* Esta informaci�n se muestra una sola vez si existe al menos un art. relacionado */
	?>

	<section id="relac" class="article-block">

		<h4>Noticias Relacionadas</h4>
		
		<ul>
		<?php
		/* Esta parte repite el bloque completo por cada relacionada devuelta por el art�culo */
		foreach ($v["relacionadas"] as $j => $m) { 
			
			/* Estrucura del link */
			$nota_url_rel = $m["seccion_rss_page"]."?id=".$m["noticia_id"];	// Link tipo: nota.php?id=xx
			$nota_url_rel = $m["seccion_rss_index"].$m["noticia_page_url"].".htm"; // Link tipo: nota-demo-de-hoy.htm
			?>
		
			<li>
				<span class="ico"></span>
				<span class="sec"><?php echo $m["seccion_nombre"];?></span>
				<a href="<?php echo $nota_url_rel;?>" title="<?php echo $m["noticia_titulo"];?>"><?php echo $m["noticia_titulo"];?></a> 
			</li>
		
		<? } ?>
		</ul>
		
		<div class="clear"></div>
		
	</section><!--fin block-->	
	
<? } ?>