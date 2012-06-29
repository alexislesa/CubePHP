<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

// Guardo información de las páginas no encontradas
$err_msj = 'Error, página no encontrada:'.$_SERVER["REQUEST_URI"];
$num_err = 404;
$vars_err = '';
error_handler('', '', 'E_ERROR', $num_err, $err_msj, $vars_err);

$webTitulo = 'Página no disponible';

include('header.inc.php');?> 

<div class="notfound">

	<h2 class="nota-title">Página no disponible</h2>

	<p>La página que usted solicitó no se encuentra disponible en este momento. Puede tratar de encontrar la página accediendo a:</p>
	
	<ul>
		<li>Página de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro menú o desde nuestro pie de página</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?> 