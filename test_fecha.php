<?php

date_default_timezone_set('America/Mexico_City');

$fecha_actual = date("Y-m-d H:i:s");
echo $fecha_actual;
echo "<br>";

// //sumo 30 minutos
// echo date("Y-m-d H:m:i", strtotime($fecha_actual . "+ 30 minutes"));
// echo "<br>";
