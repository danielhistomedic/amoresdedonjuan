<?php

/**
 * Punto de Entrada Principal del Sistema (Front Controller)
 * Fraccionamiento Amores de Don Juan
 */

//==================================================================
// [ Versión del Sistema y Directorio Raíz ]
const VERSION_SYS = "1.4.150";
const DIR         = __DIR__;

//==================================================================
// [ Carga de Archivos de Configuración y Helpers ]
require_once DIR . '/Config/Config.php';
require_once DIR . '/Config/Modulos.php';
require_once DIR . '/Helpers/Helpers.php';
require_once DIR . '/Helpers/LogErrors.php';
include_once DIR . '/Libraries/Core/Session.php';

//==================================================================
// [ Configuración de Reporte de Errores según el Entorno ]
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

//==================================================================
// [ Procesamiento y Sanitización de la URL ]
$urlInput = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
if (empty($urlInput) && isset($_GET['url'])) {
    $urlInput = filter_var($_GET['url'], FILTER_SANITIZE_URL);
}

$url = !empty($urlInput) ? rtrim(trim($urlInput), '/') : 'login';
if (empty($url)) {
    $url = 'login';
}

$arrUrl     = explode('/', $url);
$controller = $arrUrl[0];
$method     = !empty($arrUrl[1]) ? $arrUrl[1] : $arrUrl[0];
$params     = count($arrUrl) > 2 ? implode(',', array_slice($arrUrl, 2)) : '';

//==================================================================
// [ Autoload de Clases del Núcleo y Modelos ]
require_once DIR . '/Libraries/Core/Autoload.php';
require_once DIR . '/Libraries/Core/AutoloadModel.php';

//==================================================================
// [ Carga Inicial y Despacho del Controlador ]
require_once DIR . '/Libraries/Core/Load.php';
