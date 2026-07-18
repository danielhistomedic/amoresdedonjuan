<?php

/**
 * Clase DomiciliosModel
 */
class DomiciliosModel extends Mysql
{

    private $id;
    private $tipo_vialidad_id;
    private $calle;
    private $num_ext;
    private $num_int;
    private $tipo_asentamiento_id;
    private $colonia;
    private $municipio_id;
    private $localidad_id;
    private $entidad_id;
    private $cp_id;
    private $pais_id;
    private $referencia;
    private $activo;

    private $created_at;
    private $updated_at;
    private $usuario_id_created;
    private $usuario_id_updated;


    /**
     * Método Constructor de DomiciliosModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Obtiene la lista del catálogo de entidades.
     * 
     * @return array $request
     * 
     */
    public function selectDomicilios($filter): array
    {

        try {

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $filter = "%" . $filter . "%";
            $sql = "SELECT ";
            $sql .= "m.* ";
            $sql .= "FROM menus m ";
            $sql .= "WHERE ";
            $sql .= "(m.descripcion LIKE :filter OR ";
            $sql .= " m.menu LIKE :filter) ";
            $sql .= "order by m.descripcion LIMIT 10";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'filter' => $filter
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $request = $this->selectLike($sql, $arr_values);

            /*-------------------------------------------
            [ Retorna array con la lista de registros ]*/
            return $request;
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }
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
     * Get the value of tipo_vialidad_id
     */
    public function getTipo_vialidad_id()
    {
        return $this->tipo_vialidad_id;
    }

    /**
     * Set the value of tipo_vialidad_id
     *
     * @return  self
     */
    public function setTipo_vialidad_id($tipo_vialidad_id)
    {
        $this->tipo_vialidad_id = $tipo_vialidad_id;

        return $this;
    }

    /**
     * Get the value of calle
     */
    public function getCalle()
    {
        return $this->calle;
    }

    /**
     * Set the value of calle
     *
     * @return  self
     */
    public function setCalle($calle)
    {
        $this->calle = $calle;

        return $this;
    }

    /**
     * Get the value of num_ext
     */
    public function getNum_ext()
    {
        return $this->num_ext;
    }

    /**
     * Set the value of num_ext
     *
     * @return  self
     */
    public function setNum_ext($num_ext)
    {
        $this->num_ext = $num_ext;

        return $this;
    }

    /**
     * Get the value of num_int
     */
    public function getNum_int()
    {
        return $this->num_int;
    }

    /**
     * Set the value of num_int
     *
     * @return  self
     */
    public function setNum_int($num_int)
    {
        $this->num_int = $num_int;

        return $this;
    }

    /**
     * Get the value of tipo_asentamiento_id
     */
    public function getTipo_asentamiento_id()
    {
        return $this->tipo_asentamiento_id;
    }

    /**
     * Set the value of tipo_asentamiento_id
     *
     * @return  self
     */
    public function setTipo_asentamiento_id($tipo_asentamiento_id)
    {
        $this->tipo_asentamiento_id = $tipo_asentamiento_id;

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

    /**
     * Get the value of localidad_id
     */
    public function getLocalidad_id()
    {
        return $this->localidad_id;
    }

    /**
     * Set the value of localidad_id
     *
     * @return  self
     */
    public function setLocalidad_id($localidad_id)
    {
        $this->localidad_id = $localidad_id;

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
     * Get the value of cp_id
     */
    public function getCp_id()
    {
        return $this->cp_id;
    }

    /**
     * Set the value of cp_id
     *
     * @return  self
     */
    public function setCp_id($cp_id)
    {
        $this->cp_id = $cp_id;

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
     * Get the value of referencia
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set the value of referencia
     *
     * @return  self
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;

        return $this;
    }
}
