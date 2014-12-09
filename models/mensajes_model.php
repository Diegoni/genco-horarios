<?php
function getMensajes($action=NULL, $estado=NULL, $tabla, $registro){
	if($action=='insert' && $estado=='ok'){
	$mensaje="<div class='alert alert-success'>"
						."<button type='button' class='close' data-dismiss='alert'>&times;</button>"
						.$tabla.": <b>".$registro."</b><br>"
						."se ha crado con éxito <i class='icon-thumbs-up text-success'></i>"
						."</div>";
	}else if($action=='insert' && $estado=='error'){
	$mensaje="<div class='alert alert-danger'>"
						."<button type='button' class='close' data-dismiss='alert'>&times;</button>"
						.$tabla." ya está registrado"
						."</div>";
	}else if($action=='update' && $estado=='ok'){
	$mensaje="<div class='alert alert-success'>"
						."<button type='button' class='close' data-dismiss='alert'>&times;</button>"
						.$tabla.": <b>".$registro."</b><br>"
						."se ha modificado con éxito <i class='icon-thumbs-up text-success'></i>"
						."</div>";
	}else if($action=='update' && $estado=='error'){
	$mensaje="<div class='alert alert-danger'>"
						."<button type='button' class='close' data-dismiss='alert'>&times;</button>"
						.$tabla.": <b>".$registro."</b><br>"
						."no se ha modificado, controle los datos ingresados <i class='icon-thumbs-down text-danger'></i>"
						."</div>";
	}else if($action=='delete' && $estado=='ok'){
	$mensaje="<div class='alert alert-success'>"
						."<button type='button' class='close' data-dismiss='alert'>&times;</button>"
						.$tabla
						." se ha dado de baja con éxito <i class='icon-thumbs-up text-success'></i>"
						."</div>";
	}
			
	return $mensaje;
}

?>