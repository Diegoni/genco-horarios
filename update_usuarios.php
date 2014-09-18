<?php    
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
include_once("menu.php"); 
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."empresas_model.php");
include_once($url['models_url']."departamentos_model.php");
include_once($url['models_url']."convenios_model.php");


$query="SELECT * FROM `usuario`";   
$usuarios=mysql_query($query) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);
$cantidad_parametros=mysql_num_rows($usuarios);
$i=0;

do{
		
	$query="SELECT *			
		FROM `importacion` 
		WHERE usuario='$row_usuarios[usuario]'";   
	$usuario = mysql_query($query) or die(mysql_error());
	$row_usuario = mysql_fetch_assoc($usuario);
	$cantidad_parametros=mysql_num_rows($usuario);
	
	if($cantidad_parametros>0){
		
		$empresa = getEmpresas($row_usuario['empresa'], 'empresa');
		$row_empresa = mysql_fetch_assoc($empresa);
		$cantidad_parametros=mysql_num_rows($empresa);
		if($cantidad_parametros>0){
			$id_empresa=$row_empresa['id_empresa'];
		}else{
			$id_empresa=0;
		}
		
		$departamento = getDepartamentos($row_usuario['departamento'], 'nombre');
		$row_departamento = mysql_fetch_assoc($departamento);
		$cantidad_parametros=mysql_num_rows($departamento);
		if($cantidad_parametros>0){
			$id_departamento=$row_departamento['id_departamento'];
		}else{
			$id_departamento=0;
		}
		
				
		$convenio = getConvenios($row_usuario['convenio'], 'convenio');
		$row_convenio = mysql_fetch_assoc($convenio);
		$cantidad_parametros=mysql_num_rows($convenio);
		echo $cantidad_parametros;
		
		if($cantidad_parametros>0){
			$id_convenio=$row_convenio['id_convenio'];
		}else{
			$id_convenio=0;
		}
		
		
		mysql_query("UPDATE `usuario` SET	
								nombre='$row_usuario[nombre]',
								apellido='$row_usuario[apellido]',
								dni='$row_usuario[DNI]',
								cuil='$row_usuario[cuil]',
								id_empresa='$id_empresa',
								id_departamento='$id_departamento',
								id_convenio='$id_convenio',
								fecha_ingreso='$row_usuario[ingreso]',
								legajo='$row_usuario[legajo]'	
								WHERE id_usuario='$row_usuarios[id_usuario]'") or die(mysql_error());
								
		$i=$i+1;
	}
	
	
}while ($row_usuarios = mysql_fetch_array($usuarios));

echo "se actualizaron ".$i." usuarios";
