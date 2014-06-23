<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");    
include_once($models_url."convenios_model.php");   
include_once($models_url."convenio_turnos_model.php");
include_once($models_url."mensajes_model.php");


function intervalo_tiempo($init,$finish)
{
	$diferencia = strtotime($finish) - strtotime($init);
	$diferencia = round($diferencia/60);
	$diferencia = $diferencia/60;
	
	if($diferencia<0){
		$diferencia="ERROR";
	}
	
    return $diferencia;
}


	$total=0;
	
	$convenio=getConvenio($_GET['id']);
	$row_convenio = mysql_fetch_assoc($convenio);
	
	$convenio_turno=getConvenioturnos($_GET['id'], 'id_convenio');
	$row_convenio_turno = mysql_fetch_assoc($convenio_turno);



?>

<div class="well">
		<table class="table table-hover">
			<thead>
			<tr>
				<td class="table-center">Redondeo</td>
				<td>Entrada</td>
				<td>Salida</td>
				<td>Límite</td>
				<td title="Lunes" class="table-center">L</td>
				<td title="Martes" class="table-center">M</td>
				<td title="Miércoles" class="table-center">M</td>
				<td title="Jueves" class="table-center">J</td>
				<td title="Viernes" class="table-center">V</td>
				<td title="Sábado" class="table-center">S</td>
				<td title="Domingo" class="table-center">D</td>
				<td>Horas</td>
				<td></td>
			</tr>
			</thead>
			<tbody>
			<?php do{ ?>
			<tr>
				<td class="table-center">
					<?php if($row_convenio_turno['redondeo']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
					};?>
				</td>
				<td><?php echo date("H:i", strtotime($row_convenio_turno['entrada'])) ;?></td>
				<td><?php echo date("H:i", strtotime($row_convenio_turno['salida'])) ;?></td>
				<td><?php echo $row_convenio_turno['limite'];?> min</td>
				<td class="table-center">
					<?php if($row_convenio_turno['lunes']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['martes']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['miercoles']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['jueves']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['viernes']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['sabado']==1){
						echo "<span class='label label-info'><i class='icon-ok'></i></span>";
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['domingo']==1){
						echo "<span class='label label-important'><i class='icon-ok'></i></span>";
					};?>
				</td>
				<td>
					<?php 
						$e=date("H:i", strtotime($row_convenio_turno['entrada']));
						$s=date("H:i", strtotime($row_convenio_turno['salida']));
						$m=intervalo_tiempo($e,$s);
						$total=$total+$m;
						echo $m;
					?>
				</td>		
				<td>
					<a class="btn btn-primary" title="Editar turno" href="modificar_convenio_turno.php?id=<?php echo $row_convenio_turno['id_convenio_turno'];?>"><i class="icon-edit"></i></a>
					<a type="submit" class="btn btn-danger" ><i class="icon-minus-sign"></i></a>
				</td>

			</tr>
			<?php }while($row_convenio_turno=mysql_fetch_assoc($convenio_turno))?>
			<tr>
				<td colspan="11">Total de horas semanales</td>
				<td><?php echo $total;?></td>
				<td></td>
			</tr>
			</tbody>

		</table>
</div>		
	
