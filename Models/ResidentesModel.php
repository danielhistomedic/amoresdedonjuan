<?php

/**
 * Clase ResidentesModel
 */
class ResidentesModel extends Mysql
{


    private $id;
    private $nombre;
    private $calle;
    private $mza;
    private $lote;
    private $numero;

    private $email;
    private $telefono;
    private $indicaciones_generales_visitas;

    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;


    /**
     * Método Constructor de ResidentesModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista de menus para el search en vistas.
     * 
     * @return array $request
     * 
     */
    public function selectResidentesSearch($arrFilter): array
    {

        try {

            /*-------------------------------------------
            [ Instruccion sql ]*/
            if (count($arrFilter) == 1) {
                $calle = $arrFilter[0];
                $numero = "";
                $sql_num = "";
            } elseif (count($arrFilter) == 2) {
                $calle = $arrFilter[0];
                $numero = $arrFilter[1];
                $sql_num = "AND r.numero LIKE :numero ";
            } elseif (count($arrFilter) > 2) {
                $calle = $arrFilter[1];
                $numero = $arrFilter[2];
                $sql_num = "AND r.numero LIKE :numero ";
            }


            $calle_filter = "%" . $calle . "%";
            $numero_filter = "%" . $numero . "%";

            $sql = "SELECT ";
            $sql .= "r.id, r.nombre, r.calle, r.numero, r.mza, r.lote ";
            $sql .= "FROM residentes r ";
            $sql .= "WHERE ";
            $sql .= "r.calle LIKE :calle ";
            $sql .= $sql_num;
            $sql .= "order by r.calle, r.numero LIMIT 100";

            /*-------------------------------------------
            [ Paramteros condicionales, se envía vacío en caso de no aplicar ]*/
            if ($sql_num == "") {
                $arr_values = [
                    'calle' => $calle_filter
                ];
            } else {
                $arr_values = [
                    'calle' => $calle_filter,
                    'numero' => $numero_filter
                ];
            }

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $request = $this->selectLikeResidentes($sql, $arr_values);

            /*-------------------------------------------
            [ Retorna array con la lista de registros ]*/
            return $request;
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene datos de un Residente determinado.
     * 
     * @param int $residente_id
     * Identificador de residente
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectResidente(int $residente_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "r.* ";
            $sql .= "FROM residentes r ";
            $sql .= "WHERE ";
            $sql .= "r.id = :residente_id ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'residente_id' => $residente_id
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
     * Guardar datos del Residente.
     * 
     * @param object &$model ResidentesModel
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
    public function updateResidente(ResidentesModel &$model, int $usuario_id_register): bool
    {

        try {

            $response = true;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Usuario ]*/
            $this->residenteUpdate($model, $usuario_id_register);

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
     * Subrutina dentro de updateResidente para actualizar el Residente
     * 
     * @param object RecibosModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function residenteUpdate(ResidentesModel &$model,  int $usuario_id_register): void
    {



        /*-------------------------------------------
        [ Instruccion sql ]*/
        $sql = "UPDATE residentes SET ";
        $sql .= "nombre = :nombre, ";
        $sql .= "calle = :calle, ";
        $sql .= "numero = :numero, ";
        $sql .= "telefono = :telefono, ";
        $sql .= "indicaciones_generales_visitas = :indicaciones_generales_visitas, ";
        $sql .= "email = :email, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "residentes.id = :id ";


        /*-------------------------------------------
        [ Datos a insertar ]*/
        $arrData = [
            'id' => $model->getId(),
            'nombre' => $model->getNombre(),
            'calle' => $model->getCalle(),
            'numero' => $model->getNumero(),
            'telefono' => $model->getTelefono(),
            'indicaciones_generales_visitas' => $model->getIndicaciones_generales_visitas(),
            'email' => $model->getEmail(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Ejecuta el Metodo udpate de MySQL ]*/
        $this->update($sql, $arrData);
    }


    /*==============================================
    [ Directorio ]*/

    /**
     * Obtiene la lista de residentes para directorio.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectResidentes(): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "res.* ";
            $sql .= "FROM residentes res ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

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


    /*==============================================
    [ Getters & Setteres ]*/

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
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

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
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of telefono
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of indicaciones_generales_visitas
     */
    public function getIndicaciones_generales_visitas()
    {
        return $this->indicaciones_generales_visitas;
    }

    /**
     * Set the value of indicaciones_generales_visitas
     *
     * @return  self
     */
    public function setIndicaciones_generales_visitas($indicaciones_generales_visitas)
    {
        $this->indicaciones_generales_visitas = $indicaciones_generales_visitas;

        return $this;
    }
}
