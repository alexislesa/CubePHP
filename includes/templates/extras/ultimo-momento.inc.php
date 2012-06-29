<?php
/* Consulto por el último momento */
$ultimoMomento = '';

$oSql = "SELECT * FROM grilla WHERE campo_id = '1' LIMIT 0,1";
if ($res = $db->query($oSql)) {
	if ($db->num_rows($res)) {
		$rs = $db->next();
		if ($rs['campo_1'] != '') {
			$ultimoMomento = $rs['campo_1']; ?>
			
			<div class="ultimo">
				<b>Último momento:</b> <?php echo $ultimoMomento;?>
			</div>

		<?php }
	}
}
?>