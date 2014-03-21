<? 
//configuracion de base de datos
include_once("config/database.php");
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
<script type="text/javascript" src="js/jquery.min.js"></script>


<!-- Scroll  -->
<script type="text/javascript" src="js/jquery.slimscroll.js"></script>
<script type="text/javascript">
    $(function(){
      $('#target').slimScroll({
         color: '#00f',
		width: '100%',
		height: '500px'
      });
    });
</script>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Css y Js creados 
----------------------------------------------------------------------
--------------------------------------------------------------------->
<link rel="stylesheet" href="css/style.css" />
<script type="text/javascript" src="js/script.js"></script>
<link rel="stylesheet" type="text/css" href="css/main.css" media="screen" />
<script src="js/main.js"></script>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						JQuery UI
----------------------------------------------------------------------
--------------------------------------------------------------------->

<link rel="stylesheet" href="ui/jquery-ui.css" />

<script src="ui/jquery-ui.js"></script>
<script src="js/jquery.tabs.pack.js" type="text/javascript"></script>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Bootstrap
----------------------------------------------------------------------
--------------------------------------------------------------------->

<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
<link href="font/css/font-awesome.css" rel="stylesheet">

<script src="bootstrap/js/bootstrap.js"></script>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Funciones
----------------------------------------------------------------------
--------------------------------------------------------------------->	


</head>

<div class="carga">
Cargando, por favor espere
</div>


<center>