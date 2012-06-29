<?php
/**
 * Levanta información sobre los partidos que se estan jugando actualmente
 * y los guarda en la base de datos
 */

/* Cargo las funciones utilizadas en el cron */
include("funciones.inc.php");

/* Incluyo el Conector */
include ('../../includes/comun/conector.inc.php');

/* Cargo los Min a Min de los partidos a jugarse ahora */
$sql = "UPDATE fixture_deportes SET fix_activo = '0' WHERE fix_deporte = 'tenis' AND fix_jugado = '0'";
if (!$res = $db->query($sql)) {

	echo "<br> Error, no puedo inicializar los partidos comenzados.";

} 
 
$sql = "SELECT * FROM fixture_deportes WHERE fix_jugado = '0'";

if ($res = $db->query($sql)) {

	if ($db->num_rows($res)) {
	
		echo "<br/> Total de partidos próximos a jugarse:".$db->num_rows($res);
	
		for ($i=0; $i<$db->num_rows($res); $i++) {
		
			$rs = $db->next($res);

			/* Si esta próximo a comenzar */
			$fecha_partido = $rs["fix_fecha"];
			
			echo "<br/>Partido ID:{$rs["fix_nombre"]} - ";
			echo "Fecha: {$fecha_partido} (".date("d.m.Y - G:i", $fecha_partido).") ";
			echo "-> ahora: ".time()." (".date("G:i").") - día hoy:".date("ymd"). " == ".date("ymd", $rs["fix_fecha"]);
			
			if (time() > ($fecha_partido - (60*30)) && (date("ymd") == date("ymd", $fecha_partido))) {
			
				$sql = "UPDATE fixture_deportes SET fix_activo = '1' WHERE fix_xml = '{$rs["fix_xml"]}'";

				echo "<br/> Actualizo información del partido. <b>(Jugandose ahora)</b>";
			
				if (!$res2 = $db->query($sql)) {
				
					echo "<br/> Error al cargar el min a min del partido. <br/>SQL:".$sql;
				
				}
				
			} else {
			
				/* Si la fecha de la que se jugo el mayor 24 hs de hoy, lo cargo como jugado */
				if (($fecha_partido + (60*60*24)) < time()) {
				
					$sql = "UPDATE fixture_deportes SET fix_activo = '0', fix_jugado = '1' WHERE fix_xml = '{$rs["fix_xml"]}'";

					echo "<br/> Actualizo información del partido. <b>(Ya se jugó)</b>";
				
					if (!$res2 = $db->query($sql)) {
					
						echo "<br/> Error al cargar el min a min del partido. <br/>SQL:".$sql;

					}				
				
				}
			
			}
			
			echo "<br/>";
		}
		
	} else {
	
		echo "<br/> No hay fechas de partidos próximos";
	}
	
} else {

	echo "<br/> Error al cargar los partidos a jugarse. SQL:".$sql;
}

echo "<br/><br/><br/> Cargo listado de los partidos que se estan jugando actualmente. <br/>";

/* Cargo la información de los partidos que se estan jugando */
$sql = "SELECT * FROM fixture_deportes WHERE fix_activo = '1' AND fix_deporte = 'tenis'";
$xml_data_list = array();
if ($res = $db->query($sql)) {

	if ($db->num_rows($res)) {
	
		echo "<br/> Cantidad de Partidos jugandose actualmente:".$db->num_rows($res);
		
		for ($i=0; $i<$db->num_rows($res); $i++) {
		
			$rs = $db->next($res);
			$xml_data_list[$rs["fix_ficha"]] = "../cache/".$rs["fix_xml"];
			
			echo "<br/> Partido jugandose ahora:".$rs["fix_nombre"]." -> ".date("d.m.Y G:i",$rs["fix_fecha"]);
			
		}

	} else {
	
		echo "<br/> No hay partido jugandose actualmente.";
		
	}
	
} else {

	echo "<br/> Error al cargar los partidos que se estan jugando ahora. <br/>SQL:".$sql;

}

foreach ($xml_data_list as $file_src => $file_dst) {

	$xml_data = file_get_contents($file_src);

	$arrayData = xml2array($xml_data,1, "attribute");

	//Guardo la información serializada en un archivo local.
	$contenido = serialize($arrayData);

	if (!$archivo = fopen($file_dst, "w")) {
	
		echo "<br/> No se puede abrir el archivo ($file_dst)";

	}

	if (fwrite($archivo, $contenido) === FALSE) {
	
		echo "<br/> No se puede escribir al archivo ($file_dst)";
		
	}
	fclose($archivo);
	
	/* Consulto si el partido finalizó */
	$rs = $arrayData["ficha"];
	$ficha_campeonato = $rs["campeonato"]["value"];
	$ficha_partido = $rs["fichapartido"]["attr"]["nombrenivel"];
	$ficha_estado = $rs["fichapartido"]["estadoEvento"]["value"];
	
	$ficha_loc_nombre = $rs["fichapartido"]["equipo"][0]["attr"]["nombre"];
	$ficha_loc_pais = $rs["fichapartido"]["equipo"][0]["attr"]["paisNombre"];
	$ficha_loc_sigla = $rs["fichapartido"]["equipo"][0]["attr"]["paisSigla"];
	$ficha_loc_saque = $rs["fichapartido"]["equipo"][0]["attr"]["saque"];
	
	$ficha_vis_nombre = $rs["fichapartido"]["equipo"][1]["attr"]["nombre"];
	$ficha_vis_pais = $rs["fichapartido"]["equipo"][1]["attr"]["paisNombre"];
	$ficha_vis_sigla = $rs["fichapartido"]["equipo"][1]["attr"]["paisSigla"];
	$ficha_vis_saque = $rs["fichapartido"]["equipo"][1]["attr"]["saque"];	
	
	echo "<br/><br/> - Partido:".$ficha_loc_nombre." vs ".$ficha_vis_nombre;
	
	echo "<br/>Estado:";
	echo (!empty($ficha_estado)) ? $ficha_estado : "null";
	
	if (!empty($arrayData["ficha"]["fichapartido"]["estadoEvento"]["attr"]["idestado"]) 
	&& $arrayData["ficha"]["fichapartido"]["estadoEvento"]["attr"]["idestado"] == "8") {

		$sql = "UPDATE fixture_deportes SET fix_jugado = '1' WHERE fix_xml = '{$file_src}'";

		echo "<br> Doy de baja este partido porque ya se jugó. SQL:".$sql;
		
		if (!$res2 = $db->query($sql)) {
		
			echo "<br/> Error al intentar dar de baja el partido. SQL:".$sql;
			
		}
	}
}
?>