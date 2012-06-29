<?php
/* Cargo Helpers del sitio */
$pathHelpers = dirPath.'/includes/widgets/base/helpers/';
include ($pathHelpers.'helper.browser.php');
include ($pathHelpers.'helper.files.php');
include ($pathHelpers.'helper.form.php');
include ($pathHelpers.'helper.html.php');
include ($pathHelpers.'helper.sessions.php');
include ($pathHelpers.'helper.site.php');
include ($pathHelpers.'helper.string.php');
include ($pathHelpers.'helper.time.php');
include ($pathHelpers.'helper.validations.php');
include ($pathHelpers.'helper.security.php');

/* Cargo Clases del sitio */
$pathClasses = dirPath.'/includes/widgets/base/classes/';
include ($pathClasses.'core.inc.php');
include ($pathClasses.'adjuntos.inc.php');
include ($pathClasses.'cache.inc.php');
include ($pathClasses.'captcha.inc.php');
include ($pathClasses.'checkformulario.inc.php');
include ($pathClasses.'clasificados.inc.php');
include ($pathClasses.'comentarios.inc.php');
include ($pathClasses.'encuestas.inc.php');
include ($pathClasses.'enviomails.inc.php');
include ($pathClasses.'grilla.inc.php');
include ($pathClasses.'lectores.inc.php');
include ($pathClasses.'lectoresstats.inc.php');
include ($pathClasses.'menu.inc.php');
include ($pathClasses.'notas.inc.php');
include ($pathClasses.'nubetags.inc.php');
include ($pathClasses.'paginador.inc.php');
include ($pathClasses.'publicidad.inc.php');
include ($pathClasses.'stats.inc.php');
include ($pathClasses.'obj.inc.php');

/* Clases OLD */
// include ($pathClasses.'lectores-noticias.inc.php');

/* Cargo Clases específicas del sitio */
include (dirPath.'/includes/widgets/site/autoload.inc.php');
?>