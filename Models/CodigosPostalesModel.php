<?php

/**
 * Clase CodigosPostalesModel
 */
class CodigosPostalesModel extends Mysql
{

    private $id;
    private $codigo_postal;
    private $colonia;
    private $entidad_codigo;
    private $municipio_codigo;
    private $entidad_id;
    private $municipio_id;

    private $created_at;
    private $updated_at;
    private $usuario_id_created;
    private $usuario_id_updated;
    private $activo;




    /**
     * Método Constructor de CodigosPostalesModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Obtiene la lista del catálogo de codigos postales.
     * 
     * @return array $request
     * 
     */
    public function selectAllCodigosPostales($entidad_id, $municipio_id): array
    {

        try {

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cat.*,  us.usuario ";
            $sql .= "FROM codigos_postales cat ";
            $sql .= "INNER JOIN usuarios us ON (us.id = cat.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "cat.entidad_id = :entidad_id and ";
            $sql .= "cat.municipio_id = :municipio_id and ";
            $sql .= "cat.activo = 1";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'entidad_id' => $entidad_id,
                'municipio_id' => $municipio_id
            ];

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
     * Obtiene datos de un Codigo Postal determinado.
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectCodigoPostal(int $codigo_postal_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cat.*,  us.usuario ";
            $sql .= "FROM codigos_postales cat ";
            $sql .= "INNER JOIN usuarios us ON (us.id = cat.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "cat.id = :codigo_postal_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'codigo_postal_id' => $codigo_postal_id
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

    /**
     * Get the value of codigo_postal
     */
    public function getCodigo_postal()
    {
        return $this->codigo_postal;
    }

    /**
     * Set the value of codigo_postal
     *
     * @return  self
     */
    public function setCodigo_postal($codigo_postal)
    {
        $this->codigo_postal = $codigo_postal;

        return $this;
    }

    /**
     * Get the value of colonia
     */
    public function getColonia()
    {
        return $this->colonia;
    }

    /**
     * Set the value of colonia
     *
     * @return  self
     */
    public function setColonia($colonia)
    {
        $this->colonia = $colonia;

        return $this;
    }

    /**
     * Get the value of entidad_codigo
     */
    public function getEntidad_codigo()
    {
        return $this->entidad_codigo;
    }

    /**
     * Set the value of entidad_codigo
     *
     * @return  self
     */
    public function setEntidad_codigo($entidad_codigo)
    {
        $this->entidad_codigo = $entidad_codigo;

        return $this;
    }

    /**
     * Get the value of municipio_codigo
     */
    public function getMunicipio_codigo()
    {
        return $this->municipio_codigo;
    }

    /**
     * Set the value of municipio_codigo
     *
     * @return  self
     */
    public function setMunicipio_codigo($municipio_codigo)
    {
        $this->municipio_codigo = $municipio_codigo;

        return $this;
    }

    /**
     * Get the value of entidad_id
     */
    public function getEntidad_id()
    {
        return $this->entidad_id;
    }

    /**
     * Set the value of entidad_id
     *
     * @return  self
     */
    public function setEntidad_id($entidad_id)
    {
        $this->entidad_id = $entidad_id;

        return $this;
    }

    /**
     * Get the value of municipio_id
     */
    public function getMunicipio_id()
    {
        return $this->municipio_id;
    }

    /**
     * Set the value of municipio_id
     *
     * @return  self
     */
    public function setMunicipio_id($municipio_id)
    {
        $this->municipio_id = $municipio_id;

        return $this;
    }
}
