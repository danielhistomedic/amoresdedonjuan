<?php

/**
 * Clase Core Conexion (Singleton)
 * Garantiza una única conexión PDO por request.
 */
class Conexion
{

    private static $instance = null;
    private $conn;

    /**
     * Método Constructor de Core Conexion (privado para Singleton)
     */
    private function __construct()
    {
        $connectionString = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";" . DB_CHARSET . ";";
        try {
            $this->conn = new PDO($connectionString, DB_USER, DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->conn->exec("SET SQL_BIG_SELECTS=1");
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Obtiene la instancia única de Conexion (Singleton).
     * 
     * @return Conexion
     */
    public static function getInstance(): Conexion
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Método para retornar la Conexión PDO
     * 
     */
    public function connect()
    {
        return $this->conn;
    }
}
