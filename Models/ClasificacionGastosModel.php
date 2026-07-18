<?php

/**
 * Clase ClasificacionGastosModel
 */
class ClasificacionGastosModel extends Mysql
{


    private $id;
    private $clasificacion;
    private $activo;
    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;

    /**
     * Método Constructor de ClasificacionGastosModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Obtiene la lista de Clasificacion de Gastos.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectClasificacionesGastos(): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/

            $sql = "SELECT ";
            $sql .= "cg.* ";
            $sql .= "FROM clasificacion_gastos cg ";
            $sql .= "ORDER BY cg.clasificacion ";

            /*-------------------------------------------
                [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene datos de una Clasificacion determinada.
     * 
     * @param int $clasif_gastos_id
     * Identificador de Clasificacion
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectClasificacionGastos(int $clasif_gastos_id): array
    {

        try {

            $arrResponse = array();


            //             DROP TABLE IF EXISTS `histocli_amores_test`.`clasificacion_gastos`;
            // CREATE TABLE  `histocli_amores_test`.`clasificacion_gastos` (
            //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            //   `clasificacion` char(75) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            //   `activo` int(10) unsigned NOT NULL,
            //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
            //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
            //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
            //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
            //   PRIMARY KEY (`id`)
            // ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Catalogo de Clasificacion de Gastos';


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "clasif.*, CONCAT_WS(' ', usr_dg.nombre, usr_dg.paterno, usr_dg.materno) AS usuario ";
            $sql .= "FROM clasificacion_gastos clasif ";
            $sql .= "INNER JOIN usuarios_datos_generales usr_dg ON (usr_dg.usuario_id = clasif.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "clasif.id = :clasif_gastos_id ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'clasif_gastos_id' => $clasif_gastos_id
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
     * Valida si existe una Clasificacion determinada.
     * 
     * @param int $clasificacion
     * Clasificacionque desea validar
     * 
     * @return bool $response
     * * true = Existe.
     * * false = No existe.
     * 
     */
    public function validaExisteClasificacionGastos(string $clasificacion): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "clasif.clasificacion ";
            $sql .= "FROM clasificacion_gastos clasif ";
            $sql .= "WHERE ";
            $sql .= "clasif.clasificacion = :clasificacion ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'clasificacion' => $clasificacion
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
            if (count($arrResponse) == 0) {
                $response = false;
            }
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $response;
    }

    /**
     * Guardar datos de la Nueva Clasificación de Gastos.
     * 
     * @param object &$model ClasificacionGastosModel
     * Envío del modelo por referencia, para asiganr el valor lastInsertId al modelo.
     * 
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function insertClasificacionGastos(ClasificacionGastosModel &$modelo, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Clasificación ]*/
            $clasif_gastos_id =  $this->clasifGastosCreate($modelo, $usuario_id_register);
            $modelo->setId($clasif_gastos_id);

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
     * Subrutina dentro de insertClasificacionGastos para crear la ClasificacionGastosModel
     * 
     * @param object ClasificacionGastosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function clasifGastosCreate(ClasificacionGastosModel &$model,  int $usuario_id_register): int
    {

        $result = 0;

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO clasificacion_gastos SET ";
        $sql .= "clasificacion = :clasificacion, ";
        $sql .= "activo = 1, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'clasificacion' => $model->getClasificacion(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $lastInsertId = $this->insert($sql, $arrData);
        $result = $lastInsertId;

        /*-------------------------------------------
        [ Asigna por referencia el valor del id insertado ]*/
        $model->setId($lastInsertId);

        /*-------------------------------------------
        [ Retorna Id de Recibo Insertado ]*/
        return $result;
    }

    /**
     * Valida si existe la Clasificación.
     * 
     * @param string $clasificacion
     * Numero de tag que desea validar
     * 
     * @return bool $response
     * * true = Existe.
     * * false = No existe.
     * 
     */
    public function validaExisteClasificacionGastosUpdate(string $clasificacion, int $clasif_gastos_id): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "clasif.id  ";
            $sql .= "FROM clasificacion_gastos clasif ";
            $sql .= "WHERE ";
            $sql .= "clasif.clasificacion = :clasificacion and ";
            $sql .= "clasif.id NOT IN(:id) ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'clasificacion' => $clasificacion,
                'id' => $clasif_gastos_id
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
     * Actualizar Datos de Clasificación.
     * 
     * @param object &$model 
     * Envío de object ClasificacionGastosModel por referencia, que contine la información
     * que contiene los parametros necesarios para realizar el registro.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function updateClasificacionGastos(ClasificacionGastosModel &$modelo, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();


            // /*-------------------------------------------
            // [ Guardar Datos de Clasificación ]*/
            $this->clasifGastosUpdate($modelo, $usuario_id_register);

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
     * Subrutina dentro de updateClasificacionGastos para actualizar datos de Clasificacio
     * 
     * @param object $model 
     * Envío de object ClasificacionGastosModel por valor, que contine la información a actualizar y
     * los parámetros condicionales para realizar el update.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     * 
     */
    public function clasifGastosUpdate(ClasificacionGastosModel $modelo, int $usuario_id_register)
    {


        //         DROP TABLE IF EXISTS `histocli_amores_test`.`clasificacion_gastos`;
        // CREATE TABLE  `histocli_amores_test`.`clasificacion_gastos` (
        //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        //   `clasificacion` char(75) COLLATE utf8mb4_unicode_520_ci NOT NULL,
        //   `activo` int(10) unsigned NOT NULL,
        //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
        //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
        //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
        //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
        //   PRIMARY KEY (`id`)
        // ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Catalogo de Clasificacion de Gastos';

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE clasificacion_gastos SET ";
        $sql .= "clasificacion = :clasificacion, ";
        $sql .= "activo = :activo, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $modelo->getId(),
            'activo' => $modelo->getActivo(),
            'clasificacion' => $modelo->getClasificacion(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);
    }

    /**
     * Eliminar Clasificación.
     * 
     * @param int $clasif_gastos_id
     * Identificador de Clasificación de Gastos
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function deleteClasificacionGastos(int $clasif_gastos_id, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();


            // /*-------------------------------------------
            // [ Guardar Datos de Clasificación ]*/
            $this->clasifGastosDelete($clasif_gastos_id, $usuario_id_register);

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
     * Subrutina dentro de deleteClasificacionGastos para Eliminar Clasificación
     * 
     * @param int $clasif_gastos_id
     * Identificador de Clasificación de Gastos
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     */
    public function clasifGastosDelete(int $clasif_gastos_id, int $usuario_id_register)
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE clasificacion_gastos SET ";
        $sql .= "activo = 0, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $clasif_gastos_id,
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);
    }

    /**
     * Activar Clasificación.
     * 
     * @param int $clasif_gastos_id
     * Identificador de Clasificación de Gastos
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function activeClasificacionGastos(int $clasif_gastos_id, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();


            // /*-------------------------------------------
            // [ Guardar Datos de Clasificación ]*/
            $this->clasifGastosActive($clasif_gastos_id, $usuario_id_register);

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
     * Subrutina dentro de activeClasificacionGastos para Activar Clasificación
     * 
     * @param int $clasif_gastos_id
     * Identificador de Clasificación de Gastos
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     */
    public function clasifGastosActive(int $clasif_gastos_id, int $usuario_id_register)
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE clasificacion_gastos SET ";
        $sql .= "activo = 1, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $clasif_gastos_id,
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);
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
     * Get the value of clasificacion
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    }

    /**
     * Set the value of clasificacion
     *
     * @return  self
     */
    public function setClasificacion($clasificacion)
    {
        $this->clasificacion = $clasificacion;

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
}
