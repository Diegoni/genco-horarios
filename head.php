<? 
//configuracion de base de datos
include_once("config/database.php");
include_once("config/config.php");

date_default_timezone_set('America/Argentina/Mendoza');  
?>
<html>
<head>
<title>Sistema de horario Genco</title>
<!--BEGIN META TAGS-->
<META NAME="keywords" CONTENT="">
<META NAME="description" CONTENT="Sistema de horario Genco by TMS Group">
<META NAME="rating" CONTENT="General">
<META NAME="ROBOTS" CONTENT="ALL">
<!--END META TAGS-->

<!-- Charset tiene que estar en utf-8 para que tome ñ y acentos -->
<meta http-equiv="Content-type" content="text/html; charset="utf-8" />


<!-- Iconos -->
<link type="image/x-icon" href="imagenes/favicon.ico" rel="icon" />
<link type="image/x-icon" href="imagenes/favicon.ico" rel="shortcut icon" />

<!-- Necesario para que funcione Jquery UI y Bootstrap -->

<script src="<?= $librerias_url?>js/jquery.min.js" type="text/javascript"></script>
<script src="<?= $librerias_url?>jquery.dataTables.js" type="text/javascript" language="javascript"></script>

<script type="text/javascript" charset="utf-8">
function imprSelec(muestra){
	var ficha=document.getElementById(muestra);
	var ventimp=window.open(' ','popimpr');ventimp.document.write(ficha.innerHTML);
	ventimp.document.close();
	ventimp.print();
	ventimp.close();
}
</script>	

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Bootstrap
----------------------------------------------------------------------
--------------------------------------------------------------------->

<link href="<?= $librerias_url?>bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<link href="<?= $librerias_url?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
<link href="<?= $librerias_url?>font/css/font-awesome.css" rel="stylesheet">

<script src="<?= $librerias_url?>bootstrap/js/jquery.js"></script>
<script src="<?= $librerias_url?>bootstrap/js/bootstrap.js"></script>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Css y Js creados 
----------------------------------------------------------------------
--------------------------------------------------------------------->
<link href="<?= $librerias_url?>css/style.css" rel="stylesheet">
<link href="<?= $librerias_url?>css/main.css" rel="stylesheet" type="text/css"  media="screen" />
<script src="<?= $librerias_url?>js/script.js" type="text/javascript"></script>
<script src="<?= $librerias_url?>js/main.js"></script>



<!--------------------------------------------------------------------
----------------------------------------------------------------------
						JQuery UI
----------------------------------------------------------------------
--------------------------------------------------------------------->

<link href="<?= $librerias_url?>ui/jquery-ui.css"  rel="stylesheet" />

<script src="<?= $librerias_url?>ui/jquery-ui.js"></script>
<script src="<?= $librerias_url?>js/jquery.tabs.pack.js" type="text/javascript"></script>





<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Funciones
----------------------------------------------------------------------
--------------------------------------------------------------------->	
<script src="<?= $librerias_url?>js/jquery.dataTables.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				 $('#example').dataTable( {
				"sPaginationType": "full_numbers"
			} );
			} );
		</script>
		



</head>



<center>
<div class="container">	
	