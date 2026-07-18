<?php

use Spipu\Html2Pdf\Tag\Html\I;

/**
 * Clase GastosModel
 */
class GastosModel extends Mysql
{

    private $Id;
    private $clasificacion_gasto_id;
    private $descripcion;
    private $importe;
    private $archivo;
    private $proveedor;
    private $fecha_docto;
    // private $fecha_pago;
    private $folio_nota;


    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;

    //Opciones de Filtro
    private $fecha_inicio;
    private $fecha_fin;

    /**
     * Método Constructor de GastosModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista de Gastos.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectGastos(): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/

            $sql = "SELECT ";
            $sql .= "g.*, cg.clasificacion ";
            $sql .= "FROM gastos g ";
            $sql .= "INNER JOIN clasificacion_gastos cg on (cg.id = g.clasificacion_gasto_id) ";
            $sql .= "WHERE ";
            $sql .= "g.estatus = 0 ";
            $sql .= "ORDER BY g.created_at desc ";

            /*-------------------------------------------
                [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

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
     * Obtiene datos de un Gasto determinado.
     * 
     * @param int $gasto_id
     * Identificador de Gasto
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectGasto(int $gasto_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "g.*, cg.clasificacion, CONCAT_WS(' ', usr_dg.nombre, usr_dg.paterno, usr_dg.materno) AS usuario ";
            $sql .= "FROM gastos g ";
            $sql .= "INNER JOIN usuarios_datos_generales usr_dg ON (usr_dg.usuario_id = g.usuario_id_updated) ";
            $sql .= "INNER JOIN clasificacion_gastos cg on (cg.id = g.clasificacion_gasto_id) ";
            $sql .= "WHERE ";
            $sql .= "g.id = :gasto_id ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'gasto_id' => $gasto_id
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
     * Guardar datos del Nuevo Gasto.
     * 
     * @param object &$model GastosModel
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
    public function insertGasto(GastosModel &$modelo, $files, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Clasificación ]*/
            $gasto_id =  $this->gastoCreate($modelo, $usuario_id_register);
            $modelo->setId($gasto_id);


            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();


            // /*-------------------------------------------
            // [ Guardar Archivo Adjunto ]*/
            if (count($files) > 0) {
                if ($files['name'] != '') {
                    $att_name = $modelo->getArchivo();
                    $archivo =  $this->gastoUpload($gasto_id, $files, $att_name);
                    $modelo->setArchivo($archivo);

                    /*-------------------------------------------
                    [ Actualiza el Nombre del Archivo Adjunto ]*/
                    $this->gastoUpdateNombreArchivo($archivo, $gasto_id);
                }
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

    /**
     * Subrutina dentro de insertGasto para crear el Gasto
     * 
     * @param object GastosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function gastoCreate(GastosModel &$model,  int $usuario_id_register): int
    {

        $result = 0;

        $fecha_docto = formatDate_DB($model->getFecha_docto());
        // $fecha_pago = formatDate_DB($model->getFecha_pago());
        $fecha_pago = date('Y-m-d');

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO gastos SET ";
        $sql .= "clasificacion_gasto_id = :clasificacion_gasto_id, ";
        $sql .= "descripcion = :descripcion, ";
        $sql .= "importe = :importe, ";
        $sql .= "proveedor = :proveedor, ";
        $sql .= "folio_nota = :folio_nota, ";
        $sql .= "fecha_docto = :fecha_docto, ";
        $sql .= "fecha_pago = :fecha_pago, ";
        $sql .= "estatus = 0, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'clasificacion_gasto_id' => $model->getClasificacion_gasto_id(),
            'descripcion' => $model->getDescripcion(),
            'importe' => $model->getImporte(),
            'proveedor' => $model->getProveedor(),
            'folio_nota' => $model->getFolio_nota(),
            'fecha_docto' => $fecha_docto,
            'fecha_pago' => $fecha_pago,
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
     * Subrutina dentro de insertGasto cargar el archivo adjunto en caso de enviarlo
     * 
     * @param int $unidad_medica_id 
     * Identificador de unidad medica 
     * @param array $files
     * array con el contenido del archivo a cargar
     * @param string $logo_name
     * Nombre del archivo a cargar 
     * 
     */
    public function gastoUpload($gasto_id, $files, $adjunto_name): string
    {

        // error: 4 Vacio
        // error: 0 Contiene Datos

        $archivo = '';

        $hash_value = $gasto_id;
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
     * Subrutina dentro de insertGasto actaulizar el nombre del archivo del gasto
     * 
     * @param string $archivo
     * Identificador de Gasto
     * 
     * @param int $gasto_id
     * Identificador de Gasto
     * 
     */
    public function gastoUpdateNombreArchivo(string $archivo, int $gasto_id): void
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE gastos SET ";
        $sql .= "archivo = :archivo ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $gasto_id,
            'archivo' => $archivo
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);
    }

    /**
     * Actualizar Datos de Gasto.
     * 
     * @param object &$model 
     * Envío de object GastosModel por referencia, que contine la información
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
    public function updateGasto(GastosModel &$modelo, $files, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            // /*-------------------------------------------
            // [ Guardar Datos de Clasificación ]*/
            $this->gastoUpdate($modelo, $usuario_id_register);

            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();

            // /*-------------------------------------------
            // [ Guardar Archivo Adjunto ]*/
            if (count($files) > 0) {
                if ($files['name'] != '') {
                    $att_name = $modelo->getArchivo();
                    $archivo =  $this->gastoUpload($modelo->getId(), $files, $att_name);
                    $modelo->setArchivo($archivo);
                    /*-------------------------------------------
                    [ Actualiza el Nombre del Archivo Adjunto ]*/
                    $this->gastoUpdateNombreArchivo($archivo, $modelo->getId());
                }
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

    /**
     * Subrutina dentro de updateGasto para actualizar datos de Gasto
     * 
     * @param object $model 
     * Envío de object GastosModel por valor, que contine la información a actualizar y
     * los parámetros condicionales para realizar el update.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     * 
     */
    public function gastoUpdate(GastosModel $modelo, int $usuario_id_register)
    {


        $fecha_docto = formatDate_DB($modelo->getFecha_docto());
        // $fecha_pago = formatDate_DB($modelo->getFecha_pago());
        // $fecha_pago = date('Y-m-d');

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE gastos SET ";
        $sql .= "clasificacion_gasto_id = :clasificacion_gasto_id, ";
        $sql .= "descripcion = :descripcion, ";
        $sql .= "importe = :importe, ";
        $sql .= "proveedor = :proveedor, ";
        $sql .= "folio_nota = :folio_nota, ";
        $sql .= "fecha_docto = :fecha_docto, ";
        // $sql .= "fecha_pago = :fecha_pago, ";
        $sql .= "estatus = 0, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $modelo->getId(),
            'clasificacion_gasto_id' => $modelo->getClasificacion_gasto_id(),
            'descripcion' => $modelo->getDescripcion(),
            'importe' => $modelo->getImporte(),
            'proveedor' => $modelo->getProveedor(),
            'folio_nota' => $modelo->getFolio_nota(),
            'fecha_docto' => $fecha_docto,
            'usuario_id_register' => $usuario_id_register
        ];
        // 'fecha_pago' => $fecha_pago,

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);
    }

    /**
     * Eliminar Gasto.
     * 
     * @param int $gastos_id
     * Identificador de Gasto
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function deleteGasto(int $gastos_id, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();


            // /*-------------------------------------------
            // [ Guardar Datos de Clasificación ]*/
            $this->gastoDelete($gastos_id);

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
     * Subrutina dentro de deleteGasto para Eliminar Gasto
     * 
     * @param int $gasto_id
     * Identificador de Gasto
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     */
    public function gastoDelete(int $gasto_id)
    {


        //             DROP TABLE IF EXISTS `histocli_amores`.`gastos`;
        // CREATE TABLE  `histocli_amores`.`gastos` (
        //   `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        //   `clasificacion_gasto_id` int(10) unsigned NOT NULL,
        //   `descripcion` varchar(255) CHARACTER SET latin1 NOT NULL,
        //   `importe` double NOT NULL,
        //   `archivo` varchar(255) CHARACTER SET latin1 NOT NULL,
        //   `created_at` datetime NOT NULL,
        //   `usuario_id_created` int(10) unsigned NOT NULL,
        //   `updated_at` datetime DEFAULT NULL,
        //   `usuario_id_updated` int(10) unsigned DEFAULT NULL,
        //   `proveedor` varchar(255) CHARACTER SET latin1 NOT NULL,
        //   `fecha_docto` date NOT NULL,
        //   `fecha_pago` date NOT NULL,
        //   PRIMARY KEY (`Id`)
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE gastos SET ";
        $sql .= "estatus = 1 ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";

        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $gasto_id
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);
    }

    /**
     * Eliminar Archivo Adjunto.
     * 
     * @param int $gasto_id
     * Identificador de Gasto
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function deleteFile(int $gasto_id): bool
    {

        try {

            $response = true;

            $gasto = $this->selectGasto($gasto_id);
            $adjunto_name = $gasto['archivo'];
            if ($adjunto_name != '') {
                $path_to_file = "Assets/files/";
                $file_delete =  $path_to_file . $adjunto_name;
                $response =  unlink($file_delete);
                if ($response == true) {
                    $this->gastoUpdateNombreArchivo('', $gasto_id);
                }
            }
        } catch (\Throwable $th) {

            $_logger = getLoggerSystem()->error(getMensajeError($th));
            $response = false;
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }



    /**
     * Obtiene la lista de Gastos para un periodo determinado
     * 
     * @param object $model
     * Envío del modelo GastosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return array $arrResponse
     * 
     */
    public function selectGastosPeriodo(GastosModel $modelo): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "gas.*, clasif.clasificacion ";
            $sql .= "FROM gastos gas ";
            $sql .= "INNER JOIN clasificacion_gastos clasif on (clasif.id = gas.clasificacion_gasto_id) ";
            $sql .= "WHERE ";
            $sql .= "gas.fecha_pago BETWEEN :fecha_pago_ini and :fecha_pago_fin ";
            $sql .= "ORDER BY gas.Id desc ";


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


    //*==================================================================
    // [ GETTERS & SETTERS ]*/

    /**
     * Get the value of Id
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Set the value of Id
     *
     * @return  self
     */
    public function setId($Id)
    {
        $this->Id = $Id;

        return $this;
    }

    /**
     * Get the value of clasificacion_gasto_id
     */
    public function getClasificacion_gasto_id()
    {
        return $this->clasificacion_gasto_id;
    }

    /**
     * Set the value of clasificacion_gasto_id
     *
     * @return  self
     */
    public function setClasificacion_gasto_id($clasificacion_gasto_id)
    {
        $this->clasificacion_gasto_id = $clasificacion_gasto_id;

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

    /**
     * Get the value of proveedor
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set the value of proveedor
     *
     * @return  self
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;

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
     * Get the value of fecha_docto
     */
    public function getFecha_docto()
    {
        return $this->fecha_docto;
    }

    /**
     * Set the value of fecha_docto
     *
     * @return  self
     */
    public function setFecha_docto($fecha_docto)
    {
        $this->fecha_docto = $fecha_docto;

        return $this;
    }

    // /**
    //  * Get the value of fecha_pago
    //  */
    // public function getFecha_pago()
    // {
    //     return $this->fecha_pago;
    // }

    // /**
    //  * Set the value of fecha_pago
    //  *
    //  * @return  self
    //  */
    // public function setFecha_pago($fecha_pago)
    // {
    //     $this->fecha_pago = $fecha_pago;

    //     return $this;
    // }

    /**
     * Get the value of folio_nota
     */
    public function getFolio_nota()
    {
        return $this->folio_nota;
    }

    /**
     * Set the value of folio_nota
     *
     * @return  self
     */
    public function setFolio_nota($folio_nota)
    {
        $this->folio_nota = $folio_nota;

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
}
