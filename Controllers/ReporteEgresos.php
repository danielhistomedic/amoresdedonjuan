<?php

require 'Libraries/html2pdf/vendor/autoload.php';
require 'Libraries/numlet/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Luecano\NumeroALetras\NumeroALetras;

/**
 * Controlador ReporteEgresos 
 */
class ReporteEgresos extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador ReporteEgresos.
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
     * Carga la Vista ReporteEgresos. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function ReporteEgresos()
    {


        try {


            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_GASTOS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_REPORTES_GASTOS;

            //Header
            $data['page_title'] = "Reporte de Gastos";
            $data['page_description'] = "Reporte de Gastos";

            //Form Principal 
            $data['page_form_title'] = "<i class='fa-regular fa-message-dollar fa-fw text-secondary text-shadow-info'></i> Reporte de Gastos";

            //Breadcrump
            $data['page_breadcrumb'] = "Gastos";

            //Card Principal
            $data['page_card_title'] = "Reporte de Gastos";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo para Obtener Reporte de Gastos del Fraccionamiento.";

            //JS Principal
            $data['page_functions_js'] = "reporte_egresos.js";

            //Call Vista
            $this->views->getView($this, "reporteEgresos", $data);
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
    public function getEgresos($params)
    {

        try {

            $arrData = array();


            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_GASTOS];
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
            $gastos_model = new GastosModel;
            $gastos_model->setFecha_inicio($fecha_inicial);
            $gastos_model->setFecha_fin($fecha_final);

            $arrData = $gastos_model->selectGastosPeriodo($gastos_model);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                //Formato Estandar de datos

                // { "data": "fecha_pago" },
                // { "data": "clasificacion" },
                // { "data": "proveedor" },
                // { "data": "fecha_docto" },
                // { "data": "folio_nota" },
                // { "data": "descripcion" },
                // { "data": "importe" },
                // { "data": "archivo" },
                // { "data": "options" }

                //Formato Estandar de datos
                $arrData[$i]['fecha_pago'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . formatDate($arrData[$i]['fecha_pago']) .
                    '</div>';

                $arrData[$i]['clasificacion'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['clasificacion'] .
                    '</div>';

                $arrData[$i]['proveedor'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['proveedor'] .
                    '</div>';

                $arrData[$i]['fecha_docto'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . formatDate($arrData[$i]['fecha_docto']) .
                    '</div>';

                $arrData[$i]['folio_nota'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['folio_nota'] .
                    '</div>';

                $arrData[$i]['descripcion'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['descripcion'] .
                    '</div>';

                $arrData[$i]['importe'] =  '<div class="px-2 py-1 d-flex justify-content-end align-items-center">'
                    . formatMoney($arrData[$i]['importe']) .
                    '</div>';

                if ($arrData[$i]['archivo'] != '') {

                    $arrData[$i]['archivo'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center">
                                             <a target="_blank" href="' . base_url_assets() . '/files/' . $arrData[$i]['archivo'] . '"><i class="fa-regular fa-paperclip-vertical fs-16"></i> Ver Archivo</a> 
                                         </div>';
                }
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
    public function getResumenReporteEgresos(string $params)
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REPORTES_GASTOS];
            if ($this->permisosMod['r'] == 0) {
                $arrData = array(
                    'importe_total_egresos' => 0
                );
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }


            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($params)) {

                $arrData = array(
                    'importe_total_egresos' => 0
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

            $gastos_model = new GastosModel;
            $gastos_model->setFecha_fin($fecha_final);
            $gastos_model->setFecha_inicio($fecha_inicial);


            /*-------------------------------------------
            [ Obtiene importe total de ingresos del periodo ]*/
            $importe_total_egresos = $estado_resultados_model->selectImporteEgresosPeriodo($estado_resultados_model);


            $arrData = array(
                'importe_total_egresos' => $importe_total_egresos
            );
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrData = array(
                'importe_total_egresos' => 0
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
    public function generarPDFReporteEgresos(string $params)
    {

        /*-------------------------------------------
        [ Validación de Permisos ]*/
        $arrPermisos = getPermisosGlobal();
        $this->permisosMod = $arrPermisos[MOD_REPORTES_GASTOS];
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

        $gastos_model = new GastosModel;
        $gastos_model->setFecha_fin($fecha_final);
        $gastos_model->setFecha_inicio($fecha_inicial);


        /*-------------------------------------------
            [ Obtiene importe total de ingresos del periodo ]*/
        $importe_total_egresos = $estado_resultados_model->selectImporteEgresosPeriodo($estado_resultados_model);


        $arrData = array(
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
            'importe_total_egresos' => $importe_total_egresos
        );

        /*-------------------------------------------
        [ Obtiene lista de egresos del periodo ]*/

        $arrEgresos = $gastos_model->selectGastosPeriodo($gastos_model);
        $arrData['list_egresos'] = $arrEgresos;

        /*-------------------------------------------
        [ html2pdf ]*/

        $importe_total_ingresos_letra = 0;
        if ($importe_total_egresos > 0) {
            $importe_total_ingresos_letra = $importe_total_egresos;
        }
        $formatter = new NumeroALetras();
        $formatter->conector = 'PESOS';
        $numero_letra = $formatter->toInvoice($importe_total_ingresos_letra, 2, '');
        $arrData['importe_total_ingresos_letra'] = $numero_letra;

        $html = getFile("Template/Pdf/reporteEgresosPDF",  $arrData);
        // $html = getFile("Template/Modals/pdfBlanco",  $arrData);

        // (izquierda, superior, derecha, inferior)
        $margen_recibo = array(10, 10, 10, 10);

        $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8',  $margen_recibo);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($html, true, false, true, false, '');
        $html2pdf->output('ReporteEgresos.pdf');
    }
}
