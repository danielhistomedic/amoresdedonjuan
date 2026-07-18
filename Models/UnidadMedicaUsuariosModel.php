<?php

/**
 * Clase UnidadMedicaUsuariosModel
 */
class UnidadMedicaUsuariosModel extends Mysql
{

    private $usuario_id;
    private $unidad_medica_id;
    private $titular;
    private $activo;
    private $rol_id;

    // DROP TABLE IF EXISTS `histocli_sys`.`unidad_medica_usuarios`;
    // CREATE TABLE  `histocli_sys`.`unidad_medica_usuarios` (
    //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    //   `usuario_id` int(10) unsigned NOT NULL COMMENT 'FK usuarios',
    //   `unidad_medica_id` int(10) unsigned NOT NULL COMMENT 'FK unidades_medicas',
    //   `titular` int(10) unsigned NOT NULL COMMENT '1 = si, 0 = no',
    //   `activo` int(10) unsigned NOT NULL,
    //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
    //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
    //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
    //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
    //   `rol_id` int(10) unsigned NOT NULL,
    //   PRIMARY KEY (`id`)
    // ) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Usuarios - Unidades Medicas';



    /**
     * Método Constructor de UnidadMedicaUsuariosModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Obtiene total de unidades Medicas a las que pertenece con todos sus datos
     * 
     * @param int $usuario_id
     * Identificador de Usuario
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function getUnidadesMedicasUsuario(int $usuario_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "umusr.unidad_medica_id, ";
            $sql .= "umusr.titular, ";
            $sql .= "umusr.activo, ";
            $sql .= "umusr.rol_id, ";
            $sql .= "rol.name as rol, ";
            $sql .= "um.nombre_unidad, ";
            $sql .= "um.tipo_licencia_id, ";
            $sql .= "um.domicilio_id, ";
            $sql .= "um.email_contacto_unidadmedica, ";
            $sql .= "um.estatus_licencia_id, ";
            $sql .= "um.telefono_unidadmedica, ";
            $sql .= "um.logo, ";
            $sql .= "um.fecha_limite_prueba ";
            $sql .= "FROM unidad_medica_usuarios umusr ";
            $sql .= "INNER JOIN unidades_medicas um ON (um.id = umusr.unidad_medica_id) ";
            $sql .= "INNER JOIN roles rol (rol.id = umusr.rol_id) ";
            $sql .= "WHERE ";
            $sql .= "umusr.usuario_id = :usuario_id ";

            /*-------------------------------------------
            [ Parametros condicionales ]*/
            $arr_values = [
                'usuario_id' => $usuario_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }



    /**
     * Obtiene total de unidades Medicas a las que pertenece un usuario determinado
     * 
     * @param int $usuario_id
     * Identificador de Usuario
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function getUnidadesMedicasUsuarioLogin(int $usuario_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "umusr.unidad_medica_id, umusr.activo ";
            $sql .= "FROM unidad_medica_usuarios umusr ";
            $sql .= "WHERE ";
            $sql .= "umusr.usuario_id = :usuario_id ";

            /*-------------------------------------------
            [ Parametros condicionales ]*/
            $arr_values = [
                'usuario_id' => $usuario_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }


    /**
     * Valida si el usuario ya existe en la unidad que se desea asignar.
     * 
     * @param int $usuario_id
     * Identificador de Usuario
     * 
     * @return bool $response
     * * true = Existe Usuario en la Unidad Medica
     * * false = No existe
     */
    public function validaExisteUsuarioUnidadMedica(int $usuario_id, int $unidad_medica_id): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "umusr.unidad_medica_id ";
            $sql .= "FROM unidad_medica_usuarios umusr ";
            $sql .= "WHERE ";
            $sql .= "umusr.unidad_medica_id = :unidad_medica_id and ";
            $sql .= "umusr.usuario_id = :usuario_id ";

            /*-------------------------------------------
            [ Parametros condicionales ]*/
            $arr_values = [
                'unidad_medica_id' => $unidad_medica_id,
                'usuario_id' => $usuario_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
            if (count($arrResponse) > 0) {
                $response = true;
            }
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $response;
    }


    /**
     * Subrutina dentro de insertUsuario para crear la relación de Usuario con Unidad Medica
     * 
     * @param object UnidadMedicaUsuariosModel $model
     * Envío del modelo por valor con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function setUsuarioUnidadMedica(UnidadMedicaUsuariosModel $model, int $usuario_id_register): bool
    {


        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Datos Generales de Usuario ]*/
            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "INSERT INTO unidad_medica_usuarios SET ";
            $sql .= "usuario_id = :usuario_id, ";
            $sql .= "unidad_medica_id = :unidad_medica_id, ";
            $sql .= "activo = :activo, ";
            $sql .= "titular = :titular, ";
            $sql .= "rol_id = :rol_id, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";

            /*-------------------------------------------
            [ Datos a insertar ]*/
            $arrData = [
                'usuario_id' => $model->getUsuario_id(),
                'titular' => $model->getTitular(),
                'activo' => $model->getActivo(),
                'rol_id' => $model->getRol_id(),
                'unidad_medica_id' => $model->getUnidad_medica_id(),
                'usuario_id_register' => $usuario_id_register
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo insert de MySQL ]*/
            $this->insert($sql, $arrData);

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
     * Actualiza el estatus a inactivo de un Usuario determinado para uan Unidad determinada
     * 
     * @param object UnidadMedicaUsuariosModel $model
     * Envío del modelo por valor, que contine la información a actualizar y 
     * los parámetros condicionales para realizar el update.
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function deleteUsuario(UnidadMedicaUsuariosModel $model, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE unidad_medica_usuarios SET ";
            $sql .= "activo = :activo, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE  ";
            $sql .= "usuario_id = :usuario_id and ";
            $sql .= "unidad_medica_id = :unidad_medica_id";

            /*-------------------------------------------
            [ Parámetros condicionales para realizar el update ]*/
            $arrData = [
                'usuario_id' => $model->getUsuario_id(),
                'unidad_medica_id' => $model->getUnidad_medica_id(),
                'activo' => $model->getActivo(),
                'usuario_id_register' => $usuario_id_register
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo delete de MySQL ]*/
            $response = $this->delete($sql, $arrData);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }


    //*==================================================================
    // [ GETTERS & SETTERS ]*/


    /**
     * Get the value of usuario_id
     */
    public function getUsuario_id()
    {
        return $this->usuario_id;
    }

    /**
     * Set the value of usuario_id
     *
     * @return  self
     */
    public function setUsuario_id($usuario_id)
    {
        $this->usuario_id = $usuario_id;

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
}
