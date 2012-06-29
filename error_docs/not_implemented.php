<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "No implementado";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">No implementado</h2>
	
	<p>El servidor no posee la funcionalidad requerida para procesar esta página. Puede tratar de encontrar la página accediendo a:</p>
	
	<ul>
		<li>Página de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro menú o desde nuestro pie de página</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>