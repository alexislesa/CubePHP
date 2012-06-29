<?php
/**
 * Parsea la información del Fixture y carga los partidos a jugarse en los próximos 5 días
 */

/* Incluyo el Conector */
include ('../../includes/comun/conector.inc.php');

$partidos_arr = array();
$partidos_arr["nacionalb"] = "../cache/df-nacionalb-fixture.inc.php";
$partidos_arr["primeraa"] = "../cache/df-primeraa-fixture.inc.php";

/* Parsea los partidos contratados y carga el listado de los min. a min. próximos a jugarse */
foreach ($partidos_arr as $prefix => $path_fixture) {

	if ($contenido = file_get_contents($path_fixture)) {

		$dfix = unserialize($contenido);

		$partido_desde = time() - (60*60*24*2);
		$partido_hasta = time() + (60*60*24*7);
		$partidos = array();

		$cantidad_fechas = count($dfix["fixture"]["fecha"]);
		
		/* Recorro todas las fechas a jugarse */
		for ($i=0; $i<$cantidad_fechas; $i++) {

			$fpartido = $dfix["fixture"]["fecha"][$i];
			
			$cantidad_partidos = count($fpartido["partido"]);
			
			/* Recorro todos los partidos a jugarse en esa fecha */
			for ($j=0; $j<$cantidad_partidos; $j++) {
			
				$fpart = $fpartido["partido"][$j];

				$fecha_anio = substr($fpart["attr"]["fecha"],0,4);
				$fecha_mes = substr($fpart["attr"]["fecha"],4,2);
				$fecha_dia = substr($fpart["attr"]["fecha"],6);
				
				$fecha_hora = 0;
				$fecha_min = 0;
				
				if (!empty($fpart["attr"]["hora"]) && $fpart["attr"]["hora"] != "") {
					$fecha_hora = substr($fpart["attr"]["hora"],0,2);
					$fecha_min = substr($fpart["attr"]["hora"],3,2);
				}
				
				$fecha_unix = mktime($fecha_hora,$fecha_min,0,$fecha_mes, $fecha_dia, $fecha_anio);
				
				if ($fecha_unix > $partido_desde && $fecha_unix < $partido_hasta) {
					
					$pid = $fpart["attr"]["id"];
					
					$pid_partido = utf8_decode($fpart["local"]["attr"]["nombreasociacion"])." vs. ".utf8_decode($fpart["visitante"]["attr"]["nombreasociacion"]);
					
					/* Nombre del XML del partido */
					$pid_xml = $fpart["attr"]["id"].".mam.xml";
					
					echo "<br/> Partido fecha:".date("d.m.Y G:i", $fecha_unix)." - ";
					echo "Partido:".utf8_decode($fpart["local"]["attr"]["nombreasociacion"]);
					echo " vs. ".utf8_decode($fpart["visitante"]["attr"]["nombreasociacion"]);
					
					$sql = "SELECT * FROM noticias_varios WHERE campo_3 = '{$pid}.mam.xml'";
					
					if ($res = $db->query($sql)) {
					
						if (!$db->num_rows($res)) {
							
							$sql = "INSERT INTO noticias_varios (campo_1, campo_2, campo_3, campo_5, campo_6) VALUES (
								'{$pid_partido}', 
								'http://www.df06.com.ar/xml/deportes.futbol.{$prefix}.ficha.{$pid}.mam.xml',
								'{$pid_xml}',
								'{$fecha_unix}',
								'b'
							)";
							
							if ($res = $db->query($sql)) {
								echo "<br/> Partido Nuevo. <br/>";
							} else {
								echo "<br> Error al cargar el partido Nuevo. <br/>";
							}
							
						} else {
						
							$rs = $db->next($res);

							/* Se actualiza solo si no jugó */
							if ($rs["campo_5"] != "" && $rs["campo_5"] != "0") {
								$sql = "UPDATE noticias_varios SET campo_5 = '{$fecha_unix}' WHERE campo_3 = '{$pid_xml}'";
								
								if ($res = $db->query($sql)) {
								
									echo "<br> Actualizo Fecha, partido anteriormente cargado";
									
								} else {
								
									echo "<br> Error al actualizar fecha.";
									
								}

							} else {
							
								echo "<br/> El partido ya se jugó.";
								
							}
						}
					} else {

						echo "<br> Error al cargar el partido.";

					}

					echo "<br/>";

				}

			}
		}

	}

} // End foreach partidos

echo "<br/><br/> -Actualizado:".date("d.m.Y G:i:s");
?>