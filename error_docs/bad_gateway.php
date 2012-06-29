<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "Error de Proxy";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">Error de proxy</h2>
	
	<p>El navegador ha recibido una respuesta no válida del servidor. Puede tratar de encontrar la página accediendo a:</p>
	
	<ul>
		<li>Página de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro menú o desde nuestro pie de página</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>