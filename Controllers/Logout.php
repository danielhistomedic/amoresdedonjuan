<?php

/**
 * Controlador Logout 
 */
class Logout
{

    /**
     * Método Constructor de Controlador Roles.
     * Inicializa Controllers::__construct
     */
    public function __construct()
    {

        /*-------------------------------------------
        [ Validación de Sesion ]*/
        $this->session = new Session();
        $this->session->close();
    }
}
