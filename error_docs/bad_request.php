<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "Error de solicitud";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">Error de solicitud</h2>

	<p>El servidor no pudo entender la direcci�n URL solicitada. Puede tratar de encontrar la p�gina accediendo a:</p>
	
	<ul>
		<li>P�gina de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro men� o desde nuestro pie de p�gina</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>