<?php    
session_start();
include_once("control_usuario.php");
include_once("head.php"); 
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."departamentos_model.php");
include_once($url['models_url']."empresas_model.php");
include_once($url['models_url']."convenios_model.php");
include_once($url['models_url']."convenio_turnos_model.php");
include_once($url['models_url']."marcadas_model.php");
include_once($url['models_url']."otrahora_model.php");
include_once("helpers.php");

/*------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Buscar grupo
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------*/

if(isset($_GET['grupo'])){
	if($_GET['grupo']==1){
		
		$query        = getUsuarios();
		$row_query    = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo        = 'usuario';
		
	}else if($_GET['grupo']==2){
			
		$query        = getDepartamentos();
		$row_query    = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo        ='departamento';
		
	}else if($_GET['grupo']==3){
		
		$query        = getEmpresas();
		$row_query    = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo        ='empresa';
		
	}else if($_GET['grupo']==4){
		
		$query        = getConvenios();
		$row_query    = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo        ='convenio';
		
	}
}


/*------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Buscar grupo de usuarios
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------*/


if(isset($_GET['id'])){
		
	if($_GET['grupo'] == 1){
		$campo    = 'id_usuario';
	}else if($_GET['grupo'] == 2){
		$campo    = 'id_departamento';
	}else if($_GET['grupo'] == 3){
		$campo    = 'id_empresa';
	}else if($_GET['grupo'] == 4){
		$campo    = 'id_convenio';
	}
   
	$usuarios      = getUsuarios($_GET['id'], $campo, $_GET['orden']);
	$row_usuario   = mysql_fetch_assoc($usuarios);
	
	$usuarios2     = getUsuarios($_GET['id'], $campo, $_GET['orden']);
	$row_usuario2  = mysql_fetch_assoc($usuarios2);
	$numero_usuario2   = mysql_num_rows($usuarios2);
}

/*------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Buscar intervalo de fechas
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------*/

if(isset($_GET['buscar'])){
		$fecha_inicio = date( "Y-m-d", strtotime($_GET['fecha_inicio']));
		$fecha_final  = date( "Y-m-d", strtotime($_GET['fecha_final']));
}else{
		$fecha        = date("d-m-Y");
		$fecha_inicio = date('01-m-Y', strtotime($fecha));
		$ultimoDia    = getUltimoDiaMes(date('Y', strtotime($fecha)),date('m', strtotime($fecha)));
		$fecha_final  = $ultimoDia.date('-m-Y', strtotime($fecha));
}

?>

<HTML LANG="es">
<head>
	<title>Reporte horario.</title>
	<TITLE><?php $title;?></TITLE>
	<style>
		.titulo{
			font-weight:bold;
			text-align:center;
			vertical-align: middle;
			font-size:12px;
		}
		
		.texto{
			text-align:center;
			height: 30px; 
			vertical-align: middle;
			font-size:11px;
		}
		
		.texto-min{
			text-align:center;
			height: 30px; 
			vertical-align: middle;
			font-size:10px;
		}
		
		.hora{
			text-align:center;
			height: 40px; 
			vertical-align: middle;
			font-size:14px;
		}
		table {
		    border-collapse: collapse;
		}
		
		table, td, th {
		    border: 1px solid black;
		}
		
		H1.SaltoDePagina{
		     PAGE-BREAK-AFTER: always
		 }
	</style>
</head>

<body onload="window.print();
window.onfocus=function(){ window.close();}">

<!------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Usuarios
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------->	


<?php 
if(isset($_GET['buscar'])){ 
	$contador_usuarios=0;
     
	do{
		$array_marcaciones=array();
		$array_otrashoras=array();
							
		if(isset($row_usuario2['id_usuario'])){														
			$marcacion			= getMarcaciones($row_usuario2['id_usuario'], $fecha_inicio, $fecha_final);
			$row_marcacion		= mysql_fetch_assoc($marcacion);   
			$cantidad_marcacion = mysql_num_rows($marcacion);
			/*					
			$otrahora= getOtrahora($row_usuario2['id_usuario'], $fecha_inicio, $fecha_final);
			$row_otrahora = mysql_fetch_assoc($otrahora);
			$cantidad=mysql_num_rows($otrahora);
			*/					
			if($config['aplicar_redondeo']==1){
				do{
					$array_marcaciones['marcacion-'.$row_marcacion['id_parametros'].'-'.date('Y-m-d', strtotime($row_marcacion['entrada']))] = date('H:i', strtotime(redondear_minutos($row_marcacion['entrada'])));
				}while ($row_marcacion = mysql_fetch_array($marcacion));
			}else{
				do{
					$array_marcaciones['marcacion-'.$row_marcacion['id_parametros'].'-'.date('Y-m-d', strtotime($row_marcacion['entrada']))] = date('H:i', strtotime($row_marcacion['entrada']));
				}while ($row_marcacion = mysql_fetch_array($marcacion));
			}
			/*					
			do{
				$array_otrashoras['otrahora-'.date('Y-m-d', strtotime($row_otrahora['fecha']))]	= $row_otrahora['tipootra']." :".$row_otrahora['horas'];
			}while ($row_otrahora = mysql_fetch_array($otrahora));
			*/				 								
			if($cantidad_marcacion>0){
				$arrayFechas	= devuelveArrayFechasEntreOtrasDos($fecha_inicio, $fecha_final);
			}
		}										
		
		$contador=0;
		
		foreach($arrayFechas as $valor){ 
			if ($array_marcaciones['marcacion-1-'.$valor]!="" || $array_marcaciones['marcacion-2-'.$valor]!="" ||$array_marcaciones['marcacion-3-'.$valor]!="" ||$array_marcaciones['marcacion-4-'.$valor]!="" ){
			?>
			<table style="margin: 16.6px;">
				<tr>
					<td class="titulo" colspan="4"><?php echo $row_usuario2['empresa']?> <br> C.U.I.L. N <?php echo $row_usuario2['cuil_empresa']?></td>
					<td class="titulo" colspan="2">Fecha Emisión</td>
					<td class="texto-min">
						<?php 
							if($config['fecha_actual']==1){
								echo date("d-m-Y");
							}else{
								echo date( "d-m-Y", strtotime ( '+'.$config['suma_dias'].' day' , strtotime ( $valor ) ));	
							}
						?>
					</td>
					<td class="titulo">Legajo</td>
					<td class="texto" colspan="2"><?php echo $row_usuario2['legajo']?></td>
					<?php if($_GET['buscar']==1){ ?>
						<td class="texto" width="25%" colspan="3" rowspan="4" style="vertical-align:bottom;">Firma Empleado</td>
					<?php }else{ ?>
						<td class="texto" width="25%" colspan="3" rowspan="4"><img src="<?php echo $config['firma'];?>" width="120" height="90"></td>
					<?php } ?>
				</tr>
				
				<tr>
					<td class="titulo" colspan="2">Apellido y Nombre</th>
						<?php
							$cadena  = $row_usuario2['apellido']." ".$row_usuario2['nombre'];
							$subcadena = explode(" ", $cadena);
							if(count($subcadena)>3){
								$cadena = $subcadena[0]." ".$subcadena[1]." ".$subcadena[2]; 
							}else if(strlen($cadena)>25){
								$cadena = substr($cadena, 0, 22)."...";
							};
						?>
					<td class="texto" colspan="2"><?php echo $cadena;?></td>
					<td class="titulo">DNI</td>
					<td class="texto"><?php echo $row_usuario2['dni']?></td>
					<td class="titulo" colspan="2">C.U.I.L.</td>
					<td class="texto" colspan="2"><?php echo $row_usuario2['cuil']?></td>
				</tr>
				
				<tr>
					<td class="titulo" width="15%" colspan="2">Fecha</td>
					<td class="titulo" width="15%" colspan="2">Entrada</td>
					<td class="titulo" width="15%" colspan="2">Salida</td>
					<td class="titulo" width="15%" colspan="2">Entrada</td>
					<td class="titulo" width="15%" colspan="2">Salida</td>
				</tr>
				
				<tr>
					<td class="hora" colspan="2"><?php echo date( "d-m-Y", strtotime($valor));?></td>
					<td class="hora" colspan="2"><?php echo $array_marcaciones['marcacion-1-'.$valor]; ?></td> 	
					<td class="hora" colspan="2"><?php echo $array_marcaciones['marcacion-2-'.$valor]; ?></td>
					<td class="hora" colspan="2"><?php echo $array_marcaciones['marcacion-3-'.$valor]; ?></td>
					<td class="hora" colspan="2"><?php echo $array_marcaciones['marcacion-4-'.$valor]; ?></td>
				</tr>
			</table>
			<?php
				$contador = $contador+1;
				if($contador==$config['marcaciones_x_hoja']){
					$contador=0;
					echo "<H1 class='SaltoDePagina'> </H1>";
				}
			}//if		
			}//foreach($arrayFechas as $valor){
			$contador_usuarios = $contador_usuarios + 1;
			
			if($numero_usuario2 >= $contador_usuarios && $contador_usuarios!=0){		
				$contador=0;
				echo "<H1 class='SaltoDePagina'> </H1>";
			}else{
				
			}
    }while ($row_usuario2 = mysql_fetch_array($usuarios2)); 
} 
?>
</body>
