<?php

require 'Libraries/html2pdf/vendor/autoload.php';
require 'Libraries/numlet/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Luecano\NumeroALetras\NumeroALetras;

/**
 * Controlador EstadoResultados 
 */
class EstadoResultados extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador EstadoResultados.
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
     * Carga la Vista EstadoResultados. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function EstadoResultados()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_INGRESOS_EGRESOS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_INFORMES_INGRESOS_EGRESOS;

            //Header
            $data['page_title'] = "Informe de Ingresos y Egresos";
            $data['page_description'] = "Informe de Ingresos y Egresos";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-file-invoice-dollar fa-fw text-secondary text-shadow-info'></i> Informe de Ingresos y Egresos";

            //Breadcrump
            $data['page_breadcrumb'] = "Ingresos y Egresos";

            //Card Principal
            $data['page_card_title'] = "Ingresos y Egresos";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo para Obtener Informe de Ingresos y Egresos";

            //JS Principal
            $data['page_functions_js'] = "functions_estado_resultados.js";

            //Call Vista
            $this->views->getView($this, "estado_resultados", $data);
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
            $this->permisosMod = $arrPermisos[MOD_INFORMES_INGRESOS_EGRESOS];
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
            $estado_resultados_model = new EstadoResultadosModel;
            $estado_resultados_model->setFiltro_fecha_inicio($fecha_inicial);
            $estado_resultados_model->setFiltro_fecha_fin($fecha_final);

            $arrData = $estado_resultados_model->selectIngresos($estado_resultados_model);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                //Formato Estandar de datos
                $arrData[$i]['calle'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center">'
                    . $arrData[$i]['calle'] .
                    '</div>';

                $arrData[$i]['importe'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center">'
                    . formatMoney($arrData[$i]['importe']) .
                    '</div>';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de Egresos en un Periodo Determinado para llenar la tabla en DataTable.net
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
            $this->permisosMod = $arrPermisos[MOD_INFORMES_INGRESOS_EGRESOS];
            if ($this->permisosMod['r'] == 0) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($params)) {
                header('Location: ' . base_url());
                die();
            } else {
                $arrParams = explode(',', $params);
                $fecha_inicial = strClean($arrParams[0]);
                $fecha_final = strClean($arrParams[1]);
            }

            /*-------------------------------------------
            [ Obtiene el array con la lista de egreso del periodo ]*/
            $estado_resultados_model = new EstadoResultadosModel;
            $estado_resultados_model->setFiltro_fecha_inicio($fecha_inicial);
            $estado_resultados_model->setFiltro_fecha_fin($fecha_final);

            $arrData = $estado_resultados_model->selectEgresos($estado_resultados_model);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                //     { "data": "fecha_pago" },
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
     * Obtiene el saldo de la Cuenta a una Fecha Determinada.
     * 
     * @param string $fecha
     * Fecha en formato Y-m-d
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 
     * * saldo = resultado de  $importe_ingresos - $importe_egresos de una fecha determinada;
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getSaldoAnterior(string $fecha)
    {

        try {

            $arrRespuesta = array();
            $saldo = 0;

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_INGRESOS_EGRESOS];
            if ($this->permisosMod['r'] == 0) {
                $arrRespuesta = array(
                    'saldo' => $saldo
                );
                die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $fecha  = strClean($fecha);

            /*-------------------------------------------
            [ Obtiene el saldo anterior a la fecha indicada ]*/
            $estado_resultados_model = new EstadoResultadosModel;
            $estado_resultados_model->setFiltro_fecha_inicio($fecha);
            $estado_resultados_model->setSaldo_inicial(SALDO_INICIAL_CUENTA);

            $importe_ingresos = $estado_resultados_model->selectImporteIngresosSaldo($estado_resultados_model);
            $importe_egresos =  $estado_resultados_model->selectImporteEgresosSaldo($estado_resultados_model);

            $saldo = $importe_ingresos - $importe_egresos;
            $arrRespuesta = array(
                'saldo' => $saldo
            );
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
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
    public function getImporteTotalIngresosPeriodo(string $params)
    {

        try {

            $arrRespuesta = array();
            $total_ingresos = 0;
            $importe_total_ingresos_desglose = 0;

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_INGRESOS_EGRESOS];
            if ($this->permisosMod['r'] == 0) {
                $arrRespuesta = array(
                    'total_ingresos' => $total_ingresos,
                    'importe_total_ingresos_desglose' => $importe_total_ingresos_desglose
                );
                die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($params)) {
                $arrRespuesta = array(
                    'total_ingresos' => $total_ingresos,
                    'importe_total_ingresos_desglose' => $importe_total_ingresos_desglose
                );
                die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
            } else {
                $arrParams = explode(',', $params);
                $fecha_inicial = strClean($arrParams[0]);
                $fecha_final = strClean($arrParams[1]);
            }

            /*-------------------------------------------
            [ Obtiene importe total de ingresos del periodo ]*/
            $estado_resultados_model = new EstadoResultadosModel;
            $estado_resultados_model->setFiltro_fecha_inicio($fecha_inicial);
            $estado_resultados_model->setFiltro_fecha_fin($fecha_final);

            $total_ingresos = $estado_resultados_model->selectImporteIngresosPeriodo($estado_resultados_model);

            /*-------------------------------------------
            [ Obtiene arreglo de desglose de ingresos en un periodo determinado ]*/
            $recibos_model = new RecibosModel;
            $recibos_model->setFecha_fin($fecha_final);
            $recibos_model->setFecha_inicio($fecha_inicial);
            $importe_total_ingresos_desglose = $recibos_model->selectIngresosDesglose($recibos_model);


            $arrRespuesta = array(
                'total_ingresos' => $total_ingresos,
                'importe_total_ingresos_desglose' => $importe_total_ingresos_desglose
            );
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene Importe Total de Egresos de la Cuenta en un Periodo Seleccionado.
     * 
     * @param string $fecha
     * Fecha en formato Y-m-d
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores:
     * * total_egresos;
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getImporteTotalEgresosPeriodo(string $params)
    {

        try {

            $arrRespuesta = array();
            $total_egresos = 0;

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_INGRESOS_EGRESOS];
            if ($this->permisosMod['r'] == 0) {
                $arrRespuesta = array(
                    'total_egresos' => $total_egresos
                );
                die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($params)) {
                $arrRespuesta = array(
                    'total_egresos' => $total_egresos
                );
                die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
            } else {
                $arrParams = explode(',', $params);
                $fecha_inicial = strClean($arrParams[0]);
                $fecha_final = strClean($arrParams[1]);
            }

            /*-------------------------------------------
            [ Obtiene el importe de egreso del periodo ]*/
            $estado_resultados_model = new EstadoResultadosModel;
            $estado_resultados_model->setFiltro_fecha_inicio($fecha_inicial);
            $estado_resultados_model->setFiltro_fecha_fin($fecha_final);

            $total_egresos = $estado_resultados_model->selectImporteEgresosPeriodo($estado_resultados_model);


            $arrRespuesta = array(
                'total_egresos' => $total_egresos
            );
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Genera Infome de Estado de Ingresos y Egresos en formato pdf.
     * 
     * @param string $params
     * Arreglo con los paramteros a filtrar
     * 
     */
    public function generarEstadoResultados(string $params)
    {

        /*-------------------------------------------
        [ Validación de Permisos ]*/
        $arrPermisos = getPermisosGlobal();
        $this->permisosMod = $arrPermisos[MOD_INFORMES_INGRESOS_EGRESOS];
        // Valida si tiene acceso a la pagina.
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
        $estado_resultados_model->setSaldo_inicial(SALDO_INICIAL_CUENTA);

        /*-------------------------------------------
        [ Obtiene el saldo anterior a la fecha indicada ]*/
        $importe_ingresos = $estado_resultados_model->selectImporteIngresosSaldo($estado_resultados_model);
        $importe_egresos =  $estado_resultados_model->selectImporteEgresosSaldo($estado_resultados_model);
        $saldo_anterior = $importe_ingresos - $importe_egresos;


        /*-------------------------------------------
        [ Obtiene importe total de ingresos del periodo ]*/
        $ingresos_periodo = $estado_resultados_model->selectImporteIngresosPeriodo($estado_resultados_model);


        /*-------------------------------------------
        [ Obtiene arreglo de desglose de ingresos en un periodo determinado ]*/
        $recibos_model = new RecibosModel;
        $recibos_model->setFecha_fin($fecha_final);
        $recibos_model->setFecha_inicio($fecha_inicial);
        $importe_total_ingresos_desglose = $recibos_model->selectIngresosDesglose($recibos_model);


        /*-------------------------------------------
        [ Obtiene el importe de egreso del periodo ]*/
        $gastos_periodo = $estado_resultados_model->selectImporteEgresosPeriodo($estado_resultados_model);


        /*-------------------------------------------
        [ Obtiene el saldo actual del periodo ]*/
        $saldo_periodo = $saldo_anterior + $ingresos_periodo - $gastos_periodo;



        $fecha_saldo_anterior = date("Y-m-d", strtotime($fecha_inicial . "- 1 days"));
        $fecha_saldo_anterior = formatDate($fecha_saldo_anterior);

        $arrData = array(
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
            'fecha_saldo_anterior' => $fecha_saldo_anterior,
            'saldo_anterior' => $saldo_anterior,
            'ingresos_periodo' => $ingresos_periodo,
            'importe_total_ingresos_desglose' => $importe_total_ingresos_desglose,
            'gastos_periodo' => $gastos_periodo,
            'saldo_periodo' => $saldo_periodo
        );

        /*-------------------------------------------
        [ Obtiene lista de ingresos del periodo ]*/
        $arrIngresos = $estado_resultados_model->selectIngresos($estado_resultados_model);
        $arrData['list_ingresos'] = $arrIngresos;


        /*-------------------------------------------
        [ Obtiene lista de egresos del periodo ]*/
        $arrEgresos = $estado_resultados_model->selectEgresos($estado_resultados_model);
        $arrData['list_egresos'] = $arrEgresos;


        /*-------------------------------------------
        [ html2pdf ]*/

        $saldo_periodo_letra = 0;
        if ($saldo_periodo > 0) {
            $saldo_periodo_letra = $saldo_periodo;
        }
        $formatter = new NumeroALetras();
        $formatter->conector = 'PESOS';
        $numero_letra = $formatter->toInvoice($saldo_periodo_letra, 2, '');
        $arrData['saldo_periodo_letra'] = $numero_letra;

        $html = getFile("Template/Pdf/estadoResultados",  $arrData);
        // $html = getFile("Template/Modals/pdfBlanco",  $arrData);

        // (izquierda, superior, derecha, inferior)
        $margen_recibo = array(10, 10, 10, 10);

        $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8',  $margen_recibo);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($html, true, false, true, false, '');
        $html2pdf->output('InfomreIngresosEgresos.pdf');
    }


    /**
     * Genera Infome de Ingresos y Egresos en formato pdf para subirlo al portal del Cliente
     * 
     * @param string $params
     * Arreglo con los paramteros a filtrar
     * 
     */
    public function generarEstadoResultadosCierre(string $params): array
    {

        /*-------------------------------------------
        [ Validación de Permisos ]*/
        $arrPermisos = getPermisosGlobal();
        $this->permisosMod = $arrPermisos[MOD_INFORMES_INGRESOS_EGRESOS];
        if (!$this->permisosMod['c']) {
            die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Asignar y Limpiar parametros recibidos ]*/
        if (empty($params)) {
            die(json_encode(getResponse('Debe llenar correctamente los datos de Periodo a Ejecutar.'), JSON_UNESCAPED_UNICODE));
        } else {
            $arrParams = explode(',', $params);
            $fecha_inicial = strClean($arrParams[0]);
            $fecha_final = strClean($arrParams[1]);
        }

        /*-------------------------------------------
        [ Valida Datos obligatroios. ]*/
        if ($fecha_inicial == '' || $fecha_inicial == 'undefined-undefined-') {
            die(json_encode(getResponse('Debe indicar Fecha Inicial.'), JSON_UNESCAPED_UNICODE));
        }
        if ($fecha_final == '' || $fecha_final == 'undefined-undefined-') {
            die(json_encode(getResponse('Debe indicar Fecha Final.'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Se asigan variables de Sesion. ]*/
        $usuario_id_register =  $this->session->get('usuario_id');

        /*-------------------------------------------
        [ Instanciar el modelo ]*/
        $ingresos_egresos_cierre_model = new IngresosEgresosCierreModel;
        $ingresos_egresos_cierre_model->setFiltroFechaInicio($fecha_inicial);
        $ingresos_egresos_cierre_model->setFiltroFechaFin($fecha_final);
        $ingresos_egresos_cierre_model->setUsuarioIdCreated($usuario_id_register);

        $mes = formatDate_Mes2($fecha_inicial);
        $ingresos_egresos_cierre_model->setMes($mes);

        $anio = formatDate_Anio2($fecha_inicial);
        $ingresos_egresos_cierre_model->setAnio($anio);

        $descripcion = 'Informe de Ingresos y Egresos del Periodo del ' . formatDate($fecha_inicial) . ' al ' . formatDate($fecha_final);
        $ingresos_egresos_cierre_model->setDescripcion($descripcion);

        $response = $ingresos_egresos_cierre_model->insertInformeIngresosEgresosCierre($ingresos_egresos_cierre_model);

        /*-------------------------------------------
        [ Evalúa Respuesta ]*/
        if ($response == false) {
            die(json_encode(getResponse('Error al generar el Archivo de Cierre de Mes, para el Portal de Residentes. <br/> Intente nuevamente'), JSON_UNESCAPED_UNICODE));
        }
        $arrResponse = getResponse('Archivo generado exitosamente, verifique en su portal', 'ok', true);

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }
}
