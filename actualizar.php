<?php
 
    if($_POST['actualizar']){
        $nombre = $_POST['nombre'];
        
        $link = new Conexion();
        mysql_query("UPDATE usuario SET nombre='$nombre' WHERE id='1'",$link->conectarBD())or die(mysql_error());
        echo "<script>opener.location.reload();window.close();</script>";
    }
?>
<script type="text/javascript">
function cargar(){
    opener.location.reload();
    window.close();
}
</script>
    <form method="post">
        <input type="text" name="nombre" id="nombre" />
        <input type="submit" name="actualizar" id="actualizar" value="actualizar" onclick="cargar()"/>
   </form>