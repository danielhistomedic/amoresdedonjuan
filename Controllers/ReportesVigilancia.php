<?php

/**
 * Controlador ReportesVigilancia 
 */
class ReportesVigilancia extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador ReportesVigilancia.
     * Inicializa Controllers::__construct
     * Inicializa y valida datos de session.
     * 
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
     * Carga la Vista de Reportes de Vigilancia. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function ReportesVigilancia(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_VIGILANCIA];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;

            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_REPORTES_VIGILANCIA;

            //Header
            $data['page_title'] = "Registro de Reportes de Vigilancia";
            $data['page_description'] = "Registro de Reportes de Vigilancia";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-camera-cctv fa-fw text-secondary text-shadow-info'></i> Reportes de Vigilancia";

            //Breadcrump
            $data['page_breadcrumb'] = "Reportes de Vigilancia";

            //Card Principal
            $data['page_card_title'] = "Registro de Reportes de Vigilancia";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo para registro de Reportes de Vigilancia";

            //JS Principal
            $data['page_functions_js'] = "reportes_vigilancia.js";

            //Call Vista
            $this->views->getView($this, "reportes_vigilancia", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }


    /**
     * Obtiene la lista de catálogo de ReportesVigilancia para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getReportes()
    {

        try {


            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_VIGILANCIA];
            if ($this->permisosMod['r'] == 0) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeIn";

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $reportes_model = new ReportesVigilanciaModel;
            $arrData = $reportes_model->selectReportesVigilancia();

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {
                // { "data": "asunto" },
                // { "data": "reporte" },
                // { "data": "estatus" },
                // { "data": "archivo" },
                // { "data": "options" }
                $arrData[$i]['options'] = '';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }


    /**
     * Obtiene los datos del Reporte.
     * 
     * @param int $reporte_id
     * Identificador de Reporte seleccionado
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
    public function getRol(int $reporte_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_VIGILANCIA];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $reporte_id = intval(strClean($reporte_id));

            if ($reporte_id > 0) {

                /*-------------------------------------------
                [ Obtiene array con los datos del rol ]*/
                $rol_model = new RolesModel;
                $arrData = $rol_model->selectRol($reporte_id);
                if (empty($arrData)) {
                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                        'mensaje' => 'Lo sentimos, Datos no encontrados'
                    );
                } else {
                    $arrResponse = array(
                        'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                        'mensaje' => 'Lo sentimos, Datos no encontrados',
                        'data' => $arrData
                    );
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 6000,
                'mensaje' => 'Datos no encontrados'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }


    /**
     * Guardar/Actualizar datos de Reporte de Vigilancia
     *
     * @return string
     * json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     * $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa, caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     *
     */
    public function setReporteVigilancia()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_VIGILANCIA];

            /*-------------------------------------------
            [ Se asignan las variables de Sesión ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $id_post = intval($_POST['reportes_vigilancia_id']);

            if ($id_post == 0) {

                /*-------------------------------------------
                [ Valida Permisos de Creación. ]*/
                if (!$this->permisosMod['c']) {
                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                        'mensaje' => 'No cuenta con privilegios suficientes para realizar esta acción'
                    );
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }
            } else {

                /*-------------------------------------------
                [ Valida Permisos de Actualización. ]*/
                if (!$this->permisosMod['u']) {
                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                        'mensaje' => 'No cuenta con privilegios suficientes para realizar esta acción'
                    );
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }
            }

            $asunto_post  = strClean($_POST['asunto']);
            $reporte_post = strClean($_POST['reporte']);

            /*-------------------------------------------
            [ Se asignan las variables al Modelo ]*/
            $reporte_model = new ReportesVigilanciaModel;
            $reporte_model->setId($id_post);
            $reporte_model->setAsunto($asunto_post);
            $reporte_model->setReporte($reporte_post);
            $reporte_model->setEstatus(1);
            $reporte_model->setArchivo('');
            $reporte_model->setUsuarioIdUpdated($usuario_id_register);
            $reporte_model->setUsuarioIdCreated($usuario_id_register);

            if ($id_post == 0) {

                /*==========================================
                [ Crear Nuevo Registro ]*/
                $response = $reporte_model->insertReporte($reporte_model);
                if ($response == true) {
                    $mensaje = 'Registro creado exitosamente';
                } else {
                    $mensaje = 'Error al crear el registro, intente nuevamente';
                }
            } else {

                /*==========================================
                [ Actualizar Registro ]*/
                $response = $reporte_model->updateReporte($reporte_model);
                if ($response == true) {
                    $mensaje = 'Registro actualizado exitosamente';
                } else {
                    $mensaje = 'Error al actualizar el registro, intente nuevamente';
                }
            }

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => $mensaje
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => $mensaje
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 6000,
                'mensaje' => 'Ocurrió un error al procesar la solicitud'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }


    /**
     * Actualizar Estatus de Rol
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setEstatusRol()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_ROLES];
            if (!$this->permisosMod['u']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register =  $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $id_post = intval($_POST['id']);
            $estatus = intval($_POST['estatus']);


            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            $rol_model = new RolesModel;
            $rol_model->setId($id_post);
            $rol_model->setActivo($estatus);

            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $response = $rol_model->updateEstatusRol($rol_model, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Registro Eliminado exitosamente.'
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }


    /**
     * Obtiene la lista de catalogo de roles, para crear el html con los options que llenaran el Select correspondiente.
     * 
     * @return string $htmlOptions
     * 
     */
    public function getSelectRoles()
    {

        try {

            $htmlOptions = '';

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_ROLES];
            if (!$this->permisosMod['r']) {
                die($htmlOptions);
            }

            $htmlOptions .= '<option value="" selected="selected" disabled>Seleccione una opcion</option>';
            $rol_model = new RolesModel;
            $arrData = $rol_model->selectRoles();
            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['name'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta string html ]*/
        die($htmlOptions);
    }
}
