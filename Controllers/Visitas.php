<?php

require 'Libraries/phpqrcode/qrlib.php';

/**
 * Controlador Visitas 
 */
class Visitas extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Visitas.
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
     * Obtiene la lista de Visitas para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getListVisitas($params)
    {

        try {


            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_VISITAS];
            if (!$this->permisosMod['r']) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($params)) {

                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            } else {
                $arrParams = explode(',', $params);
                $fecha_inicial = strClean($arrParams[0]);
                $fecha_inicial .= ' 00:00:00';
                getLoggerSystem($fecha_inicial);
                $fecha_final = strClean($arrParams[1]);
                $fecha_final .= ' 23:59:59';
                getLoggerSystem($fecha_final);
            }


            /*-------------------------------------------
            [ Obtiene el array con la lista de ingresos del periodo ]*/
            $viistas_model = new VisitasModel;
            $viistas_model->setFechaInicio($fecha_inicial);
            $viistas_model->setFechaFin($fecha_final);

            $arrData = $viistas_model->selecVisitasPeriodo($viistas_model);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                // { "data": "No" },
                $arrData[$i]['No'] =  $i + 1;

                // { "data": "residente" },
                $arrData[$i]['residente'] =  $arrData[$i]['calle'] . ' ' .  $arrData[$i]['numero'];

                // { "data": "nombre" },

                // { "data": "telefono" },

                // { "data": "comentarios_adicionales" },

                // { "data": "estatus" }

                // 0 = En Espera 1 = Visita Registrada, 2 = Caducado

                if ($arrData[$i]['estatus'] == 2) {

                    $arrData[$i]['estatus'] = '<span class="text-danger">Caducado.</span>';
                } else if ($arrData[$i]['estatus'] == 1) {

                    $arrData[$i]['estatus'] = '<span class="text-success">Visita Registrada</span>';
                } else if ($arrData[$i]['estatus'] == 0) {

                    $fecha_actual = date("Y-m-d H:i:s");
                    $fecha_actual = strtotime(date("Y-m-d H:i:s"));
                    $fecha_qr = date("Y-m-d H:i:s", strtotime($arrData[$i]['created_at'] . "+ " . VIGENCIA_QR . " minutes"));
                    $fecha_qr = strtotime($fecha_qr);
                    if ($fecha_actual < $fecha_qr) {
                        $arrData[$i]['estatus'] = '<span class="text-warning">En Espera</span>';
                    } else {
                        $arrData[$i]['estatus'] = '<span class="text-danger">Caducado.</span>';
                    }
                }

                // { "data": "fecha_entrada" }
                if ($arrData[$i]['fecha_entrada'] != '') {
                    $arrData[$i]['fecha_entrada'] =  formatDateTime($arrData[$i]['fecha_entrada']);
                }


                // { "data": "created_at" },
                $arrData[$i]['created_at'] =  formatDateTime($arrData[$i]['created_at']);

                // { "data": "options" }
                // $btnView = '';
                // $btnView = ' <a class="btn btn-primary btn-sm rounded-11" data-bs-toggle="tooltip" data-bs-original-title="Ver Detalle"><i class="fa-regular fa-eye"></i></a>';
                // $arrData[$i]['options'] = '<div class="d-flex justify-content-center align-items-center">' . $btnView . '</div>';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Generar codigo QR de visitas
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    private function generarQRRecibo($contenido, $residente_id)
    {
        $dir = 'Assets/files/visitas';
        $file_name = $dir . '/' . $residente_id . '.png';

        $tamanio = 10;
        $level = 'M';
        $frameSize = 4;

        QRcode::png($contenido, $file_name, $level, $tamanio, $frameSize);
    }

    /**
     * Carga la Vista Visitas. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function visitasValida($visita_id): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_VISITAS_QR];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['c']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;

            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_REGISTRO_VISITAS_QR;

            // Se asigna variables recibidas
            $visita_id = intval(strClean($visita_id));

            // Se asigan variables de Sesion.
            $usuario_id_register = $this->session->get('usuario_id');

            //Instanciar Modelo.
            $visitas_model = new VisitasModel;
            $visitas_model->setId($visita_id);
            $arrVisita =  $visitas_model->selectVisitaPrincipal($visita_id);

            if (count($arrVisita) > 0) {

                if ($arrVisita['estatus'] == 0) {
                    //Valida caducidad del QR
                    $fecha_actual = date("Y-m-d H:i:s");
                    $fecha_actual = strtotime(date("Y-m-d H:i:s"));
                    $fecha_qr = date("Y-m-d H:i:s", strtotime($arrVisita['created_at'] . "+ " . VIGENCIA_QR . " minutes"));
                    $fecha_qr = strtotime($fecha_qr);
                    if ($fecha_actual < $fecha_qr) {
                        $visitas_model->setEstatus(1);
                        $result = $visitas_model->updateRegistroVisita($visitas_model, $usuario_id_register);
                        $data['visita'] = $arrVisita;
                        $data['visita']['mensaje'] = 'Acceso autorizado.';
                        $data['visita']['result'] = $result;
                    } else {
                        $visitas_model->setEstatus(2);
                        $result = $visitas_model->updateRegistroVisita($visitas_model, $usuario_id_register);
                        $data['visita']['mensaje'] = 'Codigo Caducado.';
                        $data['visita']['result'] = false;
                    }
                } else if ($arrVisita['estatus'] == 1) {

                    $data['visita']['mensaje'] = 'Codigo ya ha sido utilizado, solo se permite una vez.';
                    $data['visita']['result'] = false;
                } else if ($arrVisita['estatus'] == 2) {

                    $data['visita'] = $arrVisita;
                    $data['visita']['mensaje'] = 'Codigo Caducado';
                    $data['visita']['result'] = false;
                }
            } else {
                $visitas_model->setEstatus(2);
                $result = $visitas_model->updateRegistroVisita($visitas_model, $usuario_id_register);
                $data['visita']['mensaje'] = 'Codigo No Valido';
                $data['visita']['result'] = false;
            }

            //Header
            $data['page_title'] = "Control de Acceso de Visitantes con codigo QR";
            $data['page_description'] = "Registro de Control de Acceso de Visitantes con codigo QR";

            //Form Principal
            $data['page_form_title'] = "Control de Acceso de Visitantes con codigo QR";

            //Breadcrump
            $data['page_breadcrumb'] = "Control de Acceso";

            //Card Principal
            $data['page_card_title'] = "Control de Acceso de Visitantes con codigo QR";
            $data['page_card_description'] = "Módulo de Control de Acceso de Visitantes con codigo QR";

            //JS Principal
            $data['page_functions_js'] = "visitas.js";

            //Call Vista
            $this->views->getView($this, "visitasValida", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Carga la Vista Registro de Visitas. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function registroVisitas(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_VISITAS_QR];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_REGISTRO_VISITAS_QR;

            //Header
            $data['page_title'] = "Registro de Visitas";
            $data['page_description'] = "Registro de Visitas";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-qrcode fa-fw text-secondary text-shadow-info'></i> Registro de Visitas";

            //Breadcrump
            $data['page_breadcrumb'] = "Registro de Visitas";

            //Card Principal
            $data['page_card_title'] = "Registro de Visitas";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Registro de Visitas";

            //JS Principal
            $data['page_functions_js'] = "visitas.js";

            //Call Vista
            $this->views->getView($this, "registroVisitas", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    //*==================================================================
    // [ SeguimientoVisitas ]*/

    /**
     * Carga la Vista del Seguimiento de Visitas de Residentes. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function SeguimientoVisitas(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_VISITAS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_VISITAS;

            //Header
            $data['page_title'] = "Seguimiento de Visitas";
            $data['page_description'] = "Seguimiento de Visitas";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-person-to-door fa-fw text-primary text-shadow-info'></i> Seguimiento de Visitas";

            //Breadcrump
            $data['page_breadcrumb'] = "Visitas";

            //Card Principal
            $data['page_card_title'] = "Consulta de Seguimiento de Visitas";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Administración y Seguimiento de Vistas Registradas";

            //JS Principal
            $data['page_functions_js'] = "visitas.js";

            //Call Vista
            $this->views->getView($this, "seguimiento_visitas", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }
}
