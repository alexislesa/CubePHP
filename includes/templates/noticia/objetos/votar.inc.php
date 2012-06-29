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
 * Muestra un bloque de votación para el artículo
 * @see http://plugins.jquery.com/project/raty
 * 
 * Array de datos que es posible devolver:
 *	- stats_vote_total	Puntaje total hasta el momento (sumatoria de puntajes)
 *	- stats_vote_users	Total de votacione realizadas hasta el momento
 *
 *	$start_prom		Total de estrellas visibles a mostrar
 *	$voto_prom			Voto actual
 *
 * @changelog:
 */
$start_prom = 0;
$voto_prom = 0;

if (!empty($v["estadisticas"]["stats_vote_total"])) {
	$start_prom = round($v["estadisticas"]["stats_vote_total"] / $v["estadisticas"]["stats_vote_users"], 0);
	$voto_prom = round($v["estadisticas"]["stats_vote_total"] / $v["estadisticas"]["stats_vote_users"], 2);
}
?>

<section id="votar" class="article-block">
	Votación actual: <?php echo $voto_prom;?> 
	<br/>
	<div id="votacion<?php echo $v["noticia_id"];?>"></div>
</section>

<script type="text/javascript">
$(document).ready(function () {

	$("#votacion<?php echo $v["noticia_id"];?>").raty({
		scoreName:  "entity.score",
		start: <?php echo $start_prom;?>,
		number:     5,
		path: "/images/",
		starOn:    "star-on.png",
		starOff:   "star-off.png",
		onClick: function(score) {
			$.fn.raty.readOnly(true);
			var urlvt = "/extras/notas/voto.php?id=<?php echo $v["noticia_id"];?>&v=" + score;
			
			$.ajax({
				type: "GET",
				async: false,
				url: urlvt,
				data: "",
				success: function(msj){
					if (msj == "OK") {
						$.cookie("nota_voto_<?php echo $v["noticia_id"];?>", score, { expires: 0 });
					} else {
						alert(msj);
					}
				}
			});	
		}
	});

	if ($.cookie("nota_voto_<?php echo $v["noticia_id"];?>")) {
		$.fn.raty.readOnly(true);
	}
});
</script>