<?php

/**
 * Clase CallesModel
 */
class CallesModel extends Mysql
{

    //     DROP TABLE IF EXISTS `histocli_amores`.`calles`;
    // CREATE TABLE  `histocli_amores`.`calles` (
    //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    //   `calle` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Nombre de calle',
    //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
    //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
    //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
    //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
    //   `activo` int(10) unsigned NOT NULL COMMENT '0 = inactivo, 1 = activo, 2 = eliminado',
    //   PRIMARY KEY (`id`)
    // ) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Catalogo de Calles';


    private $id;
    private $calle;
    private $activo;

    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;


    /**
     * Método Constructor de CallesModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista de menus para el search en vistas.
     * 
     * @return array $request
     * 
     */
    public function selectCallesSearch($arrFilter): array
    {

        try {


            //             DROP TABLE IF EXISTS `histocli_amores`.`calles`;
            // CREATE TABLE  `histocli_amores`.`calles` (
            //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            //   `calle` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Nombre de calle',
            //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
            //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
            //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
            //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
            //   `activo` int(10) unsigned NOT NULL COMMENT '0 = inactivo, 1 = activo, 2 = eliminado',
            //   PRIMARY KEY (`id`)
            // ) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Catalogo de Calles';


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $calle = $arrFilter[0];
            $calle_filter = "%" . $calle . "%";


            $sql = "SELECT ";
            $sql .= "* ";
            $sql .= "FROM calles c ";
            $sql .= "WHERE ";
            $sql .= "c.calle LIKE :filter ";
            $sql .= "order by c.calle";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'filter' => $calle_filter
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

    /**
     * Obtiene datos de una Calle determinada.
     * 
     * @param int $calle_id
     * Identificador de calle que se desa obtener
     * 
     * 
     * @return array $arrResponse
     * * Array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * * Retorna array de tipo:
     *   fetch(PDO::FETCH_ASSOC): returns an array indexed by column name as returned in your result set
     * 
     */
    public function selectCalle(int $calle_id): array
    {

        try {

            $arrResponse = array();

            // DROP TABLE IF EXISTS `histocli_amores_test`.`calles`;
            // CREATE TABLE  `histocli_amores_test`.`calles` (
            //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            //   `calle` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Nombre de calle',
            //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
            //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
            //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
            //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
            //   `activo` int(10) unsigned NOT NULL COMMENT '0 = inactivo, 1 = activo, 2 = eliminado',
            //   PRIMARY KEY (`id`)
            // ) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Catalogo de Calles';


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "* ";
            $sql .= "FROM calles c ";
            $sql .= "WHERE ";
            $sql .= "c.id = :calle_id";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'calle_id' => $calle_id
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
     * Obtiene Lista de Calles Registradas.
     * 
     * @return array $arrResponse
     * * Array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * * Retorna array de tipo:
     *   fetch(PDO::FETCH_ASSOC): DOStatement::fetchAll returns an array containing all of the remaining rows in the result set.
     * 
     */
    public function selectCalles(): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "* ";
            $sql .= "FROM calles c ";
            $sql .= "WHERE ";
            $sql .= "c.activo = 1";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }




    /*==============================================
    [ Getters & Setteres ]*/

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
