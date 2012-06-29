<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "No aceptable";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">No aceptable</h2>

	<p>La página solicitada posee características de contenidos que no son aceptables. Puede tratar de encontrar la página accediendo a:</p>
	
	<ul>
		<li>Página de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro menú o desde nuestro pie de página</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>