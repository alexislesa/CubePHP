<?php
/**
 * Muestra un bloque con los comentarios del sitio
 */
if ($v['noticia_comentarios'] && !empty($v['comentarios'])) {
	$totalComentarios = count($v['comentarios']);
	
	// función necesaria para recuperar el comentario
	include (dirTemplate."/{$pathRelative}/objetos/comentario-inc-simple.inc.php");
	?>

	<section id="comentarios" class="article-block">
		
		<h4><span>[<?php echo $totalComentarios;?>]</span> Comentarios</h4>

		<?php 
		// Listado de todos los comentarios de la nota
		foreach ($v['comentarios'] as $comid => $comentario) {

			muestraComentario($comentario, $v);

		} ?>

	</section>
	
	<script type="text/javascript">
	$(".cmostrar a").live("click",function() {
		$(this).parent().toggleClass("active");
		var m = "#" + $(this).attr("rel");
		$(m).toggle();
		return false;
	});

	$(".voto-positivo:not(.disable)").live("click", function() {
		checkvotos(this,"1");
	});

	$(".voto-negativo:not(.disable)").live("click", function() {
		checkvotos(this,"0");
	});

	function checkvotos(m,voto) {

		votocom = $(m).attr("rel");

		$.ajax({
			type: "GET",
			async: false,
			url: "/extras/notas/voto-com.php?id=<?php echo $v['noticia_id'];?>&com=" + votocom + "&vt=" + voto,
			success: function(msg){
				if (msg == "OK") {
				
					var nrovt = $(m).find("i").html();
					nrovt = parseInt(nrovt);
					nrovt+= 1;
					$(m).find("i").html(nrovt);
					
					var nro = $(m).parent().find(".txt2 i").html();
					nro = parseInt(nro);
					nro+= (voto == "1") ? 1 : -1;
					$(m).parent().find(".txt2 i").html(nro);
					$(m).parent().find(".voto-negativo, .voto-positivo").addClass("disable");
					
					if (nro < 0) {
						$(m).parent().find(".txt2").addClass("posi");
					} else {
						$(m).parent().find(".txt2").removeClass("posi");
					}
					
					$.cookie("voto_com_" + votocom, 1, { expires: 15 });
				} else {
					alert(msg);
				}
			}
		});
	}
	</script>

<?php } ?>