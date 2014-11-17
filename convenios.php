<?php 
session_start();
include_once("control_usuario.php");
include_once("menu.php");    
include_once($url['models_url']."convenios_model.php");   
include_once($url['models_url']."mensajes_model.php");   
$bandera=0;

/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Borrar Convenio
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if (isset($_GET['eliminar'])){
	deleteConvenio($_GET['eliminar']);
}	 


/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Editar Convenio
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if (isset($_GET['modificar'])){
	updateConvenio($_GET['id'], $_GET['convenio']);
}	 
	
	
	
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Nuevo Convenio
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if (isset($_GET['nuevo'])){
		
	$convenio=getConvenios($_GET['convenio'], 'convenio');
	$row_convenio = mysql_fetch_assoc($convenio);
	$numero_convenios = mysql_num_rows($convenio);
		
	if($numero_convenios==0){	
		$id=insertConvenio(	$_GET['convenio']);
		$bandera=1;
	}else{
		$bandera=0;
	}
}
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Editar entradas existentes
----------------------------------------------------------------------
--------------------------------------------------------------------*/

	$convenio=getConvenios();
	$row_convenio = mysql_fetch_assoc($convenio);
	$numero_convenios = mysql_num_rows($convenio);
	?>
	<!-- si hay modificacion o eliminacion de convenio se da aviso que se realizado exitosamente -->
	<?php 
	if($bandera==1 && isset($_GET['nuevo'])){
		echo getMensajes('insert', 'ok', 'Convenio', $_GET['convenio']);
	}else if($bandera==0 && isset($_GET['nuevo'])) { 
		echo getMensajes('insert', 'error', 'Convenio', $_GET['convenio']);
	}else if(isset($_GET['modificar'])){
		echo getMensajes('update', 'ok', 'Convenio', $_GET['convenio']);
	}else if(isset($_GET['eliminar'])){
		echo getMensajes('delete', 'ok', 'Convenio', $_GET['eliminar']);
	}
	?>
	
<div class="row">
<div class="col-md-12">
	<p class="block-title">Convenios</p>
	<div>
		<a href='#' class='show_hide btn btn-primary' rel='tooltip' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
		<a href="javascript:imprSelec('muestra')" class='btn btn-default'><i class="icon-print"></i> Imprimir</a>
		<button class="btn btn-default" onclick="tableToExcel('example', 'Convenios')"><i class="icon-download-alt"></i> Excel</button>
	</div>
<div class="divider"></div>

	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Nuevos Feriados
----------------------------------------------------------------------
--------------------------------------------------------------------->		
	<div class='slidingDiv'>
	<form action="convenios.php" method="get" > 
	<table class="table table-hover">
	<tr>
		<td>Convenio</td>
		<td><input type="text" name="convenio" class="form-control" placeholder="ingrese convenio" maxlength="32" required></td>
	</tr>
	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="nuevo" value="1"><i class="icon-plus-sign-alt"></i> Alta</button>
		<A class="show_hide btn btn-danger"  rel='tooltip' title="Cancelar" href='#'><i class="icon-ban-circle"></i> Cancelar</A></td>
		</td>
	</tr>
	</table>
	</form>
	<div class="divider"></div>
	</div>
	
	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Tabla
----------------------------------------------------------------------
--------------------------------------------------------------------->	

	<center>
	<div id="muestra">
	<table class="table table-hover" id="example">
	<thead>
		<tr>
			<td>Convenio</td>
			<td>Semanales</td>
			<td>Sábado</td>
			<td>Salida sábado</td>
			<td>Turnos</td>
			<td>Eliminar</td>
		</tr>
	</thead>	
	<tbody>
	<?php do{ ?>
	<tr>
		<td><?php echo $row_convenio['convenio'];?></td>
		<td><?php echo $row_convenio['semana'];?></td>
		<td><?php echo $row_convenio['sabado'];?></td>
		<td><?php echo $row_convenio['salida_sabado'];?></td>
		<td>
			<a class="btn btn-default" href="convenios_turno.php?id=<?php echo $row_convenio['id_convenio']?>"><i class="icon-folder-open-alt"></i> Ver</a>		
		</td>
		<td>
		<A class="btn btn-primary" rel='tooltip' title="Editar convenio" HREF="modificar_convenio.php?id=<?php echo $row_convenio['id_convenio'];?>&action=1"><i class="icon-edit"></i></A>
		<?php if ($row_convenio['id_estado']==0) {?>
		<A type="submit" class="btn btn-danger disabled"  rel='tooltip' title="El convenio ya esta dado de baja"><i class="icon-minus-sign"></i></i></A>
		<?php } else { ?>
		<a href="convenios.php?eliminar=<?php echo $row_convenio['id_convenio'];?>" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-danger"><i class="icon-minus-sign"></i></a>
		<?php } ?>
		</td>
	</tr>
	<?php }while($row_convenio=mysql_fetch_assoc($convenio));?> 
	</tbody>
	</table>
	</div>
	</center>
	
	

	</div><!--Cierra el div class="celeste"-->
	</body>

	

	
<?php include_once("footer.php");?>
