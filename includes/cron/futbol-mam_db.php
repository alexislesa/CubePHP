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
$sql = "UPDATE noticias_varios SET campo_4 = ''";
if (!$res = $db->query($sql)) {

	echo "<br> Error, no puedo inicializar los partidos comenzados.";

} 
 
$sql = "SELECT * FROM noticias_varios WHERE campo_5 != '0' AND campo_5 != ''";

if ($res = $db->query($sql)) {

	if ($db->num_rows($res)) {
	
		echo "<br/> Total de partidos próximos a jugarse:".$db->num_rows($res);
	
		for ($i=0; $i<$db->num_rows($res); $i++) {
		
			$rs = $db->next($res);

			/* Si esta próximo a comenzar */
			$fecha_partido = (int) $rs["campo_5"];
			
			echo "<br/>Partido ID:{$rs["campo_id"]} - ";
			echo "Fecha: {$fecha_partido} (".date("d.m.Y - G:i", $fecha_partido).") ";
			echo "-> ahora: ".time()." (".date("G:i").") - día hoy:".date("ymd"). " == ".date("ymd", $rs["campo_5"]);
			
			if (time() > ($fecha_partido - (60*30)) && (date("ymd") == date("ymd", $fecha_partido))) {
			
				$sql = "UPDATE noticias_varios SET campo_4 = '1' WHERE campo_id = {$rs["campo_id"]}";
				
				echo "<br/> Actualizo información del partido. <b>(Jugandose ahora)</b>";
			
				if (!$res2 = $db->query($sql)) {
				
					echo "<br/> Error al cargar el min a min del partido. <br/>SQL:".$sql;

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

/**
 * Cargo la información de los partidos que se estan jugando
 */
$sql = "SELECT * FROM noticias_varios WHERE campo_4 = '1'";
$xml_data_list = array();
if ($res = $db->query($sql)) {

	if ($db->num_rows($res)) {
	
		echo "<br/> Cantidad de Partidos jugandose actualmente:".$db->num_rows($res);
		
		for ($i=0; $i<$db->num_rows($res); $i++) {
		
			$rs = $db->next($res);
			$xml_data_list[$rs["campo_2"]] = "../cache/".$rs["campo_3"];
			
			echo "<br/> Partido jugandose ahora:".$rs["campo_2"]." -> ".date("d.m.Y G:i",$rs["campo_5"]);
			
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
	echo "<br/><br/> - Partido:".$arrayData["ficha"]["fichapartido"]["equipo"][0]["attr"]["nombre"]." vs ";
	echo $arrayData["ficha"]["fichapartido"]["equipo"][1]["attr"]["nombre"];
	
	echo "<br/>Estado:";
	echo (!empty($arrayData["ficha"]["fichapartido"]["estadoEvento"]["value"])) ? $arrayData["ficha"]["fichapartido"]["estadoEvento"]["value"] : "null";
	
	if (!empty($arrayData["ficha"]["fichapartido"]["estadoEvento"]["value"]) 
	&& $arrayData["ficha"]["fichapartido"]["estadoEvento"]["value"] == "Finalizado") {

		if (!empty($arrayData["ficha"]["fichapartido"]["horaEstadoEvento"]["value"]) 
		&& $arrayData["ficha"]["fichapartido"]["horaEstadoEvento"]["value"] != "") {
		
			$fin_hora = substr($arrayData["ficha"]["fichapartido"]["horaEstadoEvento"]["value"],0,2);
			$fin_min = substr($arrayData["ficha"]["fichapartido"]["horaEstadoEvento"]["value"],3,2);
			$fin_min = ((int) $fin_min) + 30;
			
			/* Si el partido finalizó hace 30 min, no lo muestro más */
			if (mktime($fin_hora, $fin_min,0, date("m"), date("d"), date("Y")) > mktime(date("G"), date("i"), 0, date("m"), date("d"), date("Y"))) {
				
				$sql = "UPDATE noticias_varios SET campo_4 = '', campo_5 = '0' WHERE campo_2 = '{$file_src}'";
				
				echo "<br> Doy de baja este partido porque ya se jugó. SQL:".$sql;
				
				if (!$res2 = $db->query($sql)) {
				
					echo "<br/> Error al intentar dar de baja el partido. SQL:".$sql;
					
				}
			}
			
		}

	}
	
}

?>