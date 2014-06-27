<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");    
include_once($models_url."limites_model.php");    
include_once($models_url."mensajes_model.php");    

/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Borrar Limite
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if (isset($_GET['delete'])){
	deleteLimite($_GET['delete']);
	echo getMensajes('delete', 'ok', 'Limite', $_GET['delete']);
}	 
	
	
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Nuevo Limite
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if(isset($_GET['insert'])){
	if(isset($_GET['suma_hora'])){
		$suma_hora=1;
	}else{
		$suma_hora=0;
	}
	
	$datos=array('limite'=>$_GET['limite'],
				'redondeo'=>$_GET['redondeo'],
				'suma_hora'=>$suma_hora);
	
	insertLimite($datos);
	echo getMensajes('insert', 'ok', 'Limite', $_GET['limite']);
	
}

/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Editar entradas existentes
----------------------------------------------------------------------
--------------------------------------------------------------------*/
if(isset($_GET['update'])){
	if(isset($_GET['suma_hora'])){
		$suma_hora=1;
	}else{
		$suma_hora=0;
	}
		
	$datos=array('id_limite'=>$_GET['id'],
				'limite'=>$_GET['limite'],
				'redondeo'=>$_GET['redondeo'],
				'suma_hora'=>$suma_hora);
	updateLimite($datos);
	echo getMensajes('update', 'ok', 'Limite', $_GET['limite']);				
}

$limite=getLimites();
$row_limite = mysql_fetch_assoc($limite);
$numero_limites = mysql_num_rows($limite);
	?>
	<div class="row">
	<div class="span12">
	<center>

	
<div ALIGN=left class="well">
	<a href='#' class='show_hide btn btn-primary' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
	<a href="javascript:imprSelec('muestra')" class='btn'><i class="icon-print"></i> Imprimir</a>
	<button class="btn" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
	<div class="pull-right"><h4>Limites</h4></div>
</div>
<br>

	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Nuevos Limites
----------------------------------------------------------------------
--------------------------------------------------------------------->		
<div class='slidingDiv'>
	<div class="span9">
	<form action="limites.php" method="get" > 
	<table class="table table-hover">
	<tr>
		<td>Limite</td>
		<td><input type="number" min="1" max="60" name="limite" placeholder="ingrese limite" required></td>
	</tr>
	<tr>
		<td>Redondeo</td>
		<td><input type="number" min="0" max="60" name="redondeo" placeholder="ingrese redondeo" required></td>		
	</tr>
	<tr>
	<tr>
		<td>Sumar hora</td>
		<td><input type="checkbox" name="suma_hora"></td>		
	</tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="insert" value="1"><i class="icon-plus-sign-alt"></i> Alta</button>
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
		<td>Limite</td>
		<td>Redondeo</td>
		<td>Sumar Hora</td>
		<td>Opciones</td>
	</tr>
	</thead>	
	<tbody>
	<?php do{ ?>
	<tr>
		<td><?php echo $row_limite['limite'];?></td>
		<td><?php echo $row_limite['redondeo'];?></td>
		<td><?php if($row_limite['suma_hora']==0){
					echo "-";
				}else{
					echo "SI";
				};?>
		</td>
		<td><A class="btn btn-primary" title="Editar limite" HREF="modificar_limite.php?id=<?php echo $row_limite['id_limite'];?>"><i class="icon-edit"></i></a>
			<a href="limites.php?delete=<?php echo $row_limite['id_limite'];?>" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-danger"><i class="icon-minus-sign"></i></a></td>
	</tr>
	<?php }while($row_limite=mysql_fetch_assoc($limite));?> 
	</tbody>
	</table>
	</div>
	</center>
	
	

	</div><!--Cierra el div class="celeste"-->
	</body>

	

<?php include_once("footer.php");?>
 
