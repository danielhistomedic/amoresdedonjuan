<?php

/**
 * Clase InformeAdeudosCalleModel
 */
class InformeAdeudosCalleModel extends Mysql
{

    private  $calle_id;
    private  $total_activos;
    private  $total_inactivos;
    private  $total_activos_convenio;
    private  $importe_adeudo;

    /**
     * Método Constructor de InformeAdeudosCalleModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getResidentesCalle($calle_id): array
    {
        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            // SELECT id, calle, numero, nombre FROM residentes
            // where calle_id = 20;
            $sql = "SELECT id, calle, numero, nombre ";
            $sql .= "FROM residentes r ";
            $sql .= "WHERE ";
            $sql .= "r.calle_id = :calle_id order by numero";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'calle_id' => $calle_id
            ];

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
     * Guardar datos del Resumen de los Nuevo Gasto.
     * 
     * @param object &$model InformeAdeudosCalleModel
     * Envío del modelo por referencia, para asiganr el valor lastInsertId al modelo.
     * 
     * @param array $files
     * Array con los datos del archivo adjunto
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function updateResumenAdeudosCalle(InformeAdeudosCalleModel $modelo, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Resumen de Adeudo ]*/

            //             DROP TABLE IF EXISTS `histocli_amores`.`adeudos_calle`;
            // CREATE TABLE  `histocli_amores`.`adeudos_calle` (
            //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            //   `calle_id` int(10) unsigned NOT NULL COMMENT 'FK calles',
            //   `importe_adeudo` double unsigned NOT NULL COMMENT 'Total Adedudo',
            //   `total_activos` int(10) unsigned NOT NULL COMMENT 'Total Residentes Activos',
            //   `total_inactivos` double unsigned NOT NULL COMMENT 'Total Residentes Inactivos',
            //   `total_activos_convenio` double unsigned NOT NULL COMMENT 'Total Residentes Activos con Convenio',
            //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
            //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
            //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
            //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
            //   PRIMARY KEY (`id`)
            // ) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Adeudo por Calles';

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE adeudos_calle SET ";
            $sql .= "importe_adeudo = :importe_adeudo, ";
            $sql .= "total_activos = :total_activos, ";
            $sql .= "total_inactivos = :total_inactivos, ";
            $sql .= "total_activos_convenio = :total_activos_convenio, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE ";
            $sql .= "calle_id = :calle_id";

            getLoggerSystem()->error($modelo->getImporte_adeudo());

            /*-------------------------------------------
            [ Datos a insertar ]*/
            $arrData = [
                'calle_id' => $modelo->getCalle_id(),
                'importe_adeudo' => $modelo->getImporte_adeudo(),
                'total_activos' => $modelo->getTotal_activos(),
                'total_inactivos' => $modelo->getTotal_inactivos(),
                'total_activos_convenio' => $modelo->getTotal_activos_convenio(),
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
            getLoggerSystem()->error(getMensajeError($th));
            $response = false;
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Obtener datos del Resumen de Adeudos de Calle.
     * 
     * @param int $calle_id
     * Identificador de Calle
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function getResumenAdeudosCalle($calle_id): array
    {
        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            // SELECT id, calle, numero, nombre FROM residentes
            // where calle_id = 20;
            $sql = "SELECT id, calle, numero, nombre ";
            $sql .= "FROM residentes r ";
            $sql .= "WHERE ";
            $sql .= "r.calle_id = :calle_id order by numero";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'calle_id' => $calle_id
            ];

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
     * Obtiene datos de un resumen de Adeudo por Calle determinado.
     * 
     * @param int $calle_id
     * Identificador de Calle
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectResumenInformeAdeudoCalle(int $calle_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "ad.* ";
            $sql .= "FROM adeudos_calle ad ";
            $sql .= "WHERE ";
            $sql .= "ad.calle_id = :calle_id ";


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


    //*==================================================================
    // [ GETTERS & SETTERS ]*/


    /**
     * Get the value of total_activos
     */
    public function getTotal_activos()
    {
        return $this->total_activos;
    }

    /**
     * Set the value of total_activos
     *
     * @return  self
     */
    public function setTotal_activos($total_activos)
    {
        $this->total_activos = $total_activos;

        return $this;
    }

    /**
     * Get the value of total_inactivos
     */
    public function getTotal_inactivos()
    {
        return $this->total_inactivos;
    }

    /**
     * Set the value of total_inactivos
     *
     * @return  self
     */
    public function setTotal_inactivos($total_inactivos)
    {
        $this->total_inactivos = $total_inactivos;

        return $this;
    }

    /**
     * Get the value of total_activos_convenio
     */
    public function getTotal_activos_convenio()
    {
        return $this->total_activos_convenio;
    }

    /**
     * Set the value of total_activos_convenio
     *
     * @return  self
     */
    public function setTotal_activos_convenio($total_activos_convenio)
    {
        $this->total_activos_convenio = $total_activos_convenio;

        return $this;
    }

    /**
     * Get the value of importe_adeudo
     */
    public function getImporte_adeudo()
    {
        return $this->importe_adeudo;
    }

    /**
     * Set the value of importe_adeudo
     *
     * @return  self
     */
    public function setImporte_adeudo($importe_adeudo)
    {
        $this->importe_adeudo = $importe_adeudo;

        return $this;
    }

    /**
     * Get the value of calle_id
     */
    public function getCalle_id()
    {
        return $this->calle_id;
    }

    /**
     * Set the value of calle_id
     *
     * @return  self
     */
    public function setCalle_id($calle_id)
    {
        $this->calle_id = $calle_id;

        return $this;
    }
}
