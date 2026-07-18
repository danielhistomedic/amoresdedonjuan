<?php

/**
 * Clase MenusModel
 */
class MenusModel extends Mysql
{

    private $id;
    private $menu;
    private $descripcion;
    private $url;
    private $icon;
    private $activo;
    private $created_at;
    private $updated_at;
    private $usuario_id_created;
    private $usuario_id_updated;


    // DROP TABLE IF EXISTS `histocli_sys`.`menus`;
    // CREATE TABLE  `histocli_sys`.`menus` (
    //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    //   `menu` varchar(45) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Titulo del menu',
    //   `descripcion` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Decripción breve de la funcionalidad del formulario',
    //   `url` varchar(45) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Url de Acceso al Menu',
    //   `icon` varchar(45) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Icono FontAwesome 6 del Menu',
    //   `activo` int(10) unsigned NOT NULL COMMENT '0 = inactivo, 1 = activo, 2 = eliminado',
    //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
    //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
    //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
    //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
    //   PRIMARY KEY (`id`)
    // ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

    /**
     * Método Constructor de MenusModel.
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
    public function selectMenus($filter): array
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
            $session = new Session;
            $rol_id = $session->get('rol_id');

            $filter = "%" . $filter . "%";
            $sql = "SELECT ";
            $sql .= "m.id, m.url, m.icon, m.menu, m.descripcion, m.activo ";
            $sql .= "FROM menus m ";
            $sql .= "INNER JOIN modulos mo ON (mo.id = m.modulo_id) ";
            $sql .= "INNER JOIN permisos p ON (p.modulo_id = m.modulo_id) ";
            $sql .= "WHERE ";
            $sql .= "(m.descripcion LIKE :filter OR ";
            $sql .= " m.menu LIKE :filter) and ";
            $sql .= "p.rol_id = " . $rol_id . " and ";
            $sql .= "p.r = 1 and ";
            $sql .= "m.activo = 1 ";
            $sql .= "order by m.descripcion LIMIT 12";

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
     * Get the value of menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set the value of menu
     *
     * @return  self
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of url
     *
     * @return  self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the value of icon
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set the value of icon
     *
     * @return  self
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

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
}
