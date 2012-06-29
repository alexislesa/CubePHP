<?php
/**
 * Realiza el proceso de subida de archivos al FTP
 */

// Consulto si se procesaron los archivos y los subo vía FTP.
$fileFinalList = array();
if (!$msjError && count($fileUpToFTP)) {

	// Path donde se encuentra la abstracción de FileSystem
	$fspath = dirPath.'/includes/clases/ftp_index.php';

	if (!file_exists($fspath)) {
	
		$msjError = 'Error al cargar las funciones de repositorio remoto';

	} else {
	
		include($fspath);

	}

	$flt = new fs($saveData);
	
	if (!$fl = $flt->conect()) {
		// Como no se pudo conectar, elimino todas las entradas de archivos
		$msjError =  $fl->error_msj;
		$msjError = 'No se puede contectar al servidor.';
	
	} else {

		$fl->Passive(true);
		
		foreach ($fileUpToFTP as $k => $v) {
		
			if (isset($v['files']) && !$v['file_src_error']) {
			
				$inError = false;
				
				foreach ($v['files'] as $j => $m) {
				
					if (!$inError) {
					
						if (!$fl->file_exists($saveData['path'].$m['remoto_file_path'])) {
							if (!$fl->mmkdir($saveData['path'].$m['remoto_file_path'])) {

								$inError = true;

								$fileUpToFTP[$k]['file_src_error'] = 'No se puede crear la carpeta destino';
							}
						}

						if (!$inError) {

							$fl->chdir($saveData['path'].$m['remoto_file_path']);
							
							if (false == $fl->put($m['local_file_pathname'], $m['remoto_file_name'])) {
								
								$inError = true;
								$msjError = 'Error al leer información de la carpeta del repositorio remoto';
								
								$fileUpToFTP[$k]['file_src_error'] = 'No se puede copiar el archivo a la carpeta destino';
							}
						}
					}

				} // End foreach v[files]
				
				// Agrego el archivo al array final
				if (empty($fileUpToFTP[$k]['file_src_error'])) {
					$fileFinalList[] = $fileUpToFTP[$k];
				}				

			} // End if file_error
			
		} // End foreach fileupload

	} // End if conect

} // End if count fileToUploadFTP
?>