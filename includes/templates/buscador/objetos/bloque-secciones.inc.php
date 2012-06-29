<?php
/**
 * Muestra el bloque de las secciones donde filtrar la búsqueda
 */
if (!empty($secToFacetados)) {
	$inc_bloque_secciones = true;
	?>

	<section class="buscar-secciones">

		<h5>Buscar en:</h5>
	
		<div class="secciones">
		
			<div class="secciones_todas">
			
				<span class="checkbox">
					<input type="checkbox" name="seccion_all" id="seccion_all" title="Seleccionar todas las secciones" value="1" />
				</span>
				<label for="seccion_all">Todas las secciones</label>

			</div><!--fin secciones todas-->
		
			<?php foreach($secToFacetados as $secId => $secData) { 
			
				$secName = $secData['seccion_nombre'];
				$sel = '';
				if (!empty($_GET['seccion']) && is_array($_GET['seccion']) && count($_GET['seccion'])) {
					$sel = in_array($secId, $_GET['seccion']) ? 'checked' : '';
				} ?>
		
				<div class="secciones_nombres">
				
					<span class="checkbox">
						<input type="checkbox" name="seccion[]" id="seccion_<?php echo $secId;?>" value="<?php echo $secId;?>" title="<?php echo $secName;?>" <?php echo $sel;?> />
					</span>
				
					<label for="seccion_<?php echo $secId;?>"><?php echo $secName;?></label>
				
				</div><!--fin secciones nombres-->
		
			<?php } ?>

		</div><!--fin secciones-->
	
	</section>

	<script type="text/javascript">
	$("#seccion_all").click(function(){
		$("div.secciones_nombres input:checkbox").attr("checked", true);
		$(this).attr("checked", true);
	});

	$("div.secciones_nombres input:checkbox").click(function() {
		if ( $("div.secciones_nombres input:checkbox").length == $("div.secciones_nombres input:checked").length ){ 
			$("#seccion_all").attr("checked", true);
		} else {
			$("#seccion_all").attr("checked", false);
		}
	});
	</script>

<?php } ?>