<?php

/**
 * Controlador Catalogos 
 */
class Catalogos extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Catalogos.
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


    //*==================================================================
    // [ Clasificacion de Gastos ]*/

    /**
     * Carga la Vista Clasificacion de Gastos.
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function ClasificacionGastos()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_GASTOS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_CLASIFICACION_GASTOS;

            //Header
            $data['page_title'] = "Clasificación de Gastos";
            $data['page_description'] = "Clasificación de Gastos";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-indent fa-fw text-primary text-shadow-info'></i> Clasificación de Gastos";

            //Breadcrump
            $data['page_breadcrumb'] = "Gastos";

            //Card Principal
            $data['page_card_title'] = "Registro de Clasificación de Gastos";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Clasificación de Gastos, Altas, Bajas, Permisos";

            //JS Principal
            $data['page_functions_js'] = "functions_clasificacion_gastos.js";

            //Call Vista
            $this->views->getView($this, "clasificacion_gastos", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene la lista de Clasificaciones de Gastos para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getClasificacionesGastos()
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_GASTOS];
            if (!$this->permisosMod['r']) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeInLeft";

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $clasif_gastos_model = new ClasificacionGastosModel;
            $arrData = $clasif_gastos_model->selectClasificacionesGastos();

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                // { "data": "created_at" },
                // { "data": "clasificacion" },
                // { "data": "activo" },
                // { "data": "options" }

                //Formato Estandar de datos
                $arrData[$i]['clasificacion'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['clasificacion'] .
                    '</div>';


                $arrData[$i]['created_at'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center">'
                    . formatDateTime($arrData[$i]['created_at']) .
                    '</div>';

                //Formato Opciones
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';

                if ($this->permisosMod['r']) {
                    $btnView = '<button style="box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-info d-flex justify-content-center align-items-center view_clasificacion_gastos" data-animation="' . $data_animation . '" onclick="fntViewClasifGastos(this)" data-id="' . $arrData[$i]['id'] . '" title= "Ver Detalle de Registro">
                                        <i class="fa-regular fa-eye fs-12"></i>
                                    </button>';
                }

                if ($this->permisosMod['u']) {
                    if ($arrData[$i]['activo'] == 1) {
                        $btnEdit .= ' <button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center crear_editar_htas_roles" data-animation="' . $data_animation . '" onclick="fntEditClasifGastos(this)" data-id="' . $arrData[$i]['id'] . '" title= "Editar Registro">
                                        <i class="fa-regular fa-pencil-alt fs-12"></i>
                                    </button>';
                    } else {
                        $btnEdit = ' <button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-success d-flex justify-content-center align-items-center" onclick="fntActiveClasifGastos(this)" data-id="' . $arrData[$i]['id'] . '" title= "Reactivar Registro">
                                            <i class="fa-regular fa-arrow-rotate-left fs-12"></i>
                                      </button>';
                    }
                }

                if ($this->permisosMod['d']) {
                    if ($arrData[$i]['activo'] == 1) {
                        $btnDelete = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center" onclick="fntDeleteClasifGastos(this)" data-id="' . $arrData[$i]['id'] . '" title= "Eliminar Registro">
                                            <i class="fa-regular fa-trash-can fs-12"></i>
                                       </button>';
                    }
                }

                //Formato Estatus de Registro
                if ($arrData[$i]['activo'] == 1) {
                    $arrData[$i]['activo'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-success">Activo</span>
                                                 </div>';
                } else {
                    $arrData[$i]['activo'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-danger">Inactivo</span>
                                                  </div>';
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
     * Obtiene los datos de Clasificacion seleccionada.
     * 
     * @param int $clasif_gastos_id Id de Clasificacion
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
    public function getClasificacionGastos(int $clasif_gastos_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_GASTOS];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $clasif_gastos_id = intval(strClean($clasif_gastos_id));

            if ($clasif_gastos_id > 0) {

                $clasif_gastos_model = new ClasificacionGastosModel;
                /*-------------------------------------------
                [ Obtiene array con los datos del Usuario ]*/
                $arrData = $clasif_gastos_model->selectClasificacionGastos($clasif_gastos_id);

                if (empty($arrData)) {
                    $arrRespuesta = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                        'mensaje' => 'Datos no encontrados'
                    );
                } else {

                    $arrRespuesta = array(
                        'respuesta' => 'ok', 'mostrar_mensaje' => false, 'tiempo' => 4000,
                        'mensaje' => 'Datos encontrados',
                        'data' => $arrData
                    );
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrRespuesta = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                'mensaje' => 'Datos no encontrados'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de catálogo de Clasificacion de Gastos para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectClasificacionesGastos(): string
    {

        try {

            $htmlOptions = '';

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_GASTOS];
            if (!$this->permisosMod['r']) {
                die($htmlOptions);
            }

            $clasif_gastos_model = new ClasificacionGastosModel;
            $arrData = $clasif_gastos_model->selectClasificacionesGastos();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['clasificacion'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Guardar datos de Clasificación de Gastos
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setClasifGastos()
    {

        try {


            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_GASTOS];

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $clasif_gastos_id = intval(strclean($_POST['inputClasifGastosId']));
            $clasif_gastos = strclean($_POST['inputClasifGastos']);

            $clasif_gastos_model = new ClasificacionGastosModel;
            $clasif_gastos_model->setId($clasif_gastos_id);
            $clasif_gastos_model->setClasificacion($clasif_gastos);
            $clasif_gastos_model->setActivo(1);


            /*-------------------------------------------
            [ Se asigan variables de Sesion. ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Actualizar Registro de Unidad Medica si pasa las validaciones. ]*/
            if ($clasif_gastos_id == 0) {

                if (!$this->permisosMod['c']) {
                    die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Valida Clasificación antes de insertar ]*/
                $result = $clasif_gastos_model->validaExisteClasificacionGastos($clasif_gastos);
                if ($result == true) {

                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                        'mensaje' => "Error CT101. La Clasificación que desea registrar ya existe."
                    );
                    /*-------------------------------------------
                    [ Retorna anticipado respuesta json_encode ]*/
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }

                $response = $clasif_gastos_model->insertClasificacionGastos($clasif_gastos_model, $usuario_id_register);
            } else {


                if (!$this->permisosMod['u']) {
                    die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
                }

                $result = $clasif_gastos_model->validaExisteClasificacionGastosUpdate($clasif_gastos,  $clasif_gastos_id);
                if ($result == true) {
                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                        'mensaje' => "Error CT102. La Clasificación que desea registrar ya ha sido asignada, verifique."
                    );
                    /*-------------------------------------------
                    [ Retorna anticipado respuesta json_encode ]*/
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }

                $response = $clasif_gastos_model->updateClasificacionGastos($clasif_gastos_model, $usuario_id_register);
            }

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => "ok", 'mostrar_mensaje' => true, 'tiempo' => 4000,
                    'mensaje' => "Registro realizado exitosamente"
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                    'mensaje' => "Error CT103 al realizar el registro, intente nuevamente"
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                'mensaje' => "Error CG100  al realizar el registro, intente nuevamente"
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Elminar Registro de Clasificación de Gastos
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function eliminarClasificacionGastos()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_GASTOS];
            if (!$this->permisosMod['d']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $clasif_gastos_id = intval($_POST['id']);

            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $clasif_gastos_model = new ClasificacionGastosModel;
            $response = $clasif_gastos_model->deleteClasificacionGastos($clasif_gastos_id, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Registro Eliminado exitosamente.'
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Activar Registro de Clasificación de Gastos
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function activarClasificacionGastos()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_GASTOS];
            if (!$this->permisosMod['u']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }


            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $clasif_gastos_id = intval($_POST['id']);

            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $clasif_gastos_model = new ClasificacionGastosModel;
            $response = $clasif_gastos_model->activeClasificacionGastos($clasif_gastos_id, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Registro Reactivado exitosamente.'
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }


    //*==================================================================
    // [ Clasificacion de Ingresos ]*/

    /**
     * Carga la Vista Clasificacion de Gastos.
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function ClasificacionIngresos()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_INGRESOS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_CLASIFICACION_INGRESOS;

            //Header
            $data['page_title'] = "Clasificación de Ingresos";
            $data['page_description'] = "Clasificación de Ingresos";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-indent fa-fw text-primary text-shadow-info'></i> Clasificación de Ingresos";

            //Breadcrump
            $data['page_breadcrumb'] = "Ingresos";

            //Card Principal
            $data['page_card_title'] = "Registro de Clasificación de Ingresos";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Clasificación de Ingresos, Altas, Bajas, Permisos";

            //JS Principal
            $data['page_functions_js'] = "clasificacion_ingresos.js";

            //Call Vista
            $this->views->getView($this, "clasificacion_ingresos", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }


    /**
     * Obtiene la lista de Clasificaciones de Ingresos para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getClasificacionesIngresos()
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_INGRESOS];
            if (!$this->permisosMod['r']) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeInLeft";

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $clasif_ingresos_model = new ClasificacionIngresosModel;
            $arrData = $clasif_ingresos_model->selectClasificacionesIngresos();

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                // { "data": "created_at" },
                // { "data": "clasificacion" },
                // { "data": "activo" },
                // { "data": "options" }

                //Formato Estandar de datos
                $arrData[$i]['concepto'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['concepto'] .
                    '</div>';


                $arrData[$i]['created_at'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center">'
                    . formatDateTime($arrData[$i]['created_at']) .
                    '</div>';

                //Formato Opciones
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';

                if ($this->permisosMod['r']) {
                    $btnView = '<button style="box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-info d-flex justify-content-center align-items-center view_clasificacion_ingresos" data-animation="' . $data_animation . '" onclick="fntViewClasifIngresos(this)" data-id="' . $arrData[$i]['id'] . '" title= "Ver Detalle de Registro">
                                        <i class="fa-regular fa-eye fs-12"></i>
                                    </button>';
                }

                if ($this->permisosMod['u']) {
                    if ($arrData[$i]['activo'] == 1) {
                        $btnEdit .= ' <button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center crear_editar_htas_roles" data-animation="' . $data_animation . '" onclick="fntEditClasifIngresos(this)" data-id="' . $arrData[$i]['id'] . '" title= "Editar Registro">
                                        <i class="fa-regular fa-pencil-alt fs-12"></i>
                                    </button>';
                    } else {
                        $btnEdit = ' <button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-success d-flex justify-content-center align-items-center" onclick="fntActiveClasifIngresos(this)" data-id="' . $arrData[$i]['id'] . '" title= "Reactivar Registro">
                                            <i class="fa-regular fa-arrow-rotate-left fs-12"></i>
                                      </button>';
                    }
                }

                if ($this->permisosMod['d']) {
                    if ($arrData[$i]['activo'] == 1) {
                        $btnDelete = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center" onclick="fntDeleteClasifIngresos(this)" data-id="' . $arrData[$i]['id'] . '" title= "Eliminar Registro">
                                            <i class="fa-regular fa-trash-can fs-12"></i>
                                       </button>';
                    }
                }

                //Formato Estatus de Registro
                if ($arrData[$i]['activo'] == 1) {
                    $arrData[$i]['activo'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-success">Activo</span>
                                                 </div>';
                } else {
                    $arrData[$i]['activo'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-danger">Inactivo</span>
                                                  </div>';
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
     * Obtiene los datos de Clasificacion seleccionada.
     * 
     * @param int $clasif_ingresos_id Id de Clasificacion
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
    public function getClasificacionIngresos(int $clasif_ingresos_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_INGRESOS];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $clasif_ingresos_id = intval(strClean($clasif_ingresos_id));

            if ($clasif_ingresos_id > 0) {

                $clasif_ingresos_model = new ClasificacionIngresosModel;
                /*-------------------------------------------
                [ Obtiene array con los datos del Usuario ]*/
                $arrData = $clasif_ingresos_model->selectClasificacionIngresos($clasif_ingresos_id);

                if (empty($arrData)) {
                    $arrRespuesta = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                        'mensaje' => 'Datos no encontrados'
                    );
                } else {

                    $arrRespuesta = array(
                        'respuesta' => 'ok', 'mostrar_mensaje' => false, 'tiempo' => 4000,
                        'mensaje' => 'Datos encontrados',
                        'data' => $arrData
                    );
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrRespuesta = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                'mensaje' => 'Datos no encontrados'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de catálogo de Clasificacion de Ingresos para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectClasificacionesIngresos(): string
    {

        try {

            $htmlOptions = '';

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_INGRESOS];
            if (!$this->permisosMod['r']) {
                die($htmlOptions);
            }

            $clasif_ingresos_model = new ClasificacionIngresosModel;
            $arrData = $clasif_ingresos_model->selectClasificacionesIngresos();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        if ($arrData[$i]['id'] != 2) {
                            $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['concepto'] . '</option>';
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Guardar datos de Clasificación de Ingresos
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setClasifIngresos()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_INGRESOS];

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $clasif_ingresos_id = intval(strclean($_POST['inputClasifIngresosId']));
            $concepto = strclean($_POST['inputClasifIngresos']);

            $clasif_ingresos_model = new ClasificacionIngresosModel;
            $clasif_ingresos_model->setId($clasif_ingresos_id);
            $clasif_ingresos_model->setConcepto($concepto);
            $clasif_ingresos_model->setActivo(1);


            /*-------------------------------------------
            [ Se asigan variables de Sesion. ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Actualizar Registro de Unidad Medica si pasa las validaciones. ]*/
            if ($clasif_ingresos_id == 0) {

                if (!$this->permisosMod['c']) {
                    die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Valida Clasificación antes de insertar ]*/
                $result = $clasif_ingresos_model->validaExisteClasificacionIngresos($concepto);
                if ($result == true) {

                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                        'mensaje' => "Error CT101. La Clasificación que desea registrar ya existe."
                    );
                    /*-------------------------------------------
                    [ Retorna anticipado respuesta json_encode ]*/
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }

                $response = $clasif_ingresos_model->insertClasificacionIngresos($clasif_ingresos_model, $usuario_id_register);
            } else {

                if (!$this->permisosMod['u']) {
                    die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
                }

                $result = $clasif_ingresos_model->validaExisteClasificacionIngresosUpdate($concepto,  $clasif_ingresos_id);
                if ($result == true) {
                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                        'mensaje' => "Error CT102. La Clasificación que desea registrar ya ha sido asignada, verifique."
                    );
                    /*-------------------------------------------
                    [ Retorna anticipado respuesta json_encode ]*/
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }

                $response = $clasif_ingresos_model->updateClasificacionIngresos($clasif_ingresos_model, $usuario_id_register);
            }

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => "ok", 'mostrar_mensaje' => true, 'tiempo' => 4000,
                    'mensaje' => "Registro realizado exitosamente"
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                    'mensaje' => "Error CT103 al realizar el registro, intente nuevamente"
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                'mensaje' => "Error CG100  al realizar el registro, intente nuevamente"
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Elminar Registro de Clasificación de Inresos
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function eliminarClasificacionIngresos()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_INGRESOS];
            if (!$this->permisosMod['d']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $clasif_ingresos_id = intval($_POST['id']);

            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $clasif_ingresos_model = new ClasificacionIngresosModel;
            $response = $clasif_ingresos_model->deleteClasificacionIngresos($clasif_ingresos_id, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Registro Eliminado exitosamente.'
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Activar Registro de Clasificación de ingresos
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function activarClasificacionIngresos()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_CLASIFICACION_INGRESOS];
            if (!$this->permisosMod['u']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $clasif_ingresos_id = intval($_POST['id']);

            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $clasif_ingresos_model = new ClasificacionIngresosModel;
            $response = $clasif_ingresos_model->activeClasificacioningresos($clasif_ingresos_id, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Registro Reactivado exitosamente.'
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                'mensaje' => 'Error al eliminar el registro, intente nuevamente.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }



    //*==================================================================
    // [ Funciones Fill Autocomplete y Get Catalogo Calles ]*/

    /**
     * Obtiene la lista de calles para llenar el autocomplete.
     * 
     * @param string $filtro
     * Texto recibido para filtrar la información en el query
     * 
     * @response $arrResponse, donde:
     * Array con la lista requerida para llenar el autocomplete.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getCallesSearch(string $filtro)
    {

        try {

            if ($this->session->getStatus() === false || empty($this->session->get('email'))) {
                echo "Error: Acceso restringido";
                die();
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $filtro = strClean($filtro);


            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $arrFiltro = explode(" ", $filtro);

            /*-------------------------------------------
            [ Obtiene array con los datos de Residente ]*/
            $calles_model = new CallesModel;
            $arrData = $calles_model->selectCallesSearch($arrFiltro);
            $arrResponse = array();

            //             DROP TABLE IF EXISTS `histocli_amores`.`calles`;
            // CREATE TABLE  `histocli_amores`.`calles` (
            //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            //   `calle` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Nombre de calle',
            //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
            //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
            //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
            //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
            //   `activo` int(10) unsigned NOT NULL COMMENT '0 = inactivo, 1 = activo, 2 = eliminado',
            //   PRIMARY KEY (`id`)
            // ) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Catalogo de Calles';

            for ($i = 0; $i < count($arrData); $i++) {
                $arrTemp = array();
                $arrTemp['value'] =   $arrData[$i]['calle'];

                $arrTemp['label'] = '<div class="w-100" style="padding: 10px;">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-0 tx-semibold tx-13 tx-menu"><i class="fa-regular fa-house-window pe-2 text-warning"></i> ' . $arrData[$i]['calle'] . '</h5>
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

    /**
     * Obtiene los datos de Calle seleccionado.
     * 
     * @param int $calle_id Id de Calle
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
    public function getCalle(int $calle_id)
    {

        try {



            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $calle_id = intval(strClean($calle_id));

            if ($calle_id > 0) {

                $calles_model = new CallesModel;
                /*-------------------------------------------
                [ Obtiene array con los datos del Usuario ]*/
                $arrData = $calles_model->selectCalle($calle_id);

                if (empty($arrData)) {

                    die(json_encode(getResponse('Datos no encontrados'), JSON_UNESCAPED_UNICODE));
                } else {

                    $arrRespuesta = getResponse('Datos encontrados', 'ok', false);
                    $arrRespuesta['data'] = $arrData;
                }
            }
        } catch (\Throwable $th) {

            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('Code Error cat_1001. Error desconocido: ' . $th->getMessage()), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }



    //*==================================================================
    // [ Funciones Fill Selects ]*/

    /**
     * Obtiene la lista de catálogo de especialidades para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectEspecialidades(): string
    {

        try {
            $htmlOptions = '';
            $especialidades_model = new EspecialidadesModel;
            $arrData = $especialidades_model->selectEspecialidades();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['especialidad'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de tipos de usarios para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectTipoUsuario(): string
    {

        try {
            $htmlOptions = '';
            $tipo_usuarios_model = new TipoUsuariosModel;
            $arrData = $tipo_usuarios_model->selectTipoUsuarios();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['tipo'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de especialidades para llenar un Autocomplete
     * @return json $datos
     */
    public function getAutocompleteEspecialidades($filter)
    {

        try {
            $datos =  array();
            $arrData = array();

            $filter = strClean($filter);
            $especialidades_model = new EspecialidadesModel;
            $arrData = $especialidades_model->autocompleteEspecialidades($filter);
            for ($i = 0; $i < count($arrData); $i++) {
                $datos[] = $arrData[$i]['especialidad'];
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($datos, JSON_UNESCAPED_UNICODE));
    }

    // /**
    //  * Obtiene la lista de catálogo de tipos de Unidad para llenar un Select
    //  * @return string $htmlOptions
    //  */
    // public function getSelectTiposUnidad(): string
    // {

    //     try {
    //         $htmlOptions = '';
    //         $tipo_unidades_meddicas_model = new TipoUnidadesMedicasModel;

    //         $arrData = $tipo_unidades_meddicas_model->selectTipoUnidadesMedicas();

    //         if (count($arrData) > 0) {
    //             for ($i = 0; $i < count($arrData); $i++) {
    //                 if ($arrData[$i]['activo'] == 1) {
    //                     if ($arrData[$i]['id'] != 1) {
    //                         $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['tipo_unidad'] . '</option>';
    //                     }
    //                 }
    //             }
    //         }
    //     } catch (\Throwable $th) {
    //         getLoggerSystem()->error(getMensajeError($th));
    //     }

    //     die($htmlOptions);
    // }

    /**
     * Obtiene la lista de catálogo de tipo de Vialidades para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectTipoVialidades(): string
    {

        try {
            $htmlOptions = '';
            $tipo_vialidades_model = new TipoVialidadesModel;

            $arrData = $tipo_vialidades_model->selectTipoVialidades();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['vialidad'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de tipo de Asentamientos para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectTipoAsentamientos(): string
    {

        try {
            $htmlOptions = '';
            $tipo_vialidades_model = new TipoAsentamientosModel;

            $arrData = $tipo_vialidades_model->selectTipoAsentamientos();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['asentamiento'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de Paises para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectPaises(): string
    {

        try {
            $htmlOptions = '';
            $paises_model = new PaisesModel;

            $arrData = $paises_model->selectPaises();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . ' [' . $arrData[$i]['codigo'] . '] ' . $arrData[$i]['pais'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de Entidades para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectEntidades(string $params): string
    {

        try {

            /*-------------------------------------------
            [ Validar Parametros ]*/
            $htmlOptions = '';

            if (empty($params)) {
                die($htmlOptions);
            } else {
                $arrParams = explode(',', $params);
                $pais_id = intval(strClean($arrParams[0]));
            }

            $entidades_model = new EntidadesModel;

            $arrData = $entidades_model->selectEntidades($pais_id);

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['entidad'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de Municipios para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectMunicipios(string $params): string
    {

        try {

            /*-------------------------------------------
            [ Validar Parametros ]*/
            $htmlOptions = '';

            if (empty($params)) {
                die($htmlOptions);
            } else {
                $arrParams = explode(',', $params);
                $entidad_id = intval(strClean($arrParams[0]));
            }

            $municipios_model = new MunicipiosModel;
            $arrData = $municipios_model->selectMunicipios($entidad_id);

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['municipio'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de Codigos Postales para llenar un Select
     * @return string $htmlOptions
     */
    public function getSelectCodigoPostal(string $params): string
    {

        try {

            /*-------------------------------------------
            [ Validar Parametros ]*/

            $htmlOptions = '';

            if (empty($params)) {
                die($htmlOptions);
            } else {
                $arrParams = explode(',', $params);
                $entidad_id = intval(strClean($arrParams[0]));
                $municipio_id = intval(strClean($arrParams[1]));
            }


            $codigos_postales_model = new CodigosPostalesModel;

            $arrData = $codigos_postales_model->selectAllCodigosPostales($entidad_id, $municipio_id);

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['codigo_postal'] . ', Colonia: ' . $arrData[$i]['colonia'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene los datos del Codigo Postal determinado.
     * 
     * @param int $codigo_postal_id Id de Usuario
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
    public function getCodigoPostal(int $codigo_postal_id)
    {

        try {

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $codigo_postal_id = intval(strClean($codigo_postal_id));

            if ($codigo_postal_id > 0) {

                /*-------------------------------------------
                [ Obtiene array con los datos del Usuario ]*/
                $codigos_postales_model = new CodigosPostalesModel;
                $arrData = $codigos_postales_model->selectCodigoPostal($codigo_postal_id);

                if (empty($arrData)) {
                    $arrRespuesta = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                        'mensaje' => 'Datos no encontrados'
                    );
                } else {
                    $arrRespuesta = array(
                        'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                        'mensaje' => 'Datos encontrados',
                        'data' => $arrData
                    );
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrRespuesta = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 4000,
                'mensaje' => 'Datos no encontrados'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de escuelas para llenar el autocomplete.
     * 
     * @param string $filtro
     * Texto recibido para filtrar la información en el query
     * 
     * @response $arrResponse, donde:
     * Array con la lista requerida para llenar el autocomplete.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getEscuelasAutocomplete(string $filtro)
    {

        try {

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $filtro = strClean($filtro);

            /*-------------------------------------------
            [ Obtiene array con los datos de Escuelas ]*/
            $codigos_postales_model = new EscuelasModel;
            $arrData = $codigos_postales_model->getAutocompleteEscuelas($filtro);
            $arrResponse = array();

            for ($i = 0; $i < count($arrData); $i++) {
                $arrTemp = array();
                $arrTemp['value'] =  $arrData[$i]['escuela'];

                $arrTemp['label'] = '<div class="w-100 p-2">
                                        <i class="fa-solid fa-angles-right me-1"></i>' . $arrData[$i]['escuela'] . '
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
     * Obtiene la lista de catálogo de Tipos de Licencia para llenar un Select
     * @return string $htmlOptions
     */
    public function getTipoLicencia(): string
    {

        try {
            $htmlOptions = '';
            $tipo_licencia_model = new TipoLicenciaModel;

            $arrData = $tipo_licencia_model->selectTipoLicencia();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['tipo'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    /**
     * Obtiene la lista de catálogo de Orgien de como se enteran los clientes para llenar un Select
     * @return string $htmlOptions
     */
    public function getOrigenEntera(): string
    {

        try {
            $htmlOptions = '';
            $tipo_licencia_model = new OrigenEnteraModel;

            $arrData = $tipo_licencia_model->selectOrigenEntera();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['origen'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }

    //
}
