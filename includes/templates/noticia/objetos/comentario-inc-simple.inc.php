<?php
/**
 * Retorna un solo comentario y sus respuestas
 */
function muestraComentario($com, $v) {

	/* Indica si el comentario esta aprobado o no */
	$comOk = ($com['comentario_estado'] == 1 || $com['comentario_estado'] == 5) ? true : false;

	/* Indica si tiene repuestas */
	$respuestas = !empty($com['comentario_respuestas']) ? true : false;

	/* Información sobre los votos del comentario */
	$votoPositivo = $com['comentario_votos_positivo'];
	$votoNegativo = $com['comentario_votos_negativo'];
	$votoTotal = $com['comentario_votos_positivo'] + $com['comentario_votos_negativo'];
	$votoPuntaje = $com['comentario_votos_positivo'] - $com['comentario_votos_negativo'];
	$votoPuntajeClase = ($votoPuntaje) ? '' : 'posi';
	$votoVotar = empty($_COOKIE['voto_com_'.$com['comentario_id']]) ? '' : 'disable';

	$tipo_fecha = "%d%/%m%/%y%";
	$fechaCom = formatDate($tipo_fecha, $com['comentario_fecha_hora']);
	
	// Avatar del usuario
	$com['lector_avatar'] = getGravatar($com['lector_email'],40);
	?>

	<div class="com-wrapper">
		
		<div class="com-main">

			<div class="com-user">
			 
				<span class="nro"><?php echo $com['comentario_numero'];?></span>
				
				<div class="avatar">
					
					<img src="<?php echo $com['lector_avatar'];?>" />
					
				</div><!--fin avatar-->
	
				<span class="name"><?php echo $com['lector_nombre'];?></span>
	
				<span class="cfecha"><?php echo $fechaCom;?></span>
	
			</div><!--fin com-user-->

			<div class="com-right">
			
				<span class="ico arrow"></span>
				
				<div class="comentarios-txt <?php echo !$comOk ? 'rechazado' : '';?>">
	
					<article><?php echo $com["comentario_texto"];?></article>
					
					<?php if (!$comOk) { ?>
					
						<div class="disclaimerc">CONOCE LOS <span>MOTIVOS DE RECHAZO</span> 
						DE UN COMENTARIO <a href="/institucional/normas.php" title="Normas de Participación" target="_blank">AQUI</a>
						</div>
	
					<?php } ?>
	
				</div><!--fin txt-->
	
				<?php 
				/* Comentario Aprobado */
				if ($comOk) { ?>
				
					<div class="comentarios-bottom">
					
						<div class="total">
	
							<span class="txt2 <?php echo $votoPuntajeClase;?>">(<i><?php echo $votoPuntaje;?></i>)</span>
							
							<span class="txt"><b>Total:</b></span>
							
							<span class="ico linet"></span>													
	
							<span class="voto-negativo <?php echo $votoVotar;?>" rel="<?php echo $com['comentario_id'];?>" title="No me gusta">
								<span class="ico"></span>(<i><?php echo $votoNegativo;?></i>)
							</span>
							
							<span class="voto-positivo <?php echo $votoVotar;?>" rel="<?php echo $com['comentario_id'];?>" title="Me gusta">
								<span class="ico"></span>(<i><?php echo $votoPositivo;?></i>)
							</span>

							<b class="txt">Valorar</b>

						</div><!--fin total-->

						<a href="/extras/notas/comentario-pop.php?id=<?php echo $v['noticia_id']?>&comid=<?php echo $com['comentario_id'];?>&KeepThis=true&TB_iframe=true&height=310&width=635" class="thickbox" title="Responder">
						<span class="ico"></span>Responder</a>

					</div><!--fin bottom-->

				<?php } ?>

				<?php 
				/* Si el comentario tiene respuestas */
				if ($respuestas) { ?>
				
					<div class="cmostrar">
						<div>
						<span class="ico"></span>
						<a href="#" rel="com_res_id<?php echo $com['comentario_id'];?>" title="Ver Respuestas" class="mostrar" style="display:none;">VER RESPUESTAS</a>
						<a href="#" rel="com_res_id<?php echo $com['comentario_id'];?>" title="Ocultar" class="ocultar">OCULTAR</a>
						</div>
					</div><!--fin cmostrar-->
	
				<?php } ?>
				
			</div><!--fin comentarios right-->
	
			<div class="clear"></div>

		</div><!--/.com-main-->
		
		<?php
		/**
		 * Bloque de respuestas al comentario
		 */
		if ($respuestas) { ?>
			
			<div id="com_res_id<?php echo $com['comentario_id'];?>" class="contenedor-resp">
					
				<?php foreach ($com['comentario_respuestas'] as $respNro => $respArr) {
				
					muestraComentario($respArr, $v);

				} ?>
	
			</div>
	
		<?php } ?>
		
	</div><!--/.com-wrapper-->
	
<?php } ?>