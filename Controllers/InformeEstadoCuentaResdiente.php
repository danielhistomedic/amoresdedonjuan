<?php

require 'Libraries/html2pdf/vendor/autoload.php';
require 'Libraries/numlet/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Luecano\NumeroALetras\NumeroALetras;

/**
 * Controlador InformeEstadoCuentaResdiente 
 */
class InformeEstadoCuentaResdiente extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador InformeEstadoCuentaResdiente.
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
     * Carga la Vista InformeEstadoCuentaResdiente. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function InformeEstadoCuentaResdiente()
    {

        try {


            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_ESTADO_CUENTA_RESIDENTE];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_INFORMES_ESTADO_CUENTA_RESIDENTE;

            //Header
            $data['page_title'] = "Informe de Estado de Cuenta de Residente";
            $data['page_description'] = "Informe de Estado de Cuenta de Residente";

            //Form Principal 
            $data['page_form_title'] = "<i class='fa-regular fa-chart-user fa-fw text-secondary text-shadow-info'></i> Informe de Estado de Cuenta de Residente";

            //Breadcrump
            $data['page_breadcrumb'] = "Estado de Cuenta";

            //Card Principal
            $data['page_card_title'] = "Estado de Cuenta de Residente";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo para Obtener Informe de Historial de Pagos por Residente";

            //JS Principal
            $data['page_functions_js'] = "functions_informe_estado_cuenta_residente.js";

            //Call Vista
            $this->views->getView($this, "informe_estado_cuenta_residente", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene la lista de Estado de Cuenta del Residente para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getCuentas($params)
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_ESTADO_CUENTA_RESIDENTE];
            if ($this->permisosMod['r'] == 0) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }


            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($params)) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            } else {
                $arrParams = explode(',', $params);
                $residente_id = strClean($arrParams[0]);
            }

            /*-------------------------------------------
            [ Obtiene el array con la lista de ingresos del periodo ]*/
            $estado_cuenta_model = new CuentasModel;
            $arrData = $estado_cuenta_model->selectListaEstadoCuenta($residente_id);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                //Formato Estandar de datos
                $arrData[$i]['anio'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center">'
                    . $arrData[$i]['anio'] .
                    '</div>';

                $arrData[$i]['mes'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center"> '
                    . format_Mes($arrData[$i]['mes'])  .
                    '</div>';

                //Formato Estatus de Registro
                if ($arrData[$i]['estatus'] == 1) {
                    $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-success">Pagado</span>
                                                  </div>';
                } else {
                    $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-danger">Pendiente de Pago</span>
                                                  </div>';
                }



                $arrData[$i]['descripcion'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center"> '
                    . $arrData[$i]['descripcion'] .
                    '</div>';

                $arrData[$i]['folio'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center"> '
                    . $arrData[$i]['folio'] .
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
     * Obtiene los datos del Resumen de Estado de Cuenta de un residente seleccionado.
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
    public function getResumenEstadoCuenta(int $residente_id)
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_ESTADO_CUENTA_RESIDENTE];
            if ($this->permisosMod['r'] == 0) {
                $arrRespuesta = getResponse('Acceso restrigido');
                $arrRespuesta['data'] = $arrData;
                die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $residente_id = intval(strClean($residente_id));

            if ($residente_id > 0) {

                $cuentas_model = new CuentasModel;

                /*-------------------------------------------
                [ Obtiene array con los datos del Usuario ]*/
                $importe_adeudo = $cuentas_model->getTotalAdeudo($residente_id);
                $arrData['importe_adeudo'] =  $importe_adeudo;

                $importe_saldo_disponible = $cuentas_model->getSaldoCuentaDisponible($residente_id);
                $arrData['importe_saldo_disponible'] =  $importe_saldo_disponible;

                $arrRespuesta = array(
                    'respuesta' => 'ok', 'mostrar_mensaje' => false, 'tiempo' => 6000,
                    'mensaje' => 'Datos encontrados',
                    'data' => $arrData
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrRespuesta = getResponse('Datos No Encontrados.');
            $arrRespuesta['data'] = $arrData;
            die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
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
            $this->permisosMod = $arrPermisos[MOD_INFORMES_ESTADO_CUENTA_RESIDENTE];
            if ($this->permisosMod['r'] == 0) {
                die(json_encode(getResponse('Acceso restrigido'), JSON_UNESCAPED_UNICODE));
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



    /**
     * Genera Infome de Adeudos por Calle en formato pdf.
     * 
     * @param string $params
     * Arreglo con los paramteros a filtrar
     * 
     */
    public function generarEstadoCuentaResidente(string $params)
    {


        /*-------------------------------------------
        [ Validación de Permisos ]*/
        $arrPermisos = getPermisosGlobal();
        $this->permisosMod = $arrPermisos[MOD_INFORMES_ESTADO_CUENTA_RESIDENTE];
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
            $residente_id = strClean($arrParams[0]);
        }


        $cuentas_model = new CuentasModel;
        $importe_adeudo = $cuentas_model->getTotalAdeudo($residente_id);
        $importe_saldo_disponible = $cuentas_model->getSaldoCuentaDisponible($residente_id);

        $residente_model = new ResidentesModel;
        $residente = $residente_model->selectResidente($residente_id);

        $arrData = array(
            'nombre' => $residente['nombre'],
            'calle' => $residente['calle'],
            'numero' => $residente['numero'],
            'importe_adeudo' => $importe_adeudo,
            'importe_saldo_disponible' => $importe_saldo_disponible
        );


        /*-------------------------------------------
        [ Obtiene el array con la lista de ingresos del periodo ]*/
        $estado_cuenta_model = new CuentasModel;
        $arrCuenta = $estado_cuenta_model->selectListaEstadoCuenta($residente_id);

        // cta.*, rec.folio, res.calle, res.numero, res.nombre
        $arrData['list_cuenta'] = $arrCuenta;

        /*-------------------------------------------
        [ html2pdf ]*/

        $total_adeudo_letra = 0;
        if ($importe_adeudo > 0) {
            $total_adeudo_letra = $importe_adeudo;
        }
        $formatter = new NumeroALetras();
        $formatter->conector = 'PESOS';
        $numero_letra = $formatter->toInvoice($total_adeudo_letra, 2, '');
        $arrData['total_adeudo_letra'] = $numero_letra;

        $html = getFile("Template/Pdf/estadoCuentaResidente",  $arrData);
        // $html = getFile("Template/Modals/pdfBlanco",  $arrData);

        // (izquierda, superior, derecha, inferior)
        $margen_recibo = array(10, 10, 10, 10);

        $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8',  $margen_recibo);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($html, true, false, true, false, '');
        $html2pdf->output('EstadoCuentaResidente.pdf');
    }
}
