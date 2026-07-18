<?php

/**
 * Controlador Home 
 */
class Home extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Home.
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
     * Carga la Vista Home. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Home()
    {

        try {
            $data['id'] = 3;
            $data['page_tag'] = "Histoclin Web";
            $data['page_breadcrumb'] = "Home Page";
            $data['page_name'] = "home";
            $data['page_title'] = "Home Page";
            $data['page_title_form'] = "Sistema de Administración y Control de Expedientes Clínicos";
            $data['page_content'] = "";
            $data['page_functions_js'] = "functions_home.js";
            $this->views->getView($this, "home", $data);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }
    }


    /**
     * Obtiene la lista de menus para llenar el autocomplete.
     * 
     * @param string $filtro
     * Texto recibido para filtrar la información en el query
     * 
     * @response $arrResponse, donde:
     * Array con la lista requerida para llenar el autocomplete.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getMenus(string $filtro)
    {

        try {


            if ($this->session->getStatus() === false || empty($this->session->get('email'))) {
                echo "Error: Acceso restringido";
                die();
            }

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $filtro = strClean($filtro);

            /*-------------------------------------------
            [ Obtiene array con los datos de Escuelas ]*/
            $menus_model = new MenusModel;
            $arrData = $menus_model->selectMenus($filtro);
            $arrResponse = array();

            for ($i = 0; $i < count($arrData); $i++) {
                $arrTemp = array();
                $arrTemp['value'] =  $arrData[$i]['menu'];

                $arrTemp['label'] = '<div class="w-100" style="padding: 5px;">
                                        <a href="' . base_url() . $arrData[$i]['url'] . '" style="padding: 7px;" class="list-group-item list-group-item-action flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-0 tx-semibold tx-13 tx-menu"><i class="' . $arrData[$i]['icon'] . '"></i> ' . $arrData[$i]['menu'] . '</h5>
                                            </div>
                                            <span><small class="tx-10">' . $arrData[$i]['descripcion'] . '</small><span>
                                        </a>
                                    </div>';
                $arrTemp['id'] =  $arrData[$i]['id'];
                $arrTemp['url'] =  base_url() . $arrData[$i]['url'];
                $arrResponse[] = $arrTemp;
            }
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }
}
