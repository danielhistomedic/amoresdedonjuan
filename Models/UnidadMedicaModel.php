<?php

/**
 * Clase UnidadMedica
 */
class UnidadMedicaModel extends Mysql
{

    private $id;
    private $nombre_unidad;
    private $tipo_licencia_id;
    private $domicilio_id;
    private $telefono_unidadmedica;
    private $email_contacto_unidadmedica;
    private $logo;
    private $created_at;
    private $updated_at;
    private $usuario_id_created;
    private $usuario_id_updated;
    private $estatus_licencia_id;


    /**
     * Método Constructor de UnidadMedicaModel
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene datos de una Unidad Medica determinada.
     * 
     * @param int $unidad_medica_id
     * Identificador de unidad médica
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectUnidadMedica(int $unidad_medica_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "ums.*, usr.usuario as usuario_register, ";
            $sql .= "dom.tipo_vialidad_id, ";
            $sql .= "dom.calle, ";
            $sql .= "dom.num_ext, ";
            $sql .= "dom.num_int, ";
            $sql .= "dom.tipo_asentamiento_id, ";
            $sql .= "dom.colonia, ";
            $sql .= "dom.municipio_id, ";
            $sql .= "dom.localidad_id, ";
            $sql .= "dom.entidad_id, ";
            $sql .= "dom.cp_id, ";
            $sql .= "dom.pais_id, ";
            $sql .= "dom.referencia, ";
            $sql .= "CONCAT('[', paises.codigo, '] ', paises.pais) AS pais, ";
            $sql .= "entidades.entidad, ";
            $sql .= "municipios.municipio, ";
            $sql .= "codigos_postales.codigo_postal ";
            $sql .= "FROM unidades_medicas ums ";
            $sql .= "INNER JOIN usuarios usr ON (usr.id = ums.usuario_id_updated) ";
            $sql .= "LEFT JOIN domicilios dom ON (dom.id = ums.domicilio_id) ";
            $sql .= "LEFT JOIN paises ON (paises.id = dom.pais_id) ";
            $sql .= "LEFT JOIN entidades ON (entidades.id = dom.entidad_id) ";
            $sql .= "LEFT JOIN municipios ON (municipios.id = dom.municipio_id) ";
            $sql .= "LEFT JOIN codigos_postales ON (codigos_postales.id = dom.cp_id) ";
            $sql .= "WHERE ";
            $sql .= "ums.id = :unidad_medica_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'unidad_medica_id' => $unidad_medica_id
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
     * Obtiene datos de los servicios asociados a una Unidad Medica determinada.
     * 
     * @param int $unidad_medica_id
     * Identificador de unidad médica
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectUnidadMedicaServicios(int $unidad_medica_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "p.id, p.unidad_medica_id, p.descripcion, p.precio, p.activo ";
            $sql .= "FROM productos p ";
            $sql .= "WHERE ";
            $sql .= "p.activo = 1 and ";
            $sql .= "p.unidad_medica_id = :unidad_medica_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'unidad_medica_id' => $unidad_medica_id
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo selectModel de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array asociativo con los datos del registro o empty en caso de error ]*/
        return $arrResponse;
    }

    /**
     * Actualizar Datos de Unidad Mádica.
     * 
     * @param object &$model 
     * Envío de object UnidadMedicaModel por referencia, que contine la información
     * que contiene los parametros necesarios para realizar el registro.
     * 
     * @param object &$model_domicilio
     * Envío de object DomiciliosModel por referencia, que contine la información
     * que contiene los parametros necesarios para realizar el registro del domicilio asociado
     *
     * @param array $files
     * Array que contiene el archivo del logotipo de la unidad medica.
     * 
     * @param array $servicios
     * Array que contiene los productos y servicios asociados a la unidad medica.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function updateUnidadMedica(UnidadMedicaModel &$model, DomiciliosModel &$model_domicilio, $files, $servicios, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            $unidad_medica_id = $model->getId();

            // /*-------------------------------------------
            // [ Guardar Domicilio Unidad Medica ]*/
            if ($model->domicilio_id == 0) {
                $this->domicilioUnidadMedicaCreate($model_domicilio, $usuario_id_register);
                $model->setDomicilio_id($model_domicilio->getId());
            } else {
                $this->domicilioUnidadMedicaUpdate($model_domicilio, $usuario_id_register);
            }

            // /*-------------------------------------------
            // [ Guardar Logo Unidad Medica ]*/

            if ($files['name'] != '') {
                $logo_name = $model->getLogo();
                $logo =  $this->unidadmedicaUploadLogo($unidad_medica_id, $files, $logo_name);
                $model->setLogo($logo);
            }

            // /*-------------------------------------------
            // [ Guardar Datos Generales de Unidad Medica ]*/
            $this->unidadmedicaUpdate($model, $usuario_id_register);


            // /*-------------------------------------------
            // [ registro Express de Servicios de Unidad Medica ]*/
            $this->productosCreate($servicios, $unidad_medica_id, $usuario_id_register);


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
     * Subrutina dentro de updateUnidadMedica para actualizar datos de una Unidad Médica determinada
     * 
     * @param object $model 
     * Envío de object UnidadMedicaModel por valor, que contine la información a actualizar y
     * los parámetros condicionales para realizar el update.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     * 
     */
    public function unidadmedicaUpdate(UnidadMedicaModel $model, int $usuario_id_register)
    {


        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE unidades_medicas SET ";
        $sql .= "nombre_unidad = :nombre_unidad, ";
        $sql .= "domicilio_id = :domicilio_id, ";
        $sql .= "email_contacto_unidadmedica = :email_contacto_unidadmedica, ";
        $sql .= "telefono_unidadmedica = :telefono_unidadmedica, ";
        $sql .= "logo = :logo, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $model->getId(),
            'nombre_unidad' => $model->getNombre_unidad(),
            'domicilio_id' => $model->getDomicilio_id(),
            'email_contacto_unidadmedica' => $model->getEmail_contacto_unidadmedica(),
            'telefono_unidadmedica' => $model->getTelefono_unidadmedica(),
            'logo' => $model->getLogo(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);
    }

    /**
     * Subrutina dentro de updateUnidadMedica para crear el Domicilio asociado
     * 
     * @param object &$model_domicilio 
     * Envío de object DomiciliosModel por referencia, para asignar el valor lastInsertId al modelo.
     *
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     */
    public function domicilioUnidadMedicaCreate(DomiciliosModel &$model_domicilio,  int $usuario_id_register): void
    {


        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "INSERT INTO domicilios SET ";
        $sql .= "tipo_vialidad_id = :tipo_vialidad_id, ";
        $sql .= "calle = :calle, ";
        $sql .= "num_ext = :num_ext, ";
        $sql .= "num_int = :num_int, ";
        $sql .= "tipo_asentamiento_id = :tipo_asentamiento_id, ";
        $sql .= "colonia = :colonia, ";
        $sql .= "municipio_id = :municipio_id, ";
        $sql .= "entidad_id = :entidad_id, ";
        $sql .= "cp_id = :cp_id, ";
        $sql .= "pais_id = :pais_id, ";
        $sql .= "referencia = :referencia, ";
        $sql .= "activo = :activo, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";

        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'tipo_vialidad_id' => $model_domicilio->getTipo_vialidad_id(),
            'calle' => $model_domicilio->getCalle(),
            'num_ext' => $model_domicilio->getNum_ext(),
            'num_int' => $model_domicilio->getNum_int(),
            'tipo_asentamiento_id' => $model_domicilio->getTipo_asentamiento_id(),
            'colonia' => $model_domicilio->getColonia(),
            'municipio_id' => $model_domicilio->getMunicipio_id(),
            'entidad_id' => $model_domicilio->getEntidad_id(),
            'cp_id' => $model_domicilio->getCp_id(),
            'pais_id' => $model_domicilio->getPais_id(),
            'referencia' => $model_domicilio->getReferencia(),
            'activo' => $model_domicilio->getActivo(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo insert de MySQL ]*/
        $lastInsertId = $this->insert($sql, $arrData);

        /*-------------------------------------------
        [ Asigna por referencia el valor del id insertado ]*/
        $model_domicilio->setId($lastInsertId);
    }

    /**
     * Subrutina dentro de updateUnidadMedica para actualizar datos de Domicilio determinado
     * 
     * @param object $model_domicilio 
     * Envío de object DomiciliosModel por valor, que contine la información a actualizar y los parámetros condicionales para realizar el update.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     *
     */
    public function domicilioUnidadMedicaUpdate(DomiciliosModel $model_domicilio,  int $usuario_id_register): void
    {

        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE domicilios SET ";
        $sql .= "tipo_vialidad_id = :tipo_vialidad_id, ";
        $sql .= "calle = :calle, ";
        $sql .= "num_ext = :num_ext, ";
        $sql .= "num_int = :num_int, ";
        $sql .= "tipo_asentamiento_id = :tipo_asentamiento_id, ";
        $sql .= "colonia = :colonia, ";
        $sql .= "municipio_id = :municipio_id, ";
        $sql .= "entidad_id = :entidad_id, ";
        $sql .= "cp_id = :cp_id, ";
        $sql .= "pais_id = :pais_id, ";
        $sql .= "referencia = :referencia, ";
        $sql .= "activo = :activo, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "id = :id ";


        /*-------------------------------------------
        [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
        $arrData = [
            'id' => $model_domicilio->getId(),
            'tipo_vialidad_id' => $model_domicilio->getTipo_vialidad_id(),
            'calle' => $model_domicilio->getCalle(),
            'num_ext' => $model_domicilio->getNum_ext(),
            'num_int' => $model_domicilio->getNum_int(),
            'tipo_asentamiento_id' => $model_domicilio->getTipo_asentamiento_id(),
            'colonia' => $model_domicilio->getColonia(),
            'municipio_id' => $model_domicilio->getMunicipio_id(),
            'entidad_id' => $model_domicilio->getEntidad_id(),
            'cp_id' => $model_domicilio->getCp_id(),
            'pais_id' => $model_domicilio->getPais_id(),
            'referencia' => $model_domicilio->getReferencia(),
            'activo' => $model_domicilio->getActivo(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo update de MySQL ]*/
        $response = $this->update($sql, $arrData);
    }

    /**
     * Subrutina dentro de updateUnidadMedica para CARGAR el logotpo de la unidad medica correspondiente
     * 
     * @param int $unidad_medica_id 
     * Identificador de unidad medica 
     * @param array $files
     * array con el contenido del archivo a cargar
     * @param string $logo_name
     * Nombre del archivo a cargar 
     * 
     */
    public function unidadmedicaUploadLogo($unidad_medica_id, $files, $logo_name): string
    {

        // error: 4 Vacio
        // error: 0 Contiene Datos

        $logo = "";

        $hash_value = $unidad_medica_id;
        $fecha  = date('YmdHis');
        $hash_base = $hash_value . $fecha . $files['name'];
        $prefijo = encode($hash_base);

        $name = $files['name'];
        $tmp_name = $files['tmp_name'];
        $error = $files['error'];

        $max_size = 1024 * 1024 * 2;
        $size = $files['size'];
        $type = $files['type'];

        if ($name == '') {
        } else if ($error > 0) {
        } else if ($size > $max_size) {
        } else {


            //*==================================================================
            // [ Delete File Anterior ]*/
            if ($logo_name != "" && $logo_name != 'histoclin_logo_default.png') {
                $path_to_file = "Assets/images/logos/";
                $file_delete =  $path_to_file . $logo_name;
                unlink($file_delete);
            }

            //*==================================================================
            // [ Upload File ]*/
            $ruta_doctos = "Assets/images/logos/";
            $file_name_destination = $prefijo . '.png';
            // $file_name_destination = $prefijo . '_' . $name;
            $destination =  $ruta_doctos . $file_name_destination;
            $upload_response =  move_uploaded_file($tmp_name, $destination);


            /*-------------------------------------------
            [ Asigan el nombre del archivo guardado al modelo ]*/
            if ($upload_response == true) {
                $logo = $file_name_destination;
            } else {
                $logo = 'histoclin_logo_default.png';
            }
        }

        return $logo;
    }

    /**
     * Subrutina dentro de updateUnidadMedica para crear los servicios asociados
     * 
     * @param array $servicios
     * Array que contiene los servicios ascoiados a la unidad medica.
     *
     * @param int $unidad_medica_id
     * Identificador de unidad medica
     * 
     * @param int $usuario_id_register
     * Identificador de usuario que realiza el registro
     */
    public function productosCreate($servicios, int $unidad_medica_id,  int $usuario_id_register): void
    {


        //         DROP TABLE IF EXISTS `histocli_sys`.`productos`;
        // CREATE TABLE  `histocli_sys`.`productos` (
        //   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        //   `unidad_medica_id` int(10) unsigned NOT NULL COMMENT 'FK unidades_medicas',
        //   `descripcion` char(75) COLLATE utf8mb4_unicode_520_ci NOT NULL,
        //   `precio` double NOT NULL COMMENT 'precio de venta con iva incluido',
        //   `activo` int(10) unsigned NOT NULL,
        //   `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
        //   `usuario_id_created` int(10) unsigned NOT NULL COMMENT 'Usuario que creó el registro originalmente',
        //   `updated_at` datetime DEFAULT NULL COMMENT 'Fecha de actualización o modificación del registro',
        //   `usuario_id_updated` int(10) unsigned DEFAULT NULL COMMENT 'Usuario que actualizó o modificó el registro',
        //   PRIMARY KEY (`id`)
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='Catalogo de Productos y Servicios';


        for ($i = 0; $i < count($servicios); $i++) {

            // servicios: Array(5)
            //      0: {idServicio: '10', inputServicio: 'CONSULTA DE PRIMERA VEZ', inputPrecioServicio: '600'}
            //      1: {idServicio: '11', inputServicio: 'CONSULTA SUBSECUENTE', inputPrecioServicio: '400'}
            //      2: {idServicio: '12', inputServicio: 'CURACIONES ESPECIALES', inputPrecioServicio: '150'}
            //      3: {idServicio: '', inputServicio: 'CONSULTA A DOMICILIO', inputPrecioServicio: '900'}
            //      4: {idServicio: '', inputServicio: 'APLICACIÓN DE INYECCIÓN', inputPrecioServicio: '100'}

            $id = intval($servicios[$i]['idServicio']);

            if ($id === 0) {

                /*-------------------------------------------
                [ Instruccion sql ]*/
                $sql = "INSERT INTO productos SET ";
                $sql .= "unidad_medica_id = :unidad_medica_id, ";
                $sql .= "descripcion = :descripcion, ";
                $sql .= "precio = :precio, ";
                $sql .= "activo = 1, ";
                $sql .= "created_at = current_timestamp, ";
                $sql .= "updated_at = current_timestamp, ";
                $sql .= "usuario_id_created = :usuario_id_register, ";
                $sql .= "usuario_id_updated = :usuario_id_register ";

                /*-------------------------------------------
                [ Datos a insertar ]*/
                $arrData = [
                    'unidad_medica_id' => $unidad_medica_id,
                    'descripcion' => strClean($servicios[$i]['inputServicio']),
                    'precio' => $servicios[$i]['inputPrecioServicio'],
                    'usuario_id_register' => $usuario_id_register
                ];

                /*-------------------------------------------
                [ Ejecuta el Metodo insert de MySQL ]*/
                $this->insert($sql, $arrData);
            } else {

                /*-------------------------------------------
                [ Instruccion sql ]*/
                $sql = "UPDATE productos SET ";
                $sql .= "unidad_medica_id = :unidad_medica_id, ";
                $sql .= "descripcion = :descripcion, ";
                $sql .= "precio = :precio, ";
                $sql .= "activo = 1, ";
                $sql .= "updated_at = current_timestamp, ";
                $sql .= "usuario_id_updated = :usuario_id_register ";
                $sql .= "WHERE ";
                $sql .= "id = :id ";

                /*-------------------------------------------
                [ Datos a actualizar ]*/
                $arrData = [
                    'id' => $id,
                    'unidad_medica_id' => $unidad_medica_id,
                    'descripcion' => strClean($servicios[$i]['inputServicio']),
                    'precio' => $servicios[$i]['inputPrecioServicio'],
                    'usuario_id_register' => $usuario_id_register
                ];

                /*-------------------------------------------
                [ Ejecuta el Metodo insert de MySQL ]*/
                $this->update($sql, $arrData);
            }
        }
    }


    /**
     * Eliminar Servivio de Unidad Medica.
     * 
     * @param int $servicio_id
     * Id del serviico a eliminar
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function deleteServicioUnidadMedica(int $servicio_id,  int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
                [ Instruccion sql ]*/
            $sql = "UPDATE productos SET ";
            $sql .= "activo = 0,  ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE ";
            $sql .= "id = :id ";

            /*-------------------------------------------
                [ Datos a actualizar ]*/
            $arrData = [
                'id' => $servicio_id,
                'usuario_id_register' => $usuario_id_register
            ];

            /*-------------------------------------------
                [ Ejecuta el Metodo insert de MySQL ]*/
            $this->update($sql, $arrData);


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
     * Get the value of nombre_unidad
     */
    public function getNombre_unidad()
    {
        return $this->nombre_unidad;
    }

    /**
     * Set the value of nombre_unidad
     *
     * @return  self
     */
    public function setNombre_unidad($nombre_unidad)
    {
        $this->nombre_unidad = $nombre_unidad;

        return $this;
    }

    /**
     * Get the value of tipo_licencia_id
     */
    public function getTipo_tipo_licencia_id()
    {
        return $this->tipo_licencia_id;
    }

    /**
     * Set the value of tipo_licencia_id
     *
     * @return  self
     */
    public function setTipo_tipo_licencia_id($tipo_licencia_id)
    {
        $this->tipo_licencia_id = $tipo_licencia_id;

        return $this;
    }

    /**
     * Get the value of domicilio_id
     */
    public function getDomicilio_id()
    {
        return $this->domicilio_id;
    }

    /**
     * Set the value of domicilio_id
     *
     * @return  self
     */
    public function setDomicilio_id($domicilio_id)
    {
        $this->domicilio_id = $domicilio_id;

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
     * Get the value of telefono_unidadmedica
     */
    public function getTelefono_unidadmedica()
    {
        return $this->telefono_unidadmedica;
    }

    /**
     * Set the value of telefono_unidadmedica
     *
     * @return  self
     */
    public function setTelefono_unidadmedica($telefono_unidadmedica)
    {
        $this->telefono_unidadmedica = $telefono_unidadmedica;

        return $this;
    }

    /**
     * Get the value of email_contacto_unidadmedica
     */
    public function getEmail_contacto_unidadmedica()
    {
        return $this->email_contacto_unidadmedica;
    }

    /**
     * Set the value of email_contacto_unidadmedica
     *
     * @return  self
     */
    public function setEmail_contacto_unidadmedica($email_contacto_unidadmedica)
    {
        $this->email_contacto_unidadmedica = $email_contacto_unidadmedica;

        return $this;
    }

    /**
     * Get the value of logo
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set the value of logo
     *
     * @return  self
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }


    /**
     * Get the value of estatus_licencia_id
     */
    public function getEstatus_licencia_id()
    {
        return $this->estatus_licencia_id;
    }

    /**
     * Set the value of estatus_licencia_id
     *
     * @return  self
     */
    public function setEstatus_licencia_id($estatus_licencia_id)
    {
        $this->estatus_licencia_id = $estatus_licencia_id;

        return $this;
    }
}
