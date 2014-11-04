<?php    
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
include_once("menu.php"); 
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."departamentos_model.php");
include_once($url['models_url']."empresas_model.php");
include_once($url['models_url']."convenios_model.php");
include_once($url['models_url']."convenio_turnos_model.php");
include_once($url['models_url']."marcadas_model.php");
include_once($url['models_url']."encargados_model.php");
include_once("helpers.php");

/*------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Buscar grupo
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------*/

if(isset($_GET['grupo'])){
	if($_GET['grupo']==1){
		
		$query=getUsuarios();
		$row_query = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo='usuario';
		
	}else if($_GET['grupo']==2){
			
		$query=getDepartamentos();
		$row_query = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo='departamento';
		
	}else if($_GET['grupo']==3){
		
		$query=getEmpresas();
		$row_query = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo='empresa';
		
	}else if($_GET['grupo']==4){
		
		$query=getConvenios();
		$row_query = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo='convenio';
		
	}
}


/*------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Buscar grupo de usuarios
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------*/


if(isset($_GET['id'])){
		
	if($_GET['grupo']==1){
		$campo='id_usuario';
	}else if($_GET['grupo']==2){
		$campo='id_departamento';
	}else if($_GET['grupo']==3){
		$campo='id_empresa';
	}else if($_GET['grupo']==4){
		$campo='id_convenio';
	}
	
	$usuarios=getUsuarios($_GET['id'], $campo);
	$row_usuario = mysql_fetch_assoc($usuarios);
	$usuarios2=getUsuarios($_GET['id'], $campo);
	$row_usuario2 = mysql_fetch_assoc($usuarios);
	$numero_usuario = mysql_num_rows($usuarios);
}




/*------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Buscar intervalo de fechas
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------*/
if($_GET['buscar']==1){
		$fecha_inicio=date( "Y-m-d", strtotime($_GET['fecha_inicio']));
		$fecha_final=date( "Y-m-d", strtotime($_GET['fecha_final']));
}else{
		$fecha=date("d-m-Y");
		$fecha_inicio=date('01-m-Y', strtotime($fecha));
		$ultimoDia = getUltimoDiaMes(date('Y', strtotime($fecha)),date('m', strtotime($fecha)));
		$fecha_final=$ultimoDia.date('-m-Y', strtotime($fecha));
}

?>

<!------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Formulario Inicial
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------->	

<div class="row well">
	<div class="col-lg-12">
		<p>Reporte de Tardanza</p>
		<form class="form-horizontal" role="form">
		<div class="col-md-6">	
		<div class="form-group">	
			<label for="inputEmail3" class="col-sm-4 control-label"> Seleccione grupo </label>
			<div class="col-md-8">	
				<select name="grupo" class="chosen-select form-control"
				onChange="javascript:window.location.href='reporte_tardanza.php?grupo='+this.value+'';">
					<option></option>
					<option value="1" <?php echo selected(1, 'grupo');?>>Usuario</option>
					<option value="2" <?php echo selected(2, 'grupo');?>>Departamento</option>
					<option value="3" <?php echo selected(3, 'grupo');?>>Empresa</option>
					<option value="4" <?php echo selected(4, 'grupo');?>>Convenio</option>
				</select>
			</div>
		</div>
		
		
		<?php if(isset($_GET['grupo'])){ ?>
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-4 control-label"> Seleccione <?php echo $grupo ?> </label>
			<div class="col-md-8">	
				<select name="id" class="chosen-select form-control"
				onChange="javascript:window.location.href='reporte_tardanza.php?id='+this.value+'&grupo=<?php  echo $_GET['grupo'];?>'">
					<option value=""></option>
					<?php do{ ?>
						<option value="<?php echo $row_query['id_'.$grupo]?>" <?php echo selected($row_query['id_'.$grupo], 'id');?>>
							<?php echo $row_query[$grupo]?>
						</option>
					<?php }while ($row_query = mysql_fetch_array($query)) ?>
				</select>
			</div>
		</div>
		<?php } ?>
		</div>
		
		<?php if(isset($_GET['id'])){ ?>
		
		<div class="col-md-6">	
		<div class="form-group">
			<div class="col-md-6 ">
    		<div class="input-group">
      			<div class="input-group-addon" onclick="document.getElementById('datepicker2').focus();">
      				<span class="add-on">
      					<i class="icon-calendar"></i>
      				</span>
      			</div>
      			<input value="<?php echo date('d-m-Y', strtotime($fecha_inicio)); ?>" type="text" name="fecha_inicio" id="datepicker2" placeholder="fecha de inicio" class="form-control" autocomplete="off" required>
    		</div>
    		</div>
			
			<div class="col-md-6 ">
    		<div class="input-group">
      			<div class="input-group-addon" onclick="document.getElementById('datepicker').focus();">
      				<span class="add-on">
      					<i class="icon-calendar"></i>
      				</span>
      			</div>
      			<input value="<?php echo date('d-m-Y', strtotime($fecha_final)); ?>"	type="text" name="fecha_final" id="datepicker" placeholder="fecha final" class="form-control" autocomplete="off" required>
    		</div>
    		</div>
  		</div>
		</div>
		
		<div class="col-md-6 pull-right">		
		<div class="form-group">
			<div class="col-md-12">
				<button type="submit" class="btn btn-default pull-right" title="Buscar" name="buscar" value="1">
				<i class="icon-search"></i> Buscar
			</button>
			</div>
		</div>
		</div>
		<?php } ?>
		
		</form>
	</div>
</div>

<!------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Usuarios
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------->	


<?php if(isset($_GET['id'])){ ?>
<div class="row">
	<div class="col-md-3">
		<div class="panel panel-default">
  			<div class="panel-body">
  				<ul class="nav nav-pills nav-stacked">
						<li><a class="show_hide">Mostrar todos</a></li>
						<?php 
						do{ 
							if(isset($_GET['buscar'])){
								$marcacion = getMarcaciones($row_usuario['id_usuario'], $fecha_inicio, $fecha_final);
								$cantidad_marcacion = mysql_num_rows($marcacion);
							}else{
								
							}
						?>
						<li id="li<?php echo $row_usuario['id_usuario']?>">
							<a class="show_hide<?php echo $row_usuario['id_usuario']?>">
								<?php echo $row_usuario['usuario']?>
								
									<?php
									if($cantidad_marcacion>0){
										echo "<a href='#seccion".$row_usuario['id_usuario']."' id='go".$row_usuario['id_usuario']."' class='btn disabled pull-right'>";
										echo "<i class='icon-angle-right'></i>";	
										echo "</a>";
									}else{
										echo "<a class='pull-right disabled'>-</a>";
									}
									?>
							</a>
						</li>
					<?php }while ($row_usuario = mysql_fetch_array($usuarios)) ?>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="col-md-9" id="scrollit">
		<div class="panel panel-default">
			<a class='btn btn-default pull-right' href="javascript:imprSelec('muestra')" ><i class="icon-print"></i> Imprimir</a>
			<button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#myModal">
					<i class="icon-envelope-alt"></i> Enviar por correo
			</button>
			
			<div class="panel-body" id="muestra">
				<img src="<?php echo $url['imagenes']?>loading_small.gif" class="hide_loading">
				<?php do{ ?>
					<a name="seccion<?php echo $row_usuario2['id_usuario']?>" id="seccion<?php echo $row_usuario2['id_usuario']?>"></a>
					<div class="slidingDiv <?php echo $row_usuario2['id_usuario']?>">
						<?php 
						if(isset($_GET['buscar'])){?>
							
							
							
							<a href="#" class="upscroll"><h3><?php echo $row_usuario2['usuario']?></h3></a>
							
							<button class="btn btn-default pull-right excel" onclick="tableToExcel('example<?php echo $row_usuario2['id_usuario']?>', '<?php echo $row_usuario2['usuario']?>')">
								<i class="icon-download-alt"></i>
							</button>
							
							<table border="1" style="width: 100%" class="table table-hover" id="example<?php echo $row_usuario2['id_usuario']?>">
								<thead>
									<tr class="success">
										<th style="text-align: center">Día</th>
										<th style="text-align: center">Fecha</th>
										<th style="text-align: center">Marcación</th>
										<th style="text-align: center">Tiempo</th>
										<th style="text-align: center">Tipo</th>
									</tr>
								</thead>
								<tbody>
						<?php
							$marcacion = getMarcaciones($row_usuario2['id_usuario'], $fecha_inicio, $fecha_final);
							$row_marcacion = mysql_fetch_assoc($marcacion);   
							$cantidad_marcacion = mysql_num_rows($marcacion);
							
							if($cantidad_marcacion>0){
							do{
								$i = strtotime($row_marcacion['marcada']); 
								$nro = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)));
								
								$datos=array(
											'id_usuario'	=> $row_usuario2['id_usuario'],
											'dia'			=> $nro,
											'id_parametros'	=> $row_marcacion['id_parametros'],
											'marcada'		=> $row_marcacion['entrada']);
								
								$tardanza = getConvenios_tardanza($datos);
								
								$parametro = tipoParametro($row_marcacion['id_parametros']);
																
								if($tardanza['tiempo']!=0){
									list ($clase, $title, $esferiado) = esferiado(date('Y-m-d', strtotime($row_marcacion['entrada'])));
									
									echo "<tr>";									
									echo "<td><p class='dia'>".devuelve_dia(date('Y/m/d', strtotime($row_marcacion['entrada'])))."</p></td>";
									echo "<td><p class='".$clase."' title='".$title."'>".date('d/m/Y', strtotime($row_marcacion['entrada']))."</p></td>";
									echo "<td style='text-align: center'>".date('H:i', strtotime($row_marcacion['entrada']))."</td>";
									echo "<td style='text-align: center'>".pasar_hora($tardanza['tiempo'])."</td>";
									echo "<td><p class='".$parametro['clase']."' title='".$parametro['title'].$tardanza['parametro']."'>".$parametro['cadena']."</p></td>";
									echo "</tr>";
								}
								
							
							}while ($row_marcacion = mysql_fetch_array($marcacion));
							}?>
								</tbody>
							</table>
							<H1 class="SaltoDePagina"></H1>
							<STYLE>
								H1.SaltoDePagina{
									PAGE-BREAK-AFTER: always
								}
							</STYLE>
						<?php
						}else{ ?>
							<div class="alert alert-warning alert-dismissible" role="alert">
  								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
  								Para ver las marcaciones de <strong><?php echo $row_usuario2['usuario'] ?></strong> seleccione un periodo de tiempo
							</div>
						<?php
						} 
						?>
					</div>
					
					<script>
					$(".<?php echo $row_usuario2['id_usuario']?>").hide();
					      
					$('.show_hide<?php echo $row_usuario2['id_usuario']?>').click(function(){
		    			$(".<?php echo $row_usuario2['id_usuario']?>").slideToggle();
		    			$('#li<?php echo $row_usuario2['id_usuario']?>').toggleClass('active');
		    			$('#go<?php echo $row_usuario2['id_usuario']?>').toggleClass('disabled');
		    		});
		    		
		    		$('.#go<?php echo $row_usuario2['id_usuario']?>').click(function(){
            			$("html, body").animate({ scrollTop: $("#seccion<?php echo $row_usuario2['id_usuario']?>").scrollTop() }, 1000);
           				return false;
        			});
 
		    		</script>
				<?php }while ($row_usuario2 = mysql_fetch_array($usuarios2)) ?>
			</div>
		</div>
	</div>
	</div>
</div>
<?php } ?>


<!------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Modal
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------->	
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form class="form-horizontal" role="form" action="email_tardanza.php">
				
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Envió de Reporte</h4>
			</div>
			
			<div class="modal-body">
					<input name="grupo" 		type="hidden" value="<?php echo $_GET['grupo']?>">
					<input name="id" 			type="hidden" value="<?php echo $_GET['id']?>">
					<input name="fecha_inicio" 	type="hidden" value="<?php echo $_GET['fecha_inicio']?>">
					<input name="fecha_final" 	type="hidden" value="<?php echo $_GET['fecha_final']?>">
					<input name="buscar" 		type="hidden" value="1">
					
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">
							Asunto
						</label>
					    <div class="col-sm-10">
							<input name="asunto" class="form-control" value="Reporte de tardanza <?php echo date('d-m-Y');?>"?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">
							Email
						</label>
					    <div class="col-sm-10">
							<textarea class="ckeditor form-control" cols="40" id="editor" name="email">
							</textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">
							Encargado
						</label>
					    <div class="col-sm-10">
					    	<?php 
					    	if($_GET['grupo']==2){
					    		
					    		$query=getEncargado_departamento($_GET['id']);
								$row_query = mysql_fetch_assoc($query);
								$numero_filas = mysql_num_rows($query);
								
								if($numero_filas>0 && $row_query['id_encargado']!=0){
										
									echo "<input name='email_1' class='form-control' value='".$row_query['email_1']."'>";
									
									if($row_query['email_2']!=""){
										echo "<input name='email_2' class='form-control' value='".$row_query['email_2']."'>";
									}else{
										echo "<input name='email_2' class='form-control'>";
									}
									
									if($row_query['email_3']!=""){
										echo "<input name='email_3' class='form-control' value='".$row_query['email_3']."'>";
									}else{
										echo "<input name='email_3' class='form-control'>";
									}
									
									
								}
					    	}else{
					    			echo "<input name='email_1' class='form-control'>";
									echo "<input name='email_2' class='form-control'>";
									echo "<input name='email_3' class='form-control'>";
							}
					    	?>
						</div>
					</div>
				
				
			</div>
			
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Enviar</button>
			</div>
			</form>
		</div>
	</div>
</div>
	




<?php   include_once("footer.php");?> 


</div><!--cierra el class="span12" -->
</div><!--cierra el row -->
</div><!--cierra el class="container"-->


</body>
