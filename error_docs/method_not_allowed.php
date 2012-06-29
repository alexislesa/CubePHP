<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "Método no permitido";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">Método no permitido</h2>

	<p>La dirección Url que intenta acceder no es posible desde el método solicitado. Puede tratar de encontrar la página accediendo a:</p>
	
	<ul>
		<li>Página de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro menú o desde nuestro pie de página</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>