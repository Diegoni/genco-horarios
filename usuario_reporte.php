<?php 
include_once("head.php");
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."marcadas_model.php");
include_once($url['models_url']."otrahora_model.php");
include_once("helpers.php"); ?>
<HTML LANG="es">
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

 H1.SaltoDePagina
 {
     PAGE-BREAK-AFTER: always
 }
 
 
</style>
</head>

<body onload="window.print();
window.onfocus=function(){ window.close();}">


<?php
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Valores iniciales
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

$fecha			= date("d-m-Y");
$id_usuario		= $_GET['id'];
	  
$usuario		= getUsuario($id_usuario);
$row_usuario	= mysql_fetch_assoc($usuario);	


//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Busqueda de fechas
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

$fecha_inicio	= date( "Y-m-d", strtotime($_GET['fecha_inicio']));
$fecha_final	= date( "Y-m-d", strtotime($_GET['fecha_final']));

$arrayFechas		= devuelveArrayFechasEntreOtrasDos($fecha_inicio, $fecha_final);
$array_marcaciones	= array();
$array_otrashoras	= array();
							
if(isset($id_usuario)){
	$marcacion			= getMarcaciones($id_usuario, $fecha_inicio, $fecha_final);
	$row_marcacion		= mysql_fetch_assoc($marcacion);   
	$cantidad_marcacion	= mysql_num_rows($marcacion);
								
	$otrahora			= getOtrahora($id_usuario, $fecha_inicio, $fecha_final);
	$row_otrahora		= mysql_fetch_assoc($otrahora);
	$cantidad			= mysql_num_rows($otrahora);
								
	do{
		$array_marcaciones['marcacion-'.$row_marcacion['id_parametros'].'-'.date('Y-m-d', strtotime($row_marcacion['entrada']))] = date('H:i', strtotime($row_marcacion['entrada']));
	}while ($row_marcacion = mysql_fetch_array($marcacion));
								
	do{
		$array_otrashoras['otrahora-'.date('Y-m-d', strtotime($row_otrahora['fecha']))]	= $row_otrahora['tipootra']." :".$row_otrahora['horas'];
	}while ($row_otrahora = mysql_fetch_array($otrahora));
								 								
	if($cantidad_marcacion>0){
		$arrayFechas=devuelveArrayFechasEntreOtrasDos($fecha_inicio, $fecha_final);
	}
}

?>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->	
<center>
<?php 
for ($i=0; $i < 2; $i++) {
	$contador=0; 
foreach($arrayFechas as $valor){
	$subtotal=0;
	list ($clase, $title, $esferiado) = esferiado($valor);
								
	if($array_marcaciones['marcacion-1-'.$valor]>0 &&  $array_marcaciones['marcacion-2-'.$valor]>0){
		$m=intervalo_tiempo($array_marcaciones['marcacion-1-'.$valor],  $array_marcaciones['marcacion-2-'.$valor]);
	}else{
		$m=0;
	}
								
	if($array_marcaciones['marcacion-3-'.$valor]>0 && $array_marcaciones['marcacion-4-'.$valor]>0){
		$t=intervalo_tiempo($array_marcaciones['marcacion-3-'.$valor],$array_marcaciones['marcacion-4-'.$valor]);
	}else{
		$t=0;
	}
					
	if($t>0 || $m>0){
		$subtotal=$m+$t;
	}

if($subtotal>0){
?>

<table style="margin: 16.6px;">
<tr>
	<td class="titulo" colspan="4">
		<?php echo $row_usuario['empresa']?> <br> 
		C.U.I.L. N <?php echo $row_usuario['cuil_empresa']?>
	</td>
	<td class="titulo" colspan="2">
		Fecha Emisión
	</td>
	<td class="texto-min">
		<?php 
			if($config['fecha_actual']==1){
				echo date("d-m-Y");
			}else{
				echo date( "d-m-Y", strtotime ( '+'.$config['suma_dias'].' day' , strtotime ( $valor ) ));	
			}?>
	</td>
	<td class="titulo">
		Legajo
	</td>
	<td class="texto" colspan="2">
		<?php echo $row_usuario['legajo']?>
	</td>
	<td class="texto" width="25%" colspan="3" rowspan="4" style="vertical-align:bottom;">
		<?php 
		if($i==0){
			echo "Firma Empleado";	
		}else{
			echo "<img src=".$config['firma']." width='75%' class='img-responsive' alt='Responsive image'>";
		}
		?>
	</td>
</tr>
<tr>
	<td class="titulo" colspan="2">Apellido y Nombre</th>
		<?php
		$cadena  = $row_usuario['apellido']." ".$row_usuario['nombre'];
		$subcadena = explode(" ", $cadena);
		if(count($subcadena)>3){
			$cadena = $subcadena[0]." ".$subcadena[1]." ".$subcadena[2]; 
		}else if(strlen($cadena)>25){
			$cadena = substr($cadena, 0, 22)."...";
		};
		?>
	<td class="texto" colspan="2"><?php echo $cadena;?></td>
	<td class="titulo">DNI</td>
	<td class="texto"><?php echo $row_usuario['dni']?></td>
	<td class="titulo" colspan="2">C.U.I.L.</td>
	<td class="texto" colspan="2"><?php echo $row_usuario['cuil']?></td>
</tr>
<tr>
	<td class="titulo" width="15%" colspan="2">Fecha</td>
	<td class="titulo" width="15%" colspan="2">Entrada</td>
	<td class="titulo" width="15%" colspan="2">Salida</td>
	<td class="titulo" width="15%" colspan="2">Entrada</td>
	<td class="titulo" width="15%" colspan="2">Salida</td>
</tr>
<tr>
	<td class="hora" colspan="2">
		<?php echo date( "d-m-Y", strtotime($valor));?>
	</td>
	<?php 
		if($config['aplicar_redondeo']==1){
			$array_marcaciones['marcacion-1-'.$valor] = date('H:i', strtotime(redondear_minutos($array_marcaciones['marcacion-1-'.$valor])));	
			$array_marcaciones['marcacion-2-'.$valor] = date('H:i', strtotime(redondear_minutos($array_marcaciones['marcacion-2-'.$valor])));
			$array_marcaciones['marcacion-3-'.$valor] = date('H:i', strtotime(redondear_minutos($array_marcaciones['marcacion-3-'.$valor])));
			$array_marcaciones['marcacion-4-'.$valor] = date('H:i', strtotime(redondear_minutos($array_marcaciones['marcacion-4-'.$valor])));
		} 
	?> 
	<td class="hora" colspan="2"><?php echo $array_marcaciones['marcacion-1-'.$valor]; ?></td> 	
	<td class="hora" colspan="2"><?php echo $array_marcaciones['marcacion-2-'.$valor]; ?></td>
	<td class="hora" colspan="2"><?php echo $array_marcaciones['marcacion-3-'.$valor]; ?></td>
	<td class="hora" colspan="2"><?php echo $array_marcaciones['marcacion-4-'.$valor]; ?></td>

</tr>
</table>	
<?php 
$contador=$contador+1;
	} //cierra el if($subtotal>0)
	if($contador==$config['marcaciones_x_hoja']){
	$contador=0;
	echo "<H1 class='SaltoDePagina'> </H1>";
}		
?>					
<?php }//foreach
if($i==0){
	echo "<H1 class='SaltoDePagina'> </H1>";	
}	
}//for ?>

</body>

</html>

