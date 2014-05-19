<?php
function getEmpresas($dato=NULL, $campo=NULL){
	if(isset($dato, $campo)){
		$query="SELECT  *
				FROM `empresa` 
				WHERE 
				empresa.$campo like '$dato'";
		$empresa=mysql_query($query) or die(mysql_error());
	}else{
		$query="SELECT * FROM `empresa` WHERE empresa.id_estado=1 ORDER BY empresa ASC";   
		$empresa=mysql_query($query) or die(mysql_error());
	}
	
	return $empresa;
}

function getEmpresa($id){
	$query="SELECT * FROM `empresa` WHERE id_empresa='$id'";   
	$empresa=mysql_query($query) or die(mysql_error());
	
	return $empresa;
}

function updateEmpresa(	$empresa,
												$cod_empresa,
												$cuil1,
												$cuil2,
												$cuil3,
												$estado,
												$id){
	$cuil=$cuil1."-".$cuil2."-".$cuil3;
	mysql_query("UPDATE `empresa` SET	
				empresa='$empresa',
				cod_empresa='$cod_empresa',
				cuil='$cuil',
				id_estado='$estado'		
				WHERE id_empresa='$id'") or die(mysql_error());
}

function insertEmpresa(	$empresa,
												$cod_empresa,
												$cuil1,
												$cuil2,
												$cuil3,
												$estado){
	$cuil=$cuil1."-".$cuil2."-".$cuil3;
	mysql_query("INSERT INTO `empresa` (
				empresa, 
				cod_empresa, 
				cuil, 
				id_estado) 
				VALUES (
				'$empresa', 
				'$cod_empresa',
				'$cuil',
				'$estado')") or die(mysql_error());

}

?>