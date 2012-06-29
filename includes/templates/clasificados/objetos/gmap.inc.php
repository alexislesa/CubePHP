<?php
/**
 * Muestra un bloque de geoposicionamiento devuelto por el artículo
 */
 
if (!empty($v["gmap"]) && is_array($v["gmap"])) { 
	
	/**
	 * Selecciono el tipo de mapa a mostrar
	 * Gmap_tipo 
	 */
	switch($v["gmap"][1]["mapa_tipo"]) {
		case "satellite":
			$map_type_name = "G_SATELLITE_MAP";
		break;
		
		case "hybrid":
			$map_type_name = "G_HYBRID_MAP";
		break;

		case "terrain":
			$map_type_name = "G_PHYSICAL_MAP";
		break;
		
		default:
			$map_type_name = "G_NORMAL_MAP";
		break;
	}
	?>
	
	<section id="gmap" class="article-block">
		
		<h4>Georeferencia</h4>
		
		<div id="map" style="width:500px; height:340px"></div>
		
		<?php if (isset($v["gmap"][1]["texto"]) && $v["gmap"][1]["texto"] != "") { ?>
		
			<div>
			
				<?php echo $v["gmap"][1]["texto"];?>
				
			</div>
			
		<?php } ?>
		
		<script src="http://maps.google.com/maps?file=api&v=2.x&key=<?php echo $ss_config["api_google_maps"];?>" type="text/javascript"></script>
	
		<script type="text/javascript">
		//<![CDATA[
	
		var map = null;
		var geocoder = null;

		function load() {
			if (GBrowserIsCompatible()) {
	
				map = new GMap2(document.getElementById("map"), { size:new GSize(640,340) });
				
				map.setCenter(new GLatLng(<?php echo $v["gmap"][1]["mapa_y"];?>,<?php echo $v["gmap"][1]["mapa_x"];?>),<?php echo $v["gmap"][1]["mapa_zoom"];?>);
	
				<?php /* Agrego controles de Zoom */ ?>
				map.addControl(new GLargeMapControl());
	
				<?php /* Agrego controles de Tipo satelite, hibrido, etc */ ?>
				map.addControl(new GMapTypeControl());
	
				<?php /* Agrega mapa físico y google earth */ ?>
				map.addMapType(G_PHYSICAL_MAP);
				map.addMapType(G_SATELLITE_3D_MAP);
	
				<?php /* Seteo el tipo de mapa a mostrar: G_SATELLITE_TYPE, G_NORMAL_MAP */ ?>
				map.setMapType(<?php echo $map_type_name;?>); 
			
				<?php /* Icono por defecto */ ?>
				var iconoMarca = new GIcon(G_DEFAULT_ICON);
				
				<?php 
				/**
				 * Información por si tubiera icono propio
				 *
					iconoMarca.image = "/images/map.png"; 
					iconoMarca.iconSize = new GSize(29,29);
					iconoMarca.shadow = "/images/maps/icono_1_sombra.png"; 
					iconoMarca.shadowSize = new GSize(22,18);
					iconoMarca.iconAnchor = new GPoint(5,35);
				 *
				 */
				?>

				function createMarker(point) {
					var marker = new GMarker(point, iconoMarca);
	
					GEvent.addListener(marker, 'click', function() {
						// marker.openInfoWindowHtml(outHTML);
					});
					return marker;
				} 
	
				<?php /* Cargo el punto en el Mapa */ ?>
				var point = new GPoint (<?php echo $v["gmap"][1]["mapa_x"];?>, <?php echo $v["gmap"][1]["mapa_y"];?>);
				var marker = createMarker (point);
				map.addOverlay(marker);
				

				<?php 
				/**
				 * Estilos personalizados de google map 
				 * Info en: http://googlegeodevelopers.blogspot.com/2010/05/add-touch-of-style-to-your-maps.html
				 * http://googlemapsmania.blogspot.com/2010/05/add-style-to-your-google-maps.html
				 * Wizart: http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html
				 *
				 */ ?>
				 
				/*
				var grayStyles = [ { 
					featureType: "all", 
					elementType: "all", 
					stylers: [ { saturation: -100 }, { lightness: 16 } ] 
				} ];
				
				var styledMapType = new google.maps.StyledMapType(grayStyles, {name: "grayStyles"});
				map.mapTypes.set("grayStyles", styledMapType);				
				*/
				
				/* Capa de Panoramio */
				/*
				var capa_panoramio = new GLayer("com.panoramio.all");
				map.addOverlay(capa_panoramio); 
				*/
				
				/* Capa de Wikipedia */
				/*
				var capa_wikipedia = new GLayer("org.wikipedia.es");
				map.addOverlay(capa_wikipedia);
				*/
			
			}
		}
	
		window.onload=load;
		//]]>
		</script>
		
	</section><!--fin /gmap-->
	
<?php } ?>