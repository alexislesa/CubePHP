<?php
/** 
 * Cargo los Min a Min de Nacional A y Nacional B
 */
$sql = "SELECT * FROM noticias_varios WHERE campo_4 = '1' ORDER BY campo_6, campo_5";

if ($res = $db->query($sql)) {

	if ($db->num_rows($res)) {
	
		$info_mam_arr = array();
		for ($i=0; $i<$db->num_rows($res); $i++) {
			$rs = $db->next($res);
			if (file_exists($path_root."/includes/cache/".$rs["campo_3"])) {
				$info_mam_arr[$i] = $rs["campo_3"];
			}
		}
		
		if (count($info_mam_arr) >= 3) {
			
			$info_mam_1 = $info_mam_arr[0];
			$info_mam_2 = $info_mam_arr[1];
			$info_mam_3 = $info_mam_arr[2];
			
			include($path_root."/includes/templates/extras/min-a-min3.inc.php");
			
			// Si es más de tres
			if (count($info_mam_arr) == 4) {
			
				$info_mam_1 = $info_mam_arr[3];
				
				include($path_root."/includes/templates/extras/min-a-min.inc.php");
				
			} else {
			
				if (count($info_mam_arr) == 5) {
				
					$info_mam_1 = $info_mam_arr[3];
					$info_mam_2 = $info_mam_arr[4];
					include($path_root."/includes/templates/extras/min-a-min2.inc.php");
					
				} else {
				
					if (count($info_mam_arr) == 6) {
					
						$info_mam_1 = $info_mam_arr[3];
						$info_mam_2 = $info_mam_arr[4];
						$info_mam_3 = $info_mam_arr[5];
						include($path_root."/includes/templates/extras/min-a-min3.inc.php");
					}
				}
			
			}
			
		} else {

			if (count($info_mam_arr) == 1) {
				$info_mam_1 = $info_mam_arr[0];
				include($path_root."/includes/templates/extras/min-a-min.inc.php");
			} 
			
			if (count($info_mam_arr) == 2) {
				$info_mam_1 = $info_mam_arr[0];
				$info_mam_2 = $info_mam_arr[1];
				include($path_root."/includes/templates/extras/min-a-min2.inc.php");
			} 
		}
		
	}
}


/**
 * Retorna array de datos con los valores del min a min
 *
 * @access	public
 * @param	$file	string	Path donde esta el archivo que contiene las var. del partido
 * @return	array	Información del partido que se esta jugando
 */
function data_min($file) {

	if ($contenido = file_get_contents($file)) {
	
		$data_min = unserialize($contenido);

		$partido_arr = array();
		
		$partido_arr["estado"] = $data_min["ficha"]["fichapartido"]["estadoEvento"]["value"];
		
		$partido_arr["loc_nombre"] = utf8_decode($data_min["ficha"]["fichapartido"]["equipo"][0]["attr"]["nombreCorto"]);
		$partido_arr["loc_img"] = $data_min["ficha"]["fichapartido"]["equipo"][0]["attr"]["id"];
		$partido_arr["loc_goles"] = $data_min["ficha"]["fichapartido"]["equipo"][0]["attr"]["goles"];

		$partido_arr["vis_nombre"] = utf8_decode($data_min["ficha"]["fichapartido"]["equipo"][1]["attr"]["nombreCorto"]);
		$partido_arr["vis_img"] = $data_min["ficha"]["fichapartido"]["equipo"][1]["attr"]["id"];
		$partido_arr["vis_goles"] = $data_min["ficha"]["fichapartido"]["equipo"][1]["attr"]["goles"];

		$partido_arr["hora_actual"] = $data_min["ficha"]["horaActual"]["value"];

		$partido_incidencia = "";
		if (!empty($data_min["ficha"]["fichapartido"]["incidencias"]["incidencia"])) {
			if (empty($data_min["ficha"]["fichapartido"]["incidencias"]["incidencia"]["attr"])) {
				foreach ($data_min["ficha"]["fichapartido"]["incidencias"]["incidencia"] as $p_inc => $p_incidencia) {
					$partido_incidencia.= "<span>";
					$partido_incidencia.= $p_incidencia["attr"]["tipo"].":".$p_incidencia["minuto"]["value"]."' ".$p_incidencia["tiempo"]["value"];
					$partido_incidencia.= utf8_decode($p_incidencia["jugador"]["value"])." (".utf8_decode($p_incidencia["equiponomcorto"]["value"]).")";
					$partido_incidencia.="</span>";
				}
			} else {
				$p_incidencia = $data_min["ficha"]["fichapartido"]["incidencias"]["incidencia"];
				$partido_incidencia.= "<span>";
				$partido_incidencia.= $p_incidencia["attr"]["tipo"].":".$p_incidencia["minuto"]["value"]."' ".$p_incidencia["tiempo"]["value"];
				$partido_incidencia.= utf8_decode($p_incidencia["jugador"]["value"])." (".utf8_decode($p_incidencia["equiponomcorto"]["value"]).")";
				$partido_incidencia.="</span>";
			}
		}
		
		$partido_arr["incidencia"] = $partido_incidencia;
		
		return $partido_arr;
		
	} else {
	
		return false;

	}
}
?>