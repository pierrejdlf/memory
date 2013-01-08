<?
date_default_timezone_set('Europe/Paris');
$timestamp = $_GET['time']/1000.0;
echo date("Y/m/d,H:i",$timestamp);

?>