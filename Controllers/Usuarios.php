<?php

/**
 * Controlador Usuarios 
 */
class Usuarios extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Usuarios.
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
     * Obtiene la lista de usuarios para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getUsuarios()
    {

        try {

            $arrData = array();

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_USUARIOS];
            if ($this->permisosMod['r'] == 0) {
                die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Variables. ]*/
            $data_animation = "fadeIn";
            $fa_type = "fa-regular";


            /*-------------------------------------------
            [ Obtiene el array con la lista de usuarios ]*/
            $usuarios_model = new UsuariosModel;
            $arrData = $usuarios_model->selectUsuarios();

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrData); $i++) {

                /*-------------------------------------------
                [ Varibale For]*/
                $activo = $arrData[$i]['activo'];


                //Usuario
                $arrData[$i]['usuario'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['usuario'] .
                    '</div>';

                //Nombre
                $arrData[$i]['nombre'] = '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['nombre'] . ' ' . $arrData[$i]['paterno'] . ' ' . $arrData[$i]['materno'] .
                    '</div>';


                //Email
                $arrData[$i]['email'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['email'] .
                    '</div>';

                //Telefono
                $arrData[$i]['telefono'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['telefono'] .
                    '</div>';


                //Unidad Médica
                // $arrData[$i]['nombre_unidad'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                //     . $arrData[$i]['nombre_unidad'] .
                //     '</div>';

                //Rol
                $arrData[$i]['rol'] =  '<div class="px-2 py-1 d-flex justify-content-start align-items-center">'
                    . $arrData[$i]['rol'] .
                    '</div>';


                //Estatus
                if ($activo == 1) {
                    $arrData[$i]['activo'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-success">Activo</span>
                                                 </div>';
                } else {
                    $arrData[$i]['activo'] = '<div class="d-flex justify-content-center align-items-center">
                                                    <span class="badge badge-danger">Inactivo</span>
                                                  </div>';
                }

                //Opciones
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';

                if ($this->permisosMod['r']) {
                    $btnView = '<button style="box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-info d-flex justify-content-center align-items-center view_htas_usuario" data-animation="' . $data_animation . '" onclick="fntViewUsuario(this)" data-um-id="' . $arrData[$i]['unidad_medica_id'] . '" data-id="' . openssl_encrypt($arrData[$i]['id'], METHODENCRIPT, KEY) . '" title= "Ver Detalle de Registro">
                                         <i class="' . $fa_type . ' fa-eye fs-12"></i>
                                     </button>';
                }

                if ($this->permisosMod['u']) {
                    if ($activo == 1) {
                        $btnEdit .= ' <button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center crear_editar_htas_usuario" data-animation="' . $data_animation . '" onclick="fntEditUsuario(this)" data-um-id="' . $arrData[$i]['unidad_medica_id'] . '" data-id="' . openssl_encrypt($arrData[$i]['id'], METHODENCRIPT, KEY) . '" title= "Editar Registro">
                                         <i class="' . $fa_type . ' fa-pencil-alt fs-12"></i>
                                     </button>';
                    } else {
                        $btnEdit = ' <button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-success d-flex justify-content-center align-items-center" onclick="fntActiveUsuario(this)" data-um-id="' . $arrData[$i]['unidad_medica_id'] . '" data-id="' . openssl_encrypt($arrData[$i]['id'], METHODENCRIPT, KEY) . '" title= "Reactivar Registro">
                                             <i class="' . $fa_type . ' fa-arrow-rotate-left fs-12"></i>
                                       </button>';
                    }
                }

                if ($this->permisosMod['d']) {
                    if ($activo == 1) {
                        $btnDelete = '<button style="margin-left: 3px; box-shadow: none!important; width: 40px;" class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center" onclick="fntDeleteUsuario(this)" data-um-id="' . $arrData[$i]['unidad_medica_id'] . '" data-id="' . openssl_encrypt($arrData[$i]['id'], METHODENCRIPT, KEY) . '" title= "Suspender Registro">
                                             <i class="' . $fa_type . ' fa-trash-can fs-12"></i>
                                        </button>';
                    }
                }

                $arrData[$i]['options'] = '<div class="px-2 py-1 d-flex justify-content-center align-items-center">' . $btnView . ' ' . $btnEdit  . ' ' . $btnDelete . '</div>';
            }

            /*-------------------------------------------
            [ Retorna respuesta json_encode ]*/
            die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene los datos del Usuario.
     * 
     * @param int $usuario_id
     * Identificador de Usuario seleccionado
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * * data (array). En caso de ser exitoso, el elemento data contiene la información solicitada.
     * * dataEspecialidad (array). En caso de ser exitoso, el elemento dataEspecialidad contiene la información solicitada.
     * 
     * @return string
     * json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     * 
     * 
     */
    public function getUsuario()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_USUARIOS];
            if (!$this->permisosMod['r']) {
                die(json_encode(getResponse('Acceso restringido'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se reciben Datos del POST ]*/
            $usuario_id = $_POST['idUsuario'];


            /*-------------------------------------------
            [ Asigna variables de Sesión. ]*/
            $unidad_medica_id = $this->session->get('unidad_medica_id');


            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $usuario_id = intval(openssl_decrypt($usuario_id, METHODENCRIPT, KEY));


            if ($usuario_id > 0) {

                /*-------------------------------------------
                [ Obtiene array con los datos del Usuario ]*/
                $usuario_model = new UsuariosModel;
                $arrData = $usuario_model->selectUsuario($usuario_id, $unidad_medica_id);
                $arrDataEspecialidad = $usuario_model->selectUsuarioEspecialidad($usuario_id);

                if (empty($arrData)) {
                    $arrResponse = array(
                        'respuesta' => 'error',
                        'mostrar_mensaje' => true,
                        'tiempo' => 4000,
                        'mensaje' => 'Datos no encontrados'
                    );
                } else {
                    $arrResponse = array(
                        'respuesta' => 'ok',
                        'mostrar_mensaje' => true,
                        'tiempo' => 4000,
                        'mensaje' => 'Datos encontrados',
                        'dataId' => openssl_encrypt($usuario_id, METHODENCRIPT, KEY),
                        'data' => $arrData,
                        'dataEspecialidad' => $arrDataEspecialidad
                    );
                }
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 4000,
                'mensaje' => 'Datos no encontrados'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Guardar/Actualizar datos de Usuario
     * 
     * @return string json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     * $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     */
    public function setUsuario()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_USUARIOS];

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $unidad_medica_id = $this->session->get('unidad_medica_id');
            $usuario_id_register = $this->session->get('usuario_id');
            $pais_id = 136;

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $id_usuario = $_POST['inputIdUsuario'];
            $password_sys = strclean($_POST['inputRegisterConfirmPassword']);


            if ($_POST['inputIdUsuario'] == '') {
                $id_usuario = 0;
            } else {
                $id_usuario = $_POST['inputIdUsuario'];
                $id_usuario = intval(strClean(openssl_decrypt($id_usuario, METHODENCRIPT, KEY)));
            }

            if ($id_usuario == 0) {

                /*-------------------------------------------
                [ Valida Permisos. ]*/
                if (!$this->permisosMod['c']) {
                    $arrResponse = array(
                        'respuesta' => 'error',
                        'mostrar_mensaje' => true,
                        'tiempo' => 3000,
                        'mensaje' => 'No cuenta con privilegios suficientes para realizar esta acción'
                    );
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }
                $password_sys = empty($password_sys) ? passGenerator() : $password_sys;
                $password_encrypt = hash("SHA256", $password_sys);
            } else {

                /*-------------------------------------------
                [ Valida Permisos. ]*/
                if (!$this->permisosMod['u']) {
                    $arrResponse = array(
                        'respuesta' => 'error',
                        'mostrar_mensaje' => true,
                        'tiempo' => 3000,
                        'mensaje' => 'No cuenta con privilegios suficientes para realizar esta acción'
                    );
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }

                if ($password_sys == '') {
                    $password_encrypt = $password_sys;
                } else {
                    $password_encrypt = hash("SHA256", $password_sys);
                }
            }

            // -- Datos de Usuario --
            $user_sys = strclean($_POST['inputEmail']);


            $activo = 1;
            $tipo_usuario_id = intval(3);
            $theme = "light-mode";
            $origen_id = 9;

            // -- Datos Generales --
            $nombre = ucwords(strclean($_POST['inputNombreUsuario']));
            $paterno = ucwords(strclean($_POST['inputApellidoPaterno']));
            $materno = ucwords(strclean($_POST['inputApellidoMaterno']));
            $email = strtolower(strclean($_POST['inputEmail']));
            $telefono = strclean($_POST['inputTelefono']);
            $sexo_id = intval($_POST['comboSexo']);
            $rol_id = intval($_POST['comboRoles']);

            // -- Datos Relación Usuarios Unidades Médicas --
            $titular = 0;

            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            // -- Datos de Usuario --
            $usuario_model = new UsuariosModel;
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
            $usuario_model->setResidente_id(0);

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
                    $arrResponse = array(
                        'respuesta' => 'error',
                        'mostrar_mensaje' => true,
                        'tiempo' => 3000,
                        'mensaje' => 'El usuario ya existe'
                    );
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Inserta el Registro si pasa las validaciones. ]*/
                $response = $usuario_model->insertUsuario($usuario_model, $unidad_medica_id, $usuario_id_register);
                if ($response == true) {
                    $mensaje = 'Registro creado exitosamente';
                } else {
                    $mensaje = 'Error al crear el registro';
                }
            } else {

                /*===========================================
                [ Actualizar Registro ]*/

                /*-------------------------------------------
                [ Valida si el Usuario ya existe ]*/
                $existe = $usuario_model->validUpdateExistUsuario($user_sys, $id_usuario);
                if ($existe == true) {
                    $arrResponse = array(
                        'respuesta' => 'error',
                        'mostrar_mensaje' => true,
                        'tiempo' => 4000,
                        'mensaje' => 'El usuario ya existe'
                    );
                    die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
                }

                /*-------------------------------------------
                [ Actualizar Registro ]*/
                $response = $usuario_model->updateUsuario($usuario_model, $unidad_medica_id, $usuario_id_register);
                if ($response == true) {
                    $mensaje = 'Registro actualizado exitosamente';
                } else {
                    $mensaje = 'Error al crear el registro';
                }
            }

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $arrResponse = array(
                    'respuesta' => 'ok',
                    'mostrar_mensaje' => true,
                    'tiempo' => 8000,
                    'mensaje' => $mensaje,
                    'usuario' => $email
                );

                if ($id_usuario == 0) {
                    /*-------------------------------------------
                    [ Enviar Correo de Bienvenida  ]*/
                    $nombre_usuario = $nombre . ' ' .  $paterno . ' ' . $materno;
                    $datos_usuario = array(
                        'nombre_usuario' => $nombre_usuario,
                        'email' => $email,
                        'password' => $password_sys,
                        'asunto' => 'Cuenta de Acceso al Sistema. Fracc. Amores de Don Juan',
                    );
                    $sendEmail = sendEmailPHPMailer($datos_usuario, 'email_bienvenido_new_user');
                    if (!$sendEmail) {
                        getLoggerSystem()->error('No se envió correo de bienvenida', $datos_usuario);
                    }
                    /*------------------------------------------- */
                }
            } else {
                $arrResponse = array(
                    'respuesta' => 'error',
                    'mostrar_mensaje' => true,
                    'tiempo' => 4000,
                    'mensaje' => $mensaje
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrResponse = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 4000,
                'mensaje' => $mensaje
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Estatus de Registro
     * Modifica el status para activar o desactivar de un registro determinado
     * 
     * 
     * @return string json_encode($arrResponse, JSON_UNESCAPED_UNICODE)
     * $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * 
     * 
     */
    public function setEstatusUsuario()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_USUARIOS];
            if (!$this->permisosMod['u']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se aignan las variables de Sesión ]*/
            $usuario_id_register = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $id_usuario = $_POST['usuario_id'];
            $activo = $_POST['activo'];
            $id_usuario = intval(strClean(openssl_decrypt($id_usuario, METHODENCRIPT, KEY)));
            $unidad_medica_id = intval($_POST['unidad_medica_id']);

            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            $unidad_medica_usuarios_model = new UnidadMedicaUsuariosModel;
            $unidad_medica_usuarios_model->setUsuario_id($id_usuario);
            $unidad_medica_usuarios_model->setActivo($activo);
            $unidad_medica_usuarios_model->setUnidad_medica_id($unidad_medica_id);

            /*-------------------------------------------
            [ Elimina el Registro seleccionado. ]*/
            $response = $unidad_medica_usuarios_model->deleteUsuario($unidad_medica_usuarios_model, $usuario_id_register);

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $respuesta = array(
                    'respuesta' => 'ok',
                    'mostrar_mensaje' => true,
                    'tiempo' => 4000,
                    'mensaje' => 'Estatus actualizado exitosamente.'
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error',
                    'mostrar_mensaje' => true,
                    'tiempo' => 4000,
                    'mensaje' => 'Error al actualizar el estatus del registro, intente nuevamente.'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $respuesta = array(
                'respuesta' => 'error',
                'mostrar_mensaje' => true,
                'tiempo' => 4000,
                'mensaje' => 'Error al actualizar el estatus del registro, intente nuevamente.'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($respuesta, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Guardar/Actualizar tema de Usuario
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function setTheme()
    {

        try {

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $theme = strclean($_POST['theme']);
            $user_id = $this->session->get('usuario_id');

            /*-------------------------------------------
            [ Se aignan las variables al Modelo ]*/
            $usuario_model = new UsuariosModel;
            $usuario_model->setTheme($theme);
            $usuario_model->setId($user_id);

            /*-------------------------------------------
            [ Actualizar Registro ] */
            $response = $this->model->updateTheme($usuario_model);
            if ($response == false) {
                die(json_encode(getResponse('Error al crear el registro'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Evalúa respuesta  ]*/
            if ($response == true) {
                $this->session->add('theme', $theme);
                $arrResponse = getResponse('Registro actualizado exitosamente', 'ok', false);
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            die(json_encode(getResponse('Error al crear el registro'), JSON_UNESCAPED_UNICODE));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene el tema del Usuario.
     * 
     * @return string.
     */
    public function getTheme(): string
    {

        try {
            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $theme = $this->session->get('theme');
        } catch (\Throwable $th) {
            $theme = "";
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die($theme);
    }
}
