<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'clasificados';
$incBody = dirTemplate.'/'.$pathRelative.'/listado.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = 'clasificados';
$pagActualClass[] = 'clasificados-listado';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Clasificados'] = '/clasificados';


/**
 * Opciones de filtro, consulto solo por estas
 *
 * 	cat		categoría
 *	img		solo con imagen
 *	dep		Departamentos (ubicación)
 *	des		Tipo de destacado
 *	est		Estado del aviso (nuevo, usado, etc)
 *	env		Formas de envío
 *	id		Id del art
 *	loc		Localidad (ubicación)
 *	pag		Medio de pago 
 *	op		Operación (compra, venta)
 *	pais	Pais
 *	prov	Provincia
 *	act		Solo activos
 *	q		Busqueda por texto
 *	tipo	Tipo de texto
 *	ttip	Filtro por tipo (ventas, subasta)
 *	t		Url friendly
 *	usr		Id del usuario
 *	ord		Orden de listado
 */
$filtroKeys = array(
	'cat' => 'itemCategoria', 
	'img' => 'itemConImagen', 
	'dep' => 'itemDepartamento', 
	'des' => 'itemDestacado', 
	'est' => 'itemEstado', 
	'env' => 'itemFormasEnvio', 
	'id' => 'itemId', 
	'loc' => 'itemLocalidad', 
	'pag' => 'itemMedioPago', 
	'op' => 'itemOperacion', 
	'pais' => 'itemPais', 
	'prov' => 'itemProvincia', 
	// 'act' => 'filtroSoloActivos', 
	'q' => 'itemTexto', 
	'tipo' => 'itemTextoTipo', 
	'ttipo' => 'itemTipo', 
	't' => 'itemTitulo', 
	'usr' => 'itemUserId', 
	'ord' => 'orden'
);

$filtroName = array(
	'cat' => 'Categorías', 
	'img' => 'Solo con imagen', 
	'dep' => 'Departamento', 
	'des' => 'Destacados', 
	'est' => 'Estado', 
	'env' => 'Formas de Envío', 
	'id' => 'ID', 
	'loc' => 'Localidad', 
	'pag' => 'Medio de pago', 
	'op' => 'Operación', 
	'pais' => 'País', 
	'prov' => 'Provincia', 
	'act' => 'Solo avisos activos', 
	'q' => 'Texto', 
	'tipo' => 'Tipo de texto', 
	'ttipo' => 'Tipo', 
	't' => 'titulo', 
	'usr' => 'Usuario', 
	'ord' => 'Orden'
);

$filtroArray = array();
foreach ($filtroKeys as $k => $v) {
	if (isset($_GET[$k]) && is_string($_GET[$k])) {
		$filtroArray[$v] = trim($_GET[$k]);
	}
}
/* Cargo los filtro básicos de esta página, esto sobreescribe los valores cargados */
$filtroArray = array_merge($filtroArray, 
	array(
		'itemTipo' => 1,
		'itemSQLExtra' => '',
		'itemCategoriaRecursiva' => 1,
		'itemSoloActivos' => 1
	)
);

$gd = New Clasificados();
$gd->db = $db;
$gd->cantidad = $pagCantidad;
$gd->desplazamiento = $pagDesplazamiento;

/* Asigno las opciones de filtro */
foreach ($filtroArray as $k => $v) {
	$gd->$k = $v;
}

$dataToSkin = $gd->process();
$dataFacetas = $gd->facetas;

$totalResultados = $gd->totalResultados;
$totalPaginas = $gd->totalPaginas;

if ($gd->errorInfo) {

	$msjError = $gd->errorInfo;

} else {

	if ($totalResultados) {

	} else {

		$msjAlerta = 'No se encontraron avisos para mostrar';
	}
}

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>