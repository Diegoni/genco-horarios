<?php 
    session_start(); 
    include('../config/database.php');
    $perfil = mysql_query("SELECT * FROM usuarios WHERE usuario_id='".$_GET['id']."'") or die(mysql_error()); 
    if(mysql_num_rows($perfil)) { // Comprobamos que exista el registro con la ID ingresada 
        $row = mysql_fetch_array($perfil); 
        $id = $row["usuario_id"]; 
        $nick = $row["usuario_nombre"]; 
        $email = $row["usuario_email"]; 
        $freg = $row["usuario_freg"]; 
?> 
        <strong>Nick:</strong> <?=$nick?><br /> 
        <strong>Email:</strong> <?=$email?><br /> 
        <strong>Registrado el:</strong> <?=$freg?><br /> 
<?php 
    }else { 
?> 
        <p>El perfil seleccionado no existe o ha sido eliminado.</p> 
<?php 
    } 
?>