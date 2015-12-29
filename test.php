<?php
include_once("menu.php");    
include_once($url['models_url']."checkinout_model.php");  

$checkinout = new Checkinout();


$checkinout->getRegistros();