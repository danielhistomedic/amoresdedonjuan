<?php

use Spipu\Html2Pdf\Tag\Html\I;

/**
 * Clase BuzonModel
 */
class BuzonModel extends Mysql
{


    private $id;
    private $tipo_id;
    private $asunto;
    private $estatus;
    private $mensaje;
    private $adjunto;
    private $residente_id;

    private $buzon_detalle;


    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;

    /**
     * Método Constructor de BuzonModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Guardar datos del Nuevo Buzon.
     * 
     * @param object &$model BuzonModel
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
    public function insertBuzon(BuzonModel &$modelo, $files, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Clasificación ]*/
            $resultado =  $this->buzonCreate($modelo, $usuario_id_register);
            if ($resultado == false) {
                /* [ RollBack ] */
                $this->getConexion()->rollBack();
            } else {

                /* [ Guardar Archivo Adjunto ] */
                if ($files['name'] != '') {
                    $adjunto_name = $modelo->getAdjunto();
                    $res_file =  $this->buzonUpload($files, $adjunto_name);
                    if ($res_file == false) {
                        /* [ RollBack ] */
                        $this->getConexion()->rollBack();
                    } else {

                        $response = true;
                        /* [ Commit Transaction ] */
                        $this->getConexion()->commit();
                    }
                } else {

                    $response = true;
                    /* [ Commit Transaction ] */
                    $this->getConexion()->commit();
                }
            }
        } catch (\Throwable $th) {
            /* [ RollBack ] */
            $this->getConexion()->rollBack();
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Subrutina dentro de insertBuzon para crear el Gasto
     * 
     * @param object BuzonModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function buzonCreate(BuzonModel &$model,  int $usuario_id_register): bool
    {

        $result = false;

        /*-------------------------------------------
        [ Buzon ]*/
        $sql = "INSERT INTO buzon SET ";
        $sql .= "tipo_id = :tipo_id, ";
        $sql .= "asunto = :asunto, ";
        $sql .= "estatus = 0, ";
        $sql .= "residente_id = :residente_id, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $arrData = [
            'tipo_id' => $model->getTipo_id(),
            'residente_id' => $model->getResidente_id(),
            'asunto' => $model->getAsunto(),
            'usuario_id_register' => $usuario_id_register
        ];
        $lastInsertId = $this->insert($sql, $arrData);

        /*-------------------------------------------
        [ Buzon Detalle ]*/
        if ($lastInsertId > 0) {

            /*- [ Asigna por referencia el valor del id insertado ] */
            $model->setId($lastInsertId);

            $sql = "INSERT INTO buzon_detalle SET ";
            $sql .= "buzon_id = :buzon_id, ";
            $sql .= "mensaje = :mensaje, ";
            $sql .= "adjunto = :adjunto, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $arrData = [
                'buzon_id' => $lastInsertId,
                'mensaje' => $model->getMensaje(),
                'adjunto' => $model->getAdjunto(),
                'usuario_id_register' => $usuario_id_register
            ];
            $lastInsertIdDetalle = $this->insert($sql, $arrData);
            if ($lastInsertIdDetalle > 0) {
                $result = true;
            }
        }

        /*-------------------------------------------
        [ Retorna Id de Recibo Insertado ]*/
        return $result;
    }

    /**
     * Subrutina dentro de inserBuzon cargar el archivo adjunto en caso de enviarlo
     * 
     * @param int $unidad_medica_id 
     * Identificador de unidad medica 
     * @param array $files
     * array con el contenido del archivo a cargar
     * @param string $logo_name
     * Nombre del archivo a cargar 
     * 
     */
    public function buzonUpload($files, $adjunto_name): bool
    {

        $response = false;
        $tmp_name = $files['tmp_name'];

        //*==================================================================
        // [ FTP Upload File Portal Residentes ]*/
        // $conn_id = ftp_connect(FTP_SERVER);
        // $lr = ftp_login($conn_id, FTP_USUARIO, FTP_PASSWORD);
        // if ((!$conn_id) || (!$lr)) {
        //     $response = false;
        // } else {
        //     $destination_ftp = "Assets/files/buzon/" . $adjunto_name;
        //     ftp_pasv($conn_id, true);
        //     $response = ftp_put($conn_id, $destination_ftp, $tmp_name, FTP_BINARY);
        // }
        // ftp_close($conn_id);


        //*==================================================================
        // [ Upload File ]*/
        $ruta_doctos = "Assets/files/buzon/";
        $destination =  $ruta_doctos . $adjunto_name;
        $response =  move_uploaded_file($tmp_name, $destination);


        //*==================================================================
        // [ Upload File - Copia arvhivo guardado para replicarlo en residentes ]*/
        $dstfile = '/home/histocli/residentes.amoresdedonjuan.org' . '/' . $destination;
        if (is_dir(dirname($dstfile))) {
            @copy($destination, $dstfile);
        }

        return $response;
    }

    /**
     * Obtiene la lista de Buzon.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectListBuzon($residente_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "b.*, t.tipo, r.nombre, r.numero, r.calle, CONCAT_WS(' ', r.calle, r.numero) as domicilio ";
            $sql .= "FROM buzon b ";
            $sql .= "INNER JOIN residentes r on (r.id = b.residente_id) ";
            $sql .= "INNER JOIN tipo t on (t.id = b.tipo_id) ";
            $sql .= "WHERE ";
            $sql .= "residente_id = :residente_id ";
            $sql .= "ORDER BY b.created_at desc ";

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
     * Obtiene la lista de Buzon de la cuenta de Administración
     * 
     * @return array $arrResponse
     * 
     */
    public function selectListBuzonAdmin(): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "b.*, t.tipo, r.nombre, r.numero, r.calle, CONCAT_WS(' ', r.calle, r.numero) as domicilio ";
            $sql .= "FROM buzon b ";
            $sql .= "INNER JOIN residentes r on (r.id = b.residente_id) ";
            $sql .= "INNER JOIN tipo t on (t.id = b.tipo_id) ";
            $sql .= "ORDER BY b.created_at desc ";


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
     * Obtiene datos de un Buzon determinado.
     * 
     * @param int $buzon_id
     * Identificador de Buzon
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectSeguimientoBuzon(int $buzon_id, int $residente_id): array
    {

        try {

            $arrResponse = array();


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "detalle.*, r.nombre, r.calle, r.numero, b.asunto, tipo.tipo ";
            $sql .= "FROM buzon_detalle detalle ";
            $sql .= "INNER JOIN buzon b ON (b.Id = detalle.buzon_id) ";
            $sql .= "INNER JOIN residentes r on (r.id = b.residente_id) ";
            $sql .= "INNER JOIN tipo on (tipo.id = b.tipo_id) ";
            $sql .= "WHERE ";
            $sql .= "detalle.buzon_id = :buzon_id and ";
            $sql .= "b.residente_id = :residente_id ";
            $sql .= "ORDER BY detalle.created_at DESC";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'buzon_id' => $buzon_id,
                'residente_id' => $residente_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Obtiene datos de un Buzon determinado.
     * 
     * @param int $buzon_id
     * Identificador de Buzon
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectBuzon(int $buzon_id, int $residente_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "b.*, r.nombre, r.calle, r.numero, b.asunto, tipo.tipo ";
            $sql .= "FROM buzon b ";
            $sql .= "INNER JOIN residentes r on (r.id = b.residente_id) ";
            $sql .= "INNER JOIN tipo on (tipo.id = b.tipo_id) ";
            $sql .= "WHERE ";
            $sql .= "b.Id = :buzon_id and ";
            $sql .= "b.residente_id = :residente_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'buzon_id' => $buzon_id,
                'residente_id' => $residente_id
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
     * Actualizar datos del Buzon.
     * 
     * @param object &$model BuzonModel
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
    public function updateBuzon(BuzonModel &$modelo, $files, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Clasificación ]*/
            $resultado =  $this->buzonUpdate($modelo, $usuario_id_register);

            if ($resultado == false) {
                /* [ RollBack ] */
                $this->getConexion()->rollBack();
            } else {

                /* [ Guardar Archivo Adjunto ] */
                if ($files['name'] != '') {
                    $adjunto_name = $modelo->getAdjunto();
                    $res_file =  $this->buzonUpload($files, $adjunto_name);
                    if ($res_file == false) {
                        /* [ RollBack ] */
                        $this->getConexion()->rollBack();
                    } else {

                        $response = true;
                        /* [ Commit Transaction ] */
                        $this->getConexion()->commit();
                    }
                } else {

                    $response = true;
                    /* [ Commit Transaction ] */
                    $this->getConexion()->commit();
                }
            }
        } catch (\Throwable $th) {
            /* [ RollBack ] */
            $this->getConexion()->rollBack();
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }

    /**
     * Subrutina dentro de updateBuzon para actualizar el Buzón
     * 
     * @param object BuzonModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function buzonUpdate(BuzonModel &$model,  int $usuario_id_register): bool
    {

        $result = false;


        /*-------------------------------------------
        [ Buzon ]*/
        $sql = "UPDATE buzon SET ";
        $sql .= "estatus = :estatus, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "Id = :Id ";
        $arrData = [
            'Id' => $model->getId(),
            'estatus' => $model->getEstatus(),
            'usuario_id_register' => $usuario_id_register
        ];
        $response = $this->update($sql, $arrData);


        /*-------------------------------------------
        [ Buzon Detalle ]*/
        if ($response == true) {

            $sql = "INSERT INTO buzon_detalle SET ";
            $sql .= "buzon_id = :buzon_id, ";
            $sql .= "mensaje = :mensaje, ";
            $sql .= "administracion = 1, ";
            $sql .= "adjunto = :adjunto, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $arrData = [
                'buzon_id' => $model->getId(),
                'mensaje' => $model->getMensaje(),
                'adjunto' => $model->getAdjunto(),
                'usuario_id_register' => $usuario_id_register
            ];

            $lastInsertIdDetalle = $this->insert($sql, $arrData);
            if ($lastInsertIdDetalle > 0) {
                $result = true;
            }
        }

        /*-------------------------------------------
        [ Retorna Id de Recibo Insertado ]*/
        return $result;
    }

    //*==================================================================
    // [ GETTERS & SETTERS ]*/


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
     * Get the value of tipo_id
     */
    public function getTipo_id()
    {
        return $this->tipo_id;
    }

    /**
     * Set the value of tipo_id
     *
     * @return  self
     */
    public function setTipo_id($tipo_id)
    {
        $this->tipo_id = $tipo_id;

        return $this;
    }

    /**
     * Get the value of asunto
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set the value of asunto
     *
     * @return  self
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

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
     * Get the value of buzon_detalle
     */
    public function getBuzon_detalle()
    {
        return $this->buzon_detalle;
    }

    /**
     * Set the value of buzon_detalle
     *
     * @return  self
     */
    public function setBuzon_detalle($buzon_detalle)
    {
        $this->buzon_detalle = $buzon_detalle;

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
     * Get the value of mensaje
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set the value of mensaje
     *
     * @return  self
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get the value of adjunto
     */
    public function getAdjunto()
    {
        return $this->adjunto;
    }

    /**
     * Set the value of adjunto
     *
     * @return  self
     */
    public function setAdjunto($adjunto)
    {
        $this->adjunto = $adjunto;

        return $this;
    }
}
