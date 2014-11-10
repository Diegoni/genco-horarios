<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($url['models_url']."relojes_model.php");
include_once($url['models_url']."mensajes_model.php");


/*--------------------------------------------------------------------
----------------------------------------------------------------------
			ABM empresas
----------------------------------------------------------------------			
--------------------------------------------------------------------*/
if(isset($_GET['delete'])){
	deleteReloj($_GET['delete']);
	echo getMensajes('delete', 'ok', 'Reloj', $_GET['id']);
}

//modifica al reloj segun el formulario de modificar.php
if(isset($_GET['update'])){
	$datos=array(
				'reloj'		=> $_GET['reloj'], 
				'ip'		=> $_GET['ip'],
				'puerto'	=> $_GET['puerto'],
				'color'		=> $_GET['color'],
				'id_reloj'	=> $_GET['id']);
	updateReloj($datos);
	echo getMensajes('update', 'ok', 'Reloj', $_GET['reloj']);
}

//modifica al reloj segun el formulario de modificar.php
if(isset($_GET['nuevo'])){
	$relojes		= getRelojes($_GET['ip'], 'ip');
	$row_reloj		= mysql_fetch_assoc($relojes);
	$numero_reloj	= mysql_num_rows($relojes);
	
	if($numero_reloj>0){
		echo getMensajes('insert', 'error', 'Reloj', $_GET['reloj']);
	}else{
		$datos=array(
				'reloj'		=> $_GET['reloj'], 
				'ip'		=> $_GET['ip'],
				'puerto'	=> $_GET['puerto'],
				'color'		=> $_GET['color']);		
		
		insertReloj($datos);
		echo getMensajes('insert', 'ok', 'Reloj', $_GET['reloj']);
	}
}

/*--------------------------------------------------------------------
----------------------------------------------------------------------
			Consulta para traer los relojes
----------------------------------------------------------------------			
--------------------------------------------------------------------*/
	$relojes		= getRelojes();
	$row_reloj 		= mysql_fetch_assoc($relojes);
	$numero_relojes = mysql_num_rows($relojes);
	
?>
<div class="row">
<div class="col-md-12">
	<p class="block-title">Relojes</p>
	<div>
		<a href='#' class='show_hide btn btn-primary' rel='tooltip' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
		<a href="javascript:imprSelec('muestra')" class='btn btn-default'><i class="icon-print"></i> Imprimir</a>
		<button class="btn btn-default" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
	</div>
<div class="divider"></div>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario nuevo departamento
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class='slidingDiv'>
	
<form class="form-inline" action="relojes.php">
<table class="table table-hover">

	<tr>
		<td>Reloj</td>
		<td><input type="text" name="reloj" class="form-control" placeholder="ingrese reloj" required></td>
	</tr>

	<tr>
		<td>IP</td>
		<td><input type="text" name="ip" class="form-control" placeholder="ingrese ip" required></td>
	</tr>

	<tr>
		<td>Puerto</td>
		<td><input type="text" name="puerto" class="form-control" placeholder="ingrese puerto" required></td>
	</tr>
	
	<tr>
		<td>Color</td>
		<td><input type="text" name="color" class="form-control" placeholder="ingrese color" required></td>
	</tr>

	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="nuevo" value="1" rel='tooltip' title="Alta registro"><i class="icon-plus-sign-alt"></i> Alta</button>
		<A class="show_hide btn btn-danger"  rel='tooltip' title="Cancelar" href='#'><i class="icon-ban-circle"></i> Cancelar</A></td>
	</tr>  
	
</table>
</form>
<div class="divider"></div>
</div>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Tabla de usuario
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<div id="muestra">
<table class="table table-hover" id="example">

<!-- Cabecera -->
<thead>
	<tr>
	<td>Reloj</td>
	<td>IP</td>
	<td>Puerto</td>
	<td>Estado</td>
	<td>Operación</td>
	</tr>
<thead>
<tbody>
<?php do{ ?>
<tr>
<td><?php echo $row_reloj['reloj'];?></td>
<td><?php echo $row_reloj['ip'];?></td>
<td><?php echo $row_reloj['puerto'];?></td>
<td>
	<?php if ($row_reloj['id_estado']==0) {?>
		baja
	<?php } else { ?>
		activa
	<?php } ?>
</td>
<td><A class="btn btn-primary" rel='tooltip' title="Editar registro" HREF="modificar_reloj.php?id=<?php echo $row_reloj['id_reloj'];?>&action=1"><i class="icon-edit"></i></A>
	<?php if ($row_reloj['id_estado']==0) {?>
	<A type="submit" class="btn btn-danger disabled"  rel='tooltip' title="El reloj ya esta dada de baja"><i class="icon-minus-sign"></i></i></A>
	<?php } else { ?>
	<A type="submit" class="btn btn-danger" rel='tooltip' title="Dar de baja" HREF="relojes.php?delete=<?php echo $row_reloj['id_reloj'];?>&action=0" onclick="return confirm('Esta seguro de eliminar este item?');"><i class="icon-minus-sign"></i></i></A>
	<?php } ?>
	</td>
</tr>
<?php }while ($row_reloj = mysql_fetch_array($relojes)) ?>
</tbody>

</table>
</div>
</center>
</div>

<?php include_once("footer.php");?>