<?php

/**
 * Controlador Theme 
 */
class Theme extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Theme.
     * Inicializa Controllers::__construct.
     * Inicializa y valida datos de session.
     */
    public function __construct()
    {
        parent::__construct();

        /*-------------------------------------------
        [ Validación de Sesion ]*/
        $this->session = new Session();

        if ($this->session->getStatus() === false || empty($this->session->get('email'))) {
            $this->session->redirect('inicio');
        }
    }




    //==================================================================
    // [ Tema del Sistema ]

    /**
     * Funcion para obtener el tema del sistema
     * 
     */
    function getTheme(): string
    {
        try {
            $response = $this->session->get('theme');
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
    [ Retorna respuesta ]*/
        return $response;
    }


    /**
     * Funcion para obtener el tema del sistema
     * 
     */
    function setTheme($theme): string
    {

        try {

            $response = $theme;
            $this->session->add('theme', $theme);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta ]*/
        return $response;
    }
}
