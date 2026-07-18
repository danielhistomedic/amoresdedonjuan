<?php

/**
 * Controlador de Errores
 */
class Errors extends Controllers
{

    private $session;

    /**
     * Método Constructor de Controlador Errors.
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

    /**
     * Carga la Vista Pagina No encontrada Error 404 Page. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function notFound()
    {

        try {

            //Header
            $data['page_title'] = "Error 404";
            $data['page_description'] = "Error 404";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-cancel fa-fw text-primary text-shadow-info'></i> Error 404";

            //Breadcrump
            $data['page_breadcrumb'] = "Error 404";

            //Card Principal
            $data['page_card_title'] = "Error 404";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Error 404.";

            //JS Principal
            $data['page_functions_js'] = "functions_errors.js";

            //Call Vista
            $this->views->getView($this, "error", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }
}

try {
    $error_page = new Errors();
    $error_page->notFound();
} catch (\Throwable $th) {
    getLoggerSystem()->error(getMensajeError($th));
}
