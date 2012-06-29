<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "No disponible";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">No disponible. La p�gina que intenta acceder ya no existe en el servidor</h2>

	<p>La p�gina que intenta acceder ya no existe en el servidor. Puede tratar de encontrar la p�gina accediendo a:</p>
	
	<ul>
		<li>P�gina de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro men� o desde nuestro pie de p�gina</li>
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>