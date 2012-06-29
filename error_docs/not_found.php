<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

// Guardo informaci�n de las p�ginas no encontradas
$err_msj = 'Error, p�gina no encontrada:'.$_SERVER["REQUEST_URI"];
$num_err = 404;
$vars_err = '';
error_handler('', '', 'E_ERROR', $num_err, $err_msj, $vars_err);

$webTitulo = 'P�gina no disponible';

include('header.inc.php');?> 

<div class="notfound">

	<h2 class="nota-title">P�gina no disponible</h2>

	<p>La p�gina que usted solicit� no se encuentra disponible en este momento. Puede tratar de encontrar la p�gina accediendo a:</p>
	
	<ul>
		<li>P�gina de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro men� o desde nuestro pie de p�gina</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?> 