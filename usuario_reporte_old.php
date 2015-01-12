<?php 
include_once("head.php");
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."marcadas_model.php");
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

<!--<body onload="window.print();
window.onfocus=function(){ window.close();}">-->
<body>

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

$arrayFechas=devuelveArrayFechasEntreOtrasDos($fecha_inicio, $fecha_final);

# Creo y completo tabla temporal para horas
$query_create	= "CREATE TEMPORARY TABLE temp (id_marcada int, entrada datetime, id_usuario int, id_parametros int, id_estado int)";
$res_create		= mysql_query($query_create) or die(mysql_error());

	$marcacion		= getMarcaciones($id_usuario, $fecha_inicio, $fecha_final);;
	$row_marcacion	= mysql_fetch_assoc($marcacion);
	$cantidad_marcacion = mysql_num_rows($marcacion);   
		
do{
	$query_ins	= "INSERT INTO temp VALUES ('$row_marcacion[id_marcada]', '$row_marcacion[entrada]', '$row_marcacion[id_usuario]', '$row_marcacion[id_parametros]', '$row_marcacion[id_estado]')";
	$res_ins	= mysql_query($query_ins) or die(mysql_error());
}while ($row_marcacion = mysql_fetch_array($marcacion));

?>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->	
<center>
<?php 
$contador=0;
foreach($arrayFechas as $valor){

	$query="SELECT * 
		FROM temp 
		WHERE
		DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'";   
	$marcacion=mysql_query($query) or die(mysql_error());
	$cantidad_parametros=mysql_num_rows($marcacion);


	
if($cantidad_parametros>0){
?>



<table style="margin: 16.6px;">
<tr>
	<td class="titulo" colspan="4"><?php echo $row_usuario['empresa']?> <br> C.U.I.L. N <?php echo $row_usuario['cuil_empresa']?></td>
	<td class="titulo" colspan="2">Fecha Emisión</td>
	<td class="texto-min"><?php 
						if($config['fecha_actual']==1){
							echo date("d-m-Y");
						}else{
							echo date( "d-m-Y", strtotime ( '+'.$config['suma_dias'].' day' , strtotime ( $valor ) ));	
						}?></td>
	<td class="titulo">Legajo</td>
	<td class="texto" colspan="2"><?php echo $row_usuario['legajo']?></td>
	<td class="texto" width="25%" colspan="3" rowspan="4" style="vertical-align:bottom;">Firma Empleado</td>
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
	<td class="hora" colspan="2"><?php echo date( "d-m-Y", strtotime($valor));?></td>
	
		<?php 
		for ($i = 1; $i <= 4; $i++) {
				$query="SELECT * 
				FROM temp 
				WHERE
				DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'
				AND id_parametros=$i";   
			$marcacion=mysql_query($query) or die(mysql_error());
			$row_marcacion = mysql_fetch_assoc($marcacion);
			$cantidad_parametros=mysql_num_rows($marcacion);
			
			$redondear_minutos=redondear_minutos(date('H:i', strtotime($row_marcacion['entrada'])));
	?>
	<?php
	if($cantidad_parametros==0){?>
	<td class="hora" colspan="2"> - </td>

	<?php }else{ ?>
	<td class="hora" colspan="2">
		<?php 
			if(!($config['mostar_marcada']==0 && $config['aplicar_redondeo']==1)){
				echo date('H:i', strtotime($row_marcacion['entrada']));	
			}
			if($config['aplicar_redondeo']==1 && $config['mostar_marcada']){
				echo " - ";
			}
			if($config['aplicar_redondeo']==1){
				echo date('H:i', strtotime(redondear_minutos($row_marcacion['entrada'])));	
			} 
		?> 
	</td>
	<?php }
	
	} ?>

</tr>
</table>	

<?php 
$contador=$contador+1;
		} //cierra el if($cantidad_parametros>0)
	if($contador==$config['marcaciones_x_hoja']){
	$contador=0;
	echo "<H1 class='SaltoDePagina'> </H1>";
}		
?>
<?php 	} ?>	


<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->	
<H1 class='SaltoDePagina'> </H1>	
<center>
<?php 
$contador=0;
foreach($arrayFechas as $valor){

	$query="SELECT * 
		FROM temp 
		WHERE
		DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'";   
	$marcacion=mysql_query($query) or die(mysql_error());
	$cantidad_parametros=mysql_num_rows($marcacion);


	
if($cantidad_parametros>0){
?>



<table style="margin: 16.6px;">
<tr>
	<td class="titulo" colspan="4"><?php echo $row_usuario['empresa']?> <br> C.U.I.L. N <?php echo $row_usuario['cuil_empresa']?></td>
	<td class="titulo" colspan="2">Fecha Emisión</td>
	<td class="texto-min"><?php 
						if($config['fecha_actual']==1){
							echo date("d-m-Y");
						}else{
							echo date( "d-m-Y", strtotime ( '+'.$config['suma_dias'].' day' , strtotime ( $valor ) ));	
						}?></td>
	<td class="titulo">Legajo</td>
	<td class="texto" colspan="2"><?php echo $row_usuario['legajo']?></td>
	<td class="texto" width="25%" colspan="3" rowspan="4"><img src="<?php echo $config['firma'];?>" width="120" height="90"></td> 
</tr>
<tr>
	<td class="titulo" colspan="2">Apellido y Nombre</th>
	<?php
		$cadena  = $row_usuario['apellido']." ".$row_usuario['nombre'];
		$subcadena = explode(" ", $cadena);
		if(count($subcadena)>3){
			$cadena = $subcadena[0]." ".$subcadena[1]." ".$subcadena[2]; 
		}else if(strlen($cadena)>40){
			$cadena = substr($cadena, 0, 38)."...";
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
	<td class="hora" colspan="2"><?php echo date( "d-m-Y", strtotime($valor));?></td>
	
		<?php 
		for ($i = 1; $i <= 4; $i++) {
				$query="SELECT * 
				FROM temp 
				WHERE
				DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'
				AND id_parametros=$i";   
			$marcacion=mysql_query($query) or die(mysql_error());
			$row_marcacion = mysql_fetch_assoc($marcacion);
			$cantidad_parametros=mysql_num_rows($marcacion);
			
			$redondear_minutos=redondear_minutos(date('H:i', strtotime($row_marcacion['entrada'])));
	?>
	<?php
	if($cantidad_parametros==0){?>
	<td class="hora" colspan="2"> - </td>

	<?php }else{ ?>
	<td class="hora" colspan="2">
		<?php 
			if(!($config['mostar_marcada']==0 && $config['aplicar_redondeo']==1)){
				echo date('H:i', strtotime($row_marcacion['entrada']));	
			}
			if($config['aplicar_redondeo']==1 && $config['mostar_marcada']){
				echo " - ";
			}
			if($config['aplicar_redondeo']=1){
				echo date('H:i', strtotime(redondear_minutos($row_marcacion['entrada'])));	
			} 
		?> 
	</td>
	<?php }
	
	} ?>

</tr>
</table>	

<?php 
$contador=$contador+1;
		} //cierra el if($cantidad_parametros>0)
	if($contador==$config['marcaciones_x_hoja']){
	$contador=0;
	echo "<H1 class='SaltoDePagina'> </H1>";
}		
?>
<?php 	} ?>	
</center>
<?php
//elimino las tablas temporaria
$query_drop = "DROP TABLE temp";
$res_drop = mysql_query($query_drop) or die(mysql_error());

?>
</body>

</html>

