<?php  
include_once("head.php");    
include_once($url['models_url']."updates_model.php");
include_once($url['models_url']."usuarios_sistema_model.php");   

	$updates			= getUpdate($_GET['id']);
	$row_update			= mysql_fetch_assoc($updates);
	$cantidad_update	= mysql_num_rows($updates);
   
?>
<body>
<div class="container; celeste">
	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Editar entradas existentes
----------------------------------------------------------------------
--------------------------------------------------------------------->		

	
	<fieldset>
	<legend>Actualizacion:</legend>
		<table class="table">
			<?php do{ ?>
				<tr>
					<td> Comienzo </td>
					<td><?php echo date('d-m-Y', strtotime($row_update['start_date']))?></td>
				</tr>
				<tr>
					<td> Final </td>
					<td><?php echo date('d-m-Y', strtotime($row_update['end_date']))?></td>
				</tr>
				<tr>
					<td> Reloj </td>
					<td bgcolor="<?php echo $row_update['color']?>" style="color:#FFF"><?php echo $row_update['reloj']?></td>
				</tr>
				<tr>
					<td> Registros </td>
					<td><?php echo $row_update['cantidad_registros']?></td>
				</tr>
				<tr>
					<td> Fecha de ejecución </td>
					<td><?php echo date('d-m-Y H:i:s', strtotime($row_update['fecha_update']))?></td>
				</tr>
				<tr>
					<td> Tipo </td>
					<td><?php 
							if($row_update['id_tipo']==1){
								echo "Programada";
							}else{
								echo "Manual";
							}
						?>
					</td>
				</tr>
				<?php if($row_update['id_tipo']==2){ ?>
				<tr>
					<td> Usuario </td>
					<td><?php 
						$usuarios			= getUsuario_sistema($row_update['id_usuario']);
						$row_usuario		= mysql_fetch_assoc($usuarios);
						$cantidad_usuario	= mysql_num_rows($usuarios);
					echo $row_usuario['usuario_nombre'];?></td>
				</tr>
				<?php } ?>
			<?php }while($row_update = mysql_fetch_array($updates)); ?>
		</table>
		<a class='btn btn-danger' href='' rel='tooltip' title='Volver' onClick='window.close()'>Volver</a>
	</fieldset>
</div>
</body>
