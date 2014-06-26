<?php
// Codigo a insertar al principio de la web
function getTiempo() { 
        list($usec, $sec) = explode(" ",microtime()); 
        return ((float)$usec + (float)$sec); 
  } 
$TiempoInicial = getTiempo(); 
?>
<?php
// Todo el contenido de la web
//Tanto PHP
//Como HTML
?>
<?php
//Código a insertar al final de la web
$TiempoFinal = getTiempo(); 
$Tiempo = $TiempoFinal - $TiempoInicial; 
$Tiempo = round($Tiempo,6); 
echo "Esta web ha cargado en $Tiempo segundos."; 
?>