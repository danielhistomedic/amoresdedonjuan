<?php

/**
 * Clase EntidadesModel
 */
class EntidadesModel extends Mysql
{

    private $id;
    private $entidad;
    private $pais_id;
    private $codigo;
    private $created_at;
    private $updated_at;
    private $usuario_id_created;
    private $usuario_id_updated;
    private $activo;


    // DROP TABLE IF EXISTS `histomed_his`.`entidades`;
    // CREATE TABLE  `histomed_his`.`entidades` (
    //   `codigo` varchar(45) COLLATE utf8mb4_unicode_520_ci NOT NULL,
    //   `entidad` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
    //   `id` int(10) NOT NULL AUTO_INCREMENT,
    //   `activo` int(10) unsigned NOT NULL,
    //   `pais_id` int(10) unsigned NOT NULL,
    //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
    //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
    //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
    //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
    //   PRIMARY KEY (`id`) USING BTREE
    // ) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Catalogo de entidades';

    /**
     * Método Constructor de EntidadesModel.
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
    public function selectEntidades($pais_id): array
    {

        try {


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cat.*,  us.usuario ";
            $sql .= "FROM entidades cat ";
            $sql .= "INNER JOIN usuarios us ON (us.id = cat.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "cat.pais_id = :pais_id ";



            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'pais_id' => $pais_id
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
     * Get the value of entidad
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * Set the value of entidad
     *
     * @return  self
     */
    public function setEntidad($entidad)
    {
        $this->entidad = $entidad;

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
}
