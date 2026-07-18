<?php

/**
 * Controlador Roles 
 */
class Roles extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Roles.
     * Inicializa Controllers::__construct
     * Inicializa y valida datos de session.
     * 
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
     * Obtiene la lista de catálogo de Roles para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getRoles()
    {

        try {


            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_ROLES];
            if ($this->permisosMod['r'] == 0) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeIn";

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $roles_model = new RolesModel;
            $arrData = $roles_model->selectRoles();


            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                //Formato Estandar de datos
                $arrData[$i]['name'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['name'] .
                    '</div>';

                $arrData[$i]['descripcion'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['descripcion'] .
                    '</div>';

                //Formato Opciones
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';

                if ($this->permisosMod['r']) {
                    $btnView = '<button style="box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-info d-flex justify-content-center align-items-center view_htas_roles" data-animation="' . $data_animation . '" onclick="fntViewRol(this)" data-id="' . $arrData[$i]['id'] . '" title= "Ver Detalle de Registro">
                                        <i class="fa-regular fa-eye fs-12"></i>
                                    </button>';
                }

                if ($this->permisosMod['u']) {
                    if ($arrData[$i]['activo'] == 1) {

                        $btnEdit .= '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-warning d-flex justify-content-center align-items-center" onclick="fntPermisosRol(this)" data-id="' . $arrData[$i]['id'] . '" title= "Asignar Permisos">
                                        <i class="fa-regular fa-key-skeleton fs-12"></i>
                                    </button>';

                        $btnEdit .= ' <button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center crear_editar_htas_roles" data-animation="' . $data_animation . '" onclick="fntEditRol(this)" data-id="' . $arrData[$i]['id'] . '" title= "Editar Registro">
                                        <i class="fa-regular fa-pencil-alt fs-12"></i>
                                    </button>';
                    } else {
                        $btnEdit = ' <button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-success d-flex justify-content-center align-items-center" onclick="fntActiveRol(this)" data-id="' . $arrData[$i]['id'] . '" title= "Reactivar Registro">
                                            <i class="fa-regular fa-arrow-rotate-left fs-12"></i>
                                      </button>';
                    }
                }

                if ($this->permisosMod['d']) {
                    if ($arrData[$i]['activo'] == 1) {
                        $btnDelete = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center" onclick="fntDeleteRol(this)" data-id="' . $arrData[$i]['id'] . '" title= "Suspender Registro">
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

                //Formato Options y Registros reservados
                if ($arrData[$i]['reservado'] == 1) {
                    if ($this->session->get('usuario_id') == 1) {
                        $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex justify-content-center align-items-center">' . $btnView . ' ' . $btnEdit  . ' ' . $btnDelete . '</div>';
                    } else {
                        $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex flex-column justify-content-center align-items-center text-muted tx-11 tx-thin"><span>No Disponible</span><span>[Reservado por sistema]</span></div>';
                    }
                } else {
                    $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex justify-content-center align-items-center">' . $btnView . ' ' . $btnEdit  . ' ' . $btnDelete . '</div>';
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
     * Obtiene los datos del Rol.
     * 
     * @param int $rol_id
     * Identificador de Rol seleccionado
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * * data (array). En caso de ser exitoso, el elemento data contiene la información solicitada.
     * 
     * @return string 
     * json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     * 
     * 
     */
    public function getRol(int $rol_id)
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_ROLES];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $rol_id = intval(strClean($rol_id));

            if ($rol_id > 0) {

                /*-------------------------------------------
                [ Obtiene array con los datos del rol ]*/
                $rol_model = new RolesModel;
                $arrData = $rol_model->selectRol($rol_id);
                if (empty($arrData)) {
                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                        'mensaje' => 'Lo sentimos, Datos no encontrados'
                    );
                } else {
                    $arrResponse = array(
                        'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                        'mensaje' => 'Lo sentimos, Datos no encontrados',
                        'data' => $arrData
                    );
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 6000,
                'mensaje' => 'Datos no encontrados'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }


    /**
     * Guardar/Actualizar datos de Rol
     * 
     * @return string 
     * json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     * $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     */
    public function setRol()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_ROLES];

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $unidad_medica_id = $this->session->get('unidad_medica_id');
            $usuario_id_register = $this->session->get('usuario_id');


            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $id_post = intval($_POST['inputIdRol']);
            if ($id_post == 0) {

                /*-------------------------------------------
                [ Valida Permisos. ]*/
                if (!$this->permisosMod['c']) {
                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                        'mensaje' => 'No cuenta con privilegios suficientes para realizar esta acción'
                    );
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }
            } else {

                /*-------------------------------------------
                [ Valida Permisos. ]*/
                if (!$this->permisosMod['u']) {
                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                        'mensaje' => 'No cuenta con privilegios suficientes para realizar esta acción'
                    );
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }
            }

            $rol_post = strclean($_POST['inputNombreRol']);
            $description_post = strclean($_POST['inputDescripcionRol']);


            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            $rol_model = new RolesModel;
            $rol_model->setId($id_post);
            $rol_model->setName($rol_post);
            $rol_model->setDescripcion($description_post);
            $rol_model->setUnidad_medica_id($unidad_medica_id);

            if ($id_post == 0) {

                /*==========================================
                [ Crear Nuevo Registro ]*/

                /*-------------------------------------------
                [ Valida si el rol ya existe ]*/
                $existe = $rol_model->validInsertExistRol($rol_post);
                if ($existe == true) {
                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                        'mensaje' => 'El rol ya existe'
                    );
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Inserta el Registro si pasa las validaciones. ]*/
                $response = $rol_model->insertRol($rol_model, $usuario_id_register);
                if ($response == true) {
                    $mensaje = 'Registro creado exitosamente';
                } else {
                    $mensaje = 'Error al crear el registro, intente nuevamente';
                }
            } else {

                /*==========================================
                [ Actualizar Registro ]*/

                /*-------------------------------------------
                [ Valida si el rol ya existe ]*/
                $existe = $rol_model->validUpdateExistRol($rol_post, $id_post);
                if ($existe == true) {
                    $arrResponse = array(
                        'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                        'mensaje' => 'El rol ya existe'
                    );
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Actualizar Registro ]*/
                $response = $rol_model->updateRol($rol_model, $usuario_id_register);
                if ($response == true) {
                    $mensaje = 'Registro actualizado exitosamente';
                } else {
                    $mensaje = 'Error al crear el registro, intente nuevamente';
                }
            }

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => $mensaje
                );
            } else {
                $arrResponse = array(
                    'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => $mensaje
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                'mensaje' => $mensaje
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }


    /**
     * Actualizar Estatus de Rol
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setEstatusRol()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_ROLES];
            if (!$this->permisosMod['u']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register =  $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $id_post = intval($_POST['id']);
            $estatus = intval($_POST['estatus']);


            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            $rol_model = new RolesModel;
            $rol_model->setId($id_post);
            $rol_model->setActivo($estatus);

            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $response = $rol_model->updateEstatusRol($rol_model, $usuario_id_register);

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
     * Obtiene la lista de catalogo de roles, para crear el html con los options que llenaran el Select correspondiente.
     * 
     * @return string $htmlOptions
     * 
     */
    public function getSelectRoles()
    {

        try {

            $htmlOptions = '';

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_ROLES];
            if (!$this->permisosMod['r']) {
                die($htmlOptions);
            }

            $htmlOptions .= '<option value="" selected="selected" disabled>Seleccione una opcion</option>';
            $rol_model = new RolesModel;
            $arrData = $rol_model->selectRoles();
            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['name'] . '</option>';
                    }
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta string html ]*/
        die($htmlOptions);
    }
}
