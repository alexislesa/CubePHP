<?php
/* Conector */
include ("../includes/comun/conector.inc.php");

// Consulta si esta logeado
include (dirTemplate.'/lectores/check-login.inc.php');

$pathRelative = 'lectores';
$incBody = dirTemplate.'/'.$pathRelative.'/clasificado-add-2.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = 'lectores';
$pagActualClass[] = 'lector-online';
$pagActualClass[] = 'clasificado';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Mi cuenta'] = '/lectores';
$breds['Nuevo Clasificado'] = '';

$webTitulo = 'Nuevo Clasificado - '.$webTitulo;

// Información de configuración de la página
$catEstadosId = 139;
$catMediosPagoId = 142;
$catFormasEnvioId = 142;
$datosFile = $adjuntos_files[1];	// Información sobre los tamaños de las imagenes
$saveData = $datosFile['save_data'];	// Información del FTP de las imagenes

// Consulto si realmente debe estar en esta página, sino retorno al inicio
if (empty($_SESSION['clasificado-add']) || $_SESSION['clasificado-add']['paso_actual'] < 2) {
	Header("Location: clasificado-add.php");
	exit();
}

// Recupero información de la página anterior
$clasToSkin = $_SESSION['clasificado-add'];

// Recupero información sobre las categorías
$catDB = New ClasificadosCategoria();
$catDB->db = $db;
// $catDB->cache = $Cache;
$catArr = $catDB->process();
$catInf = $catDB->g($clasToSkin['catid']);

$catInf1 = $catDB->g($clasToSkin['categoria']);
$catInf2 = $catDB->g($clasToSkin['subcat1']);
$catInf3 = $catDB->g($clasToSkin['subcat2']);
$catInf4 = $catDB->g($clasToSkin['subcat3']);

// Recupero información sobre las operaciones permitidas
$catOp = New ClasificadosOperaciones();
$catOp->db = $db;
$opArr = $catOp->process();

$opInf = json_decode($catInf['categoria_operaciones']);
$opTmp = implode(',',$opInf);
$opArrData = $catOp->g($opTmp);

// Listas
$catLista = New ClasificadosListas();
$catLista->db = $db;

// Listado de Estados
$catEstados = $catLista->process($catEstadosId);

// Listado de Formas de pago
$catMediosPago = $catLista->process($catMediosPagoId);

// Listado de Formas de envío
$catFormasEnvio = $catLista->process($catFormasEnvioId);

// Monedas
$catMon = New ClasificadosMonedas();
$catMon->db = $db;
$catMonedas = $catMon->process();

/* Validaciones a realizar */
$checkForm = array();
$checkForm[] = "required, titulo, Ingrese el titulo";
$checkForm[] = "length=0-60, titulo, El titulo ingresado es demasiado largo";

$checkForm[] = "required, texto, Ingrese el texto";
$checkForm[] = "length=0-3000, texto, El texto ingresado es demasiado largo.";

if ($catInf['categoria_usa_venta'] && $catInf['categoria_usa_subasta']) {
	$checkForm[] = "required, tipo, seleccione el tipo de publicación (venta/subasta)";
	$checkForm[] = "digits_only, tipo, Error al seleccionar el tipo de publicación. Intente nuevamente";
}

$checkForm[] = "required, operacion, Seleccione el tipo de operación";

if ($catInf['categoria_usa_estado']) {
	$checkForm[] = "required, estado, Seleccione el estado del artículo";
	$checkForm[] = "digits_only, estado, Error al seleccionar el estado del artículo. Intente nuevamente";
}

if ($catInf['categoria_usa_precio']) {
	$checkForm[] = "required, moneda, Seleccione el tipo de moneda (seleccione PESO si no desea ingresar precio)";
	$checkForm[] = "digits_only, moneda, Error al seleccionar el tipo de moneda. Intente nuevamente";
	$checkForm[] = "if:precio1!=,required, moneda, Seleccione el tipo de moneda";
	$checkForm[] = "digits_only, precio1, Ingrese solo números para el precio";
	$checkForm[] = "if:precio1!=,required, precio2, Ingrese los decimales (0 para números sin decimal)";
	$checkForm[] = "if:precio2!=,required, precio1, Ingrese el valor de venta";
	$checkForm[] = "digits_only, precio2, Ingrese solo números para los decimales del precio";
}

// Consulta si muestra cantidad
if ($catInf['categoria_usa_cantidad']) {
	$checkForm[] = "required, cantidad, Seleccione la cantidad de artículos disponibles";
	$checkForm[] = "digits_only, cantidad, Error al seleccionar la cantidad de artículos. Intente nuevamente";
}

if ($catInf['categoria_usa_ubicacion']) {
	$checkForm[] = "required, pais, Seleccione su país";
	$checkForm[] = "digits_only, pais, Error al seleccionar el país. Intente nuevamente";
	$checkForm[] = "if:pais=12, required, provincia, Seleccione su provincia";
	$checkForm[] = "if:provincia!=795, required, departamento, Seleccioná tu departamento";
	$checkForm[] = "if:provincia!=795, required, localidad, Seleccione su localidad";
}

if ($catInf['categoria_usa_contacto']) {
	$checkForm[] = "required, contacto_1, Ingrese un nombre para el contacto";
}

$dataToSkin = array();
if (!empty($_GET['act'])) {

	$_POST = cleanArray($_POST);
	
	$usrCheck = new checkFormulario($_POST, $checkForm);

	// Seguridad para prevenir XSS en form.
	$usrCheck->tokenName = 'token';
	$usrCheck->tokenValue = !empty($_SESSION['token']) ? $_SESSION['token'] : '';
	unset($_SESSION['token']);

	if ($usrCheck->process()) {

		// Procesa las imagenes
		if (!empty($_FILES)) {
		
			// Cargo la clase que voy a utilizar
			include (dirPath.'/includes/clases/class.upload.php');

			$fl = 0;
			$fileUpToFTP = array();

			$pbase = '';
			if ($datosFile['path'] != '') {
				$pbase = $datosFile['path'];

				$pFecha = (!empty($_POST['fecha']) && is_numeric($_POST['fecha'])) ? $_POST['fecha'] : time();
				
				$m = array('d', 'j', 'N', 'w', 'z', 'W', 'm', 'n', 'Y', 'y', 'g', 'G', 'h', 'H', 'i', 's', 'U');
				foreach ($m as $fId => $fType) {
					$pbase = str_replace("%{$fType}", date($fType, $pFecha), $pbase);
				}
			}		

			foreach ($_FILES as $k => $file) {

				$fileTmpData = array();

				// Consulta si el archivo subio bien (consulta por el nombre)
				if ($_FILES[$k]['name'] == '') {
				
					// $msjError = 'Error al intentar adjuntar imagenes. Error #1. Intente nuevamente.<pre>'.print_r($_FILES, true).'</pre>';
					// break;
				} else {
				
					$fl++;
					$handle = new upload($_FILES[$k]);
					if (!$handle->uploaded) {
					
						$msjError = 'Error al intentar adjuntar imagenes. Error #2. Intente nuevamente.';
						break;
					}

					if (!$handle->file_is_image) {
					
						$msjError = 'El archivo no es una imagen del tipo GIF, JPG, PNG ';
						$msjError.= 'o el archivo se encuentra dañado.<br />';
						$msjError.= 'Intente nuevamente con otra imagen.';
						break;
					}

					// Genero el nombre de la foto y la cargo en el array final
					$nameBaseTime = time();
					$nameBase = $nameBaseTime.'_'.$_SESSION['web_site']['id'].'_'.$fl;

					// Si es imagen continúo por este lado.
					if ($datosFile['is_image']) {
						
						$imgDatos = explode('|', trim($datosFile['imagen']));
						$imgDatos = array_map("trim", $imgDatos);

						foreach ($imgDatos as $imgNro => $imgV) {
						
							$imgTmpName = '';
							$imgTmpFolder = '';
							$imgTmpPrefix = '';
							$imgTmpSufix = '';

							$mf = explode(',', $imgV);
							list($imgTmpName,$imgTmpX,$imgTmpY,$imgTmpFolder,$imgTmpPrefix,$imgTmpSufix,$imgTmpCrop) = array_pad($mf,7,'');
							unset($mf);

							if (!empty($datosFile['convert'])) {
								$handle->image_convert = $datosFile['convert'];
							}
							$handle->file_new_name_body = $nameBase;
							$handle->file_name_body_add = $imgTmpSufix;
							$handle->file_name_body_pre = $imgTmpPrefix;

							$handle->file_overwrite = true;
							$handle->file_auto_rename = false;							

							// Redimensiono solo cuando es diferente
							if (($imgTmpX || $imgTmpY) && ($imgTmpX != $handle->image_src_x || $imgTmpY != $handle->image_src_y)) {
								
								$inCrop = false; // Para comprobar si hace crop

								// Utilizado cuando se utiliza de la forma. 480x0
								$imgTmpX = is_numeric($imgTmpX) ? $imgTmpX : 0;
								$imgTmpY = is_numeric($imgTmpY) ? $imgTmpY : 0;

								if (($imgTmpY == 0 || $imgTmpX == 0) && $imgTmpCrop == 'exact') {
									$imgTmpY = ($imgTmpY) ? $imgTmpY : round(($imgTmpX * $handle->image_src_y / $handle->image_src_x), 0);
									$imgTmpX = ($imgTmpX) ? $imgTmpX : round(($imgTmpY * $handle->image_src_x / $handle->image_src_y), 0);
									$inCrop = true;
								}
								
								if (is_numeric($imgTmpX) && ($imgTmpX > 1) && ($imgTmpX != $handle->image_src_x)) {
									$handle->image_x = $imgTmpX;
									$inCrop = true;
								}
								
								if (is_numeric($imgTmpY) && ($imgTmpY > 1) && ($imgTmpY != $handle->image_src_y)) {
									$handle->image_y = $imgTmpY;
									$inCrop = true;
								}
								
								// No realiza crop si el sistema no debe redimensionar
								if ($inCrop) {
									$handle->image_resize = true;
								
									switch($imgTmpCrop) {
										case 'exact':
										default:
											$handle->image_ratio = false;
										break;

										case 'best':
											$handle->image_ratio = true;
										break;

										case 'crop':
											$handle->image_ratio_crop = true;
										break;
									}
								}
							}

							$handle->image_ratio_fill = true;
							$handle->image_background_color = '#FFFFFF';

							// Cargo marca de agua de la img
							/*
							if ($m == 0 || $m == 1) {
								$handle->image_watermark = dirPath.'/images/marca-agua.png';

								if ($m==0) {
									$handle->image_watermark_x = 140;
									$handle->image_watermark_y = 160;
								} 
								if ($m==1) {
									$handle->image_watermark_x = 94;
									$handle->image_watermark_y = 123;
								} 									
							} */						
							
							$handle->process(dirPath.'/includes/tmp/');
							
							if ($handle->processed) {
							
								$fileTmpData['files'][$imgNro]['local_file_pathname'] = $handle->file_dst_pathname;
								$fileTmpData['files'][$imgNro]['remoto_file_path'] = $pbase;
								$fileTmpData['files'][$imgNro]['remoto_file_name'] = $handle->file_dst_name;

								$new_name_body = $handle->file_dst_name_body;
								$new_name_body = substr($new_name_body, strlen($imgTmpPrefix));
								$new_name_body = substr($new_name_body, 0, strlen($new_name_body) - strlen($imgTmpSufix));
								$fileTmpData['file_new_name_body'] = $new_name_body;
								
								/* Información extra del archivo (extraido solo de tamaño original) */
								if ($imgTmpName == 'o') {
									$fileTmpData['file_src_x'] = $handle->image_dst_x;
									$fileTmpData['file_src_y'] = $handle->image_dst_y;
									$fileTmpData['file_src_size'] = filesize($handle->file_dst_pathname);

									$fileTmpData['file_dst_name'] = $fileTmpData['file_new_name_body'].'.'.$handle->file_dst_name_ext;
									// $fileTmpData['file_src_size_format'] = size_format($fileTmpData['file_src_size'],2);
								}

							} else {

								// $fileTmpData["file_src_error"] = "Error al generar los thumnails del archivo";
								$msjError = 'Error al general los thumb de las imagenes.';
								$fileTmpData['file_src_error'] = 'Error al generar un thumb de la imagen';
								// break;

							} // End processed
							
						} // end foreach FILES
					
					// Proceso en caso de que no sea imagen					
					} else {

						$handle->file_new_name_body = $nameBase;
						// $handle->file_name_body_add = $imgTmpSufix;
						// $handle->file_name_body_pre = $imgTmpPrefix;

						$handle->file_overwrite = true;
						$handle->file_auto_rename = false;		

						$handle->process(dirPath.'/includes/tmp/');

						if ($handle->processed) {
						
							$fileTmpData['files'][0]['local_file_pathname'] = $handle->file_dst_pathname;
							$fileTmpData['files'][0]['remoto_file_path'] = $pbase;
							$fileTmpData['files'][0]['remoto_file_name'] = $handle->file_dst_name;

							$new_name_body = $handle->file_dst_name_body;
							$new_name_body = substr($new_name_body, strlen($img_prefix));
							$new_name_body = substr($new_name_body, 0, strlen($new_name_body) - strlen($img_sufix));
							$fileTmpData['file_new_name_body'] = $new_name_body;

							$fileTmpData['file_src_size'] = filesize($handle->file_dst_pathname);
							$fileTmpData['file_dst_name'] = $fileTmpData['file_new_name_body'].'.'.$handle->file_dst_name_ext;
							// $fileTmpData['file_src_size_format'] = size_format($fileTmpData['file_src_size'],2);

						} else {

							// $fileTmpData["file_src_error"] = "Error al generar los thumnails del archivo";
							$msjError = 'Error al general los thumb de las imagenes.';
							$fileTmpData['file_src_error'] = 'Error al generar un thumb de la imagen';
							// break;

						} // End processed

					}

					// Guardo información a subir vía FTP
					$fileUpToFTP[] = $fileTmpData;
					
				} // end if check if name != ''

			} // End foreach FILE
			
			// Cargo el conector para subir los archivos procesados al FTP.
			include(dirPath.'/includes/widgets/base/file.upload.inc.php');

			// Cargo las imagenes que se cargaron en el array final
			$usrCheck->campos['files'] = $fileFinalList;

		} // End if not empty FILE
		
		$_SESSION['clasificado-add'] = array_merge($_SESSION['clasificado-add'], $usrCheck->campos);

		// Indico que voy al paso 3
		$_SESSION['clasificado-add']['paso_actual'] = 3;
		
		// Paso 2 OK, ingreso al Paso 3
		// echo "<pre>".print_r($_SESSION, true)."</pre>";
		// echo "<pre>".print_r($fileUpToFTP, true)."</pre>";
		Header("Location: clasificado-add-3.php");
		exit();

	} else {
	
		$msjError = $usrCheck->errorInfo;

	}
	
	$dataToSkin = $usrCheck->campos;
}

$dataToSkin = $usr->campos;

// Cargo token
$token = md5(microtime(true));
$_SESSION['token'] = $token;

/**
 * Agregado en caso de debug
 * Para visualizar el contenido del proceso final
 */
if (!empty($_GET['debug']) && $_GET['debug'] == true) {
	$incBody = $incBodyEnd;
	$dataToSkin['email'] = 'emaildemo@advertis.com.ar';
}

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>