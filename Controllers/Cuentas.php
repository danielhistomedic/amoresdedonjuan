<?php

/**
 * Controlador Cuentas 
 */
class Cuentas extends Controllers
{


    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Cuentas.
     * Inicializa Controllers::__construct.
     * Inicializa y valida datos de session.
     */
    public function __construct()
    {
        parent::__construct();

        /*-------------------------------------------
        [ Validación de Sesion ]*/
        $this->session = new Session();

        if ($this->session->getStatus() === false || empty($this->session->get('email'))) {
            $this->session->redirect('inicio');
        }
    }

    /**
     * Obtiene la lista de registros de mantenimiento que tienen adeudos.
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getMesesAdeudos($residente_id)
    {

        try {

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $residente_id = intval(strClean($residente_id));
            $cuentas_model = new CuentasModel;
            $estatus = 0;
            $arrData = $cuentas_model->selectCuentasEstatus($residente_id, $estatus);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de registros de mantenimiento pagados.
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getMesesPagados($residente_id)
    {

        try {

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $residente_id = intval(strClean($residente_id));
            $cuentas_model = new CuentasModel;
            $estatus = 1;
            $arrData = $cuentas_model->selectCuentasEstatus($residente_id, $estatus);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene el ultimo mes para llenar lista de pagos adelantados.
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function getLastMonthCuenta($residente_id)
    {

        try {

            /*-------------------------------------------
            [ Obtiene el array con la lista de catálogo de roles ]*/
            $residente_id = intval(strClean($residente_id));
            $cuentas_model = new CuentasModel;
            $arrData = $cuentas_model->selectLastMonthCuenta($residente_id);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }
}
