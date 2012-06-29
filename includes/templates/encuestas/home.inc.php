<?php
/**
 * Muestra las opciones de votación de la encuesta activa en la portada del sitio
 *
 */
$encv = $encToSkin[0]; 
?>
<article id="encuesta" class="homeblock">

	<div class="inner">

		<header> 
			<h4>Encuesta de la semana</h4> 
			<h3><?php echo $encv['encuesta_titulo'];?></h3>
		</header>

		<form name="formencuesta<?php echo $encv['encuesta_id'];?>">

			<input type="hidden" name="encuestanum" value="<?php echo $encv['encuesta_id'];?>" />
			
			<ul>
				
				<?php 
				// Muestra todos los items de la encuesta
				foreach ($encv['items'] as $j => $m) { ?>
				
					<li>

						<span class="radio">
							<input type="radio" name="voto" value="<?php echo $m['id'];?>" id="voto_<?php echo $m['id'];?>"/>
						</span><!--fin radio-->
					
						<label for="voto_<?php echo $m['id'];?>"><?php echo $m['valor'];?></label>
						
						<div class="clear"></div>
						
					</li><!--fin item encuesta-->
				
				<?php } ?>
			
			</ul>

			<footer>

				<input type="button" title="Votar" value="Votar" onclick="votar<?php echo $encv['encuesta_id'];?>();" />

				<a href="javascript:verresultados<?php echo $encv['encuesta_id'];?>();" title="Ver Resultados">Ver Resultados</a>
				
			</footer><!--fin footer-->
			
			<div class="clear"></div>

		</form>

	</div><!--fin /.inner-->

</article><!--fn /.encuesta--> 

<script language='JavaScript' type='text/javascript'>
function votar<?php echo $encv['encuesta_id'];?>(){
	var f = document.formencuesta<?php echo $encv['encuesta_id'];?>;
	var encnum = f.encuestanum.value;
	var seleccionado = 0;
	for (i=0;i<f.length;i++) {
		if (f[i].checked) {
			seleccionado = 1;
			<?php
			// Utilizar esta parte si la votación se realiza vía PopUp
			?>
			var url = "/extras/encuestas/votar.php?encuesta="+encnum+"&item="+f[i].value;
			ventana(url,"votacion",508, 390, "no");
			
			<?php
			// Utilizar esta parte si la votación se realiza vía LightBox
			?>
			var url = "/extras/encuestas/votar.php?encuesta="+encnum+"&item="+f[i].value + "&KeepThis=true&TB_iframe=true&height=400&width=440";
			tb_show("votar",url,false);
			
			return false;
		} 
	}

	if (seleccionado == 0) {
		alert ("Ud. deberá seleccionar una opción para poder votar");
		return false;
	}
}

function verresultados<?php echo $encv['encuesta_id'];?>(){
	<?php
	// Utilizar esta parte si visualizar los resultados se realiza vía PopUp
	?>
	var url = "/extras/encuestas/resultados.php?id=<?php echo $encv['encuesta_id'];?>";
	ventana(url,'votacion',648, 439, 'no');
	
	<?php
	// Utilizar esta parte si visualiza la encuetsa en una página
	?>
	document.location.href="/extras/encuestas/resultados.php?id=<?php echo $encv['encuesta_id'];?>";
	
	<?php
	// Utilizar esta parte si visualizar los resultados se realiza vía LightBox
	?>
	var url = "/extras/encuestas/resultados.php?id=<?php echo $encv['encuesta_id'];?>&KeepThis=true&TB_iframe=true&height=500&width=600";
	tb_show("votar",url,false);
}
</script>