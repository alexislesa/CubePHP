<?php
/**
 * Muestra un listado de artículos devueltos en forma de preguntas frecuentes
 */
if (!$error_msj && !empty($dataToSkin)) { ?>
	
	<h2 id="title">Preguntas Frecuentes</h2>
	
	<section class="listado-faq">
		
		<p class="info">
			En esta sección, respondemos algunas preguntas que suelen formular <br/>
			En caso de que tales respuestas no sean suficientes para 
			esclarecer sus dudas, envíenos su pregunta a nuestra casilla de email
		</p>

		<ul>
			
			<?php
			$parImpar = 0;
			foreach ($dataToSkin as $k => $v) {
				
				$fecha = "%l% %j% de %F% de %y%"; 
				$notaFecha = formatDate($fecha, $v["noticia_fecha_modificacion"]);

				// Al primer bloque le agrega la clase "primera"
				$notaFirstClass = (!$k) ? 'primera' : '';
				
				// Clase si el art tiene imagen
				$notaImgClass = !empty($v['imagen']) ? 'confoto' : '';

				// Par / impar
				$parImpar = !$parImpar;
				$notaImparClass = ($parImpar) ? 'impar' : '';
				
				// nombre de la sección como clase
				$notaSeccionClass = "seccion-".$v['noticia_seccion_id'];
				
				// Formato de link
				$v["seccion_rss_page"] = "/noticias/nota.php";	// solo para demo, luego quitar
				$notaUrl = $v["seccion_rss_page"]."?id=".$v["noticia_id"];	// Link tipo: nota.php?id=xx
				// $notaUrl = $v["seccion_rss_index"].$v["noticia_page_url"].".htm"; // Link tipo: nota-demo-de-hoy.htm
				?>
				
				<li class="item-faq <?php echo $notaSeccionClass.' '.$notaFirstClass.' '.$notaImgClass.' '.$notaImparClass;?>">

					<article>
						
						<header>
							<h3>
								<span class="ico icofaq"></span>
								<a href="#" title="<?php echo $v['noticia_titulo'];?>" ><?php echo $v['noticia_titulo'];?></a>
								<span class="txt">+</span>
								<span class="txt2">-</span>
							</h3><!--fin h3-->
						</header>
					
						<div class="item-texto" style="display:none;">
						
							<div class="item-bajada">
								<?php echo $v['noticia_texto'];?>
							</div>
						
							<div class="clear"></div>
						
							<footer>
		
								<a href="/institucional/contacto.php" title="Si no encontró la respuesta adecuada dejenos su consulta aquí" class="faq-consultas">
									<span class="ico"></span>
									<span class="txt">Si no encontró la respuesta adecuada dejenos su consulta aquí</span>
								</a>

								<a href="#" title="Cerrar" class="faq-cerrar"><span class="ico"></span><span class="txt">Cerrar</span></a>
							</footer>
						
						</div><!--/.item-texto-->
		
						<div class="clear"></div>
					
					</article>
					
				</li><!--/.item-faq-->
			
			<?php } ?>

		</ul>

	</section><!--/.listado-faq-->
	
<?php } ?>

<script type="text/javascript">
$(".item-faq header").click(function() {
	if ($(this).next().css("display") == "none") {
		$(".item-texto").slideUp("fast");
		$(this).next().slideDown("slow");
		$(".item-faq header").removeClass('active');
		$(this).addClass("active");
	} else {
		$(".item-texto").slideUp("slow");
		$(".item-faq header").removeClass("active");
	}
	return false;
});

$(".faq-cerrar").click(function() {
	$(".item-texto").slideUp("slow");
	$(".item-faq header").removeClass("active");
	return false;
});
</script>