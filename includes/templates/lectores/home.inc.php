<?
/**
 * Muestra el estritorio del usuario
 */
include (dirTemplate.'/lectores/objetos/profile.inc.php');
?>

<article>
	
	<header><h2 id="title"><?php echo $usr->campos['lector_usuario'];?></h2></header>
	
	<figure><img src="<?php echo $usr->campos['lector_avatar'];?>" width="40" height="40" alt="" title="" /></figure>
	
	<p>
		Miembro desde:
		<span><?php echo date('d/m/Y', $usr->campos['lector_fregistro']);?></span>
	</p>

	<p class="activity legend">
		<h5>Actividad Reciente:</h5>
	</p>

</article>