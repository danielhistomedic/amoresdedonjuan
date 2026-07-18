<?php

/**
 * Clase RecibosAnterioresModel
 */
class RecibosAnterioresModel extends Mysql
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
    private $folio_anterior;
    private $archivo;


    /**
     * Método Constructor de RecibosAnterioresModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Guardar datos del Nuevo Recibo Anterior.
     * 
     * @param object &$model RecibosAnterioresModel
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
    public function insertRecibo(RecibosAnterioresModel &$modelo, $files, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Recibo Anterior ]*/
            $recibo_id =  $this->reciboCreate($modelo, $usuario_id_register);
            if ($recibo_id > 0) {

                $modelo->setId($recibo_id);

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

                // /*-------------------------------------------
                // [ Guardar Archivo Adjunto ]*/
                if (count($files) > 0) {
                    if ($files['name'] != '') {
                        $att_name = $modelo->getArchivo();
                        $archivo =  $this->reciboAnteriorUpload($recibo_id, $files, $att_name);
                        $modelo->setArchivo($archivo);

                        /*-------------------------------------------
                    [ Actualiza el Nombre del Archivo Adjunto ]*/
                        $this->reciboAnteriorUpdateNombreArchivo($archivo, $recibo_id);
                    }
                }
            } else {

                $this->getConexion()->rollBack();
            }
        } catch (\Throwable $th) {

            /*-------------------------------------------
            [ RollBack ]*/
            $this->getConexion()->rollBack();
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Subrutina dentro de insertRecibo para crear el Recibo Anterior
     * 
     * @param object RecibosAnterioresModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function reciboCreate(RecibosAnterioresModel &$model,  int $usuario_id_register): int
    {

        $result = 0;

        //Obtener el nuevo nuemro de folio a asignar al recibo de cobro nuevo
        $folio_nuevo = $this->getNewFolioCobroAnteriores();

        if ($folio_nuevo != '') {

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
            $sql .= "cantidad = :cantidad, ";
            $sql .= "tipo = 2, ";
            $sql .= "folio_anterior = :folio_anterior, ";
            $sql .= "fecha_pago = current_timestamp, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";

            /*-------------------------------------------
            [ Datos a insertar ]*/
            $arrData = [
                'residente_id' => $model->getResidente_id(),
                'folio' => $folio_nuevo,
                'residente' => $model->getResidente(),
                'importe' => $model->getImporte(),
                'concepto_id' => $model->getConcepto_id(),
                'concepto' => $model->getConcepto(),
                'calle' => $model->getCalle(),
                'numero' => $model->getNumero(),
                'subtotal' => $model->getSubtotal(),
                'porc_descuento' => $model->getPorc_descuento(),
                'deja_cuenta' => $model->getDeja_cuenta(),
                'recibe' => $model->getRecibe(),
                'cambio' => $model->getCambio(),
                'cantidad' => $model->getCantidad(),
                'folio_anterior' => $model->getFolio_anterior(),
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
        }


        /*-------------------------------------------
        [ Retorna Id de Recibo Insertado ]*/
        return $result;
    }

    /**
     * Subrutina dentro de insertRecibo para registrar el Pago de Adeduos en la Cuenta correspondiente
     * 
     * @param object RecibosAnterioresModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function reciboCreatePagoAdeudos(RecibosAnterioresModel &$model,  int $usuario_id_register): void
    {

        $arrAdeudos = $model->getPagos_adeudos();

        if (count($arrAdeudos) > 0) {

            $subtotal = $model->getSubtotal();

            $total_registros = count($arrAdeudos);
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
     * Subrutina dentro de insertRecibo para registrar el Pago de los que deja a Cuenta.
     * 
     * @param object RecibosAnterioresModel $model
     * Envío del modelo por valor con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function reciboCreateDejaCuenta(RecibosAnterioresModel $model,  int $usuario_id_register): void
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
     * Subrutina dentro de insertRecibo cargar el archivo adjunto en caso de enviarlo
     * 
     * @param int $recibo_id 
     * Identificador de Recibo
     *  
     * @param array $files
     * array con el contenido del archivo a cargar
     * 
     * @param string $adjunto_name
     * Nombre del archivo a cargar 
     * 
     * @return string $archivo
     * Nombre del Archivo cargado.
     * 
     */
    public function reciboAnteriorUpload($recibo_id, $files, $adjunto_name): string
    {

        // error: 4 Vacio
        // error: 0 Contiene Datos

        $archivo = '';

        $hash_value = $recibo_id;
        $fecha  = date('YmdHis');
        $hash_base = $hash_value . $fecha . $files['name'];
        $prefijo = encode($hash_base);

        $name = $files['name'];
        $tmp_name = $files['tmp_name'];
        $error = $files['error'];

        $max_size = 1024 * 1024 * 10;
        $size = $files['size'];

        $type = $files['type'];
        $arrType = explode('/', $type);
        $type = $arrType[1];


        if ($name == '') {
        } else if ($error > 0) {
        } else if ($size > $max_size) {
        } else {


            //*==================================================================
            // [ Delete File Anterior ]*/
            if ($adjunto_name != "") {
                $path_to_file = "Assets/files/";
                $file_delete =  $path_to_file . $adjunto_name;
                unlink($file_delete);
            }

            //*==================================================================
            // [ Upload File ]*/
            $ruta_doctos = "Assets/files/";
            $file_name_destination = $prefijo . '.' . $type;
            // $file_name_destination = $prefijo . '_' . $name;
            $destination =  $ruta_doctos . $file_name_destination;
            $upload_response =  move_uploaded_file($tmp_name, $destination);

            /*-------------------------------------------
            [ Asigan el nombre del archivo guardado al modelo ]*/
            if ($upload_response == true) {
                $archivo = $file_name_destination;
            } else {
                $archivo = '';
            }
        }

        return $archivo;
    }

    /**
     * Subrutina dentro de insertRecibo actaulizar el nombre del archivo adjunto
     * 
     * @param string $archivo
     * Nombre del Archivo cargado
     * 
     * @param int $recibo_id
     * Identificador de Recibo Anterior
     * 
     */
    public function reciboAnteriorUpdateNombreArchivo(string $archivo, int $recibo_id): void
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE recibos SET ";
        $sql .= "archivo = :archivo ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $recibo_id,
            'archivo' => $archivo
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);
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
     * Obtiene la lista de Recibos Anteriores para un residente determinado
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
            $whereAdmin = "";
            if ($residente_id > 0) {
                $whereAdmin = "WHERE rec.residente_id = :residente_id and rec.tipo = 2 ";

                /*-------------------------------------------
                [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
                $arr_values = [
                    'residente_id' => $residente_id
                ];
            } else {
                $whereAdmin = "WHERE rec.tipo = 1 ";

                /*-------------------------------------------
                [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
                $arr_values = [];
            }

            $sql = "SELECT ";
            $sql .= "rec.* ";
            $sql .= "FROM recibos rec ";
            $sql .= $whereAdmin;
            $sql .= "ORDER BY rec.anio desc, rec.mes desc ";


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
     * Actualiza el estatus a Cancelado de un Recibo Anterior determinado.
     * 
     * @param object RecibosAnterioresModel $model
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
    public function cancelarRecibo(RecibosAnterioresModel $modelo, int $usuario_id_register): bool
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
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE  ";
            $sql .= "id = :id ";

            /*-------------------------------------------
            [ Parámetros condicionales para realizar el update ]*/
            $arrData = [
                'id' => $modelo->getId(),
                'estatus' => $modelo->getEstatus(),
                'usuario_id_register' => $usuario_id_register
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
     * Obtiene el nuevo numero de folio asignado para el recibo de cobro.
     * 
     * @return string $folio
     * 
     */
    private function getNewFolioCobroAnteriores(): string
    {

        try {

            $folio = "";

            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT ";
            $sql .= "max(folio) as last_folio  ";
            $sql .= "FROM recibos rec ";
            $sql .= "WHERE ";
            $sql .= "folio like 'B%' ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->selectModel($sql, $arr_values);
            if ($arrResponse['last_folio'] != '') {

                $last_folio = $arrResponse['last_folio'];
                $rest = substr($last_folio, 2);
                $rest = intval($rest);
                $rest += 1;
                $folio = 'B-' . str_pad($rest, 6, "0", STR_PAD_LEFT);
            } else {

                $rest = 1;
                $folio = 'B-' . str_pad($rest, 6, "0", STR_PAD_LEFT);
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $folio;
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
     * Get the value of folio_anterior
     */
    public function getFolio_anterior()
    {
        return $this->folio_anterior;
    }

    /**
     * Set the value of folio_anterior
     *
     * @return  self
     */
    public function setFolio_anterior($folio_anterior)
    {
        $this->folio_anterior = $folio_anterior;

        return $this;
    }

    /**
     * Get the value of archivo
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set the value of archivo
     *
     * @return  self
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }
}
