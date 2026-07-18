<?php

/**
 * Clase PermisosModel
 */
class PermisosModel extends Mysql
{
    private $id;
    private $rol_id;
    private $modulo_id;
    private $c_create;
    private $r_read;
    private $u_update;
    private $d_delete;
    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;
    private $p_excel;

    /**
     * Método Constructor de PermisosModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista del catálogo de modulos.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectModulos()
    {

        try {

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT * FROM modulos ORDER BY orden";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);

            /*-------------------------------------------
            [ Retorna array con la lista de registros ]*/
            return $arrResponse;
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene la lista de permisos de un rol determinado.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectPermisosRol(int $rol_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT per.*, m.name ";
            $sql .= "FROM permisos per ";
            $sql .= "INNER JOIN modulos m ON (m.id = per.modulo_id) ";
            $sql .= "WHERE per.rol_id = :rol_id";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'rol_id' => $rol_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros ]*/
        return $arrResponse;
    }

    /**
     * Elimina todos los permisos de un rol determinado.
     * 
     * @param int $rol_id
     * Identificador de rol que se va a elminar los permisos
     * 
     * @return bool $response
     * * true si se ejecuto correctamente.
     * * false en caso de falla. 
     * 
     */
    public function deletePermisos(int $rol_id): bool
    {

        try {

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "DELETE FROM permisos WHERE rol_id = :rol_id";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'rol_id' =>  $rol_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo delete de MySQL ]*/
            $response = $this->delete($sql, $arr_values);

            /*-------------------------------------------
            [ Retorna true|false ]*/
            return $response;
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Inserta todos los permisos seleccionados para un rol determinado.
     * 
     * @param object $model
     * Envío del modelo \PermisosModel por valor.
     * 
     * @param int $usuario_id_register
     * Identificador de usuario que realiza el registro
     * 
     * @return bool $result.
     * true = exitoso
     * false = en caso de falla
     * 
     */
    public function insertPermisos(PermisosModel $model,  int $usuario_id_register): bool
    {

        try {

            $result = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "INSERT INTO permisos SET ";
            $sql .= "modulo_id = :modulo_id, ";
            $sql .= "rol_id = :rol_id, ";
            $sql .= "c = :c, ";
            $sql .= "r = :r, ";
            $sql .= "u = :u, ";
            $sql .= "d = :d, ";
            $sql .= "p_excel = :p_excel, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arrData = [
                'rol_id' => $model->getRol_id(),
                'modulo_id' => $model->getModulo_id(),
                'c' => $model->getC_create(),
                'r' => $model->getR_read(),
                'u' => $model->getU_update(),
                'd' => $model->getD_delete(),
                'p_excel' => $model->getP_excel(),
                'usuario_id_register' => $usuario_id_register
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo insert de MySQL ]*/
            $response = $this->insert($sql, $arrData);

            /*-------------------------------------------
            [ Retorna true|false ]*/
            $result = $response > 0 ? true : false;
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
            $result = false;
        }

        return $result;
    }


    /**
     * Obtiene la lista de permisos de cada modulo, a partir de un rol determinado.
     * 
     * @param int $rol_id
     * Identificador de rol que se va a obtener los permisos
     * 
     * @return array $arrResponse
     * 
     */
    public function getPermisosMoudulo($rol_id): array
    {

        try {

            $arrResponse = array();

            // DROP TABLE IF EXISTS `histocli_sys`.`permisos`;
            // CREATE TABLE  `histocli_sys`.`permisos` (
            //   `id` bigint(20) NOT NULL AUTO_INCREMENT,
            //   `rol_id` bigint(20) NOT NULL COMMENT 'Rol relacionado',
            //   `modulo_id` bigint(20) NOT NULL COMMENT 'Modulo relacionado',
            //   `c` int(11) NOT NULL DEFAULT '0' COMMENT 'Permiso para crear nuevos registros',
            //   `r` int(11) NOT NULL DEFAULT '0' COMMENT 'Permiso de lectura de datos',
            //   `u` int(11) NOT NULL DEFAULT '0' COMMENT 'Permiso para actualizar o modificar registros',
            //   `d` int(11) NOT NULL DEFAULT '0' COMMENT 'Permiso para elminar registros (cambio de estatus)',
            //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
            //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
            //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
            //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
            //   `p_excel` int(10) unsigned NOT NULL COMMENT 'Permiso para exportar listados a excel',
            //   PRIMARY KEY (`id`),
            //   KEY `rol_id` (`rol_id`),
            //   KEY `module_id` (`modulo_id`) USING BTREE
            // ) ENGINE=InnoDB AUTO_INCREMENT=626 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='allows';

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT per.rol_id, per.modulo_id, per.c, per.r, per.u, per.d, per.p_excel, m.name ";
            $sql .= "FROM permisos per ";
            $sql .= "INNER JOIN modulos m ON (m.id = per.modulo_id) ";
            $sql .= "WHERE per.rol_id = :rol_id";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'rol_id' => $rol_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrayPermisos = $this->select($sql, $arr_values);

            $arrayResult = array();
            for ($i = 0; $i < count($arrayPermisos); $i++) {
                $arrayResult[$arrayPermisos[$i]['modulo_id']] =  $arrayPermisos[$i];
            }
            $arrResponse = $arrayResult;
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
            [ Retorna array con la lista de registros ]*/
        return $arrResponse;
    }


    /**
     * Obtiene la lista de permisos de los modulos base para mostrar en Menu Superior Horizontal.
     * 
     * @return array $arrResponse
     * 
     */
    public function getPermisosMenuBase($rol_id): array
    {

        try {

            $arrResponse = array();


            /*-------------------------------------------
            [ Instruccion sql ]*/
            //     SELECT m.menu_base, sum(per.r) as suma
            //     FROM permisos per
            //    INNER JOIN modulos m ON (m.id = per.modulo_id)
            //    WHERE per.rol_id = 2
            //    group by m.menu_base ;

            $sql = "SELECT m.menu_base, sum(per.r) as permisos ";
            $sql .= "FROM permisos per ";
            $sql .= "INNER JOIN modulos m ON (m.id = per.modulo_id) ";
            $sql .= "WHERE ";
            $sql .= "per.rol_id = :rol_id ";
            $sql .= "group by m.menu_base ";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'rol_id' => $rol_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
            [ Retorna array con la lista de registros ]*/
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
     * Get the value of rol_id
     */
    public function getRol_id()
    {
        return $this->rol_id;
    }

    /**
     * Set the value of rol_id
     *
     * @return  self
     */
    public function setRol_id($rol_id)
    {
        $this->rol_id = $rol_id;

        return $this;
    }

    /**
     * Get the value of modulo_id
     */
    public function getModulo_id()
    {
        return $this->modulo_id;
    }

    /**
     * Set the value of modulo_id
     *
     * @return  self
     */
    public function setModulo_id($modulo_id)
    {
        $this->modulo_id = $modulo_id;

        return $this;
    }

    /**
     * Get the value of c_create
     */
    public function getC_create()
    {
        return $this->c_create;
    }

    /**
     * Set the value of c_create
     *
     * @return  self
     */
    public function setC_create($c_create)
    {
        $this->c_create = $c_create;

        return $this;
    }

    /**
     * Get the value of r_read
     */
    public function getR_read()
    {
        return $this->r_read;
    }

    /**
     * Set the value of r_read
     *
     * @return  self
     */
    public function setR_read($r_read)
    {
        $this->r_read = $r_read;

        return $this;
    }

    /**
     * Get the value of u_update
     */
    public function getU_update()
    {
        return $this->u_update;
    }

    /**
     * Set the value of u_update
     *
     * @return  self
     */
    public function setU_update($u_update)
    {
        $this->u_update = $u_update;

        return $this;
    }

    /**
     * Get the value of d_delete
     */
    public function getD_delete()
    {
        return $this->d_delete;
    }

    /**
     * Set the value of d_delete
     *
     * @return  self
     */
    public function setD_delete($d_delete)
    {
        $this->d_delete = $d_delete;

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

    /**
     * Get the value of p_excel
     */
    public function getP_excel()
    {
        return $this->p_excel;
    }

    /**
     * Set the value of p_excel
     *
     * @return  self
     */
    public function setP_excel($p_excel)
    {
        $this->p_excel = $p_excel;

        return $this;
    }
}
