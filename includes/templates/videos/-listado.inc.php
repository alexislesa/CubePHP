<?php
/**
 * ***************************************************************
 * @package		GUI-WebSite
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory
 * @licence 	Comercial
 * @version 	1.0
 * @revision 	12.03.2011
 * *************************************************************** 
 *
 * Muestra un listado de videos y un reproductor sin recargar el HTML 
 * 
 * El listado muestra mensaje de error si existiera, como por ejemplo: Que la sección no contiene ningun tipo de resultados 
 * o que se intento generar un comando de hacking (injection, XSS, etc).
 *
 * Permite copiarlo a cualquier sección que se requiera solo modificando el parametro: $path_relativo;
 *
 *
 *
 * @changelog:
 */
 
$path_relativo = "videos";
?>
<div id="container">

	<div id="content">

		<div class="multimedia">
			
			<div class="inner">
			
				<?php include ($path_root."/includes/templates/herramientas/breadcrumb.inc.php"); ?>
			
				<div id="bloque-video">
				
					<?php include ($path_root."/includes/templates/{$path_relativo}/objetos/reproductor.inc.php"); ?>
				
				</div>
				
				<div class="clear"></div>	
				
				<div class="cont-gal">
				
					<div class="main">
						
						<div class="main-videos">

							<div class="buscador-videos">
							
								<form name="fvideo" id="fvideo" action="?" method="get">
								
									<span>Buscador</span>
									
									<input type="text" name="q" value="" maxlength="35 " class="search"/>
									
									<div class="btn"><input type="submit" name="buscar" value="" class="bt"/></div>
									
									<div class="select-videos">
									
										<select name="fecha">
											<option value="">Filtrar por...</option>
											<option value="7">Esta semana</option>
											<option value="30">Último mes</option>
											<option value="">Todos</option>
										</select>
									
									</div><!--fin select-->
								
								</form>
							</div><!--fin buscador-->
							
						</div><!--fin main-videos-->
				
						<div class="interior">
						
							<div id="bloque-resultados">
							
								<div class="bloque-res">
							
									<?php
									if ($searchtext) {
										/* No hay resultados de la búsqueda */
										if (!$total_resultados) { 
											$texto_res = "<b>No se encontraron coincidencia/s con la palabra/s o frase:</b> <span>{$searchtext}</span><br />
												Revisá la ortografía de la/s palabra/s de búsqueda que utilizaste. Si la ortografía es correcta y escribiste una <br />
												sola palabra, comenzá una nueva búsqueda con una o más palabras similares";
											$texto_res = utf8_encode($texto_res);
											?>
										
												<div class="bsq-error">
										
													<span>
													
														<?php echo $texto_res;?>
													
													</span>
										
												</div>
										
										<?php } else { 
											/* Si se encontraron al menos un resultado */
											$texto_res = "<b>({$total_resultados})</b> coincidencia/s con la palabra/s o frase: <b>{$searchtext}</b><br /> 
												 Resultado/s de los últimos: <span>30 días</span><br />
												 Dividido/s en: <span>{$total_paginas}</span> página/s";
											$texto_res = utf8_encode($texto_res);
											?>
										
											<div class="greybox resultadosbox">
										
												<?php echo $texto_res;?>
										
											</div>
										
										<?php } 
									} ?>
									
									<div id="bloque-listado">
									
										<div class="bloque-in">
						
											<?php include ($path_root."/includes/templates/{$path_relativo}/objetos/listado.inc.php"); ?>
						
										</div>
								
									</div><!--fin listado-->

									<span class="border ico borderv"></span>
									
									<?php include ($path_root."/includes/templates/herramientas/paginador-1.inc.php"); ?>

								</div>
								
							</div>
						
						</div><!--fin interior-->

					</div><!--fin main-->
				
					<div class="sidebar barra-interior">
					
						<?php include ($path_root."/includes/templates/{$path_relativo}/barra.inc.php"); ?>
					
					</div><!--fin sidebar-->
			
				</div>
				
			</div><!--fin inner-->
			
		</div><!--fin multimedia-->
    
		<div class="clear"></div>
   		
	</div>
	
</div><!--fin content | container-->

<div id="shadow" class="lightSwitcher"></div>

<script type="text/javascript">

/* Click del Paginador */
$("#paginador a").live("click", function() {
	var m = $(this).attr("href") + " .bloque-res";
	$("#bloque-resultados").load(m);

	return false;
});

/* Carga el reproductor y los controles */
function loadvideo(url) {
	
	/* Cargo el reproductor */
	var m = url + " .video-interior";
	$("#video-cont").load(m, function() {

		/* Cargo los controles */
		var m = url + " .list-links .linksv";
		$(".list-links").load(m);

		$("html, body").animate({scrollTop:150}, "slow");
		
	});
	
}

/* Click en listado */
$(".item-video a").live("click", function() {
	$(".thumbs").removeClass("active");
	loadvideo($(this).attr("href"));
	
	return false;
});

/* Click en thumb de la barra derecha (en link) */
$(".thumbs .img a").live("click", function() {
	$(".thumbs").removeClass("active");
	$(this).parent().parent().addClass("active");
	loadvideo($(this).attr("href"));
	
	return false;
});

/* Click en thumb de la barra derecha (en link) */
$(".thumbs .desc a").live("click", function() {
	$(".thumbs").removeClass("active");
	$(this).parent().parent().parent().parent().addClass("active");
	loadvideo($(this).attr("href"));

	return false;
});


/* Modo Cine */
$(document).ready(function(){
	$("#shadow").css("height", $(document).height()).hide();
	
	$(".lightSwitcher").live("click", function(){
		$("#shadow").toggle();
		if ($("#shadow").is(":hidden")) {
			$(this).html("").removeClass("turnedOff");
		} else {
			$(this).html("").addClass("turnedOff");
		}
		return false;
	});
});

/* Buscador */
$("#fvideo").submit( function () {
	var m = $("#fvideo").serialize();
	$("#bloque-resultados").load("/videos/index.php?" + m + " .bloque-res");
	return false;
});
</script>