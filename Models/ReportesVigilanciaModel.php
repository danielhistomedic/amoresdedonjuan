<?php

/**
 * Clase ReportesVigilanciaModel
 */
class ReportesVigilanciaModel extends Mysql
{

    // TABLA reportes_vigilancia


    private $Id;
    private $asunto;
    private $reporte;
    private $estatus;
    private $archivo;

    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;


    /**
     * Método Constructor de ReportesVigilanciaModel.
     * Inicializa Mysql::__construct
     * 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista de Reportes.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectReportesVigilancia(): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "t.*, us.usuario ";
            $sql .= "FROM reportes_vigilancia t ";
            $sql .= "INNER JOIN usuarios us ON (us.id = t.usuario_id_updated) ";


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
     * Obtiene datos de un Reporte determinado.
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectReporte(int $reporte_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "t.*, usr.usuario ";
            $sql .= "FROM reportes_vigilancia t ";
            $sql .= "INNER JOIN usuarios usr ON (usr.id = t.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "t.id = :reporte_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'reporte_id' => $reporte_id
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
     * Guardar datos del Nuevo Reporte.
     * 
     * @param model &$model
     * Envío del modelo \ReportesVigilanciaModel por referencia, para asiganr el valor lastInsertId al modelo.
     *
     * @param int $usuario_id
     * Usuario que realiza el registro.
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function insertReporte(ReportesVigilanciaModel &$modelo): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "INSERT INTO reportes_vigilancia SET ";
            $sql .= "asunto = :asunto, ";
            $sql .= "reporte = :reporte, ";
            $sql .= "estatus = :estatus, ";
            $sql .= "archivo = :archivo, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";

            /*-------------------------------------------
            [ Datos a insertar ]*/
            $arrData = [
                'asunto' => $modelo->getAsunto(),
                'reporte' => $modelo->getReporte(),
                'estatus' => $modelo->getEstatus(),
                'archivo' => $modelo->getArchivo(),
                'usuario_id_register' => $modelo->getUsuarioIdUpdated()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo insert de MySQL ]*/
            $lastInsertId = $this->insert($sql, $arrData);

            if ($lastInsertId > 0) {

                $response = true;
                $modelo->getId($lastInsertId);
                $this->getConexion()->commit();
            } else {

                $this->getConexion()->rollBack();
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $this->getConexion()->rollBack();
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Actualiza el estatus de un Reporte determinado.
     * 
     * @param object $model
     * Envío del modelo por valor, que contine la información a actualizar y 
     * los parámetros condicionales para realizar el update.
     *
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function updateEstatusReporte(ReportesVigilanciaModel $modelor): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE reportes_vigilancia SET ";
            $sql .= "estatus = :estatus, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE  ";
            $sql .= "Id = :modelo_id ";

            /*-------------------------------------------
            [ Parámetros condicionales para realizar el update ]*/
            $arrData = [
                'modelo_id' => $modelor->getId(),
                'estatus' => $modelor->getEstatus(),
                'usuario_id_register' => $modelor->getUsuarioIdUpdated()
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


    /**
     * Actualiza los datos de un Reporte de Vigilancia.
     *
     * @param ReportesVigilanciaModel $modelo
     * Modelo con la información a actualizar.
     *
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     *
     */
    public function updateReporte(ReportesVigilanciaModel $modelo): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE reportes_vigilancia SET ";
            $sql .= "asunto = :asunto, ";
            $sql .= "reporte = :reporte, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE ";
            $sql .= "id = :modelo_id ";

            /*-------------------------------------------
            [ Parámetros para realizar el update ]*/
            $arrData = [
                'modelo_id'           => $modelo->getId(),
                'asunto'              => $modelo->getAsunto(),
                'reporte'             => $modelo->getReporte(),
                'usuario_id_register' => $modelo->getUsuarioIdUpdated()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo update de MySQL ]*/
            $response = $this->update($sql, $arrData);
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
     * Get the value of Id
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Set the value of Id
     */
    public function setId($Id): self
    {
        $this->Id = $Id;

        return $this;
    }

    /**
     * Get the value of asunto
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set the value of asunto
     */
    public function setAsunto($asunto): self
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get the value of reporte
     */
    public function getReporte()
    {
        return $this->reporte;
    }

    /**
     * Set the value of reporte
     */
    public function setReporte($reporte): self
    {
        $this->reporte = $reporte;

        return $this;
    }

    /**
     * Get the value of estatus
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set the value of estatus
     */
    public function setEstatus($estatus): self
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * Get the value of archivo
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set the value of archivo
     */
    public function setArchivo($archivo): self
    {
        $this->archivo = $archivo;

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
