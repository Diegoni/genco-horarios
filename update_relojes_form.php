<?php    
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
		header("Location: ../login/acceso.php");
	}

include_once("menu.php"); 
include_once($url['models_url']."relojes_model.php");
include_once($url['models_url']."updates_model.php");
include_once("helpers.php");


$relojes			= getRelojes();
$row_reloj			= mysql_fetch_assoc($relojes);
$cantidad_reloj		= mysql_num_rows($relojes);


?>
<div class="row">
	<div class="col-md-12 well">
		<form class="form-inline" action="update_relojes.php" name="ente" onsubmit="javascript: alert('La actualización esta por comenzar, espere a que se terminen de cargar los datos.');">
			
			<div class="form-group">
    			<div class="input-group">
      				<div class="input-group-addon" onclick="document.getElementById('datepicker2').focus();">
      					<span class="add-on">
      						<i class="icon-calendar"></i>
      					</span>
      				</div>
      				<input value="" type="text" name="start_date" id="datepicker2" placeholder="fecha de inicio" class="form-control" autocomplete="off" required>
    			</div>
  			</div>
		
		
			<div class="form-group">
    			<div class="input-group">
      				<div class="input-group-addon" onclick="document.getElementById('datepicker').focus();">
      					<span class="add-on">
      						<i class="icon-calendar"></i>
      					</span>
      				</div>
      				<input value=""	type="text" name="end_date" id="datepicker" placeholder="fecha final" class="form-control" autocomplete="off" required>
    			</div>
  			</div>
  			
  			<select 
			class="chosen-select form-control" tabindex="2" name="reloj" required>
			<option value="0" selected>Todos</option>
			<?php do{ ?>
				<option value="<?php echo $row_reloj['id_reloj']?>">
					<?php   echo $row_reloj['reloj']?>
				</option>
			<?php } while($row_reloj=mysql_fetch_array($relojes));?>
			</select>
			
			
			<button type="submit" class="btn btn-default" rel='tooltip' title="Actualizar marcaciones" name="tipo" value="2">
				<i class="icon-search"></i> Actualizar
			</button>
		</form>

</div><!--cierra el class="span12" -->
</div>
<div class="row">
<div class="col-md-2">
	<div class="panel panel-default">
  		<div class="panel-body">
  			<ul class="nav nav-pills nav-stacked">
  				<?php 
				$relojes			= getRelojes();
				$row_reloj			= mysql_fetch_assoc($relojes);
				$cantidad_reloj		= mysql_num_rows($relojes);
					
				do{
					echo "<li><p style='color: #fff; background-color:".$row_reloj['color']."'>".$row_reloj['reloj']."</p></li>";
				}while($row_reloj=mysql_fetch_array($relojes));
				
				?>
			</ul>
		</div>
	</div>
</div>
<div class="col-md-10">
	<iframe src="calendar.php" width="100%" height="600" frameborder="0"></iframe>
</div>

</div><!--cierra el row -->		
<?php   include_once("footer.php");?> 
		