<?php

require 'Libraries/html2pdf/vendor/autoload.php';
require 'Libraries/numlet/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Luecano\NumeroALetras\NumeroALetras;


/**
 * Controlador RecibosAnteriores 
 */
class RecibosAnteriores extends Controllers
{


    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador RecibosAnteriores.
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
     * Carga la Vista RecibosAnteriores. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function RecibosAnteriores(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO_PASADOS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_RECIBOS_COBRO_PASADOS;

            //Header
            $data['page_title'] = "Recibos de Cobro Pasados";
            $data['page_description'] = "Recibos de Cobro Pasados";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-money-check-dollar-pen fa-fw text-primary text-shadow-info'></i> Recibos de Cobro Pasados";

            //Breadcrump
            $data['page_breadcrumb'] = "Recibos Cobro Pasados";

            //Card Principal
            $data['page_card_title'] = "Registro de Recibos de Cobro";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Registro y Control de Recibos de Pago Pasados.";

            //JS Principal
            $data['page_functions_js'] = "functions_recibos_anteriores.js";

            //Call Vista
            $this->views->getView($this, "recibos_anteriores", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Guardar datos de Recibo de Cobro Anterior
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setReciboAnterior()
    {
        /*-------------------------------------------
        [ Validación de Límite de Tamaño POST ]*/
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
            die(json_encode(getResponse('El tamaño de la solicitud o del archivo adjunto supera el límite permitido por el servidor.'), JSON_UNESCAPED_UNICODE));
        }

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO_PASADOS];
            if (!$this->permisosMod['c']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            // /*-------------------------------------------
            // [ Se reciben Datos del POST con FormData ]*/
            $arrPagosAdeudos = array();
            if (isset($_POST['mes_pago_adeudos'])) {
                $arrPagosAdeudos = $_POST['mes_pago_adeudos'];
            }

            $recibo_id = isset($_POST['inputIdReciboAnterior']) ? strclean($_POST['inputIdReciboAnterior']) : '';
            $folioanterior = isset($_POST['recibo_anterior_folioanterior']) ? strclean($_POST['recibo_anterior_folioanterior']) : '';
            $cambio = isset($_POST['recibo_anterior_cambio']) ? strclean($_POST['recibo_anterior_cambio']) : '0';
            $cantidad = isset($_POST['recibo_anterior_cantidad']) ? strclean($_POST['recibo_anterior_cantidad']) : '0';
            $concepto = isset($_POST['recibo_anterior_concepto']) ? strclean($_POST['recibo_anterior_concepto']) : '';
            $dejaacuenta = isset($_POST['recibo_anterior_dejaacuenta']) ? strclean($_POST['recibo_anterior_dejaacuenta']) : '0';
            $descuento = isset($_POST['recibo_anterior_descuento']) ? strclean($_POST['recibo_anterior_descuento']) : '0';
            $monto = isset($_POST['recibo_anterior_importe']) ? strclean($_POST['recibo_anterior_importe']) : '0';
            $recibe = isset($_POST['recibo_anterior_recibe']) ? strclean($_POST['recibo_anterior_recibe']) : '0';
            $subtotal = isset($_POST['recibo_anterior_subtotal']) ? strclean($_POST['recibo_anterior_subtotal']) : '0';

            $residente_id = isset($_POST['recibo_residente_id']) ? intval(strclean($_POST['recibo_residente_id'])) : 0;
            $nombre = "-";
            $calle = "";
            $numero = "";

            if ($residente_id > 0) {
                $residente_model = new ResidentesModel;
                $residente = $residente_model->selectResidente($residente_id);
                if (!empty($residente)) {
                    $nombre = !empty($residente['nombre']) ? $residente['nombre'] : "-";
                    $calle = !empty($residente['calle']) ? $residente['calle'] : "";
                    $numero = !empty($residente['numero']) ? $residente['numero'] : "";
                }
            }

            // FILE
            $files = array();
            if (isset($_FILES['recibo_anterior_archivo'])) {
                $files = $_FILES['recibo_anterior_archivo'];
            }

            $recibo_model = new RecibosAnterioresModel;
            $recibo_model->setResidente_id($residente_id);
            $recibo_model->setResidente($nombre);
            $recibo_model->setCalle($calle);
            $recibo_model->setNumero($numero);
            $recibo_model->setImporte($monto);
            $recibo_model->setConcepto($concepto);
            $recibo_model->setSubtotal($subtotal);
            $recibo_model->setFolio_anterior($folioanterior);

            $recibo_model->setCambio($cambio);
            $recibo_model->setCantidad($cantidad);
            $recibo_model->setDeja_cuenta($dejaacuenta);
            $recibo_model->setPorc_descuento($descuento);
            $recibo_model->setRecibe($recibe);
            $recibo_model->setConcepto_id(2);

            $recibo_model->setPagos_adeudos($arrPagosAdeudos);

            // /*-------------------------------------------
            // [ Se asigan variables de Sesion. ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            // /*-------------------------------------------
            // [ Actualizar Registro de Unidad Medica si pasa las validaciones. ]*/
            $response = $recibo_model->insertRecibo($recibo_model, $files, $usuario_id_register);


            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {

                /*-------------------------------------------
                [ Activar Tags  ]*/
                $tag_model = new TagsModel;
                $tag_model->activarTag($residente_id, $usuario_id_register);

                $recibo = $recibo_model->selectRecibo($recibo_model->getId());

                $arrResponse = getResponse('Registro realizado exitosamente', 'ok', false);
                $arrResponse['recibo'] = $recibo;
            } else {

                die(json_encode(getResponse('Error al realizar el registro, intente nuevamente.'), JSON_UNESCAPED_UNICODE));
            }
        } catch (\Throwable $th) {

            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('Error al realizar el registro, intente nuevamente.'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Cancelar Recibo Anterior
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setCancelarReciboAnterior()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO_PASADOS];
            if (!$this->permisosMod['d']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $id_recibo = intval($_POST['id']);
            $estatus = intval($_POST['estatus']);
            $residente_id = intval($_POST['residente_id']);

            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            $recibos_model = new RecibosAnterioresModel;
            $recibos_model->setId($id_recibo);
            $recibos_model->setEstatus($estatus);

            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $response = $recibos_model->cancelarRecibo($recibos_model, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {

                $tag_model = new TagsModel;
                $tag_model->desactivarTag($residente_id, $usuario_id_register);

                /*-------------------------------------------
                [ Actualizar Estatus de Tag de Residente ]*/
                $arrResponse = array(
                    'respuesta' => 'ok',
                    'mostrar_mensaje' => true,
                    'tiempo' => 3000,
                    'mensaje' => 'Registro Cancelado exitosamente.'
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error',
                    'mostrar_mensaje' => true,
                    'tiempo' => 3000,
                    'mensaje' => 'Error al cancelar el recibo, intente nuevamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 3000,
                'mensaje' => 'Error al cancelar el recibo, intente nuevamente.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de Recibos Anteriores para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getRecibosAnteriores($residente_id)
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO_PASADOS];
            if (!$this->permisosMod['r']) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeIn";

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $residente_id = intval(strClean($residente_id));
            $recibos_model = new RecibosAnterioresModel;
            $arrData = $recibos_model->selectRecibos($residente_id);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                // { "data": "folio" },
                // { "data": "created_at" },
                // { "data": "concepto" },
                // { "data": "folio_anterior" },
                // { "data": "archivo" },
                // { "data": "estatus" },
                // { "data": "options" }

                //Formato Estandar de datos
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
                $btnView = '';
                $btnReimprimir = '';
                $btnDelete = '';

                if ($this->permisosMod['r']) {

                    $btnView = '<button style="box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-info d-flex justify-content-center align-items-center view_htas_roles" data-animation="' . $data_animation . '" onclick="fntViewRecibo(this)" data-id="' . $arrData[$i]['id'] . '" title= "Ver Detalle de Comprobante">
                                        <i class="fa-regular fa-eye fs-14"></i>
                                    </button>';

                    if ($arrData[$i]['estatus'] == 0) {
                        $btnReimprimir = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center" onclick="fntReimprimirRecibo(this)" data-id="' . $arrData[$i]['id'] . '" title= "Reimprimir Comprobante">
                                                <i class="fa-regular fa-file-pdf fs-14"></i>
                                        </button>';
                    }
                }

                if ($this->permisosMod['d']) {
                    if ($arrData[$i]['estatus'] == 0) {
                        $btnDelete = '<a href="' . base_url() . '/Recibos/cancelarRecibo/' . $arrData[$i]['id'] . '" style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center" data-id="' . $arrData[$i]['id'] . '" title= "Cancelar Comprobante">
                                            <i class="fa-regular fa-trash-can fs-14"></i>
                                       </a>';
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
                $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex justify-content-center align-items-center">' . $btnView . ' ' . $btnReimprimir  . ' ' . $btnDelete . '</div>';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene los datos de Recibo Anterior seleccionado.
     * 
     * @param int $recibo_id Id de Recibo Anterior
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
    public function getReciboAnterior(int $recibo_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO_PASADOS];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $recibo_id = intval(strClean($recibo_id));

            if ($recibo_id > 0) {

                $recibos_model = new RecibosAnterioresModel;
                /*-------------------------------------------
                [ Obtiene array con los datos del Usuario ]*/
                $arrData = $recibos_model->selectRecibo($recibo_id);

                if (empty($arrData)) {
                    $arrRespuesta = array(
                        'respuesta' => 'error',
                        'mostrar_mensaje' => true,
                        'tiempo' => 6000,
                        'mensaje' => 'Datos no encontrados'
                    );
                } else {

                    $arrRespuesta = array(
                        'respuesta' => 'ok',
                        'mostrar_mensaje' => true,
                        'tiempo' => 6000,
                        'mensaje' => 'Datos encontrados',
                        'data' => $arrData
                    );
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrRespuesta = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 6000,
                'mensaje' => 'Datos no encontrados'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Genera Recibo Anterior de Cobro en formato pdf.
     * 
     * @param string $recibo_id
     * Identificador de Recibo correspondiente
     * 
     */
    public function generarComprobanteAnterior(int $recibo_id)
    {

        /*-------------------------------------------
        [ Validación de Permisos ]*/
        // $arrPermisos = getPermisosGlobal();
        // $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO_PASADOS];
        // if (!$this->permisosMod['r']) {
        //     die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
        // }

        $recibos_model = new RecibosAnterioresModel;
        $arrData = $recibos_model->selectRecibo($recibo_id);

        if (count($arrData) > 0) {

            $importe = floatval($arrData['importe']);
            $formatter = new NumeroALetras();
            // $numero_letra = $formatter->toWords($importe, 2);
            $formatter->conector = 'PESOS';
            $numero_letra = $formatter->toInvoice($importe, 2, '');
            $arrData['importe_letra'] = $numero_letra;

            $html = getFile("Template/Pdf/comprobanteAnteriorPDF",  $arrData);

            // (izquierda, superior, derecha, inferior)
            $margen_recibo = array(10, 5, 10, 10);

            $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8',  $margen_recibo);
            $html2pdf->pdf->SetDisplayMode('fullpage');

            $html2pdf->writeHTML($html, true, false, true, false, '');
            $html2pdf->output('ComprobantePago.pdf');
        }
    }
}
