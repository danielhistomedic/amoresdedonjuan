<?php


/**
 * Controlador Residentes 
 */
class Residentes extends Controllers
{

    private $session;
    private $permisosMod;
    private $cuenta_model;

    /**
     * Método Constructor de Controlador Residentes.
     * Inicializa Controllers::__construct.
     * Inicializa y valida datos de session.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene los datos de Residente seleccionado.
     * 
     * @param int $residente_id Id de Residente
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
    public function getResidente(int $residente_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Sesion ]*/
            $this->session = new Session();
            if ($this->session->getStatus() === false || empty($this->session->get('email'))) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $residente_id = intval(strClean($residente_id));

            if ($residente_id > 0) {

                $residente_model = new ResidentesModel;
                /*-------------------------------------------
                [ Obtiene array con los datos del Usuario ]*/
                $arrData = $residente_model->selectResidente($residente_id);

                /*-------------------------------------------
                [ Obtiene el importe del saldo disponible del residente ]*/
                $cuenta_model = new CuentasModel;
                $saldo_disponible =  $cuenta_model->selectImporteEgresosPeriodo($residente_id);
                $arrData['saldo_disponible'] = $saldo_disponible;

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
     * Guardar datos de Residente
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setResidente()
    {

        /*-------------------------------------------
        [ Captura cualquier output previo (warnings/notices) para no contaminar el JSON ]*/
        ob_start();

        try {

            /*-------------------------------------------
            [ Validación de Sesion ]*/
            $this->session = new Session();
            if ($this->session->getStatus() === false || empty($this->session->get('email'))) {
                ob_clean();
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod['recibos'] = $arrPermisos[MOD_RECIBOS_COBRO] ?? ['c' => 0, 'r' => 0, 'u' => 0, 'd' => 0];
            $this->permisosMod['recibos_otros'] = $arrPermisos[MOD_RECIBOS_COBRO_OTROS_CONCEPTOS] ?? ['c' => 0, 'r' => 0, 'u' => 0, 'd' => 0];
            $this->permisosMod['recibos_pasados'] = $arrPermisos[MOD_RECIBOS_COBRO_PASADOS] ?? ['c' => 0, 'r' => 0, 'u' => 0, 'd' => 0];

            if ($this->permisosMod['recibos']['u'] == 0 && $this->permisosMod['recibos_otros']['u'] == 0 && $this->permisosMod['recibos_pasados']['u'] == 0) {
                ob_clean();
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $id = intval(strclean($_POST['residente_id'] ?? ''));

            $nombre = strclean($_POST['res_nombre'] ?? '');
            $calle = strclean($_POST['res_calle'] ?? '');
            $numero = strclean($_POST['res_numero'] ?? '');
            $email = strclean($_POST['res_email'] ?? '');
            $telefono = strclean($_POST['res_telefono'] ?? '');
            $res_indicaciones_generales_visitas = strclean($_POST['res_indicaciones_generales_visitas'] ?? '');

            $residente_model = new ResidentesModel;
            $residente_model->setId($id);
            $residente_model->setNombre($nombre);
            $residente_model->setCalle($calle);
            $residente_model->setNumero($numero);
            $residente_model->setEmail($email);
            $residente_model->setTelefono($telefono);
            $residente_model->setIndicaciones_generales_visitas($res_indicaciones_generales_visitas);

            /*-------------------------------------------
            [ Se asigan variables de Sesion. ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Actualizar Registro de Unidad Medica si pasa las validaciones. ]*/
            $response = $residente_model->updateResidente($residente_model, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {

                $arrResponse = getResponse('Registro actualizado exitosamente', 'ok', false);
            } else {

                ob_clean();
                die(json_encode(getResponse('Error al actualizar los datos de residente'), JSON_UNESCAPED_UNICODE));
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            ob_clean();
            die(json_encode(getResponse('Error al actualizar los datos de residente'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        ob_clean();
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de residentes para llenar el autocomplete.
     * 
     * @param string $filtro
     * Texto recibido para filtrar la información en el query
     * 
     * @response $arrResponse, donde:
     * Array con la lista requerida para llenar el autocomplete.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getResidenteSearch(string $filtro)
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Validación de Sesion ]*/
            $this->session = new Session();
            if ($this->session->getStatus() === false || empty($this->session->get('email'))) {
                die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $filtro = strClean($filtro);

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $arrFiltro = explode(",", $filtro);

            /*-------------------------------------------
            [ Obtiene array con los datos de Residente ]*/
            $residente_model = new ResidentesModel;
            $arrData = $residente_model->selectResidentesSearch($arrFiltro);

            for ($i = 0; $i < count($arrData); $i++) {
                $arrTemp = array();
                $arrTemp['value'] =   $arrData[$i]['calle'] . ' '  . $arrData[$i]['numero'];

                $arrTemp['label'] = '<div class="w-100" style="padding: 10px;">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-0 tx-semibold fs-18 tx-menu"><i class="fa-regular fa-house-window pe-2 text-warning"></i> ' . $arrData[$i]['calle'] . ' '  . $arrData[$i]['numero'] . '</h5>
                                        </div>
                                        <span style="padding-left: 32px;"><small class="fs-15"> Manzana: ' . $arrData[$i]['mza'] . ' Lote: ' .  $arrData[$i]['lote'] . '</small><span>
                                        <span><small class="fs-15">Titular: ' . $arrData[$i]['nombre'] . '</small><span>
                                    </div>';
                $arrTemp['id'] =  $arrData[$i]['id'];
                $arrResponse[] = $arrTemp;
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de residentes para llenar el autocomplete de caseta
     * 
     * @param string $filtro
     * Texto recibido para filtrar la información en el query
     * 
     * @response $arrResponse, donde:
     * Array con la lista requerida para llenar el autocomplete.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getResidenteSearchCaseta(string $filtro)
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Validación de Sesion ]*/
            $this->session = new Session();
            if ($this->session->getStatus() === false || empty($this->session->get('email'))) {
                die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $filtro = strClean($filtro);


            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $arrFiltro = explode(",", $filtro);

            /*-------------------------------------------
            [ Obtiene array con los datos de Residente ]*/
            $residente_model = new ResidentesModel;
            $arrData = $residente_model->selectResidentesSearch($arrFiltro);

            for ($i = 0; $i < count($arrData); $i++) {
                $arrTemp = array();
                $arrTemp['value'] =   $arrData[$i]['calle'] . ' '  . $arrData[$i]['numero'];

                $arrTemp['label'] = '<div class="w-100" style="padding: 10px;">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-0 tx-semibold tx-14 tx-menu"><i class="fa-regular fa-house-window pe-2 text-warning"></i> ' . $arrData[$i]['calle'] . ' '  . $arrData[$i]['numero'] . '</h5>
                                        </div>
                                    </div>';
                $arrTemp['id'] =  $arrData[$i]['id'];
                $arrResponse[] = $arrTemp;
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    //*==================================================================
    // [ Directorio ]*/

    /**
     * Carga la Vista del Directorio de Residentes. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Directorio(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_DIRECTORIO_RESIDENTES];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_DIRECTORIO_RESIDENTES;

            //Header
            $data['page_title'] = "Directorio de Residentes";
            $data['page_description'] = "Directorio de Residentes";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-address-book fa-fw text-primary text-shadow-info'></i> Directorio de Residentes";

            //Breadcrump
            $data['page_breadcrumb'] = "Directorio Residentes";

            //Card Principal
            $data['page_card_title'] = "Consulta de Directorio de Residentes";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo para obtener reporte de Datos Generales de Residentes";

            //JS Principal
            $data['page_functions_js'] = "directorio.js";

            //Call Vista
            $this->views->getView($this, "directorio", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene la lista de datos de redidentes para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getDirectorio()
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_DIRECTORIO_RESIDENTES];
            if ($this->permisosMod['r'] == 0) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeIn";

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $residentes_model = new ResidentesModel;
            $arrData = $residentes_model->selectResidentes();

            /*-------------------------------------------
            [ Instanciar modelos para la Clase ]*/
            $this->cuenta_model = new CuentasModel;

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                // { "data": "calle" },
                // { "data": "numero" },
                // { "data": "nombre" },
                // { "data": "telefono" },
                // { "data": "email" },
                // { "data": "estatus" },
                // { "data": "indicaciones_generales_visitas" }
                // $arrData[$i]['estatus'] = '';

                // $importe_adeudo = $this->cuenta_model->getTotalAdeudo($arrData[$i]['id']);

                // $estatus = $this->getEstatusResidente($arrData[$i]['id']);
                // if ($estatus == 1 || $estatus == 2) {

                //     if ($importe_adeudo == 0) {
                //         $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                //                     <span class="badge badge-success">ACTIVO</span>
                //                 </div>';
                //     } else {
                //         if ($importe_adeudo > RENTA_ANT) {
                //             $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                //                                                 <span class="badge badge-warning">ACTIVO (CONVENIO)</span>
                //                                             </div>';
                //         } else {
                //             $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                //                                                     <span class="badge badge-success">ACTIVO</span>
                //                                                 </div>';
                //         }
                //     }
                // } else {
                //     $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                //                                     <span class="badge badge-danger">INACTIVO</span>
                //                                   </div>';
                // }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene los datos del Estatus de Cuenta de un residente seleccionado.
     * NOTA: SI SE MODIFICA DEBE CONSIDERARSE LA MISMA FUNCION EN LA CLASE InformesAdeudosCalle
     * 
     * @param int $residente_id
     * Identificador de residente
     * 
     * @return int $estatus

     */
    private function getEstatusResidente(int $residente_id): int
    {

        try {

            $response = 0;

            /*-------------------------------------------
            [ Actualizar Estatus de Tag de Residente ]*/
            $estatus_cuenta_mes_corriente = $this->cuenta_model->getEstatusCuentaMesCorriente($residente_id);

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
                    $estatus_cuenta_mes_corriente = $this->cuenta_model->getEstatusCuentaMesAnterior($residente_id);
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
}
