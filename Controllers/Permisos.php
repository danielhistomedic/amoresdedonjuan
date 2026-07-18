<?php

/**
 * Controlador Permisos 
 */
class Permisos extends Controllers
{

    private $session;
    private $permisosMod;

    /**
     * Método Constructor de Controlador Permisos.
     * Inicializa Controllers::__construct
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
     * Obtiene la lista de permisos asociados a un rol específico.
     * Ejecuta la funcion getModal(string $nameModal, $data) para mostrar el formualrio 
     * de registro de permisos, donde:
     * 1) $nameModal = Id del elemento modal que se va a ejecutar.
     * 2) $data = Array con los datos complementarios para llenar datos en la vista.
     * 
     * @param int $id Id de Rol
     * 
     */
    public function getPermisosRol(int $id): void
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisosGlobal = getPermisosGlobal();
            $this->permisosMod = $arrPermisosGlobal[MOD_PERMISOS];

            /*-------------------------------------------
            [ Asignar y Limpiar parametros recibidos ]*/
            $rol_id = intval($id);


            if ($rol_id > 0) {

                /*-------------------------------------------
                [ Se aignan las variables al Modelo ]*/
                $rol_model = new RolesModel;
                $rol = $rol_model->selectRol($rol_id);

                /*-------------------------------------------
                [ Obtiene array con los permisos del rol asociados a cada modulo ]*/
                $permisos_model = new PermisosModel;
                $arrModulos = $permisos_model->selectModulos();
                $arrPermisosRol = $permisos_model->selectPermisosRol($rol_id);

                /*-------------------------------------------
                [ Genera array de permisos default ]*/
                $arrPermisos = array(
                    'r' => 0,
                    'c' => 0,
                    'u' => 0,
                    'd' => 0,
                    'p_excel' => 0
                );

                /*-------------------------------------------
                [ Genera array de con valores del rol ]*/
                $arrPermisoRol = array(
                    'rol' => $rol['name'],
                    'rol_id' => $rol_id
                );

                if (empty($arrPermisosRol)) {

                    /*-------------------------------------------
                    [ Agregar al array de modulos, el array de permisos default ]*/
                    for ($i = 0; $i < count($arrModulos); $i++) {
                        $arrModulos[$i]['permisos'] = $arrPermisos;
                    }
                } else {

                    /*-------------------------------------------
                    [ Agregar al array de modulos, el array de permisos de la base de datos, correspondientes a cada modulo ]*/
                    for ($i = 0; $i < count($arrModulos); $i++) {

                        for ($pm = 0; $pm < count($arrPermisosRol); $pm++) {

                            if ($arrModulos[$i]['id'] == $arrPermisosRol[$pm]['modulo_id']) {
                                $arrPermisos = array(
                                    'r' => $arrPermisosRol[$pm]['r'],
                                    'c' => $arrPermisosRol[$pm]['c'],
                                    'u' => $arrPermisosRol[$pm]['u'],
                                    'd' => $arrPermisosRol[$pm]['d'],
                                    'p_excel' => $arrPermisosRol[$pm]['p_excel']
                                );
                                $arrModulos[$i]['permisos'] = $arrPermisos;
                                break;
                            }
                        }
                        if (!isset($arrModulos[$i]['permisos'])) {
                            $arrPermisos = array('r' => 0, 'c' => 0, 'u' => 0, 'd' => 0, 'p_excel' => 0);
                            $arrModulos[$i]['permisos'] = $arrPermisos;
                        }
                    }
                }

                /*-------------------------------------------
                [ Agregar al array de permisos base, el array con los modulos y permisos correspondiente a cada rol ]*/
                $arrPermisoRol['modulos'] = $arrModulos;
                $arrPermisoRol[] = $this->permisosMod;

                /*-------------------------------------------
                [ Obtiene html del modal de permisos enviando como parametro data, el array $arrPermisoRol ]*/
                $html = getModal("modalPermisos", $arrPermisoRol);
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
    }


    /**
     * Guardar permisos asociados a un rol específico.
     * 
     * @return string json_encode($arrData, JSON_UNESCAPED_UNICODE)
     * 
     */
    public function setPermisos()
    {

        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[MOD_PERMISOS];
            if (!$this->permisosMod['u']) {
                die(json_encode(getResponse('No cuenta con los suficientes privilegios para realizar esta acción.'), JSON_UNESCAPED_UNICODE));
            }

            /*-------------------------------------------
            [ Se reciben Datos del POST con FormData ]*/
            $rol_id_post = intval($_POST['rol_id']);
            $modulos_post = $_POST['modulos'];


            /*-------------------------------------------
            [ Elimina los permisos actuales ]*/
            $permisos_model = new PermisosModel;
            $permisos_model->deletePermisos($rol_id_post);


            /*-------------------------------------------
            [ Inserta los permisos seleccionados. ]*/
            foreach ($modulos_post as $modulo) {

                $modulo_id = $modulo['modulo_id'];
                $c = empty($modulo['c']) ? 0 : 1;
                $r = empty($modulo['r']) ? 0 : 1;
                $u = empty($modulo['u']) ? 0 : 1;
                $d = empty($modulo['d']) ? 0 : 1;
                $p_excel = empty($modulo['p_excel']) ? 0 : 1;

                $permisos_model = new PermisosModel;
                $permisos_model->setRol_id($rol_id_post);
                $permisos_model->setmodulo_id($modulo_id);
                $permisos_model->setC_create($c);
                $permisos_model->setR_read($r);
                $permisos_model->setU_update($u);
                $permisos_model->setD_delete($d);
                $permisos_model->setP_excel($p_excel);

                $response =  $permisos_model->insertPermisos($permisos_model, 1);
            }

            if ($response == true) {
                $arrRespuesta = array(
                    'respuesta' => 'ok', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Permisos asignados correctamente'
                );
            } else {
                $arrRespuesta = array(
                    'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                    'mensaje' => 'Error al realizar el registro, intente nuevamente'
                );
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
            $arrRespuesta = array(
                'respuesta' => 'error', 'mostrar_mensaje' => true, 'tiempo' => 3000,
                'mensaje' => 'Error al realizar el registro, intente nuevamente'
            );
        }

        /*-------------------------------------------
        [ Retorna respuesta json_encode ]*/
        die(json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Obtiene la lista de permisos especailes asociados a un modulo específico.
     *
     * @param int $id Id de Rol
     * 
     */
    public function getPermisosExcel(int $modulo_id): string
    {
        try {

            /*-------------------------------------------
            [ Validación de Permisos ]*/
            $arrPermisos = getPermisosGlobal();
            $this->permisosMod = $arrPermisos[$modulo_id];


            $response =  $this->permisosMod['p_excel'];
        } catch (\Throwable $th) {
            getLoggerSystem()->error(getMensajeError($th));
        }
        die($response);
    }
}
