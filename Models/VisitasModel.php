<?php

/**
 * Clase VisitasModel
 */
class VisitasModel extends Mysql
{

    private $id;
    private $nombre;
    private $comentarios_adicionales;
    private $vigencia;
    private $estatus;
    private $residente_id;
    private $telefono;
    private $fecha_entrada;

    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;

    //Opciones de Filtro
    private $fecha_inicio;
    private $fecha_fin;

    /**
     * Método Constructor de VisitasModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Guardar datos del Nueva Visita.
     * 
     * @param object &$model VisitasModel
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
    public function insertVisita(VisitasModel &$modelo, &$valida_id, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Clasificación ]*/
            $resultado =  $this->visitaCreate($modelo, $valida_id, $usuario_id_register);

            if ($resultado == false) {
                /* [ RollBack ] */
                $this->getConexion()->rollBack();
            } else {

                $response = true;
                /* [ Commit Transaction ] */
                $this->getConexion()->commit();
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
     * Subrutina dentro de insertVisita para crear el Gasto
     * 
     * @param object VisitasModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function visitaCreate(VisitasModel &$model, &$valida_id, int $usuario_id_register): bool
    {

        $result = false;


        /*-------------------------------------------
        [ Instrucción SQL ]*/
        $sql = "INSERT INTO visitas SET ";
        $sql .= "nombre = :nombre, ";
        $sql .= "comentarios_adicionales = :comentarios_adicionales, ";
        $sql .= "vigencia = :vigencia, ";
        $sql .= "estatus = :estatus, ";
        $sql .= "residente_id = :residente_id, ";
        $sql .= "telefono = :telefono, ";
        $sql .= "created_at = current_timestamp, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_created = :usuario_id_register, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";

        /*-------------------------------------------
        [ Datos a Guardar ]*/
        $arrData = [
            'nombre' => $model->getNombre(),
            'comentarios_adicionales' => $model->getComentarios_adicionales(),
            'vigencia' => $model->getVigencia(),
            'estatus' => $model->getEstatus(),
            'residente_id' => $model->getResidente_id(),
            'telefono' => $model->getTelefono(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Evalúa respuesta ]*/
        $lastInsertId = $this->insert($sql, $arrData);
        $valida_id = $lastInsertId;

        /*-------------------------------------------
        [ Buzon Detalle ]*/
        if ($lastInsertId > 0) {
            $result = true;
        }

        /*-------------------------------------------
        [ Retorna Id de Recibo Insertado ]*/
        return $result;
    }

    /**
     * Obtiene la lista de Visitas.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectListVisitas($residente_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "v.* ";
            $sql .= "FROM visitas v ";
            $sql .= "WHERE ";
            $sql .= "residente_id = :residente_id ";
            $sql .= "ORDER BY v.created_at desc ";

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
     * Obtiene datos de un Visita determinado.
     * 
     * @param int $visita_id
     * Identificador de Visita
     * 
     * @return array $arrResponse
     * * array de tipo asociativo con los nombres 
     *   de las columnas indicadas en la instrucción sql.
     * 
     */
    public function selectVisitaPrincipal(int $visita_id): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "vis.*, r.calle, r.numero ";
            $sql .= "FROM visitas vis ";
            $sql .= "INNER JOIN residentes r on (r.id = vis.residente_id) ";
            $sql .= "WHERE ";
            $sql .= "vis.Id = :visita_id";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'visita_id' => $visita_id
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
     * Guardar estatus de registro de visita en vigilancia.
     * 
     * @param object &$model VisitasModel
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
    public function updateRegistroVisita(VisitasModel &$modelo, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Registrar Visita Update ]*/
            $resultado =  $this->visitaUpdate($modelo, $usuario_id_register);
            if ($resultado == false) {
                $this->getConexion()->rollBack();
            } else {
                $response = true;
                /* [ Commit Transaction ] */
                $this->getConexion()->commit();
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
     * Subrutina dentro de insertVisita para crear el Gasto
     * 
     * @param object VisitasModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro.
     *
     */
    public function visitaUpdate(VisitasModel &$model, int $usuario_id_register): bool
    {

        $result = false;

        /*-------------------------------------------
        [ Instrucción SQL ]*/
        $sql = "UPDATE visitas SET ";
        $sql .= "fecha_entrada = current_timestamp, ";
        $sql .= "estatus = :estatus, ";
        $sql .= "updated_at = current_timestamp, ";
        $sql .= "usuario_id_updated = :usuario_id_register ";
        $sql .= "WHERE ";
        $sql .= "Id = :visita_id ";

        /*-------------------------------------------
        [ Datos a Guardar ]*/
        $arrData = [
            'visita_id' => $model->getId(),
            'estatus' => $model->getEstatus(),
            'usuario_id_register' => $usuario_id_register
        ];

        /*-------------------------------------------
        [ Evalúa respuesta ]*/
        $result = $this->update($sql, $arrData);

        /*-------------------------------------------
        [ Retorna Id de Recibo Insertado ]*/
        return $result;
    }



    /**
     * Obtiene la lista de Visitas para un periodo determinado
     * 
     * @param object $model
     * Envío del modelo GastosModel por valor, que contine los datos de 
     * los parámetros condicionales para realizar el filtro y consulta.
     *  
     * @return array $arrResponse
     * 
     */
    public function selecVisitasPeriodo(VisitasModel $modelo): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "v.*, res.calle, res.numero ";
            $sql .= "FROM visitas v ";
            $sql .= "INNER JOIN residentes res ON (res.id = v.residente_id) ";
            $sql .= "WHERE ";
            $sql .= "(v.created_at BETWEEN :fecha_pago_ini and :fecha_pago_fin) OR ";
            $sql .= "(v.fecha_entrada BETWEEN :fecha_pago_ini and :fecha_pago_fin) ";
            $sql .= "ORDER BY v.Id desc ";

            // fecha_entrada
            // created_at

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [
                'fecha_pago_ini' => $modelo->getFechaInicio(),
                'fecha_pago_fin' => $modelo->getFechaFin()
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
     * Get the value of comentarios_adicionales
     */
    public function getComentarios_adicionales()
    {
        return $this->comentarios_adicionales;
    }

    /**
     * Set the value of comentarios_adicionales
     *
     * @return  self
     */
    public function setComentarios_adicionales($comentarios_adicionales)
    {
        $this->comentarios_adicionales = $comentarios_adicionales;

        return $this;
    }

    /**
     * Get the value of vigencia
     */
    public function getVigencia()
    {
        return $this->vigencia;
    }

    /**
     * Set the value of vigencia
     *
     * @return  self
     */
    public function setVigencia($vigencia)
    {
        $this->vigencia = $vigencia;

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
     * Get the value of fecha_entrada
     */
    public function getFecha_entrada()
    {
        return $this->fecha_entrada;
    }

    /**
     * Set the value of fecha_entrada
     *
     * @return  self
     */
    public function setFecha_entrada($fecha_entrada)
    {
        $this->fecha_entrada = $fecha_entrada;

        return $this;
    }

    /**
     * Get the value of fecha_inicio
     */
    public function getFechaInicio()
    {
        return $this->fecha_inicio;
    }

    /**
     * Set the value of fecha_inicio
     */
    public function setFechaInicio($fecha_inicio): self
    {
        $this->fecha_inicio = $fecha_inicio;

        return $this;
    }

    /**
     * Get the value of fecha_fin
     */
    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    /**
     * Set the value of fecha_fin
     */
    public function setFechaFin($fecha_fin): self
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }
}
