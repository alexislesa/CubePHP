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
 * Muestra un listado de encuestas devueltos
 *
 * @changelog:
 */
if (!$msjError && !empty($dataToSkin)) { 

	foreach ($dataToSkin as $k => $v) { 
	
		$tipo = "%d%/%m%/%Y%";
	
		$fechaInicio = formatDate($tipo, $v['encuesta_fecha_inicio']);
		$fechaFin = formatDate($tipo, $v['encuesta_fecha_fin']);
		?>

		<article class="encuesta" style="padding:10px; border:solid 1px #CCC;margin-bottom:10px;">

			<div class="inner">

				<header>

					<?php echo  $v['encuesta_titulo']?>

				</header><!--fin modalidad-->
				
				<?php if ($v['encuesta_texto'] != '') { ?>

					<?php echo  $v['encuesta_texto']?>

				<?php } ?>
			
				<div class="box-resultados">

					<?php if ($v['encuesta_estado'] == 1) { ?>

						<p>
							<strong>Encuesta activa </strong><br/>
								Fecha de finalización: <b><?php echo $fechaFin; ?></b>
							<br/><br/>
						</p>

						<form name="formencuesta<?php echo $v['encuesta_id'];?>">

							<input type="hidden" name="encuestanum" value="<?php echo  $v['encuesta_id'];?>" />

							<div class="opciones-encuesta">

								<?php 
								// Muestra todos los items de la encuesta
								foreach ($v['items'] as $j => $m) { ?>

									<div class="item-encuesta">
					
										<div class="radio-no">
											<input type="radio" name="voto" value="<?php echo  $m['id'];?>" id="voto_<?php echo  $m['id'];?>"/>
										</div><!--fin radio-->

										<label for="voto_<?php echo  $m['id'];?>"><?php echo  $m['valor'];?></label>

										<div class="clear"></div>

									</div><!--fin item encuesta-->

									<div class="clear"></div>

								<?php } ?>

							</div><!--fin opciones-->

							<div class="enc-bt">
								<input type="button" title="Votar" value="Votar" onclick="votar<?php echo  $v['encuesta_id'];?>();" />
								<a href="javascript:verresultados<?php echo  $v['encuesta_id'];?>();" title="Ver Resultados">Ver Resultados</a>
							</div><!--fin bt-->

							<div class="clear"></div>

						</form>

						Cantidad de votos hasta el momento: <b><?php echo  $v['encuesta_total_votos'];?></b>
						
					<?php } else { ?>
					
						<p>
							<strong>Encuesta finalizada el <?php echo $fechaFin; ?></strong>
							<br/><br/>
						</p>					

						Total de votos: <b><?php echo  $v['encuesta_total_votos'];?></b>
						
						<a href="javascript:verresultados<?php echo  $v['encuesta_id'];?>();" title="Ver Resultados">Ver Resultados</a>

					<?php } ?>

				</div><!--fin box resultados-->
			
			</div><!--fin inner-->

		</article><!--fin encuesta-->

		<script language='JavaScript' type='text/javascript'>
		function votar<?php echo  $v['encuesta_id'];?>(){
			var f = document.formencuesta<?php echo  $v['encuesta_id'];?>;
			var encnum = f.encuestanum.value;
			var seleccionado = 0;
			for (i=0;i<f.length;i++) {
				if (f[i].checked) {
					seleccionado = 1;
					<?php
					/* Utilizar esta parte si la votación se realiza vía PopUp */
					?>
					var url = "/extras/encuestas/votar.php?encuesta="+encnum+"&item="+f[i].value;
					ventana(url,"votacion",508, 390, "no");
					
					<?php
					/* Utilizar esta parte si la votación se realiza vía LightBox */
					?>
					// var url = "/extras/encuestas/votar.php?encuesta="+encnum+"&item="+f[i].value + "&KeepThis=true&TB_iframe=true&height=400&width=440";
					// tb_show("votar",url,false);
					
					return false;
				} 
			}

			if (seleccionado == 0) {
				alert ("Ud. deberá seleccionar una opción para poder votar");
				return false;
			}
		}

		function verresultados<?php echo  $v['encuesta_id'];?>(){
			<?php
			/* Utilizar esta parte si visualizar los resultados se realiza vía PopUp */
			?>
			var url = "/extras/encuestas/resultados.php?id=<?php echo  $v["encuesta_id"];?>";
			ventana(url,'votacion',648, 439, 'no');
			
			<?php
			/* Utilizar esta parte si visualizar los resultados se realiza vía LightBox */
			?>
			// var url = "/extras/encuestas/resultados.php?id=<?php echo  $v["encuesta_id"];?>&KeepThis=true&TB_iframe=true&height=500&width=600";
			// tb_show("votar",url,false);
		}
		</script>

	<?php } ?>

<?php } ?>