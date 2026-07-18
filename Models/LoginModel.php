<?php

/**
 * Clase LoginModel
 */
class LoginModel extends Mysql
{

    /**
     * Método Constructor de LoginModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Valida los datos de usuario para el acceso al sistema.
     * 
     * @param object &$model
     * Envío del modelo por referencia, para validar el acceso al sistema.
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function loginUser(UsuariosModel &$model): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "Select id, theme ";
            $sql .= "from usuarios ";
            $sql .= "WHERE ";
            $sql .= "usuario = :usuario and ";
            $sql .= "pass = :pass and ";
            $sql .= "activo = 1";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arrData = [
                'usuario' => $model->getUsuario(),
                'pass' => $model->getPass()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arrData);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Valida si el usuario existe en base al email recibido y estatus activo
     * 
     * @param string $email
     * Email del usuario que se desa validar.
     * 
     * @return bool $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function validateResetPasswordUser(string $email): array
    {

        try {

            $arrResponse = array();

            //     DROP TABLE IF EXISTS `histocli_sys`.`usuarios_datos_generales`;
            // CREATE TABLE  `histocli_sys`.`usuarios_datos_generales` (
            //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            //   `usuario_id` int(10) unsigned NOT NULL,
            //   `nombre` varchar(95) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Nombre completo del usuario',
            //   `paterno` varchar(95) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Apellido paterno del usuario',
            //   `materno` varchar(45) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Apellido materno del usuario',
            //   `email` varchar(45) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'correo electronico del usuario',
            //   `telefono` varchar(10) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'telefono celular del usuario',
            //   `cedula` varchar(45) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'cedual profesional predeterminada para uso en recetas',
            //   `sexo_id` int(1) unsigned NOT NULL COMMENT 'sexo seleccionado',
            //   `domicilio_id` int(10) unsigned DEFAULT NULL,
            //   `pais_id` int(10) unsigned NOT NULL COMMENT 'Pais de origen del usuario',
            //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
            //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
            //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
            //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
            //   `escuela` varchar(145) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'escuela predeterminada para uso en recetas',
            //   `especialidad_id` int(10) unsigned DEFAULT NULL COMMENT 'especialidad predeterminada para uso en recetas',
            //   `titulo` varchar(10) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
            //   `tipo_usuario_id` int(10) unsigned DEFAULT NULL,
            //   PRIMARY KEY (`id`)
            // ) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Datos generales del usuario registrado';

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "us.id, datgen.nombre, datgen.paterno, datgen.materno  ";
            $sql .= "FROM usuarios us ";
            $sql .= "INNER JOIN usuarios_datos_generales datgen ON (datgen.usuario_id = us.id) ";
            $sql .= "WHERE ";
            $sql .= "us.usuario = :email and ";
            $sql .= "us.activo = 1";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'email' => $email
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }


    /**
     * Guarda el valor del token generado para un usuario determinado.
     * 
     * @param int $usuario_id
     * Identificador de Usuario
     * 
     * @param string $token
     * Valor de token asignado
     * 
     * @return bool $response
     * * true indica si el token se guardó exitosamente.
     * * false en caso de falla.
     * 
     */
    public function setTokenUser(int $usuario_id, string $token): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE usuarios SET ";
            $sql .= "token = :token, ";
            $sql .= "token_created = current_timestamp ";
            $sql .= "WHERE id = :usuario_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'token' => $token,
                'usuario_id' => $usuario_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo update de MySQL ]*/
            $response = $this->update($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }


    /**
     * Valida los datos de Usuario y token recibido y estatus activo
     * 
     * @param string $token
     * token temporal asignado para recuperar contraseña.
     *
     * 
     * @return array $arrResponse [Datos de Usuario]
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function validUserToken(string $email, string $token): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "us.id, us.token_created, datgen.nombre, datgen.paterno, datgen.materno  ";
            $sql .= "FROM usuarios us ";
            $sql .= "INNER JOIN usuarios_datos_generales datgen ON (datgen.usuario_id = us.id) ";
            $sql .= "WHERE ";
            $sql .= "us.usuario = :email and ";
            $sql .= "us.token = :token and ";
            $sql .= "us.activo = 1";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'email' => $email,
                'token' => $token
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }



    /**
     * Guarda el valor del password para un usuario determinado.
     * 
     * @param int $usuario_id
     * Identificador de Usuario
     * 
     * @param string $pass
     * Password del Usuario
     * 
     * @return bool $response
     * * true indica si el token se guardó exitosamente.
     * * false en caso de falla.
     * 
     */
    public function updatePassword(int $usuario_id, string $pass): bool
    {

        try {

            $response = false;


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE usuarios SET ";
            $sql .= "token = '', ";
            $sql .= "pass = :pass ";
            $sql .= "WHERE id = :usuario_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'pass' => $pass,
                'usuario_id' => $usuario_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo update de MySQL ]*/
            $response = $this->update($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Guardar datos del Nuevo Usuario.
     * 
     * @param \UsuariosModel &$model
     * Envío del modelo por referencia, para asiganr el valor lastInsertId al modelo.
     *
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */

    /**
     * Guardar datos del Nuevo Usuario.
     * 
     * @param object &$model
     * Envío del modelo por referencia, para asiganr el valor lastInsertId al modelo.
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function insertUsuario(UsuariosModel &$model,  int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Usuario ]*/
            $this->usuarioCreate($model, $usuario_id_register);

            /*-------------------------------------------
            [ Registrar Datos Generales de Usuario ]*/
            $this->usuarioCreateDatosGenerales($model, $usuario_id_register);

            /*-------------------------------------------
            [ Registrar Especialidades seleccionadas por el Médico ]*/
            $arr_especialidades = $model->getEspecialidades();
            $this->usuarioCreateEspecialidad($model->getId(), $arr_especialidades);

            /*-------------------------------------------
            [ Registrar Datos en Catlogo de Unidades Médicas ]*/
            $unidad_medica_id =  $this->usuarioCreateUnidadMedicaLogin($model, $usuario_id_register);

            /*-------------------------------------------
            [ Registrar Relación Usuarios - Unidad Medica ]*/
            $this->usuarioCreateRelacionUsuarioUnidadMedicaLogin($model, $unidad_medica_id, $usuario_id_register);


            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
        } catch (\Throwable $th) {

            /*-------------------------------------------
            [ RollBack ]*/
            $this->getConexion()->rollBack();
            $_logger = getLoggerSystem()->error(getMensajeError($th));
            $response = false;
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Subrutina dentro de insertUsuario para crear el Usuario
     * 
     * @param object UsuariosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function usuarioCreate(UsuariosModel &$model,  int $usuario_id_register): void
    {


        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO usuarios SET ";
        $sql .= "usuario = :usuario, ";
        $sql .= "pass = :pass, ";
        $sql .= "activo = :activo, ";
        $sql .= "origen_id = :origen_id, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";


        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'usuario' => $model->getUsuario(),
            'pass' => $model->getPass(),
            'activo' => $model->getActivo(),
            'origen_id' => $model->getOrigen_id(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $lastInsertId = $this->insert($sql, $arrData);

        /*-------------------------------------------
        [ Asigna por referencia el valor del id insertado ]*/
        $model->setId($lastInsertId);
    }

    /**
     * Subrutina dentro de insertUsuario para crear los datos generales del Usuario
     * 
     * @param object UsuariosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function usuarioCreateDatosGenerales(UsuariosModel &$model,  int $usuario_id_register): void
    {


        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO usuarios_datos_generales SET ";
        $sql .= "usuario_id = :usuario_id, ";
        $sql .= "nombre = :nombre, ";
        $sql .= "paterno = :paterno, ";
        $sql .= "materno = :materno, ";
        $sql .= "email = :email, ";
        $sql .= "telefono = :telefono, ";
        $sql .= "sexo_id = :sexo_id, ";
        $sql .= "titulo = :titulo, ";
        $sql .= "pais_id = :pais_id, ";
        $sql .= "tipo_usuario_id = :tipo_usuario_id, ";
        $sql .= "cedula = :cedula, ";
        $sql .= "escuela = :escuela, ";
        $sql .= "especialidad_id = :especialidad_id, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";


        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'usuario_id' => $model->getId(),
            'nombre' => $model->getNombre(),
            'paterno' => $model->getPaterno(),
            'materno' => $model->getMaterno(),
            'email' => $model->getEmail(),
            'telefono' => $model->getTelefono(),
            'tipo_usuario_id' => $model->getTipo_usuario_id(),
            'sexo_id' => $model->getSexo_id(),
            'titulo' => $model->getTitulo(),
            'pais_id' => $model->getPais_id(),
            'cedula' => $model->getCedula(),
            'escuela' => $model->getEscuela(),
            'especialidad_id' => $model->getEspecialidad_id(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $this->insert($sql, $arrData);
    }

    /**
     * Subrutina dentro de insertUsuario/updateUsuario para crear las especialidades seleccionadas por el Usuario
     * 
     * @param array $arrEspecialidad
     * Array con las especialidades que tiene el usuario medico
     *
     * @param int $usuario_id
     * Identificador de Usuario.
     *
     */
    public function usuarioCreateEspecialidad(int $usuario_id, array $arrEspecialidad)
    {

        // DROP TABLE IF EXISTS `histocli_sys`.`usuarios_especialidad`;
        // CREATE TABLE  `histocli_sys`.`usuarios_especialidad` (
        //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        //   `usuario_id` int(10) unsigned NOT NULL,
        //   `especialidad_id` int(10) unsigned NOT NULL,
        //   `cedula` varchar(10) COLLATE utf8mb4_unicode_520_ci NOT NULL,
        //   `escuela` varchar(105) COLLATE utf8mb4_unicode_520_ci NOT NULL,
        //   PRIMARY KEY (`id`)
        // ) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Especialidades de usuario registrado';

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO usuarios_especialidad SET ";
        $sql .= "usuario_id = :usuario_id, ";
        $sql .= "especialidad_id = :especialidad_id, ";
        $sql .= "cedula = :cedula, ";
        $sql .= "escuela = :escuela ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'usuario_id' => $usuario_id,
            'especialidad_id' => $arrEspecialidad['especialidad_id'],
            'escuela' => $arrEspecialidad['escuela'],
            'cedula' => $arrEspecialidad['cedula']
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $this->insert($sql, $arrData);
    }

    /**
     * Subrutina dentro de insertUsuario para crear la Unidad Medica (Consultorio del Médico)
     * 
     * @param object UsuariosModel $model
     * Envío del modelo por valor con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function usuarioCreateUnidadMedicaLogin(UsuariosModel &$model,  int $usuario_id_register): int
    {


        $unidad_medica_id = 0;

        /*-------------------------------------------
        [ Asignar nombre de la unidad ]*/
        $nombre_unidad = "CONSULTORIO " . $model->getTitulo() . ' ' . $model->getNombre() . ' ' . $model->getPaterno() . ' ' . $model->getMaterno();

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO unidades_medicas SET ";
        $sql .= "nombre_unidad = :nombre_unidad, ";
        $sql .= "tipo_licencia_id = :tipo_licencia_id, ";
        $sql .= "fecha_limite_prueba = :fecha_limite_prueba, ";
        $sql .= "estatus_licencia_id = 3, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'nombre_unidad' => $nombre_unidad,
            'tipo_licencia_id' => $model->getTipo_licencia_id(),
            'fecha_limite_prueba' => $model->getFecha_limite_prueba(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $unidad_medica_id =  $this->insert($sql, $arrData);


        return $unidad_medica_id;
    }

    /**
     * Subrutina dentro de insertUsuario para crear la relación de Usuario con Unidad Medica
     * 
     * @param object UsuariosModel $model
     * Envío del modelo por valor con los datos requeridos para el insert
     *
     * @param int $unidad_medica_id
     * Identificador de Unidad Médica a la que pertencerá el usuario
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function usuarioCreateRelacionUsuarioUnidadMedicaLogin(UsuariosModel &$model,  int $unidad_medica_id, int $usuario_id_register): void
    {


        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO unidad_medica_usuarios SET ";
        $sql .= "usuario_id = :usuario_id, ";
        $sql .= "unidad_medica_id = :unidad_medica_id, ";
        $sql .= "rol_id = :rol_id, ";
        $sql .= "activo = 1, ";
        $sql .= "titular = :titular, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'usuario_id' => $model->getId(),
            'titular' => $model->getTitular(),
            'unidad_medica_id' => $unidad_medica_id,
            'rol_id' => $model->getRol_id(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $this->insert($sql, $arrData);
    }
}
