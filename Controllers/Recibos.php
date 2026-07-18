<?php

require 'Libraries/html2pdf/vendor/autoload.php';
require 'Libraries/numlet/vendor/autoload.php';
require 'Libraries/phpqrcode/qrlib.php';

use Spipu\Html2Pdf\Html2Pdf;
use Luecano\NumeroALetras\NumeroALetras;


/**
 * Controlador Recibos 
 */
class Recibos extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Recibos.
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
     * Carga la Vista Recibos. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Recibos(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_RECIBOS_COBRO;

            //Header
            $data['page_title'] = "Recibos de Cobro";
            $data['page_description'] = "Recibos de Cobro";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-money-check-dollar-pen fa-fw text-primary text-shadow-info'></i> Recibos de Cobro";

            //Breadcrump
            $data['page_breadcrumb'] = "Recibos Cobro";

            //Card Principal
            $data['page_card_title'] = "Registro de Recibos de Cobro";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Registro y Control de Recibos de Pago de Mantenimiento";

            //JS Principal
            $data['page_functions_js'] = "functions_recibos.js";

            //Call Vista
            $this->views->getView($this, "recibos", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Guardar datos de Recibo de Cobro
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setRecibo()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
            if (!$this->permisosMod['c']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            $recibo_model = new RecibosModel;

            // /*-------------------------------------------
            // [ Se reciben Datos del POST con FormData ]*/
            $arrPagosAdelantados = array();
            $arrPagosAdeudos = array();
            if (isset($_POST['mes_pago_adelantados'])) {
                $arrPagosAdelantados = $_POST['mes_pago_adelantados'];
            }
            if (isset($_POST['mes_pago_adeudos'])) {
                $arrPagosAdeudos = $_POST['mes_pago_adeudos'];
            }

            if (isset($_POST['saldo_disponible_usar'])) {
                $usar_saldo = $_POST['saldo_disponible_usar'];
                $saldo_a_cuenta = $_POST['recibo_saldo_a_cuenta_disponible'];
                $saldo_utilizado = $saldo_a_cuenta;
            } else {
                $usar_saldo = "off";
                $saldo_a_cuenta = 0;
                $saldo_utilizado = 0;
            }

            $recibo_id = strclean($_POST['inputIdRecibo']);
            $cambio = strclean($_POST['recibo_cambio']);
            $cantidad = strclean($_POST['recibo_cantidad']);
            $concepto = strclean($_POST['recibo_concepto']);
            $dejaacuenta = strclean($_POST['recibo_dejaacuenta']);
            $descuento = strclean($_POST['recibo_descuento']);
            $monto = strclean($_POST['recibo_importe']);
            $recibe = strclean($_POST['recibo_recibe']);
            $subtotal = strclean($_POST['recibo_subtotal']);

            $residente_id = intval(strclean($_POST['recibo_residente_id']));
            $residente_model = new ResidentesModel;
            $residente = $residente_model->selectResidente($residente_id);
            $nombre = $residente['nombre'];
            $email = $residente['email'];
            if ($residente['nombre'] == null || $residente['nombre'] == '') {
                $nombre = "-";
            }
            $calle = $residente['calle'];
            $numero = $residente['numero'];

            // Valida que los meses hayan sido cargados conforme a el id del residente.
            if (isset($_POST['mes_pago_adeudos'])) {
                for ($i = 0; $i < count($arrPagosAdeudos); $i++) {
                    $cuenta_id_valid = $arrPagosAdeudos[$i];
                    $resp_error = '';
                    $respose_valid = $recibo_model->validaResidenteCuentas($cuenta_id_valid, $residente_id, $resp_error);
                    if (!$respose_valid) {
                        die(json_encode(getResponse($resp_error, "error", true, 6000), JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            $recibo_model->setResidente_id($residente_id);
            $recibo_model->setResidente($nombre);
            $recibo_model->setCalle($calle);
            $recibo_model->setNumero($numero);
            $recibo_model->setImporte($monto);
            $recibo_model->setConcepto($concepto);
            $recibo_model->setSubtotal($subtotal);

            $recibo_model->setCambio($cambio);
            $recibo_model->setCantidad($cantidad);
            $recibo_model->setDeja_cuenta($dejaacuenta);
            $recibo_model->setPorc_descuento($descuento);
            $recibo_model->setRecibe($recibe);
            $recibo_model->setConcepto_id(2);

            $recibo_model->setPagos_adelantados($arrPagosAdelantados);
            $recibo_model->setPagos_adeudos($arrPagosAdeudos);


            $recibo_model->setUsar_saldo($usar_saldo);
            $recibo_model->setSaldo_a_cuenta($saldo_a_cuenta);
            $recibo_model->setSaldo_utilizado($saldo_utilizado);

            //Obtener el nuevo nuemro de folio a asignar al recibo de cobro nuevo
            $folio_recibo = $recibo_model->getNewFolioCobro();
            if ($folio_recibo == "") {
                die(json_encode(getResponse('Error al realizar el registro, intente nuevamente.'), JSON_UNESCAPED_UNICODE));
            }
            $recibo_model->setFolio($folio_recibo);


            // /*-------------------------------------------
            // [ Se asigan variables de Sesion. ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            // /*-------------------------------------------
            // [ Actualizar Registro de Unidad Medica si pasa las validaciones. ]*/
            $response = $recibo_model->insertRecibo($recibo_model, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {

                /*-------------------------------------------
                [ Activar Tags  ]*/
                $tag_model = new TagsModel;
                $tag_model->activarTag($residente_id, $usuario_id_register);

                $recibo = $recibo_model->selectRecibo($recibo_model->getId());
                $this->sendComprobante($recibo);

                $arrResponse = getResponse('Registro realizado exitosamente', 'ok', false);
                $arrResponse['recibo'] = $recibo;


                //Activar Portal en caso de estar estatus activo el residente
                if (trim($email) != '') {
                    $cuenta_model = new CuentasModel;

                    $estatus_cuenta_mes_corriente = $cuenta_model->getEstatusCuentaMesCorriente($residente_id);

                    if ($estatus_cuenta_mes_corriente == 1) {

                        $this->setUsuarioPortalFromRecibo($residente_id, $email);
                    }
                }
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
     * Obtiene la lista de Recibos para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getRecibos($residente_id)
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
            if (!$this->permisosMod['r']) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeIn";

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $residente_id = intval(strClean($residente_id));
            $recibos_model = new RecibosModel;
            $arrData = $recibos_model->selectRecibos($residente_id);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

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

                $arrData[$i]['created_at'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . formatDateTime($arrData[$i]['created_at']) .
                    '</div>';

                //Formato Opciones
                $btnView = '';
                $btnReimprimir = '';
                $btnDelete = '';
                $btnSend = '';

                if ($this->permisosMod['r']) {

                    $btnView = '<button style="box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-info d-flex justify-content-center align-items-center view_htas_roles" data-animation="' . $data_animation . '" onclick="fntViewRecibo(this)" data-id="' . $arrData[$i]['id'] . '" title= "Ver Detalle de Registro">
                                        <i class="fa-regular fa-eye fs-14"></i>
                                    </button>';

                    if ($arrData[$i]['estatus'] == 0) {

                        if ($this->permisosMod['u']) {
                            $btnReimprimir = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center" onclick="fntReimprimirRecibo(this)" data-id="' . $arrData[$i]['id'] . '" title= "Reimprimir Recibo">
                                                    <i class="fa-regular fa-file-pdf fs-14"></i>
                                            </button>';

                            $btnSend = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-success d-flex justify-content-center align-items-center" onclick="fntEnviarReciboEmail(this)" data-id="' . $arrData[$i]['id'] . '" title= "Reenviar Recibo por Email">
                                            <i class="fa-regular fa-paper-plane fs-14"></i>
                                    </button>';
                        }
                    }
                }
                // <i class="fa-light fa-paper-plane"></i>



                if ($this->permisosMod['d']) {
                    if ($arrData[$i]['estatus'] == 0) {
                        $btnDelete = '<a  href="' . base_url() . '/Recibos/cancelarRecibo/' . $arrData[$i]['id'] . '" style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center" data-id="' . $arrData[$i]['id'] . '" title= "Cancelar Recibo">
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
                $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex justify-content-center align-items-center">' . $btnView . ' ' . $btnReimprimir  . ' ' . $btnSend  . ' ' . $btnDelete . '</div>';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene los datos de Recibo seleccionado.
     * 
     * @param int $recibo_id Id de Recibo
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
    public function getRecibo(int $recibo_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }


            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $recibo_id = intval(strClean($recibo_id));

            if ($recibo_id > 0) {

                $recibos_model = new RecibosModel;
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
                        'mostrar_mensaje' => false,
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
     * Obtiene los datos de Recibo seleccionado. en array
     * 
     * @param int $recibo_id Id de Recibo
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
    public function getReciboData(int $recibo_id): array
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }


            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $recibo_id = intval(strClean($recibo_id));

            if ($recibo_id > 0) {

                $recibos_model = new RecibosModel;
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
                        'mostrar_mensaje' => false,
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
        return $arrRespuesta;
    }

    /**
     * Genera Recibo de Cobro en formato pdf.
     * 
     * @param string $recibo_id
     * Identificador de Recibo correspondiente
     * 
     */
    public function generarComprobante(int $recibo_id)
    {

        /*-------------------------------------------
        [ Validación de Permisos ]*/
        // $arrPermisos = getPermisosGlobal();
        // $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
        // if (!$this->permisosMod['r']) {
        //     die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
        // }

        $recibos_model = new RecibosModel;
        $arrData = $recibos_model->selectRecibo($recibo_id);

        if (count($arrData) > 0) {

            $ruta_validacion = base_url() . '/recibosValida/valida/' . $recibo_id;
            $this->generarQRRecibo($ruta_validacion);

            $importe = floatval($arrData['importe']);
            $formatter = new NumeroALetras();
            // $numero_letra = $formatter->toWords($importe, 2);
            $formatter->conector = 'PESOS';
            $numero_letra = $formatter->toInvoice($importe, 2, '');
            $arrData['importe_letra'] = $numero_letra;

            if ($arrData['tipo'] == 1) {
                $html = getFile("Template/Pdf/comprobantePDF",  $arrData);
            } else {
                $html = getFile("Template/Pdf/comprobanteAnteriorPDF",  $arrData);
            }
            // (izquierda, superior, derecha, inferior)
            $margen_recibo = array(10, 5, 10, 10);

            $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8',  $margen_recibo);
            $html2pdf->pdf->SetDisplayMode('fullpage');

            $html2pdf->writeHTML($html, true, false, true, false, '');
            $html2pdf->output('Comprobante.pdf');
        }
    }

    /**
     * Genera Recibo de Cobro en formato pdf y enviar por email
     * 
     * @param string $recibo_id
     * Identificador de Recibo correspondiente
     * 
     */
    private function sendComprobante($arrData): void
    {

        if (count($arrData) > 0) {

            $respuesta = $this->generarPDF($arrData);

            if ($respuesta == true) {

                /*-------------------------------------------
                [ Enviar Correo de Bienvenida  ]*/
                $residente_model = new ResidentesModel;
                $residente = $residente_model->selectResidente($arrData['residente_id']);

                $datos_residente = array(
                    'attachment' => 'Assets/files/temp/comprobante.pdf',
                    'calle' => $residente['calle'],
                    'numero' => $residente['numero'],
                    'email' => $residente['email'],
                    'nombre_usuario' => $residente['nombre'],
                    'folio' => $arrData['folio'],
                    'concepto' => $arrData['concepto'],
                    'asunto' => 'Envío de Comprobante de Pago Fracc. Amores de Don Juan',
                );
                sendEmailPHPMailer($datos_residente, 'email_recibo');
            }
        }
    }

    /**
     * Genera Recibo de Cobro en formato pdf y enviar por email
     * 
     * @param string $recibo_id
     * Identificador de Recibo correspondiente
     * 
     */
    public function enviarComprobante(int $recibo_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            $recibos_model = new RecibosModel;
            $arrData = $recibos_model->selectRecibo($recibo_id);

            if (count($arrData) > 0) {

                $respuesta = $this->generarPDF($arrData);

                if ($respuesta == true) {

                    /*-------------------------------------------
                    [ Enviar Correo de Bienvenida  ]*/
                    $residente_model = new ResidentesModel;
                    $residente = $residente_model->selectResidente($arrData['residente_id']);

                    $datos_residente = array(
                        'attachment' => 'Assets/files/temp/comprobante.pdf',
                        'calle' => $residente['calle'],
                        'numero' => $residente['numero'],
                        'email' => $residente['email'],
                        'nombre_usuario' => $residente['nombre'],
                        'folio' => $arrData['folio'],
                        'concepto' => $arrData['concepto'],
                        'asunto' => 'Envío de Comprobante de Pago Fracc. Amores de Don Juan',
                    );
                    $sendEmail = sendEmailPHPMailer($datos_residente, 'email_recibo');
                    if (!$sendEmail) {
                        die(json_encode(getResponse('Error al enviar el correo electrónico, intente nuevamente.'), JSON_UNESCAPED_UNICODE));
                    }

                    $arrRespuesta = getResponse('Correo enviado exitosamente', 'ok', true);
                } else {
                    die(json_encode(getResponse('Error al enviar el correo electrónico, intente nuevamente.'), JSON_UNESCAPED_UNICODE));
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('Error al enviar el correo electrónico, intente nuevamente.'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }

    private function generarPDF($arrData): bool
    {

        $response = true;

        $ruta_validacion = base_url() . '/recibosValida/valida/' . $arrData['id'];
        $this->generarQRRecibo($ruta_validacion);

        $importe = floatval($arrData['importe']);
        $formatter = new NumeroALetras();
        // $numero_letra = $formatter->toWords($importe, 2);
        $formatter->conector = 'PESOS';
        $numero_letra = $formatter->toInvoice($importe, 2, '');
        $arrData['importe_letra'] = $numero_letra;

        if ($arrData['tipo'] == 1) {
            $html = getFile("Template/Pdf/comprobantePDF",  $arrData);
        } else {
            $html = getFile("Template/Pdf/comprobanteAnteriorPDF",  $arrData);
        }
        // (izquierda, superior, derecha, inferior)
        $margen_recibo = array(10, 5, 10, 10);

        $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8',  $margen_recibo);
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($html, true, false, true, false, '');
        $output_file =  __DIR__ . 'Assets/files/temp/comprobante.pdf';
        $output_file = str_replace('Controllers', '', $output_file);
        $html2pdf->output($output_file, 'F');

        $response = true;

        return $response;
    }


    //*==================================================================
    // [ Recibos de Cobro - Portal de Residente ]*/

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
    public function getEstatusResidentePortal(int $residente_id)
    {

        try {


            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $unidad_medica_id = $this->session->get('unidad_medica_id');
            $usuario_id_register = $this->session->get('usuario_id');


            $arrData = array();

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

            $usuario_model = new UsuariosModel();
            $arrUsuario = $usuario_model->selectUsuarioFromResidenteId($residente_id, $unidad_medica_id);
            if (count($arrUsuario) == 0) {
                $arrData['portal_estatus'] = 0;
            } else {
                if ($arrUsuario['activo'] == 0) {
                    $arrData['portal_estatus'] = 0;
                } else {
                    $arrData['portal_estatus'] = 1;
                }
            }

            $arrRespuesta = array(
                'respuesta' => 'ok',
                'mostrar_mensaje' => false,
                'tiempo' => 6000,
                'mensaje' => 'Datos encontrados',
                'data' => $arrData
            );
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
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
     * Activar Portal de Residente
     * 
     * @return string json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     * $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     */
    public function setUsuarioPortal()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
            if (!$this->permisosMod['c']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $unidad_medica_id = $this->session->get('unidad_medica_id');
            $usuario_id_register = $this->session->get('usuario_id');
            $pais_id = $this->session->get('pais_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $residente_id = intval($_POST['residente_id']);
            $res_email = $_POST['res_email'];

            /*-------------------------------------------
            [ Validar Datos ]*/
            if ($residente_id == 0) {
                die(json_encode(getResponse('Debe seleccionar un residente.'), JSON_UNESCAPED_UNICODE));
            }
            if (trim($res_email) == '') {
                die(json_encode(getResponse('Debe indicar email.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Password ]*/
            $password_sys = passGenerator();
            $password_encrypt = hash("SHA256", $password_sys);

            // -- Datos de Usuario --
            $user_sys = $res_email;


            $activo = 1;
            $tipo_usuario_id = intval(4);
            $theme = "transparent-mode";
            $origen_id = 9;


            $residente_model = new ResidentesModel;
            $arrResidente = $residente_model->selectResidente($residente_id);

            // -- Datos Generales --
            $nombre = ucwords($arrResidente['nombre']);
            $paterno = ucwords($arrResidente['calle']);
            $materno = ucwords($arrResidente['numero']);
            $email = strtolower($res_email);
            $telefono = $arrResidente['telefono'];
            $sexo_id = '3';
            $rol_id = '32';

            // -- Datos Relación Usuarios Unidades Médicas --
            $titular = 0;

            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            // -- Datos de Usuario --
            $usuario_model = new UsuariosModel;
            $arrUsuario = $usuario_model->selectUsuarioFromResidenteId($residente_id, $unidad_medica_id);
            if (count($arrUsuario) == 0) {
                $id_usuario = 0;
            } else {
                $id_usuario = $arrUsuario['id'];
            }

            $usuario_model->setId($id_usuario);
            $usuario_model->setUsuario($user_sys);
            $usuario_model->setActivo($activo);
            $usuario_model->setTipo_usuario_id($tipo_usuario_id);
            $usuario_model->setTheme($theme);
            $usuario_model->setOrigen_id($origen_id);
            $usuario_model->setPass($password_encrypt);


            // -- Datos Generales --
            $usuario_model->setNombre($nombre);
            $usuario_model->setPaterno($paterno);
            $usuario_model->setMaterno($materno);
            $usuario_model->setEmail($email);
            $usuario_model->setTelefono($telefono);
            $usuario_model->setSexo_id($sexo_id);
            $usuario_model->setPais_id($pais_id);
            $usuario_model->setResidente_id($residente_id);

            // -- Datos Relación Usuarios Unidades Médicas --
            $usuario_model->setRol_id($rol_id);
            $usuario_model->setTitular($titular);


            if ($id_usuario == 0) {

                /*===========================================
                [ Crear Nuevo Registro ]*/

                /*-------------------------------------------
                [ Valida si el Usuario ya existe ]*/
                $existe = $usuario_model->validInsertExistUsuario($user_sys);
                if ($existe == true) {
                    die(json_encode(getResponse('CodeError: portal_1001. El correo ya ha sido registrado.'), JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Inserta el Registro si pasa las validaciones. ]*/
                $response = $usuario_model->insertUsuario($usuario_model, $unidad_medica_id, $usuario_id_register);
                if ($response == false) {
                    die(json_encode(getResponse('CodeError: portal_1002. Error al crear el usuario para el Portal'), JSON_UNESCAPED_UNICODE));
                }
            } else {

                /*-------------------------------------------
                [ Actualiza el estatus del Portal del residente ]*/
                $estatus = 1;
                $response = $usuario_model->updateEstatusPortalResidente($id_usuario, $estatus, $unidad_medica_id, $usuario_id_register);
            }

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {

                $arrResponse = getResponse('Portal Activado exitosamente', 'ok');
                $arrResponse['usuario'] = $email;

                if ($id_usuario == 0) {
                    /*-------------------------------------------
                    [ Enviar Correo de Bienvenida  ]*/
                    $nombre_usuario = $nombre . ' ' .  $paterno . ' ' . $materno;
                    $datos_usuario = array(
                        'nombre_usuario' => $nombre_usuario,
                        'email' => $email,
                        'password' => $password_sys,
                        'asunto' => 'Acceso al Portal de Residentes Fracc. Amores de Don Juan',
                    );
                    $sendEmail = sendEmailPHPMailer($datos_usuario, 'email_bienvenida_residente');
                    if (!$sendEmail) {
                        getLoggerSystem()->error('No se envió correo de bienvenida', $datos_usuario);
                    }
                    /*------------------------------------------- */
                }
            } else {
                die(json_encode(getResponse('CodeError: portal_1004. Error al crear el usuario para el Portal'), JSON_UNESCAPED_UNICODE));
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('CodeError: portal_1005. Error al crear el usuario para el Portal'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Descativar acceso a portal del residente
     * 
     * @return string json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     * $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     */
    public function estatusPortalResidente()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
            if (!$this->permisosMod['c']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $unidad_medica_id = $this->session->get('unidad_medica_id');
            $usuario_id_register = $this->session->get('usuario_id');


            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $residente_id = $_POST['residente_id'];
            $estatus = $_POST['estatus'];

            /*-------------------------------------------
            [ Validar Datos ]*/
            if (trim($residente_id) == '') {
                die(json_encode(getResponse('Debe seleccionar un residente.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Obtenemos el id de usuario para actulizar el registro ]*/
            $usuario_model = new UsuariosModel;
            $arrUsuario = $usuario_model->selectUsuarioFromResidenteId($residente_id, $unidad_medica_id);
            if (count($arrUsuario) == 0) {
                $id_usuario = 0;
                $arrResponse = getResponse('Portal Inactivo Actualmente', 'ok');
                die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
            } else {
                $id_usuario = $arrUsuario['id'];
            }


            /*-------------------------------------------
            [ Actualiza el estatus del Portal del residente ]*/
            $response = $usuario_model->updateEstatusPortalResidente($id_usuario, $estatus, $unidad_medica_id, $usuario_id_register);


            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = getResponse('Portal Desactivado exitosamente', 'ok');
            } else {
                die(json_encode(getResponse('CodeError: portal_des_1001. Error al crear el usuario para el Portal'), JSON_UNESCAPED_UNICODE));
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('CodeError: portal_des_1002. Error al crear el usuario para el Portal'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Activar Portal de Residente
     * 
     */
    private function setUsuarioPortalFromRecibo(int $residente_id, string $res_email): void
    {

        try {


            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $unidad_medica_id = $this->session->get('unidad_medica_id');
            $usuario_id_register = $this->session->get('usuario_id');
            $pais_id = 136;


            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            // -- Datos de Usuario --
            $usuario_model = new UsuariosModel;
            $arrUsuario = $usuario_model->selectUsuarioFromResidenteId($residente_id, $unidad_medica_id);
            if (count($arrUsuario) == 0) {


                /*-------------------------------------------
                [ Password ]*/
                $password_sys = passGenerator();
                $password_encrypt = hash("SHA256", $password_sys);

                // -- Datos de Usuario --
                $user_sys = $res_email;


                $activo = 1;
                $tipo_usuario_id = intval(4);
                $theme = "transparent-mode";
                $origen_id = 9;


                $residente_model = new ResidentesModel;
                $arrResidente = $residente_model->selectResidente($residente_id);

                // -- Datos Generales --
                $nombre = ucwords($arrResidente['nombre']);
                $paterno = ucwords($arrResidente['calle']);
                $materno = ucwords($arrResidente['numero']);
                $email = strtolower($res_email);
                $telefono = $arrResidente['telefono'];
                $sexo_id = '3';
                $rol_id = '32';

                // -- Datos Relación Usuarios Unidades Médicas --
                $titular = 0;


                $id_usuario = 0;

                $usuario_model->setId($id_usuario);
                $usuario_model->setUsuario($user_sys);
                $usuario_model->setActivo($activo);
                $usuario_model->setTipo_usuario_id($tipo_usuario_id);
                $usuario_model->setTheme($theme);
                $usuario_model->setOrigen_id($origen_id);
                $usuario_model->setPass($password_encrypt);


                // -- Datos Generales --
                $usuario_model->setNombre($nombre);
                $usuario_model->setPaterno($paterno);
                $usuario_model->setMaterno($materno);
                $usuario_model->setEmail($email);
                $usuario_model->setTelefono($telefono);
                $usuario_model->setSexo_id($sexo_id);
                $usuario_model->setPais_id($pais_id);
                $usuario_model->setResidente_id($residente_id);

                // -- Datos Relación Usuarios Unidades Médicas --
                $usuario_model->setRol_id($rol_id);
                $usuario_model->setTitular($titular);


                /*===========================================
                    [ Crear Nuevo Registro ]*/

                /*-------------------------------------------
                [ Valida si el Usuario ya existe ]*/
                $existe = $usuario_model->validInsertExistUsuario($user_sys);
                if ($existe == false) {
                    /*-------------------------------------------
                    [ Inserta el Registro si pasa las validaciones. ]*/
                    $response = $usuario_model->insertUsuario($usuario_model, $unidad_medica_id, $usuario_id_register);
                }

                /*-------------------------------------------
                [ Evalúa respuesta  ]*/
                if ($response == true) {

                    /*-------------------------------------------
                    [ Enviar Correo de Bienvenida  ]*/
                    $nombre_usuario = $nombre . ' ' .  $paterno . ' ' . $materno;
                    $datos_usuario = array(
                        'nombre_usuario' => $nombre_usuario,
                        'email' => $email,
                        'password' => $password_sys,
                        'asunto' => 'Acceso al Portal de Residentes Fracc. Amores de Don Juan',
                    );
                    $sendEmail = sendEmailPHPMailer($datos_usuario, 'email_bienvenida_residente');
                    if (!$sendEmail) {
                        getLoggerSystem()->error('No se envió correo de bienvenida', $datos_usuario);
                    }
                    /*------------------------------------------- */
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Restablecer Portal de Residente
     * 
     * @return string json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     * $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     */
    public function resetUsuarioPortal()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
            if (!$this->permisosMod['c']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $unidad_medica_id = $this->session->get('unidad_medica_id');
            $usuario_id_register = $this->session->get('usuario_id');
            $pais_id = 136;

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $residente_id = intval(strClean($_POST['residente_id']));
            $res_email = strClean($_POST['res_email']);

            /*-------------------------------------------
            [ Validar Datos ]*/
            if ($residente_id == 0) {
                die(json_encode(getResponse('Debe seleccionar un residente.'), JSON_UNESCAPED_UNICODE));
            }
            if (trim($res_email) == '') {
                die(json_encode(getResponse('Debe indicar email.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Password ]*/
            $password_sys = passGenerator();
            $password_encrypt = hash("SHA256", $password_sys);

            // -- Datos de Usuario --
            $user_sys = $res_email;


            $activo = 1;
            $tipo_usuario_id = intval(4);
            $theme = "transparent-mode";
            $origen_id = 9;


            $residente_model = new ResidentesModel;
            $arrResidente = $residente_model->selectResidente($residente_id);

            // -- Datos Generales --
            $nombre = ucwords($arrResidente['nombre']);
            $paterno = ucwords($arrResidente['calle']);
            $materno = ucwords($arrResidente['numero']);
            $email = strtolower($res_email);
            $telefono = $arrResidente['telefono'];
            $sexo_id = '3';
            $rol_id = '32';

            // -- Datos Relación Usuarios Unidades Médicas --
            $titular = 0;

            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            // -- Datos de Usuario --
            $usuario_model = new UsuariosModel;
            $arrUsuario = $usuario_model->selectUsuarioFromResidenteId($residente_id, $unidad_medica_id);
            if (count($arrUsuario) == 0) {
                $id_usuario = 0;
            } else {
                $id_usuario = $arrUsuario['id'];
            }

            $usuario_model->setId($id_usuario);
            $usuario_model->setUsuario($user_sys);
            $usuario_model->setActivo($activo);
            $usuario_model->setTipo_usuario_id($tipo_usuario_id);
            $usuario_model->setTheme($theme);
            $usuario_model->setOrigen_id($origen_id);
            $usuario_model->setPass($password_encrypt);


            // -- Datos Generales --
            $usuario_model->setNombre($nombre);
            $usuario_model->setPaterno($paterno);
            $usuario_model->setMaterno($materno);
            $usuario_model->setEmail($email);
            $usuario_model->setTelefono($telefono);
            $usuario_model->setSexo_id($sexo_id);
            $usuario_model->setPais_id($pais_id);
            $usuario_model->setResidente_id($residente_id);

            // -- Datos Relación Usuarios Unidades Médicas --
            $usuario_model->setRol_id($rol_id);
            $usuario_model->setTitular($titular);


            if ($id_usuario == 0) {

                /*===========================================
                [ Crear Nuevo Registro ]*/

                /*-------------------------------------------
                [ Valida si el Usuario ya existe ]*/
                $existe = $usuario_model->validInsertExistUsuario($user_sys);
                if ($existe == true) {
                    die(json_encode(getResponse('CodeError: portal_1001. El correo ya ha sido registrado.'), JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Inserta el Registro si pasa las validaciones. ]*/
                $response = $usuario_model->insertUsuario($usuario_model, $unidad_medica_id, $usuario_id_register);
                if ($response == false) {
                    die(json_encode(getResponse('CodeError: portal_1002. Error al crear el usuario para el Portal'), JSON_UNESCAPED_UNICODE));
                }
            } else {

                /*===========================================
                [ Actualizar Registro ]*/

                /*-------------------------------------------
                [ Valida si el Usuario ya existe ]*/
                $existe = $usuario_model->validUpdateExistUsuario($user_sys, $id_usuario);
                if ($existe == true) {
                    die(json_encode(getResponse('CodeError: portal_1003. El correo ya ha sido registrado'), JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Actualizar Registro ]*/
                $reset_usuario_portal = true;
                $response = $usuario_model->updateUsuario($usuario_model, $unidad_medica_id, $usuario_id_register, $reset_usuario_portal);
                if ($response == false) {
                    die(json_encode(getResponse('CodeError: portal_1003a. Error al crear el usuario para el Portal'), JSON_UNESCAPED_UNICODE));
                }
            }

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {

                $arrResponse = getResponse('Portal Regenerado exitosamente. Indique al residente que verifique sus credenciales de acceso enviadas al correo registrado: --' . $email . '--', 'ok', true, 8000);
                $arrResponse['usuario'] = $email;

                /*-------------------------------------------
                [ Enviar Correo de Bienvenida  ]*/
                $nombre_usuario = $nombre . ' ' .  $paterno . ' ' . $materno;
                $datos_usuario = array(
                    'nombre_usuario' => $nombre_usuario,
                    'email' => $email,
                    'password' => $password_sys,
                    'asunto' => 'Acceso al Portal de Residentes Fracc. Amores de Don Juan',
                );
                $sendEmail = sendEmailPHPMailer($datos_usuario, 'email_bienvenida_residente');
                if (!$sendEmail) {
                    getLoggerSystem()->error('No se envió correo de bienvenida', $datos_usuario);
                }
                /*------------------------------------------- */
            } else {
                die(json_encode(getResponse('CodeError: portal_1004. Error al crear el usuario para el Portal'), JSON_UNESCAPED_UNICODE));
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('CodeError: portal_1005. Error al crear el usuario para el Portal'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    //*==================================================================
    // [ Transferir Recibo ]*/

    /**
     * Carga la Vista Recibos Otros Conceptos
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function transferirRecibo(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_TRANSFERIR_RECIBO];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_TRANSFERIR_RECIBO;

            //Header
            $data['page_title'] = "Transferir Recibo";
            $data['page_description'] = "Transferir Recibo";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-arrow-right-arrow-left fa-fw text-primary text-shadow-info'></i> Transferir Recibo";

            //Breadcrump
            $data['page_breadcrumb'] = "Transferir Recibo";

            //Card Principal
            $data['page_card_title'] = "Transferir Recibo";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo para transferir un recibo que haya sido cobrado a orto domicilio.";

            //JS Principal
            $data['page_functions_js'] = "recibos_transferir.js";

            //Call Vista
            $this->views->getView($this, "transferirRecibo", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Transferir Recibo
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setTransferirRecibo()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_TRANSFERIR_RECIBO];
            if (!$this->permisosMod['c']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register =  $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $folio_recibo_origen = strclean($_POST['recibo_origen']);
            $residente_destino_id = intval(strclean($_POST['residente_id']));


            /*-------------------------------------------
            [ Valida Datos ]*/
            if ($residente_destino_id == 0) {
                die(json_encode(getResponse('Debe seleccionar Residente al cual se transfiere el recibo'), JSON_UNESCAPED_UNICODE));
            }

            if ($folio_recibo_origen == '') {
                die(json_encode(getResponse('Debe indicar Recibo que desea transferir'), JSON_UNESCAPED_UNICODE));
            }

            $recibos_model = new RecibosModel;
            $arrRecibo = $recibos_model->selectReciboFromFolio($folio_recibo_origen);
            if (count($arrRecibo) == 0) {
                die(json_encode(getResponse('El Folio de Recibo no es valido, verifique'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Preparara arreglo para guardar datos de Transferencia ]*/
            $arrDatosTransferencia = array();
            $arrDatosTransferencia['recibo_id'] = $arrRecibo['id'];
            $arrDatosTransferencia['residente_id_origen'] = $arrRecibo['residente_id'];
            $arrDatosTransferencia['residente_id_destino'] = $residente_destino_id;

            $residente_model = new ResidentesModel;
            $arrResidenteDestino = $residente_model->selectResidente($residente_destino_id);

            $arrDatosTransferencia['residente_destino']['residente'] = $arrResidenteDestino['nombre'];
            $arrDatosTransferencia['residente_destino']['calle'] = $arrResidenteDestino['calle'];
            $arrDatosTransferencia['residente_destino']['numero'] = $arrResidenteDestino['numero'];
            $arrDatosTransferencia['residente_destino']['mza'] = $arrResidenteDestino['mza'];
            $arrDatosTransferencia['residente_destino']['lote'] = $arrResidenteDestino['lote'];


            /*-------------------------------------------
            [ Valida Mes y Año de Destino en Cuenta. ]*/
            $response = $recibos_model->validaDestinoTranferenciaRecibo($arrDatosTransferencia);
            if ($response == false) {
                die(json_encode(getResponse('No es posible transferir el recibo ya que el residente de destino tiene pagado el mes que desea transferir. Verifique el Residente de Destino e intente nuevamente.', 'error', true, 7000), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Transferir Recibo. ]*/
            $response = $recibos_model->updateTransferirRecibo($arrDatosTransferencia, $usuario_id_register);
            if ($response == false) {
                die(json_encode(getResponse('Error al transferir el recibo, intente nuevamente.'), JSON_UNESCAPED_UNICODE));
            }

            $arrResponse = getResponse('Recibo Transferido Exitosamente', 'ok', true);
            $arrResponse['data']['recibo_id'] = $arrDatosTransferencia['recibo_id'];
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('Code Error transfer_1001. Error desconocido. Intente nuevamente.'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }


      //*==================================================================
    // [ Cancelar Recibo ]*/

    /**
     * Carga la Vista para cancelación de Recibos.
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function cancelarRecibo($recibo_id): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['d']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            $data['recibo_id'] = intval(strClean($recibo_id));
            if (intval($data['recibo_id']) == 0) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            $recibos_model = new RecibosModel;
            $recibo = $recibos_model->selectRecibo($recibo_id);

            if (empty($recibo)) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            $data['recibo'] = $recibo;


            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = 0;

            //Header
            $data['page_title'] = "Cancelar Recibo";
            $data['page_description'] = "Cancelar Recibo";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-arrow-right-arrow-left fa-fw text-primary text-shadow-info'></i> Cancelar Recibo";

            //Breadcrump
            $data['page_breadcrumb'] = "Cancelar Recibo";

            //Card Principal
            $data['page_card_title'] = "Cancelar Recibo";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo para Cancelar un recibo que haya sido cobrado a orto domicilio.";

            //JS Principal
            $data['page_functions_js'] = "recibos_cancelar.js";

            //Call Vista
            $this->views->getView($this, "cancelarRecibo", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            echo "<h4>Lo sentimos, Acceso restringido</h4>";
            die();
        }
    }

    /**
     * Cancelar Recibo
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setCancelarRecibo()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO];
            if (!$this->permisosMod['d']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register =  $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $id_recibo = intval($_POST['recibo_id']);
            $estatus = 1;
            $residente_id = intval($_POST['residente_id']);
            $motivo_cancela = strClean($_POST['motivo_cancela']);

            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            $recibos_model = new RecibosModel;
            $recibos_model->setId($id_recibo);
            $recibos_model->setEstatus($estatus);
            $recibos_model->setMotivoCancela($motivo_cancela);
            $recibos_model->setUsuarioIdCancela($usuario_id_register);

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

    //*==================================================================
    // [ Recibos Otros Conceptos ]*/

    /**
     * Carga la Vista Recibos Otros Conceptos
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function RecibosOtrosConceptos(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO_OTROS_CONCEPTOS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_RECIBOS_COBRO_OTROS_CONCEPTOS;

            //Header
            $data['page_title'] = "Recibos de Cobro Otros Conceptos";
            $data['page_description'] = "Recibos de Otros Conceptos";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-money-check-dollar-pen fa-fw text-success text-shadow-success'></i> Recibos de Cobro Otros Conceptos";

            //Breadcrump
            $data['page_breadcrumb'] = "Recibos de Cobro Otros Conceptos";

            //Card Principal
            $data['page_card_title'] = "Registro de Recibos de Otros Conceptos";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Registro y Control de Recibos de Cobro de Otros Conceptos.";

            //JS Principal
            $data['page_functions_js'] = "functions_recibos_otros_conceptos.js";

            //Call Vista
            $this->views->getView($this, "recibos_otros_conceptos", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene la lista de Recibos de Otros Conceptos para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getRecibosOtrosConceptos($residente_id)
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO_OTROS_CONCEPTOS];
            if (!$this->permisosMod['r']) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeIn";

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $residente_id = intval(strClean($residente_id));
            $recibos_model = new RecibosModel;
            $arrData = $recibos_model->selectRecibos($residente_id);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

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

                $arrData[$i]['created_at'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . formatDateTime($arrData[$i]['created_at']) .
                    '</div>';

                //Formato Opciones
                $btnView = '';
                $btnReimprimir = '';
                $btnDelete = '';

                if ($this->permisosMod['r']) {

                    $btnView = '<button style="box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-info d-flex justify-content-center align-items-center view_htas_roles" data-animation="' . $data_animation . '" onclick="fntViewRecibo(this)" data-id="' . $arrData[$i]['id'] . '" title= "Ver Detalle de Registro">
                                        <i class="fa-regular fa-eye fs-14"></i>
                                    </button>';

                    if ($arrData[$i]['estatus'] == 0) {
                        $btnReimprimir = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center" onclick="fntReimprimirRecibo(this)" data-id="' . $arrData[$i]['id'] . '" title= "Reimprimir Recibo">
                                                <i class="fa-regular fa-file-pdf fs-14"></i>
                                        </button>';
                    }
                }

                if ($this->permisosMod['d']) {
                    if ($arrData[$i]['estatus'] == 0) {
                        $btnDelete = '<a href="' . base_url() . '/Recibos/cancelarRecibo/' . $arrData[$i]['id'] . '" style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center" data-id="' . $arrData[$i]['id'] . '" title= "Cancelar Recibo">
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
                if ($arrData[$i]['concepto_id'] == 2) {
                    $arrData[$i]['options'] = '';
                } else {
                    $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex justify-content-center align-items-center">' . $btnView . ' ' . $btnReimprimir  . ' ' . $btnDelete . '</div>';
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    // /**
    //  * Cancelar Recibo
    //  * 
    //  * @response $arrResponse, donde:
    //  * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
    //  * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
    //  * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
    //  * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
    //  * 
    //  * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
    //  */
    // public function setCancelarReciboOtrosConceptos()
    // {

    //     try {

    //         /*-------------------------------------------
    //         [ Validación de Permisos ]*/
    //         $arrPermisos = getPermisosGlobal();
    //         $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO_OTROS_CONCEPTOS];
    //         if (!$this->permisosMod['d']) {
    //             die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
    //         }

    //         /*-------------------------------------------
    //         [ Se aignan las variables de Sesión ]*/
    //         $usuario_id_register =  $this->session->get('usuario_id');

    //         /*-------------------------------------------
    //         [ Se reciben Datos del POST con FormData ]*/
    //         $id_recibo = intval($_POST['id']);
    //         $estatus = intval($_POST['estatus']);
    //         $residente_id = intval($_POST['residente_id']);

    //         /*-------------------------------------------
    //         [ Se aignan las variables al Modelo ]*/
    //         $recibos_model = new RecibosModel;
    //         $recibos_model->setId($id_recibo);
    //         $recibos_model->setEstatus($estatus);

    //         /*-------------------------------------------
    //         [ Elimina el Registro seleccionado. ]*/
    //         $response = $recibos_model->cancelarReciboOtrosConceptos($recibos_model, $usuario_id_register);

    //         /*-------------------------------------------
    //         [ Evalúa respuesta  ]*/
    //         if ($response == true) {

    //             $arrResponse = array(
    //                 'respuesta' => 'ok',
    //                 'mostrar_mensaje' => true,
    //                 'tiempo' => 3000,
    //                 'mensaje' => 'Registro Cancelado exitosamente.'
    //             );
    //         } else {
    //             $arrResponse = array(
    //                 'respuesta' => 'error',
    //                 'mostrar_mensaje' => true,
    //                 'tiempo' => 3000,
    //                 'mensaje' => 'Error al cancelar el recibo, intente nuevamente.'
    //             );
    //         }
    //     } catch (\Throwable $th) {
    //         getLoggerSystem()->error(getMensajeError($th));
    //         $arrResponse = array(
    //             'respuesta' => 'error',
    //             'mostrar_mensaje' => true,
    //             'tiempo' => 3000,
    //             'mensaje' => 'Error al cancelar el recibo, intente nuevamente.'
    //         );
    //     }

    //     /*-------------------------------------------
    //     [ Retorna respuesta json_encode ]*/
    //     die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    // }

    /**
     * Guardar datos de Recibo de Cobro Otros Conceptos
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setReciboOtrosConceptos()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_RECIBOS_COBRO_OTROS_CONCEPTOS];
            if (!$this->permisosMod['c']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            // /*-------------------------------------------
            // [ Se reciben Datos del POST con FormData ]*/
            $recibo_id = strclean($_POST['inputIdRecibo']);
            $cambio = strclean($_POST['recibo_cambio']);
            $cantidad = 1;
            $concepto = strclean($_POST['recibo_concepto']);
            $monto = strclean($_POST['recibo_importe']);
            $recibe = strclean($_POST['recibo_recibe']);
            $concepto_id = intval(strclean($_POST['comboClasificacionIngresos']));


            // /*-------------------------------------------
            // [ Valida concepto ]*/
            if ($concepto_id == 0) {
                die(json_encode(getResponse('Debe seleccionar una clasificación de ingreso.'), JSON_UNESCAPED_UNICODE));
            }

            $residente_id = intval(strclean($_POST['recibo_residente_id']));
            $residente_model = new ResidentesModel;
            $residente = $residente_model->selectResidente($residente_id);
            $nombre = $residente['nombre'];
            if ($residente['nombre'] == null || $residente['nombre'] == '') {
                $nombre = "-";
            }
            $calle = $residente['calle'];
            $numero = $residente['numero'];

            $recibo_model = new RecibosModel;
            $recibo_model->setResidente_id($residente_id);
            $recibo_model->setResidente($nombre);
            $recibo_model->setCalle($calle);
            $recibo_model->setNumero($numero);
            $recibo_model->setImporte($monto);
            $recibo_model->setConcepto($concepto);
            $recibo_model->setSubtotal($monto);

            $recibo_model->setCambio($cambio);
            $recibo_model->setCantidad($cantidad);
            $recibo_model->setRecibe($recibe);
            $recibo_model->setConcepto_id($concepto_id);

            //Obtener el nuevo nuemro de folio a asignar al recibo de cobro nuevo
            $folio_recibo = $recibo_model->getNewFolioCobro();
            if ($folio_recibo == "") {
                die(json_encode(getResponse('Error al realizar el registro, intente nuevamente.'), JSON_UNESCAPED_UNICODE));
            }
            $recibo_model->setFolio($folio_recibo);



            // /*-------------------------------------------
            // [ Se asigan variables de Sesion. ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            // /*-------------------------------------------
            // [ Actualizar Registro de Unidad Medica si pasa las validaciones. ]*/
            $response = $recibo_model->insertReciboOtrosConceptos($recibo_model, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {

                $recibo = $recibo_model->selectRecibo($recibo_model->getId());
                $arrResponse = getResponse('Recibo generado exitosamente', 'ok');
                $arrResponse['recibo'] = $recibo;
            } else {

                die(json_encode(getResponse('Error al generar el recibo. Intente nuevamente'), JSON_UNESCAPED_UNICODE));
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('Error al generar el recibo. Intente nuevamente'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Generar codigo QR de recibos de cobro
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    private function generarQRRecibo($contenido)
    {
        $dir = 'Assets/files/temp';
        $file_name = $dir . '/qr.png';

        $tamanio = 10;
        $level = 'M';
        $frameSize = 1;

        QRcode::png($contenido, $file_name, $level, $tamanio, $frameSize);
    }
}
