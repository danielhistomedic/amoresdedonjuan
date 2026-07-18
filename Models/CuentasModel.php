<?php

/**
 * Clase CuentasModel
 */
class CuentasModel extends Mysql
{

    private $id;
    private $concepto_id;
    private $residente_id;
    private $importe;
    private $estatus;
    private $recibo_id;
    private $mes;
    private $anio;
    private $descripcion;

    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;

    private $filtro_fecha_inicio;
    private $filtro_fecha_fin;



    /**
     * Método Constructor de CuentasModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene el arreglo con las cuentas con un estatus determinado.
     * 
     * @param int $estatus
     * Identificador de Estatus del registro en la cuenta
     * * 0 = pendiente.
     * * 1 = pagado.
     *  
     * @return array $arrResponse
     * 
     */
    public function selectCuentasEstatus($residente_id, $estatus): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cta.*, r.folio  ";
            $sql .= "FROM cuenta cta ";
            $sql .= "LEFT JOIN recibos r on (r.id = cta.recibo_id) ";
            $sql .= "WHERE ";
            $sql .= "cta.residente_id = :residente_id and ";
            $sql .= "(cta.concepto_id = 1 OR cta.concepto_id = 2)  and ";
            $sql .= "cta.estatus = :estatus ";
            $sql .= "ORDER BY cta.anio desc, cta.mes desc";

            $arr_values = [
                'residente_id' => $residente_id,
                'estatus' => $estatus
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
     * Obtiene un integer con el estatus de pago del mes corriente.
     * 
     * @param int $residente_id
     * Identificador de Residente
     * 
     *  
     * @return int $result
     * * 0 = pendiente.
     * * 1 = pagado.
     * 
     */
    public function getEstatusCuentaMesCorriente($residente_id): int
    {

        try {

            $result = 0;

            $date_y = date("Y");
            $date_m = date("n");

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cta.estatus ";
            $sql .= "FROM cuenta cta ";
            $sql .= "WHERE ";
            $sql .= "cta.residente_id = :residente_id and ";
            $sql .= "cta.mes = :mes and ";
            $sql .= "cta.anio = :anio ";

            $arr_values = [
                'residente_id' => $residente_id,
                'mes' => $date_m,
                'anio' => $date_y
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                $result = $arrResponse['estatus'];
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $result = 0;
        }

        /*-------------------------------------------
        [ Retorna resultado integer o 0 en caso de error ]*/
        return $result;
    }


    /**
     * Obtiene una string con la fecha de vigencia para las tags de acuerdo al utlimo mes pagado.
     * 
     * @param int $residente_id
     * Identificador de Residente
     * 
     *  
     * @return string $result
     * * fecha en formato YYYY-mm-05
     * en caso de no existir cadena vacía "";
     * 
     */
    public function getFechaFinalUltimoMesPagadoParaVigenciaTag($residente_id): string
    {

        try {

            $result = "";

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cta.anio, cta.mes ";
            $sql .= "FROM cuenta cta ";
            $sql .= "WHERE ";
            $sql .= "cta.residente_id = :residente_id and ";
            $sql .= "cta.anio > 0 and ";
            $sql .= "cta.recibo_id > 0 ";
            $sql .= "order by cta.anio desc, cta.mes desc limit 1";

            $arr_values = [
                'residente_id' => $residente_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                $mes = str_pad($arrResponse['mes'], 2, "0", STR_PAD_LEFT);
                $result = $arrResponse['anio'] . '-' . $mes . '-05';
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna resultado integer o 0 en caso de error ]*/
        return $result;
    }





    /**
     * Obtiene un integer con el estatus de pago del mes corriente.
     * 
     * @param int $residente_id
     * Identificador de Residente
     * 
     *  
     * @return int $result
     * * 0 = pendiente.
     * * 1 = pagado.
     * 
     */
    public function getEstatusCuentaMesAnterior($residente_id): int
    {

        try {

            $result = 0;

            $date_y = date("Y");
            $date_m = date("n");

            $date_str = $date_y . "-" . $date_m . "-05";
            $date = date_create($date_str);
            date_add($date, date_interval_create_from_date_string("- 1 month"));
            $date_y = date_format($date, "Y");
            $date_m = date_format($date, "n");

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cta.estatus ";
            $sql .= "FROM cuenta cta ";
            $sql .= "WHERE ";
            $sql .= "cta.residente_id = :residente_id and ";
            $sql .= "cta.mes = :mes and ";
            $sql .= "cta.anio = :anio ";

            $arr_values = [
                'residente_id' => $residente_id,
                'mes' => $date_m,
                'anio' => $date_y
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (!empty($arrResponse)) {
                $result = $arrResponse['estatus'];
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $result = 0;
        }

        /*-------------------------------------------
        [ Retorna resultado integer o 0 en caso de error ]*/
        return $result;
    }

    /**
     * Obtenter el valor del ultimo mes registrado en las cuentas.
     * 
     * @param int $residente_id
     * Identificador de Residente
     *  
     * @return array $arrResponse
     * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectLastMonthCuenta($residente_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "mes, anio ";
            $sql .= "FROM cuenta cta ";
            $sql .= "WHERE ";
            $sql .= "cta.residente_id = :residente_id ";
            $sql .= "order by anio desc, mes desc limit 1 ";

            $arr_values = [
                'residente_id' => $residente_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene el Importe Total Disponible de de lo que ha dejado a cuenta un residente determinado.
     * 
     * @param int $residente_id
     * Identificador de residente.
     *  
     * @return float $total_importe_a_cuenta_disponible
     * 
     */
    public function selectImporteEgresosPeriodo($residente_id): float
    {

        try {

            $total_importe_a_cuenta_disponible = 0;

            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT ";
            $sql .= "sum(saldo.importe) as importe ";
            $sql .= "FROM cuenta_saldo saldo ";
            $sql .= "WHERE ";
            $sql .= "saldo.estatus = 0 and ";
            $sql .= "saldo.residente_id = :residente_id ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'residente_id' => $residente_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                if ($arrResponse['importe'] == '') {
                    $total_importe_a_cuenta_disponible = 0;
                } else {
                    $total_importe_a_cuenta_disponible = $arrResponse['importe'];
                }
            } else {
                $total_importe_a_cuenta_disponible = 0;
            }
        } catch (\Throwable $th) {
            $total_importe_a_cuenta_disponible = 0;
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $total_importe_a_cuenta_disponible;
    }


    /**
     * Obtiene la lista de Ingresos para un periodo determinado
     * 
     * @param int $residente_id
     * Identificador de Residente
     *  
     * @return array $arrResponse
     * 
     */
    public function selectListaEstadoCuenta($residente_id): array
    {

        try {

            $arrResponse = array();


            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT ";
            $sql .= "cta.*, rec.folio, rec.created_at as fecha_pago, res.calle, res.numero, res.nombre ";
            $sql .= "FROM cuenta cta ";
            $sql .= "INNER JOIN residentes res on (res.id = cta.residente_id) ";
            $sql .= "LEFT JOIN recibos rec on (rec.id = cta.recibo_id) ";
            $sql .= "WHERE ";
            $sql .= "cta.residente_id = :residente_id ";
            $sql .= "ORDER BY cta.anio desc, cta.mes desc";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'residente_id' => $residente_id
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
     * Obtien el saldo disponible del residente seleccionado.
     * 
     * @param float $residente_id
     * Identificador de Residente
     * 
     *  
     * @return float $result
     * Importe del saldo disponible
     */
    public function getSaldoCuentaDisponible($residente_id): float
    {

        try {

            $result = 0;

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "sum(saldo.importe) as importe_a_cuenta ";
            $sql .= "FROM cuenta_saldo saldo ";
            $sql .= "WHERE ";
            $sql .= "saldo.residente_id = :residente_id and ";
            $sql .= "saldo.estatus = 0 ";

            $arr_values = [
                'residente_id' => $residente_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                if ($arrResponse['importe_a_cuenta'] == '') {
                    $result = 0;
                } else {
                    $result = $arrResponse['importe_a_cuenta'];
                }
            } else {
                $result = 0;
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $result = 0;
        }

        /*-------------------------------------------
        [ Retorna resultado integer o 0 en caso de error ]*/
        return $result;
    }

    /**
     * Obtien el importe total del adeudo del residente seleccionado.
     * 
     * @param float $residente_id
     * Identificador de Residente
     * 
     *  
     * @return float $result
     * Importe del total de adeudo. return 0 en caso de error. 
     */
    public function getTotalAdeudo($residente_id): float
    {

        try {

            $result = 0;

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/

            $sql = "SELECT ";
            $sql .= "sum(cta.importe) as importe_adeudo ";
            $sql .= "FROM cuenta cta ";
            $sql .= "WHERE ";
            $sql .= "cta.residente_id = :residente_id and ";
            $sql .= "cta.estatus = 0 ";

            $arr_values = [
                'residente_id' => $residente_id
            ];



            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                if ($arrResponse['importe_adeudo'] == '') {
                    $result = 0;
                } else {
                    $result = $arrResponse['importe_adeudo'];
                }
            } else {
                $result = 0;
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $result = 0;
        }

        /*-------------------------------------------
        [ Retorna resultado integer o 0 en caso de error ]*/
        return $result;
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
     * Get the value of concepto_id
     */
    public function getConcepto_id()
    {
        return $this->concepto_id;
    }

    /**
     * Set the value of concepto_id
     *
     * @return  self
     */
    public function setConcepto_id($concepto_id)
    {
        $this->concepto_id = $concepto_id;

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
     * Get the value of importe
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set the value of importe
     *
     * @return  self
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

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
     * Get the value of recibo_id
     */
    public function getRecibo_id()
    {
        return $this->recibo_id;
    }

    /**
     * Set the value of recibo_id
     *
     * @return  self
     */
    public function setRecibo_id($recibo_id)
    {
        $this->recibo_id = $recibo_id;

        return $this;
    }

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
}
