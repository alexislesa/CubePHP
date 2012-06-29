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
 * Muestra un bloque de geoposicionamiento devuelto por el artículo
 * 
 * Array de datos que es posible devolver:
 *	- mapa_tipo			Tipo de mapa a mostrar (Satelite, Físico, Normal, etc)
 *	- mapa_x			Latitud
 *	- mapa_y			Longitud
 *	- mapa_zoom			Zoom del mapa
 *
 * @changelog:
 */
 
if (!empty($v['gmap']) && is_array($v['gmap'])) { 

	$gm = $v['gmap'][1];
	if ($gm['mapa_extra'] != '') {
		$gm = array_merge($gm, json_decode($gm['mapa_extra'], true));
	}

	/**
	 * Selecciono el tipo de mapa a mostrar
	 * Gmap_tipo 
	 */
	switch($gm['mapa_tipo']) {
		case 'G_SATELLITE_MAP':
		case 'satellite':
		default:
			$mapType = 'satellite';
		break;

		case 'G_HYBRID_MAP':
		case 'hybrid':
			$mapType = 'hybrid';
		break;

		case 'G_PHYSICAL_MAP':
		case 'terrain':
			$mapType = 'terrain';
		break;
	}
	?>
	
	<section id="gmap" class="article-block">
		
		<h4>Georeferencia</h4>
		
		<div id="map" style="width:500px; height:340px"></div>
		
		<?php if (isset($gm['texto']) && $gm['texto'] != '') { ?>
		
			<div><?php echo $gm['texto'];?></div>

		<?php } ?>

		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		
		<script type="text/javascript">
			var latlng = new google.maps.LatLng(<?php echo $gm['mapa_x'];?>,<?php echo $gm['mapa_y'];?>);
			var options = {
				zoom: <?php echo $gm['mapa_zoom'];?>,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.<?php echo strtoupper($mapType);?>
			};

			map = new google.maps.Map(document.getElementById('map'), options);

			var marker = new google.maps.Marker({
				icon: '/images/gmap.png',
				title:"<?php echo $gm['texto'];?>",
				position: latlng
			});
			marker.setMap(map);
			map.setCenter(location);
			
			<?php 
			/**
			 * Estilos personalizados de google map 
			 * Info en: 
			 * - http://googlegeodevelopers.blogspot.com/2010/05/add-touch-of-style-to-your-maps.html
			 * - http://googlemapsmania.blogspot.com/2010/05/add-style-to-your-google-maps.html
			 *
			 * Wizart: 
			 * - http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html
			 */
			?>

			/*
			var grayStyles = [ { 
				featureType: "all", 
				elementType: "all", 
				stylers: [ { saturation: -100 }, { lightness: 16 } ] 
			} ];

			var styledMapType = new google.maps.StyledMapType(grayStyles, {name: "grayStyles"});
			map.mapTypes.set("grayStyles", styledMapType);				
			*/
		</script>
		
	</section><!--fin /gmap-->
	
<?php } ?>