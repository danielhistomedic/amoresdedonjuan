<?php

if (!defined('K_PATH_CACHE')) {
    $K_PATH_CACHE = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
    if (substr($K_PATH_CACHE, -1) != '/') {
        $K_PATH_CACHE .= '/';
    }
    define('K_PATH_CACHE', $K_PATH_CACHE);
}

$file_id = 9999991;
$type = 'temp';
$valor = tempnam(K_PATH_CACHE, '__tcpdf_' . $file_id . '_' . $type . '_' . md5('asaa') . '_');

echo $valor;

echo '</br>';
echo '</br>';
echo '</br>  otr';


echo __DIR__;
echo '</br>';
echo '</br>';
echo '</br>  otr';


echo __FILE__;
