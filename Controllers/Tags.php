<?php

/**
 * Controlador Tags 
 */
class Tags extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Tags.
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
     * Carga la Vista Registro de Tags.
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Tags()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_ACTIVACION_TAGS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_REGISTRO_ACTIVACION_TAGS;

            //Header
            $data['page_title'] = "Registro de Tags";
            $data['page_description'] = "Registro de Tags";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-tags fa-fw text-info text-shadow-info'></i> Registro de Tags";

            //Breadcrump
            $data['page_breadcrumb'] = "Registro de Tags";

            //Card Principal
            $data['page_card_title'] = "Registro de Tags";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Registro y Control de Recibos de Emergencia de Tags de Residentes.";

            //JS Principal
            $data['page_functions_js'] = "functions_tags.js";

            //Call Vista
            $this->views->getView($this, "tags", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene la lista de tags registradas de un Residente Determinado
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getTagsResidente($residente_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_ACTIVACION_TAGS];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Limpiar Variable. ]*/
            $residente_id = intval(strClean($residente_id));

            /*-------------------------------------------
            [ Obtiene el array con la lista de tags del residente seleccionado ]*/
            $tags_model = new TagsModel;
            $arrData = $tags_model->selectTagResidente($residente_id);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de tags para llenar el autocomplete.
     * 
     * @param string $filtro
     * Texto recibido para filtrar la información en el query
     * 
     * @response $arrResponse, donde:
     * Array con la lista requerida para llenar el autocomplete.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getTagsSearch(string $filtro)
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_ACTIVACION_TAGS];
            if (!$this->permisosMod['r']) {
                die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $filtro = strClean($filtro);

            /*-------------------------------------------
            [ Obtiene array con los datos de Escuelas ]*/
            $tag_model = new TagsModel;
            $arrData = $tag_model->selectTagSearch($filtro);

            for ($i = 0; $i < count($arrData); $i++) {

                $arrTemp = array();
                $arrTemp['value'] =   $arrData[$i]['tag'];

                $label = "";
                $residente_id = intval($arrData[$i]['residente_id']);
                if ($residente_id == 0) {
                    $label  = ' <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-0 tx-bold tx-13 tx-menu text-secondary"><i class="fa-regular fa-tag pe-2 text-warning"></i> ' . $arrData[$i]['tag'] . '</h5>
                                </div>
                                <span style="padding-left: 32px;"><small class="fs-12 text-black">Tag No asignada</small><span>';
                } else {
                    $label  = ' <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-0 tx-bold tx-13 tx-menu text-green"><i class="fa-regular fa-check pe-2 text-success small-text"></i> ' . $arrData[$i]['tag'] . '</h5>
                                </div>
                                <span style="padding-left: 32px;"><small class="fs-12 text-success small-text"> Tag Asignada a: </small><span>
                                <span><small class="fs-12 text-success small-text">Calle: ' . $arrData[$i]['calle'] . ' ' . $arrData[$i]['numero'] . '</small><span>';
                }

                $arrTemp['label'] = '<div class="w-100" style="padding: 10px;">
                                        ' . $label . '
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
     * Guardar datos de Tag asociada a un residente
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setTag()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_ACTIVACION_TAGS];

            /*-------------------------------------------
            [ Validación de Acceso POST ]*/
            if (!$_POST) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $residente_id = intval(strclean($_POST['residente_id']));
            $tag_id = intval(strclean($_POST['tag_id']));
            $tag = strclean($_POST['res_tag']);



            /*-------------------------------------------
            [ Validación de datos recibidos ]*/
            if ($residente_id == 0) {
                die(json_encode(getResponse('Debe seleccionar un residente'), JSON_UNESCAPED_UNICODE));
            }

            if (trim($tag) == '') {
                die(json_encode(getResponse('Debe indicar Folio de Tag'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Instanciar Modelo ]*/
            $tag_model = new TagsModel;
            $tag_model->setId($tag_id);
            $tag_model->setResidente_id($residente_id);
            $tag_model->setTag($tag);
            $tag_model->setEstatus(0);
            $tag_model->setSinc(0);

            $date_y = date("Y");
            $date_m = date("m");
            $date_d = date("d");
            $date_str = $date_y . "-" . $date_m . "-" . $date_d;
            $tag_model->setFchFinVIgencia($date_str);



            /*-------------------------------------------
            [ Se asigan variables de Sesion. ]*/
            $usuario_id_register = $this->session->get('usuario_id');



            /*-------------------------------------------
            [ Actualizar Registro de Unidad Medica si pasa las validaciones. ]*/
            if ($tag_id == 0) {

                if (!$this->permisosMod['c']) {
                    die(json_encode(getResponse("No cuenta con privilegios para realizar esta acción."), JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Valida Tag antes de insertar ]*/
                $result = $tag_model->validaExisteTag($tag);
                if ($result == true) {

                    die(json_encode(getResponse("La Tag que desea registrar ya existe, seleccione de la lista la Tag."), JSON_UNESCAPED_UNICODE));
                }

                $response = $tag_model->insertTag($tag_model, $usuario_id_register);
            } else {

                if (!$this->permisosMod['u']) {
                    die(json_encode(getResponse("No cuenta con privilegios para realizar esta acción."), JSON_UNESCAPED_UNICODE));
                }

                $result = $tag_model->validaTagAsignadaResidente($tag, $residente_id);
                if ($result == true) {

                    die(json_encode(getResponse("La Tag que desea registrar ya ha sido asignada a otro residente, verifique."), JSON_UNESCAPED_UNICODE));
                }

                $response = $tag_model->updateTag($tag_model, $usuario_id_register);
            }


            if ($response == true) {

                /*-------------------------------------------
                [ Validación de estatus de cuenta del residente. ]*/
                $cuenta_model = new CuentasModel;
                $estatus_cuenta_mes_corriente = $cuenta_model->getEstatusCuentaMesCorriente($residente_id);
                $tag_model = new TagsModel;
                if ($estatus_cuenta_mes_corriente == 1) {

                    $tag_model->activarTag($residente_id, $usuario_id_register);
                    $arrResponse = getResponse(
                        'Tag registrada exitosamente, sincronice el sistema ZKAccess3.5 Security System para finalizar el proceso de activación',
                        'ok',
                        true,
                        5000
                    );
                } else {

                    $tag_model->desactivarTag($residente_id, $usuario_id_register);

                    $arrResponse = getResponse(
                        'Tag registrada, pero <span class="text-danger">NO SE ACTIVA</span> debido a que no ha pagado el mes corriente.',
                        'warning',
                        true,
                        5000
                    );
                }
            } else {

                die(json_encode(getResponse("Error al realizar el registro, intente nuevamente."), JSON_UNESCAPED_UNICODE));
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse("Error al realizar el registro, intente nuevamente."), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de Tags para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getTags($residente_id)
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_ACTIVACION_TAGS];


            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeInLeft";

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $residente_id = intval(strClean($residente_id));
            $tag_model = new TagsModel;
            $arrData = $tag_model->selectTags($residente_id);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                // { "data": "nombre" },
                // { "data": "domicilio" },
                // { "data": "tag" },
                // { "data": "estatus" },
                // { "data": "options" }
                $arrData[$i]['usuario_reg'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['usuario_reg'] .
                    '</div>';

                //Formato Estandar de datos
                $arrData[$i]['nombre'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['nombre'] .
                    '</div>';

                $arrData[$i]['domicilio'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['calle'] . ' ' . $arrData[$i]['numero'] .
                    '</div>';

                $arrData[$i]['tag'] =  '<div class="fw-semibold text-secondary px-2 py-1 d-flex justify-content-center align-items-center">'
                    . $arrData[$i]['tag'] .
                    '</div>';


                //Formato Estatus de Registro
                $fecha_actual = strtotime(date("Y-m-d"));
                $fecha_entrada = strtotime($arrData[$i]['fchFinVIgencia']);

                if ($fecha_actual >= $fecha_entrada) {
                    $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-danger">Inactiva</span>
                                                </div>';
                } else {
                    $arrData[$i]['estatus'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-success">Activada</span>
                                                </div>';
                }


                $arrData[$i]['created_at'] =  '<div class="px-2 py-1 d-flex justify-content-center align-items-center">'
                    . formatDateTime($arrData[$i]['created_at']) .
                    '</div>';

                //Formato Opciones
                $btnView = '';
                $btnDelete = '';

                if ($this->permisosMod['r']) {

                    $btnView = '<button style="box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-info d-flex justify-content-center align-items-center view_tag" data-animation="' . $data_animation . '" onclick="fntViewTag(this)" data-id="' . $arrData[$i]['id'] . '" title= "Ver Detalle de Registro de Tag">
                                        <i class="fa-regular fa-eye fs-14"></i>
                                    </button>';
                }

                if ($this->permisosMod['d']) {

                    $btnDelete = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center" onclick="fntDeleteTag(this)" data-id="' . $arrData[$i]['id'] . '" title= "Remover Tag">
                                            <i class="fa-regular fa-trash-can fs-14"></i>
                                    </button>';
                }


                //Formato Options y Registros reservados
                $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex justify-content-center align-items-center">' . $btnView . ' '  . $btnDelete . '</div>';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene los datos de Tag seleccionada.
     * 
     * @param int $tag_id Id de Tag
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
    public function getTag(int $tag_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_ACTIVACION_TAGS];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $tag_id = intval(strClean($tag_id));

            if ($tag_id > 0) {

                $tag_model = new TagsModel;
                /*-------------------------------------------
                [ Obtiene array con los datos del Usuario ]*/
                $arrData = $tag_model->selectTag($tag_id);

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
     * Eliminar Tag
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function eliminarTag()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_REGISTRO_ACTIVACION_TAGS];
            if (!$this->permisosMod['d']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $id_tag = intval($_POST['id']);

            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            $tag_model = new TagsModel;
            $tag_model->setId($id_tag);

            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $response = $tag_model->deleteTag($tag_model, $usuario_id_register);

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
                    'mensaje' => 'Error al eliminar la Tag, intente nuevamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 3000,
                'mensaje' => 'Error al eliminar la Tag, intente nuevamente.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }
}
