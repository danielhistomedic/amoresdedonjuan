<?php

/**
 * Clase CronModel
 */
class CronModel extends Mysql
{

    private $mes;
    private $anio;
    private $residente_id;
    private $descripcion;

    /**
     * Método Constructor de CronModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function getListResidentes(): array
    {
        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT id, calle, numero, nombre ";
            $sql .= "FROM residentes r ";
            $sql .= "order by r.calle, r.numero";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Guardar datos de Cuenta del Mes Corriente.
     * 
     * @param object &$model CronModel
     * Envío del modelo por referencia, para asiganr el valor lastInsertId al modelo.
     * 
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function setCuenta(CronModel &$modelo): bool
    {

        try {

            $response = true;


            /*-------------------------------------------
            [ Registrar Cuenta Mes Corriente ]*/
            $valida = $this->validaResidenteMesActual($modelo);

            if ($valida == false) {

                /*-------------------------------------------
                [ Begin Transaction ]*/
                $this->getConexion()->beginTransaction();

                /*-------------------------------------------
                [ Instruccion sql ]*/
                $sql = "INSERT INTO cuenta SET ";
                $sql .= "concepto_id = 2, ";
                $sql .= "residente_id = :residente_id, ";
                $sql .= "importe = 200, ";
                $sql .= "estatus = 0, ";
                $sql .= "valid = 100, ";
                $sql .= "recibo_id = null, ";
                $sql .= "mes = :mes, ";
                $sql .= "anio = :anio, ";
                $sql .= "descripcion = :descripcion, ";
                $sql .= "created_at = current_timestamp, ";
                $sql .= "updated_at = current_timestamp, ";
                $sql .= "usuario_id_created = 3, ";
                $sql .= "usuario_id_updated = 3 ";

                /*-------------------------------------------
                [ Datos a insertar ]*/
                $arrData = [
                    'residente_id' => $modelo->getResidente_id(),
                    'mes' => $modelo->getMes(),
                    'anio' => $modelo->getAnio(),
                    'descripcion' => $modelo->getDescripcion()

                ];

                /*-------------------------------------------
                [ Ejecuta el Metodo insert de MySQL ]*/
                $lastInsertId = $this->insert($sql, $arrData);
                if ($lastInsertId == 0) {
                    $this->getConexion()->rollBack();
                    return false;
                }

                /*-------------------------------------------
                [ Commit Transaction ]*/
                $this->getConexion()->commit();
            } else {
                $response = false;
            }
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



    public function validaResidenteMesActual(CronModel &$modelo): bool
    {
        try {

            $response = true;

            // DROP TABLE IF EXISTS `histocli_amores`.`cuenta`;
            // CREATE TABLE  `histocli_amores`.`cuenta` (
            //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            //   `concepto_id` int(10) unsigned NOT NULL COMMENT 'FK conceptos',
            //   `residente_id` int(10) unsigned NOT NULL COMMENT 'FK residentes',
            //   `importe` double unsigned NOT NULL COMMENT 'Monto cobrado',
            //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
            //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
            //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
            //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
            //   `estatus` int(10) unsigned NOT NULL DEFAULT '0',
            //   `recibo_id` int(10) unsigned DEFAULT NULL,
            //   `mes` int(10) unsigned NOT NULL,
            //   `anio` int(10) unsigned NOT NULL,
            //   `descripcion` varchar(3000) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            //   PRIMARY KEY (`id`)
            // ) ENGINE=InnoDB AUTO_INCREMENT=43218 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Estado de Cuenta';

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT * ";
            $sql .= "FROM cuenta c ";
            $sql .= "WHERE ";
            $sql .= "residente_id = :residente_id and ";
            $sql .= "mes = :mes and ";
            $sql .= "anio = :anio ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'residente_id' => $modelo->getResidente_id(),
                'mes' => $modelo->getMes(),
                'anio' => $modelo->getAnio()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResult = $this->selectModel($sql, $arr_values);
            if (count($arrResult) == 0) {
                $response = false;
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con los datos del registro o empty en caso de error ]*/
        return $response;
    }






    //*==================================================================
    // [ GETTERS & SETTERS ]*/



    /**
     * Get the value of mes
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set the value of mes
     *
     * @return  self
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get the value of anio
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set the value of anio
     *
     * @return  self
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

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
}
