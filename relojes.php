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
	deleteReloj($_GET['id']);
	echo getMensajes('delete', 'ok', 'Reloj', $_GET['id']);
}

//modifica al reloj segun el formulario de modificar.php
if(isset($_GET['modificar'])){
	updateReloj($_GET['reloj'],
				$_GET['ip'],
				$_GET['puerto'],
				$_GET['color']);
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
		insertEmpresa(	$_GET['reloj'],
						$_GET['ip'],
						$_GET['puerto'],
						$_GET['color']);
		echo getMensajes('insert', 'ok', 'Reloj', $_GET['reloj']);
	}
}
?>
<div class="row">
<div class="span12">
<center>

<div ALIGN=left class="well">
	<?php echo $botones['Alta']; ?>
	<?php echo $botones['Imprimir']; ?>
	<?php echo $botones['Excel']; ?>
	<div class="pull-right"><h4>Relojes</h4></div>
</div>
<br>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario nuevo departamento
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class='slidingDiv'>
<div class="well">
		
<form class="form-inline" action="empresas.php">
<table class="table table-hover">

	<tr>
		<td>Empresa</td>
		<td><input type="text" name="empresa" class="form-control" placeholder="ingrese Empresa" required></td>
	</tr>

	<tr>
		<td>Cod</td>
		<td><input type="text" name="cod_empresa" class="form-control" placeholder="ingrese codigo de empresa" required></td>
	</tr>

	<tr>
		<td>Cuil</td>
		<td>
			<input type="text" name="cuil1" onkeypress="return isNumberKey(event)" maxlength="2" class="form-control" required>-
			<input type="text" name="cuil2" onkeypress="return isNumberKey(event)" maxlength="8" class="form-control" required>-
			<input type="text" name="cuil3" onkeypress="return isNumberKey(event)" maxlength="1" class="form-control" required>
		</td>
	</tr>

	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="nuevo" value="1" title="Alta empresa"><i class="icon-plus-sign-alt"></i> Alta</button>
		<A class="show_hide btn btn-danger"  title="Cancelar" href='#'><i class="icon-ban-circle"></i> Cancelar</A></td>
	</tr>  
	
</table>
</form><br>
</div>
</div>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Tabla de usuario
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<div id="muestra">
<table border="1" class="table table-hover" id="example">

<!-- Cabecera -->
<thead>
	<tr class="success">
		<td>Empresa</td>
		<td>Codigo</td>
		<td>CUIL</td>
		<td>Estado</td>
		<td>Operación</td>
	</tr>
<thead>
<tbody>
	<?php do{ ?>
	<tr>
		<td><?php echo $row_empresa['empresa'];?></td>
		<td><?php echo $row_empresa['cod_empresa'];?></td>
		<td><?php echo $row_empresa['cuil'];?></td>
		<td>
			<?php if ($row_empresa['id_estado']==0) {
				echo $texto['baja'];
			} else {
				echo $texto['alta'];
			} 
			?>
		</td>
		<td>
			<?php 
			$datos=array(
						'href'	=> 'modificar_empresa.php',
						'id'	=> $row_empresa['id_empresa'],
						'action'=> 1
						);
			echo button_edit($datos)." ";
			
			$datos['action']=0;
			if ($row_empresa['id_estado']==0) {
				$datos['delete']='disabled';
			} else { 
				$datos['delete']='';
			} 
			
			echo button_delete($datos);
			?>
		</td>
	</tr>
	<?php }while ($row_empresa = mysql_fetch_array($empresa)) ?>
</tbody>

</table>
</div>
</center>
</div>

<?php include_once("footer.php");?>