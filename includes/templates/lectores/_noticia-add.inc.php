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
 *
 * @changelog:
 */
$tabindex=1; 
?>

<?php include ($path_root."/includes/templates/lectores/objetos/profile.inc.php"); ?>
				
<article>
	
	<header><h2 id="title">Publicar noticia</h2></header>
	
	<figure><img src="<?php echo $usr->campos["lector_avatar"];?>" width="40" height="40" alt="" title="" /></figure>
			
	<?php 
	/* Muestra mensaje de error */
		if ($error_msj) {
		include($path_root."/includes/templates/herramientas/mensaje-error.inc.php");
		}
	?>

	<form name="fcontacto" id="fcontacto" action="?act=true" method="post" enctype="multipart/form-data">
	
		<section class="formulario">
			
			<h5>cuerpo de la noticia</h5>
			
			<div class="form-block wd2">
				<label><strong>*</strong> Titulo:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" name='titulo' autocomplete="off" value='<?php echo !empty($dataToSkin["titulo"]) ? $dataToSkin["titulo"] : "";?>' maxlength='80'/>
						
				<div class="countdown">
					<input type="text" value="80" id="nota-titulo">
					<span id="nota-titulo-html"></span>
					<span> Caracteres restantes. Máximo 80 caracteres.</span>
				</div>
						
			</div><!--fin form block-->
					
			<div class="form-block formprofile">
	
				<label><strong>*</strong> Descripción:</label>
				<textarea tabindex='<?php echo $tabindex++;?>' name='bajada'><?php echo !empty($dataToSkin["bajada"]) ? $dataToSkin["bajada"] : "";?></textarea>

				<div class="countdown">
					<input type="text" value="240" id="nota-bajada"/>
					<span> Caracteres restantes. Máximo 240 caracteres.</span>
				</div>
				
			</div><!--fin formprofile-->
					
			<div class="form-block formprofile">
	
				<label><strong>*</strong>  Texto:</label>
				<textarea tabindex='<?php echo $tabindex++;?>' name='texto'><?php echo !empty($dataToSkin["texto"]) ? $dataToSkin["texto"] : "";?></textarea>
				
				<div class="countdown">
					<input type="text" value="4000" id="nota-texto"/>
					<span>Caracteres restantes. Máximo 4000 caracteres.</span>
				</div>
						
			</div><!--fin formblock-->						
				
			<h5>Foto</h5>
					
			<div class="form-block wd">
	
				<label>Subir Imágen:</label>		
				<input tabindex='<?php echo $tabindex++;?>' type='file' class="file" name='foto1' value=''/>
						
				<p class="disclaimer">
					El archivo debe ser de tipo JPG o GIF, cualquier otro tipo no es válido. <br/>
					Tamaño del archivo: La imagen ha de ser de un mínimo de 440 pix. de ancho, y no puede exceder de 512 Kb de peso.
				</p>
				
			</div><!--fin form block-->						
	
			<div class="form-block wd2">
	
				<label>Descripción de la imágen:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" autocomplete="off" name='foto_desc' value='<?php echo !empty($dataToSkin["foto_desc"]) ? $dataToSkin["foto_desc"] : "";?>' maxlength='80'/>
				
				<div class="countdown">
					<input type="text" value="80" readonly id="nota-imagen"/>
					<span>Caracteres restantes. Máximo 80 caracteres.</span>
				</div>
						
			</div><!--fin form block-->
	
			<h5>Video</h5>
					
			<div id="videoadd" class="form-block wd">
	
				<label>URL del video de YouTube:</label>
				<input tabindex='<?php echo $tabindex++;?>' autocomplete="off" type='text' class="txt" name='video' value='<?php echo !empty($dataToSkin["video"]) ? $dataToSkin["video"] : "";?>' maxlength='180'/>

				<p class="disclaimer">Ejemplo: <u>http://www.youtube.com/watch?v=MQCNuv2QxQY</u></p>
	
			</div><!--fin form block-->
					
			<h5>Mapa</h5>
					
			<input type="hidden" name="map_map" id="map_map" value="0" />
			<input type="hidden" name="map[x]" id="map_x" value="<?php echo !empty($dataToSkin["map"]["x"]) ? $dataToSkin["map"]["x"] : "";?>" />
			<input type="hidden" name="map[y]" id="map_y" value="<?php echo !empty($dataToSkin["map"]["y"]) ? $dataToSkin["map"]["y"] : "";?>" />
			<input type="hidden" name="map[zoom]" id="map_zoom" value="<?php echo !empty($dataToSkin["map"]["zoom"]) ? $dataToSkin["map"]["zoom"] : "";?>" />
			<input type="hidden" name="map[tipo]" id="map_tipo" value="<?php echo !empty($dataToSkin["map"]["tipo"]) ? $dataToSkin["map"]["tipo"] : "";?>" />
					
			<!-- -->
			<div class="form-block formprofile" id="gprofile">
					
				<label>Definir Ubicación:</label>
						
				<div class="gmap2">
					<div id="map" style="width:216px; height:180px"></div>
				</div>
							
				<p class="disclaimer">Indique la ubicación haciendo click en el mapa o utilice el buscador de calles y/o ciudades.</u></p>
	
			</div>
			
			<div class="form-block buscacalle">
					
				<label>Buscar calle:</label>
				<input type="text" size="30" name="address" value="" class="txt" />
				<input type="button" title="Buscar" name="busmap" value="Buscar Calle" onclick="showAddress(this.form.address.value);" class="buscacalle"/>
			</div>					
				
			<!-- -->
			<div class="form-block">

				<div class="item-normas">
					<span class="checkbox"><input class="check" tabindex='<?php echo $tabindex++;?>' type="checkbox" name="terminosycondiciones" checked value="1" /></span>
					<label>He leído y acepto las <a rel="nofollow" target="_blank" title="Normas de Participación" href="/institucional/normas.php">Normas de Participación</a> y la <a href="/institucional/politicas.php" title="Política de Privacidad" target="_blank">Política de Privacidad</a> </label>
				</div>		
						
			</div><!--fin form block-->

			<div class="form-block form-bt">
				<input tabindex='<?php echo $tabindex++;?>' type='submit' name='registrarme' title='Enviar Noticia' value='Enviar Noticia'/>			
			</div><!--fin form block-->
					
		</section>
		
	</form>
				
	<footer>
		Los campos marcados con un <strong>(*)</strong> son obligatorios
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
<script type="text/javascript">
SI.Files.stylizeAll();

$("input[name='foto1']").change(function() {
	$(".cabinet span").html($(this).val());
});

</script>