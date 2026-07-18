<?php

/**
 * Clase EscuelasModel
 */
class EscuelasModel extends Mysql
{

    private $id;
    private $escuela;
    private $created_at;
    private $updated_at;
    private $usuario_id_created;
    private $usuario_id_updated;
    private $activo;




    /**
     * Método Constructor de EscuelasModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Obtiene la lista de escuelas para llenar autocomplete.
     * 
     * @return array $request
     * 
     */
    public function getAutocompleteEscuelas($filter): array
    {

        try {

            /*-------------------------------------------
            [ Instruccion sql ]*/
            // SELECT men.menu, p.r
            // FROM
            // menus men
            // INNER JOIN modulos m ON (m.id = men.modulo_id)
            // INNER JOIN permisos p ON (p.modulo_id = men.modulo_id)
            // where p.rol_id = 2 and p.r = 1;

            $filter = "%" . $filter . "%";
            $sql = "SELECT ";
            $sql .= "esc.id, ";
            $sql .= "esc.escuela ";
            $sql .= "FROM escuelas esc ";
            $sql .= "WHERE ";
            $sql .= "esc.escuela LIKE :filter and ";
            $sql .= "esc.activo = 1 ";
            $sql .= "order by esc.escuela LIMIT 20";

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
}
