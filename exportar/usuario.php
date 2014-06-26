<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Usuario.xls");
?>
<HTML LANG=”es”>
<title>Bases de Datos.</title>
<TITLE>Titulo de la Página.</TITLE>
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

.hora{
	text-align:center;
	height: 40px; 
	vertical-align: middle;
	font-size:14px;
}
</style>
</head>
<body>
<?php
		$username="root";
		$password="";
		$database="controlfinal2";
		$url="localhost";
		mysql_connect($url,$username,$password);
		@mysql_select_db($database) or die( "No pude conectarme a la base de datos");
		
	
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Valores iniciales
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
$id_usuario=$_GET['id'];
$fecha=date("d-m-Y");

$query="SELECT 	usuario.id_usuario,
				usuario.nombre,
				usuario.apellido,
				usuario.legajo,
				usuario.dni,
				usuario.cuil,
				departamento.nombre as departamento,				
				empresa.empresa as empresa,
				empresa.cuil as cuil_empresa
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		INNER JOIN
		empresa on(usuario.id_empresa=empresa.id_empresa)
		WHERE id_usuario='$id_usuario'";   
$usuario=mysql_query($query) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);


//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Funciones php
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->


function devuelveArrayFechasEntreOtrasDos($fechaInicio, $fechaFin){
	$arrayFechas=array();
	$fechaMostrar = $fechaInicio;

	while(strtotime($fechaMostrar) <= strtotime($fechaFin)) {
	$arrayFechas[]=$fechaMostrar;
	$fechaMostrar = date("Y-m-d", strtotime($fechaMostrar . " + 1 day"));
	}

	return $arrayFechas;
} 

function redondear_minutos($hora){
	$horas=date("H", strtotime($hora));
	$minutos=date("i", strtotime($hora));

	$query="SELECT *
			FROM `limite` 
			ORDER BY limite";   
	$limite=mysql_query($query) or die(mysql_error());
	$row_limite = mysql_fetch_assoc($limite);


	do{
	if($minutos<$row_limite['limite']){
		$minutos=$row_limite['redondeo'];
		$horas=$horas+$row_limite['suma_hora'];
		return "$horas:$minutos";
		break;
	}
	}while($row_limite= mysql_fetch_array($limite));


}

function intervalo_tiempo($init,$finish)
{
	$diferencia = strtotime($finish) - strtotime($init);
	$diferencia=round($diferencia/60);
	$diferencia=$diferencia/60;
	if($diferencia<0){
		$diferencia="ERROR";
	}
    return $diferencia;
}




//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Busqueda de fechas
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

$fecha_inicio=date( "Y-m-d", strtotime($_GET['fecha_inicio']));
$fecha_final=date( "Y-m-d", strtotime($_GET['fecha_final']));

$arrayFechas=devuelveArrayFechasEntreOtrasDos($fecha_inicio, $fecha_final);

# Creo y completo tabla temporal para horas
$query_create = "CREATE TEMPORARY TABLE temp (id_marcada int, entrada datetime, id_usuario int, id_parametros int, id_estado int)";
$res_create = mysql_query($query_create) or die(mysql_error());

$query="SELECT * 
		FROM marcada 
		WHERE 
		DATE_FORMAT(entrada, '%Y-%m-%d') >= '$fecha_inicio' AND
		DATE_FORMAT(entrada, '%Y-%m-%d') <= '$fecha_final' AND
		id_usuario='$id_usuario' AND
		id_estado!=0";   
		$marcacion=mysql_query($query) or die(mysql_error());
		$row_marcacion = mysql_fetch_assoc($marcacion);   
$cantidad_marcacion = mysql_num_rows($marcacion);

		
do{
$query_ins = "INSERT INTO temp VALUES ('$row_marcacion[id_marcada]', '$row_marcacion[entrada]', '$row_marcacion[id_usuario]', '$row_marcacion[id_parametros]', '$row_marcacion[id_estado]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_marcacion = mysql_fetch_array($marcacion));

?>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->	
<center>
<?php 
	foreach($arrayFechas as $valor){

	$query="SELECT * 
		FROM temp 
		WHERE
		DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'";   
	$marcacion=mysql_query($query) or die(mysql_error());
	$cantidad_parametros=mysql_num_rows($marcacion);
	
	if($cantidad_parametros>0){

?>


<table border="1">
<tr>
	<td class="titulo" colspan="5"><?php echo $row_usuario['empresa']?> - C.U.I.L. N <?php echo $row_usuario['cuil_empresa']?></td>
	<td class="titulo" colspan="2">Fecha Emision</td>
	<td class="texto"><?php echo date( "d-m-Y", strtotime($valor));?></td>
	<td class="titulo">Legajo</td>
	<td class="texto"><?php echo $row_usuario['legajo']?></td>
	<td class="texto" colspan="3" rowspan="4">Firma Empleado</td>
</tr>
<tr>
	<td class="titulo" colspan="2">Apellido y Nombre</th>
	<td class="texto" colspan="3"><?php echo $row_usuario['apellido']?>, <?php echo $row_usuario['nombre']?></td>
	<td class="titulo">DNI</td>
	<td class="texto"><? echo $row_usuario['dni']?></td>
	<td class="titulo">C.U.I.L.</td>
	<td class="texto" colspan="2"><?php echo $row_usuario['cuil']?></td>
</tr>
<tr>
	<td class="titulo" colspan="2">Fecha</td>
	<td class="titulo" colspan="2">Entrada</td>
	<td class="titulo" colspan="2">Salida</td>
	<td class="titulo" colspan="2">Entrada</td>
	<td class="titulo" colspan="2">Salida</td>
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
	<td class="hora" colspan="2"><?php echo date('H:i', strtotime($row_marcacion['entrada']));?></td>
	<?php }
	
	} ?>

</tr>
</table>	

<?php } //cierra el if($cantidad_parametros>0)?>
<br>	
<br>	
<?php } ?>		
</center>

<?php
//elimino las tablas temporaria
$query_drop = "DROP TABLE temp";
$res_drop = mysql_query($query_drop) or die(mysql_error());
?>




</div><!--cierra el class="span12" -->
</div><!--cierra el row -->
</div><!--cierra el class="container"-->

</body>

</html>

