<?php

/**
 * Controlador Cron 
 */
class Cron extends Controllers
{

    private $session;
    private $permisosMod;
    private $cuenta_model;

    /**
     * Método Constructor de Controlador Cron.
     * Inicializa Controllers::__construct.
     * Inicializa y valida datos de session.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Obtiene la lista de Estado de Cuenta del Residente para llenar la tabla en DataTable.net
     * 
     * @return string $arrData
     * json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function setAdeudoMesCorriente(): void
    {

        try {

            /*-------------------------------------------
            [ Varibales modelo ]*/
            $anio = date('Y');
            $mes = date('m');
            $descripcion = 'MANTENIMIENTO MES ' . strtoupper(format_Mes($mes)) . ' ' . $anio;

            /*-------------------------------------------
            [ Obtiene el array con la lista de residentes relacionados a la calle ]*/
            $cron_model = new CronModel;
            $arrListResidentes = $cron_model->getListResidentes();
            $cron_model->setMes($mes);
            $cron_model->setAnio($anio);
            $cron_model->setDescripcion($descripcion);

            /*-------------------------------------------
            [ Personaliza los datos del array ]*/
            for ($i = 0; $i < count($arrListResidentes); $i++) {

                $residente_id = $arrListResidentes[$i]['id'];

                $cron_model->setResidente_id($residente_id);
                $cron_model->setCuenta($cron_model);
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }
}
