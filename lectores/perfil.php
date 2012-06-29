<?php
/* Conector */
include ("../includes/comun/conector.inc.php");

// Consulta si esta logeado
include (dirTemplate.'/lectores/check-login.inc.php');

$pathRelative = 'lectores';
$incBody = dirTemplate.'/'.$pathRelative.'/perfil.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// una vez modificada
$incBodyEnd = dirTemplate.'/'.$pathRelative.'/perfil-fin.inc.php';

// Body class / Menú active
$pagActualClass[] = "lectores";
$pagActualClass[] = "lector-online";
$pagActualClass[] = "perfil";
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds["Mi cuenta"] = "/lectores";
$breds["Mi perfil"] = "";

$webTitulo = 'Mi Perfil - '.$webTitulo;

/* Validaciones a realizar */
$checkForm = array();
$checkForm[] = "required, email, Ingrese su email";
$checkForm[] = "valid_email, email, Ingrese un email válido";
$checkForm[] = "length=0-100, email, El email ingresado es demasiado largo. Verifique si es correcto";
$checkForm[] = "required, nombre, Ingrese su nombre";
$checkForm[] = "length=0-35, nombre, El nombre no puede superar los 35 caracteres";
$checkForm[] = "required, apellido, Ingrese su apellido";
$checkForm[] = "length=0-64, nombre, El apellido no puede superar los 64 caracteres";
// $checkForm[] = "required, sexo, Debe seleccionar su sexo";
// $checkForm[] = "range>1901, fanio, Seleccione su fecha de nacimiento";
// $checkForm[] = "valid_date,fmes,fdia,fanio,any_date, Ingrese una fecha de nacimiento válida";
$checkForm[] = "required, pais, Seleccione su país";
$checkForm[] = "if:pais=12, required, provincia, Seleccione su provincia";
//$checkForm[] = "if:provincia!=795, required, departamento, Seleccioná tu departamento";
$checkForm[] = "if:provincia=248, required, localidad, Seleccione su localidad";

$dataToSkin = array();
if (!empty($_GET['act'])) {

	$_POST = cleanArray($_POST);
	
	/* Modificación para que el sistema no me pida el sexo. ya que no se puede modificar */
	$_POST['fdia'] = substr($usr->campos['lector_fnacimiento'],0,2);
	$_POST['fmes'] = substr($usr->campos['lector_fnacimiento'],2,2);
	$_POST['fanio'] = substr($usr->campos['lector_fnacimiento'],4);
	$_POST['sexo'] = $usr->campos['lector_sexo'];	

	$usrCheck = new checkFormulario($_POST, $checkForm);

	if ($usrCheck->process()) {
	
		if ($usr->edit($usrCheck->campos)) {
		
			$incBody = $incBodyEnd;

		} else {
		
			$msjError = $usr->errorInfo;
		}

	} else {
	
		$msjError = $usrCheck->errorInfo;

	}
	
	$dataToSkin = $usrCheck->campos;
}

/* Cargo la información básica del perfil de usuario */
foreach ($usr->campos as $k => $v) {
$m = str_replace('lector_', '', $k);
	$dataToSkin[$m] = $v;
}

$dataToSkin['localidad2'] = $dataToSkin['localidad_txt'];

$dataToSkin['fdia'] = substr($usr->campos['lector_fnacimiento'],0,2);
$dataToSkin['fmes'] = substr($usr->campos['lector_fnacimiento'],2,2);
$dataToSkin['fanio'] = substr($usr->campos['lector_fnacimiento'],4);

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