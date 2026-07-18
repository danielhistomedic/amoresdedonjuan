<?php

/**
 * Controlador Checkout 
 */
class Checkout extends Controllers
{

    private $session;

    /**
     * Método Constructor de Controlador Checkout.
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
     * Carga la Vista Checkout. 
     * Este método llama el metodo getview($controller, $view, $data=""), donde:
     * * $controller = $this, 
     * * $view = Nombre del archivo de la vista, 
     * * $data = Array con los siguentes datos:
     * 1) Datos de encabezado de la pagina html, 
     * 2) Archivo *.js correspondiente a la vista.
     * ** NOTA. El array $data puede ampliarse segun la necesidad de la vista.
     */
    public function Checkout(): void
    {

        try {

            // getPermisos(MOD_INICIO);
            $arrPermisos = getPermisosGlobal();
            $data['permisos'] = $arrPermisos;
            $data['menu'] = "";

            $data['page_id'] = 1;
            $data['page_tag'] = "Histoclin Web";
            $data['page_breadcrumb'] = "Suscripción";
            $data['page_name'] = "suscripcion";
            $data['page_title'] = "Histoclin Web Suscripción";
            $data['page_title_form'] = "Suscripción";
            $data['page_content'] = "Suscripción";
            $data['page_functions_js'] = "";

            $this->views->getView($this, "checkout", $data);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }
    }



    /**
     * Obtiene el formulario de Checkout de Stripe.
     * 
     * @return json json_encode($arrResponse, JSON_UNESCAPED_UNICODE).
     */
    public function getCheckout()
    {

        // Use Stripe secret key from environment variables.
        \Stripe\Stripe::setApiKey(getenv('STRIPE_API_KEY'));

        function calculateOrderAmount(array $items): int
        {
            // Replace this constant with a calculation of the order's amount
            // Calculate the order total on the server to prevent
            // people from directly manipulating the amount on the client
            return 1400;
        }

        header('Content-Type: application/json');

        try {

            // retrieve JSON from POST body
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            // Create a PaymentIntent with amount and currency
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => calculateOrderAmount($jsonObj->items),
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            echo json_encode($output);
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
