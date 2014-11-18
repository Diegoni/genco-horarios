<?php 
session_start();
header('Content-type: text/html; charset=utf-8');
 
include('../config/database.php');
include('../config/config.php');
	
include_once('../'.$url['models_url']."usuarios_sistema_model.php");
include_once('../'.$url['models_url']."correos_model.php");

$usuarios			= getUsuario_sistema_moderador();
$row_usuario		= mysql_fetch_assoc($usuarios);
$numero_usuarios 	= mysql_num_rows($usuarios);   

$datos=array(
		'asunto'			=> 'Problemas para acceder',
		'mensaje'			=> $_GET['mensaje'],
		'fecha_inicio'		=> 0,
		'fecha_final'		=> 0,
		'grupo'				=> 0,
		'id'				=> 0,
		'email_1'			=> $_GET['email'],
		'email_2'			=> '-',
		'email_3'			=> '-',
		'id_usuario'		=> '-',
		'fecha'				=> date('Y-m-d H:i:s'),
		'id_tipo_reporte'	=> 0);

$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
$cabeceras .= 'From: '.$config['remitente'].' <'.$config['correo'].'>' . "\r\n";

$mensaje	= $datos['mensaje'];
$mensaje	.= "<br>email: <b>".$datos['email_1']."</b>";

do{
	mail($row_usuario['usuario_email'], $datos['asunto'], $mensaje, $cabeceras);
	
}while($row_usuario = mysql_fetch_array($usuarios));		

insertCorreo($datos);
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../<?php echo $url['librerias_url']?>css/login.css">
<link href="../<?php echo $url['librerias_url']?>bootstrap/css/<?php echo $config['css']?>.css" rel="stylesheet" media="screen">

<script src="../<?php echo $url['librerias_url']?>bootstrap/js/jquery.js"></script>
<script src="../<?php echo $url['librerias_url']?>bootstrap/js/bootstrap.js"></script>
<head>
<body>

<div class="container">
	<div class="login-container">
		<div id="output"></div>
		<div class="avatar" style="background-image: url('user.png');"></div>
		<div class="form-box">
			<div class="row">
				<div class="alert alert-success" role="alert">
					Mensaje enviado, la administración se comunicara con usted.
				</div>
			</div>		
			<p>
				<span>
					<a href="acceso.php" class="btn btn-default" type="submit" name="enviar" value="Ingresar">
						Volver
					</a>
				</span>
			</p>
		</div>
	</div>
</div> 

</body>
</html>

