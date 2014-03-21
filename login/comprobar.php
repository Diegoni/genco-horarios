<?php 
    session_start(); 
    include('../config/database.php'); 
    if(isset($_POST['enviar'])) { // comprobamos que se hayan enviado los datos del formulario 
        // comprobamos que los campos usuarios_nombre y usuario_clave no estén vacíos 
        if(empty($_POST['usuario_nombre']) || empty($_POST['usuario_clave'])) {?> 
			<div id="boxlogin">
            <? echo "El usuario o el password no han sido ingresados. <a href='javascript:history.back();'>Reintentar</a>"; ?>
			</div>
        <? }else { 
            // "limpiamos" los campos del formulario de posibles códigos maliciosos 
            $usuario_nombre = mysql_real_escape_string($_POST['usuario_nombre']); 
            $usuario_clave = mysql_real_escape_string($_POST['usuario_clave']); 
            $usuario_clave = md5($usuario_clave); 
            // comprobamos que los datos ingresados en el formulario coincidan con los de la BD 
            $sql = mysql_query("SELECT * FROM usuarios WHERE usuario_nombre='".$usuario_nombre."' AND usuario_clave='".$usuario_clave."'"); 
            if($row = mysql_fetch_array($sql)) { 
                $_SESSION['usuario_id'] = $row['usuario_id']; // creamos la sesion "usuario_id" y le asignamos como valor el campo usuario_id 
                $_SESSION['usuario_nombre'] = $row["usuario_nombre"]; // creamos la sesion "usuario_nombre" y le asignamos como valor el campo usuario_nombre 
				$_SESSION['id_tipousuario'] = $row["id_tipousuario"]; 
                header("Location: ../index.php"); 
            }else { 
?>
 				<div id="boxlogin">
                Error, no se ha podido conectar <a href="acceso.php">Reintentar</a> 
				</div>
<?php 
            } 
        } 
    }else { 
        header("Location: acceso.php"); 
    } 
?>
<link rel="stylesheet" href="../css/login.css">