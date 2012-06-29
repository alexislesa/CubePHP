<?
/* Conector */
include ("../includes/comun/conector.inc.php");

/* Si estoy editando una nota */
$nota_id = !empty($_GET["id"]) ? (is_numeric($_GET["id"]) ? $_GET["id"] : 0) : 0;
	
/* Web titulo */
$web_titulo = "Editar Noticia - ".$web_titulo;
$web_descripcion = "";

$pagina_actual = "lector-online";
$pagina_actual_2 = "noticialist";
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

/* Breadcrums */
$breds["Mi cuenta"] = "/lectores";
$breds["Editar Noticia"] = "";

/** 
 * Login de usuario
 */
$usr = new usuarios();
$usr->db = $db;
$usr->pws_format = "md5";
if (empty($_SESSION["web_site"]) || !$usr->login($_SESSION["web_site"]["user"], $_SESSION["web_site"]["pw"])) {
	Header("Location: /lectores/login.php");
	exit();
}

/* Archivo a incluir por defecto. */
$include_file 	= dirTemplate."/lectores/noticia-edit.inc.php";
$include_file_fin 	= dirTemplate."/lectores/noticia-edit-fin.inc.php";

/**
 * Agregado en caso de debug
 * Para visualizar el contenido del proceso final
 */
if (!empty($_GET["debug"]) && $_GET["debug"] == true) {
	$include_file = $include_file_fin;
}

/* Validaciones a realizar */
$checkForm = array();
$checkForm[] = "required, seccion_id, Seleccione una sección";

$checkForm[] = "required, titulo, Ingrese un titulo para la noticia";
$checkForm[] = "length=0-80, titulo, El titulo no debe superar los 80 caracteres";

$checkForm[] = "required, bajada, Ingrese una descripcion breve para la noticia";
$checkForm[] = "length=0-240, bajada, La descripción breve no debe superar los 240 caracteres";

$checkForm[] = "required, texto, Ingrese el texto de la noticia";
$checkForm[] = "length=0-4000, texto, El texto no debe superar los 4000 caracteres";

$dataToSkin = array();
if (!empty($_GET["act"])) {

	$_POST = limpia_datos($_POST);

	/* Modificación para evitar que se puedan poner [Enter] en la bajada */
	$_POST["bajada"] = trim($_POST["bajada"]);
	$_POST["bajada"] = str_replace("\n", " ", $_POST["bajada"]);
	$_POST["bajada"] = str_replace("\r", " ", $_POST["bajada"]);
	$_POST["bajada"] = str_replace("\t", " ", $_POST["bajada"]);
	$_POST["bajada"] = strip_tags($_POST["bajada"]);
	
	$usrcheck = new checkFormulario($_POST, $checkForm);

	if ($usrcheck->process()) {
	
		/**
		 * Verifico que la nota sea del usuario que la envío y que este pendiente de aprobar
		 * Sino, envio al listado de notas
		 */
		$tipo_id = 7;
		$seccion_id = 0;
		
		$gd = New notaVer($nota_id);
		$gd->db = $db;
		$gd->nota_tipo = $tipo_id;
		$gd->nota_seccion = $seccion_id;
		$gd->nota_prefix = "lectores_noticias";
		$gd->nota_force_view = true;
		$dtToSkin = $gd->process();

		$dtToSkin = $gd->process();
		$error_msj = ($gd->error_found) ? $gd->error_msj : false;
		$total_resultados = !$gd->nota_not_found;
	
		if ($gd->error_found) {
			Header("Location: /lectores/noticia-list.php");
			exit();
			echo $gd->error_msj;
		}
		
		if ($total_resultados) {
			if (!($dtToSkin[0]["noticia_user_id"] == $usr->campos["lector_id"] && $dtToSkin[0]["noticia_estado"] == 0)) {
				Header("Location: /lectores/noticia-list.php");
				exit();
			}
		} else {

			// Si la nota no existe o no es del usuario voy al listado de notas
			Header("Location: /lectores/noticia-list.php");
			exit();
		}
		
		/**
		 * Comprobación para eliminar una nota
		 */
		if (strtolower(trim($_GET["act"])) == "del") {
		
			$sql = "UPDATE lectores_noticias SET noticia_estado = 3 WHERE noticia_id = '{$nota_id}'";
			$db->query($sql);
			
			/* Si la nota no existe o no es del usuario voy al listado de notas */
			Header("Location: /lectores/noticia-list.php");
			exit();
		}

		/* Limpio informacion de los adjuntos, menos las imagenes */
		$sql = "DELETE FROM lectores_noticias_galeria_multimedia WHERE gal_tipo != 'image' AND gal_id IN (
			SELECT adjunto_multimedia_id FROM lectores_noticias_multimedia WHERE adjunto_noticia_id = '{$nota_id}'
		)";
		$db->query($sql);
		
		$sql = "DELETE FROM lectores_noticias_multimedia WHERE adjunto_noticia_id = '{$nota_id}'";
		$db->query($sql);
		
		/**
		 * Reviso si se ha enviado un video y si este es una url válida de youtube
		 */
		if ($_POST["video"] != "") {

			$video_yt = trim($_POST["video"]);
			$video_yt = str_replace("http://","",$video_yt);

			if (strpos($video_yt, "www.youtube.com") !== false) {

				$utlTmp = parse_url($_POST["video"]);
				parse_str($utlTmp["query"], $noticia_video_Tmp);
				$url_links = $noticia_video_Tmp["v"];
				if (empty($url_links) || $url_links == "" || (strlen($url_links) < 5)) {

					// "http://www.youtube.com/v/{$url_links}&hl=en&fs=1&rel=0";
					
					$ftt = str_replace("www.youtube.com/v/", "", $video_yt);
					$ftt = substr($ftt, 0, strpost($ftt, "&"));

					if ($ftt != "" && strlen($ftt) > 4) {
						$_POST["video"] = "http://www.youtube.com/watch?v=".$ftt."&feature=recentu";
					} else {
						// Error, la direccion del video ingresada es incorrecta.
						$error_msj = "Error, la dirección del video ingresado es incorrecta.";
					}
					
				} else {
				
					// http://www.youtube.com/watch?v=5riPWIhKgUM&feature=recentu
					// Cargo la url más limpia
					$_POST["video"] = "http://www.youtube.com/watch?v=".$url_links."&feature=recentu";
				}			

			} else {

				$error_msj = "Error, la dirección del video ingresado es incorrecta.";
			}
		}		
		
		if (!$error_msj) {
		
			/**
			 * Consulta si tiene objetos adjuntos y los procesa
			 */
			/* ID de Galería de imagenes adjuntas */
			$gal_adj_id = 6;
			$adj = $adjuntos_files[$gal_adj_id];
			
			$item_file = array();
			$local_file_time = time();
			
			/**
			 * ***************************************************************************************
			 * Procesa los archivos recibidos vía POST
			 * ***************************************************************************************
			 *
			 * Formato de ingreso de información:
			 * $_FILES			Envio vía POST
			 * $_POST["url"] 	Dirección URL del archivo
			 * $_POST["file"]	Dirección local del archivo (utilizado cuando se subio desde otro programa/clase)
			 *
			 * Devuelve array con la información del archivo guardado en la carpeta temporal.
			 * Formato del array devuelve: 
			 * array("file_src_pathname" => "nombre del archivo");
			 *
			 */
			 
			// Cargo la clase que maneja todos los archivos POST
			include (dirPath."/includes/clases/pfiles.class.php");
			
			$up = new pFiles(dirPath."/includes/tmp/");
			if ($item_file = $up->upload()) {
				$local_file = true;
			}
			
			/**
			 * ***************************************************************************************
			 * Levanto información caracteristica del archivo procesado
			 * ***************************************************************************************
			 *
			 * Solo funciona con archivos locales
			 *
			 * Formato de ingreso del archivo:
			 * array("file_src_pathname" => "nombre del archivo");
			 *
			 * Formato de salida:
			 * array
			 * 	file_src_name
			 * 	file_src_name_body
			 * 	file_src_name_ext
			 * 	file_src_pathname
			 * 	file_src_mime
			 * 	file_src_size
			 * 	file_src_size_format
			 * 	file_is_image
			 * 	file_is_audio
			 * 	file_is_video
			 * 	file_is_swf
			 * 	file_src_x
			 * 	file_src_y
			 * 	file_src_duracion
			 * 	file_src_duracion_format
			 * 	file_src_audio_rate
			 * 	file_src_video_rate
			 * 	file_src_title
			 *
			 */
			
			// Cargo la clase que maneja los archivos
			include (dirPath."/includes/clases/checkfiles.class.php");
			
			if (count($item_file)>0) {
			
				foreach ($item_file as $k => $v) {
				
					// Proceso el archivo y devuelvo toda la info disponible y si esta dentro de lo que se pide
					$gd = new checkFile($v["file_src_pathname"]);
					$gd->data = $adj;
					if ($data = $gd->process()) {
						
						// Guardo el resto de la información recolectada en el mismo array
						$item_file[$k] = $data;
					} else {
						// Si hay error, cancelo la carga de este archivo y continúo con los otros
						@unlink($v["file_src_pathname"]);
						unset($item_file[$k]);
						
						$error_found = true;
						$error_msj = $gd->error_msj;
					}
				}
			}
			
			/**
			 * ***************************************************************************************
			 * Proceso los archivos que se cargaron correctamente
			 * ***************************************************************************************
			 *
			 * Le cargo el nombre nuevo que debería tener, y si es imagen, genero todos los tamaños
			 * A la estructura actual del array de datos, le agrego la siguiente estructura:
			 * array
			 *	file_new_name_body
			 *	file_dst_name [nombre del archivo tal como se va a guardar en la base de datos]
			 *	files
			 *		local_file_pathname
			 *		remoto_file_path
			 *		remoto_file_name
			 *
			 */
			
			/* Si no hay ningún error, continúo */
			if (!$error_found) {
			
				// Procesa los archivos e imagenes
				include (dirPath."/includes/clases/class.upload.php");
				 
				// Calculo el path base donde se van a guardar los archivos.
				$pbase = "";
				if ($adj["path"] != "") {
					$pbase = $adj["path"];
					
					$m = array("d", "j", "N", "w", "z", "W", "m", "n", "Y", "y", "g", "G", "h", "H","i", "s", "U");
					foreach ($m as $fId => $fType) {
						$pbase = str_replace("%{$fType}", date($fType), $pbase);
					}
				}

				if (isset($item_file) && count($item_file)) { 
				
					foreach ($item_file as $k => $v) {
						
						$item_file[$k]["file_new_name_body"] = $local_file_time;
						
						$gd = new upload($v["file_src_pathname"]);
						$gd->process();
						if (!$gd->uploaded) {
							$error_found = true;
							$error_msj = "El archivo no se subió";
						}

						// Cambio el nombre y cargo que no se pueda sobreescribir si subo mas de uno a la vez
						$gd->file_new_name_body = $item_file[$k]["file_new_name_body"];
						$gd->file_auto_rename = true;

						// Flag que me indica que todas las imagenes se generaron correctamente
						$file_ok = true;
						
						// Parametros si el archivo es una imagen y en el panel estaba subiendo una imagen
						if ($v["file_is_image"] && $adj["is_image"]) {

							$img_datos = explode("|", trim($adj["imagen"]));
							$img_datos = array_map("trim", $img_datos);

							// name(o:original, t:thumb), ancho, alto, folder, prefix, sufix, resize mode (extact, best, crop)
							foreach($img_datos as $j=> $m) {
								if ($file_ok) {

									$mf = explode(",", $m);

									$img_name = "";
									$img_folder = "";
									$img_prefix = "";
									$img_sufix = "";
									
									list($img_name,$img_x,$img_y,$img_folder,$img_prefix,$img_sufix,$img_crop) = array_pad($mf,7,"");
									unset($mf);
									
									if (!empty($adj["convert"])) {
										$gd->image_convert = $adj["convert"];
									}
									$gd->file_new_name_body = $item_file[$k]["file_new_name_body"];
									$gd->file_name_body_add = $img_sufix;
									$gd->file_name_body_pre = $img_prefix;
									
									// Redimensiono solo cuando es diferente
									if (($img_x || $img_y) && ($img_x != $v["file_src_x"] || $img_y != $v["file_src_y"])) {
										
										$in_crop = 0; // utilizado para comprobar si hace crop

										/* Utilizado cuando se utiliza de la siguiente forma. Ej. 480x0 */									
										$img_x = is_numeric($img_x) ? $img_x : 0;
										$img_y = is_numeric($img_y) ? $img_y : 0;
										if (($img_y == 0 || $img_x == 0) && $img_crop == "exact") {
											$img_y = ($img_y) ? $img_y : round(($img_x * $v["file_src_y"] / $v["file_src_x"]), 0);
											$img_x = ($img_x) ? $img_x : round(($img_y * $v["file_src_x"] / $v["file_src_y"]), 0);
											$in_crop = true;
										}									
										
										if (is_numeric($img_x) && ($img_x > 1) && ($img_x != $v["file_src_x"])) {
											$gd->image_x = $img_x;
											$in_crop++;
										}
										
										if (is_numeric($img_y) && ($img_y > 1) && ($img_y != $v["file_src_y"])) {
											$gd->image_y = $img_y;
											$in_crop++;
										}
										
										// No realiza crop si el sistema no debe redimensionar
										if ($in_crop) {
										
											$gd->image_resize = true;
										
											switch($img_crop) {
												case "exact":
												default:
													$gd->image_ratio = false;
												break;
												
												case "best":
													$gd->image_ratio = true;
												break;
												
												case "crop":
													$gd->image_ratio_crop = true;
												break;
											}
										}
									}
									
									$gd->process(dirPath."/includes/tmp/");
									if ($gd->processed) {
										$item_file[$k]["file_dst_name"] = $item_file[$k]["file_new_name_body"].".".$gd->file_dst_name_ext;
										$item_file[$k]["files"][$j]["local_file_pathname"] = $gd->file_dst_pathname;
										$item_file[$k]["files"][$j]["remoto_file_path"] = $pbase;
										$item_file[$k]["files"][$j]["remoto_file_name"] = $gd->file_dst_name;
										
										// Cargo la información extra del archivo 
										// con la información de la version O (original o tamaño más grande)
										if ($img_name == "o") {
											$item_file[$k]["file_src_x"] = $gd->image_dst_x;
											$item_file[$k]["file_src_y"] = $gd->image_dst_y;
											$item_file[$k]["file_src_size"] = filesize($gd->file_dst_pathname);
											
											// Cargo el peso en formato humano (ej: 2.34 MB) con dos decimales
											$precision = 2;
											$units = array('B', 'KB', 'MB', 'GB', 'TB');
											
											$bytes = $item_file[$k]["file_src_size"];
											$pow = floor(($item_file[$k]["file_src_size"] ? log($item_file[$k]["file_src_size"]) : 0) / log(1024));
											$pow = min($pow, count($units) - 1);
											$bytes /= pow(1024, $pow);
											$item_file[$k]["file_src_size_format"] = round($bytes, $precision)." ".$units[$pow];
										}
										
									} else {
										$file_ok = false;
										$error_found = true;
										$error_msj = "Error al generar los thumnails del archivo";
									}
									
								} // end file ok
							} // foreach image
							
						} else {
						
							$gd->process(dirPath."/includes/tmp/");
							if ($gd->processed) {
								// Utilizado al guardar en la dbase
								$item_file[$k]["file_dst_name"] = $gd->file_dst_name;	
								
								$item_file[$k]["files"][0]["local_file_pathname"] = $gd->file_dst_pathname;
								$item_file[$k]["files"][0]["remoto_file_path"] = $pbase;
								$item_file[$k]["files"][0]["remoto_file_name"] = $gd->file_dst_name;
							} else {
								$file_ok = false;
								$error_found = true;
								$error_msj = "Error al procesar el archivo<br/>";
							}
						}
							
						// Elimino el archivo fuente
						$gd->clean();
						
						// si hubo error elimino las imagenes generadas / archivos y quito del array de archivos
						if (!$file_ok && isset($item_file[$k]["files"])) {
							foreach ($item_file[$k]["files"] as $j=>$m) {
								@unlink($m["local_file_pathname"]);
							}
							unset($item_file[$k]);
						}
					}
				} // End if isset $item_file
			
			} // End if error_found;

			/**
			 * ***************************************************************************************
			 * Proceso el array de archivos correctos y los subo al servidor definitivo
			 * ***************************************************************************************
			 *
			 * Variable requeridas para el procesamiento:
			 * files
			 *	local_file_pathname
			 * 	remoto_file_path
			 */
			if (!$error_found) {
			
				// Path donde se encuentra la abstracción de FileSystem
				$fspath = dirPath."/includes/clases/".$adj["metodo"]."_index.php";
				
				if (!file_exists($fspath)) {
					$error_found = true;
					$error_msj = "Error al cargar las funciones de repositorio remoto";
					unset($item_file);
				} else {
					include($fspath);
				}
				
				$flt = new fs($adj["save_data"]);
				if (!$fl = $flt->conect()) {
					// Como no se pudo conectar, elimino todas las entradas de archivos
					$error_found = true;
					$error_msj =  $fl->error_msj;
					unset($item_file);
				}

				if (isset($item_file) && count($item_file)) {
					foreach ($item_file as $k => $v) {
						$file_ok = true;
						
						if (isset($v["files"])) {
							foreach ($v["files"] as $j=>$m) {
							
								if (!$fl->file_exists($adj["save_data"]["path"].$m["remoto_file_path"])) {
									if (!$fl->mmkdir($adj["save_data"]["path"].$m["remoto_file_path"])) {
										$file_ok = false;
									}
								}

								$fl->chdir($adj["save_data"]["path"].$m["remoto_file_path"]);
								if(FALSE == $fl->put($m["local_file_pathname"], $m["remoto_file_name"])) {
									$fl->quit();
									
									$file_ok = false;
									$error_found = true;
									$error_msj = "Error al leer información de la carpeta del repositorio remoto";
								}
							}

							// Elimino los archivos locales temporales
							foreach ($v["files"] as $j=>$m) {
								@unlink($m["local_file_pathname"]);
							}
						}
						
						// Si hubo error, elimino los archivos locales
						if (!$file_ok) {
							unset($item_file[$k]);
						}
					}
				}
			} // End if error_found;

			/**
			 * ***************************************************************************************
			 * Procesa los links recibidos vía POST
			 * ***************************************************************************************
			 *
			 * Formato de ingreso de información:
			 * $_POST["link"] 	Dirección URL del link (youtube,link, etc)
			 *
			 * Devuelve array con la información del link
			 * Formato del array devuelve: 
			 * array("file_src_pathname" => "nombre del archivo");
			 */
			if (!empty($_POST["link"])) {
				$_POST["link"] = trim($_POST["link"]);
				if ($_POST["link"] != "") {
					$item_file[]["file_dst_name"] = $_POST["link"];
				}
			}

			/**
			 * ***************************************************************************************
			 * Proceso todo el resto de información necesaria para guardar en la base de datos
			 * ***************************************************************************************
			 *
			 * Utiliza la información de las características del archivo 
			 * y el resto de información enviada vía POST
			 * 
			 * Guardo toda esta información en la base de datos
			 */
			if (!$error_found) {

				if (isset($item_file) && count($item_file)){
					foreach ($item_file as $k => $v) {
						$item_file[$k] = array_merge($v, $_POST);
						
						// Cargo algunas variables extras del archivo
						$item_file[$k]["extra"]["size"] = !empty($v["file_src_size"]) ? $v["file_src_size"] : "";

						// if Image / Video
						$item_file[$k]["extra"]["width"] = !empty($v["file_src_x"]) ? $v["file_src_x"] : "";
						$item_file[$k]["extra"]["height"] = !empty($v["file_src_y"]) ? $v["file_src_y"] : "";

						// if Video
						$item_file[$k]["extra"]["video_rate"] = !empty($v["file_src_video_rate"]) ? $v["file_src_video_rate"] : "";
						
						// if Audio / Video
						$item_file[$k]["extra"]["duracion"] = !empty($v["file_src_duracion"]) ? $v["file_src_duracion"] : "";
						$item_file[$k]["extra"]["audio_rate"] = !empty($v["file_src_audio_rate"]) ? $v["file_src_audio_rate"] : "";
					}
				}
				
				// si no hay error guardo la información de los adjuntos
				if (count($item_file) && !empty($item_file[0]["file_dst_name"])) {
					$hoy = time();
					$ft_extra = json_encode($item_file[0]["extra"]);
					
					$sql = "REPLACE INTO lectores_noticias_galeria_multimedia (
					gal_file,
					gal_nombre,
					gal_descripcion,
					gal_comentario,
					gal_user_id,
					gal_fecha,
					gal_tipo,
					gal_galeria, 
					gal_extra
					) VALUES (
					'{$item_file[0]["file_dst_name"]}',
					'{$item_file[0]["file_src_name"]}',
					'',
					'',
					'{$usr->campos["lector_id"]}',
					'{$hoy}',
					'image',
					'{$gal_adj_id}',
					'{$ft_extra}'
					)";

					if ($res = $db->query($sql)) {
						$ft_id = $db->last_insert_id();
					
						// Genero el campo para cargar las imagenes
						$_POST["adjuntos"]["imagen"] = "img[]={$ft_id}";
						$_POST["img_type"] = 2;	// 1: noticias, 2: adjuntos,...
						$_POST["img_desc_{$ft_id}"] = $_POST["foto_desc"];
					}
				}
				
				// Consulto si tengo videos a subir
				if (!empty($_POST["video"]) && $_POST["video"] != "") {
					$hoy = time();
					
					$sql = "REPLACE INTO lectores_noticias_galeria_multimedia (
					gal_file,
					gal_nombre,
					gal_descripcion,
					gal_comentario,
					gal_user_id,
					gal_fecha,
					gal_tipo,
					gal_galeria, 
					gal_extra
					) VALUES (
					'{$_POST["video"]}',
					'Video',
					'',
					'',
					'{$usr->campos["lector_id"]}',
					'{$hoy}',
					'ytube',
					'9',
					''
					)";

					if ($res = $db->query($sql)) {
						$ft_id = $db->last_insert_id();
					
						// Genero el campo para cargar las imagenes
						$_POST["adjuntos"]["videos"] = "videos[]={$ft_id}";
						$_POST["videos_type"] = 2;
					}
				}
				
				/* Si no modifique la imagen, cargo esto */
				if (!empty($_POST["imgold"]) && is_numeric($_POST["imgold"])) {
				
					$_POST["adjuntos"]["imagen"] = "img[]={$_POST["imgold"]}";
					$_POST["img_type"] = 2;	// 1: noticias, 2: adjuntos,...
					$_POST["img_desc_{$_POST["imgold"]}"] = $_POST["foto_desc"];
				}
				
				/**
				 */
				// Extra, le cargo el estado desactivado apra evitar que eta nota sea visible sin aprobación
				$_POST["estado"] = 0;
				$_POST["tipo"] = 7;
				
				$nota = new lectornoticias();
				$nota->db = $db;
				$nota->ss_config = $ss_config;
				$nota->user_id = $usr->campos["lector_id"];
				$nota->adj = $adjuntos_files;
				
				if (!$nota->save($_POST)) {
					$error_found = true;
					$error_msj = $nota->error_msj;
				} else {
				
					/* Modificación para que el sistema me muestre el listado en vez de la página final */
					Header("Location: /lectores/noticia-list.php");
					exit();
				
				}				
		
			}
		
		} // End if error.
		
	} else {
	
		// Devuelvo el mensaje de error detectado
		$error_msj = $usrcheck->error_msj;
	}
	
	$dataToSkin = $usrcheck->campos;
	
} else {

	$tipo_id = 7;
	$seccion_id = 0;
	
	$gd = New notaVer($nota_id);
	$gd->db = $db;
	$gd->nota_tipo = $tipo_id;
	$gd->nota_seccion = $seccion_id;
	$gd->nota_objetos_datos = $adjuntos_files;
	$gd->nota_prefix = "lectores_noticias";
	$gd->nota_force_view = true;
	$dtToSkin = $gd->process();
	
	$error_msj = ($gd->error_found) ? $gd->error_msj : false;
	$total_resultados = !$gd->nota_not_found;
	
	if ($total_resultados) {
		if ($dtToSkin[0]["noticia_user_id"] == $usr->campos["lector_id"] && $dtToSkin[0]["noticia_estado"] == 0) {
			$dataToSkin["seccion_id"] = $dtToSkin[0]["noticia_seccion_id"];
			$dataToSkin["id"] = $dtToSkin[0]["noticia_id"];
			$dataToSkin["titulo"] = $dtToSkin[0]["noticia_titulo"];
			$dataToSkin["bajada"] = $dtToSkin[0]["noticia_bajada"];
			$dataToSkin["texto"] = $dtToSkin[0]["noticia_texto"];
			$dataToSkin["video"] = !empty($dtToSkin[0]["videos"][1]["gal_file"]) ? $dtToSkin[0]["videos"][1]["gal_file"] : "";
			
			$dataToSkin["foto_desc"] = "";
			if (!empty($dtToSkin[0]["imagen"][1])) {
				$dataToSkin["imagen"] = $dtToSkin[0]["imagen"][1]["url"]["t"];
				$dataToSkin["foto_desc"] = $dtToSkin[0]["imagen"][1]["adjunto_descripcion"];
			}
			
			$dataToSkin["map"]["x"] = !empty($dtToSkin[0]["gmap"][1]["mapa_x"]) ? $dtToSkin[0]["gmap"][1]["mapa_x"] : 0;
			$dataToSkin["map"]["y"] = !empty($dtToSkin[0]["gmap"][1]["mapa_y"]) ? $dtToSkin[0]["gmap"][1]["mapa_y"] : 0;
			$dataToSkin["map"]["zoom"] = !empty($dtToSkin[0]["gmap"][1]["mapa_zoom"]) ? $dtToSkin[0]["gmap"][1]["mapa_zoom"] : 11;
			$dataToSkin["map"]["tipo"] = !empty($dtToSkin[0]["gmap"][1]["mapa_tipo"]) ? $dtToSkin[0]["gmap"][1]["mapa_tipo"] : "satellite";
			
		} else {
			Header("Location: /lectores/noticia-list.php");
			exit();
		}
	} else {

		Header("Location: /lectores/noticia-list.php");
		exit();
	}
}
?>

<? include (dirPath."/includes/comun/header.inc.php");?>

<? include (dirPath."/includes/comun/top.inc.php");?>

<?php include (dirPath."/includes/comun/menu.inc.php");?>

<? 
$pathRootCommon = dirPath."/includes/comun/";
$pathRootTemplate = dirTemplate."/";
$path_relativo = "lectores";
$fileIncludeCuerpo = $include_file;
$fileIncludeBarra = $pathRootTemplate.$path_relativo."/barra.inc.php";
include ($pathRootTemplate."herramientas/base.inc.php");
?>

<? include (dirPath."/includes/comun/pie.inc.php");?>

<? include (dirPath."/includes/comun/bottom.inc.php");?>