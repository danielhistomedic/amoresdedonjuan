<?php

// Creamos un instancia de la clase ZipArchive
$zip = new ZipArchive();

// Creamos y abrimos un archivo zip temporal
$zip->open("recibos.zip", ZipArchive::CREATE);

// Añadimos un directorio
$dir = 'otros';
$zip->addEmptyDir($dir);

// Añadimos un archivo en la raiz del zip.
$zip->addFile("Assets/files/docs/reglamento.pdf", "reglamento_p.pdf");
$zip->addFile("Assets/files/docs/6a2d632a.pdf", "6a2d632a.pdf");

//Añadimos un archivo dentro del directorio que hemos creado
$zip->addFile("Assets/files/temp/comprobante.pdf", $dir . "/comprobante_p.pdf");
$zip->addFile("Assets/files/temp/qr.png", $dir . "/qr.png");

// Una vez añadido los archivos deseados cerramos el zip.
$zip->close();

// Creamos las cabezeras que forzaran la descarga del archivo como archivo zip.
header("Content-type: application/octet-stream");
header("Content-disposition: attachment; filename=recibos.zip");

// leemos el archivo creado
readfile('recibos.zip');

// Por último eliminamos el archivo temporal creado
unlink('recibos.zip');//Destruye el archivo temporal
