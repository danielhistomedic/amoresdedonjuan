<?php

/**
 * Controlador Legal 
 */
class Legal extends Controllers
{

    /**
     * Método Constructor de Controlador Legal.
     * Inicializa Controllers::__construct.
     * Inicializa y valida datos de session.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Carga la Vista Aviso de Privacidad. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Privacidad()
    {

        try {
            $data['id'] = 3;
            $data['page_tag'] = "Histoclin Web";
            $data['page_breadcrumb'] = "Aviso de Privacidad";
            $data['page_name'] = "privacidad";
            $data['page_title'] = "Aviso de Privacidad";
            $data['page_title_form'] = "Aviso de Privacidad";
            $data['page_content'] = "Contenido de Aviso de Privacidad";
            $data['page_functions_js'] = "";
            $this->views->getView($this, "privacidad", $data);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }
    }

    /**
     * Carga la Vista Terminos y Condiciones. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Terminos()
    {

        try {
            $data['id'] = 3;
            $data['page_tag'] = "Histoclin Web";
            $data['page_breadcrumb'] = "Terminos y Condiciones";
            $data['page_name'] = "privacidad";
            $data['page_title'] = "Terminos y Condiciones";
            $data['page_title_form'] = "Terminos y Condiciones";
            $data['page_content'] = "Contenido de Terminos y Condiciones";
            $data['page_functions_js'] = "";
            $this->views->getView($this, "terminos", $data);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }
    }
}
