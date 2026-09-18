<?php

/**
 * Archivo de Configuración General del Sistema
 * Fraccionamiento Amores de Don Juan
 */

//==================================================================
// [ Autoload de Vendor / Composer ]
$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
} elseif (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

//==================================================================
// [ Entorno de la Aplicación ]
// Valores permitidos: 'development' | 'production'
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'production');
}

//==================================================================
// [ URL del Proyecto ]
const BASE_HOSTING = (ENVIRONMENT === 'development') ? "http://localhost" : "https://amoresdedonjuan.org";
const BASE_URL     = (ENVIRONMENT === 'development') ? "http://localhost/amores_sandbox" : "https://amoresdedonjuan.org";

//==================================================================
// [ Zona Horaria ]
date_default_timezone_set('America/Mexico_City');

//==================================================================
// [ Datos de Conexión a la Base de Datos ]
const DB_HOST     = (ENVIRONMENT === 'development') ? "localhost" : "localhost";
const DB_NAME     = "histocli_amores";
const DB_USER     = "histocli_amores";
const DB_PASSWORD = 'k-%sh9SJbsvT';
const DB_CHARSET  = "charset=utf8";

//==================================================================
// [ Datos de Conexión FTP Portal Residentes ]
const FTP_SERVER   = "ftp.histoclin.mx";
const FTP_USUARIO  = "ftp_amores_residentes@residentes.amoresdedonjuan.org";
const FTP_PASSWORD = "k-%sh9SJbsvD";

//==================================================================
// [ Configuración de Visitas ]
/** Tiempo dado en minutos */
const VIGENCIA_QR = "150";

//==================================================================
// [ Formato de Moneda y Números ]
const SPD    = ".";
const SPM    = ",";
const SMONEY = "$";

//==================================================================
// [ Configuración de Bitácora / Logging ]
const LOG_CHANNEL = "amores";
const LOG_PATH    = "Log";

//==================================================================
// [ Datos de Envío de Correo ]
const NOMBRE_REMITENTE = "Administración Fracc. Amores de Don Juan";
const EMAIL_REMITENTE  = "notificaciones@amoresdedonjuan.org";

//==================================================================
// [ Información del Sistema y Empresa ]
const NOMBRE_SISTEMA       = "Sistema de Gestión Fracc. Amores de Don Juan";
const WEB_LOGIN            = BASE_URL . "/login";
const WEB_LOGIN_RESIDENTES = "https://residentes.amoresdedonjuan.org/login";

const NOMBRE_EMPRESA      = "Fracc. Amores de Don Juan de Tellez";
const DOMICILIO_EMPRESA   = "Avenida Victoria S/N, Col. Jagüey de Téllez,";
const DOMICILIO_EMPRESA_2 = "Mpio. Zempoala, Hidalgo, México. C.P. 43845";
const WEB_EMPRESA         = "www.amoresdedonjuan.com.mx";
const DIRECCION           = "Calle Victoria";
const TELEMPRESA          = "55 5334 7966, 56 1415 4967";
const EMAIL_EMPRESA       = "administracion@amoresdedonjuan.org";

//==================================================================
// [ Sesión y Seguridad ]
const PREFIJO_SESSION = "amor_02022419_";
const KEY             = "&25/Amor*46)==";
const METHODENCRIPT   = "AES-128-ECB";

//==================================================================
// [ Finanzas - Saldo Inicial ]
const SALDO_INICIAL_CUENTA = 17004.75;
