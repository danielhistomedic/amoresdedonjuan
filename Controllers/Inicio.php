<?php

/**
 * Controlador Inicio 
 */
class Inicio extends Controllers
{

    private $session;

    /**
     * Método Constructor de Controlador Inicio.
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
            $this->session->redirect('login');
        }
    }


    /**
     * Carga la Vista Inicio. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Inicio(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();

            // Permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;


            //Header
            $data['page_title'] = "Bienvenido";
            $data['page_description'] = "Página de Inicio de Sistema";

            //Form Principal <i class="fa-regular fa-chart-user"></i>
            $data['page_form_title'] = "<i class='fa-regular fa-grid fa-fw text-yellow text-shadow-warning'></i> Menú de Inicio";

            //Breadcrump
            $data['page_breadcrumb'] = "Accesos Directos";

            //Card Principal
            $data['page_card_title'] = "Menu Principal";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Panel de Accesos Directos.";

            //JS Principal
            $data['page_functions_js'] = "functions_inicio.js";

            //Menu
            $data['menu'] = "";

            //Call Vista
            $this->views->getView($this, "inicio", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }
}
