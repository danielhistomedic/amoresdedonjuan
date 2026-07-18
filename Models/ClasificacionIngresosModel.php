<?php

/**
 * Clase ClasificacionIngresosModel
 */
class ClasificacionIngresosModel extends Mysql
{

    // tabla conceptos

    private $id;
    private $concepto;
    private $activo;
    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;


    /**
     * Método Constructor de ClasificacionIngresosModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Obtiene la lista de Clasificacion de Ingresos.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectClasificacionesIngresos(): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/

            $sql = "SELECT ";
            $sql .= "cg.* ";
            $sql .= "FROM conceptos cg ";
            $sql .= "ORDER BY cg.concepto ";

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
     * Obtiene datos de una Clasificacion determinada.
     * 
     * @param int $clasif_ingresos_id
     * Identificador de Clasificacion
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectClasificacionIngresos(int $clasif_ingresos_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "clasif.*, CONCAT_WS(' ', usr_dg.nombre, usr_dg.paterno, usr_dg.materno) AS usuario ";
            $sql .= "FROM conceptos clasif ";
            $sql .= "INNER JOIN usuarios_datos_generales usr_dg ON (usr_dg.usuario_id = clasif.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "clasif.id = :clasif_ingresos_id ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'clasif_ingresos_id' => $clasif_ingresos_id
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
    public function validaExisteClasificacionIngresos(string $clasificacion): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "clasif.concepto ";
            $sql .= "FROM conceptos clasif ";
            $sql .= "WHERE ";
            $sql .= "clasif.concepto = :clasificacion ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'clasificacion' => $clasificacion
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
            if (count($arrResponse) == 0) {
                return false;
            }
            $response =  true;
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $response;
    }

    /**
     * Guardar datos de la Nueva Clasificación de Ingresos.
     * 
     * @param object &$model ClasificacionIngresosModel
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
    public function insertClasificacionIngresos(ClasificacionIngresosModel &$modelo, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Clasificación ]*/
            $clasif_ingresos_id =  $this->clasifIngresosCreate($modelo, $usuario_id_register);
            if ($clasif_ingresos_id == 0) {
                $this->getConexion()->rollBack();
                return false;
            }
            $modelo->setId($clasif_ingresos_id);

            $response = true;
            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
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
     * Subrutina dentro de insertClasificacionIngresos para crear la ClasificacionIngresosModel
     * 
     * @param object ClasificacionIngresosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function clasifIngresosCreate(ClasificacionIngresosModel &$model,  int $usuario_id_register): int
    {

        $result = 0;

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO conceptos SET ";
        $sql .= "concepto = :concepto, ";
        $sql .= "activo = 1, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'concepto' => $model->getConcepto(),
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
    public function validaExisteClasificacionIngresosUpdate(string $clasificacion, int $clasif_ingresos_id): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "clasif.id  ";
            $sql .= "FROM conceptos clasif ";
            $sql .= "WHERE ";
            $sql .= "clasif.concepto = :concepto and ";
            $sql .= "clasif.id NOT IN(:id) ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'concepto' => $clasificacion,
                'id' => $clasif_ingresos_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
            if (count($arrResponse) == 0) {
                return false;
            }
            $response = true;
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $response;
    }

    /**
     * Actualizar Datos de Clasificación.
     * 
     * @param object &$model 
     * Envío de object ClasificacionIngresosModel por referencia, que contine la información
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
    public function updateClasificacionIngresos(ClasificacionIngresosModel &$modelo, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();


            // /*-------------------------------------------
            // [ Guardar Datos de Clasificación ]*/
            $result = $this->clasifIngresosUpdate($modelo, $usuario_id_register);
            if ($result == false) {
                $this->getConexion()->rollBack();
                return false;
            }

            $response = true;
            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
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
     * Subrutina dentro de updateClasificacionIngresos para actualizar datos de Clasificacio
     * 
     * @param object $model 
     * Envío de object ClasificacionIngresosModel por valor, que contine la información a actualizar y
     * los parámetros condicionales para realizar el update.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function clasifIngresosUpdate(ClasificacionIngresosModel $modelo, int $usuario_id_register): bool
    {

        $response = false;

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE conceptos SET ";
        $sql .= "concepto = :concepto, ";
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
            'concepto' => $modelo->getConcepto(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);

        /*-------------------------------------------
        [ Retorna resultado ]*/
        return $response;
    }

    /**
     * Eliminar Clasificación.
     * 
     * @param int $clasif_ingresos_id
     * Identificador de Clasificación de Ingresos
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function deleteClasificacionIngresos(int $clasif_ingresos_id, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            // /*-------------------------------------------
            // [ Guardar Datos de Clasificación ]*/
            $result = $this->clasifIngresosDelete($clasif_ingresos_id, $usuario_id_register);
            if ($result == false) {
                $this->getConexion()->rollBack();
                return false;
            }
            $response = true;
            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
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
     * Subrutina dentro de deleteClasificacionIngresos para Eliminar Clasificación
     * 
     * @param int $clasif_ingresos_id
     * Identificador de Clasificación de Ingresos
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     */
    public function clasifIngresosDelete(int $clasif_ingresos_id, int $usuario_id_register): bool
    {

        $response = false;

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE conceptos SET ";
        $sql .= "activo = 0, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $clasif_ingresos_id,
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Activar Clasificación.
     * 
     * @param int $clasif_ingresos_id
     * Identificador de Clasificación de Ingresos
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function activeClasificacionIngresos(int $clasif_ingresos_id, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();


            // /*-------------------------------------------
            // [ Guardar Datos de Clasificación ]*/
            $result = $this->clasifIngresosActive($clasif_ingresos_id, $usuario_id_register);
            if ($result == false) {
                $this->getConexion()->rollBack();
                return false;
            }

            $response = true;
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
     * Subrutina dentro de activeClasificacionIngresos para Activar Clasificación
     * 
     * @param int $clasif_ingresos_id
     * Identificador de Clasificación de Ingresos
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     */
    public function clasifIngresosActive(int $clasif_ingresos_id, int $usuario_id_register): bool
    {

        $response = false;

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE conceptos SET ";
        $sql .= "activo = 1, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $clasif_ingresos_id,
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);

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
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of concepto
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * Set the value of concepto
     */
    public function setConcepto($concepto): self
    {
        $this->concepto = $concepto;

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
     */
    public function setActivo($activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     */
    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of usuario_id_created
     */
    public function getUsuarioIdCreated()
    {
        return $this->usuario_id_created;
    }

    /**
     * Set the value of usuario_id_created
     */
    public function setUsuarioIdCreated($usuario_id_created): self
    {
        $this->usuario_id_created = $usuario_id_created;

        return $this;
    }

    /**
     * Get the value of updated_at
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     */
    public function setUpdatedAt($updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of usuario_id_updated
     */
    public function getUsuarioIdUpdated()
    {
        return $this->usuario_id_updated;
    }

    /**
     * Set the value of usuario_id_updated
     */
    public function setUsuarioIdUpdated($usuario_id_updated): self
    {
        $this->usuario_id_updated = $usuario_id_updated;

        return $this;
    }
}
