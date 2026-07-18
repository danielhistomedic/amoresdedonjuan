<?php

/**
 * Clase MunicipiosModel
 */
class MunicipiosModel extends Mysql
{

    private $id;
    private $entidad_codigo;
    private $codigo;
    private $municipio;
    private $entidad_id;

    private $created_at;
    private $updated_at;
    private $usuario_id_created;
    private $usuario_id_updated;
    private $activo;


    //     DROP TABLE IF EXISTS `histomed_his`.`municipios`;
    // CREATE TABLE  `histomed_his`.`municipios` (
    //   `id` int(10) NOT NULL AUTO_INCREMENT,
    //   `entidad_codigo` varchar(5) COLLATE utf8mb4_unicode_520_ci NOT NULL,
    //   `codigo` varchar(5) COLLATE utf8mb4_unicode_520_ci NOT NULL,
    //   `municipio` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
    //   `activo` int(10) unsigned NOT NULL DEFAULT 1,
    //   `entidad_id` int(10) unsigned NOT NULL,
    //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
    //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
    //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
    //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
    //   PRIMARY KEY (`id`) USING BTREE
    // ) ENGINE=InnoDB AUTO_INCREMENT=2459 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Catalogo de Municipios';

    /**
     * Método Constructor de MunicipiosModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Obtiene la lista del catálogo de municipios.
     * 
     * @return array $request
     * 
     */
    public function selectMunicipios($entidad_id): array
    {

        try {


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cat.*,  us.usuario ";
            $sql .= "FROM municipios cat ";
            $sql .= "INNER JOIN usuarios us ON (us.id = cat.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "cat.entidad_id = :entidad_id ";



            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'entidad_id' => $entidad_id
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
     * Get the value of municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Set the value of municipio
     *
     * @return  self
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;

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
}
