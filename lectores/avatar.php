<?php
/* Conector */
include ("../includes/comun/conector.inc.php");

// Consulta si esta logeado
include (dirTemplate.'/lectores/check-login.inc.php');

// Validaciones del archivo de avatar
$avatarMaxSize = 25000;	// Peso en kb
$avatarWidth = 40;	// ancho en pixeles
$avatarHeight = 40;	// alto en pixeles

$pathRelative = 'lectores';
$incBody = dirTemplate.'/lectores/avatar.inc.php';
$incBar = dirTemplate.'/lectores/barra.inc.php';

// una vez modificada
$incBodyEnd = dirTemplate.'/lectores/avatar-fin.inc.php';

// Body class / Menú active
$pagActualClass[] = 'lectores';
$pagActualClass[] = 'avatar';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Mi cuenta'] = '/lectores';
$breds['Mi avatar'] = '';

$webTitulo = 'Modificación de avatar - '.$webTitulo;

/* Validaciones a realizar */
$checkForm = array();
$checkForm[] = 'if:avatarf=,required, avatar, Seleccione un avatar';
$checkForm[] = 'length=0-200, avatar, La url de su avatar es demasiado larga';

$dataToSkin = array();
if (!empty($_GET['act'])) {

	$_POST = cleanArray($_POST);

	/* Agregado para que me permita adjuntar el avatar */
	if (!empty($_FILES['avatarf']['name'])) {
	
		$v = $_FILES['avatarf'];
		$imgtemp = getimagesize($v['tmp_name']);

		if ( filesize($v['tmp_name']) > $avatarMaxSize ) {
		
			$msjError = 'El archivo supera los 25Kb de peso. ';
			$msjError.= 'Intente nuevamente con otra imagen.';
	
		} else {

			if ( $imgtemp[2] != 1 && $imgtemp[2] != 2 && $imgtemp[2] != 3 ) {
			
				$msjError = 'El archivo seleccionado no es una imagen válida. ';
				$msjError.= 'Solo se permiten archivos JPG, GIF y PNG. ';
				$msjError.= 'Intente nuevamente con otra imagen.';

			} else {
			
				if ($imgtemp[0] < 40 || $imgtemp[1] < 40) {
			
					$msjError = 'La es demasiado pequeña, no posee el ancho o alto solicitado. ';
					$msjError.= 'Intente nuevamente con otra imagen.';

				} else {
				
					/* Si no hay error, prosugo con la carga del archivo */
					include (dirPath.'/includes/clases/class.upload.php');
					include (dirPath.'/includes/clases/ftp_class.php');
					
					$handle = new upload($v['tmp_name']);

					if ($handle->uploaded) {

						if ($handle->file_is_image) {

							$scrFileNameBody = $usr->campos['lector_id'].'_'.time();

							$handle->image_convert = 'jpg';
							$handle->image_resize = true;
							// $handle->image_ratio = true;
							$handle->image_ratio_crop = true;
							$handle->image_x = $avatarWidth;
							$handle->image_y = $avatarHeight;
							$handle->jpeg_quality = 95;
							$handle->file_new_name_body = $scrFileNameBody;
							$handle->file_new_name_ext = 'jpg';
							$handle->file_overwrite = true;
							$handle->file_auto_rename = false;
							$handle->process(dirPath.'/includes/cache/');

							if ($handle->processed) {
								// Cargo los datos ftp del sitio
								$dataFtp = $ss_config['remoto'][0];

								$scrFileName = dirPath.'includes/cache/'.$scrFileNameBody.'.jpg';

								$dstFileName = $scrFileNameBody;
								$dstFileNamePath = '/usuarios/avatar/';
								$dstFileNamePathFinal = $dataFtp['path'].$dstFileNamePath;
								// Parche para evitar error al unir la path base de ftp y del datasite.
								$dstFileNamePathFinal = str_replace("//", "/", $dstFileNamePathFinal);
								
								// Subo el avatar a la zona del usuario
								$ftp = new ftp();
								$ftp->Verbose = FALSE;
								$ftp->LocalEcho = FALSE;
								
								if(!$ftp->SetServer($dataFtp['host'], $dataFtp['port'])) {
								
									$ftp->quit();
									
								} else {
								
									if ($ftp->connect()) {
									
										if (!$ftp->login($dataFtp['user'], $dataFtp['pass'])) {
											$ftp->quit();
										} else {
											if(!$ftp->SetType(FTP_AUTOASCII)) {
											} 
											if(!$ftp->Passive(FALSE)) {
											}
										} // End if login
										
									} // End if conect
								}

								if (!$ftp->is_exists($dstFileNamePathFinal)) {
								
									if (!$ftp->mmkdir($dstFileNamePathFinal)) {
									}

								}

								$ftp->chdir($dstFileNamePathFinal);

								if(FALSE !== $ftp->put($scrFileName, $dstFileNamePathFinal.$dstFileName)) {

								} else {
								
									$ftp->quit();

								}

								// Cargo el Nuevo avatar en la variable POST
								$_POST['avatar'] = $dataFtp['url'].$dstFileNamePath.$dstFileName;

							} else {
							
								$msjError  = 'Error al subir la imagen. Intente nuevamente.';
								
							}

							$handle->clean();

						} else {

							$msjError = 'El archivo seleccionado no es una imagen.';
						}
					}
				}
			}		
		}
	
	} // end if FILES

	/* Si no hay errores, prosigo */
	if (!$msjError) {

		$usrCheck = new checkFormulario($_POST, $checkForm);

		if ($usrCheck->process()) {
		
			if ($usr->avatar($usrCheck->campos['avatar'])) {
				
				$usr->campos['lector_avatar'] = $usrCheck->campos['avatar'];

				$incBody = $incBodyEnd;

			} else {

				$msjError = $usr->errorInfo;
			}

		} else {
		
			$msjError = $usrCheck->errorInfo;
		}	
	}

	$dataToSkin = $usrCheck->campos;
}

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