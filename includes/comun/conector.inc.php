<?php 
/* Inicializa las opciones más utilizadas en el sitio */
define('dirPath', substr(__FILE__, 0, strlen(__FILE__) - strlen('/includes/comun/'.basename(__FILE__))));
define('dirTemplate', dirPath.'/includes/templates');

$_SERVER['HTTP_USER_AGENT'] = !empty($_SERVER['HTTP_USER_AGENT']) 
							? $_SERVER['HTTP_USER_AGENT'] 
							: '';

include (dirPath.'/includes/db/config.inc.php');
include (dirPath.'/includes/db/datasite.inc.php');
if (file_exists(dirPath.'/includes/db/datasite-local.inc.php')) {
	include (dirPath.'/includes/db/datasite-local.inc.php');
}
include (dirPath.'/includes/clases/db.class.php');

include(dirPath.'/includes/widgets/base/autoload.inc.php');

/* Inicio sessión */
session_cache_limiter('public');
session_cache_expire(5);	// 5 min
session_start();

/* Inicializo el conector de dBase */
$db = New dbConnect($ss_config['db_host'], $ss_config['db_user'], $ss_config['db_pass'], $ss_config['db_name']);
$db->conect();

/* Cargo el ADV-This */
$urlRoot = 'http://'.$_SERVER['SERVER_NAME'];
$urlRoot.= ($_SERVER['SERVER_PORT'] != 80) ? ':'.$_SERVER['SERVER_PORT'] : '';

$advThisUrl = $urlRoot.$_SERVER['REQUEST_URI'];

$advThisUrlClean = urlencode($advThisUrl);

// Mensajes de error / éxito
$msjError = false;
$msjExito = false;
$msjAlerta = false;
$msjTip = false;

// Identifico si es mobile
$oMobile = isMobile();

// Clase para el body, también utilizado en el menú
$pagActualClass = Array();

// Breadcrums
$breds = array();


// Defino variables básicas para el funcionamiento de noticias
// Estas variables luego se sobre-escriben
$pagCantidad = 10;
$pagPaginador = 10;
$pagActual = !empty($_GET['p']) ? (is_numeric($_GET['p']) ? $_GET['p'] : 0) : 0;
$pagDesplazamiento = $pagCantidad * $pagActual;
$totalResultados = 0;
$totalPaginas = 0;

// Zonas publicitarias
include (dirPath.'/includes/comun/adv_zones.inc.php');

/* Cargo el manejo global de cache */
if (isset($ss_config['cache_type']) && $ss_config['cache_type'] != '') {

	$cache = new Cache($ss_config['cache_type']);
	if ($ss_config['cache_type'] == 'file') {
	
		$cache->path = ($ss_config['cache_path'] != '') ? $ss_config['cache_path'] : dirPath.'/includes/cache';
		
	} elseif ($ss_config['cache_type'] == 'db') {
	
		$cache->db = $db;
	}
}
?>