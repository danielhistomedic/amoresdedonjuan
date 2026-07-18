<?php

// use Stripe\Terminal\Location;

/**
 * Controlador Login 
 */
class Login extends Controllers
{

    private $session;

    /**
     * Método Constructor de Controlador Roles.
     * Inicializa Controllers::__construct
     * Inicializa y valida variables de sesión.
     */
    public function __construct()
    {

        parent::__construct();

        /*-------------------------------------------
        // [ Validación de Sesion ]*/
        $this->session = new Session;
        if (isset($_SESSION[PREFIJO_SESSION  . 'email'])) {
            header('Location: ' . base_url() . '/inicio');
        }
    }

    /**
     * Carga la Vista Login. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Login()
    {

        try {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION[PREFIJO_SESSION . 'userId'])) {
                header('location: inicio');
            }
            /*-------------------------------------------
            [ Crea el array $data para enviar a la vista ]*/
            $data['id'] = 4;
            $data['sesion'] = $_SESSION;
            $data['page_tag'] = "Login Fracc. Amores de Don Juan";
            $data['page_breadcrumb'] = "Login";
            $data['page_name'] = "login";
            $data['page_title'] = "Login Fracc. Amores de Don Juan";
            $data['page_title_form'] = "Inicio de Sesión";
            $data['page_content'] = "Sistema de Gestión Fracc. Amores de Don Juan";
            $data['page_functions_js'] = "functions_login.js";

            /*-------------------------------------------
            [ Ejecuta el método para generar la vista en el navegador ]*/
            $this->views->getView($this, "login", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Carga la Vista de Auto Registro de Nuevo Usuario. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Registro()
    {

        try {

            /*-------------------------------------------
            [ Crea el array $data para enviar a la vista ]*/
            $data['id'] = 104;
            $data['page_tag'] = "Registro de Usuario";
            $data['page_breadcrumb'] = "Registro";
            $data['page_name'] = "registro";
            $data['page_title'] = "Registro de Usuario";
            $data['page_title_form'] = "Registro de Usuario";
            $data['page_content'] = "Autoregistro de Nuevos Usuarios";
            $data['page_functions_js'] = "functions_login.js";

            /*-------------------------------------------
            [ Ejecuta el método para generar la vista en el navegador ]*/
            $this->views->getView($this, "registro", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Carga la Vista de Recuepración de Contraseña de Usuario. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function recuperarContrasena()
    {

        try {

            /*-------------------------------------------
            [ Crea el array $data para enviar a la vista ]*/
            $data['id'] = 104;
            $data['page_tag'] = "Recuperar Contraseña";
            $data['page_breadcrumb'] = "Recuperar Contraseña";
            $data['page_name'] = "recuperar_password";
            $data['page_title'] = "Recuperar Contraseña";
            $data['page_title_form'] = "Recuperar Contraseña";
            $data['page_content'] = "Recueprar password en caso de olvidarlo";
            $data['page_functions_js'] = "functions_login.js";

            /*-------------------------------------------
            [ Ejecuta el método para generar la vista en el navegador ]*/
            $this->views->getView($this, "recuperar_password", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Carga la Vista Reset Password. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function confirmUser(string $params)
    {

        try {

            /*-------------------------------------------
            [ Validar Parametros ]*/

            if (empty($params)) {
                header('Location: ' . base_url());
                die();
            } else {
                $arrParams = explode(',', $params);
                $strEmail = strClean($arrParams[0]);
                $strToken = strClean($arrParams[1]);
            }

            /*-------------------------------------------
            [ Consulta DB ]*/
            $login_model = new LoginModel;
            $arrResponse = $login_model->validUserToken($strEmail, $strToken);


            /*-------------------------------------------
            [ Valida se existe token registrado ]*/
            if (empty($arrResponse)) {
                header('Location: ' . base_url());
                die();
            } else {

                /*-------------------------------------------
                [ Obtiene las horas transcurridas ]*/
                $date = date("Y-m-d H:i:s");
                $fecha_actual = date_create($date);
                $fecha_token = date_create($arrResponse['token_created']);
                $diff = date_diff($fecha_actual, $fecha_token);
                $total_min = ($diff->h * 60) + $diff->i;
                if ($total_min > 5) {
                    header('Location: ' . base_url());
                    die();
                }

                /*-------------------------------------------
                [ Crea el array $data para enviar a la vista ]*/
                $data['id'] = 10;
                $data['page_tag'] = "Cambiar Contraseña";
                $data['page_breadcrumb'] = "Cambiar Contraseña";
                $data['page_name'] = "cambiar_contrasenia";
                $data['page_title'] = "Cambiar Contraseña";
                $data['page_title_form'] = "Cambiar Contraseña";
                $data['page_content'] = "";
                $data['page_functions_js'] = "functions_login.js";
                $data['userId'] = $arrResponse['id'];

                /*-------------------------------------------
                [ Ejecuta el método para generar la vista en el navegador ]*/
                $this->views->getView($this, "reset", $data);
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die();
    }

    /**
     * Validar acceso al sistema
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function loginUser()
    {

        try {


            if (empty($_POST)) {
                echo "Error: Acceso restringido";
                die();
            }


            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $email_post = strtolower(strClean($_POST['inputEmail']));
            $password_sys_post = strclean($_POST['inputPassword']);
            $recordarme_post = 0;
            if (isset($_POST['inputRecordarme'])) {
                $recordarme_post = 1;
            }


            /*-------------------------------------------
            [ Validar Datos ]*/
            if (trim($email_post) == '') {
                die(json_encode(getResponse('Debe indicar Email.'), JSON_UNESCAPED_UNICODE));
            }
            if (trim($password_sys_post) == '') {
                die(json_encode(getResponse('Debe indicar Contraseña.'), JSON_UNESCAPED_UNICODE));
            }


            /*-------------------------------------------
            [ Hash Password ]*/
            $password_sys_post = hash('SHA256', $password_sys_post);


            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            $user_model = new UsuariosModel;
            $user_model->setUsuario($email_post);
            $user_model->setPass($password_sys_post);

            /*-------------------------------------------
            [ Se ejecuta el Método loginUser del modelo para validar al usuario ]*/
            $login_model = new LoginModel;
            $arrResponse = $login_model->loginUser($user_model);

            if (count($arrResponse) == 0) {
                $arrResponse = array(
                    'respuesta' => 'error',
                    'mostrar_mensaje' => true,
                    'tiempo' => 4000,
                    'mensaje' => 'Usuario o Password incorrecto'
                );
                die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
            } else {

                /*-------------------------------------------
                [ Asigan valores de usuario ] */
                $usuario_id = intval($arrResponse['id']);
                $theme = intval($arrResponse['theme']);


                /*-------------------------------------------
                [ Valida si solo pertecece a una Unidad Médica ] */
                $unidad_medica_model = new UnidadMedicaUsuariosModel;
                $arrUnidadesMedicas = $unidad_medica_model->getUnidadesMedicasUsuarioLogin($usuario_id);
                $total_unidades_medicas = count($arrUnidadesMedicas);

                if ($total_unidades_medicas > 1) {
                    // Se debe validar y
                    // buscar un modo de enviar un modal para que seleccione la unidad médica donde va a laborar
                } else {
                    $unidad_medica_id = intval($arrUnidadesMedicas[0]['unidad_medica_id']);
                }

                //Valida si el usuario está activo
                if ($arrUnidadesMedicas[0]['activo'] == 0) {
                    die(json_encode(getResponse('USUARIO INACTIVO. <br> Consulte a la Administración del Fraccionamiento.'), JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Obtener Datos General de Perfil para session ] */
                $usuarios_model = new UsuariosModel;
                $arrUsuario = $usuarios_model->selectUsuario($usuario_id, $unidad_medica_id);
                if (count($arrUsuario) == 0) {
                    $arrResponse = getResponse("Err Code 1003. Usuario o contraseña invalido, intente nuevamente");
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                };

                //Inicializa las variables de sesión
                $this->session->add('email', $email_post);
                $this->session->add('theme', $theme);
                $this->session->add('userId', $usuario_id);
                $this->session->add('usuario_id', $usuario_id);
                $this->session->add('unidad_medica_id', $unidad_medica_id);
                $this->session->add('nombre', $arrUsuario['nombre'] . ' ' . $arrUsuario['paterno'] . ' ' . $arrUsuario['materno']);
                $this->session->add('rol', $arrUsuario['rol']);

                $arrResponse =  getResponse('Acceso Autorizado', 'ok', false);
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            getResponse('Code Error log_1001, Error desconocido');
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Validar email para reset password
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function resetPassword()
    {

        try {

            if (empty($_POST)) {
                echo "Error: Acceso restringido";
                die();
            }

            error_reporting(0);

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $email_reset_post = strtolower(strClean($_POST['inputEmailReset']));


            /*-------------------------------------------
            [ Se instancian los Modelos ]*/
            $login_model = new LoginModel;


            /*-------------------------------------------
            [ Se ejecuta el metodo del modelo LoginModel para validar si existe o no el email ]*/
            $result = $login_model->validateResetPasswordUser($email_reset_post);

            if ($result == false) {

                $arrResponse = array(
                    'respuesta' => 'error',
                    'mostrar_mensaje' => true,
                    'tiempo' => 6000,
                    'mensaje' => 'Usuario inactivo o no existe, intente nuevamente'
                );
            } else {

                // $token = token();
                // $email_reset = $email_reset_post;
                // $usuario_id = $arrUsuario['id'];
                // $user_nombre = $arrUsuario['nombre'] . ' ' . $arrUsuario['paterno'] . ' ' . $arrUsuario['materno'];
                // $url_recovery = base_url()  . '/login/confirmUser/' . $email_reset . '/' . $token;

                // $responseToken = $login_model->setTokenUser($usuario_id, $token);

                // if ($responseToken  == true) {

                //     /*-------------------------------------------
                //     [ Enviar email ]*/
                //     $dataUsuario = array(
                //         'nombre_usuario' => $user_nombre,
                //         'email' => $email_reset,
                //         'asunto' => 'Recuperar cuenta - ' . NOMBRE_REMITENTE,
                //         'url_recovery' => $url_recovery
                //     );
                //     $sendEmail = sendEmailPHPMailer($dataUsuario, 'email_reset');


                //     if ($sendEmail) {
                //         $arrResponse = array(
                //             'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 6000,
                //             'mensaje' => 'Se ha enviado un email a su cuenta de correo para cambiar la contraseña.'
                //         );
                //     } else {
                //         $arrResponse = array(
                //             'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 6000,
                //             'mensaje' => 'No es posible realizar este proceso, intente más tarde.'
                //         );
                //     }
                // } else {

                //     $arrResponse = array(
                //         'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 6000,
                //         'mensaje' => 'No es posible realizar este proceso, intente más tarde.'
                //     );
                // }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 6000,
                'mensaje' => 'No es posible realizar este proceso, intente más tarde.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Actualiza el Password de un usuario determinado
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setPassword()
    {

        try {

            if (empty($_POST)) {
                echo "Error: Acceso restringido";
                die();
            }

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $password_sys_post = strClean($_POST['inputResetPassword']);
            $usuario_id_post = intval($_POST['inputUserId']);

            /*-------------------------------------------
            [ Se ejecuta el hash del password ]*/
            $password_sys_post = hash('SHA256', $password_sys_post);


            /*-------------------------------------------
            [ Se ejecuta el metodo del modelo LoginModel para actualizar el password ]*/
            $response = $this->model->updatePassword($usuario_id_post, $password_sys_post);

            if ($response == false) {

                $arrResponse = array(
                    'respuesta' => 'error',
                    'mostrar_mensaje' => true,
                    'tiempo' => 6000,
                    'mensaje' => 'Error al cambiar la contraseña, intente nuevamente'
                );
            } else {

                $arrResponse = array(
                    'respuesta' => 'ok',
                    'mostrar_mensaje' => true,
                    'tiempo' => 6000,
                    'mensaje' => 'Contraseña cambiada exitosamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 6000,
                'mensaje' => 'Error al cambiar la contraseña, intente más tarde.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }


    /**
     * Obtiene la lista de catálogo de Paises para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectPaises(): string
    {

        try {
            $htmlOptions = '';
            $paises_model = new PaisesModel;

            $arrData = $paises_model->selectPaises();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . ' [' . $arrData[$i]['codigo'] . '] ' . $arrData[$i]['pais'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de Tipos de Licencia para llenar un Select
     * @return string $htmlOptions
     */
    public function getTipoLicencia(): string
    {

        try {
            $htmlOptions = '';
            $tipo_licencia_model = new TipoLicenciaModel;

            $arrData = $tipo_licencia_model->selectTipoLicencia();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['tipo'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de especialidades para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectEspecialidades(): string
    {

        try {
            $htmlOptions = '';
            $especialidades_model = new EspecialidadesModel;
            $arrData = $especialidades_model->selectEspecialidades();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['especialidad'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de Orgien de como se enteran los clientes para llenar un Select
     * @return string $htmlOptions
     */
    public function getOrigenEntera(): string
    {

        try {
            $htmlOptions = '';
            $tipo_licencia_model = new OrigenEnteraModel;

            $arrData = $tipo_licencia_model->selectOrigenEntera();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['origen'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de escuelas para llenar el autocomplete.
     * 
     * @param string $filtro
     * Texto recibido para filtrar la información en el query
     * 
     * @response $arrResponse, donde:
     * Array con la lista requerida para llenar el autocomplete.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getEscuelasAutocomplete(string $filtro)
    {

        try {

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $filtro = strClean($filtro);

            /*-------------------------------------------
            [ Obtiene array con los datos de Escuelas ]*/
            $codigos_postales_model = new EscuelasModel;
            $arrData = $codigos_postales_model->getAutocompleteEscuelas($filtro);
            $arrResponse = array();

            for ($i = 0; $i < count($arrData); $i++) {
                $arrTemp = array();
                $arrTemp['value'] =  $arrData[$i]['escuela'];

                $arrTemp['label'] = '<div class="w-100 p-2">
                                        <i class="fa-solid fa-angles-right me-1"></i>' . $arrData[$i]['escuela'] . '
                                    </div>';
                $arrTemp['id'] =  $arrData[$i]['id'];
                $arrResponse[] = $arrTemp;
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }
}
