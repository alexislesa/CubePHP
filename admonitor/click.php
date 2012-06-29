<?php
/**
 * Realiza el conteo de los clicks a los banners
 *
 * @package		Widgets
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory
 * @licence 	Comercial
 * @version 	1.0
 * @revision 	28.04.2011
 *
 * @changelog:
 * @revision 28.12.2011
 *	- Added: Se agregó compatibilidad con google analytics para que el destinatario pueda medir la eficiencia de la campaña del banner.
 * 
 * @revision 27.05.2011
 *	- Fix: se reparó problema mínimo al recibir caracteres fuera de lo normal a base64 y 
 *			cancela el conteno en caso de existir error en el proceso de bas64 y unserialize de la información.
 *
 * @revision 28.04.2011
 */
 
/* Conector */
include ('../includes/comun/conector.inc.php');

$banId = (!empty($_GET["id"]) && is_string($_GET["id"])) ? $_GET["id"] : false;

if ($banId) {

	if ($data = base64_decode($banId)) {
	
		if ($ban = unserialize($data)) {

			if (!empty($ban["id"]) && is_numeric($ban["id"])) {

				$sql = "SELECT banner_id, banner_link, banner_nombre, zona_id, zona_nombre
					FROM publicidad_banners, publicidad_zonas, publicidad_nexo 
					WHERE banner_id = '{$ban["id"]}' 
						AND zona_filename = '{$ban["z"]}' 
						AND nexo_banner_id = banner_id 
						AND nexo_zona_id = zona_id 
				LIMIT 0,1";

				if ($res = $db->query($sql)) {
				
					if ($db->num_rows($res)) {
					
						$rs = $db->next($res);

						$sql = "UPDATE LOW_PRIORITY publicidad_banners 
							SET banner_clicks = (banner_clicks+1) 
							WHERE banner_id = '{$ban["id"]}'";

						if ($res = $db->query($sql)) {
							
							$fecha = mktime(0,0,0,date("m"), date("d"), date("Y"));
							
							$sql = "INSERT DELAYED INTO publicidad_stats 
								(stats_fecha, stats_zona, stats_banner, stats_views, stats_clicks)
								VALUES ('{$fecha}', '{$rs["zona_id"]}', '{$ban["id"]}', '0', 1)
							ON DUPLICATE KEY UPDATE stats_clicks = (stats_clicks + 1)";
							$res = $db->query($sql);
						}
						
						/** 
						 * Información para medir la campaña del anuncio
						 * Más información para generar la url en: 
						 *	https://support.google.com/analytics/bin/answer.py?hl=es&utm_id=ad&answer=1033867
						 */
						$utmSource = $_SERVER["SERVER_NAME"];
						$utmMedium = "banner";
						$utmTerm = "";
						$utmContent = $rs["zona_nombre"];
						$utmCampain = $rs["banner_nombre"];

						$bannerLink = $rs["banner_link"];
						if (strpos($bannerLink, "?") === false) {
							$bannerLink.="?";
						}
						$bannerLink.= "&utm_source=".$utmSource;
						$bannerLink.= "&utm_medium=".$utmMedium;
						$bannerLink.= "&utm_term=".$utmTerm;
						$bannerLink.= "&utm_content=".$utmContent;
						$bannerLink.= "&utm_campain=".$utmCampain;
						
						Header("Location: {$bannerLink}");
					}
				}
			}
			
		} // End if unserialize
		
	} // End if base64_decode
}
?>