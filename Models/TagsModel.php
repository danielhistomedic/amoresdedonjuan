<?php

/**
 * Clase TagsModel
 */
class TagsModel extends Mysql
{

    private $id;
    private $residente_id;
    private $tag;
    private $fchIniVIgencia;
    private $fchFinVIgencia;

    private $estatus;
    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;
    private $sinc;



    /**
     * Método Constructor de TagsModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista de tags para el residente seleccionado
     * 
     * @return array $request
     * 
     */
    public function selectTagResidente($residente_id): array
    {

        try {

            $response = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "t.* ";
            $sql .= "FROM residentes_tags t ";
            $sql .= "WHERE ";
            $sql .= "t.residente_id = :residente_id ";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'residente_id' => $residente_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $response = $this->select($sql, $arr_values);

            /*-------------------------------------------
            [ Retorna array con la lista de registros ]*/
            return $response;
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        return $response;
    }

    /**
     * Obtiene la lista de tags para el search en tags.
     * 
     * @return array $request
     * 
     */
    public function selectTagSearch($filter): array
    {

        try {

            $response = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $filter = "%" . $filter . "%";

            //             DROP TABLE IF EXISTS `histocli_amores`.`residentes`;
            // CREATE TABLE  `histocli_amores`.`residentes` (
            //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            //   `nombre` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
            //   `calle` char(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            //   `mza` char(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            //   `lote` char(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            //   `numero` char(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
            //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
            //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
            //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
            //   `telefono` varchar(12) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
            //   `email` varchar(65) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
            //   PRIMARY KEY (`id`)
            // ) ENGINE=InnoDB AUTO_INCREMENT=1031 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Residentes';


            $sql = "SELECT ";
            $sql .= "t.id, t.residente_id, t.tag, t.estatus, r.calle, r.numero ";
            $sql .= "FROM residentes_tags t ";
            $sql .= "LEFT JOIN residentes r on (r.id = t.residente_id) ";
            $sql .= "WHERE ";
            $sql .= "t.tag LIKE :filter ";
            $sql .= "order by t.tag LIMIT 30";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'filter' => $filter
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $response = $this->selectLike($sql, $arr_values);

            /*-------------------------------------------
            [ Retorna array con la lista de registros ]*/
            return $response;
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        return $response;
    }

    /**
     * Actualizar Datos de Tag.
     * 
     * @param object &$model 
     * Envío de object TagsModel por referencia, que contine la información
     * que contiene los parametros necesarios para realizar el registro.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function updateTag(TagsModel &$modelo, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();


            // /*-------------------------------------------
            // [ Guardar Datos de Tag ]*/
            $this->tagUpdate($modelo, $usuario_id_register);

            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
        } catch (\Throwable $th) {

            /*-------------------------------------------
            [ RollBack ]*/
            $this->getConexion()->rollBack();
            $_logger = getLoggerSystem()->error(getMensajeError($th));
            $response = false;
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Subrutina dentro de updateTag para actualizar datos de tag
     * 
     * @param object $modelo 
     * Envío de object TagsModel por valor, que contine la información a actualizar y
     * los parámetros condicionales para realizar el update.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     * 
     */
    public function tagUpdate(TagsModel $modelo, int $usuario_id_register)
    {


        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE residentes_tags SET ";
        $sql .= "residente_id = :residente_id, ";
        $sql .= "fchFinVIgencia = :fchFinVIgencia, ";
        $sql .= "estatus = :estatus, ";
        $sql .= "sinc = 0, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $modelo->getId(),
            'residente_id' => $modelo->getResidente_id(),
            'fchFinVIgencia' => $modelo->getFchFinVIgencia(),
            'estatus' => $modelo->getEstatus(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);
    }

    /**
     * Guardar datos de la Nueva Tag.
     * 
     * @param object &$model TagModel
     * Envío del modelo por referencia, para asiganr el valor lastInsertId al modelo.
     * 
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function insertTag(TagsModel &$modelo, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Tag ]*/
            $tag_id =  $this->tagCreate($modelo, $usuario_id_register);
            $modelo->setId($tag_id);

            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
        } catch (\Throwable $th) {

            /*-------------------------------------------
            [ RollBack ]*/
            $this->getConexion()->rollBack();
            $_logger = getLoggerSystem()->error(getMensajeError($th));
            $response = false;
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }


    /**
     * Subrutina dentro de insertTag para crear la TagModel
     * 
     * @param object TagssModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function tagCreate(TagsModel &$model,  int $usuario_id_register): int
    {

        $result = 0;

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO residentes_tags SET ";
        $sql .= "residente_id = :residente_id, ";
        $sql .= "tag = :tag, ";
        $sql .= "fchIniVIgencia = current_timestamp, ";
        $sql .= "fchFinVIgencia = :fchFinVIgencia, ";
        $sql .= "estatus = :estatus, ";
        $sql .= "sinc = 0, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'residente_id' => $model->getResidente_id(),
            'tag' => $model->getTag(),
            'fchFinVIgencia' => $model->getFchFinVIgencia(),
            'estatus' => $model->getEstatus(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $lastInsertId = $this->insert($sql, $arrData);
        $result = $lastInsertId;

        /*-------------------------------------------
        [ Asigna por referencia el valor del id insertado ]*/
        $model->setId($lastInsertId);

        /*-------------------------------------------
        [ Retorna Id de Recibo Insertado ]*/
        return $result;
    }


    /**
     * Obtiene la lista de Tags para un residente determinado.
     * 
     * @param int $residente_id
     * Identificador de Residente
     * 
     * @return array $arrResponse
     * 
     */
    public function selectTags($residente_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/

            $sql = "SELECT ";
            $sql .= "tag.*, ";
            $sql .= "r.nombre, ";
            $sql .= "r.calle, ";
            $sql .= "r.numero, ";
            $sql .= "CONCAT_WS(' ', usr.nombre, usr.paterno, usr.materno) as usuario_reg ";
            $sql .= "FROM residentes_tags tag ";
            $sql .= "INNER JOIN residentes r on (r.id = tag.residente_id) ";
            $sql .= "INNER JOIN usuarios_datos_generales usr on (usr.usuario_id = tag.usuario_id_created) ";
            $sql .= "WHERE tag.residente_id = :residente_id ";
            $sql .= "ORDER BY tag.created_at ";

            /*-------------------------------------------
                [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'residente_id' => $residente_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene datos de una Tag determinada.
     * 
     * @param int $tag_id
     * Identificador de tag
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectTag(int $tag_id): array
    {

        try {

            $arrResponse = array();


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "tag.*, date_format(tag.fchFinVIgencia, '%d/%m/%Y') as fecha_vigencia, CONCAT_WS(' ', usr_dg.nombre, usr_dg.paterno, usr_dg.materno) AS usuario, ";
            $sql .= "res.nombre as residente, CONCAT_WS(' ', calle, numero) AS domicilio ";
            $sql .= "FROM residentes_tags tag ";
            $sql .= "INNER JOIN residentes res ON (res.id = tag.residente_id) ";
            $sql .= "INNER JOIN usuarios_datos_generales usr_dg ON (usr_dg.usuario_id = tag.usuario_id_updated) ";
            $sql .= "WHERE ";
            $sql .= "tag.id = :tag_id ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'tag_id' => $tag_id
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
     * Elimina la relación de esta tag con el residente actual.
     * 
     * @param object $model
     * Envío del modelo por valor, que contine la información a actualizar y 
     * los parámetros condicionales para realizar el update.
     *
     * 
     * @param int $usuario_id_register
     * Identificador de usuario que realiza el registro
     * 
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function deleteTag(TagsModel $modelo, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();


            /*-------------------------------------------
            [ Eliminar Tag ]*/


            $fecha_actual = date("Y-m-d");
            $fecha_vigencia = date("Y-m-d", strtotime($fecha_actual . "- 1 days"));


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE residentes_tags SET ";
            $sql .= "estatus = 0, ";
            $sql .= "sinc = 0, ";
            $sql .= "remove_tag = 1, ";
            $sql .= "fchFinVIgencia = :fchFinVIgencia, ";
            $sql .= "residente_id = Null, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE  ";
            $sql .= "id = :id ";


            /*-------------------------------------------
            [ Parámetros condicionales para realizar el update ]*/
            $arrData = [
                'id' => $modelo->getId(),
                'fchFinVIgencia' => $fecha_vigencia,
                'usuario_id_register' => $usuario_id_register
            ];


            /*-------------------------------------------
            [ Ejecuta el Metodo update de MySQL ]*/
            $response = $this->update($sql, $arrData);


            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
        } catch (\Throwable $th) {

            /*-------------------------------------------
            [ RollBack ]*/
            $this->getConexion()->rollBack();
            $_logger = getLoggerSystem()->error(getMensajeError($th));
            $response = false;
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }


    /**
     * Desactivar Tag.
     * 
     * @param int $residente_id
     * Identificador de Residente
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function desactivarTag(int $residente_id, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Actualizar Estatus de Tag de Residente ]*/
            $cuenta_model = new CuentasModel;
            $estatus_cuenta_mes_corriente = $cuenta_model->getEstatusCuentaMesCorriente($residente_id);

            if ($estatus_cuenta_mes_corriente == 0) {

                $date_y = date("Y");
                $date_m = date("m");
                $date_d = date("d");
                $date_str = $date_y . "-" . $date_m . "-" .  $date_d;
                $date = date_create($date_str);
                date_add($date, date_interval_create_from_date_string("- 1 days"));
                $fchFinVIgencia = date_format($date, "Y-m-d");

                date_add($date, date_interval_create_from_date_string("- 2 month"));
                $fchIniVIgencia = date_format($date, "Y-m-d");

                /*-------------------------------------------
                [ Instruccion sql ]*/
                $sql = "UPDATE residentes_tags SET ";
                $sql .= "fchFinVIgencia = :fchFinVIgencia, ";
                $sql .= "fchIniVIgencia = :fchIniVIgencia, ";
                $sql .= "estatus = 0, ";
                $sql .= "sinc = 0, ";
                $sql .= "updated_at = current_timestamp, ";
                $sql .= "usuario_id_updated = :usuario_id_register ";
                $sql .= "WHERE ";
                $sql .= "residente_id = :residente_id ";


                /*-------------------------------------------
                [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
                $arrData = [
                    'residente_id' => $residente_id,
                    'fchFinVIgencia' => $fchFinVIgencia,
                    'fchIniVIgencia' => $fchIniVIgencia,
                    'usuario_id_register' => $usuario_id_register
                ];

                /*-------------------------------------------
                [ Ejecuta el Metodo update de MySQL ]*/
                $response = $this->update($sql, $arrData);
            }
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
            $response = false;
        }

        return $response;
    }


    /**
     * Activar Tag.
     * 
     * @param int $residente_id
     * Identificador de Residente
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function activarTag(int $residente_id, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Actualizar Estatus de Tag de Residente ]*/
            $cuenta_model = new CuentasModel;
            $estatus_cuenta_mes_corriente = $cuenta_model->getEstatusCuentaMesCorriente($residente_id);

            if ($estatus_cuenta_mes_corriente == 1) {

                //Prepara datos de fecha
                $date_y = date("Y");
                $date_m = date("m");
                $date_str = $date_y . "-" . $date_m . "-05";
                $date = date_create($date_str);


                //Fecha del ultimo mes que tiene pagado el residente.
                $fecha_utlimo_mes = $cuenta_model->getFechaFinalUltimoMesPagadoParaVigenciaTag($residente_id);
                if ($fecha_utlimo_mes == '') {

                    //Si no hay registros sigue el proceso normal de activación del mes corriente.
                    date_add($date, date_interval_create_from_date_string("1 month"));
                    $fchFinVIgencia = date_format($date, "Y-m-d");
                } else {

                    //Si hay registros tome esta fecha como base para la fecha final de vigencia.

                    $date_fecha_utlimo_mes = date_create($fecha_utlimo_mes);
                    date_add($date_fecha_utlimo_mes, date_interval_create_from_date_string("1 month"));
                    $fchFinVIgencia = date_format($date_fecha_utlimo_mes, "Y-m-d");
                }

                date_add($date, date_interval_create_from_date_string("- 2 month"));
                $fchIniVIgencia = date_format($date, "Y-m-d");

                /*-------------------------------------------
                [ Instruccion sql ]*/
                $sql = "UPDATE residentes_tags SET ";
                $sql .= "fchIniVIgencia = :fchIniVIgencia, ";
                $sql .= "fchFinVIgencia = :fchFinVIgencia, ";
                $sql .= "estatus = 1, ";
                $sql .= "sinc = 0, ";
                $sql .= "updated_at = current_timestamp, ";
                $sql .= "usuario_id_updated = :usuario_id_register ";
                $sql .= "WHERE ";
                $sql .= "residente_id = :residente_id ";

                /*-------------------------------------------
                [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
                $arrData = [
                    'residente_id' => $residente_id,
                    'fchIniVIgencia' => $fchIniVIgencia,
                    'fchFinVIgencia' => $fchFinVIgencia,
                    'usuario_id_register' => $usuario_id_register
                ];

                /*-------------------------------------------
                [ Ejecuta el Metodo update de MySQL ]*/
                $response = $this->update($sql, $arrData);
            }
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
            $response = false;
        }

        return $response;
    }



    /**
     * Valida si existe una Tag determinada.
     * 
     * @param int $tag
     * Numero de tag que desea validar
     * 
     * @return bool $response
     * * true = Existe.
     * * false = No existe.
     * 
     */
    public function validaExisteTag(string $tag): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "tags.tag ";
            $sql .= "FROM residentes_tags tags ";
            $sql .= "WHERE ";
            $sql .= "tags.tag = :tag ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'tag' => $tag
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
            if (count($arrResponse) == 0) {
                $response = false;
            }
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $response;
    }

    /**
     * Valida si existe y ya fue asiganda a un residente.
     * 
     * @param int $tag
     * Numero de tag que desea validar
     * 
     * @return bool $response
     * * true = Existe.
     * * false = No existe.
     * 
     */
    public function validaTagAsignadaResidente(string $tag, int $residente_id): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "tags.tag,  ";
            $sql .= "tags.residente_id  ";
            $sql .= "FROM residentes_tags tags ";
            $sql .= "WHERE ";
            $sql .= "tags.tag = :tag and ";
            $sql .= "tags.residente_id NOT IN(:residente_id) ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'tag' => $tag,
                'residente_id' => $residente_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
            if (count($arrResponse) > 0) {
                $response = true;
            }
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $response;
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
     * Get the value of residente_id
     */
    public function getResidente_id()
    {
        return $this->residente_id;
    }

    /**
     * Set the value of residente_id
     *
     * @return  self
     */
    public function setResidente_id($residente_id)
    {
        $this->residente_id = $residente_id;

        return $this;
    }

    /**
     * Get the value of tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set the value of tag
     *
     * @return  self
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get the value of fchIniVIgencia
     */
    public function getFchIniVIgencia()
    {
        return $this->fchIniVIgencia;
    }

    /**
     * Set the value of fchIniVIgencia
     *
     * @return  self
     */
    public function setFchIniVIgencia($fchIniVIgencia)
    {
        $this->fchIniVIgencia = $fchIniVIgencia;

        return $this;
    }

    /**
     * Get the value of fchFinVIgencia
     */
    public function getFchFinVIgencia()
    {
        return $this->fchFinVIgencia;
    }

    /**
     * Set the value of fchFinVIgencia
     *
     * @return  self
     */
    public function setFchFinVIgencia($fchFinVIgencia)
    {
        $this->fchFinVIgencia = $fchFinVIgencia;

        return $this;
    }

    /**
     * Get the value of estatus
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set the value of estatus
     *
     * @return  self
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

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
     * Get the value of sinc
     */
    public function getSinc()
    {
        return $this->sinc;
    }

    /**
     * Set the value of sinc
     *
     * @return  self
     */
    public function setSinc($sinc)
    {
        $this->sinc = $sinc;

        return $this;
    }
}
