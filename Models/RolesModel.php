<?php

/**
 * Clase RolesModel
 */
class RolesModel extends Mysql
{

    private $id;
    private $name;
    private $descripcion;
    private $unidad_medica_id;
    private $activo;

    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;


    /**
     * Método Constructor de RolesModel.
     * Inicializa Mysql::__construct
     * 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista del catálogo de roles.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectRoles(): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "rol.*, us.usuario ";
            $sql .= "FROM roles rol ";
            $sql .= "INNER JOIN usuarios us ON (us.id = rol.usuario_id_updated) ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

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
     * Obtiene datos de un rol determinado.
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectRol(int $rol_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "rol.*, usr.usuario ";
            $sql .= "FROM roles rol ";
            $sql .= "INNER JOIN usuarios usr ON (usr.id = rol.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "rol.id = :rol_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'rol_id' => $rol_id
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
     * Valida para evitar registros nuevos duplicados
     * 
     * @return bool $response
     * * true indica si el rol ya existe.
     * * false en caso de que no exista.
     * 
     */
    public function validInsertExistRol(): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT * FROM roles WHERE name = :name and unidad_medica_id IN(1, :unidad_medica_id)";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'name' => $this->getName(),
                'unidad_medica_id' => $this->getUnidad_medica_id()
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
    public function validUpdateExistRol(): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT * FROM roles WHERE name = :name and id != :id and unidad_medica_id IN(1, :unidad_medica_id)";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'name' => $this->getName(),
                'id' => $this->getId(),
                'unidad_medica_id' => $this->getUnidad_medica_id()
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
     * Guardar datos del Nuevo Rol.
     * 
     * @param model &$model
     * Envío del modelo \RolesModel por referencia, para asiganr el valor lastInsertId al modelo.
     *
     * @param int $usuario_id
     * Usuario que realiza el registro.
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function insertRol(RolesModel &$model, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "INSERT INTO roles SET ";
            $sql .= "name = :name, ";
            $sql .= "descripcion = :descripcion, ";
            $sql .= "unidad_medica_id = :unidad_medica_id, ";
            $sql .= "activo = 1, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";

            /*-------------------------------------------
            [ Datos a insertar ]*/
            $arrData = [
                'name' => $model->getName(),
                'descripcion' => $model->getDescripcion(),
                'unidad_medica_id' => $model->getUnidad_medica_id(),
                'usuario_id_register' => $usuario_id_register
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo insert de MySQL ]*/
            $lastInsertId = $this->insert($sql, $arrData);

            /*-------------------------------------------
            [ Asigna por referencia el valor del id insertado ]*/
            $model->getId($lastInsertId);

            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Actualiza datos da un Rol determinado.
     * 
     * @param object $model
     * Envío del modelo por valor, que contine la información a actualizar y 
     * los parámetros condicionales para realizar el update.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro.
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function updateRol(RolesModel $model, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE roles SET ";
            $sql .= "name = :name, ";
            $sql .= "descripcion = :descripcion, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE  ";
            $sql .= "id = :id ";

            /*-------------------------------------------
            [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
            $arrData = [
                'id' => $model->id,
                'name' => $model->name,
                'descripcion' => $model->descripcion,
                'usuario_id_register' => $usuario_id_register
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo insert de MySQL ]*/
            $response = $this->update($sql, $arrData);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Actualiza el estatus activo/inactivo de un Rol determinado.
     * 
     * @param object $model
     * Envío del modelo por valor, que contine la información a actualizar y 
     * los parámetros condicionales para realizar el update.
     *
     * 
     * @param int $usuario_id_register
     * Identificador de usuario que realiza el registro
     * 
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function updateEstatusRol(RolesModel $model, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE roles SET ";
            $sql .= "activo = :activo, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE  ";
            $sql .= "id = :id ";

            /*-------------------------------------------
            [ Parámetros condicionales para realizar el update ]*/
            $arrData = [
                'id' => $model->getId(),
                'activo' => $model->getActivo(),
                'usuario_id_register' => $usuario_id_register
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo delete de MySQL ]*/
            $response = $this->delete($sql, $arrData);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
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
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

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
}
