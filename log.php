<?php
session_start();
include_once("control_usuario.php");
include_once("menu.php");
include_once($url['models_url']."logs_model.php");

//si no hay busqueda traemos los ultimos 50 movimientos
$usuario=getLogs();
$row_usuario = mysql_fetch_assoc($usuario);
$numero_filas = mysql_num_rows($usuario);
?>

<div class="row">
<div class="col-md-12">
	<p class="block-title">Logs</p>
	<div>
		<a href="javascript:imprSelec('muestra')" class='btn btn-default'><i class="icon-print"></i> Imprimir</a>
		<button class="btn btn-default" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
	</div>
<div class="divider"></div>


<div id="muestra">
<table class="table table-hover" id="example">
<thead>
	<tr>
		<td>Fecha</td>
		<td>Hora</td>
		<td>Acción</td>
		<td>Usuario</td>
		<td rel='tooltip' title="número que identifica al usuario en base de datos">ID</td>
		<td>Operación</td>
	</tr>
<thead>

<tbody>
	<?php do{ ?>
	<tr>
		<?php
		if($row_usuario['Accion']=="Insert"){
			$action="Alta";
		}else if($row_usuario['Accion']=="Delete"){
			$action="Borrar";
		}else if($row_usuario['Accion']=="Update"){
			$action="Modificar";
		}
		
		
		?>
		<td><?php echo date( "d-m-Y", strtotime( $row_usuario['Creacion'] ) );  ?></td><!-- Cambio de formato de fecha -->
		<td><?php echo date( "H:i:s", strtotime( $row_usuario['Creacion'] ) );  ?></td><!-- Cambio de formato de hora  -->
		<td><?php echo $action;?></td>
		<td><?php echo $row_usuario['Usuario'];?></td>
		<td><?php echo $row_usuario['idusuario'];?></td>
		<td><A class="btn btn-primary" rel='tooltip' title="Ver accion" onClick="abrirVentana('edit_cliente.php?id=<?php echo $row_usuario['id_log_usuario'];?>')"><i class="icon-circle-arrow-right"></i> </A></td>
	</tr>
	<?php }while ($row_usuario = mysql_fetch_array($usuario)) ?>
</tbody>

</table>
</div>
</center>
</div>


<?php include_once("footer.php");?>