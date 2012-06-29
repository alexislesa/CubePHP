<?php
/**
 * Muestra un bloque tabs con pestañas de: Más Leidas, Más comentadas y Más Impresas
 * El listado puede mostrar las notas en un rango de fechas y un máximo estipulado
 */
$tabFecha = 7; // Rango en días de las notas a mostrara
$tabTipo = 0; // Tipo de art. a mostrar
$tabSeccion = 0; // Sección de las notas a mostrar
$tabCantidad = 5; //Cantidad de resultados a mostrar
$tabRangoFecha = time() - (60*60*24*$tabFecha);

$notaToMore = array();

$sqlData = '';
$sqlData.= !empty($tabTipo) ? ' AND noticia_tipo = '.$tabTipo.' ' : '';
$sqlData.= !empty($tabSeccion) ? ' AND noticia_seccion_id = '.$tabSeccion.' ' : '';

// Levanto información de las noticias más leidas en el rango cargado
$oSql = "SELECT noticia_id, noticia_titulo, noticia_page_url, seccion_rss_index, stats_view 
	FROM noticias, noticias_secciones, noticias_stats 
	WHERE noticia_seccion_id = seccion_id 
		AND noticia_id = stats_noticia_id 
		AND noticia_fecha_modificacion > {$tabRangoFecha} 
		AND noticia_estado = 1 
		{$sqlData}
	ORDER BY stats_view Desc
LIMIT 0,{$tabCantidad}";
if ($rTab = $db->query($oSql)) {
	for ($i=0; $i<$db->num_rows($rTab); $i++) {
		$notaToMore['leido'][] = $db->next($rTab);
	}
}

// Levanto información de las noticias más comentadas
$oSql = "SELECT noticia_id, noticia_titulo, noticia_page_url, seccion_rss_index, COUNT(comentario_id) as total 
	FROM noticias, noticias_secciones, noticias_comentarios 
	WHERE noticia_seccion_id = seccion_id 
		AND noticia_fecha_modificacion > {$tabRangoFecha} 
		AND noticia_id = comentario_noticia_id
		AND noticia_estado = 1 
		{$sqlData}
	GROUP BY comentario_noticia_id
	ORDER BY total Desc
LIMIT 0,{$tabCantidad}";
if ($rTab = $db->query($oSql)) {
	for ($i=0; $i<$db->num_rows($rTab); $i++) {
		$notaToMore['comentado'][] = $db->next($rTab);
	}
}

// Levanto información de las  noticias más compartidas en el rango seleccionado
$oSql = "SELECT noticia_id, noticia_titulo, noticia_page_url, seccion_rss_index, stats_mail 
	FROM noticias, noticias_secciones, noticias_stats 
	WHERE noticia_seccion_id = seccion_id 
		AND noticia_id = stats_noticia_id 
		AND noticia_fecha_modificacion > {$tabRangoFecha} 
		AND noticia_estado = 1 
		AND noticia_tipo = 26
		{$sqlData}
	ORDER BY stats_mail Desc
LIMIT 0,{$tabCantidad}";
if ($rTab = $db->query($oSql)) {
	for ($i=0; $i<$db->num_rows($rTab); $i++) {
		$notaToMore['compartido'][] = $db->next($rTab);
	}
}
?>

<section id="tabs" class="block">
	
	<div class="inner">
		
		<h4>lo mas...</h4>
		
		<ul class="tab">
			<li id="first"><a href="#">Leído</a></li>
			<li><a href="#">Comentado</a></li>
			<li id="last"><a href="#">Compartido</a></li>
		</ul><!--fin tabs-->
		
		<div class="panes">
			
			<div style="display:block">
				
				<ul>

					<?php
					if (!empty($notaToMore['leido'])) {
					
						foreach ($notaToMore['leido'] as $kTab => $vTab) {
							$vTab['noticia_titulo'] = htmlspecialchars_decode($vTab['noticia_titulo'], ENT_QUOTES);
							$vTab['url_nota'] = $vTab['seccion_rss_index'].'/'.$vTab['noticia_id'].'-'.$vTab['noticia_page_url'].'.htm';
							?>
						
							<li class="<?php echo !$kTab ? 'first' : '';?>" >
								<span class="ico"><?php echo $kTab+1;?></span>
								<a href="<?php echo $vTab['url_nota'];?>" title="<?php echo $vTab['noticia_titulo'];?>"><?php echo $vTab['noticia_titulo'];?></a>
							</li>
							
						<?php }
					} ?>

				</ul>
			
			</div><!--fin leido-->
			
			<div>
				
				<ul>					
					<?php
					if (!empty($notaToMore['comentado'])) {
					
						foreach ($notaToMore['comentado'] as $kTab => $vTab) {
							$vTab['noticia_titulo'] = htmlspecialchars_decode($vTab['noticia_titulo'], ENT_QUOTES);
							$vTab['url_nota'] = $vTab['seccion_rss_index'].'/'.$vTab['noticia_id']."-".$vTab['noticia_page_url'].".htm";
							?>
						
							<li class="<?php echo !$kTab ? 'first' : '';?>" >
								<span class="ico"><?php echo $kTab+1;?></span>
								<a href="<?php echo $vTab['url_nota'];?>" title="<?php echo $vTab['noticia_titulo'];?>"><?php echo $vTab['noticia_titulo'];?></a>
							</li>
							
						<?php }
					} ?>
	
				</ul>			
			
			</div><!--fin comentado-->
			
			<div>
				
				<ul>

					<?php
					if (!empty($notaToMore['compartido'])) {
					
						foreach ($notaToMore['compartido'] as $kTab => $vTab) {
							$vTab['noticia_titulo'] = htmlspecialchars_decode($vTab['noticia_titulo'], ENT_QUOTES);
							$vTab['url_nota'] = $vTab['seccion_rss_index'].'/'.$vTab['noticia_id'].'-'.$vTab['noticia_page_url'].'.htm';
							?>
						
							<li class="<?php echo !$kTab ? 'first' : '';?>" >
								<span class="ico"><?php echo $kTab+1;?></span>
								<a href="<?php echo $vTab['url_nota'];?>" title="<?php echo $vTab['noticia_titulo'];?>"><?php echo $vTab['noticia_titulo'];?></a>
							</li>
							
						<?php }
					} ?>
					
				</ul>			
			
			</div><!--fin compartido-->

		</div><!--fin panes-->

	</div><!--fin /.inner-->
	
</section><!--fin /tabs-->

<script type="text/javascript">
$(function() {
	$("ul.tab").tabs("div.panes > div");
});
</script>