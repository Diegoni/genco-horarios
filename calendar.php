<?php    
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
		header("Location: ../login/acceso.php");
	}

include_once("head.php"); 

include_once($url['models_url']."updates_model.php");
include_once("helpers.php");

$updates			= getUpdates();
$row_update			= mysql_fetch_assoc($updates);
$cantidad_update	= mysql_num_rows($updates);

?>
<link href='<?php echo $url['librerias_url']?>calendar/fullcalendar.css' rel='stylesheet'/>
<script src='<?php echo $url['librerias_url']?>calendar/jquery.min.js'></script>
<script src='<?php echo $url['librerias_url']?>calendar/moment.min.js'></script>

<script src='<?php echo $url['librerias_url']?>calendar/fullcalendar.js'></script>
<script src='<?php echo $url['librerias_url']?>calendar/lang-all.js'></script>

<script>

	$(document).ready(function() {

		$('#calendar').fullCalendar({
			defaultDate: '<?php echo date('Y-m-d')?>',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			lang: 'es',
			events: [
			<?php do{ ?>
				{
					title: 	'<?php echo $row_update['cantidad_registros']?>',
					start: 	'<?php echo $row_update['start_date']?>',
					end: 	'<?php echo date('Y-m-d', strtotime($row_update['end_date'].'+1 day'));?>',
					color:  '<?php echo $row_update['color']?> ',
					className: 'reloj-<?php echo $row_update['reloj']?>',
					url: 'updates.php?id=<?php echo $row_update['id_update']?>'
				},
			<?php }while($row_update=mysql_fetch_array($updates)); ?>
				{
					title: 'Versión 1.4',
					start: '2014-10-01'
				}
			],
			eventClick: function(event) {
				if (event.url) {
					window.open(event.url, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=50, left=500, width=400, height=460");
					return false;
				}
			}

		});
		
	});

</script>


<style>
	#calendar {
		max-width: 100%;
		max-height: 50%;
		margin: 0 auto;
	}

</style>


<div class="col-md-12">
	<div id='calendar'></div>
</div>







		