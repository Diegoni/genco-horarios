<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");    
include_once($models_url."convenios_model.php");   
include_once($models_url."mensajes_model.php");   

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
	updateConvenio(
			$_GET['id'],
			$_GET['convenio'],
			$_GET['semana'],
			$_GET['sabado'],
			$_GET['salida_sabado'],
			$_GET['estado']
	);
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
		insertConvenio(	$_GET['convenio'],
										$_GET['semana'],
										$_GET['sabado'],
										$_GET['salida_sabado']);
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
	<div class="span9">
	<center>

	<!-- si hay modificacion o eliminacion de convenio se da aviso que se realizado exitosamente -->
	<? 
	if($bandera==1 && isset($_GET['nuevo'])){
		echo getMensajes('insert', 'ok', 'Convenio', $_GET['convenio']);
	}else if($bandera==0 && isset($_GET['nuevo'])) { 
		echo getMensajes('insert', 'error', 'Convenio', $_GET['convenio']);
	}else if(isset($_GET['modificar'])){
		echo getMensajes('update', 'ok', 'Convenio', $_GET['convenio']);
	}else if(isset($_GET['eliminar'])){
		echo getMensajes('delete', 'ok', 'Convenio', $_GET['convenio']);
	}
	?>
	
	<div ALIGN=left>
	<a href='#' class='show_hide btn btn-primary' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
	<a href="javascript:imprSelec('muestra')" class='btn'><i class="icon-print"></i> Imprimir</a>
	<button class="btn" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
	</div>
	<br>

	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Nuevos Feriados
----------------------------------------------------------------------
--------------------------------------------------------------------->		
	<div class='slidingDiv'>
	<div class="span9">
	<form action="convenios.php" method="get" > 
	<table class="table table-hover">
	<tr>
		<td>Convenio</td>
		<td><input type="text" name="convenio" placeholder="ingrese convenio" required></td>
	</tr>
	<tr>
		<td>Horas semana</td>
		<td><input type="number" name="semana" placeholder="hs diarias que trabaja semanalmente" required></td>
	</tr>
	<tr>
		<td>Horas sábado</td>
		<td><input type="number" name="sabado" placeholder="hs que debe trabajar los sábados" required></td>
	</tr>
	<tr>
		<td>Horario salida sábado</td>
		<td><input type="number" name="salida_sabado" placeholder="hora debe salir el sábado" required></td>
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
		<td>Convenio</td>
		<td>Semanales</td>
		<td>Sábado</td>
		<td>Salida sábado</td>
		<td>Eliminar</td>
	</tr>
	</thead>	
	<tbody>
	<? do{ ?>
	<tr>
		<td><?= $row_convenio['convenio'];?></td>
		<td><?= $row_convenio['semana'];?></td>
		<td><?= $row_convenio['sabado'];?></td>
		<td><?= $row_convenio['salida_sabado'];?></td>
		<td>
		<A class="btn btn-primary" title="Editar convenio" HREF="modificar_convenio.php?id=<? echo $row_convenio['id_convenio'];?>&action=1"><i class="icon-edit"></i></A>
		<?if ($row_convenio['id_estado']==0) {?>
		<A type="submit" class="btn btn-danger disabled"  title="El convenio ya esta dado de baja"><i class="icon-minus-sign"></i></i></A>
		<? } else { ?>
		<a href="convenios.php?eliminar=<?= $row_convenio['id_convenio'];?>" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-danger"><i class="icon-minus-sign"></i></a>
		<? } ?>
		</td>
	</tr>
	<? }while($row_convenio=mysql_fetch_assoc($convenio));?> 
	</tbody>
	</table>
	</div>
	</center>
	
	

	</div><!--Cierra el div class="celeste"-->
	</body>

	

	
 
