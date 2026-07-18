<?php

/**
 * Clase EstadoResultadosModel
 */
class EstadoResultadosModel extends Mysql
{

    private $filtro_fecha_inicio;
    private $filtro_fecha_fin;
    private $saldo_anterior;
    private $ingresos_totales;
    private $gastos_totales;
    private $saldo_periodo;
    private $saldo_inicial;

    /**
     * Método Constructor de EstadoResultadosModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista de Ingresos para un periodo determinado
     * agrupado por calle
     * 
     * @param object $model
     * Envío del modelo EstadoResultadosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return array $arrResponse
     * 
     */
    public function selectIngresos(EstadoResultadosModel $modelo): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT ";
            $sql .= "rec.calle, sum(rec.importe) as importe ";
            $sql .= "FROM recibos rec ";
            $sql .= "WHERE ";
            $sql .= "rec.estatus = 0 and rec.tipo = 1 and ";
            $sql .= "rec.fecha_pago BETWEEN :fecha_pago_ini and :fecha_pago_fin ";
            $sql .= "GROUP BY rec.calle ";
            $sql .= "ORDER BY importe desc";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFiltro_fecha_inicio(),
                'fecha_pago_fin' => $modelo->getFiltro_fecha_fin()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene la lista de Egresos para un periodo determinado
     * 
     * @param object $model
     * Envío del modelo EstadoResultadosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return array $arrResponse
     * 
     */
    public function selectEgresos(EstadoResultadosModel $modelo): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT ";
            $sql .= "g.*, cg.clasificacion ";
            $sql .= "FROM gastos g ";
            $sql .= "INNER JOIN clasificacion_gastos cg ON (cg.id = g.clasificacion_gasto_id) ";
            $sql .= "WHERE ";
            $sql .= "g.estatus = 0 and ";
            $sql .= "g.fecha_pago BETWEEN :fecha_pago_ini and :fecha_pago_fin ";
            $sql .= "ORDER BY g.fecha_pago desc";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFiltro_fecha_inicio(),
                'fecha_pago_fin' => $modelo->getFiltro_fecha_fin()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene el Importe Total de Ingresos a una fecha determinada..
     * 
     * @param object $model
     * Envío del modelo EstadoResultadosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return float $saldo_ingresos
     * 
     */
    public function selectImporteIngresosSaldo(EstadoResultadosModel $modelo): float
    {

        try {

            $saldo_ingresos = 0;

            /*-------------------------------------------
            [ Instrucción sql ]*/
            // SELECT (sum(importe) + 17004.75) as importe FROM recibos
            // where
            // estatus = 0 and tipo = 1 and
            // fecha_pago < '2021-12-20';

            $sql = "SELECT ";
            $sql .= "(sum(rec.importe) + :saldo_inicial) as importe ";
            $sql .= "FROM recibos rec ";
            $sql .= "WHERE ";
            $sql .= "rec.estatus = 0 and rec.tipo = 1 and ";
            $sql .= "rec.fecha_pago < :fecha_pago_ini ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'saldo_inicial' => $modelo->getSaldo_inicial(),
                'fecha_pago_ini' => $modelo->getFiltro_fecha_inicio()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                if ($arrResponse['importe'] == '') {
                    $saldo_ingresos = $modelo->getSaldo_inicial();
                } else {
                    $saldo_ingresos = $arrResponse['importe'];
                }
            } else {
                $saldo_ingresos = $modelo->getSaldo_inicial();
            }
        } catch (\Throwable $th) {
            $saldo_ingresos = 0;
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $saldo_ingresos;
    }

    /**
     * Obtiene el Importe Total de Egresos a una fecha determinada.
     * 
     * @param object $model
     * Envío del modelo EstadoResultadosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return float $saldo_egresos
     * 
     */
    public function selectImporteEgresosSaldo(EstadoResultadosModel $modelo): float
    {

        try {

            $saldo_egresos = 0;


            /*-------------------------------------------
            [ Instrucción sql ]*/
            // SELECT sum(importe) FROM gastos
            // where fecha_pago < '2021-12-20';

            $sql = "SELECT ";
            $sql .= "sum(g.importe) as importe ";
            $sql .= "FROM gastos g ";
            $sql .= "WHERE ";
            $sql .= "g.estatus = 0 and ";
            $sql .= "g.fecha_pago < :fecha_pago_ini ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFiltro_fecha_inicio()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                if ($arrResponse['importe'] == '') {
                    $saldo_egresos = 0;
                } else {
                    $saldo_egresos = $arrResponse['importe'];
                }
            } else {
                $saldo_egresos = 0;
            }
        } catch (\Throwable $th) {
            $saldo_egresos = 0;
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $saldo_egresos;
    }

    /**
     * Obtiene el Importe Total de Ingresos de un periodo determinado.
     * 
     * @param object $model
     * Envío del modelo EstadoResultadosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return float $total_ingresos
     * 
     */
    public function selectImporteIngresosPeriodo(EstadoResultadosModel $modelo): float
    {

        try {

            $total_ingresos = 0;

            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT ";
            $sql .= "sum(rec.importe) as importe ";
            $sql .= "FROM recibos rec ";
            $sql .= "WHERE ";
            $sql .= "rec.estatus = 0 and rec.tipo = 1 and ";
            $sql .= "rec.fecha_pago BETWEEN :fecha_pago_ini AND :fecha_pago_fin ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFiltro_fecha_inicio(),
                'fecha_pago_fin' => $modelo->getFiltro_fecha_fin()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                if ($arrResponse['importe'] == '') {
                    $total_ingresos = 0;
                } else {
                    $total_ingresos = $arrResponse['importe'];
                }
            } else {
                $total_ingresos = 0;
            }
        } catch (\Throwable $th) {
            $total_ingresos = 0;
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $total_ingresos;
    }

    /**
     * Obtiene el Importe Total de Egresos de un periodo determinado.
     * 
     * @param object $model
     * Envío del modelo EstadoResultadosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return float $total_egresos
     * 
     */
    public function selectImporteEgresosPeriodo(EstadoResultadosModel $modelo): float
    {

        try {

            $total_egresos = 0;


            /*-------------------------------------------
            [ Instrucción sql ]*/
            // SELECT sum(importe) FROM gastos
            // where fecha_pago < '2021-12-20';

            $sql = "SELECT ";
            $sql .= "sum(g.importe) as importe ";
            $sql .= "FROM gastos g ";
            $sql .= "WHERE ";
            $sql .= "g.estatus = 0 and ";
            $sql .= "g.fecha_pago BETWEEN :fecha_pago_ini AND :fecha_pago_fin ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFiltro_fecha_inicio(),
                'fecha_pago_fin' => $modelo->getFiltro_fecha_fin()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                if ($arrResponse['importe'] == '') {
                    $total_egresos = 0;
                } else {
                    $total_egresos = $arrResponse['importe'];
                }
            } else {
                $total_egresos = 0;
            }
        } catch (\Throwable $th) {
            $total_egresos = 0;
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $total_egresos;
    }


    //*==================================================================
    // [ GETTERS & SETTERS ]*/

    /**
     * Get the value of filtro_fecha_inicio
     */
    public function getFiltro_fecha_inicio()
    {
        return $this->filtro_fecha_inicio;
    }

    /**
     * Set the value of filtro_fecha_inicio
     *
     * @return  self
     */
    public function setFiltro_fecha_inicio($filtro_fecha_inicio)
    {
        $this->filtro_fecha_inicio = $filtro_fecha_inicio;

        return $this;
    }

    /**
     * Get the value of filtro_fecha_fin
     */
    public function getFiltro_fecha_fin()
    {
        return $this->filtro_fecha_fin;
    }

    /**
     * Set the value of filtro_fecha_fin
     *
     * @return  self
     */
    public function setFiltro_fecha_fin($filtro_fecha_fin)
    {
        $this->filtro_fecha_fin = $filtro_fecha_fin;

        return $this;
    }

    /**
     * Get the value of saldo_anterior
     */
    public function getSaldo_anterior()
    {
        return $this->saldo_anterior;
    }

    /**
     * Set the value of saldo_anterior
     *
     * @return  self
     */
    public function setSaldo_anterior($saldo_anterior)
    {
        $this->saldo_anterior = $saldo_anterior;

        return $this;
    }

    /**
     * Get the value of ingresos_totales
     */
    public function getIngresos_totales()
    {
        return $this->ingresos_totales;
    }

    /**
     * Set the value of ingresos_totales
     *
     * @return  self
     */
    public function setIngresos_totales($ingresos_totales)
    {
        $this->ingresos_totales = $ingresos_totales;

        return $this;
    }

    /**
     * Get the value of gastos_totales
     */
    public function getGastos_totales()
    {
        return $this->gastos_totales;
    }

    /**
     * Set the value of gastos_totales
     *
     * @return  self
     */
    public function setGastos_totales($gastos_totales)
    {
        $this->gastos_totales = $gastos_totales;

        return $this;
    }

    /**
     * Get the value of saldo_periodo
     */
    public function getSaldo_periodo()
    {
        return $this->saldo_periodo;
    }

    /**
     * Set the value of saldo_periodo
     *
     * @return  self
     */
    public function setSaldo_periodo($saldo_periodo)
    {
        $this->saldo_periodo = $saldo_periodo;

        return $this;
    }

    /**
     * Get the value of saldo_inicial
     */
    public function getSaldo_inicial()
    {
        return $this->saldo_inicial;
    }

    /**
     * Set the value of saldo_inicial
     *
     * @return  self
     */
    public function setSaldo_inicial($saldo_inicial)
    {
        $this->saldo_inicial = $saldo_inicial;

        return $this;
    }
}
