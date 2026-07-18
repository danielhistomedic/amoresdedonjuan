<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php



    // $datos = unserialize(file_get_contents("http://www.geoplugin.net/php.gp"));


    ?>

    <pre>
    <?php
    // print_r($datos);
    // $latitud = $datos['geoplugin_latitude'];
    // $longitud = $datos['geoplugin_longitude'];

    ?>
</pre>

    <div id="position_act" style="height: 400px; width: 100%;">

    </div>

    <div id="map" style="height: 400px; width: 100%;">

    </div>

    <script>
        function initMap() {

            var x = document.getElementById('map');

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "No es compatible tu navegador";
            }



            function showPosition(position) {

                var coordenadas = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                showGoogleMap(coordenadas);

            }


            function showGoogleMap(coordenadas) {

                var mapa = new google.maps.Map(document.getElementById('map'), {
                    zoom: 12,
                    center: coordenadas
                });

                var marker = new google.maps.Marker({
                    position: coordenadas,
                    map: mapa
                });

            }



            // navigator.geolocation.getCurrentPosition(function(position) {

            //     var latitude = position.coords.latitude;
            //     var longitude = position.coords.longitude;
            //     // var geolocate = new google.maps.LatLng(lat, lng);
            //     // var infoWindow = new google.maps.infoWindow({
            //     //     map: map,
            //     //     position: geolocate,
            //     //     content: '<h1>Esta es tu ubicación con Geolocation</h1>' +
            //     //         '<h1>Latitud: ' + lat + '< /h1>' +
            //     //         '<h1>Latitud: ' + lng + '</h1>'
            //     // });
            //     // map.setCenter(geolocate);
            // });


            // ok ok ok ok
            // var coordenadas = {
            //     lat: latitude_act,
            //     lng: longitude_act
            // };

            // var mapa = new google.maps.Map(document.getElementById('map'), {
            //     zoom: 12,
            //     center: coordenadas
            // });

            // var marker = new google.maps.Marker({
            //     position: coordenadas,
            //     map: mapa
            // });


            // var map;
            // var mapOptions = {
            //     zoom: 15,
            //     mapTypeId: google.maps.MapTypeId.ROADMAP
            // };

            // map = new google.maps.Map(document.getElementById('map'), mapOptions);


        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBErDcZ7gTxivzTlQIHCDy5pALPUonoR_8&callback=initMap" async defer></script>

</body>

</html> -->

<?php

// $today = getdate();
// echo '<pre>';
// print_r($today);

// echo '</pre>';

// echo $today['year'] . '-' . str_pad($today['mon'], 2, "0", STR_PAD_LEFT) . '-' . $today['mday'];


// // [seconds] => 47
// // [minutes] => 48
// // [hours] => 15
// // [mday] => 13
// // [wday] => 0
// // [mon] => 6
// // [year] => 2021
// // [yday] => 163
// // [weekday] => Sunday
// // [month] => June
// // [0] => 1623617327



// // Mes actual en inglés, de January a December
// date("F");
// // Mes actual en 2 dígitos y con 0 en caso del 1 al 9, de 1 a 12
// date("m");
// // Mes actual en texto en 3 dígitos en inglés, de Jan a Dec
// date("M");
// // Mes actual en digitos sin 0 inicial, de 1 a 12
// date("n");
// // Número de días del mes actual, de 28 a 31
// date("t");


// // Detectar si el año es bisiesto, 1 es bisiesto y 0 no bisiesto
// date("L");
// // Año actual con 4 dígitos, ej 2013
// date("Y");
// // Año actual con 2 dígitos, ej 13
// date("y");


// // Antes del mediodía, despues del mediodía, am o pm (minúsculas)
// date("a");
// // Antes del mediodía, despues del mediodía, AM o PM (mayúsculas)
// date("A");
// // Horario de 12 horas sin ceros, de 1 a 12
// date("g");
// // Horario de 12 horas con ceros, de 01 a 12
// date("h");
// // Horario de 24 horas sin ceros, de 0 a 23
// date("G");
// // Horario de 24 horas con ceros, de 01 a 23
// date("H");
// // minutos con ceros iniciales
// date("i");
// // segundos con ceros iniciales
// date("s");

$valida = '000220';

echo intval($valida);

echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';

// echo '<br>';
date_default_timezone_set('America/Mexico_City');


$fecha_actual = date("Y-m-d H:i:s");
echo $fecha_actual;
echo "<br>";

//sumo 1 día
$fecha_base = "";
echo date("Y-m-d H:i:s", strtotime($fecha_actual . "+ 30 minutes"));
echo "<br>";

//resto 1 día
echo date("Y-m-d", strtotime($fecha_actual . "- 1 days"));
echo "<br>";


echo "<br>";


//Dateadd
$date_y = date("Y");
$date_m = date("m");
$date_str = $date_y . "-" . $date_m . "-05";
$date = date_create($date_str);
date_add($date, date_interval_create_from_date_string("1 month"));
echo date_format($date, "Y-m-d");

echo "<br>";

// echo $date_str;
// echo "<br>";

// // $date = date_create($date_str);
// // echo $date;
// echo "<br>";



// $date = date_create($date_str);
// date_add($date, date_interval_create_from_date_string("1 month"));
// echo date_format($date, "Y-m-d");

//compara fechas actuales
$fecha_actual = strtotime(date("Y-m-d H:i:s"));
$fecha_entrada = strtotime("2022-04-06 14:40:20");

if ($fecha_actual > $fecha_entrada) {
    echo "La fecha actual es mayor a la fecha_entrada.";
} else {
    echo "La fecha fecha_entrada es menor";
}

echo "<br>";





?>