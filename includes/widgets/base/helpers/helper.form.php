<?php
/**
 * CubePHP
 *
 * Framework de Desarrollo 
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>08.04.2012 <br/>
 *	- Modify: Se optimizaron las funciones del helpers. 
 *	Se actualiz� la documentaci�n de las funciones. </li>
 *
 * <li<08.03.2012 <br/>
 * - Modify: Se modific� la funci�n processForm para incorporarle opciones 
 *		personalizadas de estilos o formas de uso. </li>
 *
 * <li>21.01.2012 <br/>
 *	- Creaci�n de Helpers Time.</li>
 * </ul>
 *
 * @package		CubePHP
 * @subpackage	helpers 
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @licence 	Comercial
 * @version 	0.90
 */

/**
 * Genera los options del input select con el rango de mes indicado
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	form
 * @param		integer	$inicio	Mes de inicio a listar (1: enero... 12:diciembre)
 * @param		integer	$final	Mes final a listar (1: enero... 12:diciembre)
 * @param		integer	$actual	Mes actual que deber�a aparecer seleccionado
 */
function mesRange($inicio, $final ,$actual=false) {
	global $mes_txt;
	for ($a=$inicio; $a<=$final; $a++) {
		$sel = ($a==$actual) ? 'selected' : '';
		echo "<option value='{$a}' {$sel}>{$mes_txt[$a]}</option>\n";
	}
}
 
/**
 * Genera los options del input select con un rango de n�meros indicados
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	form
 * @param		integer	$inicio	N�mero de inicio desde el cual voy a listar
 * @param		integer	$final	N�mero de finalizaci�n hasta el cual voy a listar
 * @param		integer	$actual	N�mero actual que deber�a aparecer seleccionado
 */
function optionRange($inicio,$final ,$actual = false) {
	for ($a=$inicio; $a<=$final; $a++) {
		$sel = ($a==$actual) ? 'selected' : '';
		echo "<option value='{$a}' {$sel}>{$a}</option>\n";
	}
}

/**
 * Genera el HTML de la validaci�n del formulario
 *
 * Requiere la funcion de jQuery: RSV 
 * Ver funci�n en: http://www.benjaminkeen.com/software/rsv/jquery/
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	form
 * @param		string	$name		Nombre de formulario a procesar
 * @param		array	$fields		Matriz de validaciones a procesar
 * @param		string	$tipo		Indica como se procesa el formulario.
 *									- alert-one: mensaje de alerta por cada error
 * @param		string	$callBack	Funci�n o comando JS a procesar una vez finalizado con �xito.
 * @param		array	$opts		Array de datos a agregar al proceso de formaulario
 */
function processForm($name, $fields, $tipo='alert-one', $callBack=false, $opts=false) {
	$nombre = 'r'.substr(md5($name),0,5); 
	$callBack = ($callBack) ? $callBack : 'return true';

	$optTxt = '';
	if (!empty($opts) && is_array($opts)) {
		foreach($opts as $k => $v) {
			$optTxt.= $k.':';
			$optTxt.= ($k != 'customErrorHandler') ? '"'.$v.'"' : $v;
			$optTxt.= ',';
		}
	}
	?>

	<script type="text/javascript">

		var <?php echo $nombre;?> = [];
	
		<?php foreach ($fields as $k => $v) { 
			$v = trim($v);
			$v = str_replace(' ,', ',', $v);
			$v = str_replace(', ', ',', $v);

			echo $nombre.".push('".$v."'); \n";
		} ?>
	 
		$(document).ready(function() {
			$("#<?php echo $name;?>").RSV({
				onCompleteHandler: myOnComplete,
				displayType: "<?php echo $tipo;?>",
				<?php echo $optTxt;?>
				rules: <?php echo $nombre;?>
			});
		});	
	

		function myOnComplete() {
			<?php echo $callBack;?>;
		}
 
	</script>

<?php }
?>