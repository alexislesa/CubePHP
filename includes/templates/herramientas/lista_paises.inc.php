<?php
/**
 * Opciones de manejo de paises
 * 
 * Muestra selectores de pais, provincia, departamento y localidad
 * Se puede modificar la ubicación de la lista de paises, 
 * el ícono de preload y que opción muestra de las ubicaciones  (pais, prov, loc, dto, etc)
 */

/**
 * Url del JS con la lista de paises
 *
 * @var string
 */
$ubicacionJS = !empty($ubicacionJS) 
			? $ubicacionJS 
			: '/extras/js/ubicaciones.php';

/**
 * HTML de la imagen de preload
 * 
 * @var string
 */
$ubicacionIconLoading = isset($ubicacionIconLoading) 
						? $ubicacionIconLoading 
						: "<img src='/images/loader.gif' width='16' height='16' />";

/**
 * Indica si muestra el listado de paises o no
 *
 * @var boolean
 */
$ubicacionPaisView = isset($ubicacionPaisView) 
					? $ubicacionPaisView 
					: true;

/**
 * Id del pais seleccionado por defecto
 *
 * @var integer
 */
$ubicacionPaisDefault = isset($ubicacionPaisDefault) 
						? $ubicacionPaisDefault 
						: 0;

/**
 * Id de País seleccionado o por defecto
 *
 * @var integer
 */
$ubicacionPaisSelected = !empty($dataToSkin['pais']) 
						? $dataToSkin['pais'] 
						: $ubicacionPaisDefault;

/**
 * Indica si muestra el listado de provincias
 *
 * @var boolean
 */
$ubicacionProvinciaView = isset($ubicacionProvinciaView) 
						? $ubicacionProvinciaView 
						: true;

/**
 * Id de la provincia seleccionada por defecto
 *
 * @var integer
 */
$ubicacionProvinciaDefault = isset($ubicacionProvinciaDefault) 
							? $ubicacionProvinciaDefault 
							: 0;

/**
 * Id de Provincia seleccionada o por defecto
 *
 * @var integer
 */
$ubicacionProvinciaSelected = !empty($dataToSkin["provincia"]) 
							? $dataToSkin["provincia"] 
							: $ubicacionProvinciaDefault;

/**
 * Indica si muestra el listado de departamentos
 *
 * @var boolean
 */
$ubicacionDepartamentoView = isset($ubicacionDepartamentoView ) 
							? $ubicacionDepartamentoView 
							: true;

/**
 * Id del departamento seleccionado por defecto
 *
 * @var integer
 */
$ubicacionDepartamentoDefault = isset($ubicacionDepartamentoDefault) 
								? $ubicacionDepartamentoDefault 
								: 0;

/**
 * Id de Departamento seleccionado o por defecto
 *
 * @var integer
 */
$ubicacionDepartamentoSelected = !empty($dataToSkin["departamento"]) 
								? $dataToSkin["departamento"] 
								: $ubicacionDepartamentoDefault;

/**
 * Indica si muestra listado de localidades
 *
 * @var boolean
 */
$ubicacionLocalidadView = isset($ubicacionLocalidadView) 
						? $ubicacionLocalidadView 
						: true;

/**
 * Id de la localidad por defecto
 *
 * @var integer
 */
$ubicacionLocalidadDefault = isset($ubicacionLocalidadDefault) 
							? $ubicacionLocalidadDefault 
							: 0;

/**
 * Id de localidad seleccionada o por defecto
 *
 * @var integer
 */
$ubicacionLocalidadSelected = !empty($dataToSkin["localidad"]) 
							? $dataToSkin["localidad"] 
							: $ubicacionLocalidadDefault;
?>
 
<script language='JavaScript' type='text/javascript'>

function check_opciones(datos, item, select) {

	// Evita que liste los paises en donde no debe
	if (item != 0 ) {
	
		var element = datos[0];
	
		$("#" + element + "_block").show();
		$("#" + element + "_load").html("<?php echo $ubicacionIconLoading;?>");
	
		$.getJSON("<?php echo $ubicacionJS;?>",{id: item}, function(j){
			if (j.length) {

				insert_opciones (j, element);

				for (var i=1; i<datos.length; i++) {
					$("#" + datos[i] + "_block").show();
					insert_opciones ("", datos[i]);
				}
				
			} else {
				for (var i=0; i<datos.length; i++) {
					$("#" + datos[i] + "_block").hide();
					$("#" + datos[i] + "_txt").val("");
				}
			}

			$("#" + element + "_load").html("");
			check_loc();
		});
	} else {
		for (var i=0; i<datos.length; i++) {
			$("#" + datos[i] + "_block").hide();
			$("#" + datos[i] + "_txt").val("");
		}
	}
}

function insert_opciones(datos, item, select) {

	select = (select == null) ? 0 : select;
	
	$("#" + item + "_block").show();
	
	var options = '<option value="">' + $("#" + item + "_sel option:first").text() +'</option>';
	for (var i = 0; i < datos.length; i++) {
		sel = (select == datos[i].id) ? "selected" : "";
		options += '<option value="' + datos[i].id + '" ' + sel + '>' + datos[i].nombre + '</option>';
	}
	
	$("#" + item + "_sel").html(options);
	if (!select) {
		$("#" + item + "_sel option:first").attr('selected', 'selected');
		$("#" + item + "_txt").val("");
	} else {
		$("#" + item + "_txt").val( 
				$("#" + item + "_sel :selected").text()
		);
	}
}

function check_val(item) {
	if ($("#" + item + "_sel").val() != "") {
		$("#" + item + "_txt").val( 
			$("#" + item + "_sel :selected").text()
		);
	} else {
		$("#" + item + "_txt").val("");
	}
}

function check_loc() {
	if ($("#localidad_block").css("display") == "none") {
		$("#localidad2_block").show();
	} else {
		$("#localidad2_block").hide();
	}
	$("#localidad2_sel").val("");
	$("#localidad_txt").val("");
}

<?php 
// Scripts a ejecutar si esta habilitado para mostrar el pais
if ($ubicacionPaisView) { ?>

	$("#pais_sel").change(function() {

		datos = new Array();
		datos[0] = "provincia";
		datos[1] = "departamento";
		datos[2] = "localidad";
		
		check_opciones (datos, $(this).val());
		check_val("pais");
	});
	
	$.getJSON("<?php echo $ubicacionJS;?>?id=0", function(j){
		if (j.length) {
			insert_opciones (j, "pais", <?php echo $ubicacionPaisSelected ? $ubicacionPaisSelected : "''";?>);
		}
	});	

<?php } ?>

<?php 
// Script a ejecutar si esta habilitado la provincia
if ($ubicacionProvinciaView) { ?>

	$("#provincia_sel").change( function() {
		datos = new Array();
		datos[0] = "departamento";
		datos[1] = "localidad";

		check_opciones (datos, $(this).val());
		check_val("provincia");
	} );

	<?php if ($ubicacionProvinciaSelected || !$ubicacionPaisView || $ubicacionPaisSelected) { ?>
 
		$.getJSON("<?=$ubicacionJS;?>?id=<?=$ubicacionPaisSelected;?>", function(j){
			if (j.length) {
				insert_opciones (j, "provincia", <?=$ubicacionProvinciaSelected ? $ubicacionProvinciaSelected : "''";?>);
			}
		
		});
	<?php } ?>
<?php } ?>

<?php 
// Script a ejecutar si esta habilitado el departamento
if ($ubicacionDepartamentoView) { ?>

	$("#departamento_sel").change( function() {
		datos = new Array();
		datos[0] = "localidad";

		check_opciones (datos, $(this).val());
		check_val("departamento");

	} );
	
	<?php if ($ubicacionDepartamentoSelected || !$ubicacionProvinciaView || $ubicacionProvinciaSelected) { ?>
 
		$.getJSON("<?=$ubicacionJS;?>?id=<?=$ubicacionProvinciaSelected;?>", function(j){
			if (j.length) {
				insert_opciones (j, "departamento", <?=$ubicacionDepartamentoSelected ? $ubicacionDepartamentoSelected : "''";?>);

			}
		});

	<?php } ?>

<?php } ?>

<?php 
// Script a ejecutar si esta habilitado la localidad
if ($ubicacionLocalidadView) { ?>

	$("#localidad_sel").change(function() {
		check_val("localidad");
	});
	
	$("#localidad2_sel").change(function() {
		$("#localidad_txt").val($(this).val());
	});
	
	<?php if ($ubicacionLocalidadSelected || !$ubicacionDepartamentoView || $ubicacionDepartamentoSelected) { ?>
 
		$.getJSON("<?=$ubicacionJS;?>?id=<?=$ubicacionDepartamentoSelected;?>", function(j){
			if (j.length) {
				insert_opciones (j, "localidad", <?=$ubicacionLocalidadSelected ? $ubicacionLocalidadSelected : "''";?>);
			}
		});
	 
	<?php } ?>

<?php } ?>
 
</script>