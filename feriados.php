<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");    
include_once($url['models_url']."feriados_model.php");    
include_once($url['models_url']."mensajes_model.php");    

/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Borrar Feriado
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if (isset($_GET['eliminar'])){
	deleteFeriado($_GET['eliminar']);
	echo getMensajes('delete', 'ok', 'Feriado', $_GET['eliminar']);
}	 
	
	
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Nuevo Feriado
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if (isset($_GET['nuevo'])){
	$dia=date("Y-m-d", strtotime($_GET['dia']));
	$motivo=$_GET['feriado'];
	
	$feriado=getFeriados($dia);
	$row_feriado = mysql_fetch_assoc($feriado);
	$numero_feriados = mysql_num_rows($feriado);
	
	if($numero_feriados==0){
		insertFeriado($dia, $motivo);
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

 	$feriado=getFeriados();
	$row_feriado = mysql_fetch_assoc($feriado);
	$numero_feriados = mysql_num_rows($feriado);
	?>
	<div class="row">
	<div class="span12">
	<center>

	<!-- si hay modificacion o eliminacion de usuario se da aviso que se realizado exitosamente -->
	<?php 
	if(isset($_GET['nuevo'])){
		if($bandera==1){
			echo getMensajes('insert', 'ok', 'Feriado', $_GET['feriado']);	
		}else{
			echo getMensajes('insert', 'error', 'Feriado', $_GET['feriado']);	
		}
	}?>
	
	<div ALIGN=left class="well">
	<a href='#' class='show_hide btn btn-primary' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
	<a href="javascript:imprSelec('muestra')" class='btn btn-default'><i class="icon-print"></i> Imprimir</a>
	<button class="btn btn-default" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
	<div class="pull-right"><h4>Feriados</h4></div>
	</div>
	<br>

	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Nuevos Feriados
----------------------------------------------------------------------
--------------------------------------------------------------------->		
	<div class='slidingDiv'>
	<div class="well">
	<form action="feriados.php" method="get" > 
	<table class="table table-hover">
	<tr>
		<td>Ingrese día</td>
		<td><input type="text" name="dia" class="form-control" id="datepicker" placeholder="ingrese fecha" autocomplete="off" required></td>
	</tr>
	<tr>
		<td>Ingrese motivo del feriado</td>
		<td><input type="text" name="feriado" class="form-control" placeholder="ingrese feriado"></td>		
	</tr>
	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="nuevo" value="1"><i class="icon-plus-sign-alt"></i> Alta</button>
		<A class="show_hide btn btn-danger"  title="Cancelar" href='#'><i class="icon-ban-circle"></i> Cancelar</A></td>
		</td>
	</tr>
	</table>
	</form>
	</div>
	</div>
	
	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Tabla
----------------------------------------------------------------------
--------------------------------------------------------------------->	

	<center>
	<div id="muestra">
	<table border="1" class="table table-hover" id="example">
	<thead>
	<tr>
		<td>Día</td>
		<td>Feriado</td>
		<td>Eliminar</td>
	</tr>
	</thead>	
	<tbody>
	<?php do{ ?>
	<tr>
		<td><?php echo $row_feriado['dia'];?></td>
		<td><?php echo $row_feriado['feriado'];?></td>
		<td><a href="feriados.php?eliminar=<?php echo $row_feriado['id_feriado'];?>" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-danger"><i class="icon-minus-sign"></i></a></td>
	</tr>
	<?php }while($row_feriado=mysql_fetch_assoc($feriado));?> 
	</tbody>
	</table>
	</div>
	</center>
	
	

	</div><!--Cierra el div class="celeste"-->
	</body>

	

	
 
<?php include_once("footer.php");?>