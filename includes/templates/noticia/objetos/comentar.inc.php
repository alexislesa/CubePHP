<?php
/**
 * Carga las opciones de Comentarios de un art�culo
 * Muestra el bloque solo si esta habilitado para comentar
 */

// Comentario desde facebook
// include (dirTemplate."/{$pathRelative}/objetos/comentar-inc-fb.inc.php");

// Comentario con plataforma propia de usuario registrados
// include (dirTemplate."/{$pathRelative}/objetos/comentar-inc-registrados.inc.php");

// Comentario con plataforma propia de usuarios an�nimos
include (dirTemplate."/{$pathRelative}/objetos/comentar-inc-sin-registro.inc.php");

// Listado de comentarios de los usuarios registrados o an�nimos
include (dirTemplate."/{$pathRelative}/objetos/comentario.inc.php");
?>