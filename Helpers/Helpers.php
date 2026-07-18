<?php
// require_once('vendor/autoload.php');

use Lib\phpMailer\PHPMailer;
use Lib\phpMailer\SMTP;

//==================================================================
// [ Versión del Sistema ]

/**
 * Función que devuelve el valor de la Versión Actual del sistema.
 * 
 * @return string VERSION_SYS
 * 
 */
function version()
{
    return VERSION_SYS;
}

//==================================================================
// [ Retorna la url del proyecto ]

/**
 * Función que devuelve la Url base del proyecto.
 * 
 * @return string BASE_URL
 * 
 */
function base_url()
{
    return BASE_URL;
}

//==================================================================
// [ Retorna la url de Assets ]

/**
 * Función que devuelve la Url base de archivos css, js y pluggins del proyecto.
 * 
 * @return string BASE_URL . "/Assets"
 * 
 */
function base_url_assets()
{
    return BASE_URL . "/Assets";
}

/**
 * Función que devuelve la Url base de archivos css, js y pluggins del proyecto.
 * 
 * @return string BASE_URL . "/Assets"
 * 
 */
function media()
{
    return BASE_URL . "/Assets";
}

//==================================================================
// [ Controllers Array Repsonse ]

/**
 * Funcion para obtener el arrayRepsonse de Controllers
 * Default, Error.
 * 
 */
function getResponse($mensaje, $tipo_respuesta = "error", $mostrar_mensaje = true, $tiempo = 3000): array
{

    if ($tipo_respuesta == "error") {
        getLoggerSystem()->warning($mensaje);
    }

    $arrResponse = array(
        'respuesta' => $tipo_respuesta,
        'mostrar_mensaje' => $mostrar_mensaje,
        'tiempo' => $tiempo,
        'mensaje' => $mensaje
    );

    /*-------------------------------------------
    [ Retorna respuesta ]*/
    return $arrResponse;
}

//==================================================================
// [ Muestra información formateada de un array ]

/**
 * Función para mostrar en pantalla un arreglo con formato amigable a la lectura
 * 
 * @param array $data array que se le dará formato.
 * 
 * @return string $format Cadena formateada.
 * 
 */
function dep($data)
{
    $format = print_r('<pre>');
    $format .= print_r($data);
    $format .= print_r('</pre>');
    return $format;
}

//==================================================================
// [ Modals ]

/**
 * Funcion para cargar con require_once el template del Modal de Formularios.
 * 
 * @param string $nameModal
 * Nombre del archivo php que contiene el template que se desea cargar
 * 
 * @param array $data
 * Array con valores personalizados que se pueden enviar al cargar el template
 * 
 */
function getModal(string $nameModal, $data)
{
    $view_modal = "Views/Template/Modals/{$nameModal}.php";
    require_once $view_modal;
}

//==================================================================
// [ Limpiar Cadena / Evitar inyección sql ]

/**
 * Funcion para limpiar de inyección de codigo un valor de entrada.
 * 
 */
function strClean($strCadena)
{
    $strCadena = (string)$strCadena;
    $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena);
    $string = trim($string); //Elimina espacios en blanco al inicio y al final
    $string = stripslashes($string); // Elimina las \ invertidas
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("<script src>", "", $string);
    $string = str_ireplace("<script type=>", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("OR '1'='1", "", $string);
    $string = str_ireplace('OR "1"="1"', "", $string);
    $string = str_ireplace('OR ´1´=´1´', "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("LIKE '", "", $string);
    $string = str_ireplace('LIKE "', "", $string);
    $string = str_ireplace("LIKE ´", "", $string);
    $string = str_ireplace("OR 'a'='a", "", $string);
    $string = str_ireplace('OR "a"="a', "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("==", "", $string);
    return $string;
}

function clear_cadena(string $cadena)
{
    //Reemplazamos la A y a
    $cadena = str_replace(
        array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
        array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $cadena
    );

    //Reemplazamos la I y i
    $cadena = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena
    );

    //Reemplazamos la O y o
    $cadena = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena
    );

    //Reemplazamos la U y u
    $cadena = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena
    );

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç', ',', '.', ';', ':'),
        array('N', 'n', 'C', 'c', '', '', '', ''),
        $cadena
    );
    return $cadena;
}

//==================================================================
// [ Genera una contraseña de 10 caracteres ]

/**
 * Funcion para generar un password aleatorio .
 * 
 */
function passGenerator($lenght = 7)
{
    $pass = "";
    $longitudPass = $lenght;
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $longitudCadena = strlen($cadena);
    for ($i = 0; $i < $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $pass .= substr($cadena, $pos, 1);
    }
    return $pass;
}

//==================================================================
// [ Genera un token ]

/**
 * Funcion para generar un token para el reset de Password.
 * 
 */
function token()
{
    $r1 = bin2hex(random_bytes(10));
    $r2 = bin2hex(random_bytes(10));
    $r3 = bin2hex(random_bytes(10));
    $r4 = bin2hex(random_bytes(10));

    $token = $r1 . '-' . $r2 . '-' . $r3 . '-' . $r4;

    return $token;
}

//==================================================================
// [ Funciones Diversas ]

/**
 * Funcion para dar formato a un valor. 
 * Formato de moneda.
 * 
 */
function formatMoney($cantidad)
{
    $cantidad =  number_format($cantidad, 2, SPD, SPM);
    return '$ ' . $cantidad;
}

/**
 * Dar formato a fecha hora.
 * 
 * @param string $fecha
 * Fecha en fromato 'd/m/Y H:i'
 * 
 * @return string $fecha_formatted
 * Fecha en Formato 'd/m/Y H:i'
 * 
 */
function formatDateTime($fecha)
{
    if ($fecha == "") {
        return "";
    }

    $formato = 'Y-m-d H:i:s';
    $fecha_format = \DateTime::createFromFormat($formato, $fecha);
    if (!$fecha_format) {
        return "";
    }
    $fecha_formatted = $fecha_format->format('d/m/Y H:i');
    return $fecha_formatted;
}

/**
 * Dar formato a hora.
 * 
 * @param string $fecha
 * Fecha en fromato 'Y-m-d H:i:s'
 * 
 * @return string $fecha_formatted
 * Fecha en Formato 'H:i'
 * 
 */
function formatTime($fecha)
{
    if ($fecha == "") {
        return "";
    }
    $formato = 'Y-m-d H:i:s';
    $fecha_format = \DateTime::createFromFormat($formato, $fecha);
    if (!$fecha_format) {
        return "";
    }
    $fecha_formatted = $fecha_format->format('H:i');
    return $fecha_formatted;
}

/**
 * Dar formato a fecha.
 * 
 * @param string $fecha
 * Fecha en fromato 'Y-m-d'
 * 
 * @return string $fecha_formatted
 * Fecha en Formato 'd/m/Y'
 * 
 */
function formatDate($fecha)
{
    if ($fecha == "") {
        return "";
    }
    $formato = 'Y-m-d';
    $fecha_format = \DateTime::createFromFormat($formato, $fecha);
    if (!$fecha_format) {
        return "";
    }
    $fecha_formatted = $fecha_format->format('d/m/Y');
    return $fecha_formatted;
}

/**
 * Dar formato a fecha-día.
 * 
 * @param string $fecha
 * Fecha en fromato 'Y-m-d H:i:s'
 * 
 * @return string $fecha_formatted
 * Fecha en Formato 'd'
 * 
 */
function formatDate_Dia($fecha)
{
    if ($fecha == "") {
        return "";
    }
    $formato = 'Y-m-d H:i:s';
    $fecha_format = \DateTime::createFromFormat($formato, $fecha);
    if (!$fecha_format) {
        return "";
    }
    $fecha_formatted = $fecha_format->format('d');
    return $fecha_formatted;
}

/**
 * Dar formato a fecha-mes.
 * 
 * @param string $fecha
 * Fecha en fromato 'Y-m-d H:i:s'
 * 
 * @return string $fecha_formatted
 * Fecha en Formato '%B'
 * 
 */
function formatDate_Mes($fecha)
{
    if ($fecha == "") {
        return "";
    }
    $num_mes = (int)date("n", strtotime($fecha));
    return format_Mes($num_mes);
}

/**
 * Dar formato a fecha-mes.
 * 
 * @param string $fecha
 * Fecha en fromato 'Y-m-d'
 * 
 * @return int $fecha_formatted
 * Fecha en Formato 'm'
 * 
 */
function formatDate_Mes2($fecha)
{
    if ($fecha == "") {
        return "";
    }
    $formato = 'Y-m-d';
    $fecha_format = \DateTime::createFromFormat($formato, $fecha);
    if (!$fecha_format) {
        return "";
    }
    $fecha_formatted = $fecha_format->format('m');
    return $fecha_formatted;
}


/**
 * Obtener nombre del mes.
 * 
 * @param int $mes
 * Mes en valor numerico
 * 
 * @return string $fecha_formatted
 * Fecha en Formato '%B'
 * 
 */
function format_Mes($mes): string
{

    $mes_nombre = '';

    switch ($mes) {

        case 1:
            $mes_nombre = 'Enero';
            break;

        case 2:
            $mes_nombre = 'Febrero';
            break;

        case 3:
            $mes_nombre = 'Marzo';
            break;

        case 4:
            $mes_nombre = 'Abril';
            break;

        case 5:
            $mes_nombre = 'Mayo';
            break;

        case 6:
            $mes_nombre = 'Junio';
            break;

        case 7:
            $mes_nombre = 'Julio';
            break;

        case 8:
            $mes_nombre = 'Agosto';
            break;

        case 9:
            $mes_nombre = 'Septiembre';
            break;

        case 10:
            $mes_nombre = 'Octubre';
            break;

        case 11:
            $mes_nombre = 'Noviembre';
            break;

        case 12:
            $mes_nombre = 'Diciembre';
            break;
    }

    $mes_nombre = strtoupper($mes_nombre);
    return $mes_nombre;
}

/**
 * Dar formato a fecha para DataBase.
 * 
 * @param string $fecha
 * Fecha en fromato 'd/m/Y'
 * 
 * @return string $fecha_formatted
 * Fecha en Formato 'Y-m-d'
 * 
 */
function formatDate_DB($fecha)
{

    $formato = 'd/m/Y';
    $fecha_format = \DateTime::createFromFormat($formato, $fecha);
    $fecha_formatted = $fecha_format->format('Y-m-d');
    return $fecha_formatted;
}

/**
 * Dar formato a fecha-año.
 * 
 * @param string $fecha
 * Fecha en fromato 'Y-m-d'
 * 
 * @return string $fecha_formatted
 * Fecha en Formato 'Y'
 * 
 */
function formatDate_Anio($fecha)
{

    $formato = 'Y-m-d H:i:s';
    $fecha_format = \DateTime::createFromFormat($formato, $fecha);
    $fecha_formatted = $fecha_format->format('Y');
    return $fecha_formatted;
}

/**
 * Dar formato a fecha-año.
 * 
 * @param string $fecha
 * Fecha en fromato 'Y-m-d H:i:s'
 * 
 * @return string $fecha_formatted
 * Fecha en Formato 'Y'
 * 
 */
function formatDate_Anio2($fecha)
{

    $formato = 'Y-m-d';
    $fecha_format = \DateTime::createFromFormat($formato, $fecha);
    $fecha_formatted = $fecha_format->format('Y');
    return $fecha_formatted;
}

/**
 * Dar formato a fecha-actual.
 * 
 * @return string $fecha_formatted
 * Fecha en Formato 'Y-m-d'
 * 
 */
function getFechaActual_Ymd(): string
{
    $today = getdate();
    $today_str = $today['year'] . '-' . str_pad($today['mon'], 2, "0", STR_PAD_LEFT) . '-' . $today['mday'];
    return $today_str;
}


//==================================================================
// [ Base Int Encoder ]

const codeset = "25463211";

/**
 * Funcion para hacer un encode a un texto
 * 
 */
function encode($n)
{
    $converted = substr(md5(codeset . $n), 2, 8);
    return $converted;
}

//==================================================================
// [ Mensjaje de Error para el Log ]

/**
 * Funcion para obtener los datos completos de un mensaje de error:
 * * getMessage
 * * getFile
 * * getLine
 * * getCode
 * 
 */
function getMensajeError($th): string
{

    $error_mensaje = "mensaje: " . $th->getMessage();
    $error_mensaje .= ", archivo: " . $th->getFile();
    $error_mensaje .= ", linea: " . $th->getLine();
    $error_mensaje .= ", codigo: " . $th->getCode();

    return $error_mensaje;
}

//==================================================================
// [ Funciones Varias ]

function getFile(string $url, $data)
{
    ob_start();
    require_once("Views/{$url}.php");
    $file = ob_get_clean();
    return $file;
}

/**
 * Ceros a la izquierda.
 * 
 * @return string $fecha_formatted
 * Fecha en Formato 'Y-m-d'
 * 
 */
function formatCerosIzquierda($value, $cantidad): string
{
    $valor = str_pad($value, $cantidad, "0", STR_PAD_LEFT);
    return $valor;
}


//==================================================================
// [ Envio de correos ]


/**
 * Funcion para enviar un correo electrónico.
 * 
 */
function sendEmail($data, $template): bool
{

    try {

        $response = false;

        $asunto = $data['asunto'];
        $emailDestino = $data['email'];
        $empresa = NOMBRE_REMITENTE;
        $remitente = EMAIL_REMITENTE;

        /*-------------------------------------------
        [ ENVIO DE CORREO ]*/

        $de = "MIME-Version: 1.0\r\n";
        $de .= "Content-type: text/html; charset=UTF-8\r\n";
        $de .= "From: {$empresa} <{$remitente}>\r\n";
        ob_start();
        require_once("Views/Template/Email/" . $template . ".php");
        $mensaje = ob_get_clean();
        $send = mail($emailDestino, $asunto, $mensaje, $de);
        $response = $send;
    } catch (\Throwable $th) {
        getLoggerSystem()->error(getMensajeError($th));
    }

    /*-------------------------------------------
    [ Retorna respuesta json_encode ]*/
    return $response;
}



function sendEmailPHPMailer($data, $template): bool
{

    try {

        $response = false;


        /*-------------------------------------------
        [ Varobales Generales ]*/

        $asunto = $data['asunto'];
        $emailDestino = $data['email'];
        $empresa = mb_convert_encoding(NOMBRE_REMITENTE, 'ISO-8859-1', 'UTF-8');
        $remitente = EMAIL_REMITENTE;


        /*-------------------------------------------
        [ Include the PHPMailer class ]*/
        //include('Libraries/phpMailer/PHPMailer.php');

        /*-------------------------------------------
        [ Cargar Template de Email ]*/
        ob_start();
        require("Views/Template/Email/" . $template . ".php");
        $message = ob_get_clean();


        /*-------------------------------------------
        [ Instanciar la Clase PHPMailer ]*/
        $mail = new PHPMailer();


        /*-------------------------------------------
        [ Agregar Parámteros de Configuración SMTP a la Clase PHPMailer ]*/
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                   //Enable verbose debug output
        $mail->isSMTP();                                         //Send using SMTP
        $mail->Host = 'mail.amoresdedonjuan.org';                    //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                  //Enable SMTP authentication
        $mail->Username = 'notificaciones@amoresdedonjuan.org';               //SMTP username
        $mail->Password = 'kZ=~xcaLA3#Y';                        //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 465;                                       //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        if (isset($data['attachment']) && !empty($data['attachment'])) {
            $mail->addAttachment($data['attachment']);
        }


        /*-------------------------------------------
        [ Agregar Parámteros de Configuración Recipients a la Clase PHPMailer ]*/

        // (Remitente) Establecer de quién proviene el correo electrónico 
        $mail->setFrom($remitente, $empresa);

        // (Destinatario) Establecer a quién se envía el correo electrónico
        $mail->addAddress($emailDestino, mb_convert_encoding($data['nombre_usuario'], 'ISO-8859-1', 'UTF-8'));


        /*-------------------------------------------
        [ Agregar Parámteros de Configuración Content a la Clase PHPMailer ]*/
        $mail->isHTML(true);                                                    //Set email format to HTML
        $mail->Subject =  mb_convert_encoding($asunto, 'ISO-8859-1', 'UTF-8');                                 //Set the subject
        $mail->MsgHTML(mb_convert_encoding($message, 'ISO-8859-1', 'UTF-8'));                                  //Set the message
        // $mail->Body    = mb_convert_encoding(emailBody($cliente_id_post,  $nombre_cliente_post, $numero_departamento_post, $mensaje, "all"), 'ISO-8859-1', 'UTF-8');
        $mail->AltBody = strip_tags(mb_convert_encoding($message, 'ISO-8859-1', 'UTF-8'));


        /*-------------------------------------------
        [ Send the email ]*/
        $response = $mail->send();

        /*-------------------------------------------
        [ Evaluar respuesta ]*/
        if (!$response) {
            getLoggerSystem()->error("Mailer Error: " . $mail->ErrorInfo);
        }
    } catch (\Throwable $th) {
        getLoggerSystem()->error(getMensajeError($th));
    }

    /*-------------------------------------------
    [ Retorna respuesta ]*/
    return $response;
}


//==================================================================
// [ Permisos ]


/**
 * Funcion para obtener los permisos de acceso a modulos para un usuario determinado
 * Y asignar variables de sesión
 * 
 */
function getPermisos(int $modulo_id): array
{

    try {

        $arrResponse = array();

        $permisos_model = new PermisosModel();
        $session = new Session();
        $idrol = $session->get('rol_id');

        $arrPermisos = $permisos_model->getPermisosMoudulo($idrol);
        $arrPermisosSession = array();
        $arrPermisosModSession = array();

        if (count($arrPermisos) > 0) {
            $arrPermisosSession = $arrPermisos;
            $arrPermisosModSession = isset($arrPermisos[$modulo_id]) ? $arrPermisos[$modulo_id] : "";
        }

        $arrResponse['permisos'] = $arrPermisosSession;
        $arrResponse['permisosMod'] = $arrPermisosModSession;

        $session->add('permisos', $arrPermisosSession);
        $session->add('permisosMod', $arrPermisosModSession);
    } catch (\Throwable $th) {
        getLoggerSystem()->error(getMensajeError($th));
    }

    /*-------------------------------------------
        [ Retorna respuesta ]*/
    return $arrResponse;
}



/**
 * Funcion para obtener los permisos de acceso a modulos para un usuario determinado
 * Sin asignar variables de sesión
 * 
 */
function getPermisosOnly(int $modulo_id): array
{

    try {

        $arrResponse = array();

        $permisos_model = new PermisosModel();
        $session = new Session();
        $idrol = $session->get('rol_id');

        $arrPermisos = $permisos_model->getPermisosMoudulo($idrol);
        $arrPermisosModSession = array();
        if (count($arrPermisos) > 0) {
            $arrPermisosModSession = isset($arrPermisos[$modulo_id]) ? $arrPermisos[$modulo_id] : "";
        }
        $arrResponse['permisosMod'] = $arrPermisosModSession;
    } catch (\Throwable $th) {
        getLoggerSystem()->error(getMensajeError($th));
    }

    /*-------------------------------------------
        [ Retorna respuesta ]*/
    return $arrResponse;
}


/**
 * Funcion para obtener los permisos de acceso a a todos los modulos por Rol, para un usuario determinado
 * 
 */
function getPermisosGlobal(): array
{

    try {

        $arrResponse = array();

        /*-------------------------------------------
        [ Obtener valores de Sessión ]*/
        $session = new Session();
        $usuario_id = $session->get('usuario_id');
        // $empresa_id = $session->get('empresa_id');
        // $sucursal_id = $session->get('sucursal_id');
        $unidad_medica_id = $session->get('unidad_medica_id');

        /*-------------------------------------------
        [ Obtener datos de usuario ]*/
        if (empty($usuario_id)) {
            return $arrResponse;
        }
        $usuario_model = new UsuariosModel;
        $usuario = $usuario_model->selectUsuario($usuario_id, $unidad_medica_id);

        /*-------------------------------------------
        [ Obtener json con los permisos de acuerdo al rol de usuario ]*/
        $permisos_model = new PermisosModel();
        $idrol = $usuario['rol_id'];
        $permisos = $permisos_model->getPermisosMoudulo($idrol);
        $arrResponse = $permisos;
        // $permisos = $permisos_model->selectPermisosRol($idrol);
        // $arrResponse = json_decode($permisos['permisos'], true);
    } catch (\Throwable $th) {
        getLoggerSystem()->error(getMensajeError($th));
    }

    /*-------------------------------------------
    [ Retorna respuesta ]*/
    return $arrResponse;
}
