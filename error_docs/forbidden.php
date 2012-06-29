<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "El servidor ha rechazado su solicitud";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">El servidor ha rechazado su solicitud</h2>

	<p>No posee permisos para acceder a la p�gina solicitada. Puede continuar navegando accediendo a:</p>
	
	<ul>
		<li>P�gina de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro men� o desde nuestro pie de p�gina</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>