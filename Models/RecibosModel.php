<?php

/**
 * Clase RecibosModel
 */
class RecibosModel extends Mysql
{
    private $id;
    private $mes;
    private $anio;
    private $residente;
    private $residente_id;
    private $importe;
    private $concepto;
    private $folio;
    private $calle;
    private $numero;
    private $mza;
    private $lote;

    private $mes_no;

    private $concepto_id;
    private $estatus;
    private $temp;

    private $subtotal;
    private $porc_descuento;
    private $deja_cuenta;
    private $recibe;
    private $cambio;
    private $cantidad;


    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;

    private $pagos_adelantados;
    private $pagos_adeudos;
    private $tipo;
    private $usar_saldo;
    private $saldo_a_cuenta;
    private $saldo_utilizado;


    //Opciones de Filtro
    private $fecha_inicio;
    private $fecha_fin;

    private $motivo_cancela;
    private $usuario_id_cancela;



    /**
     * Método Constructor de RecibosModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Guardar datos del Nuevo Recibo.
     * 
     * @param object &$model RecibosModel
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
    public function insertRecibo(RecibosModel &$modelo, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Recibo ]*/
            $recibo_id =  $this->reciboCreate($modelo, $usuario_id_register);

            if ($recibo_id > 0) {

                $modelo->setId($recibo_id);

                /*-------------------------------------------
                [ Registrar Cuenta Pagos Adelantados ]*/
                if ($modelo->getUsar_saldo() == 'on') {
                    $this->reciboCreateUsarSaldo($modelo, $usuario_id_register);
                }

                /*-------------------------------------------
                [ Registrar Cuenta Pagos Adelantados ]*/
                $this->reciboCreatePagoAdelantados($modelo, $usuario_id_register);

                /*-------------------------------------------
                [ Registrar Cuenta Pagos de Adeudos ]*/
                $this->reciboCreatePagoAdeudos($modelo, $usuario_id_register);

                /*-------------------------------------------
                [ Registrar Cuenta Pagos de Deja a Cuenta ]*/
                $this->reciboCreateDejaCuenta($modelo, $usuario_id_register);

                $response = true;

                /*-------------------------------------------
                [ Commit Transaction ]*/
                $this->getConexion()->commit();
            } else {

                $this->getConexion()->rollBack();
            }
        } catch (\Throwable $th) {

            /*-------------------------------------------
            [ RollBack ]*/
            $this->getConexion()->rollBack();
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Subrutina dentro de insertRecibo para crear el Recibo
     * 
     * @param object RecibosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function reciboCreate(RecibosModel &$model,  int $usuario_id_register): int
    {

        try {

            $result = 0;

            //Regrenerar Concepto Calculos
            $concepto = $model->getConcepto();

            $subtotal = floatval($model->getSubtotal());

            $subtotal_str = 'SUBTOTAL: ' . formatMoney($subtotal) . '. ';

            $porc_descuento = floatval($model->getPorc_descuento());
            $descuento =  $subtotal * ($porc_descuento / 100);
            $descuento_str = '';
            if ($descuento > 0) {
                $descuento_str = 'DESCUENTO POR CONVENIO/PAGO ANTICIPADO: ' . formatMoney($descuento) . '. ';
            }

            $saldo_utilizado = floatval($model->getSaldo_utilizado());
            $saldo_utilizado_str = '';
            if ($saldo_utilizado > 0) {
                $saldo_utilizado_str = 'DESCUENTO POR SALDO UTILIZADO: ' . formatMoney($saldo_utilizado) . '. ';
            }

            $deja_cuenta = floatval($model->getDeja_cuenta());
            $deja_cuenta_str = '';
            if ($deja_cuenta > 0) {
                $deja_cuenta_str = 'DEJA A CUENTA: ' .  formatMoney($deja_cuenta) . '. ';
            }

            $importe = floatval($model->getImporte());
            $total_str =  'TOTAL: ' .  formatMoney($importe);

            //Regenerar cadena de conecpto.
            $concepto = $concepto . '.  // ' . $subtotal_str . ' ' . $descuento_str . ' ' . $saldo_utilizado_str . ' ' . $deja_cuenta_str . ' ' . $total_str;
            $model->setConcepto($concepto);

            /*-------------------------------------------
                [ Instruccion sql ]*/
            $sql = "INSERT INTO recibos SET ";
            $sql .= "residente = :residente, ";
            $sql .= "residente_id = :residente_id, ";
            $sql .= "folio = :folio, ";
            $sql .= "importe = :importe, ";
            $sql .= "concepto = :concepto, ";
            $sql .= "concepto_id = :concepto_id, ";
            $sql .= "estatus = 0, ";
            $sql .= "calle = :calle, ";
            $sql .= "numero = :numero, ";
            $sql .= "subtotal = :subtotal, ";
            $sql .= "porc_descuento = :porc_descuento, ";
            $sql .= "deja_cuenta = :deja_cuenta, ";
            $sql .= "recibe = :recibe, ";
            $sql .= "cambio = :cambio, ";
            $sql .= "saldo_utilizado = :saldo_utilizado, ";
            $sql .= "cantidad = :cantidad, ";
            $sql .= "tipo = 1, ";
            $sql .= "fecha_pago = current_timestamp, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";

            /*-------------------------------------------
                [ Datos a insertar ]*/
            $arrData = [
                'residente_id' => $model->getResidente_id(),
                'folio' => $model->getFolio(),
                'residente' => $model->getResidente(),
                'importe' => $model->getImporte(),
                'concepto_id' => $model->getConcepto_id(),
                'concepto' => $concepto,
                'calle' => $model->getCalle(),
                'numero' => $model->getNumero(),
                'subtotal' => $model->getSubtotal(),
                'porc_descuento' => $model->getPorc_descuento(),
                'deja_cuenta' => $model->getDeja_cuenta(),
                'recibe' => $model->getRecibe(),
                'cambio' => $model->getCambio(),
                'cantidad' => $model->getCantidad(),
                'saldo_utilizado' => $model->getSaldo_utilizado(),
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
                [ Actulizar Dato del Folio de recibo ]*/
            // $lastInsertId_formatted = str_pad($lastInsertId, 6, "0", STR_PAD_LEFT);
            // $folio = "A-" . $lastInsertId_formatted;
            // $model->setFolio($folio);
            // $sql = "";
            // $sql = "UPDATE recibos SET ";
            // $sql .= "folio = :folio ";
            // $sql .= "WHERE ";
            // $sql .= "id = :id ";

            // $arrDataUpdate = [
            //     'folio' => $folio,
            //     'id' => $lastInsertId
            // ];

            /*-------------------------------------------
                [ Ejecuta el Metodo udpate de MySQL ]*/
            // $this->update($sql, $arrDataUpdate);

            /*-------------------------------------------
            [ Retorna Id de Recibo Insertado ]*/
            return $result;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    /**
     * Subrutina dentro de insertRecibo para registrar el Pago de Adeduos en la Cuenta correspondiente
     * 
     * @param object RecibosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function reciboCreatePagoAdeudos(RecibosModel &$model,  int $usuario_id_register): void
    {

        $arrAdeudos = $model->getPagos_adeudos();

        if (count($arrAdeudos) > 0) {

            $arrAdelantados = $model->getPagos_adelantados();
            $subtotal = $model->getSubtotal();

            $total_registros = count($arrAdeudos) + count($arrAdelantados);
            if ($total_registros > 0) {
                $importe = $subtotal / $total_registros;
            } else {
                $importe = $subtotal;
            }
            $importe = round($importe, 0);

            for ($i = 0; $i < count($arrAdeudos); $i++) {

                $cuenta_id = $arrAdeudos[$i];

                /*-------------------------------------------
                [ Instruccion sql ]*/
                $sql = "UPDATE cuenta SET ";
                $sql .= "importe = :importe, ";
                $sql .= "recibo_id = :recibo_id, ";
                $sql .= "estatus = 1, ";
                $sql .= "updated_at = current_timestamp, ";
                $sql .= "usuario_id_updated = :usuario_id_register ";
                $sql .= "WHERE ";
                $sql .= "id = :cuenta_id";

                /*-------------------------------------------
                [ Datos a actualizar ]*/
                $arrData = [
                    'importe' => $importe,
                    'recibo_id' => $model->getId(),
                    'cuenta_id' => $cuenta_id,
                    'usuario_id_register' => $usuario_id_register
                ];

                /*-------------------------------------------
                [ Ejecuta el Metodo update de MySQL ]*/
                $this->update($sql, $arrData);
            }
        }
    }

    /**
     * Subrutina dentro de insertRecibo para registrar el Pago de Adelantados en la Cuenta correspondiente
     * 
     * @param object RecibosModel $model
     * Envío del modelo por valor con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function reciboCreatePagoAdelantados(RecibosModel $model,  int $usuario_id_register): void
    {


        $arrAdelantados = $model->getPagos_adelantados();

        if (count($arrAdelantados) > 0) {

            $arrAdeudos = $model->getPagos_adeudos();
            $subtotal = $model->getSubtotal();

            $total_registros = count($arrAdeudos) + count($arrAdelantados);
            if ($total_registros > 0) {
                $importe = $subtotal / $total_registros;
            } else {
                $importe = $subtotal;
            }

            $importe = round($importe, 0);

            for ($i = 0; $i < count($arrAdelantados); $i++) {

                $valor = $arrAdelantados[$i];
                $fecha = explode("-", $valor);
                $anio = $fecha[0];
                $mes = $fecha[1];

                $monthNum  = intval($mes);
                $mes_nombre = format_Mes($monthNum);
                // $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                // $monthName = $dateObj->format('F'); // March
                $descripcion = "MANTENIMIENTO " . $mes_nombre . " " .  $anio;

                /*-------------------------------------------
                [ Instruccion sql ]*/
                $sql = "INSERT INTO cuenta SET ";
                $sql .= "concepto_id = :concepto_id, ";
                $sql .= "residente_id = :residente_id, ";
                $sql .= "importe = :importe, ";
                $sql .= "recibo_id = :recibo_id, ";
                $sql .= "mes = :mes, ";
                $sql .= "anio = :anio, ";
                $sql .= "estatus = 1, ";
                $sql .= "descripcion = :descripcion, ";
                $sql .= "created_at = current_timestamp, ";
                $sql .= "updated_at = current_timestamp, ";
                $sql .= "usuario_id_created = :usuario_id_register, ";
                $sql .= "usuario_id_updated = :usuario_id_register ";

                /*-------------------------------------------
                [ Datos a actualizar ]*/
                $arrData = [
                    'concepto_id' => $model->getConcepto_id(),
                    'residente_id' => $model->getResidente_id(),
                    'importe' => $importe,
                    'recibo_id' => $model->getId(),
                    'mes' => intval($mes),
                    'anio' => intval($anio),
                    'descripcion' => $descripcion,
                    'usuario_id_register' => $usuario_id_register
                ];

                /*-------------------------------------------
                [ Ejecuta el Metodo update de MySQL ]*/
                $this->update($sql, $arrData);
            }
        }
    }

    /**
     * Subrutina dentro de insertRecibo para registrar el Pago de los que deja a Cuenta.
     * 
     * @param object RecibosModel $model
     * Envío del modelo por valor con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function reciboCreateDejaCuenta(RecibosModel $model,  int $usuario_id_register): void
    {

        $deja_cuenta = $model->getDeja_cuenta();

        if ($deja_cuenta > 0) {

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "INSERT INTO cuenta_saldo SET ";
            $sql .= "residente_id = :residente_id, ";
            $sql .= "importe = :importe, ";
            $sql .= "recibo_id = :recibo_id, ";
            $sql .= "estatus = 0, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";

            /*-------------------------------------------
            [ Datos a actualizar ]*/
            $arrData = [
                'residente_id' => $model->getResidente_id(),
                'importe' => $deja_cuenta,
                'recibo_id' => $model->getId(),
                'usuario_id_register' => $usuario_id_register
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo update de MySQL ]*/
            $this->update($sql, $arrData);
        }
    }

    /**
     * Subrutina dentro de insertRecibo para marcar como estatus usado el saldo disponible
     * 
     * @param object RecibosModel $model
     * Envío del modelo por referencia con los datos requeridos para el update
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function reciboCreateUsarSaldo(RecibosModel &$model,  int $usuario_id_register): void
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE cuenta_saldo SET ";
        $sql .= "estatus = 1, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "residente_id = :residente_id ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'residente_id' => $model->getResidente_id(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo udpate de MySQL ]*/
        $this->update($sql, $arrData);
    }

    /**
     * Obtiene datos de un Recibo determinado.
     * 
     * @param int $recibo_id
     * Identificador de recibo
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectRecibo(int $recibo_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "r.*, CONCAT_WS(' ', usr.nombre, usr.paterno, usr.materno) as usuario, ";
            $sql .= "CONCAT_WS(' ', usr_canc.nombre, usr_canc.paterno, usr_canc.materno) as usuario_cancela ";
            $sql .= "FROM recibos r ";
            $sql .= "INNER JOIN usuarios_datos_generales usr ON (usr.usuario_id = r.usuario_id_updated) ";
            $sql .= "LEFT JOIN usuarios_datos_generales usr_canc ON (usr_canc.usuario_id = r.usuario_id_cancela) ";
            $sql .= "WHERE ";
            $sql .= "r.id = :recibo_id ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'recibo_id' => $recibo_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene datos de un Recibo determinado.
     * 
     * @param string $folio
     * Identificador de recibo
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectReciboFromFolio(string $folio): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "r.*, CONCAT_WS(' ', usr.nombre, usr.paterno, usr.materno) as usuario, ";
            $sql .= "CONCAT_WS(' ', usr_canc.nombre, usr_canc.paterno, usr_canc.materno) as usuario_cancela ";
            $sql .= "FROM recibos r ";
            $sql .= "INNER JOIN usuarios_datos_generales usr ON (usr.usuario_id = r.usuario_id_updated) ";
            $sql .= "LEFT JOIN usuarios_datos_generales usr_canc ON (usr_canc.usuario_id = r.usuario_id_cancela) ";
            $sql .= "WHERE ";
            $sql .= "r.folio = :folio ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'folio' => $folio
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene la lista de Recibos para un residente determinado
     * 
     * @param int $residente_id
     * Identificador de Residente
     * * Nota: Enviar valor cero para obtener todos los recibos.
     *  
     * @return array $arrResponse
     * 
     */
    public function selectRecibos($residente_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "rec.* ";
            $sql .= "FROM recibos rec ";
            $sql .= "WHERE rec.residente_id = :residente_id and rec.tipo = 1 ";
            $sql .= "ORDER BY rec.id desc";


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
     * Obtiene la lista de Recibos para un periodo determinado (solo tipo 1 - ingresos reales)
     * 
     * @param object $model
     * Envío del modelo RecibosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return array $arrResponse
     * 
     */
    public function selectRecibosPeriodo(RecibosModel $modelo): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "rec.*, CONCAT_WS(' ', res.calle, res.numero) as domicilio, con.concepto as clasificacion_ingreso,  ";
            $sql .= "CONCAT_WS(' ', usr.nombre, usr.paterno, usr.materno) as usuario, ";
            $sql .= "CONCAT_WS(' ', usr_canc.nombre, usr_canc.paterno, usr_canc.materno) as usuario_cancela ";
            $sql .= "FROM recibos rec ";
            $sql .= "INNER JOIN residentes res on (res.id = rec.residente_id) ";
            $sql .= "INNER JOIN conceptos con on (con.id = rec.concepto_id) ";
            $sql .= "INNER JOIN usuarios_datos_generales usr ON (usr.usuario_id = rec.usuario_id_updated) ";
            $sql .= "LEFT JOIN usuarios_datos_generales usr_canc ON (usr_canc.usuario_id = rec.usuario_id_cancela) ";
            $sql .= "WHERE ";
            $sql .= "rec.tipo = 1 and ";
            $sql .= "rec.fecha_pago BETWEEN :fecha_pago_ini and :fecha_pago_fin ";
            $sql .= "ORDER BY rec.folio";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFecha_inicio(),
                'fecha_pago_fin' => $modelo->getFecha_fin()
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
     * Obtiene la lista de Recibos Anteriores para un periodo determinado
     * 
     * @param object $model
     * Envío del modelo RecibosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return array $arrResponse
     * 
     */
    public function selectRecibosAnterioresPeriodo(RecibosModel $modelo): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "rec.*, CONCAT_WS(' ', res.calle, res.numero) as domicilio, ";
            $sql .= "CONCAT_WS(' ', usr_canc.nombre, usr_canc.paterno, usr_canc.materno) as usuario_cancela ";
            $sql .= "FROM recibos rec ";
            $sql .= "INNER JOIN residentes res on (res.id = rec.residente_id) ";
            $sql .= "LEFT JOIN usuarios_datos_generales usr_canc ON (usr_canc.usuario_id = rec.usuario_id_cancela) ";
            $sql .= "WHERE ";
            $sql .= "rec.tipo = 2 and ";
            $sql .= "rec.fecha_pago BETWEEN :fecha_pago_ini and :fecha_pago_fin ";
            $sql .= "ORDER BY rec.folio ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFecha_inicio(),
                'fecha_pago_fin' => $modelo->getFecha_fin()
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
     * Actualiza el estatus a Cancelado de un Recibo determinado.
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
    public function cancelarRecibo(RecibosModel $modelo, int $usuario_id_cancela): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Cancelar Recibo ]*/

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE recibos SET ";
            $sql .= "estatus = :estatus, ";
            $sql .= "motivo_cancela = :motivo_cancela, ";
            $sql .= "fecha_cancela = current_timestamp, ";
            $sql .= "usuario_id_cancela = :usuario_id_cancela ";
            $sql .= "WHERE  ";
            $sql .= "id = :id ";

            /*-------------------------------------------
            [ Parámetros condicionales para realizar el update ]*/
            $arrData = [
                'id' => $modelo->getId(),
                'estatus' => $modelo->getEstatus(),
                'motivo_cancela' => $modelo->getMotivoCancela(),
                'usuario_id_cancela' => $usuario_id_cancela
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo update de MySQL ]*/
            $response = $this->update($sql, $arrData);


            /*-------------------------------------------
            [ Quitar recibo relacionado con el estado de cuente del Residente ]*/
            $this->cancelarReciboCuenta($modelo->getId());


            /*-------------------------------------------
            [ Cambia el estatus del saldo a cuenta del recibo correspondiente, para no poder utilizarlo ]*/
            $this->cancelarReciboCuentaSaldo($modelo->getId());


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

    private function cancelarReciboCuenta($recibo_id)
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE cuenta SET ";
        $sql .= "recibo_id = null, ";
        $sql .= "estatus = 0 ";
        $sql .= "WHERE  ";
        $sql .= "recibo_id = :recibo_id ";

        /*-------------------------------------------
        [ Parámetros condicionales para realizar el update ]*/
        $arrData = [
            'recibo_id' => $recibo_id
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $this->update($sql, $arrData);
    }

    private function cancelarReciboCuentaSaldo($recibo_id)
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE cuenta_saldo SET ";
        $sql .= "estatus = 1 ";
        $sql .= "WHERE  ";
        $sql .= "recibo_id = :recibo_id ";

        /*-------------------------------------------
        [ Parámetros condicionales para realizar el update ]*/
        $arrData = [
            'recibo_id' => $recibo_id
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $this->update($sql, $arrData);
    }

    /**
     * Obtiene la cantidad total de recibos expedidos de un periodo determinado.
     * 
     * @param object $model
     * Envío del modelo RecibosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return int $total_recibos
     * 
     */
    public function selectTotalRecibosExpedidosPeriodo(RecibosModel $modelo): int
    {

        try {

            $total_recibos = 0;

            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT ";
            $sql .= "count(rec.id) as total_recibos ";
            $sql .= "FROM recibos rec ";
            $sql .= "WHERE ";
            $sql .= "rec.tipo = 1 and ";
            $sql .= "rec.fecha_pago BETWEEN :fecha_pago_ini AND :fecha_pago_fin ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFecha_inicio(),
                'fecha_pago_fin' => $modelo->getFecha_fin()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                if ($arrResponse['total_recibos'] == '') {
                    $total_recibos = 0;
                } else {
                    $total_recibos = $arrResponse['total_recibos'];
                }
            } else {
                $total_recibos = 0;
            }
        } catch (\Throwable $th) {
            $total_recibos = 0;
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $total_recibos;
    }

    /**
     * Obtiene el importe total utilizado en recibos expedidos de un periodo determinado.
     * 
     * @param object $model
     * Envío del modelo RecibosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return int $importe_saldo_utilizado
     * 
     */
    public function selectTotalSaldoUtilizadoPeriodo(RecibosModel $modelo): int
    {

        try {

            $importe_saldo_utilizado = 0;

            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT ";
            $sql .= "sum(rec.saldo_utilizado) as importe_saldo_utilizado ";
            $sql .= "FROM recibos rec ";
            $sql .= "WHERE ";
            $sql .= "rec.estatus = 0 and rec.tipo = 1 and ";
            $sql .= "rec.fecha_pago BETWEEN :fecha_pago_ini AND :fecha_pago_fin ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFecha_inicio(),
                'fecha_pago_fin' => $modelo->getFecha_fin()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                if ($arrResponse['importe_saldo_utilizado'] == '') {
                    $importe_saldo_utilizado = 0;
                } else {
                    $importe_saldo_utilizado = $arrResponse['importe_saldo_utilizado'];
                }
            } else {
                $importe_saldo_utilizado = 0;
            }
        } catch (\Throwable $th) {
            $importe_saldo_utilizado = 0;
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $importe_saldo_utilizado;
    }

    /**
     * Obtiene el importe total dejado a cuenta en recibos expedidos de un periodo determinado.
     * 
     * @param object $model
     * Envío del modelo RecibosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return int $importe_dejado_a_cuenta
     * 
     */
    public function selectTotalDejadoACuentaPeriodo(RecibosModel $modelo): int
    {

        try {

            $importe_dejado_a_cuenta = 0;

            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT ";
            $sql .= "sum(rec.deja_cuenta) as importe_dejado_a_cuenta ";
            $sql .= "FROM recibos rec ";
            $sql .= "WHERE ";
            $sql .= "rec.estatus = 0 and rec.tipo = 1 and ";
            $sql .= "rec.fecha_pago BETWEEN :fecha_pago_ini AND :fecha_pago_fin ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFecha_inicio(),
                'fecha_pago_fin' => $modelo->getFecha_fin()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (count($arrResponse) > 0) {
                if ($arrResponse['importe_dejado_a_cuenta'] == '') {
                    $importe_dejado_a_cuenta = 0;
                } else {
                    $importe_dejado_a_cuenta = $arrResponse['importe_dejado_a_cuenta'];
                }
            } else {
                $importe_dejado_a_cuenta = 0;
            }
        } catch (\Throwable $th) {
            $importe_dejado_a_cuenta = 0;
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $importe_dejado_a_cuenta;
    }

    // /**
    //  * Actualiza el estatus a Cancelado de un Recibo determinado para Otros Conceptos
    //  * 
    //  * @param object $model
    //  * Envío del modelo por valor, que contine la información a actualizar y 
    //  * los parámetros condicionales para realizar el update.
    //  *
    //  * 
    //  * @param int $usuario_id_register
    //  * Identificador de usuario que realiza el registro
    //  * 
    //  * 
    //  * @return bool $response
    //  * * true - indica que fue exitoso.
    //  * * false - en caso de falla.
    //  * 
    //  */
    // public function cancelarReciboOtrosConceptos(RecibosModel $modelo, int $usuario_id_register): bool
    // {

    //     try {

    //         $response = true;

    //         /*-------------------------------------------
    //         [ Begin Transaction ]*/
    //         $this->getConexion()->beginTransaction();

    //         /*-------------------------------------------
    //         [ Cancelar Recibo Otros Conceptos ]*/

    //         /*-------------------------------------------
    //         [ Instruccion sql ]*/
    //         $sql = "UPDATE recibos SET ";
    //         $sql .= "estatus = :estatus, ";
    //         $sql .= "updated_at = current_timestamp, ";
    //         $sql .= "usuario_id_updated = :usuario_id_register ";
    //         $sql .= "WHERE  ";
    //         $sql .= "id = :id ";

    //         /*-------------------------------------------
    //         [ Parámetros condicionales para realizar el update ]*/
    //         $arrData = [
    //             'id' => $modelo->getId(),
    //             'estatus' => $modelo->getEstatus(),
    //             'usuario_id_register' => $usuario_id_register
    //         ];

    //         /*-------------------------------------------
    //         [ Ejecuta el Metodo update de MySQL ]*/
    //         $response = $this->update($sql, $arrData);


    //         /*-------------------------------------------
    //         [ Commit Transaction ]*/
    //         $this->getConexion()->commit();
    //     } catch (\Throwable $th) {

    //         /*-------------------------------------------
    //         [ RollBack ]*/
    //         $this->getConexion()->rollBack();
    //         getLoggerSystem()->error(getMensajeError($th));
    //         $response = false;
    //     }

    //     /*-------------------------------------------
    //     [ Retorna bool ]*/
    //     return $response;
    // }

    /**
     * Guardar datos del Nuevo Recibo.
     * 
     * @param object &$model RecibosModel
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
    public function insertReciboOtrosConceptos(RecibosModel &$modelo, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Recibo ]*/
            $recibo_id =  $this->reciboCreateOtrosConceptos($modelo, $usuario_id_register);
            $modelo->setId($recibo_id);


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
     * Subrutina dentro de insertRecibo para crear el Recibo
     * 
     * @param object RecibosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function reciboCreateOtrosConceptos(RecibosModel &$model,  int $usuario_id_register): int
    {

        try {
            $result = 0;

            $porc_descuento = 0;
            $deja_cuenta = 0;
            $saldo_utilizado = 0;

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "INSERT INTO recibos SET ";
            $sql .= "residente = :residente, ";
            $sql .= "residente_id = :residente_id, ";
            $sql .= "folio = :folio, ";
            $sql .= "importe = :importe, ";
            $sql .= "concepto = :concepto, ";
            $sql .= "concepto_id = :concepto_id, ";
            $sql .= "estatus = 0, ";
            $sql .= "calle = :calle, ";
            $sql .= "numero = :numero, ";
            $sql .= "subtotal = :subtotal, ";
            $sql .= "porc_descuento = :porc_descuento, ";
            $sql .= "deja_cuenta = :deja_cuenta, ";
            $sql .= "recibe = :recibe, ";
            $sql .= "cambio = :cambio, ";
            $sql .= "saldo_utilizado = :saldo_utilizado, ";
            $sql .= "cantidad = :cantidad, ";
            $sql .= "tipo = 1, ";
            $sql .= "fecha_pago = current_timestamp, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";

            /*-------------------------------------------
            [ Datos a insertar ]*/
            $arrData = [
                'residente_id' => $model->getResidente_id(),
                'folio' => $model->getFolio(),
                'residente' => $model->getResidente(),
                'importe' => $model->getImporte(),
                'concepto_id' => $model->getConcepto_id(),
                'concepto' =>  $model->getConcepto(),
                'calle' => $model->getCalle(),
                'numero' => $model->getNumero(),
                'subtotal' => $model->getImporte(),
                'porc_descuento' => $porc_descuento,
                'deja_cuenta' => $deja_cuenta,
                'recibe' => $model->getRecibe(),
                'cambio' => $model->getCambio(),
                'cantidad' => $model->getCantidad(),
                'saldo_utilizado' => $saldo_utilizado,
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
            [ Actulizar Dato del Folio de recibo ]*/
            // $lastInsertId_formatted = str_pad($lastInsertId, 6, "0", STR_PAD_LEFT);
            // $folio = "A-" . $lastInsertId_formatted;
            // $model->setFolio($folio);
            // $sql = "";
            // $sql = "UPDATE recibos SET ";
            // $sql .= "folio = :folio ";
            // $sql .= "WHERE ";
            // $sql .= "id = :id ";

            // $arrDataUpdate = [
            //     'folio' => $folio,
            //     'id' => $lastInsertId
            // ];

            /*-------------------------------------------
            [ Ejecuta el Metodo udpate de MySQL ]*/
            // $this->update($sql, $arrDataUpdate);

            /*-------------------------------------------
            [ Retorna Id de Recibo Insertado ]*/
            return $result;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    /**
     * Obtiene el nuevo numero de folio asignado para el recibo de cobro.
     * 
     * @return string $folio
     * 
     */
    public function getNewFolioCobro(): string
    {

        try {

            $folio = "";

            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT ";
            $sql .= "max(folio) as last_folio ";
            $sql .= "FROM recibos rec ";
            $sql .= "WHERE ";
            $sql .= "folio like 'A%' ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

            /*-------------------------------------------
                [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if (empty($arrResponse)) {
                return "";
            }
            if ($arrResponse['last_folio'] != '') {
                $last_folio = $arrResponse['last_folio'];
                $folio_temp = substr($last_folio, 2);
                $rest = intval($folio_temp);
                $rest += 1;

                $folio = 'A-' . str_pad($rest, 6, "0", STR_PAD_LEFT);
            }

            /*-------------------------------------------
            [ Retorna array con la lista de registros o empty en caso de error ]*/
            return $folio;
        } catch (\Throwable $th) {
            return "";
        }
    }

    /**
     * Guardar datos de Transferencia.
     * 
     * @param array $arrTransfer
     * Envío del arreglo con los datos requridos para relaizar la transferecnia del recibo.
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
    public function updateTransferirRecibo(array $arrDatosTransferencia, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Transferencia ]*/
            $result =  $this->transferirReciboUpdate($arrDatosTransferencia, $usuario_id_register);

            /*-------------------------------------------
            [ Evalua Respuesta ]*/
            if ($result == true) {

                /* -- Commit Transaction -- ]*/
                $response = true;
                $this->getConexion()->commit();
            } else {

                $this->getConexion()->rollBack();
            }
        } catch (\Throwable $th) {

            /* RollBack ]*/
            $this->getConexion()->rollBack();
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Subrutina dentro de updateTransferirRecibo para transferir el Recibo
     * 
     * @param object RecibosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function transferirReciboUpdate(array $arrDatosTransferencia, int $usuario_id_register): bool
    {

        $result = false;

        /*-------------------------------------------
        [ Se obtienen las cuentas asociadas al recibo de origen ]*/
        $sql = "SELECT id, mes, anio FROM cuenta ";
        $sql .= "WHERE ";
        $sql .= "recibo_id = :recibo_id and ";
        $sql .= "residente_id = :residente_id";
        $arrData = [
            'residente_id' => $arrDatosTransferencia['residente_id_origen'],
            'recibo_id' => $arrDatosTransferencia['recibo_id']
        ];
        $arrResponse = $this->select($sql, $arrData);

        /*-------------------------------------------
        [ Eliminar recibo de cuentas asociadas al residente ]*/
        for ($i = 0; $i < count($arrResponse); $i++) {

            $sql_for_del = "UPDATE cuenta SET ";
            $sql_for_del .= "estatus = 0, ";
            $sql_for_del .= "recibo_id = null ";
            $sql_for_del .= "WHERE ";
            $sql_for_del .= "id = :id ";
            $arrDataCuentaForDel = ['id' => $arrResponse[$i]['id']];
            $result = $this->update($sql_for_del, $arrDataCuentaForDel);
            if ($result == false) {
                return false;
            }
        }

        /*-------------------------------------------
        [ Actualizar rergoistros para el residente destino ]*/
        for ($j = 0; $j < count($arrResponse); $j++) {

            $sql_cuenta = "UPDATE cuenta SET ";
            $sql_cuenta .= "estatus = 1, ";
            $sql_cuenta .= "recibo_id = :recibo_id, ";
            $sql_cuenta .= "updated_at = current_timestamp, ";
            $sql_cuenta .= "usuario_id_updated = :usuario_id_register ";
            $sql_cuenta .= "WHERE ";
            $sql_cuenta .= "residente_id = :residente_id and ";
            $sql_cuenta .= "mes = :mes and ";
            $sql_cuenta .= "anio = :anio ";
            $arrDataCuentaNew = [
                'recibo_id' => $arrDatosTransferencia['recibo_id'],
                'residente_id' => $arrDatosTransferencia['residente_id_destino'],
                'mes' => $arrResponse[$j]['mes'],
                'anio' => $arrResponse[$j]['anio'],
                'usuario_id_register' => $usuario_id_register
            ];
            $result = $this->update($sql_cuenta, $arrDataCuentaNew);
            if ($result == false) {
                return false;
            }
        }


        /*-------------------------------------------
        [ Actualizar Datos de recibo para el residente destino ]*/
        $sql_recibo = "UPDATE recibos SET ";
        $sql_recibo .= "residente_id = :residente_id, ";
        $sql_recibo .= "residente = :residente, ";
        $sql_recibo .= "calle = :calle, ";
        $sql_recibo .= "numero = :numero, ";
        $sql_recibo .= "mza = :mza, ";
        $sql_recibo .= "lote = :lote ";
        $sql_recibo .= "WHERE ";
        $sql_recibo .= "id = :id ";
        $arrDataRecibo = [
            'id' => $arrDatosTransferencia['recibo_id'],
            'residente_id' => $arrDatosTransferencia['residente_id_destino'],
            'residente' => $arrDatosTransferencia['residente_destino']['residente'],
            'calle' => $arrDatosTransferencia['residente_destino']['calle'],
            'numero' => $arrDatosTransferencia['residente_destino']['numero'],
            'mza' => $arrDatosTransferencia['residente_destino']['mza'],
            'lote' => $arrDatosTransferencia['residente_destino']['lote']
        ];
        $result = $this->update($sql_recibo, $arrDataRecibo);
        if ($result == false) {
            return false;
        }

        /*-------------------------------------------
        [ Retorna Id de Recibo Insertado ]*/
        return $result;
    }

    /**
     * Subrutina dentro de updateTransferirRecibo para transferir el Recibo
     * 
     * @param object RecibosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function validaDestinoTranferenciaRecibo(array $arrDatosTransferencia): bool
    {

        try {

            $result = false;

            /*-------------------------------------------
            [ Se obtienen las cuentas asociadas al recibo de origen ]*/
            $sql = "SELECT id, mes, anio FROM cuenta ";
            $sql .= "WHERE ";
            $sql .= "recibo_id = :recibo_id and ";
            $sql .= "residente_id = :residente_id";
            $arrData = [
                'residente_id' => $arrDatosTransferencia['residente_id_origen'],
                'recibo_id' => $arrDatosTransferencia['recibo_id']
            ];
            $arrResponse = $this->select($sql, $arrData);

            /*-------------------------------------------
            [ Eliminar recibo de cuentas asociadas al residente ]*/
            for ($i = 0; $i < count($arrResponse); $i++) {

                $sql = "SELECT recibo_id FROM cuenta ";
                $sql .= "WHERE ";
                $sql .= "mes = :mes and ";
                $sql .= "anio = :anio and ";
                $sql .= "residente_id = :residente_id_destino";
                $arrData = [
                    'residente_id_destino' => $arrDatosTransferencia['residente_id_destino'],
                    'mes' => $arrResponse[$i]['mes'],
                    'anio' => $arrResponse[$i]['anio']
                ];
                $arrResponse = $this->selectModel($sql, $arrData);
                $val_ref = intval($arrResponse['recibo_id']);
                if ($val_ref == 0) {
                    $result = true;
                }
            }
        } catch (\Throwable $th) {
        }

        return $result;
    }

    /**
     * Obtiene arreglo con la lista de ingresos desglosado por concepto
     * 
     * @param object RecibosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     */
    public function selectIngresosDesglose(RecibosModel &$model): array
    {


        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "c.concepto, sum(r.importe) as importe ";
            $sql .= "FROM recibos r ";
            $sql .= "INNER JOIN conceptos c ON (c.id = r.concepto_id) ";
            $sql .= "WHERE ";
            $sql .= "r.tipo = 1 and r.estatus = 0 and ";
            $sql .= "r.fecha_pago between :fecha_filtro_ini and :fecha_filtro_fin ";
            $sql .= "GROUP BY r.concepto_id ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arrData = [
                'fecha_filtro_ini' => $model->getFecha_inicio(),
                'fecha_filtro_fin' => $model->getFecha_fin()
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arrData);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }


    /**
     * Validación de Recibos de Cobro Residente Cuentas
     * 
     * @response $arrResponse, donde:
     * * respuesta (string). Valores 'ok'/'error'. 'ok' en caso de que la respuesta sea existosa repsonde = '', caso contrario = 'error'.
     * * mostrar_mensaje (bool). Valores true/false en caso de que se desee mostrar modal con los resultados de la respuesta.
     * * tiempo (int). Total de segundos que desea que se muestre el mensaje modal de la respuesta.
     * * mensaje (string). Contiene el mensaje que aparecerá en el modal.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function validaResidenteCuentas($cuenta_id, $residente_id, &$err_response): bool
    {

        try {

            $result = true;

            /*-------------------------------------------
            [ Se obtienen las cuentas asociadas al recibo de origen ]*/
            $sql = "SELECT residente_id FROM cuenta ";
            $sql .= "WHERE ";
            $sql .= "id = :cuenta_id ";

            $arrData = [
                'cuenta_id' => $cuenta_id
            ];
            $arrResponse = $this->selectModel($sql, $arrData);
            if (empty($arrResponse)) {
                $err_response = "El mes de adeudo no fue seleccionado correctamente";
                return false;
            } else {
                $residente_id_db = $arrResponse['residente_id'];
                if ($residente_id != $residente_id_db) {
                    $err_response = "Hubo un error al seleccionar el mes de adeudo, inicie nuevamente del proceso desde seleccionar al Residente y dar click en Nuevo";
                    return false;
                }
            }
        } catch (\Throwable $th) {
        }

        return $result;
    }




    /*==============================================
    [ Getters & Setteres ]*/

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
     * Get the value of concepto
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * Set the value of concepto
     *
     * @return  self
     */
    public function setConcepto($concepto)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get the value of folio
     */
    public function getFolio()
    {
        return $this->folio;
    }

    /**
     * Set the value of folio
     *
     * @return  self
     */
    public function setFolio($folio)
    {
        $this->folio = $folio;

        return $this;
    }

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
     * Get the value of residente
     */
    public function getResidente()
    {
        return $this->residente;
    }

    /**
     * Set the value of residente
     *
     * @return  self
     */
    public function setResidente($residente)
    {
        $this->residente = $residente;

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
     * Get the value of numero
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get the value of mza
     */
    public function getMza()
    {
        return $this->mza;
    }

    /**
     * Set the value of mza
     *
     * @return  self
     */
    public function setMza($mza)
    {
        $this->mza = $mza;

        return $this;
    }

    /**
     * Get the value of lote
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set the value of lote
     *
     * @return  self
     */
    public function setLote($lote)
    {
        $this->lote = $lote;

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
     * Get the value of temp
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     * Set the value of temp
     *
     * @return  self
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;

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
     * Get the value of mes_no
     */
    public function getMes_no()
    {
        return $this->mes_no;
    }

    /**
     * Set the value of mes_no
     *
     * @return  self
     */
    public function setMes_no($mes_no)
    {
        $this->mes_no = $mes_no;

        return $this;
    }

    /**
     * Get the value of subtotal
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set the value of subtotal
     *
     * @return  self
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get the value of porc_descuento
     */
    public function getPorc_descuento()
    {
        return $this->porc_descuento;
    }

    /**
     * Set the value of porc_descuento
     *
     * @return  self
     */
    public function setPorc_descuento($porc_descuento)
    {
        $this->porc_descuento = $porc_descuento;

        return $this;
    }

    /**
     * Get the value of deja_cuenta
     */
    public function getDeja_cuenta()
    {
        return $this->deja_cuenta;
    }

    /**
     * Set the value of deja_cuenta
     *
     * @return  self
     */
    public function setDeja_cuenta($deja_cuenta)
    {
        $this->deja_cuenta = $deja_cuenta;

        return $this;
    }

    /**
     * Get the value of recibe
     */
    public function getRecibe()
    {
        return $this->recibe;
    }

    /**
     * Set the value of recibe
     *
     * @return  self
     */
    public function setRecibe($recibe)
    {
        $this->recibe = $recibe;

        return $this;
    }

    /**
     * Get the value of cambio
     */
    public function getCambio()
    {
        return $this->cambio;
    }

    /**
     * Set the value of cambio
     *
     * @return  self
     */
    public function setCambio($cambio)
    {
        $this->cambio = $cambio;

        return $this;
    }

    /**
     * Get the value of cantidad
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get the value of pagos_adeudos
     */
    public function getPagos_adeudos()
    {
        return $this->pagos_adeudos;
    }

    /**
     * Set the value of pagos_adeudos
     *
     * @return  self
     */
    public function setPagos_adeudos($pagos_adeudos)
    {
        $this->pagos_adeudos = $pagos_adeudos;

        return $this;
    }

    /**
     * Get the value of pagos_adelantados
     */
    public function getPagos_adelantados()
    {
        return $this->pagos_adelantados;
    }

    /**
     * Set the value of pagos_adelantados
     *
     * @return  self
     */
    public function setPagos_adelantados($pagos_adelantados)
    {
        $this->pagos_adelantados = $pagos_adelantados;

        return $this;
    }

    /**
     * Get the value of tipo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of usar_saldo
     */
    public function getUsar_saldo()
    {
        return $this->usar_saldo;
    }

    /**
     * Set the value of usar_saldo
     *
     * @return  self
     */
    public function setUsar_saldo($usar_saldo)
    {
        $this->usar_saldo = $usar_saldo;

        return $this;
    }

    /**
     * Get the value of saldo_a_cuenta
     */
    public function getSaldo_a_cuenta()
    {
        return $this->saldo_a_cuenta;
    }

    /**
     * Set the value of saldo_a_cuenta
     *
     * @return  self
     */
    public function setSaldo_a_cuenta($saldo_a_cuenta)
    {
        $this->saldo_a_cuenta = $saldo_a_cuenta;

        return $this;
    }

    /**
     * Get the value of saldo_utilizado
     */
    public function getSaldo_utilizado()
    {
        return $this->saldo_utilizado;
    }

    /**
     * Set the value of saldo_utilizado
     *
     * @return  self
     */
    public function setSaldo_utilizado($saldo_utilizado)
    {
        $this->saldo_utilizado = $saldo_utilizado;

        return $this;
    }

    /**
     * Get the value of fecha_inicio
     */
    public function getFecha_inicio()
    {
        return $this->fecha_inicio;
    }

    /**
     * Set the value of fecha_inicio
     *
     * @return  self
     */
    public function setFecha_inicio($fecha_inicio)
    {
        $this->fecha_inicio = $fecha_inicio;

        return $this;
    }

    /**
     * Get the value of fecha_fin
     */
    public function getFecha_fin()
    {
        return $this->fecha_fin;
    }

    /**
     * Set the value of fecha_fin
     *
     * @return  self
     */
    public function setFecha_fin($fecha_fin)
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    /**
     * Get the value of motivo_cancela
     */
    public function getMotivoCancela()
    {
        return $this->motivo_cancela;
    }

    /**
     * Set the value of motivo_cancela
     */
    public function setMotivoCancela($motivo_cancela): self
    {
        $this->motivo_cancela = $motivo_cancela;

        return $this;
    }

    /**
     * Get the value of usuario_id_cancela
     */
    public function getUsuarioIdCancela()
    {
        return $this->usuario_id_cancela;
    }

    /**
     * Set the value of usuario_id_cancela
     */
    public function setUsuarioIdCancela($usuario_id_cancela): self
    {
        $this->usuario_id_cancela = $usuario_id_cancela;

        return $this;
    }
}
