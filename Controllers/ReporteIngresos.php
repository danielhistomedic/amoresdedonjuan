<?php

require 'Libraries/html2pdf/vendor/autoload.php';
require 'Libraries/numlet/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Luecano\NumeroALetras\NumeroALetras;

/**
 * Controlador ReporteIngresos 
 */
class ReporteIngresos extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador ReporteIngresos.
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
     * Carga la Vista ReporteIngresos. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function ReporteIngresos()
    {

        try {


            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_INGRESOS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;

            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_REPORTES_INGRESOS;

            //Header
            $data['page_title'] = "Reporte de Ingresos";
            $data['page_description'] = "Reporte de Ingresos";

            //Form Principal 
            $data['page_form_title'] = "<i class='fa-regular fa-file-invoice-dollar fa-fw text-secondary text-shadow-info'></i> Reporte de Ingresos";

            //Breadcrump
            $data['page_breadcrumb'] = "Ingresos";

            //Card Principal
            $data['page_card_title'] = "Reporte de Ingresos";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo para Obtener Reporte de Ingresos al Fraccionamiento.";

            //JS Principal
            $data['page_functions_js'] = "functions_reporte_ingresos.js";

            //Call Vista
            $this->views->getView($this, "reporte_ingresos", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene la lista de Ingresos en un Periodo Determinado para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getIngresos($params)
    {

        try {

            $arrData = array();


            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_INGRESOS];
            if ($this->permisosMod['r'] == 0) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }


            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($params)) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            } else {
                $arrParams = explode(',', $params);
                $fecha_inicial = strClean($arrParams[0]);
                $fecha_final = strClean($arrParams[1]);
            }

            /*-------------------------------------------
            [ Obtiene el array con la lista de ingresos del periodo ]*/
            $recibos_model = new RecibosModel;
            $recibos_model->setFecha_inicio($fecha_inicial);
            $recibos_model->setFecha_fin($fecha_final);

            $arrData = $recibos_model->selectRecibosPeriodo($recibos_model);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                //Formato Estandar de datos
                $arrData[$i]['domicilio'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['domicilio'] .
                    '</div>';

                $arrData[$i]['clasificacion_ingreso'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['clasificacion_ingreso'] .
                    '</div>';

                $arrData[$i]['concepto'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['concepto'] .
                    '</div>';

                $arrData[$i]['importe'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . formatMoney($arrData[$i]['importe']) .
                    '</div>';

                $arrData[$i]['folio'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['folio'] .
                    '</div>';

                $arrData[$i]['created_at'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . formatDateTime($arrData[$i]['created_at']) .
                    '</div>';

                $arrData[$i]['fecha_cancela'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . formatDateTime($arrData[$i]['fecha_cancela']) .
                    '</div>';

                $arrData[$i]['motivo_cancela'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['motivo_cancela'] .
                    '</div>';

                $arrData[$i]['usuario_cancela'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['usuario_cancela'] .
                    '</div>';

                //Formato Opciones
                $btnReimprimir = '';

                if ($this->permisosMod['r']) {

                    if ($arrData[$i]['estatus'] == 0) {
                        $btnReimprimir = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center" onclick="fntReimprimirRecibo(this)" data-id="' . $arrData[$i]['id'] . '" title= "Reimprimir Recibo">
                                         <i class="fa-regular fa-file-pdf fs-14"></i>
                                 </button>';
                    }
                }


                //Formato Estatus de Registro
                if ($arrData[$i]['estatus'] == 0) {
                    $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                             <span class="badge badge-success">Vigente</span>
                                           </div>';
                } else {
                    $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                             <span class="badge badge-danger">Cancelado</span>
                                           </div>';
                }

                //Formato Options y Registros reservados
                $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex justify-content-center align-items-center">' . ' ' . $btnReimprimir  . ' </div>';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene Importe Total de Ingresos de la Cuenta en un Periodo Seleccionado.
     * 
     * @param string $fecha
     * Fecha en formato Y-m-d
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores:
     * * total_ingresos;
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getResumenReporteIngresos(string $params)
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_INGRESOS];
            if ($this->permisosMod['r'] == 0) {
                $arrData = array(
                    'importe_total_ingresos' => 0,
                    'cantidad_recibos_expedidos' => 0,
                    'saldo_utilizado' => 0,
                    'importe_dejado_a_cuenta' => 0,
                    'importe_total_ingresos_desglose' => 0
                );
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($params)) {
                $arrData = array(
                    'importe_total_ingresos' => 0,
                    'cantidad_recibos_expedidos' => 0,
                    'saldo_utilizado' => 0,
                    'importe_dejado_a_cuenta' => 0,
                    'importe_total_ingresos_desglose' => 0
                );
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            } else {
                $arrParams = explode(',', $params);
                $fecha_inicial = strClean($arrParams[0]);
                $fecha_final = strClean($arrParams[1]);
            }

            /*-------------------------------------------
            [ Instanciar Meodelo ]*/
            $estado_resultados_model = new EstadoResultadosModel;
            $estado_resultados_model->setFiltro_fecha_fin($fecha_final);
            $estado_resultados_model->setFiltro_fecha_inicio($fecha_inicial);

            $recibos_model = new RecibosModel;
            $recibos_model->setFecha_fin($fecha_final);
            $recibos_model->setFecha_inicio($fecha_inicial);


            /*-------------------------------------------
            [ Obtiene importe total de ingresos del periodo ]*/
            $importe_total_ingresos = $estado_resultados_model->selectImporteIngresosPeriodo($estado_resultados_model);


            /*-------------------------------------------
            [ Cantidad de Recibos Expedidos del Periodo ]*/
            $cantidad_recibos_expedidos = $recibos_model->selectTotalRecibosExpedidosPeriodo($recibos_model);


            /*-------------------------------------------
            [ Obtiene importe del saldo a cuenta utilizado en un periodo determinado ]*/
            $saldo_utilizado = $recibos_model->selectTotalSaldoUtilizadoPeriodo($recibos_model);

            /*-------------------------------------------
            [ Obtiene importe del saldo a cuenta utilizado en un periodo determinado ]*/
            $importe_dejado_a_cuenta = $recibos_model->selectTotalDejadoACuentaPeriodo($recibos_model);


            /*-------------------------------------------
            [ Obtiene arreglo de desglose de ingresos en un periodo determinado ]*/
            $importe_total_ingresos_desglose = $recibos_model->selectIngresosDesglose($recibos_model);


            $arrData = array(
                'importe_total_ingresos' => $importe_total_ingresos,
                'cantidad_recibos_expedidos' => $cantidad_recibos_expedidos,
                'saldo_utilizado' => $saldo_utilizado,
                'importe_dejado_a_cuenta' => $importe_dejado_a_cuenta,
                'importe_total_ingresos_desglose' => $importe_total_ingresos_desglose
            );
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrData = array(
                'importe_total_ingresos' => 0,
                'cantidad_recibos_expedidos' => 0,
                'saldo_utilizado' => 0,
                'importe_dejado_a_cuenta' => 0,
                'importe_total_ingresos_desglose' => 0
            );
        }


        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Genera Reporte de Ingreoss en formato pdf.
     * 
     * @param string $params
     * Parmateros con los valores a filtrar
     * 
     */
    public function generarPDFReporteIngresos(string $params)
    {

        /*-------------------------------------------
        [ Validación de Permisos ]*/
        $arrPermisos = getPermisosGlobal();
        $this->permisosMod = $arrPermisos[MOD_REPORTES_INGRESOS];
        if (!$this->permisosMod['p_excel']) {
            echo "<h4>Lo sentimos, Acceso restringido</h4>";
            die();
        }

        /*-------------------------------------------
        [ Asignar y Limpiar parametros recibidos ]*/
        if (empty($params)) {
            echo "<h4>Lo sentimos, Acceso restringido</h4>";
            die();
        } else {
            $arrParams = explode(',', $params);
            $fecha_inicial = strClean($arrParams[0]);
            $fecha_final = strClean($arrParams[1]);
        }

        /*-------------------------------------------
            [ Instanciar Meodelo ]*/
        $estado_resultados_model = new EstadoResultadosModel;
        $estado_resultados_model->setFiltro_fecha_fin($fecha_final);
        $estado_resultados_model->setFiltro_fecha_inicio($fecha_inicial);

        $recibos_model = new RecibosModel;
        $recibos_model->setFecha_fin($fecha_final);
        $recibos_model->setFecha_inicio($fecha_inicial);


        /*-------------------------------------------
            [ Obtiene importe total de ingresos del periodo ]*/
        $importe_total_ingresos = $estado_resultados_model->selectImporteIngresosPeriodo($estado_resultados_model);


        /*-------------------------------------------
            [ Cantidad de Recibos Expedidos del Periodo ]*/
        $cantidad_recibos_expedidos = $recibos_model->selectTotalRecibosExpedidosPeriodo($recibos_model);


        /*-------------------------------------------
            [ Obtiene importe del saldo a cuenta utilizado en un periodo determinado ]*/
        $saldo_utilizado = $recibos_model->selectTotalSaldoUtilizadoPeriodo($recibos_model);

        /*-------------------------------------------
            [ Obtiene importe del saldo a cuenta utilizado en un periodo determinado ]*/
        $importe_dejado_a_cuenta = $recibos_model->selectTotalDejadoACuentaPeriodo($recibos_model);


        /*-------------------------------------------
            [ Obtiene arreglo de desglose de ingresos en un periodo determinado ]*/
        $importe_total_ingresos_desglose = $recibos_model->selectIngresosDesglose($recibos_model);

        $arrData = array(
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
            'importe_total_ingresos' => $importe_total_ingresos,
            'cantidad_recibos_expedidos' => $cantidad_recibos_expedidos,
            'saldo_utilizado' => $saldo_utilizado,
            'importe_dejado_a_cuenta' => $importe_dejado_a_cuenta,
            'importe_total_ingresos_desglose' => $importe_total_ingresos_desglose
        );

        /*-------------------------------------------
        [ Obtiene lista de ingresos del periodo ]*/

        $arrIngresos = $recibos_model->selectRecibosPeriodo($recibos_model);
        $arrData['list_ingresos'] = $arrIngresos;

        $arrRecibosAnteriores = $recibos_model->selectRecibosAnterioresPeriodo($recibos_model);
        $arrData['list_recibos_anteriores'] = $arrRecibosAnteriores;

        /*-------------------------------------------
        [ html2pdf ]*/

        $importe_total_ingresos_letra = 0;
        if ($importe_total_ingresos > 0) {
            $importe_total_ingresos_letra = $importe_total_ingresos;
        }
        $formatter = new NumeroALetras();
        $formatter->conector = 'PESOS';
        $numero_letra = $formatter->toInvoice($importe_total_ingresos_letra, 2, '');
        $arrData['importe_total_ingresos_letra'] = $numero_letra;

        $html = getFile("Template/Pdf/reporteIngresos",  $arrData);
        // $html = getFile("Template/Modals/pdfBlanco",  $arrData);

        // (izquierda, superior, derecha, inferior)
        $margen_recibo = array(10, 10, 10, 10);

        $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8',  $margen_recibo);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($html, true, false, true, false, '');
        $html2pdf->output('ReporteIngresos.pdf');
    }

    /**
     * Obtiene la lista de Recibos Anteriores para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getRecibosAnteriores($params)
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_INGRESOS];
            if ($this->permisosMod['r'] == 0) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($params)) {

                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            } else {
                $arrParams = explode(',', $params);
                $fecha_inicial = strClean($arrParams[0]);
                $fecha_final = strClean($arrParams[1]);
            }

            /*-------------------------------------------
            [ Obtiene el array con la lista de ingresos del periodo ]*/
            $recibos_model = new RecibosModel;
            $recibos_model->setFecha_inicio($fecha_inicial);
            $recibos_model->setFecha_fin($fecha_final);

            $arrData = $recibos_model->selectRecibosAnterioresPeriodo($recibos_model);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {


                //Formato Estandar de datos
                $arrData[$i]['domicilio'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['domicilio'] .
                    '</div>';

                $arrData[$i]['concepto'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['concepto'] .
                    '</div>';

                $arrData[$i]['importe'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . formatMoney($arrData[$i]['importe']) .
                    '</div>';

                $arrData[$i]['folio'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['folio'] .
                    '</div>';

                $arrData[$i]['folio_anterior'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['folio_anterior'] .
                    '</div>';

                $arrData[$i]['created_at'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . formatDateTime($arrData[$i]['created_at']) .
                    '</div>';

                if ($arrData[$i]['archivo'] != '') {
                    $arrData[$i]['archivo'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center">
                                                        <a target="_blank" href="' . base_url_assets() . '/files/' . $arrData[$i]['archivo'] . '"><i class="fa-regular fa-paperclip-vertical fs-16"></i> Ver Archivo</a> 
                                                    </div>';
                }

                //Formato Opciones
                // $btnView = '';
                $btnReimprimir = '';
                // $btnDelete = '';

                if ($this->permisosMod['r']) {

                    if ($arrData[$i]['estatus'] == 0) {
                        $btnReimprimir = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center" onclick="fntReimprimirRecibo(this)" data-id="' . $arrData[$i]['id'] . '" title= "Reimprimir Comprobante">
                                                <i class="fa-regular fa-file-pdf fs-14"></i>
                                        </button>';
                    }
                }

                //Formato Estatus de Registro
                if ($arrData[$i]['estatus'] == 0) {
                    $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-success">Vigente</span>
                                                  </div>';
                } else {
                    $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-danger">Cancelado</span>
                                                  </div>';
                }

                //Formato Options y Registros reservados
                $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex justify-content-center align-items-center">' . $btnReimprimir  . '</div>';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }
}
