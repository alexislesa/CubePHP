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
		Paso: 1/4. <br/>
		Selección de Categoría y subcategoría
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

		<section class="formulario">

			<div rel="1">
				<div class="form-block" >

					<label><strong>*</strong> Categoría:</label>
					<select tabindex='<?php echo $tabIndex++;?>' class="select" id='categoria_sel' name='categoria'>
						<option value=''>Seleccione categoría</option>
					</select>

					<span>Seleccione la categoría principal donde desea publicar su aviso</span>
				</div><!--fin form block-->
			</div>
			
			<div id="subcat1_block" style="display:none;" rel="2">

				<div class="form-block">

					<label><strong>*</strong> Subcategoría:</label>

					<select class="select" tabindex='<?php echo $tabIndex++;?>' id='subcat1_sel' name='subcat1'>
						<option value=''>Seleccione una subcategoría</option>
					</select>

					<div class="loading" id="subcat1_load"></div>

				</div><!--fin item-->
				
			</div><!--fin block-->

			<div id="subcat2_block" style="display:none;" rel="3">

				<div class="form-block">

					<label><strong>*</strong> Subcategoría:</label>

					<select class="select" tabindex='<?php echo $tabIndex++;?>' id='subcat2_sel' name='subcat2'>
						<option value=''>Seleccione una subcategoría</option>
					</select>

					<div class="loading" id="subcat2_load"></div>

				</div><!--fin item-->
				
			</div><!--fin block-->

			<div id="subcat3_block" style="display:none;" rel="">

				<div class="form-block">

					<label><strong>*</strong> Subcategoría:</label>

					<select class="select" tabindex='<?php echo $tabIndex++;?>' id='subcat3_sel' name='subcat3'>
						<option value=''>Seleccione una subcategoría</option>
					</select>

					<div class="loading" id="subcat3_load"></div>

				</div><!--fin item-->
				
			</div><!--fin block-->

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
	dataCat = <?php echo json_encode(JsonUTF8($catArr));?>;

	$(document).ready(function() {

		var html = "<option value=''>" + $("#categoria_sel option:first").text() + "</option>\n";
		var inf = dataCat[0];
		for (i=0; i<inf.length; i++) {
			html+= "<option value='" + inf[i].categoria_id + "'>" + inf[i].categoria_nombre + "</option>\n";
		}
		$("#categoria_sel").html(html);

	});

	function muestraCat(prefix, id) {

		if (dataCat[id].length) {
		
			var html = "<option value=''>" + $("#" + prefix + "_sel option:first").text() + "</option>\n";
			var inf = dataCat[id];
			for (i=0; i<inf.length; i++) {
				html+= "<option value='" + inf[i].categoria_id + "'>" + inf[i].categoria_nombre + "</option>\n";
			}
			$("#" + prefix + "_sel").html(html);
		
			$("#" + prefix + "_block").show();
		} else {
		
			$("#" + prefix + "_block").hide();
		}

	}

	$("#categoria_sel, #subcat1_sel, #subcat2_sel, #subcat3_sel").change(function() {

		m = $(this).val();
		$("input[name='catid']").val(m);
		
		subid = $(this).parent().parent().attr("rel");
		if (subid != '') {
			muestraCat("subcat" + subid, m);
		}

	});

</script>

<?php 
// Procesa las validaciones del Form
processForm('fcontacto', $checkForm); 
?>