<?php
/**
 * Parsea la información del Fixture y carga los partidos a jugarse en los próximos 5 días
 */

/* Incluyo el Conector */
include ('../../includes/comun/conector.inc.php');

$partidos_arr = array();
$partidos_arr["cincinnati"] = $path_root."/includes/cache/deportes.tenis.cincinnati.fixture.xml";
$partidos_arr["copadavis"] = $path_root."/includes/cache/deportes.tenis.copadavis.fixture.xml";
$partidos_arr["gsaustralia"] = $path_root."/includes/cache/deportes.tenis.gsaustralia.fixture.xml";
$partidos_arr["indianwells"] = $path_root."/includes/cache/deportes.tenis.indianwells.fixture.xml";
$partidos_arr["madrid"] = $path_root."/includes/cache/deportes.tenis.madrid.fixture.xml";
$partidos_arr["masters"] = $path_root."/includes/cache/deportes.tenis.masters.fixture.xml";
$partidos_arr["miami"] = $path_root."/includes/cache/deportes.tenis.miami.fixture.xml";
$partidos_arr["montecarlo"] = $path_root."/includes/cache/deportes.tenis.montecarlo.fixture.xml";
$partidos_arr["montreal"] = $path_root."/includes/cache/deportes.tenis.montreal.fixture.xml";
$partidos_arr["paris"] = $path_root."/includes/cache/deportes.tenis.paris.fixture.xml";
$partidos_arr["rolandgarros"] = $path_root."/includes/cache/deportes.tenis.rolandgarros.fixture.xml";
$partidos_arr["roma"] = $path_root."/includes/cache/deportes.tenis.roma.fixture.xml";
$partidos_arr["toronto"] = $path_root."/includes/cache/deportes.tenis.toronto.fixture.xml";
$partidos_arr["usopen"] = $path_root."/includes/cache/deportes.tenis.usopen.fixture.xml";
$partidos_arr["wimbledon"] = $path_root."/includes/cache/deportes.tenis.wimbledon.fixture.xml";

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
			$fpart = $fpartido["partido"];

			$fecha_anio = substr($fpart["attr"]["fecha"],0,4);
			$fecha_mes = substr($fpart["attr"]["fecha"],4,2);
			$fecha_dia = substr($fpart["attr"]["fecha"],6);
			
			$fecha_hora = 0;
			$fecha_min = 0;
			
			if (!empty($fpart["attr"]["hora"]) && $fpart["attr"]["hora"] != "") {
				$fecha_hora = substr($fpart["attr"]["hora"],0,2);
				$fecha_min = substr($fpart["attr"]["hora"],3,2);
			}
			
			$partido_fecha_unix = mktime($fecha_hora,$fecha_min,0,$fecha_mes, $fecha_dia, $fecha_anio);
			
			if ($partido_fecha_unix > $partido_desde && $partido_fecha_unix < $partido_hasta) {
				
				$pid = $fpart["attr"]["id"];
				
				$partido_local = $fpart["local"]["value"];
				$partido_visitante = $fpart["visitante"]["value"];
				$partido_nombre = utf8_decode($partido_local)." vs. ".utf8_decode($partido_visitante);
				
				/* Nombre del XML del partido */
				$partido_xml = $fpart["attr"]["id"].".xml";
				
				echo "<br/> Partido fecha:".date("d.m.Y G:i", $partido_fecha_unix)." - ";
				echo "Partido:".utf8_decode($partido_local);
				echo " vs. ".utf8_decode($partido_visitante);
				
				$sql = "SELECT * FROM fixture_deportes WHERE fix_xml = '{$partido_xml}' AND fix_deporte = 'tenis'";
				
				if ($res = $db->query($sql)) {
				
					if (!$db->num_rows($res)) {
						
						$sql = "INSERT INTO fixture_deportes (fix_nombre, fix_ficha, fix_xml, fix_fecha, fix_activo, fix_deporte) VALUES (
							'{$partido_nombre}', 
							'http://www.df06.com.ar/xml/deportes.tenis.{$prefix}.ficha.{$pid}.xml',
							'{$partido_xml}',
							'{$partido_fecha_unix}',
							'0',
							'tenis'
						)";
						
						if ($res = $db->query($sql)) {
							echo "<br/> Partido Nuevo. <br/>";
						} else {
							echo "<br> Error al cargar el partido Nuevo. <br/>";
						}

					} else {
					
						$rs = $db->next($res);

						/* Se actualiza solo si no jugó */
						if ($rs["fix_jugado"] == 0) {
						
							$sql = "UPDATE fixture_deportes SET fix_fecha = '{$partido_fecha_unix}' WHERE fix_xml = '{$partido_xml}'";
							
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

} // End foreach partidos

echo "<br/><br/> -Actualizado:".date("d.m.Y G:i:s");
?>