<?php
/**
 * Muestra un formulario para nuevo aviso
 */
$tabIndex=1;

// Objetizo los datos del formulario
$dt = New ToObj($dataToSkin);
?>
<article>
	
	<header><h2 id="title">Publicar Nuevo Aviso.</h2></header>

	<p class="legend">
		Paso: 3/4. <br/>
		Duración del aviso, costos y formas de pago.
	</p><!--fin block-->

	<?php 
	// Mensaje de Error
	if ($msjError) {
		include (dirTemplate.'/herramientas/mensaje-error.inc.php');
	}

	// Mensaje de Alerta
	if ($msjAlerta) {
		include (dirTemplate.'/herramientas/mensaje-alerta.inc.php');
	}
	?>

	<form name="fcontacto" id="fcontacto" action="?act=true" method="post">
		<input type="hidden" name="token" value="<?php echo $token;?>" />
		<input type="hidden" name="catid" value="<?php echo $dt->g('catid');?>" />
		<input type="hidden" name="costo" value="" />

		<section class="formulario">

			<div class="form-block" >

				<label><strong>*</strong> Duración:</label>
				<select tabindex='<?php echo $tabIndex++;?>' class="select sel-duracion" name='duracion'>
					<option value='' >Seleccione duración</option>
					<?php foreach ($durArrData as $k => $v) {
						$sel = $dt->g('duracion', '')==$v['id'] ? 'selected' : ''; ?>
						<option value='<?php echo $v['id'];?>' <?php $sel;?> ><?php echo $v['nombre'];?></option>
					<?php } ?>
				</select>

				<span>Seleccione el tiempo de permanencia del aviso</span>
			</div><!--fin form block-->
			
			<div class="form-block" >
				<label><strong>Costo:</strong></label>
				<span class="costo">&nbsp;</span>
			</div><!--fin form block-->	

			<?php // Si es contenido adulto no muestra esta parte.
			if (!$catInf['categoria_contenido_adulto']) { ?>
				
				<div class="form-block" >

					<label><strong>*</strong> Métodos de exposición:</label>

					<?php foreach ($expArrData as $k => $v) { 
						$sel = in_array($v, $dt->g('exposicion')) ? 'checked' : '';
						?>
					
						<span>
							<input tabindex='<?php echo $tabIndex++;?>' type='checkbox' class="txt check-exposicion" name='exposicion[]' value='<?php echo $v['id'];?>' id='exposicion_id_<?php echo $v['id'];?>' <?php echo $sel;?> />
							<label for='exposicion_id_<?php echo $v['id'];?>'><?php echo $v['nombre'];?></label>
						</span>
					
					<?php } ?>

				</div><!--fin form block-->

			<?php } ?>

			<div class="form-block" >

				<label><strong>*</strong> Tiendas:</label>

				<input type="radio" name="tienda" value="0" checked /> No mostrar este aviso en mi tienda <br/>
				
				<input type="radio" name="tienda" value="1" /> Mostrar este aviso la tienda: xxx 1<br/>
				
				<input type="radio" name="tienda" value="2" /> Mostrar este aviso la tienda: xxx 2<br/>

				<span>Seleccione una forma de pago</span>

			</div><!--fin form block-->
			
			<div class="form-block" >

				<label><strong>*</strong> Formas de pago:</label>

				<span>Seleccione una forma de pago</span>
			</div><!--fin form block-->

			<div class="form-block form-bt">

				<input tabindex='<?php echo $tabIndex++;?>' type='submit' name='continuar' title='Continuar' value='Continuar'/>				

			</div><!--fin form block-->

		</section><!--fin /.formulario-->

	</form>

	<footer>
		Los campos marcados con un <strong>(*)</strong> son obligatorios
	</footer>

</article>

<script type="text/javascript">
	var costoPublicacionSetup = 0;
	var costoPublicacionDias = 0;

	var tipo = <?=$catInf['categoria_costo_formato'];?>;
	<?php // Cálculo de costo por única vez
	if ($catInf['categoria_costo_formato'] == 1) { ?>
		costoPublicacionSetup = <?php echo $catInf['categoria_costo_publicacion'];?>;
	
	<?php // Cálculo de costo por día
	} elseif ($catInf['categoria_costo_formato'] == 2) { ?>
		costoPublicacionDias = <?php echo $catInf['categoria_costo_publicacion'];?>;
	
	<?php // Cálculo de costo por porcentaje de venta
	} elseif ($catInf['categoria_costo_formato'] == 3) { ?>
		costoPublicacionSetup = <?php echo $catInf['categoria_costo_publicacion'];?>;
	<?php } ?>

	function calculoCostoFinal() {

		diasPublicacion = $(".sel-duracion option:selected").val();
		if (diasPublicacion == '') {
			diasPublicacion = 0;
		}

		costoDestacadoDias = 0;
		costoDestacadoSetup = 0;

		// Consulta por destacados
		totalDestacados = $(".check-exposicion:checked").length;
		
		for (i=0;i<totalDestacados;i++) {
			v = $(".check-exposicion:checked").eq(i).val();
			switch(v) {

				<?php foreach ($expArrData as $k => $v) { ?>
					case '<?php echo $v['id'];?>':
					
						<?php if ($v['costo_tipo'] == 1) { ?>
							costoDestacadoSetup+= <?php echo $v['costo'];?>;
						<?php } elseif ($v['costo_tipo'] == 2) { ?>
							costoDestacadoDias+= <?php echo $v['costo'];?>;
						<?php } ?>

					break;
				<?php } ?>
			}			
		}

		costoFinal = (costoDestacadoDias + costoPublicacionDias) * diasPublicacion;
		costoFinal+= costoPublicacionSetup + costoDestacadoSetup;

		costoFinal = costoFinal.toFixed(2);
		$("input[name='costo']").val(costoFinal);
		
		txtCosto = (costoFinal == 0.00) ? 'Gratis' : ('$' + costoFinal);
		$(".costo").html(txtCosto);
	}
	
	calculoCostoFinal();
	
	$(".sel-duracion").change(function() {
		calculoCostoFinal();
	});

	$(".check-exposicion").click(function() {
		calculoCostoFinal();
	});
</script>

<?php 
// Procesa las validaciones del Form
processForm('fcontacto', $checkForm); 
?>