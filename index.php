<?php

//******************************** */
// [ Versión del Sistema ]
const VERSION_SYS = "1.4.150";
const DIR = __DIR__;

//******************************** */
// [ Desplegar Errores PHP en el navegador ]
error_reporting(0);

//******************************** */
// [ Se cargan las constantes y otras funciones del proyecto. ]
require_once('Config/Config.php');
require_once('Config/Modulos.php');
require_once('Helpers/Helpers.php');
require_once('Helpers/LogErrors.php');
include_once 'Libraries/Core/Session.php';

// //******************************** */
// // [ Desplegar Errores PHP en el navegador según el Entorno ]
// if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
//     ini_set('display_errors', 1);
//     ini_set('display_startup_errors', 1);
//     error_reporting(E_ALL);
// } else {
//     // Desactivar toda las notificaciónes del PHP en producción
//     error_reporting(0);
// }

//******************************** */
// [ Se obtienen las variables de las url desde el .htacces ]

$url = !empty($_GET['url']) ? $_GET['url'] : 'login';

$arrUrl = explode("/", $url);
$controller = $arrUrl[0];
$method = $arrUrl[0];
$params = "";

if (!empty($arrUrl[1])) {
    if ($arrUrl[1] != "") {
        $method = $arrUrl[1];
    }
}

if (!empty($arrUrl[2])) {
    if ($arrUrl[2] != "") {
        for ($i = 2; $i < count($arrUrl); $i++) {
            $params .= $arrUrl[$i] . ',';
        }
        $params = trim($params, ',');
    }
}


//******************************** */
// [ Se registran las clases obtenidas desde la url. ]
require_once('Libraries/Core/Autoload.php');
require_once('Libraries/Core/AutoloadModel.php');


//******************************** */
// [ Se hace la carga inicial de la página obtenida de los controladores. ]
require_once('Libraries/Core/Load.php');
