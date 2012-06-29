<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "Fallo de precondici�n";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">Fallo de precondici�n</h2>
	
	<p>El servidor no cumple con una de las condiciones previas que la p�gina solicitada requiere. Puede tratar de encontrar la p�gina accediendo a:</p>
	
	<ul>
		<li>P�gina de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro men� o desde nuestro pie de p�gina</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>