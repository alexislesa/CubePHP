<?
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
 * <?=$dataToSkin["nombre"];?>	Devuelve el nombre ingresado en el formulario
 * <?=$dataToSkin["email"];?>	Devuelve el email ingresado en el formulario 
 * 
 *
 *
 * @changelog:
 */
?>
 
<span>
	Su recomendación ha sido enviada con exito a: <u><?=$dataToSkin["email"];?></u>
</span>

<a href="javascript:self.close();">Cerrar</a>