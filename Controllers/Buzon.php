<?php

/**
 * Controlador Buzon 
 */
class Buzon extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Buzon.
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
     * Carga la Vista BuzonSeguimiento. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function BuzonSeguimiento($params): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_BUZON];
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_BUZON;


            //Obtener y limpiar valores de parametros recibidos
            if (empty($params)) {

                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            } else {
                $arrParams = explode(',', $params);
                $buzon_id = intval(strClean($arrParams[0]));
                $residente_id = intval(strClean($arrParams[1]));
            }

            //Datos de Buzón
            $data['buzon_id'] = $buzon_id;
            $buzon_model = new BuzonModel;
            $arrBuzon = $buzon_model->selectBuzon($buzon_id, $residente_id);
            $data['buzon'] = $arrBuzon;

            //Datos de Residente
            $residente_model = new ResidentesModel;
            $residente =  $residente_model->selectResidente($residente_id);
            $data['residente'] = $residente;

            //Header
            $data['page_title'] = "Buzón de Quejas y Sugerencias";
            $data['page_description'] = "Control de Buzón de Quejas y Sugerencias";

            //Form Principal fa-file-excel
            $data['page_form_title'] = "<i class='fa-regular fa-mailbox fa-fw text-info text-shadow-info'></i> Seguimiento de Buzón de Quejas y Sugerencias";

            //Breadcrump
            $data['page_breadcrumb'] = "Seguimiento Buzón";

            //Card Principal
            $data['page_card_title'] = "Historial de Registros de Quejas y Sugerencias";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Control de Buzón de Quejas y Sugerencias.";

            //JS Principal
            $data['page_functions_js'] = "buzon.js";

            //Call Vista
            $this->views->getView($this, "buzonSeguimiento", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Carga la Vista Buzon. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Buzon(): void
    {

        //La vista aplica pero en el portal de residentes.
    }

    /**
     * Carga la Vista Buzon. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function BuzonAdmin($params): void
    {

        try {


            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_BUZON];
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;

            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_BUZON;


            //Obtener y limpiar valores de parametros recibidos
            if (empty($params)) {
                $this->session->redirect('inicio');
                die();
            } else {
                $arrParams = explode(',', $params);
                $buzon_id = intval(strClean($arrParams[0]));
                $residente_id = intval(strClean($arrParams[1]));
            }

            //Datos de Buzón
            $data['buzon_id'] = $buzon_id;
            $buzon_model = new BuzonModel;
            $arrBuzon = $buzon_model->selectBuzon($buzon_id, $residente_id);
            $data['buzon'] = $arrBuzon;

            //Datos de Residente
            $residente_model = new ResidentesModel;
            $residente =  $residente_model->selectResidente($residente_id);
            $data['residente'] = $residente;

            //Header
            $data['page_title'] = "Buzón de Quejas y Sugerencias";
            $data['page_description'] = "Control de Buzón de Quejas y Sugerencias";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-mailbox fa-fw text-info text-shadow-info'></i> Registro de Buzón de Quejas y Sugerencias";

            //Breadcrump
            $data['page_breadcrumb'] = "Registro Buzón";

            //Card Principal
            $data['page_card_title'] = "Registros de Contestación de Buzón";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Control de Buzón de Quejas y Sugerencias.";

            //JS Principal
            $data['page_functions_js'] = "buzon.js";


            //Call Vista
            $this->views->getView($this, "buzonAdmin", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Carga la Vista BuzonList. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function BuzonList(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_BUZON];
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;

            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_BUZON;

            //Header
            $data['page_title'] = "Buzón de Quejas y Sugerencias";
            $data['page_description'] = "Control de Buzón de Quejas y Sugerencias";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-mailbox fa-fw text-warning text-shadow-warning'></i> Seguimiento Buzón de Quejas y Sugerencias";


            //Breadcrump
            $data['page_breadcrumb'] = "Seguimiento Buzón";

            //Card Principal
            $data['page_card_title'] = "Historial de Registros de Quejas y Sugerencias";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Administración y Seguimiento de Buzón de Quejas y Sugerencias.";

            //JS Principal
            $data['page_functions_js'] = "buzon.js";

            //Call Vista
            $this->views->getView($this, "buzonList", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Guardar datos de Quejas y Sugerencias
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setBuzonAdmin()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_BUZON];
            if (!$this->permisosMod['c']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se asigan variables de Sesion. ]*/
            $unidad_medica_id = $this->session->get('unidad_medica_id');
            $usuario_id_register =  $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $estatus = intval($_POST['comboEstatusBuzon']);
            $mensaje = strclean($_POST['mensaje']);
            $buzon_id = intval(strclean($_POST['buzon_id']));


            if (trim($mensaje) == '') {
                die(json_encode(getResponse('Debe indicar Mensaje'), JSON_UNESCAPED_UNICODE));
            }

            if ($estatus == 0) {
                die(json_encode(getResponse('Debe indicar Estatus'), JSON_UNESCAPED_UNICODE));
            }

            if ($buzon_id == 0) {
                die(json_encode(getResponse('NO se seleccionó adecuadamente el Buzón, regrese a la lista del buzón y vuelva a seleccionar el registro que desea contestar.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se reciben Datos del FILE con FormData ]*/
            $adjunto = '';
            $files = array();
            $files = $_FILES['adjunto'];
            if ($files['name'] != '') {
                $max_size = 1024 * 1024 * 5;
                $size = $files['size'];
                if ($size > $max_size) {
                    die(json_encode(getResponse('El archivo adjunto no puede ser mayor a 5 megas'), JSON_UNESCAPED_UNICODE));
                }

                $type = $files['type'];
                $arrType = explode('/', $type);
                $type = $arrType[1];
                $adjunto = date('YmdHis');
                $adjunto = encode($adjunto) . '.' . $type;
            }

            /*-------------------------------------------
            [ Se instancia el Modelo ]*/
            $buzon_model = new BuzonModel;

            /*-------------------------------------------
            [ Se asignan lso valores a registrar ]*/
            $buzon_model->setId($buzon_id);
            $buzon_model->setMensaje($mensaje);
            $buzon_model->setAdjunto($adjunto);
            $buzon_model->setEstatus($estatus);

            /*-------------------------------------------
            [ Actualizar Registro de Unidad Medica si pasa las validaciones. ]*/
            $response = $buzon_model->updateBuzon($buzon_model, $files, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == false) {
                die(json_encode(getResponse('Code buzadmin_1001: Error al realizar el registro'), JSON_UNESCAPED_UNICODE));
            }

            $arrResponse = getResponse('Registro realizado exitosamente', 'ok', true);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('Code buzadmin_1002: Error al realizar el registro'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de Gastos para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getListBuzon($residente_id)
    {

        try {


            $arrData  = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_BUZON];
            if (!$this->permisosMod['r']) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se limpian datos recibidos. ]*/
            $residente_id = intval(strClean($residente_id));


            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $gastos_model = new BuzonModel;
            $arrData = $gastos_model->selectListBuzon($residente_id);


            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                // { "data": "No" },
                $arrData[$i]['No'] =  $i + 1;

                // { "data": "nombre" },
                // { "data": "domicilio" },

                // { "data": "Folio" },
                $arrData[$i]['Folio'] = '<a style = "text-decoration: underline;" href="'  . base_url() . '/buzon/buzonSeguimiento/' . $arrData[$i]['Id'] . '/' . $arrData[$i]['residente_id'] . '">' .  formatCerosIzquierda($arrData[$i]['Id'], 5) . '</a>';

                // { "data": "created_at" },
                // { "data": "tipo" },
                // { "data": "asunto" },

                // { "data": "estatus" },
                // 1 = En Seguimiento, 2 = Cerrado
                if ($arrData[$i]['estatus'] == 2) {
                    $arrData[$i]['estatus'] = '<span class="text-success">Cerrado</span>';
                } else if ($arrData[$i]['estatus'] == 1) {
                    $arrData[$i]['estatus'] = '<span class="text-warning">En Seguimiento</span>';
                } else if ($arrData[$i]['estatus'] == 0) {
                    $arrData[$i]['estatus'] = '<span class="text-danger">Abierto</span>';
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
     * Obtiene la lista de Seguimiento
     * @return string $htmlOptions
     */
    public function getSeguimientoBuzon($params): string
    {

        try {

            $htmlOptions = '';

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_BUZON];
            if (!$this->permisosMod['r']) {
                die($htmlOptions);
            }

            //Obtener y limpiar valores de parametros recibidos
            if (empty($params)) {
                die($htmlOptions);
            } else {
                $arrParams = explode(',', $params);
                $buzon_id = intval(strClean($arrParams[0]));
                $residente_id = intval(strClean($arrParams[1]));
            }

            /*-------------------------------------------
            [ Instanciar Modelo y Generar respuesta html ]*/
            $buzon_model = new BuzonModel;
            $arrData = $buzon_model->selectSeguimientoBuzon($buzon_id, $residente_id);

            for ($i = 0; $i < count($arrData); $i++) {

                $adjunto = '';
                if ($arrData[$i]['adjunto'] != '') {
                    $adjunto  = ' <div class="card-footer">
                                    <a class="nav-link w-100" data-bs-toggle="tooltip" href="'  . media() . '/files/buzon/' . $arrData[$i]['adjunto'] . '" target="_blank" title="" data-bs-original-title="abrir" aria-label="abrir">
                                            <i class="fe fe-paperclip me-2"></i>
                                            <span>Ver archivo adjunto</span>
                                    </a>
                                </div>';
                }

                if ($arrData[$i]['administracion'] == 0) {
                    $htmlOptions .= '<div class="">
                                        <div class="card">
                                            <div class="card-status bg-warning br-tr-7 br-tl-7"></div>
                                            <div class="card-header">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <h3 class="card-title"><i class="fa-regular fa-user fa-xl"></i></h3>
                                                    <div class="ms-2 d-flex flex-column">
                                                        <h4 class="fs-16 m-0 fw-bold">Residente</h4>
                                                        <h6 class="fs-12 m-0">' . $arrData[$i]['calle'] . ' ' . $arrData[$i]['numero'] . '</h6>
                                                    </div>
                                                </div>
                                                <div class="card-options">
                                                    <h6 class="fs-12 m-0">' . formatDateTime($arrData[$i]['created_at']) . '</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                ' . $arrData[$i]['mensaje'] . '
                                            </div>
                                            ' . $adjunto . '
                                        </div>
                                    </div>';
                } else {
                    $htmlOptions .= ' <div class="">
                                        <div class="card">
                                            <div class="card-status card-status-left bg-info br-bl-7 br-tl-7"></div>
                                            <div class="card-header">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <h3 class="card-title"><i class="fa-regular fa-user-gear fa-xl"></i></h3>
                                                    <div class="ms-2 d-flex flex-column">
                                                        <h4 class="fs-16 m-0 fw-bold">Administración</h4>
                                                        <h6 class="fs-12 m-0">Mesa Directiva</h6>
                                                    </div>
                                                </div>
                                                <div class="card-options">
                                                    <h6 class="fs-12 m-0">' . formatDateTime($arrData[$i]['created_at']) . '</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            ' . $arrData[$i]['mensaje'] . '
                                            </div>
                                            ' . $adjunto . '
                                        </div>
                                    </div>';
                }

                $htmlOptions .= '<div class="col-12"> </div>';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    //Administracion
    /**
     * Obtiene la lista de Gastos para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getListBuzonAdmin()
    {

        try {


            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_BUZON];
            if (!$this->permisosMod['r']) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $gastos_model = new BuzonModel;
            $arrData = $gastos_model->selectListBuzonAdmin();

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                // { "data": "No" },
                $arrData[$i]['No'] =  $i + 1;

                // { "data": "Folio" },
                $arrData[$i]['Folio'] = '<a style = "text-decoration: underline;" href="'  . base_url() . '/buzon/buzonSeguimiento/' . $arrData[$i]['Id'] . '/' . $arrData[$i]['residente_id'] . '">' .  formatCerosIzquierda($arrData[$i]['Id'], 5) . '</a>';

                // { "data": "created_at" },
                // { "data": "tipo" },
                // { "data": "asunto" },

                // { "data": "estatus" },
                // 1 = En Seguimiento, 2 = Cerrado
                if ($arrData[$i]['estatus'] == 2) {
                    $arrData[$i]['estatus'] = '<span class="text-success">Cerrado</span>';
                } else if ($arrData[$i]['estatus'] == 1) {
                    $arrData[$i]['estatus'] = '<span class="text-warning">En Seguimiento</span>';
                } else if ($arrData[$i]['estatus'] == 0) {
                    $arrData[$i]['estatus'] = '<span class="text-danger">Abierto</span>';
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }
}
