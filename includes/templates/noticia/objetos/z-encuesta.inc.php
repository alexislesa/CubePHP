<?php
/**
 * Muestra la encuesta asociada al artículo
 */
if (!empty($v["encuestas"]) && is_array($v["encuestas"])) { 
	$total_encuestas = count($v["encuestas"]);
	?>
	
	<section class="article-block" id="encuesta">

		<h4>encuesta</h4>

		<?php foreach ($v["encuestas"] as $enc_nro => $encuesta) { ?>
		
			<form name="formencuesta<?php echo $encuesta["encuesta_id"];?>">
			
				<input type='hidden' name='encuestanum' value='<?php echo $encuesta["encuesta_id"];?>' />
			
				<div class="main-ec">
					
					<div class="inner-ec">
						
						<h4><?php echo $encuesta["encuesta_titulo"];?></h4>
						
						<?php
						/**
						 * Solo muetro las opciones de la encuesta si esta activa, si no solo muestro la opción de ver resultados
						 */
						if (($encuesta["encuesta_fecha_inicio"] < time()) && ($encuesta["encuesta_fecha_fin"] > time())) {
							foreach ($encuesta["items"] as $eitem_nro => $enc_item) { ?>
							
								<div class="item">
									<span class="radio"><input type="radio" name="voto" value="<?php echo $enc_item["id"];?>" id="voto_<?php echo $enc_item["id"];?>" /></span>
									<label for="voto_<?php echo $enc_item["id"];?>"><?php echo $enc_item["valor"];?></label>
									<div class="clear"></div>
								</div>
							
							<?php } ?>
						
							<div class="ec-bt"><span class="rounded"><span><b>votar</b></span></span><input type="button" title="VOTAR" value="" onclick="votar<?php echo $encuesta["encuesta_id"];?>();" /></div>
							
						<?php } ?>
						
						<div class="clear"></div>
						
					</div><!--fin inner-->	
				
				</div><!--fin main-->
				
			</form>
			
			<a class="resultados" title="VER RESULTADOS" href="javascript:verresultados<?php echo $encuesta["encuesta_id"];?>();">ver resultados</a>
			
			<script language='JavaScript' type='text/javascript'>
			function votar<?php echo $encuesta["encuesta_id"];?>(){
				var f = document.formencuesta<?php echo $encuesta["encuesta_id"];?>;
				var encnum = f.encuestanum.value;
				var cantidad = f.length; 
				var seleccionado = 0;
				for (i=0;i<cantidad;i++) {
					if (f[i].checked) {
						seleccionado = 1;
						
						<?php
						/**
						 * Utilizar esta parte si la votación se realiza vía PopUp
						 */
						?>
						var url = '/extras/encuestas/votar.php?encuesta='+encnum+'&item='+f[i].value;
						ventana(url,'votacion',508, 390, 'no');
						
						<?php
						/**
						 * Utilizar esta parte si la votación se realiza vía LightBox
						 */
						?>
						var url = '/extras/encuestas/votar.php?encuesta='+encnum+'&item='+f[i].value + '&KeepThis=true&TB_iframe=true&height=400&width=440';
						tb_show("votar",url,false);
						
						return false;
					} 
				}
		
				if (seleccionado == 0) {
					alert ("Ud. deberá seleccionar una opción para poder votar");
					return false;
				}
			}
		
			function verresultados<?php echo $encuesta["encuesta_id"];?>(){
				<?php
				/**
				 * Utilizar esta parte si visualizar los resultados se realiza vía PopUp
				 */
				?>
				var url = "/extras/encuestas/resultados.php?id=<?php echo $encuesta["encuesta_id"];?>";
				ventana(url,'votacion',648, 439, 'no');
				
				<?php
				/**
				 * Utilizar esta parte si visualizar los resultados se realiza vía LightBox
				 */
				?>
				var url = "/extras/encuestas/resultados.php?id=<?php echo $v["encuesta_id"];?>" + '&KeepThis=true&TB_iframe=true&height=500&width=600';
				tb_show("votar",url,false);
			}
			</script>			
		
		<?php } ?>
		
	</section><!--fin block-->

<?php } ?>