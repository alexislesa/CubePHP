<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "M�todo no permitido";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">M�todo no permitido</h2>

	<p>La direcci�n Url que intenta acceder no es posible desde el m�todo solicitado. Puede tratar de encontrar la p�gina accediendo a:</p>
	
	<ul>
		<li>P�gina de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro men� o desde nuestro pie de p�gina</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>