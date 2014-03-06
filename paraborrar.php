<?function devuelveArrayFechasEntreOtrasDos($fechaInicio, $fechaFin)
{
$arrayFechas=array();
$fechaMostrar = $fechaInicio;

while(strtotime($fechaMostrar) <= strtotime($fechaFin)) {
$arrayFechas[]=$fechaMostrar;
$fechaMostrar = date("d-m-Y", strtotime($fechaMostrar . " + 1 day"));
}

return $arrayFechas;
} 

$arrayFechas=devuelveArrayFechasEntreOtrasDos('18-01-2010', '10-02-2010');


foreach($arrayFechas as $valor){
echo $valor;
echo "<br>";
}







?>
