<?php

/**
 * Clase UsuariosModel
 */
class UsuariosModel extends Mysql
{

    /*-------------------------------------------
    [ table usuarios ]*/
    private $id;
    private $usuario;
    private $pass;
    private $activo;
    private $theme;
    private $token;
    private $token_created;
    private $origen_id;
    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;


    /*-------------------------------------------
    [ table usuarios_datos_generales ]*/
    private $nombre;
    private $paterno;
    private $materno;
    private $email;
    private $telefono;
    private $cedula;
    private $sexo_id;
    private $pais_id;
    private $usuario_domicilio_id;
    private $titulo;
    private $tipo_usuario_id;
    private $escuela;
    private $especialidad_id;
    private $residente_id;

    /*-------------------------------------------
    [ table usuarios_especialidad ]*/
    private $especialidades = [];


    /*-------------------------------------------
    [ table domicilios ]*/
    private $domicilios = [];


    /*-------------------------------------------
    [ table unidades_medicas ]*/
    private $nombre_unidad;
    private $tipo_licencia_id;
    private $unidad_medica_domicilio_id;
    private $email_contacto_unidadmedica;
    private $estatus_licencia_id;
    private $telefono_unidadmedica;
    private $fecha_limite_prueba;
    private $logo;


    /*-------------------------------------------
    [ table unidad_medica_usuarios ]*/
    private $unidades_medicas = [];
    private $unidad_medica_id;
    private $titular;
    private $rol_id;


    /**
     * Método Constructor de UsuariosModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista de Usuarios para llenar DataTable o Selects
     * 
     * @return array $arrResponse
     * * Retorna array de tipo:
     *   fetchAll(PDO::FETCH_ASSOC): returns an array containing all of the remaining rows in the result set. 
     * 
     */
    public function selectUsuarios(): array
    {

        try {


            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $session = new Session;
            $unidad_medica_id = $session->get('unidad_medica_id');

            $sql = "SELECT ";
            $sql .= "us.id,  ";
            $sql .= "us.usuario,  ";
            $sql .= "usr_datgen.nombre, ";
            $sql .= "usr_datgen.paterno, ";
            $sql .= "usr_datgen.materno, ";
            $sql .= "rol.name as rol, ";
            $sql .= "usr_datgen.email, ";
            $sql .= "usr_datgen.telefono, ";
            $sql .= "umusr.activo, ";
            $sql .= "umusr.titular, ";
            $sql .= "umusr.unidad_medica_id, ";
            $sql .= "um.nombre_unidad, ";
            $sql .= "us.updated_at, ";
            $sql .= "usr.usuario as usuario_register ";
            $sql .= "FROM usuarios us ";
            $sql .= "INNER JOIN usuarios_datos_generales usr_datgen ON (usr_datgen.usuario_id = us.id) ";
            $sql .= "INNER JOIN unidad_medica_usuarios umusr ON (umusr.usuario_id = us.id) ";
            $sql .= "INNER JOIN unidades_medicas um ON (um.id = umusr.unidad_medica_id) ";
            $sql .= "INNER JOIN roles rol ON (rol.id = umusr.rol_id) ";
            $sql .= "INNER JOIN usuarios usr ON (usr.id = us.usuario_id_updated) ";
            $sql .= "WHERE umusr.unidad_medica_id = :unidad_medica_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                "unidad_medica_id" => $unidad_medica_id
            ];


            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene datos de un Usuario determinado.
     * 
     * @param int $usuario_id
     * Identificador de usuario que se desa obtener
     * 
     * 
     * @return array $arrResponse
     * * Array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * * Retorna array de tipo:
     *   fetch(PDO::FETCH_ASSOC): returns an array indexed by column name as returned in your result set
     * 
     */
    public function selectUsuario(int $usuario_id, int $unidad_medica_id): array
    {

        try {

            $arrResponse = array();

            getLoggerSystem('selectUsuario  usuario_id = ' . $usuario_id);
            getLoggerSystem('selectUsuario  unidad_medica_id = ' . $unidad_medica_id);

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "datgen.nombre, datgen.paterno, datgen.materno, datgen.email, datgen.telefono, datgen.cedula, datgen.sexo_id, datgen.domicilio_id, datgen.pais_id, ";
            $sql .= "datgen.escuela, datgen.titulo, ";
            $sql .= "usr.usuario as usuario_register, rol.name as rol, ";
            $sql .= "us.usuario, datgen.tipo_usuario_id, umed.fecha_limite_prueba, us.updated_at as usuario_updated_at, ";
            $sql .= "us.origen_id, us.theme, umusr.activo, ";
            $sql .= "sex.sexo, umed.nombre_unidad, umed.tipo_licencia_id, umed.estatus_licencia_id, umed.domicilio_id as unidad_medica_domicilio_id, ";
            $sql .= "umed.email_contacto_unidadmedica, umed.telefono_unidadmedica, umed.logo, ";
            $sql .= "umusr.titular, umusr.rol_id, umusr.unidad_medica_id ";
            $sql .= "FROM usuarios us ";
            $sql .= "INNER JOIN usuarios usr ON (usr.id = us.usuario_id_updated) ";
            $sql .= "INNER JOIN usuarios_datos_generales datgen ON (datgen.usuario_id = us.id) ";
            $sql .= "INNER JOIN unidades_medicas umed ON (umed.id = :unidad_medica_id) ";
            $sql .= "INNER JOIN unidad_medica_usuarios umusr ON (umusr.usuario_id = us.id and umusr.unidad_medica_id = :unidad_medica_id) ";
            $sql .= "INNER JOIN roles rol ON (rol.id = umusr.rol_id) ";
            $sql .= "INNER JOIN sexo sex ON (sex.id = datgen.sexo_id) ";
            $sql .= "WHERE ";
            $sql .= "us.id = :usuario_id";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'usuario_id' => $usuario_id,
                'unidad_medica_id' => $unidad_medica_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene datos de un Usuario determinado.
     * 
     * @param string $user
     * Identificador de usuario que se desa obtener
     * 
     * 
     * @return array $arrResponse
     * * Array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * * Retorna array de tipo:
     *   fetch(PDO::FETCH_ASSOC): returns an array indexed by column name as returned in your result set
     * 
     */
    public function selectUsuarioFromUser(string $user, int $unidad_medica_id): array
    {

        try {

            $arrResponse = array();


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "datgen.nombre, datgen.paterno, datgen.materno, datgen.email, datgen.telefono, datgen.cedula, datgen.sexo_id, datgen.domicilio_id, datgen.pais_id, ";
            $sql .= "datgen.escuela, esp.especialidad, datgen.titulo, ";
            $sql .= "usr.usuario as usuario_register, rol.name as rol, ";
            $sql .= "us.id, us.usuario, datgen.tipo_usuario_id, umed.fecha_limite_prueba, us.updated_at as usuario_updated_at, ";
            $sql .= "us.origen_id, us.theme, umusr.activo, ";
            $sql .= "sex.sexo, umed.nombre_unidad, umed.tipo_licencia_id, umed.estatus_licencia_id, umed.domicilio_id as unidad_medica_domicilio_id, ";
            $sql .= "umed.email_contacto_unidadmedica, umed.telefono_unidadmedica, umed.logo, ";
            $sql .= "umusr.titular, umusr.rol_id, umusr.unidad_medica_id, ";
            $sql .= "tipo_lic.tipo, tipo_lic.front_end ";
            $sql .= "FROM usuarios us ";
            $sql .= "INNER JOIN usuarios usr ON (usr.id = us.usuario_id_updated) ";
            $sql .= "INNER JOIN usuarios_datos_generales datgen ON (datgen.usuario_id = us.id) ";
            $sql .= "INNER JOIN unidades_medicas umed ON (umed.id = :unidad_medica_id) ";
            $sql .= "INNER JOIN unidad_medica_usuarios umusr ON (umusr.usuario_id = us.id and umusr.unidad_medica_id = :unidad_medica_id) ";
            $sql .= "INNER JOIN roles rol ON (rol.id = umusr.rol_id) ";
            $sql .= "INNER JOIN sexo sex ON (sex.id = datgen.sexo_id) ";
            $sql .= "LEFT JOIN especialidades esp ON (esp.id = datgen.especialidad_id) ";
            $sql .= "INNER JOIN tipo_licencia tipo_lic ON (tipo_lic.id = umed.tipo_licencia_id) ";
            $sql .= "WHERE ";
            $sql .= "us.usuario = :user";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'user' => $user,
                'unidad_medica_id' => $unidad_medica_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene datos de un Usuario determinado.
     * 
     * @param int $residente_id
     * Identificador de usuario que se desa obtener
     * 
     * 
     * @return array $arrResponse
     * * Array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * * Retorna array de tipo:
     *   fetch(PDO::FETCH_ASSOC): returns an array indexed by column name as returned in your result set
     * 
     */
    public function selectUsuarioFromResidenteId(int $residente_id, int $unidad_medica_id): array
    {

        try {

            $arrResponse = array();


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "datgen.nombre, datgen.paterno, datgen.materno, datgen.email, datgen.telefono, datgen.cedula, datgen.sexo_id, datgen.domicilio_id, datgen.pais_id, ";
            $sql .= "datgen.escuela, esp.especialidad, datgen.titulo, ";
            $sql .= "usr.usuario as usuario_register, rol.name as rol, ";
            $sql .= "us.id, us.usuario, datgen.tipo_usuario_id, umed.fecha_limite_prueba, us.updated_at as usuario_updated_at, ";
            $sql .= "us.origen_id, us.theme, umusr.activo, ";
            $sql .= "sex.sexo, umed.nombre_unidad, umed.tipo_licencia_id, umed.estatus_licencia_id, umed.domicilio_id as unidad_medica_domicilio_id, ";
            $sql .= "umed.email_contacto_unidadmedica, umed.telefono_unidadmedica, umed.logo, ";
            $sql .= "umusr.titular, umusr.rol_id, umusr.unidad_medica_id, ";
            $sql .= "tipo_lic.tipo, tipo_lic.front_end ";
            $sql .= "FROM usuarios us ";
            $sql .= "INNER JOIN usuarios usr ON (usr.id = us.usuario_id_updated) ";
            $sql .= "INNER JOIN usuarios_datos_generales datgen ON (datgen.usuario_id = us.id) ";
            $sql .= "INNER JOIN unidades_medicas umed ON (umed.id = :unidad_medica_id) ";
            $sql .= "INNER JOIN unidad_medica_usuarios umusr ON (umusr.usuario_id = us.id and umusr.unidad_medica_id = :unidad_medica_id) ";
            $sql .= "INNER JOIN roles rol ON (rol.id = umusr.rol_id) ";
            $sql .= "INNER JOIN sexo sex ON (sex.id = datgen.sexo_id) ";
            $sql .= "LEFT JOIN especialidades esp ON (esp.id = datgen.especialidad_id) ";
            $sql .= "INNER JOIN tipo_licencia tipo_lic ON (tipo_lic.id = umed.tipo_licencia_id) ";
            $sql .= "WHERE ";
            $sql .= "datgen.residente_id = :residente_id";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'residente_id' => $residente_id,
                'unidad_medica_id' => $unidad_medica_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene lista de especialidades registradas de un Usuario determinado.
     * 
     * @param int $usuario_id
     * Identificador de Usuario
     * 
     * @return array $arrResponse
     * * Array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * * Retorna array de tipo:
     *   fetch(PDO::FETCH_ASSOC): returns an array indexed by column name as returned in your result set
     * 
     */
    public function selectUsuarioEspecialidad(int $usuario_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= " usr_esp.cedula, usr_esp.escuela, cat_esp.especialidad ";
            $sql .= "FROM usuarios_especialidad usr_esp ";
            $sql .= "INNER JOIN especialidades cat_esp ON (cat_esp.id = usr_esp.especialidad_id) ";
            $sql .= "WHERE ";
            $sql .= "usr_esp.usuario_id = :usuario_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'usuario_id' => $usuario_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error('function: selectUsuarioEspecialidad, ' . getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Valida para evitar registros nuevos duplicados
     * 
     * @param 
     * 
     * @return bool $response
     * * true indica si el rol ya existe.
     * * false en caso de que no exista.
     * 
     */
    public function validInsertExistUsuario(string $usuario): bool
    {
        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT * FROM usuarios WHERE usuario = :usuario ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'usuario' => $usuario
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            $response = !empty($arrResponse) ? true : false;
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Validación para evitar registros existentes se puedan duplicar
     * 
     * @return bool $response
     * * true indica si el rol ya existe.
     * * false en caso de que no exista.
     * 
     */
    public function validUpdateExistUsuario(string $usuario, int $id): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT * FROM usuarios WHERE usuario = :usuario and id != :id";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'usuario' => $usuario,
                'id' => $id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            $response = !empty($arrResponse) ? true : false;
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Guardar datos del Nuevo Usuario.
     * 
     * @param object &$model
     * Envío del modelo por referencia, para asiganr el valor lastInsertId al modelo.
     * 
     * @param int $unidad_medica_id
     * Identificador de Unidad Medica a la que se asigan el nuevo usuario
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function insertUsuario(UsuariosModel &$model, int $unidad_medica_id, int $usuario_id_register): bool
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
            [ Registrar Relación Usuarios - Unidad Medica ]*/
            $this->usuarioCreateRelacionUsuarioUnidadMedica($model, $unidad_medica_id, $usuario_id_register);

            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
        } catch (\Throwable $th) {

            /*-------------------------------------------
            [ RollBack ]*/
            $this->getConexion()->rollBack();
            getLoggerSystem()->error(getMensajeError($th));
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
        $sql .= "theme = :theme, ";
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
            'theme' => $model->getTheme(),
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
        $sql .= "pais_id = :pais_id, ";
        $sql .= "tipo_usuario_id = :tipo_usuario_id, ";
        $sql .= "residente_id = :residente_id, ";
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
            'tipo_usuario_id' => $model->getTipo_usuario_id(),
            'residente_id' => $model->getResidente_id(),
            'telefono' => $model->getTelefono(),
            'sexo_id' => $model->getSexo_id(),
            'pais_id' => $model->getPais_id(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $this->insert($sql, $arrData);
    }

    /**
     * Subrutina dentro de insertUsuario/updateUsuario para crear las especialidades seleccionadas por el Usuario
     * 
     * @param \UsuariosModel &$model
     * Envío del modelo por referencia, para asignar el valor lastInsertId al modelo.
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
    public function usuarioCreateRelacionUsuarioUnidadMedica(UsuariosModel $model, int $unidad_medica_id, int $usuario_id_register): void
    {



        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO unidad_medica_usuarios SET ";
        $sql .= "usuario_id = :usuario_id, ";
        $sql .= "unidad_medica_id = :unidad_medica_id, ";
        $sql .= "activo = 1, ";
        $sql .= "titular = :titular, ";
        $sql .= "rol_id = :rol_id, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'usuario_id' => $model->getId(),
            'titular' => $model->getTitular(),
            'rol_id' => $model->getRol_id(),
            'unidad_medica_id' => $unidad_medica_id,
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $this->insert($sql, $arrData);
    }

    /**
     * Actualiza datos da un Usuario determinado.
     * 
     * @param int $usuario_id_register
     * Identificador de usuario que realizar el registro
     * 
     * @param object UsuariosModel &$model
     * Envío del modelo por valor, que contine la información a actualizar y 
     * los parámetros condicionales para realizar el update.
     *
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function updateUsuario(UsuariosModel &$model, int $unidad_medica_id, int $usuario_id_register, bool $reset_usuario_portal = false): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Actualizar Datos Generales de Usuario ]*/
            if ($this->usuarioUpdateDatosGenerales($model, $usuario_id_register)) {

                /*-------------------------------------------
                [ Actualizar Datos Generales de Usuario ]*/
                if ($this->usuarioUpdateRelacionUsuarioUnidadMedica($model, $unidad_medica_id, $usuario_id_register)) {

                    /*-------------------------------------------
                    [ Actualizar Contraseña en caso de requerido ]*/
                    $pass = $model->getPass();
                    if ($pass != '') {
                        if ($this->usuarioUpdatePassword($model, $usuario_id_register, $reset_usuario_portal)) {
                            $this->getConexion()->commit();
                            return true;
                        };
                    } else {
                        $this->getConexion()->commit();
                        return true;
                    }
                }
            }

            $this->getConexion()->rollBack();
        } catch (\Throwable $th) {
            /*-------------------------------------------
            [ RollBack ]*/
            $this->getConexion()->rollBack();
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Subrutina dentro de updateUsuario para actualizar datos generales de Uusario determinado
     * 
     * @param int $usuario_id_register
     * Identificador de usuario que realizar el registro
     * 
     * @param object UsuariosModel $model
     * Envío del modelo por valor, que contine la información a actualizar y 
     * los parámetros condicionales para realizar el update.
     *
     */
    public function usuarioUpdateDatosGenerales(UsuariosModel $model,  int $usuario_id_register): bool
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE usuarios_datos_generales SET ";
        $sql .= "nombre = :nombre, ";
        $sql .= "paterno = :paterno, ";
        $sql .= "materno = :materno, ";
        $sql .= "email = :email, ";
        $sql .= "telefono = :telefono, ";
        $sql .= "sexo_id = :sexo_id, ";
        $sql .= "residente_id = :residente_id, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "usuario_id = :usuario_id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'usuario_id' => $model->getId(),
            'nombre' => $model->getNombre(),
            'paterno' => $model->getPaterno(),
            'materno' => $model->getMaterno(),
            'email' => $model->getEmail(),
            'telefono' => $model->getTelefono(),
            'residente_id' => $model->getResidente_id(),
            'sexo_id' => $model->getSexo_id(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);

        return $response;
    }

    /**
     * Subrutina dentro de updateUsuario para actualizar la relación de Usuario con Unidad Medica
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
    public function usuarioUpdateRelacionUsuarioUnidadMedica(UsuariosModel $model, int $unidad_medica_id, int $usuario_id_register): bool
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE unidad_medica_usuarios SET ";
        $sql .= "rol_id = :rol_id, ";
        $sql .= "activo = 1, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "unidad_medica_id = :unidad_medica_id and ";
        $sql .= "usuario_id = :usuario_id ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'usuario_id' => $model->getId(),
            'rol_id' => $model->getRol_id(),
            'unidad_medica_id' => $unidad_medica_id,
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $response = $this->update($sql, $arrData);


        return $response;
    }

    /**
     * Subrutina dentro de updateUsuario para actualizar constraseña
     * 
     * @param object UsuariosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function usuarioUpdatePassword(UsuariosModel &$model,  int $usuario_id_register, bool $reset_usuario_portal = false): bool
    {

        $response = false;

        $usuario = '';
        if ($reset_usuario_portal) {
            $usuario = "usuario = '" . $model->getEmail() . "', ";
        }

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE usuarios SET ";
        $sql .= "pass = :pass, ";
        $sql .= $usuario;
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "where ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'id' => $model->getId(),
            'pass' => $model->getPass(),
            'usuario_id_register' => $usuario_id_register
        ];


        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $response = $this->update($sql, $arrData);

        return $response;
    }


    /**
     * Subrutina dentro de updateUsuario para eliminar las especialidades registradas de un Usuario determinado
     * 
     */
    public function usuarioDeleteEspecialidad(int $usuario_id)
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "DELETE FROM usuarios_especialidad  ";
        $sql .= "WHERE ";
        $sql .= "usuario_id = :usuario_id ";

        /*-------------------------------------------
        [ Parámetros condicionales para realizar el delete ]*/
        $arrData = [
            'usuario_id' => $usuario_id
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo delete de MySQL ]*/
        $response = $this->delete($sql, $arrData);
    }

    /**
     * Actualiza tema da un Usuario determinado.
     * 
     * @param \UsuariosModel &$model
     * Envío del modelo por valor, que contine la información a actualizar y 
     * los parámetros condicionales para realizar el update.
     *
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function updateTheme(UsuariosModel &$model): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Actualizar Datos de Usuario ]*/
            $this->themeUpdate($model);


            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
        } catch (\Throwable $th) {

            /*-------------------------------------------
            [ RollBack ]*/
            $this->getConexion()->rollBack();
            getLoggerSystem()->error(getMensajeError($th));
            $response = false;
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Subrutina dentro de updateUsuario para actualizar datos de Uusario determinado
     * 
     * @param \UsuariosModel &$model
     * Envío del modelo por valor, que contine la información a actualizar y 
     * los parámetros condicionales para realizar el update.
     *
     */
    public function themeUpdate(UsuariosModel &$model): void
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE usuarios SET ";
        $sql .= "theme = :theme ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $model->id,
            'theme' => $model->theme
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $this->update($sql, $arrData);
    }


    /**
     * Actulizar estatus de acceso al portal de residente
     *
     * @param int $unidad_medica_id
     * Identificador de Unidad Médica a la que pertencerá el usuario
     *     
     * @param int $estatus
     * Estatus del portal 0 = inactivo 1 = activo
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function updateEstatusPortalResidente(int $usuario_id, int $estatus, int $unidad_medica_id, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE unidad_medica_usuarios SET ";
            $sql .= "activo = :activo, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE ";
            $sql .= "unidad_medica_id = :unidad_medica_id and ";
            $sql .= "usuario_id = :usuario_id ";

            /*-------------------------------------------
            [ Datos a insertar ]*/
            $arrData = [
                'usuario_id' => $usuario_id,
                'activo' => $estatus,
                'unidad_medica_id' => $unidad_medica_id,
                'usuario_id_register' => $usuario_id_register
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo insert de MySQL ]*/
            $response = $this->update($sql, $arrData);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        return  $response;
    }



    //*==================================================================
    // [ GETTERS & SETTERS ]*/

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set the value of usuario
     *
     * @return  self
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get the value of pass
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Set the value of pass
     *
     * @return  self
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get the value of rol_id
     */
    public function getRol_id()
    {
        return $this->rol_id;
    }

    /**
     * Set the value of rol_id
     *
     * @return  self
     */
    public function setRol_id($rol_id)
    {
        $this->rol_id = $rol_id;

        return $this;
    }

    /**
     * Get the value of tipo_usuario_id
     */
    public function getTipo_usuario_id()
    {
        return $this->tipo_usuario_id;
    }

    /**
     * Set the value of tipo_usuario_id
     *
     * @return  self
     */
    public function setTipo_usuario_id($tipo_usuario_id)
    {
        $this->tipo_usuario_id = $tipo_usuario_id;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of usuario_id_created
     */
    public function getUsuario_id_created()
    {
        return $this->usuario_id_created;
    }

    /**
     * Set the value of usuario_id_created
     *
     * @return  self
     */
    public function setUsuario_id_created($usuario_id_created)
    {
        $this->usuario_id_created = $usuario_id_created;

        return $this;
    }

    /**
     * Get the value of updated_at
     */
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */
    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of usuario_id_updated
     */
    public function getUsuario_id_updated()
    {
        return $this->usuario_id_updated;
    }

    /**
     * Set the value of usuario_id_updated
     *
     * @return  self
     */
    public function setUsuario_id_updated($usuario_id_updated)
    {
        $this->usuario_id_updated = $usuario_id_updated;

        return $this;
    }

    /**
     * Get the value of especialidades
     */
    public function getEspecialidades()
    {
        return $this->especialidades;
    }

    /**
     * Set the value of especialidades
     *
     * @return  self
     */
    public function setEspecialidades($especialidades)
    {
        $this->especialidades = $especialidades;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of paterno
     */
    public function getPaterno()
    {
        return $this->paterno;
    }

    /**
     * Set the value of paterno
     *
     * @return  self
     */
    public function setPaterno($paterno)
    {
        $this->paterno = $paterno;

        return $this;
    }

    /**
     * Get the value of materno
     */
    public function getMaterno()
    {
        return $this->materno;
    }

    /**
     * Set the value of materno
     *
     * @return  self
     */
    public function setMaterno($materno)
    {
        $this->materno = $materno;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of telefono
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of cedula
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set the value of cedula
     *
     * @return  self
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get the value of sexo_id
     */
    public function getSexo_id()
    {
        return $this->sexo_id;
    }

    /**
     * Set the value of sexo_id
     *
     * @return  self
     */
    public function setSexo_id($sexo_id)
    {
        $this->sexo_id = $sexo_id;

        return $this;
    }

    /**
     * Get the value of domicilios
     */
    public function getDomicilios()
    {
        return $this->domicilios;
    }

    /**
     * Set the value of domicilios
     *
     * @return  self
     */
    public function setDomicilios($domicilios)
    {
        $this->domicilios = $domicilios;

        return $this;
    }

    /**
     * Get the value of fecha_limite_prueba
     */
    public function getFecha_limite_prueba()
    {
        return $this->fecha_limite_prueba;
    }

    /**
     * Set the value of fecha_limite_prueba
     *
     * @return  self
     */
    public function setFecha_limite_prueba($fecha_limite_prueba)
    {
        $this->fecha_limite_prueba = $fecha_limite_prueba;

        return $this;
    }

    /**
     * Get the value of titular
     */
    public function getTitular()
    {
        return $this->titular;
    }

    /**
     * Set the value of titular
     *
     * @return  self
     */
    public function setTitular($titular)
    {
        $this->titular = $titular;

        return $this;
    }

    /**
     * Get the value of theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set the value of theme
     *
     * @return  self
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get the value of escuela
     */
    public function getEscuela()
    {
        return $this->escuela;
    }

    /**
     * Set the value of escuela
     *
     * @return  self
     */
    public function setEscuela($escuela)
    {
        $this->escuela = $escuela;

        return $this;
    }

    /**
     * Get the value of especialidad_id
     */
    public function getEspecialidad_id()
    {
        return $this->especialidad_id;
    }

    /**
     * Set the value of especialidad_id
     *
     * @return  self
     */
    public function setEspecialidad_id($especialidad_id)
    {
        $this->especialidad_id = $especialidad_id;

        return $this;
    }

    /**
     * Get the value of pais_id
     */
    public function getPais_id()
    {
        return $this->pais_id;
    }

    /**
     * Set the value of pais_id
     *
     * @return  self
     */
    public function setPais_id($pais_id)
    {
        $this->pais_id = $pais_id;

        return $this;
    }

    /**
     * Get the value of activo
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set the value of activo
     *
     * @return  self
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get the value of unidades_medicas
     */
    public function getUnidades_medicas()
    {
        return $this->unidades_medicas;
    }

    /**
     * Set the value of unidades_medicas
     *
     * @return  self
     */
    public function setUnidades_medicas($unidades_medicas)
    {
        $this->unidades_medicas = $unidades_medicas;

        return $this;
    }

    /**
     * Get the value of origen_id
     */
    public function getOrigen_id()
    {
        return $this->origen_id;
    }

    /**
     * Set the value of origen_id
     *
     * @return  self
     */
    public function setOrigen_id($origen_id)
    {
        $this->origen_id = $origen_id;

        return $this;
    }

    /**
     * Get the value of tipo_licencia_id
     */
    public function getTipo_licencia_id()
    {
        return $this->tipo_licencia_id;
    }

    /**
     * Set the value of tipo_licencia_id
     *
     * @return  self
     */
    public function setTipo_licencia_id($tipo_licencia_id)
    {
        $this->tipo_licencia_id = $tipo_licencia_id;

        return $this;
    }

    /**
     * Get the value of titulo
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set the value of titulo
     *
     * @return  self
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get the value of token_created
     */
    public function getToken_created()
    {
        return $this->token_created;
    }

    /**
     * Set the value of token_created
     *
     * @return  self
     */
    public function setToken_created($token_created)
    {
        $this->token_created = $token_created;

        return $this;
    }

    /**
     * Get the value of nombre_unidad
     */
    public function getNombre_unidad()
    {
        return $this->nombre_unidad;
    }

    /**
     * Set the value of nombre_unidad
     *
     * @return  self
     */
    public function setNombre_unidad($nombre_unidad)
    {
        $this->nombre_unidad = $nombre_unidad;

        return $this;
    }

    /**
     * Get the value of estatus_licencia_id
     */
    public function getEstatus_licencia_id()
    {
        return $this->estatus_licencia_id;
    }

    /**
     * Set the value of estatus_licencia_id
     *
     * @return  self
     */
    public function setEstatus_licencia_id($estatus_licencia_id)
    {
        $this->estatus_licencia_id = $estatus_licencia_id;

        return $this;
    }

    /**
     * Get the value of telefono_unidadmedica
     */
    public function getTelefono_unidadmedica()
    {
        return $this->telefono_unidadmedica;
    }

    /**
     * Set the value of telefono_unidadmedica
     *
     * @return  self
     */
    public function setTelefono_unidadmedica($telefono_unidadmedica)
    {
        $this->telefono_unidadmedica = $telefono_unidadmedica;

        return $this;
    }

    /**
     * Get the value of email_contacto_unidadmedica
     */
    public function getEmail_contacto_unidadmedica()
    {
        return $this->email_contacto_unidadmedica;
    }

    /**
     * Set the value of email_contacto_unidadmedica
     *
     * @return  self
     */
    public function setEmail_contacto_unidadmedica($email_contacto_unidadmedica)
    {
        $this->email_contacto_unidadmedica = $email_contacto_unidadmedica;

        return $this;
    }

    /**
     * Get the value of usuario_domicilio_id
     */
    public function getUsuario_domicilio_id()
    {
        return $this->usuario_domicilio_id;
    }

    /**
     * Set the value of usuario_domicilio_id
     *
     * @return  self
     */
    public function setUsuario_domicilio_id($usuario_domicilio_id)
    {
        $this->usuario_domicilio_id = $usuario_domicilio_id;

        return $this;
    }

    /**
     * Get the value of unidad_medica_domicilio_id
     */
    public function getUnidad_medica_domicilio_id()
    {
        return $this->unidad_medica_domicilio_id;
    }

    /**
     * Set the value of unidad_medica_domicilio_id
     *
     * @return  self
     */
    public function setUnidad_medica_domicilio_id($unidad_medica_domicilio_id)
    {
        $this->unidad_medica_domicilio_id = $unidad_medica_domicilio_id;

        return $this;
    }

    /**
     * Get the value of logo
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set the value of logo
     *
     * @return  self
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get the value of unidad_medica_id
     */
    public function getUnidad_medica_id()
    {
        return $this->unidad_medica_id;
    }

    /**
     * Set the value of unidad_medica_id
     *
     * @return  self
     */
    public function setUnidad_medica_id($unidad_medica_id)
    {
        $this->unidad_medica_id = $unidad_medica_id;

        return $this;
    }

    /**
     * Get the value of residente_id
     */
    public function getResidente_id()
    {
        return $this->residente_id;
    }

    /**
     * Set the value of residente_id
     *
     * @return  self
     */
    public function setResidente_id($residente_id)
    {
        $this->residente_id = $residente_id;

        return $this;
    }
}
