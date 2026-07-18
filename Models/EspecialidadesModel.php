<?php

/**
 * Clase EspecialidadesModel
 */
class EspecialidadesModel extends Mysql
{

    private $id;
    private $especialidad;
    private $created_at;
    private $updated_at;
    private $usuario_id_created;
    private $usuario_id_updated;
    private $activo;


    /**
     * Método Constructor de EspecialidadesModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }



    /**
     * Obtiene la lista del catálogo de especialidades.
     * 
     * @return array $request
     * 
     */
    public function selectEspecialidades(): array
    {

        try {

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "esp.*,  us.usuario ";
            $sql .= "FROM especialidades esp ";
            $sql .= "INNER JOIN usuarios us ON (us.id = esp.usuario_id_updated) ";

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
     * Obtiene la lista del catálogo de especialidades para autocomplete
     * 
     * @return array $request
     * 
     */
    public function autocompleteEspecialidades($filter): array
    {

        try {

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $filter = "%" . $filter . "%";
            $sql = "SELECT ";
            $sql .= "esp.especialidad ";
            $sql .= "FROM especialidades esp ";
            $sql .= "WHERE ";
            $sql .= "esp.activo = 1 and ";
            $sql .= "esp.especialidad LIKE :filter ";
            $sql .= "order by esp.especialidad";

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
     * Get the value of especialidad
     */
    public function getEspecialidad()
    {
        return $this->especialidad;
    }

    /**
     * Set the value of especialidad
     *
     * @return  self
     */
    public function setEspecialidad($especialidad)
    {
        $this->especialidad = $especialidad;

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
}
