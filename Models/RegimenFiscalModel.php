<?php

/**
 * Clase RegimenFiscalModel
 */
class RegimenFiscalModel extends Mysql
{

    private $id;
    private $codigo;
    private $persona_aplica;
    private $regimen_fiscal;

    private $created_at;
    private $updated_at;
    private $usuario_id_created;
    private $usuario_id_updated;
    private $activo;



    /**
     * Método Constructor de RegimenFiscalModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Obtiene la lista del catálogo de Regimen Fiscal.
     * 
     * @return array $request
     * 
     */
    public function selectAll(): array
    {

        try {

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cat.*,  us.usuario ";
            $sql .= "FROM regimenfiscal cat ";
            $sql .= "INNER JOIN usuarios us ON (us.id = cat.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "cat.activo = 1";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $request = $this->select($sql, $arr_values);

            /*-------------------------------------------
            [ Retorna array con la lista de registros ]*/
            return $request;
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }
    }


    /**
     * Obtiene datos de una fila de Regimen Fiscal determinado.
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectRow(int $id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cat.*,  us.usuario ";
            $sql .= "FROM regimenfiscal cat ";
            $sql .= "INNER JOIN usuarios us ON (us.id = cat.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "cat.id = :id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'id' => $id
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


    //*== Campos Princiaples ===========================


    /**
     * Get the value of codigo
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set the value of codigo
     *
     * @return  self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get the value of persona_aplica
     */
    public function getPersona_aplica()
    {
        return $this->persona_aplica;
    }

    /**
     * Set the value of persona_aplica
     *
     * @return  self
     */
    public function setPersona_aplica($persona_aplica)
    {
        $this->persona_aplica = $persona_aplica;

        return $this;
    }

    /**
     * Get the value of regimen_fiscal
     */
    public function getRegimen_fiscal()
    {
        return $this->regimen_fiscal;
    }

    /**
     * Set the value of regimen_fiscal
     *
     * @return  self
     */
    public function setRegimen_fiscal($regimen_fiscal)
    {
        $this->regimen_fiscal = $regimen_fiscal;

        return $this;
    }
}
