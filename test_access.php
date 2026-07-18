<?php


// $mdbFilename = "C:/ZKTeco/ZKAccess3.5/Access.mdb";
// $conexión = odbc_connect("Driver={Microsoft Access Driver (*.mdb)};Dbq=" . $mdbFilename . "", "", "");


// $conexión = odbc_connect("zkteco", "", "");

if (odbc_connect("zkteco3", "", "")) {
    echo "conectado";
} else {
    echo "error al conectar";
};
