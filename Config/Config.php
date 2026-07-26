<?php

require_once('vendor/autoload.php');


//==================================================================
// [ URL del proyecto ]
// const BASE_HOSTING = "http://localhost"; //Desarrollo 
const BASE_HOSTING = "https://amoresdedonjuan.org"; //Producción


// const BASE_URL = "http://localhost/amores_sandbox"; //Desarrollo 
const BASE_URL = "https://amoresdedonjuan.org";  //Producción

//==================================================================
// [ Zona Horaria ]
date_default_timezone_set('America/Mexico_City');


//==================================================================
// [ Datos de conexión de la Base de Datos ]

// const DB_HOST = "histoclin.mx"; //Desarrollo
const DB_HOST = "localhost"; //Producción

// const DB_NAME = "histocli_amores"; //Desarrollo
const DB_NAME = "histocli_amores"; //Producción 
const DB_USER = "histocli_amores";
const DB_PASSWORD = 'k-%sh9SJbsvT';
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
const VIGENCIA_QR = "150";

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

const WEB_LOGIN = BASE_URL . "/login";
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
const PREFIJO_SESSION = "amor_02022419_";

//==================================================================
// [ ssl ]
const KEY = "&25/Amor*46)==";
const METHODENCRIPT = "AES-128-ECB";


//==================================================================
// [ Ingresos y Egresos - Saldo Inicial Administración Anterior ]
const SALDO_INICIAL_CUENTA = 17004.75;


//==================================================================
// [ STRIPE ]
