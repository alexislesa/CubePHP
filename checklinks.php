<?php
/**
 * Indica que archivos se modificaron, para saber que archivos debemos subir al sitio
 */

/**
 * Class to dynamically create a zip file (archive) of file(s) and/or directory
 *
 * @author Rochak Chauhan  www.rochakchauhan.com
 * @package CreateZipFile
 * @see Distributed under "General Public License"
 * 
 * @version 1.0
 */

class CreateZipFile {

	public $compressedData = array();
	public $centralDirectory = array(); // central directory
	public $endOfCentralDirectory = "\x50\x4b\x05\x06\x00\x00\x00\x00"; //end of Central directory record
	public $oldOffset = 0;

	/**
	 * Function to create the directory where the file(s) will be unzipped
	 *
	 * @param string $directoryName
	 * @access public
	 * @return void
	 */	
	public function addDirectory($directoryName) {
		$directoryName = str_replace("\\", "/", $directoryName);
		$feedArrayRow = "\x50\x4b\x03\x04";
		$feedArrayRow .= "\x0a\x00";
		$feedArrayRow .= "\x00\x00";
		$feedArrayRow .= "\x00\x00";
		$feedArrayRow .= "\x00\x00\x00\x00";
		$feedArrayRow .= pack("V",0);
		$feedArrayRow .= pack("V",0);
		$feedArrayRow .= pack("V",0);
		$feedArrayRow .= pack("v", strlen($directoryName) );
		$feedArrayRow .= pack("v", 0 );
		$feedArrayRow .= $directoryName;
		$feedArrayRow .= pack("V",0);
		$feedArrayRow .= pack("V",0);
		$feedArrayRow .= pack("V",0);
		$this->compressedData[] = $feedArrayRow;
		$newOffset = strlen(implode("", $this->compressedData));
		$addCentralRecord = "\x50\x4b\x01\x02";
		$addCentralRecord .="\x00\x00";
		$addCentralRecord .="\x0a\x00";
		$addCentralRecord .="\x00\x00";
		$addCentralRecord .="\x00\x00";
		$addCentralRecord .="\x00\x00\x00\x00";
		$addCentralRecord .= pack("V",0);
		$addCentralRecord .= pack("V",0);
		$addCentralRecord .= pack("V",0);
		$addCentralRecord .= pack("v", strlen($directoryName) );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("V", 16 );
		$addCentralRecord .= pack("V", $this->oldOffset );
		$this->oldOffset = $newOffset;
		$addCentralRecord .= $directoryName;
		$this->centralDirectory[] = $addCentralRecord;
	}

	/**
	 * Function to add file(s) to the specified directory in the archive 
	 *
	 * @param string $directoryName
	 * @param string $data
	 * @return void
	 * @access public
	 */	
	public function addFile($data, $directoryName)   {
		$directoryName = str_replace("\\", "/", $directoryName);
		$feedArrayRow = "\x50\x4b\x03\x04";
		$feedArrayRow .= "\x14\x00";
		$feedArrayRow .= "\x00\x00";
		$feedArrayRow .= "\x08\x00";
		$feedArrayRow .= "\x00\x00\x00\x00";
		$uncompressedLength = strlen($data);
		$compression = crc32($data);
		$gzCompressedData = gzcompress($data);
		$gzCompressedData = substr( substr($gzCompressedData, 0, strlen($gzCompressedData) - 4), 2);
		$compressedLength = strlen($gzCompressedData);
		$feedArrayRow .= pack("V",$compression);
		$feedArrayRow .= pack("V",$compressedLength);
		$feedArrayRow .= pack("V",$uncompressedLength);
		$feedArrayRow .= pack("v", strlen($directoryName) );
		$feedArrayRow .= pack("v", 0 );
		$feedArrayRow .= $directoryName;
		$feedArrayRow .= $gzCompressedData;
		$feedArrayRow .= pack("V",$compression);
		$feedArrayRow .= pack("V",$compressedLength);
		$feedArrayRow .= pack("V",$uncompressedLength);
		$this->compressedData[] = $feedArrayRow;
		$newOffset = strlen(implode("", $this->compressedData));
		$addCentralRecord = "\x50\x4b\x01\x02";
		$addCentralRecord .="\x00\x00";
		$addCentralRecord .="\x14\x00";
		$addCentralRecord .="\x00\x00";
		$addCentralRecord .="\x08\x00";
		$addCentralRecord .="\x00\x00\x00\x00";
		$addCentralRecord .= pack("V",$compression);
		$addCentralRecord .= pack("V",$compressedLength);
		$addCentralRecord .= pack("V",$uncompressedLength);
		$addCentralRecord .= pack("v", strlen($directoryName) );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("V", 32 );
		$addCentralRecord .= pack("V", $this->oldOffset );
		$this->oldOffset = $newOffset;
		$addCentralRecord .= $directoryName;
		$this->centralDirectory[] = $addCentralRecord;
	}

	/**
	 * Function to return the zip file
	 *
	 * @return zipfile (archive)
	 * @access public
	 * @return void
	 */
	public function getZippedfile() {
		$data = implode("", $this->compressedData);
		$controlDirectory = implode("", $this->centralDirectory);
		return
		$data.
		$controlDirectory.
		$this->endOfCentralDirectory.
		pack("v", sizeof($this->centralDirectory)).
		pack("v", sizeof($this->centralDirectory)).
		pack("V", strlen($controlDirectory)).
		pack("V", strlen($data)).
		"\x00\x00";
	}

	/**
	 *
	 * Function to force the download of the archive as soon as it is created
	 *
	 * @param archiveName string - name of the created archive file
	 * @access public
	 * @return ZipFile via Header
	 */
	public function forceDownload($archiveName) {
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}

		// Security checks
		if( $archiveName == "" ) {
			echo "<html><title>Public Photo Directory - Download </title><body><BR><B>ERROR:</B> The download file was NOT SPECIFIED.</body></html>";
			exit;
		}
		elseif ( ! file_exists( $archiveName ) ) {
			echo "<html><title>Public Photo Directory - Download </title><body><BR><B>ERROR:</B> File not found.</body></html>";
			exit;
		}

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=".basename($archiveName).";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($archiveName));
		readfile("$archiveName");
	}

	/**
	  * Function to parse a directory to return all its files and sub directories as array
	  *
	  * @param string $dir
	  * @access protected 
	  * @return array
	  */
	protected function parseDirectory($rootPath, $seperator="/"){
		$fileArray=array();
		$handle = opendir($rootPath);
		while( ($file = @readdir($handle))!==false) {
			if($file !='.' && $file !='..'){
				if (is_dir($rootPath.$seperator.$file)){
					$array=$this->parseDirectory($rootPath.$seperator.$file);
					$fileArray=array_merge($array,$fileArray);
				}
				else {
					$fileArray[]=$rootPath.$seperator.$file;
				}
			}
		}		
		return $fileArray;
	}

	/**
	 * Function to Zip entire directory with all its files and subdirectories 
	 *
	 * @param string $dirName
	 * @access public
	 * @return void
	 */
	public function zipDirectory($dirName, $outputDir) {
		if (!is_dir($dirName)){
			trigger_error("CreateZipFile FATAL ERROR: Could not locate the specified directory $dirName", E_USER_ERROR);
		}
		$tmp=$this->parseDirectory($dirName);
		$count=count($tmp);
		$this->addDirectory($outputDir);
		for ($i=0;$i<$count;$i++){
			$fileToZip=trim($tmp[$i]);
			$newOutputDir=substr($fileToZip,0,(strrpos($fileToZip,'/')+1));
			$outputDir=$outputDir.$newOutputDir;
			$fileContents=file_get_contents($fileToZip);
			$this->addFile($fileContents,$fileToZip);
		}
	}
}
 

/**
 * Descarga los archivos solicitados
 *
 */
if (!empty($_GET["down"]) && !empty($_POST["files"]) && is_array($_POST["files"])) {

	$path_root = dirname(__FILE__);
	$createZipFile=new CreateZipFile;

	$dirs = array();
	$fileInclude = "";
	foreach($_POST["files"] as $k => $v) {

		$t = explode("/", $v);
		$folder = substr($v,0,strlen($v)-strlen($t[count($t)-1]));
	
		if (!in_array($folder, $dirs)) {
			$createZipFile->addDirectory($folder);
			$dirs[] = $folder;
		}

		$fileContents=file_get_contents($path_root.$v);
		
		$createZipFile->addFile($fileContents, $v);
		
		// Información para generar el log
		$fileInclude.= "--	".str_replace('/', ' / ', $v)."\n";
	}
	
	/**
	 * Genero el archivo del log del sitio
	 */
	$fileText = "";
	if (!empty($_POST["info"])) {
		$_POST["info"] = str_replace("\r", "", $_POST["info"]);
		$info = explode("\n", $_POST["info"]);

		foreach ($info as $k => $v) {
			$fileText.= "--	".$v." \n";
		}
	}
	
	$infoSQL = "";
	if (!empty($_POST["infousr"])) {
		$_POST["infousr"] = str_replace("\r", "", $_POST["infousr"]);
		$infoUSR = explode("\n", $_POST["infousr"]);

		$ahora = time();

		foreach ($infoUSR as $k => $v) {
			$v = trim($v);

			$infoSQL.= "INSERT INTO _notificaciones (notificacion_fecha, notificacion_nivel, notificacion_texto) VALUES ('{$ahora}', '0', '{$v}');\n";
		}
	}

	$fileContents = "--\n";
	$fileContents.= "-- Modificación \n";
	$fileContents.= "-- \n";
	$fileContents.= $fileText;
	$fileContents.= "--	-----------------------------------------------------------------";
	$fileContents.= "-- \n";
	$fileContents.= "-- Changelog Cliente \n";
	$fileContents.= "-- \n";
	$fileContents.= "-- Módulo: \n";
	$fileContents.= "--	-----------------------------------------------------------------";
	$fileContents.= "-- \n";
	$fileContents.= "-- Archivo modificados: \n";
	$fileContents.= "-- \n";
	$fileContents.= $fileInclude;
	$fileContents.= "-- \n";
	$fileContents.= $infoSQL;
	$fileContents.= "-- \n";
	$fileContents.= "UPDATE _version SET version = '6.0.0', fecha = UNIX_TIMESTAMP( '".date("Y-m-d G:i:s")."');";
	
	$v = '/upgrade-'.date('Y-m-d').'.sql';
	$createZipFile->addFile($fileContents, $v);	
	$zipFileStream = $createZipFile->getZippedfile();

	$zipName = str_replace(".","_",$_SERVER["SERVER_NAME"])."_".date("Y_m_d_G_i").".zip";

	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"{$zipName}\"\n");
	header("Content-Length: ".strlen($zipFileStream));
	echo $zipFileStream;
	
	// Muestra reporte
	if (!empty($_POST['report'])) {
		// echo str_replace("\n", "<br/>", $fileContents);
	}
	
	exit();
}
 
 
/**
 * Recorre el sitio y retorna los archivos modificados
 */
function recorrearbol($path_base, $web_src = "") {
	
	$folder_check = array("/galeria", "/images/temp", "/includes/tmp", "/includes/cache", "/rss", "/users", "/tmp","/debug","/error_docs");
	$file_check = array("checklinks.php", "Thumbs.db");

	$file_arr = array();
	
	
	if (!in_array($web_src, $folder_check)) {
	
		$handle=opendir($path_base.$web_src);
		
		while (false!==($file = readdir($handle))) {

			if ($file != "."  AND $file != ".." AND !in_array($file, $file_check)) {

				if (is_dir($path_base.$web_src."/".$file)) {

					if (file_exists($path_base.$web_src."/".$file)) {
					
						$m = recorrearbol($path_base, $web_src."/".$file);
					
						$file_arr = array_merge($file_arr, $m);
					}

				} else {
				
					$file_time = filemtime($path_base.$web_src."/".$file);
					
					$file_arr[] = array("src" => $web_src."/".$file, "time" => $file_time);
				}
			}
		}
		
	} // end folder_check
	
	return $file_arr;
}


$file_arr = array();

$path_base = substr(__FILE__, 0, strlen(__FILE__) - strlen(basename(__FILE__)));

$file_arr = recorrearbol($path_base);


/* Ordeno los archivos por fecha de modificación */
$file_mod_hoy = array();
$file_mod_ayer = array();
$file_mod_ante_ayer = array();
$file_mod_semana = array();
$file_mod_mes = array();
$file_mod_inicio = array();

$fecha_esta_semana = mktime(0,0,0,date("m"), date("d")-7, date("y"));
$fecha_este_mes =  mktime(0,0,0,date("m"), date("d")-30, date("y"));
$fecha_mod_inicio = filemtime(__FILE__);

foreach ($file_arr as $k => $v) {

	if ($v["time"] > mktime(0,0,0,date("m"), date("d"), date("y"))) {
		$file_mod_hoy[] = $v;
	}
	
	if ($v["time"] > mktime(0,0,0,date("m"), date("d")-1, date("y")) && $v["time"] < mktime(0,0,0,date("m"), date("d"), date("y"))) {
		$file_mod_ayer[] = $v;
	}
	
	if ($v["time"] > mktime(0,0,0,date("m"), date("d")-2, date("y")) && $v["time"] < mktime(0,0,0,date("m"), date("d")-1, date("y"))) {
		$file_mod_ante_ayer[] = $v;
	}	

	if ($v["time"] > mktime(0,0,0,date("m"), date("d")-7, date("y"))) {
		$file_mod_semana[] = $v;
	}
	
	if ($v["time"] > mktime(0,0,0,date("m"), date("d")-30, date("y"))) {
		$file_mod_mes[] = $v;
	}	
	
	if ($v["time"] > $fecha_mod_inicio) {
		$file_mod_inicio[] = $v;
	}
}

$c = 1;
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="http://herramientas.dev/styles/reset.css">
<script type="text/javascript" src="http://herramientas.dev/js/jquery.js"></script>

<style>
	body {padding:10px 20px 20px 20px; line-height:140%; color:#333;}
	h2 {font-size:24px;}
	legend {padding:5px; background:#FAFAFA; padding:5px; margin:5px; border:solid 1px #DDD;}
	fieldset {border:solid 1px #DDD; background:#F0F0F0; margin:10px; padding:5px;}
	strike {color:#666; text-decoration:line-through;}
	.plegado {border:solid 1px #333;}
	.plegado DIV {display:none;}
	.enviar {margin:10px 0px; border:solid 1px #666; padding:5px; background:#888; cursor:pointer;}
</style>

</head>

<body>
<form name="listado" action="?down=true" method="post"> 

	<fieldset class="desplegado">
		<legend ><h2>Archivos modificados Hoy</h2></legend>
		<div>
		<?php foreach ($file_mod_hoy as $k => $v) {
			echo "<input type=\"checkbox\" name=\"files[]\" value=\"{$v['src']}\" id=\"file_".($c)."\" checked /> &nbsp; <label for=\"file_".($c++)."\">".$v['src']."</label><br/>";
		} ?>
		</div>
	</fieldset>
	
	<fieldset class="plegado">
		<legend ><h2>Archivos modificados Ayer</h2></legend>
		<div>
		<?php foreach ($file_mod_ayer as $k => $v) {
			echo "<input type=\"checkbox\" name=\"files[]\" value=\"{$v['src']}\" id=\"file_".($c)."\" /> &nbsp; <label for=\"file_".($c++)."\">".$v['src']."</label><br/>";
		} ?>
		</div>
	</fieldset>
	
	<fieldset class="plegado">
		<legend ><h2>Archivos modificados Antes de ayer</h2></legend>
		<div>
		<?php foreach ($file_mod_ante_ayer as $k => $v) {
			echo "<input type=\"checkbox\" name=\"files[]\" value=\"{$v['src']}\" id=\"file_".($c)."\" /> &nbsp; <label for=\"file_".($c++)."\">".$v['src']."</label><br/>";
		} ?>
		</div>
	</fieldset>	
	
	<fieldset class="plegado">
		<legend ><h2>Archivos modificados esta semana (desde el <?php echo date("d.m.Y", $fecha_esta_semana);?>)</h2></legend>
		<div>
		<?php foreach ($file_mod_semana as $k => $v) {
			if (in_array($v, $file_mod_hoy) || in_array($v, $file_mod_ayer) || in_array($v, $file_mod_ante_ayer)) {
			
				echo "<input type=\"checkbox\" name=\"files[]\" value=\"{$v['src']}\" disabled /> &nbsp; <strike>".$v['src']."  (".date("d/m", $v['time']).")</strike><br/>";
				
			} else {
			
				echo "<input type=\"checkbox\" name=\"files[]\" value=\"{$v['src']}\" id=\"file_".($c)."\" /> &nbsp; <label for=\"file_".($c++)."\">".$v['src']." (".date("d/m", $v['time']).")</label><br/>";

			}
		} ?>
		</div>
	</fieldset>
	
	<fieldset class="plegado">
		<legend ><h2>Archivos modificados en los últimos 30 días (desde el <?php echo date("d.m.Y", $fecha_este_mes);?>)</h2></legend>
		<div>
		<?php foreach ($file_mod_mes as $k => $v) {
			if (in_array($v, $file_mod_hoy) || in_array($v, $file_mod_ayer) || in_array($v, $file_mod_ante_ayer) || in_array($v, $file_mod_semana)) {
			
				echo "<input type=\"checkbox\" name=\"files[]\" value=\"{$v['src']}\" disabled /> &nbsp; <strike>".$v['src']."  (".date("d/m", $v['time']).")</strike><br/>";
				
			} else {
			
				echo "<input type=\"checkbox\" name=\"files[]\" value=\"{$v['src']}\" id=\"file_".($c)."\" /> &nbsp; <label for=\"file_".($c++)."\">".$v['src']."  (".date("d/m", $v['time']).")</label><br/>";

			}
		} ?>
		</div>
	</fieldset>	
	
	<? /*
	<fieldset>
		<legend ><h2>Archivos modificados desde el inicio</h2></legend>
		<?php foreach ($file_mod_inicio as $k => $v) {

			if (in_array($v, $file_mod_ayer) || in_array($v, $file_mod_hoy) || in_array($v, $file_mod_semana)) {
			
				echo "<input type=\"checkbox\" name=\"files[]\" value=\"{$v}\" disabled /> &nbsp; <strike>".$v."</strike><br/>";
				
			} else {
				
				echo "<input type=\"checkbox\" name=\"files[]\" value=\"{$v}\" id=\"file_".($c)."\" /> &nbsp; <label for=\"file_".($c++)."\">".$v."</label><br/>";
				
			}

		} ?>
		<br/><br/>
	</fieldset>
	*/ ?>

	<fieldset class="desplegado">
		<legend><h2>Información para el panel</h2></legend>
		<h4>Info de actualización para el usuario (un item por renglón)</h4>

		<div>
			<textarea name="infousr" rows="5" cols="80"></textarea>
		</div>
	</fieldset>

	<fieldset class="desplegado">
		<legend ><h2>Información de la actualización realizada</h2></legend>
		<div>
			<textarea name="info" rows="5" cols="80"></textarea>
		</div>
		
		<input type="submit" class="enviar" name="descargar" value="Descargar archivos seleccionados" />

	</fieldset>

	<br/><br/>
</form>

<script type="text/javascript">
$(".plegado h2").live("click", function() {
	$(this).parent().parent().attr("class","desplegado");
});
$(".desplegado h2").live("click", function() {
	$(this).parent().parent().attr("class","plegado");
});
</script>
</body>
</html>