<?php

/**
 * Clase ConfiguracionModel
 */
class ConfiguracionModel extends Mysql
{

    // table configuracion

    private $id;
    private $email_test;
    private $email_remitente;
    private $email_destino_contabilidad;
    private $smtp_host;
    private $smtp_usuario;
    private $smtp_password;
    private $smtp_puerto;

    private $updated_at;
    private $usuario_id_updated;


    /**
     * Método Constructor de ConfiguracionModel.
     * Inicializa Mysql::__construct
     * 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista del catálogo de clientes.
     * 
     * @return array $arrResponse
     * 
     */
    public function selectConfiguracion(): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "SELECT ";
            $sql .= "cnf.* ";
            $sql .= "FROM configuracion cnf ";

            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

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
     * Actualiza datos da Configfuracion.
     * 
     * @param object $model
     * Envío del modelo por valor, que contine la información a actualizar y 
     * los parámetros condicionales para realizar el update.
     * 
     * @param int $usuario_id_register
     * Usuario que realiza el registro.
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function updateConfiguracion(ConfiguracionModel $modelo, int $usuario_id_register): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();


            /*-------------------------------------------
            [ Instruccion sql ]*/
            $sql = "UPDATE configuracion SET ";
            $sql .= "email_remitente = :email_remitente, ";
            $sql .= "email_destino_contabilidad = :email_destino_contabilidad, ";
            $sql .= "smtp_host = :smtp_host, ";
            $sql .= "smtp_usuario = :smtp_usuario, ";
            $sql .= "smtp_password = :smtp_password, ";
            $sql .= "smtp_puerto = :smtp_puerto, ";
            $sql .= "email_test = :email_test, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";

            /*-------------------------------------------
            [ Datos a actualizar y parámetros condicionales para realizar el update ]*/
            $arrData = [
                'email_remitente' => $modelo->getEmail_remitente(),
                'email_destino_contabilidad' => $modelo->getEmail_destino_contabilidad(),
                'smtp_host' => $modelo->getSmtp_host(),
                'smtp_usuario' => $modelo->getSmtp_usuario(),
                'smtp_password' => $modelo->getSmtp_password(),
                'smtp_puerto' => $modelo->getSmtp_puerto(),
                'email_test' => $modelo->getEmail_test(),
                'usuario_id_register' => $usuario_id_register
            ];

            /*-------------------------------------------
            [ Ejecuta el Metodo insert de MySQL ]*/
            $response = $this->update($sql, $arrData);


            /*-------------------------------------------
            [ Commit Transaction ]*/
            $this->getConexion()->commit();
        } catch (\Throwable $th) {
            /*-------------------------------------------
            [ Roll Back ]*/
            $this->getConexion()->rollBack();
            getLoggerSystem()->error(getMensajeError($th));
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
     * Get the value of email_test
     */
    public function getEmail_test()
    {
        return $this->email_test;
    }

    /**
     * Set the value of email_test
     *
     * @return  self
     */
    public function setEmail_test($email_test)
    {
        $this->email_test = $email_test;

        return $this;
    }

    /**
     * Get the value of email_remitente
     */
    public function getEmail_remitente()
    {
        return $this->email_remitente;
    }

    /**
     * Set the value of email_remitente
     *
     * @return  self
     */
    public function setEmail_remitente($email_remitente)
    {
        $this->email_remitente = $email_remitente;

        return $this;
    }

    /**
     * Get the value of email_destino_contabilidad
     */
    public function getEmail_destino_contabilidad()
    {
        return $this->email_destino_contabilidad;
    }

    /**
     * Set the value of email_destino_contabilidad
     *
     * @return  self
     */
    public function setEmail_destino_contabilidad($email_destino_contabilidad)
    {
        $this->email_destino_contabilidad = $email_destino_contabilidad;

        return $this;
    }

    /**
     * Get the value of smtp_host
     */
    public function getSmtp_host()
    {
        return $this->smtp_host;
    }

    /**
     * Set the value of smtp_host
     *
     * @return  self
     */
    public function setSmtp_host($smtp_host)
    {
        $this->smtp_host = $smtp_host;

        return $this;
    }

    /**
     * Get the value of smtp_usuario
     */
    public function getSmtp_usuario()
    {
        return $this->smtp_usuario;
    }

    /**
     * Set the value of smtp_usuario
     *
     * @return  self
     */
    public function setSmtp_usuario($smtp_usuario)
    {
        $this->smtp_usuario = $smtp_usuario;

        return $this;
    }

    /**
     * Get the value of smtp_password
     */
    public function getSmtp_password()
    {
        return $this->smtp_password;
    }

    /**
     * Set the value of smtp_password
     *
     * @return  self
     */
    public function setSmtp_password($smtp_password)
    {
        $this->smtp_password = $smtp_password;

        return $this;
    }

    /**
     * Get the value of smtp_puerto
     */
    public function getSmtp_puerto()
    {
        return $this->smtp_puerto;
    }

    /**
     * Set the value of smtp_puerto
     *
     * @return  self
     */
    public function setSmtp_puerto($smtp_puerto)
    {
        $this->smtp_puerto = $smtp_puerto;

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
}
