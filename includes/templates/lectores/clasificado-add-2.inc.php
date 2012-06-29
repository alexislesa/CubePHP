<?php
/**
 * Muestra un formulario para nuevo aviso
 */
$tabIndex=1;

$descMaxLength = 3000;

// Objetizo los datos del formulario
$dt = New ToObj($dataToSkin);
?>
<article>
	
	<header><h2 id="title">Publicar Nuevo Aviso.</h2></header>

	<p class="legend">
		Paso: 2/4. <br/>
		Descripción del aviso y datos de contacto
	</p><!--fin block-->

	<p class="legend">
		Categoría: 
		<?php echo $catInf1['categoria_nombre'];?> <br/>

		<?php if ($catInf2) { ?>
		
			Subcategoría: 
			<?php echo $catInf2['categoria_nombre'];?> 
			<?php echo ($catInf3) ? '/'.$catInf3['categoria_nombre'] : '';?> 
			<?php echo ($catInf4) ? '/'.$catInf4['categoria_nombre'] : '';?>

		<?php } ?>
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

	<form name="fcontacto" id="fcontacto" action="?act=true" method="post" enctype="multipart/form-data">

		<input type="hidden" name="token" value="<?php echo $token;?>" />

		<section class="formulario">

			<div class="form-block">

				<label><strong>*</strong> Titulo del aviso:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' autocomplete="off" class="txt" name='titulo' value='<?php echo $dt->g('titulo');?>' maxlength='60'/>

				<div class="clear"></div>

				<p class="disc">Ingrese un titulo para el aviso. Máximo: 60 caractéres.</p>

				<div class="clear"></div>

			</div><!--fin form block-->	
			
			<div class="form-block">

				<label><strong>*</strong> Descripción del aviso:</label>
				<textarea tabindex='<?php echo $tabIndex++;?>' name='texto'><?php echo $dt->g('texto');?></textarea>

				<div class="countdown">
					<input type="text" id="texto-texto" value="<?php echo $descMaxLength-(strlen($dt->g('texto')));?>"/>
					<span>Caracteres restantes. Máximo <?php echo $descMaxLength;?> caracteres.</span>
				</div>

			</div><!--fin form block-->

			<?php // Consulta si es venta o subasta
			if ($catInf['categoria_usa_venta'] && $catInf['categoria_usa_subasta']) { ?>
			
				<div class="form-block">

					<label><strong>*</strong> Tipo de aviso:</label>
					<select tabindex='<?php echo $tabIndex++;?>' class="select" name='tipo'>
						<option value=''>Seleccione tipo de aviso</option>
						<option value='1' <?php $dt->g('tipo', '')==1 ? 'selected' : '';?> >Subasta</option>
						<option value='2' <?php $dt->g('tipo', '')==2 ? 'selected' : '';?> >Venta</option>
					</select>

					<p class="disc">Seleccione el tipo de aviso (subasta / venta)</p>

					<div class="clear"></div>

				</div><!--fin form block-->
				
			<?php } ?>

			<?php // Operaciones permitidas para el aviso
			if (is_array($opArrData) && count($opArrData)) { ?>
				
				<div class="form-block">

					<label><strong>*</strong> Tipo de operación:</label>

					<select tabindex='<?php echo $tabIndex++;?>' class="select" name='operacion'>
						<option value=''>Seleccione tipo de operación</option>
						<?php foreach ($opArrData as $k => $v) {
							$sel = $dt->g('operacion', '')==$v['id'] ? 'selected' : ''; ?>
							<option value='<?php echo $v['id'];?>' <?php $sel;?> ><?php echo $v['nombre'];?></option>
						<?php } ?>
					</select>

					<p class="disc">Seleccione el tipo de operación</p>

					<div class="clear"></div>

				</div><!--fin form block-->

			<?php } // Operaciones fin ?>

			<?php // Estados del aviso
			if ($catInf['categoria_usa_estado'] && is_array($catEstados)) { ?>
				
				<div class="form-block">

					<label><strong>*</strong> Estado:</label>
					<select tabindex='<?php echo $tabIndex++;?>' class="select" name='estado'>
						<option value=''>Seleccione estado</option>
						
						<?php foreach ($catEstados as $k => $v) { 
							$sel = $dt->g('estado', '')==$v['id'] ? 'selected' : ''; ?>
							<option value='<?php echo $v['id'];?>' <?php echo $sel;?> ><?php echo $v['nombre'];?></option>
						<?php } ?>
						
					</select>

					<p class="disc"></p>

					<div class="clear"></div>

				</div><!--fin form block-->

			<?php } // Estados fin ?>
			
			<?php // Precio del aviso
			if ($catInf['categoria_usa_precio'] && is_array($catMonedas)) { ?>
			
				<div class="form-block">

					<label><strong>*</strong> Moneda:</label>
					<select tabindex='<?php echo $tabIndex++;?>' class="select" name='moneda'>
						<option value=''>Seleccione tipo de moneda</option>
						
						<?php foreach ($catMonedas as $k => $v) { 
							$sel = $dt->g('moneda', '')==$v['id'] ? 'selected' : ''; ?>
							<option value='<?php echo $v['id'];?>' <?php echo $sel;?> ><?php echo $v['nombre'];?></option>
						<?php } ?>
						
					</select>

					<p class="disc">Seleccione el tipo de moneda</p>

					<div class="clear"></div>

				</div><!--fin form block-->

				<div class="form-block">

					<label><strong>*</strong> Precio:</label>
					<input tabindex='<?php echo $tabIndex++;?>' type='text' autocomplete="off" class="txt" name='precio1' value='<?php echo $dt->g('precio1');?>' maxlength='5' />
					.
					<input tabindex='<?php echo $tabIndex++;?>' type='text' autocomplete="off" class="txt" name='precio2' value='<?php echo $dt->g('precio2');?>' maxlength='2'/>

					<div class="clear"></div>

					<p class="disc">Ingrese precio (unidades en el primer cuadro, decimales en el segundo). <br/>
						Dejar vacio para que aparezca "Consultar"</p>

					<div class="clear"></div>

				</div><!--fin form block-->	
				
			<?php } // Precio fin ?>

			<?php // Consulta si muestra cantidad
			if ($catInf['categoria_usa_cantidad']) { ?>
			
				<div class="form-block">

					<label><strong>*</strong> Cantidad:</label>
					<select tabindex='<?php echo $tabIndex++;?>' class="select" name='cantidad'>
						<option value=''>Seleccione cantidad</option>

						<?php for ($i=1; $i<=200; $i++) { 
							$sel = $dt->g('cantidad', '')==$i ? 'selected' : ''; ?>
							<option value='<?php echo $i;?>' <?php echo $sel;?> ><?php echo $i;?></option>
						<?php } ?>

					</select>

					<p class="disc">Seleccione la cantidad de artículos que tiene para ofertar</p>

					<div class="clear"></div>

				</div><!--fin form block-->

			<?php } // Cantidad fin ?>
			
			<?php // Consulta si muestra ubicación 
			if ($catInf['categoria_usa_ubicacion']) { ?>
				
				<!--ubicacion-->
				<div class="form-block">

					<label><strong>*</strong> País:</label>
					<select tabindex='<?php echo $tabIndex++;?>' class="select" id='pais_sel' name='pais'>
						<option value=''>Seleccione un país</option>
					</select>

				</div><!--fin form block-->

				<div id="provincia_block">

					<div class="form-block">

						<label><strong>*</strong> Provincia:</label>

						<select class="select" tabindex='<?php echo $tabIndex++;?>' id='provincia_sel' name='provincia'>
							<option value=''>Seleccione una provincia</option>
						</select>

						<div class="loading" id="provincia_load"></div>

					</div><!--fin item-contact-->
					
				</div><!--fin usrprovinciablock-->

				<div id="departamento_block">

					<div class="form-block">

						<label><strong>*</strong> Dpto / Partido:</label>
						<select class="select" tabindex='<?php echo $tabIndex++;?>' id='departamento_sel' name='departamento'>
							<option value=''>Seleccione un departamento</option>
						</select>

						<div class="loading" id="departamento_load"></div>
					
					</div><!--fin block form-->

				</div><!--fin divprovincianro-->

				<div id="localidad_block">
						
					<div class="form-block">

						<label><strong>*</strong> Localidad:</label>
						<select class="select" tabindex='<?php echo $tabIndex++;?>' id='localidad_sel' name='localidad' >
							<option value=''>Seleccione una localidad</option>
						</select>

						<div class="loading" id="localidad_load"></div>

					</div><!--fin form blok-->

				</div><!--fin divLocalidad-->

				<?php include (dirTemplate.'/herramientas/lista_paises.inc.php'); ?>

				<?php
				// como estoy en modo debug, muestro los iconos de loading
				if ($debugMode) { ?>
					<script type="text/javascript">
						$("#provincia_load").html("<?php echo $ubicacionIconLoading;?>");
						$("#departamento_load").html("<?php echo $ubicacionIconLoading;?>");
						$("#localidad_load").html("<?php echo $ubicacionIconLoading;?>");
						$("#localidad2_load").html("<?php echo $ubicacionIconLoading;?>");
					</script>
				<?php } ?>

			<?php } // Ubicaciones Fin ?>

			<?php // Consulta si muestra imagenes
			if ($catInf['categoria_usa_imagen']) { ?>
			
				<div class="form-block">

					<label><strong>*</strong> Imagenes:</label>

					<?php 
					// Muestra 5 ingresos de imagenes
					for ($i=1; $i<=5; $i++) { ?>
						<div class="form-bloque">
							<label>Foto <?php echo $i;?>:</label>
							<input type="file" name="file<?php echo $i;?>" onchange="validarImg(this.value);">
							<span></span>
						</div>
					<?php } ?>
					
					<div class="clear"></div>
					
					<p class="disc">Formato de foto: jpg, gif, png. Peso máximo: 2MB</p>

					<div class="clear"></div>

				</div><!--fin form block-->

				<script type="text/javascript">
				function validarImg(valor) {
					enviar = /.(gif|jpg|png)$/i.test(valor);
					if (!enviar) {
						alert('El archivo seleccionado no es una imagen válida. Seleccione solo archivos: GIF, JPG o PNG');
					}
				}
				</script>

			<?php } ?>
			
			<?php // Consulta si muestra video
			if ($catInf['categoria_usa_video']) { ?>
			
				<div class="form-block">

					<label><strong>*</strong> Url desde YouTube:</label>
					<input tabindex='<?php echo $tabIndex++;?>' type='text' autocomplete="off" class="txt" name='video' value='<?php echo $dt->g('video');?>' maxlength='150'/>

					<div class="clear"></div>

					<p class="disc">Ingrese url de Youtube.com. Ejemplo: http://www.youtube.com/watch?v=H8gQYPTy60Q </p>

					<div class="clear"></div>

				</div><!--fin form block-->

			<?php } ?>

			<?php // Consulta si muestra mapa
			if ($catInf['categoria_usa_mapa']) { 
				// Punto y zoom por defecto donde se muestra el mapa.
				$map_x = 0;
				$map_y = 0;
				$map_zoom = 10;
				?>
			
				<div class="form-block">

					<label> Mapa:</label>
					
					<div>
						<div style="width:500px; height:500px;" id="map"></div>
					</div>
					
					<div>
						<span>Ingrese dirección:</span>
						<input type="text" name="dir_map" id="map_direccion" value="" />
						
						<input type="button" name="buscar" value="Buscar Dirección" class="find-map" />
					</div>
					
					<div class="clear"></div>

					<p class="disc"></p>

					<div class="clear"></div>

				</div><!--fin form block-->
				
				<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		
				<input type="hidden" name="mapa[check]" id="map_map" value="0" />
				<input type="hidden" name="mapa[x]" id="map_x" value="0" />
				<input type="hidden" name="mapa[y]" id="map_y" value="0" />
				<input type="hidden" name="mapa[zoom]" id="map_zoom" value="0" />

				<script type="text/javascript">
				var geocoder;
				var map;
				var marker;
				var markers;

				function initMap(){
		
					//MAP
					var latlng = new google.maps.LatLng(<?php echo $map_y;?>,<?php echo $map_x;?>);
					var options = {
						zoom: <?php echo $map_zoom;?>,
						center: latlng,
						mapTypeId: google.maps.MapTypeId.SATELLITE
					};

					map = new google.maps.Map(document.getElementById('map'), options);

					//GEOCODER
					geocoder = new google.maps.Geocoder();

					marker = new google.maps.Marker({
						map: map,
						draggable: true
					});

					var location = new google.maps.LatLng(<?php echo $map_y;?>,<?php echo $map_x;?>);
					marker.setPosition(location);
					map.setCenter(location);
				}
		
				$(document).ready(function() {
		
					initMap();

					$('.find-map').click(function() {
						if ($('#map_direccion').val() == '') {
							alert('Ingrese una dirección');
						} else {
						
							geocoder.geocode( {'address': $('#map_direccion').val() }, function(results, status) {
							
								if (status == google.maps.GeocoderStatus.OK) {
								
									if (results[0]) {

										$('#map_x').val(results[0].geometry.location.lat());
										$('#map_y').val(results[0].geometry.location.lng());
										
										map.setZoom(12);
										marker.setPosition(results[0].geometry.location);
										map.setCenter(results[0].geometry.location);
									}

								} else {
									alert('No es posible encontrar la dirección:' + $('#map_direccion').val());
								}
							})
						
						}
					}); 

					// Guarda el cambio cuando se mueve el puntero
					google.maps.event.addListener(marker, 'drag', function() {
						$('#map_x').val(marker.getPosition().lat());
						$('#map_y').val(marker.getPosition().lng());

						geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								if (results[0]) {
									$('#map_direccion').val(results[0].formatted_address);
								}
							}
						});
					});
			
					// Guarda el cambio cuando se cambia de zoom
					google.maps.event.addListener(map, 'zoom_changed', function() {
						$('#map_zoom').val(map.getZoom());
					});

				});
				</script>		

			<?php } ?>
			
			<?php // Consulta si muestra medio de pago
			if ($catInf['categoria_usa_forma_pago'] && is_array($catMediosPago)) { ?>

				<div class="form-block">

					<label><strong>*</strong> Medios de pago:</label>
					
					<?php foreach ($catMediosPago as $k => $v) { 
						$sel = in_array($v, $dt->g('pagos')) ? 'checked' : '';
						?>
					
						<span>
							<input tabindex='<?php echo $tabIndex++;?>' type='checkbox' class="txt" name='pagos[]' value='<?php echo $v['id'];?>' id='pagos_id_<?php echo $v['id'];?>' <?php echo $sel;?> />
							<label for='pagos_id_<?php echo $v['id'];?>'><?php echo $v['nombre'];?></label>
						</span>
					
					<?php } ?>

					<div class="clear"></div>

					<p class="disc">Seleccione los medios de pago que aceptará para la transacción.</p>

					<div class="clear"></div>

				</div><!--fin form block-->

			<?php } ?>
			
			<?php // Consulta si muestra formas de envío
			if ($catInf['categoria_usa_forma_envio'] && is_array($catFormasEnvio)) { ?>
				
				<div class="form-block">

					<label><strong>*</strong> Formas de envío:</label>
					
					<?php foreach ($catFormasEnvio as $k => $v) { 
						$sel = in_array($v, $dt->g('envios')) ? 'checked' : '';
						?>
					
						<span>
							<input tabindex='<?php echo $tabIndex++;?>' type='checkbox' class="txt" name='envios[]' value='<?php echo $v['id'];?>' id='envios_id_<?php echo $v['id'];?>' <?php echo $sel;?> />
							<label for='envios_id_<?php echo $v['id'];?>'><?php echo $v['nombre'];?></label>
						</span>
					
					<?php } ?>

					<div class="clear"></div>

					<p class="disc">Seleccione las formas de envío del producto.</p>

					<div class="clear"></div>

				</div><!--fin form block-->

			<?php } ?>
			
			<?php // Consulta si muestra datos de contacto
			if ($catInf['categoria_usa_contacto']) { ?>
				
				<div class="form-block">

					<label><strong>*</strong> Nombre y Apellido:</label>
					<input tabindex='<?php echo $tabIndex++;?>' type='text' autocomplete="off" class="txt" name='contacto_1' value='<?php echo $dt->g('contacto_1');?>' maxlength='60'/>

					<div class="clear"></div>

					<p class="disc">Ingrese un nombre y apellido para contacto. Máximo: 60 caractéres.</p>

					<div class="clear"></div>

				</div><!--fin form block-->

				<div class="form-block">

					<label><strong>*</strong> Dirección:</label>
					<input tabindex='<?php echo $tabIndex++;?>' type='text' autocomplete="off" class="txt" name='contacto_2' value='<?php echo $dt->g('contacto_2');?>' maxlength='60'/>

					<div class="clear"></div>

					<p class="disc">Ingrese una direccicón donde ubicarlo. Máximo: 60 caractéres.</p>

					<div class="clear"></div>

				</div><!--fin form block-->		
			
				<div class="form-block">

					<label><strong>*</strong> Email:</label>
					<input tabindex='<?php echo $tabIndex++;?>' type='text' autocomplete="off" class="txt" name='contacto_3' value='<?php echo $dt->g('contacto_3');?>' maxlength='120'/>

					<div class="clear"></div>

					<p class="disc">Ingrese un email para contacto. </p>

					<div class="clear"></div>

				</div><!--fin form block-->			
			
				<div class="form-block">

					<label><strong>*</strong> Teléfono:</label>
					<input tabindex='<?php echo $tabIndex++;?>' type='text' autocomplete="off" class="txt" name='contacto_4' value='<?php echo $dt->g('contacto_4');?>' maxlength='60'/>

					<div class="clear"></div>

					<p class="disc">Ingrese un teléfono de contacto. Máximo: 60 caractéres.</p>

					<div class="clear"></div>

				</div><!--fin form block-->		
			
				<div class="form-block">

					<label><strong>*</strong> Horarios de contacto:</label>
					<input tabindex='<?php echo $tabIndex++;?>' type='text' autocomplete="off" class="txt" name='contacto_5' value='<?php echo $dt->g('contacto_5');?>' maxlength='40'/>

					<div class="clear"></div>

					<p class="disc">Ingrese un horario de contacto. Máximo: 40 caractéres.</p>

					<div class="clear"></div>

				</div><!--fin form block-->
			
				<div class="form-block">

					<label><strong>*</strong> Sitio web:</label>
					<input tabindex='<?php echo $tabIndex++;?>' type='text' autocomplete="off" class="txt" name='contacto_6' value='<?php echo $dt->g('contacto_6');?>' maxlength='80'/>

					<div class="clear"></div>

					<p class="disc">Ingrese su dirección web. Máximo: 80 caractéres.</p>

					<div class="clear"></div>

				</div><!--fin form block-->

			<?php } ?>

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

$("#categoria_sel").change(function() {

	muestraCat("subcat1", $(this).val());
	
});
</script>


<?php 
// Procesa las validaciones del Form
processForm('fcontacto', $checkForm); 
?>