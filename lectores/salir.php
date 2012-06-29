<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

// Elimino sesiones de usuario
$_SESSION['web_site']['user'] = null;
$_SESSION['web_site']['pw'] = null;
$_SESSION['web_site']['id'] = null;
unset($_SESSION['web_site']);

Header('Location: index.php');
exit();
?>