<?php

/**
 * Controlador EstatusResidente 
 */
class EstatusResidente extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador EstatusResidente.
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
     * Carga la Vista EstatusResidente. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function EstatusResidente(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_ESTATUS_RESIDENTE];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_ESTATUS_RESIDENTE;

            //Header
            $data['page_title'] = "Estatus Residente";
            $data['page_description'] = "Estatus Residente";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-print-magnifying-glass fa-fw text-primary text-shadow-info'></i> Estatus Residente";

            //Breadcrump
            $data['page_breadcrumb'] = "Estatus Residente";

            //Card Principal
            $data['page_card_title'] = "Estatus Residente";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo para consulta de Estatus de Residente.";

            //JS Principal
            $data['page_functions_js'] = "functions_estatus_residente.js";

            //Call Vista
            $this->views->getView($this, "estatus_residente", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }


    /**
     * Obtiene los datos del Estatus de Cuenta de un residente seleccionado.
     * 
     * @param int $residente_id
     * Identificador de residente
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * * data (array). En caso de ser exitoso, el elemento data contiene la información solicitada.
     * * dataEspecialidad (array). En caso de ser exitoso, el elemento dataEspecialidad contiene la información solicitada.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getEstatusResidente(int $residente_id)
    {

        try {


            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_ESTATUS_RESIDENTE];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Actualizar Estatus de Tag de Residente ]*/
            $cuenta_model = new CuentasModel;
            $estatus_cuenta_mes_corriente = $cuenta_model->getEstatusCuentaMesCorriente($residente_id);

            if ($estatus_cuenta_mes_corriente == 1) {

                $arrData['estatus'] = $estatus_cuenta_mes_corriente;
            } else {

                $fecha_actual = strtotime(date("Y-m-d"));

                $date_y = date("Y");
                $date_m = date("m");
                $date_str = $date_y . "-" . $date_m . "-05";
                $fecha_entrada = strtotime($date_str);

                if ($fecha_actual > $fecha_entrada) {
                    $arrData['estatus'] = 0;
                } else {
                    $estatus_cuenta_mes_corriente = $cuenta_model->getEstatusCuentaMesAnterior($residente_id);
                    if ($estatus_cuenta_mes_corriente == 1) {
                        $arrData['estatus'] = $estatus_cuenta_mes_corriente;
                    }
                }
            }

            $arrRespuesta = array(
                'respuesta' => 'ok', 'mostrar_mensaje' => false, 'tiempo' => 6000,
                'mensaje' => 'Datos encontrados',
                'data' => $arrData
            );
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrRespuesta = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 6000,
                'mensaje' => 'Datos no encontrados'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }
}
