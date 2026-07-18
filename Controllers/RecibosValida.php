<?php


require 'Libraries/phpqrcode/qrlib.php';


/**
 * Controlador RecibosValida 
 */
class RecibosValida extends Controllers
{


    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Recibos.
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

        /*-------------------------------------------
        [ Validación de Permisos ]*/
        $arrPermisos = getPermisos(MOD_VALLIDACION_RECIBO);
        $this->permisosMod = $arrPermisos['permisosMod'];
    }

    /**
     * Carga la Vista Recibos. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function valida($recibo_id): void
    {

        try {

            /*-------------------------------------------
            [ Valida Permisos. ]*/
            if (!$this->permisosMod['r']) {
                /*-------------------------------------------
                [ Redireccionar a Login  ]*/
                header('Location: ' . base_url() . '/login');
                die();
            }
            $data['permisos'] = $this->session->get('permisos');
            $data['permisosMod'] = $this->permisosMod;
            $data['menu'] = MOD_VALLIDACION_RECIBO;

            $data['page_id'] = MOD_VALLIDACION_RECIBO;
            $data['page_tag'] = "Validación de Recibo de Cobro";
            $data['page_breadcrumb'] = "Validación de Recibo de Cobro";
            $data['page_name'] = "recibos";
            $data['page_title'] = "Validación de Recibo de Cobro";
            $data['page_title_form'] = "Validación de Recibo de Cobro";
            $data['page_content'] = "Validación de Recibo de Cobro";
            $data['page_functions_js'] = "functions_recibos_valida.js";


            $recibo_id = intval(strClean($recibo_id));
            $recibos_model = new RecibosModel;
            $arrData = $recibos_model->selectRecibo($recibo_id);
            $data['recibo'] = $arrData;

            $this->views->getView($this, "recibos_valida", $data);
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }
}
