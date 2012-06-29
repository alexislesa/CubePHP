<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "Error interno de servidor";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">Error interno del servidor</h2>

	<p>Se ha producido un error en el servidor y no puede completar la solicitud. Puede continuar navegando accediendo a:</p>
	
	<ul>
		<li>P�gina de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro men� o desde nuestro pie de p�gina</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>