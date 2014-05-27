<? 
// seguir el siguiente tutorial
// http://stefano.salvatori.cl/blog/2010/12/03/leer-archivo-mdb-access-de-php-en-ubuntu/
ini_set('display_errors','Off'); 
//local a phpmyadmin 
        $username="root"; 
        $password="bluepill"; 
        $database="controlfinal2"; 
        $url="localhost"; 
        mysql_connect($url,$username,$password); 
        @mysql_select_db($database) or die( "No pude conectarme a la base de datos"); 
        mysql_query("SET NAMES 'utf8'"); 
?> 
<?    //debe ser de sistema no de usuario 
        $usuario =""; 
        $clave=""; 
     
        $dsn = "DSS";  
        $mdbFilename="D:\Genco\attBackup"; 
 
//Nota: la conexion se debe hacer por sistema, en el caso de que falle probar por archivo 
        //ODBC por sistema 
        $ODBC=odbc_connect($dsn, $usuario, $clave); 
         
        //archivo 
        //$ODBC = odbc_connect("Driver={Microsoft Access Driver (*.mdb)};Dbq=$mdbFilename", $user, $password); 
         
        if (!$ODBC){ 
            exit("<strong><BR>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>"); 
        }    
 
?>