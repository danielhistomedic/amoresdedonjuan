<?php

require 'Libraries/html2pdf/vendor/autoload.php';
require 'Libraries/numlet/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Luecano\NumeroALetras\NumeroALetras;

/**
 * Controlador InformeAdeudosCalle 
 */
class InformeAdeudosCalle extends Controllers
{

    private $session;
    private $permisosMod;
    private $cuenta_model;

    /**
     * Método Constructor de Controlador InformeAdeudosCalle.
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
     * Carga la Vista InformeAdeudosCalle. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function InformeAdeudosCalle()
    {

        try {


            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_ADEUDOS_CALLE];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_INFORMES_ADEUDOS_CALLE;

            //Header
            $data['page_title'] = "Informe de Adeudos por Calle";
            $data['page_description'] = "Informe de Adeudos por Calle";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-road fa-fw text-secondary text-shadow-info'></i> Informe de Adeudos por Calle";

            //Breadcrump
            $data['page_breadcrumb'] = "Adeudos por Calle";

            //Card Principal
            $data['page_card_title'] = "Adeudos por Calle";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo para Obtener Informe de Adeudos por Calle";

            //JS Principal
            $data['page_functions_js'] = "functions_informe_adeudos_calle.js";

            //Call Vista
            $this->views->getView($this, "informe_adeudos_calle", $data);
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
    public function getAdeudosCalle($params)
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_ADEUDOS_CALLE];
            if ($this->permisosMod['r'] == 0) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($params)) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            } else {
                $arrParams = explode(',', $params);
                $calle_id = strClean($arrParams[0]);
            }

            /*-------------------------------------------
            [ Obtiene el array con la lista de residentes relacionados a la calle ]*/
            $informe_adeudos_calle_model = new InformeAdeudosCalleModel;
            $arrAdeduosCalle = $informe_adeudos_calle_model->getResidentesCalle($calle_id);

            $tags_model = new TagsModel;
            $cuentas_model = new CuentasModel;

            /*-------------------------------------------
            [ Variables de Datos para el resumen ]*/
            $total_activos = 0;
            $total_inactivos = 0;
            $total_activos_convenio = 0;
            $importe_adeudo_resumen = 0;

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrAdeduosCalle); $i++) {

                $residente_id = $arrAdeduosCalle[$i]['id'];

                // //Formato Estandar de datos
                $arrAdeduosCalle[$i]['calle'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center">'
                    . $arrAdeduosCalle[$i]['calle'] .
                    '</div>';

                $arrAdeduosCalle[$i]['domicilio'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center"> '
                    . $arrAdeduosCalle[$i]['calle'] . ' ' .  $arrAdeduosCalle[$i]['numero'] .
                    '</div>';


                $importe_adeudo = $cuentas_model->getTotalAdeudo($residente_id);


                $estatus = $this->getEstatusResidente($residente_id, $cuentas_model);
                if ($estatus == 1 || $estatus == 2) {

                    if ($importe_adeudo == 0) {
                        $arrAdeduosCalle[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                    <span class="badge badge-success">ACTIVO</span>
                                </div>';
                    } else {
                        if ($importe_adeudo > RENTA_ANT) {
                            $arrAdeduosCalle[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                                                <span class="badge badge-warning">ACTIVO (CONVENIO)</span>
                                                            </div>';
                        } else {
                            $arrAdeduosCalle[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                                                    <span class="badge badge-success">ACTIVO</span>
                                                                </div>';
                        }
                    }
                } else {
                    $arrAdeduosCalle[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-danger">INACTIVO</span>
                                                  </div>';
                }


                $arrAdeduosCalle[$i]['total_adeudo'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center">'
                    . formatMoney($importe_adeudo) .
                    '</div>';


                $arrAdeduosCalle[$i]['tags_registradas'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center"> '
                    . '---' .
                    '</div>';
                $arrTags = $tags_model->selectTagResidente($residente_id);
                if (empty($arrTags)) {
                    $arrAdeduosCalle[$i]['tags_registradas'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center"> '
                        . 'NO TIENE TAGS REGISTRADAS' .
                        '</div>';
                } else {

                    $tags_list = "";
                    for ($jj = 0; $jj < count($arrTags); $jj++) {
                        if ($jj == 0) {
                            $tags_list .= $arrTags[$jj]['tag'];
                        } else {
                            $tags_list .= ',  ' . $arrTags[$jj]['tag'];
                        }
                    }
                    $arrAdeduosCalle[$i]['tags_registradas'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center"> '
                        . $tags_list .
                        '</div>';
                }



                /*-------------------------------------------
                [ Datos para resumen ]*/
                if ($estatus == 1 || $estatus == 2) {
                    if ($importe_adeudo == 0) {
                        $total_activos += 1;
                    } else {
                        if ($importe_adeudo > RENTA_ANT) {
                            $total_activos_convenio += 1;
                        } else {
                            $total_activos += 1;
                        }
                    }
                } else {
                    $total_inactivos += 1;
                }

                $importe_adeudo_resumen += $importe_adeudo;
            }

            //Guardar Resumen de Adedudos por Calle
            $usuario_id_register = $this->session->get('usuario_id');

            $informe_adeudos_calle_model->setCalle_id($calle_id);
            $informe_adeudos_calle_model->setTotal_activos($total_activos);
            $informe_adeudos_calle_model->setTotal_inactivos($total_inactivos);
            $informe_adeudos_calle_model->setTotal_activos_convenio($total_activos_convenio);
            $informe_adeudos_calle_model->setImporte_adeudo($importe_adeudo_resumen);

            $informe_adeudos_calle_model->updateResumenAdeudosCalle($informe_adeudos_calle_model, $usuario_id_register);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrAdeduosCalle, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene los datos del Estatus de Cuenta de un residente seleccionado.
     * 
     * @param int $residente_id
     * Identificador de residente
     * 
     * @return int $estatus

     */
    public function getEstatusResidente(int $residente_id, CuentasModel $cuentasModel): int
    {

        try {

            $response = 0;

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_ADEUDOS_CALLE];
            if ($this->permisosMod['r'] == 0) {
                return $response;
            }

            /*-------------------------------------------
            [ Actualizar Estatus de Tag de Residente ]*/
            $estatus_cuenta_mes_corriente = $cuentasModel->getEstatusCuentaMesCorriente($residente_id);

            if ($estatus_cuenta_mes_corriente == 1) {

                $response = $estatus_cuenta_mes_corriente;
            } else {

                $fecha_actual = strtotime(date("Y-m-d"));

                $date_y = date("Y");
                $date_m = date("m");
                $date_str = $date_y . "-" . $date_m . "-05";
                $fecha_entrada = strtotime($date_str);

                if ($fecha_actual > $fecha_entrada) {
                    $response = 0;
                } else {
                    $estatus_cuenta_mes_corriente = $cuentasModel->getEstatusCuentaMesAnterior($residente_id);
                    if ($estatus_cuenta_mes_corriente == 1) {
                        $response = $estatus_cuenta_mes_corriente;
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $response = 0;
        }
        return $response;
    }



    /**
     * Obtiene los datos del Estatus de Cuenta de un residente seleccionado.
     * 
     * @param int $residente_id
     * Identificador de residente
     * 
     * @return int $estatus

     */
    public function getEstatusResidenteInformeAdeudos(int $residente_id, CuentasModel $cuentas_model): int
    {

        try {

            $response_estatus = 0;

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_ADEUDOS_CALLE];
            if ($this->permisosMod['r'] == 0) {
                return $response_estatus;
            }

            /*-------------------------------------------
            [ Actualizar Estatus de Tag de Residente ]*/
            $estatus_cuenta_mes_corriente = $cuentas_model->getEstatusCuentaMesCorriente($residente_id);

            if ($estatus_cuenta_mes_corriente == 1) {

                $response_estatus = $estatus_cuenta_mes_corriente;
            } else {

                $fecha_actual = strtotime(date("Y-m-d"));

                $date_y = date("Y");
                $date_m = date("m");
                $date_str = $date_y . "-" . $date_m . "-05";
                $fecha_entrada = strtotime($date_str);

                if ($fecha_actual > $fecha_entrada) {
                    $response_estatus = 0;
                } else {
                    $estatus_cuenta_mes_corriente = $cuentas_model->getEstatusCuentaMesAnterior($residente_id);
                    if ($estatus_cuenta_mes_corriente == 1) {
                        $response_estatus = 2;
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $response_estatus = 0;
        }
        return $response_estatus;
    }


    /**
     * Genera Infome de Adeudos por Calle en formato pdf.
     * 
     * @param string $params
     * Arreglo con los paramteros a filtrar
     * 
     */
    public function generarAdeudosCalle(string $params)
    {

        /*-------------------------------------------
            [ Validación de Permisos ]*/
        $arrPermisos = getPermisosGlobal();
        $this->permisosMod = $arrPermisos[MOD_INFORMES_ADEUDOS_CALLE];
        if (!$this->permisosMod['p_excel']) {
            echo "<h4>Lo sentimos, Acceso restringido</h4>";
            die();
        }

        /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
        if (empty($params)) {
            header('Location: ' . base_url());
            die();
        } else {
            $arrParams = explode(',', $params);
            $calle_id = strClean($arrParams[0]);
        }

        /*-------------------------------------------
        [ Instanciar Meodelo ]*/
        $tags_model = new TagsModel;
        $cuentas_model = new CuentasModel;


        /*-------------------------------------------
        [ Obtiene el array con la lista de residentes relacionados a la calle ]*/
        $informe_adeudos_calle_model = new InformeAdeudosCalleModel;
        $arrAdeduosCalle = $informe_adeudos_calle_model->getResidentesCalle($calle_id);


        $total_adeudo = 0;
        $total_activos = 0;
        $total_inactivos = 0;
        $arrData = array(
            'calle_reporte' => $arrAdeduosCalle[0]['calle'],
            'importe_adeudo' => $total_adeudo,
            'total_adeudo' => $total_adeudo,
            'total_activos' => $total_activos,
            'total_inactivos' => $total_inactivos
        );



        /*-------------------------------------------
        [ Obtiene lista de residentes ]*/
        /*-------------------------------------------
        [ Personaliza los datos del array ]*/
        for ($i = 0; $i < count($arrAdeduosCalle); $i++) {

            $residente_id = $arrAdeduosCalle[$i]['id'];

            $arrAdeduosCalle[$i]['calle'] =  $arrAdeduosCalle[$i]['calle'];

            $arrAdeduosCalle[$i]['domicilio'] =  $arrAdeduosCalle[$i]['calle'] . ' ' .  $arrAdeduosCalle[$i]['numero'];

            $importe_adeudo = $cuentas_model->getTotalAdeudo($residente_id);
            $arrAdeduosCalle[$i]['total_adeudo'] =  formatMoney($importe_adeudo);

            $estatus = $this->getEstatusResidenteInformeAdeudos($residente_id, $cuentas_model);

            $arrAdeduosCalle[$i]['estatus_id'] =  $estatus;

            if ($estatus == 1 || $estatus == 2) {
                if ($importe_adeudo == 0) {
                    $arrAdeduosCalle[$i]['estatus'] = 'Activo';
                } else {
                    if ($importe_adeudo > RENTA_ANT) {
                        $arrAdeduosCalle[$i]['estatus'] = 'Activo (Convenio)';
                    } else {
                        $arrAdeduosCalle[$i]['estatus'] = 'Activo';
                    }
                }
            } else {
                $arrAdeduosCalle[$i]['estatus'] = 'Inactivo';
            }

            $arrTags = $tags_model->selectTagResidente($residente_id);
            if (empty($arrTags)) {
                $arrAdeduosCalle[$i]['tags_registradas'] =  'No tiene Tags Registradas';
            } else {

                $tags_list = "";
                for ($jj = 0; $jj < count($arrTags); $jj++) {
                    if ($jj == 0) {
                        $tags_list .= $arrTags[$jj]['tag'];
                    } else {
                        $tags_list .= ',  ' . $arrTags[$jj]['tag'];
                    }
                }
                $arrAdeduosCalle[$i]['tags_registradas'] =  $tags_list;
            }
        }
        $arrData['list_residentes'] = $arrAdeduosCalle;



        $informe_adeudos_calle_model = new InformeAdeudosCalleModel;
        $arrResumen = $informe_adeudos_calle_model->selectResumenInformeAdeudoCalle($calle_id);

        $arrData['importe_adeudo'] = $arrResumen['importe_adeudo'];
        $arrData['total_activos_convenio'] = $arrResumen['total_activos_convenio'];
        $arrData['total_activos'] = $arrResumen['total_activos'];
        $arrData['total_inactivos'] = $arrResumen['total_inactivos'];

        /*-------------------------------------------
        [ html2pdf ]*/

        $total_adeudo_letra = 0;
        if ($total_adeudo > 0) {
            $total_adeudo_letra = $total_adeudo;
        }
        $formatter = new NumeroALetras();
        $formatter->conector = 'PESOS';
        $numero_letra = $formatter->toInvoice($total_adeudo_letra, 2, '');
        $arrData['total_adeudo_letra'] = $numero_letra;

        $html = getFile("Template/Pdf/adeudosCallePDF",  $arrData);
        // $html = getFile("Template/Modals/pdfBlanco",  $arrData);

        // (izquierda, superior, derecha, inferior)
        $margen_recibo = array(10, 10, 10, 10);

        $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8',  $margen_recibo);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($html, true, false, true, false, '');
        $html2pdf->output('InformeAdeudosCalle.pdf');
    }

    /**
     * Obtiene la lista de Estado de Cuenta del Residente para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getResumenAdeudosCalle($calle_id): array
    {

        try {


            $arrResponse = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_INFORMES_ADEUDOS_CALLE];
            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            if (empty($calle_id)) {
                die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Obtiene el array con la lista de residentes relacionados a la calle ]*/
            $informe_adeudos_calle_model = new InformeAdeudosCalleModel;
            $arrData = $informe_adeudos_calle_model->selectResumenInformeAdeudoCalle($calle_id);

            if (!empty($arrData)) {

                $arrResponse = getResponse('Datos encontrados.', 'ok', false);
                $arrResponse['data'] = $arrData;
            } else {

                die(json_encode(getResponse('No se encontraron datos.', 'error', false), JSON_UNESCAPED_UNICODE));
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('Code_Error infAdeudo_1001. Error desconocido.', 'error', false), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }
}
