<?php

require_once('vendor/autoload.php');

//==================================================================
// [ Zona Horaria ]
date_default_timezone_set('America/Mexico_City');

//==================================================================
// [ Entorno y Detección Automática ]
$_host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
// Normalizar: quitar puerto si lo hay (ej. localhost:8080)
$_hostClean = explode(':', $_host)[0];

$isLocalhost = in_array($_hostClean, ['localhost', '127.0.0.1']);
$isProduccion = !$isLocalhost;

// Protocolo real del request
$_scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
// URL base dinámica = protocolo + host exacto que usó el navegador
$_baseUrlDynamic = $_scheme . '://' . $_host;

if ($isLocalhost) {
    // -------------------------------------------------------
    // Entorno: DESARROLLO (Local)
    // -------------------------------------------------------
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    define('BASE_HOSTING', 'https://amoresdedonjuan.org');
    define('BASE_URL', 'http://localhost/amores_sandbox');
    define('DB_HOST', 'histoclin.mx');
    define('DB_NAME', 'histocli_amores');
    define('DB_USER', 'histocli_amores');
    define('DB_PASSWORD', 'k-%sh9SJbsvT');
    define('DB_PORT', '3306');
    define('ENVIRONMENT', 'development');
} else {
    // -------------------------------------------------------
    // Entorno: PRODUCCIÓN — amoresdedonjuan.org
    // -------------------------------------------------------
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);

    define('BASE_HOSTING', 'https://amoresdedonjuan.org');
    define('BASE_URL', $_baseUrlDynamic);
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'histocli_amores');
    define('DB_USER', 'histocli_amores');
    define('DB_PASSWORD', 'k-%sh9SJbsvT');
    define('DB_PORT', '3306');
    define('ENVIRONMENT', 'production');
}

const DB_CHARSET = "charset=utf8";

//==================================================================
// [ Datos de conexión FTP Portal Residentes ]
const FTP_SERVER = "ftp.histoclin.mx";
const FTP_USUARIO = "ftp_amores_residentes@residentes.amoresdedonjuan.org";
const FTP_PASSWORD = "k-%sh9SJbsvD";
// /home/histocli/residentes.amoresdedonjuan.org

//==================================================================
// [ Visitas ]
/**
 * Tiempo dado en minutos
 */
const VIGENCIA_QR = "250";

//==================================================================
// [ Delimitadores decimal y millar Ej. 24,1989.00 ]
const SPD = ".";
const SPM = ",";
const SMONEY = "$";

//==================================================================
// [ LOGGER ]
const LOG_CHANNEL = "amores";
const LOG_PATH = "Log";

//==================================================================
// [ Datos envio de correo ]
const NOMBRE_REMITENTE = "Administración Fracc. Amores de Don Juan";
const EMAIL_REMITENTE = "notificaciones@amoresdedonjuan.org";

const NOMBRE_SISTEMA = "Sistema de Gestión Fracc. Amores de Don Juan";

define('WEB_LOGIN', BASE_URL . "/login");
const WEB_LOGIN_RESIDENTES = "https://residentes.amoresdedonjuan.org/login";

const NOMBRE_EMPRESA = "Fracc. Amores de Don Juan de Tellez";
const DOMICILIO_EMPRESA = "Avenida Victoria S/N, Col. Jagüey de Téllez,";
const DOMICILIO_EMPRESA_2 = "Mpio. Zempoala, Hidalgo, México. C.P. 43845";
const WEB_EMPRESA = "www.amoresdedonjuan.com.mx";
const DIRECCION = "Calle Victoria";
const TELEMPRESA = "55 5334 7966, 56 1415 4967";
const EMAIL_EMPRESA = "administracion@amoresdedonjuan.org";

//==================================================================
// [ Otras Constantes ]
// PREFIJO_SESSION es diferente por entorno para evitar conflictos de cookies entre local y prod.
define('PREFIJO_SESSION', !$isProduccion ? 'amor_local_02022419_' : 'amor_02022419_');

//==================================================================
// [ ssl ]
const KEY = "&25/Amor*46)==";
const METHODENCRIPT = "AES-128-ECB";

//==================================================================
// [ Ingresos y Egresos - Saldo Inicial Administración Anterior ]
const SALDO_INICIAL_CUENTA = 17004.75;
