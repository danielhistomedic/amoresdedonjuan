<?php

$ftp_server = "ftp.histoclin.mx";
$ftp_usuario = "ftp_amores_residentes@residentes.amoresdedonjuan.org";
$ftp_pass = "k-%sh9SJbsvD";
$conn_id = ftp_connect($ftp_server);

$lr = ftp_login($conn_id, $ftp_usuario, $ftp_pass);

if ((!$conn_id) || (!$lr)) {
    echo  'no se pudo conectar';
} else {
    echo 'conectado correctamente';
}

$destination_ftp = "Assets/test_dest.jpg";
$tmp_name = "C:\\xampp\\tmp\\test.jpg";
ftp_pasv($conn_id, true);
$upload = ftp_put($conn_id, $destination_ftp, $tmp_name, FTP_ASCII);
if ($upload) {
    echo "arhivo subido";
} else {
    echo "error al cargar";
}


ftp_close($conn_id);
