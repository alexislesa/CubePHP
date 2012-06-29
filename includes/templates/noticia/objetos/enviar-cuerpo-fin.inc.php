<?php
/**
 * ***************************************************************
 * @package		GUI-WebSite
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory
 * @licence 	Comercial
 * @version 	1.0
 * @revision 	24.08.2010
 * *************************************************************** 
 *
 * Muestra la pantalla que el envío del artículo se realizó correctamente
 *
 * Puede recuperarse cualquier de los campos utilizados en el formulario,
 * utilizando la siguiente forma:
 * <?php echo $dataToSkin["nombre"];?>	Devuelve el nombre ingresado en el formulario
 * <?php echo $dataToSkin["email"];?>	Devuelve el email ingresado en el formulario 
 * 
 * @changelog:
 */
?>
 
<span>
	Su recomendación ha sido enviada con exito a: <u><?php echo $dataToSkin["email"];?></u>
</span>

<a href="#" onclick="closepopup();">Cerrar</a>

<script type="text/javascript">
/* Revisa si estoy en popup o ligthbox y cierro la ventana */ 
function closepopup() {
	if (top.location.href == self.location.href) {
		self.close();
	} else {
		window.top.tb_remove();
	}
};
</script>