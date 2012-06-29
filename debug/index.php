<?php
/* Conector */
include ('../includes/comun/conector.inc.php');
?>
<html>
<head>
<title>Herramientas de debug del sitio</title>

<style>
	body {margin:0px; padding:20px; font-family:arial; font-size:12px; line-height:140%; color#111; background:#EEE;}
	.body {border:solid 1px #CCC; padding:20px; background:#FFF; }
	
	td {padding:6px; background:#EEE; font-size:11px;}
		td a {color:#000; text-decoration:none;}
		td a:hover {color:#666; text-decoration:underline;}

	.bug_ok td {background:#CCC; color:#666; text-decoration:line-through;}	
	th {padding:6px; background:#DDD; font-size:11px; color:#333;}

</style>

</head>

<body>
	<div class="body">
		<h1>Herramientas de debug:</h1>
		
		<?php 
		$mode = !empty($_GET["mode"]) ? $_GET["mode"] : "";
		$act = !empty($_GET["act"]) ? $_GET["act"] : "";
		$id = !empty($_GET["id"]) ? $_GET["id"] : "";
		
		switch($mode) {

			/**
			 * *******************************************************************
			 * Home: Página inicial de la herramienta
			 * *******************************************************************
			 */
			default:
			case "": ?>
			
				<fieldset>
					<legend ><h2>Páginas de error</h2></legend>
					<a href="/error_docs" target="_blank">Acceso a listado de todas las páginas de error del servidor</a><br/><br/>
				</fieldset>

				<fieldset>
					<legend ><h2>Bug Tracker</h2></legend>
					<a href="?mode=bugtracker">Bug Tracker</a> <br/><br/>
				</fieldset>
				
				<fieldset>
					<legend ><h2>Formulario de contactos</h2></legend>
					<a href="/institucional/contacto.php?nojs=true" target="_blank">Ingreso al formulario de contacto sin JavaScript</a> <br/><br/>
					<a href="?mode=contact_err">Listado de los mensajes de error del formulario de contacto</a> <br/><br/>	
				</fieldset>

				<fieldset>
					<legend ><h2>Registro de usuarios</h2></legend>
					<a href="/lectores/registro.php?nojs=true" target="_blank">Ingreso al formulario de registro de usuarios sin JavaScript</a> <br/><br/>
					<a href="?mode=registro_err">Listado de los mensajes de error del formulario de registro</a> <br/><br/>	
				</fieldset>				

				<fieldset>
					<legend ><h2>Otros test</h2></legend>
					- Acceso a probar errores en las notas (listado sin noticias, interior errado) <br/><br/>
					- Acceso a probar XSS en buscador <br/><br/>
					- Acceso a probar tags sin tag <br/><br/>
				</fieldset>
				
				<?php
			break;
			
			/**
			 * *******************************************************************
			 * Contact Err: Listado de mensajes de error del formulario
			 * *******************************************************************
			 */
			case "contact_err":
				$fileName = dirPath."/institucional/contacto.php";
				$contenido = file_get_contents($fileName);
				?>

				<a href="?">Volver</a> <br/><br/>
			
				<fieldset>
					<legend ><h2>Mensajes de alerta / error en formulario de contactos</h2></legend>

					<ul>
					<?php 
					preg_match_all( '/checkForm\[\] = "([^"]*)"/i', $contenido, $match);
					if (!empty($match[1])) {
						foreach ($match[1] as $k => $v) {
							$cont_msj = explode(",", $v);
							$cont_msj_err = $cont_msj[count($cont_msj)-1];
							?>
							
							<li><?php echo $cont_msj_err;?> </li>
						
						<?php } ?>
					<?php } ?>
					
					</ul>
				</fieldset>
				
				<?php
			break;
			
			/**
			 * *******************************************************************
			 * Registro Err: Listado de mensajes de error del formulario de registro
			 * *******************************************************************
			 */
			case "registro_err":
				$fileName = dirPath.'/lectores/registro.php';
				$contenido = file_get_contents($fileName);
				?>

				<a href="?">Volver</a> <br/><br/>
			
				<fieldset>
					<legend ><h2>Mensajes de alerta / error en formulario de registro</h2></legend>

					<ul>
					<?php 
					preg_match_all( '/checkForm\[\] = "([^"]*)"/i', $contenido, $match);
					if (!empty($match[1])) {
						foreach ($match[1] as $k => $v) {
							$cont_msj = explode(",", $v);
							$cont_msj_err = $cont_msj[count($cont_msj)-1];
							?>
							
							<li><?php echo $cont_msj_err;?> </li>
						
						<?php } ?>
					<?php } ?>
					
					</ul>
				</fieldset>
				
				<?php
			break;			
			
			/**
			 * *******************************************************************
			 * Bug Tracker: Listado de errores ingresados desde el formulario base
			 * *******************************************************************
			 */
			case "bugtracker":
				$fileName = substr(__FILE__, 0, strlen(__FILE__) - strlen('debug/index.php'));
				$fileName.= 'includes/tmp/log-data.inc.php';

				$data = array();
				if (file_exists($fileName)) {
					include($fileName);
				}
				
				if ($act == "del" && $id != "") {
				
					list($act_url, $act_fecha) = explode("__|__", base64_decode($id));
				
					$data[$act_url][$act_fecha]["marca"] = time();

					/* Guardo la información actualizada */
					$contenido = "<?php\n";
					$contenido.= "/**\n";
					$contenido.= " * Error debug \n";
					$contenido.= " */\n\n";
					$contenido.= '$data = '. var_export($data, true) .';';
					$contenido.= "\n\n ?>";

					file_put_contents($fileName, $contenido);
				}

				?>
			
				<a href="?mode=bugtracker&act=view_all">Ver todos los items</a> <br/><br/>

				<a href="?">Volver</a> <br/><br/>
			
				<table>
					<thead>
					<th>&nbsp;</th>
					<th>URL</th>
					<th>Fecha</th>
					<th>Navegador</th>
					<th>Observación</th>
					</thead>
					
					<?php foreach ($data as $bug_url => $bug_arr) { 
						
						foreach ($bug_arr as $bug_fecha => $bug_data) {
						
							if (empty($bug_data["marca"]) || $act=="view_all") {
						
								$bug_fecha_err = (date("dm") == date("dm", $bug_fecha)) ? date("G:i:s",$bug_fecha) : date("d/m G:i:s",$bug_fecha);

								$bug_nav = css_browser_selector($bug_data["navegador"]);
								
								$clase_marca = !empty($bug_data["marca"]) ? "bug_ok" : "";
								?>
							
								<tr class="<?php echo $clase_marca;?>">
								<td><input type='checkbox' name='bugt[]' value="" onclick="quitarbug('<?php echo base64_encode($bug_url."__|__".$bug_fecha);?>');"/></td>
								<td><a href="<?php echo $bug_url;?>" target="_blank" title="Ir a: <?php echo $bug_url;?>"><?php echo $bug_url;?></a></td>
								<td><?php echo $bug_fecha_err;?> Hs</td>
								<td><?php echo $bug_nav;?></td>
								<td><?php echo $bug_data["error"];?></td>
								</tr>

							<?php }
						} // array
					} ?>
				</table>

				<?php
			break;
		}



		function css_browser_selector($ua=null) {
			$ua = ($ua) ? strtolower($ua) : strtolower($_SERVER['HTTP_USER_AGENT']);

			$g = 'Gecko';
			$w = 'Webkit';
			$s = 'Safari';
			$b = array();

			// browser
			if(!preg_match('/opera|webtv/i', $ua) && preg_match('/msie\s(\d)/', $ua, $array)) {
				$b[] = 'Internet Explorer ' . $array[1];
			}	else if(strstr($ua, 'firefox/2')) {
				$b[] = 'Firefox 2';		
			}	else if(strstr($ua, 'firefox/3.5')) {
				$b[] = 'Firefox 3.5';
			}	else if(strstr($ua, 'firefox/3')) {
				$b[] = 'Firefox 3';
			}	else if(strstr($ua, 'firefox/')) {
				$b[] = 'Firefox '.substr($ua, strpos($ua, 'firefox/') + strlen('firefox/'),1);		
			} else if(strstr($ua, 'gecko/')) {
				$b[] = $g;
			} else if(preg_match('/opera(\s|\/)(\d+)/', $ua, $array)) {
				$b[] = 'Opera ' . $array[2];
			} else if(strstr($ua, 'konqueror')) {
				$b[] = 'Konqueror';
			} else if(strstr($ua, 'chrome')) {
				$b[] = $w . ' ' . $s . ' Chrome';
			} else if(strstr($ua, 'iron')) {
				$b[] = $w . ' ' . $s . ' Iron';
			} else if(strstr($ua, 'applewebkit/')) {
				$b[] = (preg_match('/version\/(\d+)/i', $ua, $array)) ? $w . ' ' . $s . ' ' . $s . $array[1] : $w . ' ' . $s;
			} else if(strstr($ua, 'mozilla/')) {
				$b[] = $g;
			}

			// platform				
			if(strstr($ua, 'j2me')) {
				$b[] = 'mobile';
			} else if(strstr($ua, 'iphone')) {
				$b[] = 'iPhone';		
			} else if(strstr($ua, 'ipod')) {
				$b[] = 'iPod';		
			} else if(strstr($ua, 'mac')) {
				$b[] = 'Mac';		
			} else if(strstr($ua, 'darwin')) {
				$b[] = 'Mac';		
			} else if(strstr($ua, 'webtv')) {
				$b[] = 'WebTv';		
			} else if(strstr($ua, 'win')) {
				$b[] = 'Win';		
			} else if(strstr($ua, 'freebsd')) {
				$b[] = 'freeBsd';		
			} else if(strstr($ua, 'x11') || strstr($ua, 'linux')) {
				$b[] = 'Linux';		
			}

			return join(' / ', $b);
		}
		?>

	</div>

	<script type="text/javascript">
	function quitarbug(url) {
		texto = "¿Desea marcar este item y quitarlo de la lista actual?";
		if (confirm(texto)==false) {return false;}
		document.location.href='index.php?mode=bugtracker&act=del&id='+url;
	}
	</script>
</body>
</html>