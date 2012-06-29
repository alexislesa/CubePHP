<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "Solicitud demasiado larga";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">Solicitud demasiado larga</h2>

	
	<p>El servidor no puede procesar la direcci�n URL de la p�gina solicitada porque es demasiado larga. Puede tratar de encontrar la p�gina accediendo a:</p>
	
	<ul>
		<li>P�gina de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro men� o desde nuestro pie de p�gina</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>