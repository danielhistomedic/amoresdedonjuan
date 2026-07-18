<?php

/**
 * Controlador Gastos 
 */
class Gastos extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Gastos.
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
     * Carga la Vista Gastos. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Gastos()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_GASTOS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_REGISTRO_GASTOS;

            //Header
            $data['page_title'] = "Registro de Gastos";
            $data['page_description'] = "Registro de Gastos";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-bag-shopping fa-fw text-primary text-shadow-info'></i> Registro de Gastos";

            //Breadcrump
            $data['page_breadcrumb'] = "Registro de Gastos";

            //Card Principal
            $data['page_card_title'] = "Registro de Gastos";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Registro de Gastos del Fraccionamiento";

            //JS Principal
            $data['page_functions_js'] = "functions_gastos.js";

            //Call Vista
            $this->views->getView($this, "gastos", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene la lista de Gastos para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getGastos()
    {

        try {


            $arrData = array();

            /*-------------------------------------------
             [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_GASTOS];
            if (!$this->permisosMod['r']) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeInLeft";

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $gastos_model = new GastosModel;
            $arrData = $gastos_model->selectGastos();


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

                // //Formato Opciones
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';

                if ($this->permisosMod['r']) {
                    $btnView = '<button style="box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-info d-flex justify-content-center align-items-center view_gastos" data-animation="' . $data_animation . '" onclick="fntViewGasto(this)" data-id="' . $arrData[$i]['Id'] . '" title= "Ver Detalle de Registro">
                                        <i class="fa-regular fa-eye fs-12"></i>
                                    </button>';
                }


                if ($this->permisosMod['u']) {
                    $btnEdit = ' <button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center crear_editar_gastos" data-animation="' . $data_animation . '" onclick="fntEditGasto(this)" data-id="' . $arrData[$i]['Id'] . '" title= "Editar Registro">
                                    <i class="fa-regular fa-pencil-alt fs-12"></i>
                                </button>';
                }

                if ($this->permisosMod['d']) {
                    $btnDelete = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center" onclick="fntDeleteGasto(this)" data-id="' . $arrData[$i]['Id'] . '" title= "Eliminar Registro">
                                        <i class="fa-regular fa-trash-can fs-12"></i>
                                </button>';
                }

                //Formato Options
                $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex justify-content-center align-items-center">' . $btnView . ' ' . $btnEdit  . ' ' . $btnDelete . '</div>';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene los datos de Gasto seleccionado.
     * 
     * @param int $gastos_id Id de Gastos
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
    public function getGasto(int $gastos_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_GASTOS];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $gastos_id = intval(strClean($gastos_id));

            if ($gastos_id > 0) {

                $gastos_model = new GastosModel;
                /*-------------------------------------------
                [ Obtiene array con los datos del Usuario ]*/
                $arrData = $gastos_model->selectGasto($gastos_id);

                if (empty($arrData)) {
                    $arrRespuesta = array(
                        'respuesta' => 'error',
                        'mostrar_mensaje' => true,
                        'tiempo' => 4000,
                        'mensaje' => 'Datos no encontrados'
                    );
                } else {

                    $arrRespuesta = array(
                        'respuesta' => 'ok',
                        'mostrar_mensaje' => false,
                        'tiempo' => 4000,
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
                'tiempo' => 4000,
                'mensaje' => 'Datos no encontrados'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de Gastos para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectGastos(): string
    {

        try {

            $htmlOptions = '';

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_GASTOS];
            if (!$this->permisosMod['r']) {
                die($htmlOptions);
            }

            $gastos_model = new GastosModel;
            $arrData = $gastos_model->selectGastos();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    // if ($arrData[$i]['activo'] == 1) {
                    $htmlOptions .= '<option value="' . $arrData[$i]['Id'] . '">' . $arrData[$i]['descripcion'] . '</option>';
                    // }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Guardar datos de Gasto
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setGasto()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_GASTOS];
            if (!$this->permisosMod['c']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            // POST
            $gasto_id = isset($_POST['inputGastoId']) ? intval(strclean($_POST['inputGastoId'])) : 0;
            $clasificacion_gasto_id = isset($_POST['comboClasificacionGastos']) ? intval($_POST['comboClasificacionGastos']) : 0;
            $fecha_nota = isset($_POST['inputGastoFechaNota']) ? strclean($_POST['inputGastoFechaNota']) : '';
            $descripcion = isset($_POST['inputGastoDescripcion']) ? strclean($_POST['inputGastoDescripcion']) : '';
            $importe = isset($_POST['inputGastoImporte']) ? floatval(strclean($_POST['inputGastoImporte'])) : 0.0;
            $fecha_pago = isset($_POST['inputGastoFechaPago']) ? strclean($_POST['inputGastoFechaPago']) : '';

            $proveedor = isset($_POST['inputGastoProveedor']) ? strclean($_POST['inputGastoProveedor']) : '';
            $folio_nota = isset($_POST['inputGastoFolioNota']) ? strclean($_POST['inputGastoFolioNota']) : '';

            // FILE
            $files = array();
            if (count($_FILES) > 0) {
                $files = $_FILES['inputGastosArchivo'];
            }

            $gastos_model = new GastosModel;
            $gastos_model->setId($gasto_id);
            $gastos_model->setFecha_docto($fecha_nota);
            // $gastos_model->setFecha_pago($fecha_pago);
            $gastos_model->setClasificacion_gasto_id($clasificacion_gasto_id);
            $gastos_model->setDescripcion($descripcion);
            $gastos_model->setProveedor($proveedor);
            $gastos_model->setFolio_nota($folio_nota);
            $gastos_model->setImporte($importe);

            /*-------------------------------------------
            [ Se asigan variables de Sesion. ]*/
            $usuario_id_register =  $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Actualizar Registro de Unidad Medica si pasa las validaciones. ]*/
            if ($gasto_id == 0) {
                $response = $gastos_model->insertGasto($gastos_model, $files, $usuario_id_register);
            } else {
                $response = $gastos_model->updateGasto($gastos_model, $files, $usuario_id_register);
            }

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => "ok",
                    'mostrar_mensaje' => true,
                    'tiempo' => 4000,
                    'mensaje' => "Registro realizado exitosamente"
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error',
                    'mostrar_mensaje' => true,
                    'tiempo' => 4000,
                    'mensaje' => "Error CT103 al realizar el registro, intente nuevamente"
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 3000,
                'mensaje' => "Error CG100  al realizar el registro, intente nuevamente"
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Elminar Registro de Gasto
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function eliminarGasto()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_GASTOS];
            if (!$this->permisosMod['d']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $gasto_id = intval($_POST['id']);


            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $gastos_model = new GastosModel;

            $gasto_record = $gastos_model->selectGasto($gasto_id);
            $fecha_gasto_int = intval(date("Ym", strtotime($gasto_record['fecha_pago'])));
            // 202507;

            $fecha_actual_int = intval(date('Ym'));
            // 202508;

            // if (202507 < 202508)
            if ($fecha_gasto_int  <  $fecha_actual_int) {
                $arrResponse = array(
                    'respuesta' => 'error',
                    'mostrar_mensaje' => true,
                    'tiempo' => 3000,
                    'mensaje' => 'No es posible eliminar gastos de los meses anteriores.'
                );
                die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
            }

            $response = $gastos_model->deleteGasto($gasto_id, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => 'ok',
                    'mostrar_mensaje' => true,
                    'tiempo' => 3000,
                    'mensaje' => 'Registro Eliminado exitosamente.'
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error',
                    'mostrar_mensaje' => true,
                    'tiempo' => 3000,
                    'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 3000,
                'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }


    /**
     * Elminar Archivo Adjunto
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function eliminarArchivo()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_GASTOS];
            if (!$this->permisosMod['d']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $gasto_id = intval($_POST['id']);

            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $gastos_model = new GastosModel;
            $response = $gastos_model->deleteFile($gasto_id);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => 'ok',
                    'mostrar_mensaje' => false,
                    'tiempo' => 3000,
                    'mensaje' => 'Archivo Eliminado exitosamente.'
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error',
                    'mostrar_mensaje' => true,
                    'tiempo' => 3000,
                    'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 3000,
                'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }
}
