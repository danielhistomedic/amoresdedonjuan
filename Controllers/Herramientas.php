<?php

/**
 * Controlador Herramientas 
 */
class Herramientas extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Herramientas.
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
     * Carga la Vista de Usuarios. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Usuarios(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_USUARIOS];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_USUARIOS;

            //Header
            $data['page_title'] = "Administración de Usuarios";
            $data['page_description'] = "Administración de Usuarios";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-users fa-fw text-secondary text-shadow-info'></i> Usuarios";

            //Breadcrump
            $data['page_breadcrumb'] = "Usuarios";

            //Card Principal
            $data['page_card_title'] = "Registro de Usuarios";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Administración de Usuarios, Altas, Bajas, Permisos";

            //JS Principal
            $data['page_functions_js'] = "herramientas/function_usuarios.js";

            //Call Vista
            $this->views->getView($this, "usuarios", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }


    /**
     * Carga la Vista de Roles. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Roles(): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_ROLES];

            // Valida si tiene acceso a la pagina.
            if (!$this->permisosMod['r']) {
                echo "<h4>Lo sentimos, Acceso restringido</h4>";
                die();
            }

            // Asigna los permisos de Módulo y SideBar
            $data['permisos'] = $arrPermisos;
            $data['permisosMod'] = $this->permisosMod;


            //Id de Menu para script de Pemisos de Boton exportar a Excel
            $data['menu'] = MOD_ROLES;

            //Header
            $data['page_title'] = "Administración de Roles de Usuarios";
            $data['page_description'] = "Administración de Roles de Usuarios";

            //Form Principal
            $data['page_form_title'] = "<i class='fa-regular fa-user-unlock fa-fw text-secondary text-shadow-info'></i> Roles de Usuarios";

            //Breadcrump
            $data['page_breadcrumb'] = "Roles de Usuarios";

            //Card Principal
            $data['page_card_title'] = "Registro de Roles de Usuarios";
            $data['page_card_description'] = "<i class='fa-regular fa-circle-info fs-14'></i> Módulo de Administración de Roles de Usuarios, Altas, Bajas, Permisos";

            //JS Principal
            $data['page_functions_js'] = "herramientas/functions_roles.js";

            //Call Vista
            $this->views->getView($this, "roles", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }
}
