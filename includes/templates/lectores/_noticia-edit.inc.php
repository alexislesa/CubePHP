<?php
/**
 * ***************************************************************
 * @package		GUI-WebSite
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory
 * @licence 	Comercial
 * @version 	1.0
 * @revision 	20.09.2010
 * *************************************************************** 
 *
 * Muestra un formulario para ingresar noticias
 *
 * Pantalla que muestra un formulario para el ingreso de noticias.
 * Entre los posibles mensajes de error, se encuentran el error en el ingreso e la base de datos
 * o el ingreso de un texto es incorrecto
 *
 * Este formulario permite el manejo de textos en marca de agua solo ingresando esto como atributo "title"
 *
 * @changelog:
 */
$tabindex=1;
?>

<article>
	
		
		<header>
			<h2 class="nota-title">Editar noticia</h2>
			<img src="<?php echo $usr->campos["lector_avatar"];?>" width="40" height="40" alt="" title="" />		
		</header>
			
		<h5>cuerpo de la noticia</h5>
				
		<?php 
		/* Muestra mensaje de error */
		if ($error_msj) {
			include($path_root."/includes/templates/herramientas/mensaje-error.inc.php");
		} ?>
				
		<form name="fcontacto" id="fcontacto" action="?id=<?php echo $dataToSkin["id"];?>&act=true" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?php echo $dataToSkin["id"];?>" />

			<section class="formulario">
				
				<div class="form-block">
	
					<label><strong>*</strong> Titulo:</label>						
					<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" name='titulo' value='<?php echo $dataToSkin["titulo"];?>' maxlength='80'/>
						
					<div class="countdown">
						<input type="text" value="80" id="nota-titulo">
						<span> Caracteres restantes. Máximo 80 caracteres.</span>
					</div><!--fin form block-->
					
				</div>
					
				<div class="form-block formprofile">
	
					<label><strong>*</strong> Descripción:</label>
					<textarea tabindex='<?php echo $tabindex++;?>' name='bajada'><?php echo $dataToSkin["bajada"];?></textarea>
					
					<div class="countdown">
						<input type="text" value="240" class="countdown" id="nota-bajada"/>
						<span"> Caracteres restantes. Máximo 240 caracteres.</span>
					</div>
				</div>
					
				<div class="form-block formprofile">
	
					<label><strong>*</strong> Texto:</label>
					<textarea tabindex='<?php echo $tabindex++;?>' name='texto'><?php echo $dataToSkin["texto"];?></textarea>
						
					<div class="countdown">
						<input type="text" value="4000" class="countdown" id="nota-texto"/>
						<span>Caracteres restantes. Máximo 4000 caracteres.</span>
					</div>
					
				</div>						
					
				<h5>Foto</h5>
					
				<?php 
				/**
 				 * Si la nota tiene foto, muestra este bloque compuesto por descripcion, foto y link de eliminar foto
				 */
				$foto_add = "";
					
					if (!empty($dtToSkin[0]["imagen"])) { 
					$foto_add = "display:none;";
				?>
					
					<div class="form-block">
		
						<input type="hidden" name="imgold" value="<?php echo $dtToSkin[0]["imagen"][1]["gal_id"]?>" />
						<span class="label">Imágen:</span>
								
						<figure>
							<img src="<?php echo $dataToSkin["imagen"];?>" class="image-edit"/>
						</figure>
						
						<span class="del-img"><a href="#" class="image-del">[ Eliminar Foto ] </a></span>
						
						<script type="text/javascript">
							$(".del-img a").click(function() {
								var m = $(this).parent().parent()
								m.hide();
								$("#foto-add").fadeIn("fast", function() {
								$(m).remove();
								});
								return false;
							});
						</script>
								
					</div><!--fin form block-->	
					
					
				<?php } ?>
					
				<div id="foto-add" style="<?php echo $foto_add;?>" >
					
					<div class="form-block">
	
						<label>Imágen:</label>
							
						<input tabindex='<?php echo $tabindex++;?>' type='file' class="txt" name='foto1' value=''/>
						<p class="disc">El archivo debe ser de tipo JPG o GIF, cualquier otro tipo no es válido. <br/>
						Tamaño del archivo: La imagen ha de ser de un mínimo de 440 pix. de ancho, y no puede exceder de 512 Kb de peso.</p>
						
					</div><!--fin form block-->						
				
				</div>
					
				<div class="form-block">
	
					<label>Descripción de la Imágen:</label>
					<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" name='foto_desc' value='<?php echo $dataToSkin["foto_desc"];?>' maxlength='80'/>
				
					<div class="countdown">
						<input type="text" value="80" id="nota-imagen"/>
						<span>Caracteres restantes. Máximo 80 caracteres.</span>
					</div>
					
				</div><!--fin form block-->
					
				<h5>Video</h5>
					
				<div id="videoadd" class="form-block">
	
					<label>URL del video de YouTube:</label>
					
					<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" name='video' value='<?php echo $dataToSkin["video"];?>' maxlength='180'/>
					<p class="disc">Ejemplo: <u>http://www.youtube.com/watch?v=MQCNuv2QxQY</u></p>

				</div><!--fin form block-->
					
				<h5>Mapa</h5>
					
				<input type="hidden" name="map_map" id="map_map" value="0" />
				<input type="hidden" name="map[x]" id="map_x" value="<?php echo $dataToSkin["map"]["x"];?>" />
				<input type="hidden" name="map[y]" id="map_y" value="<?php echo $dataToSkin["map"]["y"];?>" />
				<input type="hidden" name="map[zoom]" id="map_zoom" value="<?php echo $dataToSkin["map"]["zoom"];?>" />
				<input type="hidden" name="map[tipo]" id="map_tipo" value="<?php echo $dataToSkin["map"]["tipo"];?>" />
					
				<div class="form-block">
				
					<label>Definir Ubicación:</label>
						
					<div class="select">
						
						<div class="gmap2">
							<div id="map" style="width:216px; height:180px"></div>
						</div>
							
						<p class="disc">Indique la ubicación haciendo click en el mapa o utilice el buscador de calles y/o ciudades.</u></p>
						
					</div><!--fin select-->
						
				</div>
					
				<div class="form-block formblock2 buscacalle">
					
					<label>Buscar calle:</label>
						
					<div>
						<input type="text" size="30" name="address" value="" class="txt" />
						<input type="button" title="Buscar" name="busmap" value="" onclick="showAddress(this.form.address.value);" class="buscacalle"/>
					</div>
				
				</div>					
				
				<!-- -->
					
				<div class="form-block">

					<div class="radiocheck">

						<div class="item">
							<span class="checkbox selected"><input type="checkbox" value="1" checked="" name="terminosycondiciones" tabindex="8" class="check"></span>
							<label>He leído y acepto las <a href="/institucional/normas.php" title="Normas de Participación" target="_blank" rel="nofollow">Normas de Participación</a> y la <a target="_blank" title="Política de Privacidad" href="/institucional/politicas.php">Política de Privacidad</a> </label>
						</div>		
							
					</div><!--fin radio check-->
						
				</div>
	
				<div class="form-block form-btbut">
					<input tabindex='<?php echo $tabindex++;?>' type='submit' name='registrarme' title='Guardar Cambios' value='Guardar Cambios'/>
				</div><!--fin form block-->
				
			</section>
			
		</form>
				
		<footer>
			<p>Los campos marcados con un <strong>(*)</strong> son obligatorios</p>
		</footer>
		
</article>

<script type="text/javascript">
$("#fcontacto input[type='text']").each(function() {
	if ($(this).attr("title") != "") {
		$(this).fieldtag();
	}
});
</script>

<script type="text/javascript">
$("#fcontacto input[name='titulo']").limit(80, "#nota-titulo", 15);

$("#fcontacto textarea[name='bajada']").limit(240, "#nota-bajada", 15);

$("#fcontacto textarea[name='texto']").limit(4000, "#nota-texto", 15);

$("#fcontacto input[name='foto_desc']").limit(80, "#nota-imagen", 15);
</script>

<?php include ($path_root."/includes/templates/herramientas/lista_paises.inc.php"); ?>

<?php processForm("fcontacto", $checkForm); ?>

<script src="http://maps.google.com/maps?file=api&v=2.x&key=<?php echo $ss_config["api_google_maps"];?>" type="text/javascript"></script>

<?
/* Puntos por defecto en el mapa */
$punto_y = empty($dataToSkin["map"]["y"]) ? "-31.73112374150084" : $dataToSkin["map"]["y"];
$punto_x = empty($dataToSkin["map"]["x"]) ? "-60.516085624694824" : $dataToSkin["map"]["x"];
$punto_zoom = empty($dataToSkin["map"]["zoom"]) ? 11 : $dataToSkin["map"]["zoom"];
?>

<script type="text/javascript">
//<![CDATA[
var map = null;
var geocoder = null;
var marker = null;

function load() {
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("map"));   
		map.setCenter(new GLatLng(<?php echo $punto_y;?>,<?php echo $punto_x;?>),<?php echo $punto_zoom;?>); 

		/* Para las calles. */
		geocoder = new GClientGeocoder();

		/* Controles de Zoom */
		map.addControl(new GSmallMapControl());

		/* Controles de Tipo (satelite, hibrido, etc) */
		//map.addControl(new GMapTypeControl());
		
		// Icono Quitar el resto comentado si voy a utilizar el icono por defecto.
				var iconoMarca = new GIcon(G_DEFAULT_ICON);
				
				<?php 
				/**
				 * Información por si tubiera icono propio
				 */ ?>
				iconoMarca.image = "/images/gmap.png"; 
				var tamanoIcono = new GSize(29,30);
				iconoMarca.iconSize = tamanoIcono;
				//iconoMarca.shadow = "/images/gmap.png"; 
				//var tamanoSombra = new GSize(29,30);
				//iconoMarca.shadowSize = tamanoSombra;
				iconoMarca.iconAnchor = new GPoint(5,35);
				
		/* Por defecto mapa normal. */
		map.setMapType(G_NORMAL_MAP); 
      
		var point = new GPoint (<?php echo $punto_x;?>, <?php echo $punto_y;?>);

		marker = new GMarker(point, iconoMarca);

		map.addOverlay(marker);

		/*
		GEvent.addListener(map, "moveend", function(marker, point) {
			update_info_map(point);
		});
		
		GEvent.addListener(map, "maptypechanged", function(marker, point) {
			update_info_map(point);
		});
		*/
		
		GEvent.addListener(map, "click", function (overlay,point) {
			if (point){
				marker.setPoint(point);
				
				update_info_map(point);
			}
		});
	}
}

function update_info_map(point) {

	$("#map_x").val(point.x);
	$("#map_y").val(point.y);
	
	$("#map_zoom").val(map.getZoom());
	
	/* Cargo el tipo de mapa */
	if (map.getCurrentMapType() == G_SATELLITE_MAP) {
		$("#map_tipo").val("satellite");
	}
	
	if (map.getCurrentMapType() == G_HYBRID_MAP) {
		$("#map_tipo").val("hybrid");
	}
	
	if (map.getCurrentMapType() == G_PHYSICAL_MAP) {
		$("#map_tipo").val("terrain");
	}
	
	if (map.getCurrentMapType() == G_NORMAL_MAP) {
		$("#map_tipo").val("normal");
	}
}

/* Para ubicación de calles */
function showAddress(address) {
	if (geocoder) {
		geocoder.getLatLng( address, function(point) {
			if (!point) {
				alert("Lo siento, no es posible ubicar " + address);
			} else {
				zoom = 13;
				
				map.setCenter(point, zoom);
				marker.setPoint(point);
				
				update_info_map(point);
			}
		});
	}
}

window.onload=load
//]]>
</script>