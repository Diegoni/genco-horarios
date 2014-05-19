<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");


/*--------------------------------------------------------------------
----------------------------------------------------------------------
			ABM empresas
----------------------------------------------------------------------			
--------------------------------------------------------------------*/
//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['modificar'])){
	$cuil=$_GET['cuil1']."-".$_GET['cuil2']."-".$_GET['cuil3'];
	mysql_query("UPDATE `empresa` SET	
				empresa='".$_GET['empresa']."',
				cod_empresa='".$_GET['cod_empresa']."',
				cuil='".$cuil."',
				id_estado='".$_GET['estado']."'		
				WHERE id_empresa='".$_GET['id']."'			
				") or die(mysql_error());
}

//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['nuevo'])){
	$query="SELECT  *
				FROM `empresa` 
				WHERE 
				empresa.empresa like '".$_GET['empresa']."'";
	$empresa=mysql_query($query) or die(mysql_error());
	$row_empresa = mysql_fetch_assoc($empresa);
	$numero_empresas = mysql_num_rows($empresa);
	
	if($numero_empresas>0){
		echo "<div class='alert'><button type='button' class='close' data-dismiss='alert'>&times;</button>La empresa ya existe</div>";
	}else{
	
	$cuil=$_GET['cuil1']."-".$_GET['cuil2']."-".$_GET['cuil3'];
	mysql_query("INSERT INTO `empresa` (
				empresa, 
				cod_empresa, 
				cuil, 
				id_estado) 
				VALUES (
				'".$_GET['empresa']."', 
				'".$_GET['cod_empresa']."',
				'".$cuil."',
				'".$_GET['estado']."'
				)
		
				") or die(mysql_error());
	}
}

/*--------------------------------------------------------------------
----------------------------------------------------------------------
			Consulta para traer las empresas
----------------------------------------------------------------------			
--------------------------------------------------------------------*/

//si no hay busqueda los trae a todos
if(isset($_GET['buscar'])){
$query="SELECT  *
				FROM `empresa` 
				INNER JOIN estado ON(empresa.id_estado=estado.id_estado)
				WHERE 
				empresa.empresa like '%".$_GET['empresa']."%' AND
				empresa.cod_empresa like '%".$_GET['cod_empresa']."%' AND
				empresa.cuil like '%".$_GET['cuil']."%' AND
				empresa.id_estado like '%".$_GET['estado']."%'
				ORDER BY empresa.empresa ASC";   
}

else{
			$query="SELECT *
			FROM 
			empresa
			INNER JOIN estado ON (empresa.id_estado=estado.id_estado)
			WHERE 
			empresa.id_estado=1
			ORDER BY empresa ASC"; 
}
			$empresa=mysql_query($query) or die(mysql_error());
			$row_empresa = mysql_fetch_assoc($empresa);
			$numero_empresas = mysql_num_rows($empresa);







?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		
		

		<script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable();
			} );
		</script>
	</head>
	<body id="dt_example">

	
			
			

<div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-hover" id="example" width="100%">
	<thead>
		<tr>
			<td>Empresa</td>
			<td>Codigo</td>
			<td>CUIL</td>
			<td>Estado</td>
			<td>Operación</td>
		</tr>
	</thead>
	<tbody>
	<? do{ ?>
<tr>
<td><? echo $row_empresa['empresa'];?></td>
<td><? echo $row_empresa['cod_empresa'];?></td>
<td><? echo $row_empresa['cuil'];?></td>
<td><? echo $row_empresa['estado'];?></td>
<td><A class="btn btn-primary" title="Editar empresa" HREF="modificar_empresa.php?id=<? echo $row_empresa['id_empresa'];?>&action=1"><i class="icon-edit"></i></A>
	<?if ($row_empresa['id_estado']==0) {?>
	<A type="submit" class="btn btn-danger disabled"  title="La empresa ya esta dada de baja"><i class="icon-minus-sign"></i></i></A>
	<? } else { ?>
	<A type="submit" class="btn btn-danger"  title="Dar de baja" HREF="modificar_empresa.php?id=<? echo $row_empresa['id_empresa'];?>&action=0"><i class="icon-minus-sign"></i></i></A>
	<? } ?>
	</td>
</tr>
<? }while ($row_empresa = mysql_fetch_array($empresa)) ?>
	</tbody>
</table>
			</div>
			<div class="spacer"></div>
			
			
		
			
			
			

	</body>
</html>


