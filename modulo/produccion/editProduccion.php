<?PHP
include '../../adodb5/adodb.inc.php';
include '../../classes/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$fecha = $op->ToDay();
$hora = $op->Time();

$id = $_REQUEST['id'];

$sql = "SELECT * FROM produccion WHERE id_produccion = '$id'";

$str = $db->Execute($sql);

$row = $str->FetchRow();
?>
<script>

	$(document).ready(function(e) {
		function log( message ) {
			//alert(message);
			$( "input#detalle" ).val( message );
			//$( "input#idInv" ).val( message );
			//$( "#log" ).scrollTop( 0 );
		}
		$( "#idInv" ).autocomplete({
			source: "classes/searchProd.php",
			minLength: 2,
			select: function( event, ui ) {
				log(ui.item.id
					/*ui.item ?
					 "Selected: " + ui.item.value + " aka " + ui.item.id :
					 "Nothing selected, input was " + this.value*/
				);
			}
		});
		/* idealForm */
		$('#form').idealForms();
		/* Calendario */
		$('#dateNac').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			yearRange: 'c-40:c-0'
		});
		/* Validación */
		jQuery("#form").validationEngine({
			prettySelect	: true,
			useSuffix		: "_chosen"
			// scroll		: false,
		});

	});
</script>
<style>
	form div.WrapCOD {
		width: 100px;
	}

	form div.WrapDET {
		width: 360px;
	}
	input#cant[type="text"] {
		margin: 0px;
		width: 6em;
	}
	input#detalle[type="text"] {
		width: 29.5em;
	}
</style>
<?PHP
if($row['statusProd'] == 'Nueva Orden') {
	?>
	<form id="form" class="ideal-form" action="javascript:saveOrdenP('form','update.php')">
		<fieldset>
			<legend>E D I T A R&nbsp;&nbsp;&nbsp;O R D E N&nbsp;&nbsp;&nbsp;D E&nbsp;&nbsp;&nbsp;P R O D U C I &Oacute; N</legend>
			<div class="idealWrap WrapDS">
				<label class="date">Fecha Inicio: </label>
				<input id="fecha" name="fecha" type="text" value="<?= $fecha; ?> <?= $hora; ?>" disabled="disabled"/>
				<input id="date" name="date" type="hidden" value="<?= $fecha; ?> <?= $hora; ?>"/>
				<input id="id" name="id" type="hidden" value="<?=$row['id_produccion']?>"/>
			</div><!--End idealWrap-->
			<div class="clearfix"></div>
			<br>

			<div class="idealWrap WrapCOD">
                <label>Codigo: </label>
				<input id="idInv" name="idInv" type="text" placeholder="Codigo"
					   class="validate[required,maxSize[20],custom[onlyLetterSpacio]] text-input"
					   value="<?= $row['id_inventario'] ?>"/>
			</div><!--End idealWrap-->

			<div class="idealWrap WrapDET">
                <label>Detalle: </label>
				<input id="detalle" name="detalle" type="text" placeholder="Nombre producto"
					   value="<?= $row['detalle'] ?>" class="validate[required] text-input" autocomplete="off"/>
			</div><!--End idealWrap-->

			<div class="idealWrap WrapCOD">
                <label>Cantidad: </label>
				<input id="cant" name="cant" type="text" placeholder="Cantidad" value="<?= $row['cantidad'] ?>"
					   class="validate[required, custom[number]] text-input"/>
			</div><!--End idealWrap-->

			<!--<div class="idealWrap WrapCOD">
            <input id="vol" name="vol" type="text" placeholder="Volumen" value="" class="validate[required, custom[number]] text-input" />
            </div><!--End idealWrap-->

		</fieldset>
		<div class="idealWrap" align="center">
			<input type="reset" id="reset" value="Limpiar..."/>
			<input type="submit" id="save" value="Guardar..."/>
		</div>

	</form>
	<?PHP
}else {
	?>
	<p>EN ESTE MOMENTO NO SE PUEDE EDITAR LA ORDEN</p>
	<?PHP
}
?>
<div class="clearfix"></div>