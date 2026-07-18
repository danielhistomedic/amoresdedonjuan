<?php

/**
 * Controlador CatalogosSat 
 */
class CatalogosSat extends Controllers
{

    private $session;

    /**
     * Método Constructor de Controlador CatalogosSat.
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
     * Obtiene la lista de catálogo de Regimen Fiscales para llenar un Select
     * @return string $htmlOptions
     */
    public function getAllSelectRegimenFiscal(): string
    {

        try {
            $htmlOptions = '';
            $regimen_fiscal_model = new RegimenFiscalModel;

            $arrData = $regimen_fiscal_model->selectAll();

            if (count($arrData) > 0) {
                for ($i = 0; $i < count($arrData); $i++) {
                    if ($arrData[$i]['activo'] == 1) {
                        if ($arrData[$i]['id'] != 1) {
                            $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['regimen_fiscal'] . '</option>';
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        die($htmlOptions);
    }




    //
}
