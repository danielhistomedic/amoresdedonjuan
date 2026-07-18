<?php

/**
 * Controlador Configuracion 
 */
class Configuracion extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Configuracion.
     * Inicializa Controllers::__construct.
     * Inicializa y valida datos de session.
     */
    public function __construct()
    {
        parent::__construct();

        /*-------------------------------------------
        [ Validación de Sesion ]*/
        $this->session = new Session();
        if ($this->session->getStatus() === false || empty($this->session->get('email'))) {
            $this->session->redirect('inicio');
        }
    }

    /**
     * Carga la Vista Configuracion. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Configuracion()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CONFIG];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;

            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_CONFIG;

            //Header
            $data['page_title'] = "Configuracion de Parámetros Generales de Sistema";
            $data['page_description'] = "Configuracion de Parámetros Generales de Sistema";

            //Form Principal <i class="fa-regular fa-gear"></i> text-gray-dark
            $data['page_form_title'] = "<i class='fa-regular fa-gear fa-fw text-gray text-shadow-info'></i> Configuracion";

            //Breadcrump
            $data['page_breadcrumb'] = "Configuracion";

            //Card Principal
            $data['page_card_title'] = "Registro de Parámetros Generales de Sistema";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Configuracion, para registrar Parámetros Generales de Sistema.";

            //JS Principal
            $data['page_functions_js'] = "configuracion.js";

            //Call Vista
            $this->views->getView($this, "configuracion", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene los datos de Configuración
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * * data (array). En caso de ser exitoso, el elemento data contiene la información solicitada.
     * 
     * @return string 
     * json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     * 
     * 
     */
    public function getConfiguracion()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CONFIG];
            if (!$this->permisosMod['r']) {
                $this->session->redirect('inicio');
                die();
            }

            /*-------------------------------------------
            [ Obtiene array con los datos ]*/
            $config_model = new ConfiguracionModel;
            $arrData = $config_model->selectConfiguracion();
            if (empty($arrData)) {
                die(json_encode(getResponse('Lo sentimos, Datos no encontrados'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Genarar array de respuesta positiva ]*/
            $arrResponse = getResponse('Datos encontrados', 'ok', false);
            $arrResponse['data'] = $arrData;
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('Code config_1001. Error Desconocido'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Guardar/Actualizar datos de Rol
     * 
     * @return string 
     * json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     * $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     */
    public function setConfiguracion()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CONFIG];
            if (!$this->permisosMod['u']) {
                die(json_encode(getResponse("No cuenta con privilegios para realizar esta acción."), JSON_UNESCAPED_UNICODE));
            }

            $arrResponse = array();

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $sucursal_id = $this->session->get('sucursal_id');
            $empresa_id = $this->session->get('empresa_id');
            $usuario_id_register = $this->session->get('usuario_id');


            /*-------------------------------------------
            [ Se aignan las variables del POST ]*/
            $inputEmailRemitente = strclean($_POST['inputEmailRemitente']);
            $inputEmailContabilidad = strclean($_POST['inputEmailContabilidad']);
            $inputSMTPHost = strclean($_POST['inputSMTPHost']);
            $inputSMTPUsuario = strclean($_POST['inputSMTPUsuario']);
            $inputSMTPPassword = strclean($_POST['inputSMTPPassword']);
            $inputSMTPPuerto = strclean($_POST['inputSMTPPuerto']);

            /*-------------------------------------------
            [ Valida formulario ]*/
            if ($inputEmailRemitente == '') {
                die(json_encode(getResponse("Debe indicar email del remitente"), JSON_UNESCAPED_UNICODE));
            }
            if (trim($inputSMTPHost) == '' || trim($inputSMTPUsuario) == '' || trim($inputSMTPPassword) == '' || trim($inputSMTPPuerto) == '') {
                die(json_encode(getResponse("Debe indicar parametros del Host"), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            $config_model = new ConfiguracionModel;
            $config_model->setEmail_remitente($inputEmailRemitente);
            $config_model->setEmail_destino_contabilidad($inputEmailContabilidad);
            $config_model->setSmtp_host($inputSMTPHost);
            $config_model->setSmtp_usuario($inputSMTPUsuario);
            $config_model->setSmtp_password($inputSMTPPassword);
            $config_model->setSmtp_puerto($inputSMTPPuerto);


            /*==========================================
            [ Actualizar Registro ]*/
            $response = $config_model->updateConfiguracion($config_model, $usuario_id_register);
            if ($response == false) {
                die(json_encode(getResponse("Code: conf_1003, Error al actualizar el registro, intente nuevamente"), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Crear array de respuesta Exitosa ]*/
            $arrResponse = getResponse("Registro actualizado exitosamente", "ok", true);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse("Code: conf_1004, Error desconocido, intente nuevamente"), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }
}
