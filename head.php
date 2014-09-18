<?php 
//configuracion de base de datos
include_once("config/database.php");
include_once("config/config.php");

date_default_timezone_set('America/Argentina/Mendoza');  
?>
<html>
<head>
<title><?php echo $config['title'];?></title>
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

<script src="<?php echo $url['librerias_url']?>js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $url['librerias_url']?>jquery.dataTables.js" type="text/javascript" language="javascript"></script>

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

<link href="<?php echo $url['librerias_url']?>bootstrap/css/<?php echo $config['css']?>.css" rel="stylesheet" media="screen">
<link href="<?php echo $url['librerias_url']?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
<link href="<?php echo $url['librerias_url']?>font/css/font-awesome.css" rel="stylesheet">

<script src="<?php echo $url['librerias_url']?>bootstrap/js/jquery.js"></script>
<script src="<?php echo $url['librerias_url']?>bootstrap/js/bootstrap.js"></script>


<link href="<?php echo $url['librerias_url']?>bootstrap/css/bootstrap-switch.css" rel="stylesheet"/>
<script src="<?php echo $url['librerias_url']?>bootstrap/js/bootstrap-switch.js"></script>
<script src="<?php echo $url['librerias_url']?>bootstrap/js/index.js"></script>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Css y Js creados 
----------------------------------------------------------------------
--------------------------------------------------------------------->
<link href="<?php echo $url['librerias_url']?>css/style.css" rel="stylesheet">
<link href="<?php echo $url['librerias_url']?>css/main.css" rel="stylesheet" type="text/css"  media="screen" />
<script src="<?php echo $url['librerias_url']?>js/script.js" type="text/javascript"></script>
<script src="<?php echo $url['librerias_url']?>js/main.js"></script>



<!--------------------------------------------------------------------
----------------------------------------------------------------------
						JQuery UI
----------------------------------------------------------------------
--------------------------------------------------------------------->

<link href="<?php echo $url['librerias_url']?>ui/jquery-ui.css"  rel="stylesheet" />

<script src="<?php echo $url['librerias_url']?>ui/jquery-ui.js"></script>
<script src="<?php echo $url['librerias_url']?>js/jquery.tabs.pack.js" type="text/javascript"></script>




<link href="<?php echo $url['librerias_url']?>ui/jquery.ui.timepicker.css?v=0.3.3" rel="stylesheet" type="text/css" />
<script src="<?php echo $url['librerias_url']?>ui/jquery.ui.core.min.js" type="text/javascript" ></script>
<script src="<?php echo $url['librerias_url']?>ui/jquery.ui.timepicker.js?v=0.3.3" type="text/javascript"></script>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Chosen
----------------------------------------------------------------------
--------------------------------------------------------------------->

<link rel="stylesheet" href="<?php echo $url['librerias_url']?>chosen/chosen.css">
  <style type="text/css" media="all">
    /* fix rtl for demo */
    .chosen-rtl .chosen-drop { left: -9000px; }
  </style>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Funciones
----------------------------------------------------------------------
--------------------------------------------------------------------->	
<script src="<?php echo $url['librerias_url']?>js/jquery.dataTables.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				 $('#example').dataTable( {
				"sPaginationType": "full_numbers"
			} );
			} );
		</script>
		



</head>




	