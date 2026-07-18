<?php

require 'Libraries/html2pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;


/**
 * Clase IngresosEgresosCierreModel
 */
class IngresosEgresosCierreModel extends Mysql
{

    // tabla informe_ingresos_egreso_cierre

    private $Id;
    private $mes;
    private $anio;
    private $descripcion;
    private $archivo;

    private $created_at;
    private $usuario_id_created;
    private $updated_at;
    private $usuario_id_updated;

    private $filtro_fecha_inicio;
    private $filtro_fecha_fin;


    /**
     * Método Constructor de IngresosEgresosCierreModel.
     * Inicializa Mysql::__construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene la lista de Ingresos y Egresos 
     * agrupado por calle
     *  
     * @return array $arrResponse
     * 
     */
    public function selectListIngresosEgresosPortal(): array
    {

        try {

            $arrResponse = array();

            /*-------------------------------------------
            [ Instrucción sql ]*/
            $sql = "SELECT inf.* ";
            $sql .= "FROM informe_ingresos_egreso_cierre inf ";


            /*-------------------------------------------
            [ Parametros condicionales, se envía vacío en caso de no aplicar ]*/
            $arr_values = [];

            /*-------------------------------------------
            [ Ejecuta el Metodo select de MySQL ]*/
            $arrResponse = $this->select($sql, $arr_values);
        } catch (\Throwable $th) {
            $_logger = getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna array con la lista de registros o empty en caso de error ]*/
        return $arrResponse;
    }


    /**
     * Guardar datos del Nuevo Informa de Cierre de Ingresos y Egresos.
     * 
     * @param object &$model IngresosEgresosCierreModel
     * Envío del modelo por referencia, para asiganr el valor lastInsertId al modelo.
     * 
     * 
     * @param int $usuario_id_register
     * Identificador de Usuario que realiza el registro
     * 
     * @return bool $response
     * * true - indica que fue exitoso.
     * * false - en caso de falla.
     * 
     */
    public function insertInformeIngresosEgresosCierre(IngresosEgresosCierreModel &$modelo): bool
    {

        try {

            $response = false;

            /*-------------------------------------------
            [ Begin Transaction ]*/
            $this->getConexion()->beginTransaction();

            /*-------------------------------------------
            [ Genera PDF de Cierre  ]*/
            $result = $this->generarPdf($modelo);
            if ($result == false) {
                $this->getConexion()->rollBack();
                return false;
            }

            /*-------------------------------------------
            [ Insertar Datos ]*/
            $resultado =  $this->informeIngresosEgresosCreate($modelo);
            if ($resultado == false) {
                $this->getConexion()->rollBack();
                return false;
            }

            /*-------------------------------------------
            [ Respuesta Exitosa ]*/
            $response = true;
            $this->getConexion()->commit();
        } catch (\Throwable $th) {
            $this->getConexion()->rollBack();
            getLoggerSystem()->error(getMensajeError($th));
        }

        /*-------------------------------------------
        [ Retorna bool ]*/
        return $response;
    }



    /**
     * Subrutina dentro de insertInformeIngresosEgresosCierre para crear el registro
     * 
     * @param object IngresosEgresosCierreModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     */
    public function informeIngresosEgresosCreate(IngresosEgresosCierreModel &$model): bool
    {

        $result = false;

        /*-------------------------------------------
        [ Valida si el registro de ese mes existe ]*/
        $sql = "SELECT Id FROM informe_ingresos_egreso_cierre ";
        $sql .= "WHERE ";
        $sql .= "mes = :mes AND ";
        $sql .= "anio = :anio ";
        $arrData = [
            'mes' => $model->getMes(),
            'anio' => $model->getAnio()
        ];
        $arrResponseValid = $this->selectModel($sql, $arrData);
        if (count($arrResponseValid) == 0) {

            /*-------------------------------------------
            [ Insertar Informe de Ingresos y Egresos ]*/
            $sql = "INSERT INTO informe_ingresos_egreso_cierre SET ";
            $sql .= "mes = :mes, ";
            $sql .= "anio = :anio, ";
            $sql .= "descripcion = :descripcion, ";
            $sql .= "archivo = :archivo, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            unset($arrData);
            $arrData = [
                'mes' => $model->getMes(),
                'anio' => $model->getAnio(),
                'descripcion' => $model->getDescripcion(),
                'archivo' => $model->getArchivo(),
                'usuario_id_register' => $model->getUsuarioIdCreated()
            ];
            $lastInsertId = $this->insert($sql, $arrData);
            if ($lastInsertId == 0) {
                return false;
            }

            $result = true;
        } else {

            /*-------------------------------------------
            [ Actualizar Informe de Ingresos y Egresos ]*/
            $sql = "UPDATE informe_ingresos_egreso_cierre SET ";
            $sql .= "mes = :mes, ";
            $sql .= "anio = :anio, ";
            $sql .= "descripcion = :descripcion, ";
            $sql .= "archivo = :archivo, ";
            $sql .= "created_at = current_timestamp, ";
            $sql .= "updated_at = current_timestamp, ";
            $sql .= "usuario_id_created = :usuario_id_register, ";
            $sql .= "usuario_id_updated = :usuario_id_register ";
            $sql .= "WHERE ";
            $sql .= "Id = :Id";
            unset($arrData);
            $arrData = [
                'Id' => $arrResponseValid['Id'],
                'mes' => $model->getMes(),
                'anio' => $model->getAnio(),
                'descripcion' => $model->getDescripcion(),
                'archivo' => $model->getArchivo(),
                'usuario_id_register' => $model->getUsuarioIdCreated()
            ];
            $result = $this->update($sql, $arrData);
        }

        /*-------------------------------------------
        [ Retorna Id de Recibo Insertado ]*/
        return $result;
    }


    /**
     * Subrutina dentro de insertInformeIngresosEgresosCierre para generar el pdf de cierre
     * 
     * @param object IngresosEgresosCierreModel $model
     * Envío del modelo por referencia con los datos requeridos para el insert
     *
     */
    private function generarPdf(IngresosEgresosCierreModel &$modelo): bool
    {

        try {


            $response = false;


            $fecha_final = $modelo->getFiltroFechaFin();
            $fecha_inicial = $modelo->getFiltroFechaInicio();

            /*-------------------------------------------
            [ Instanciar Meodelo ]*/
            $estado_resultados_model = new EstadoResultadosModel;
            $estado_resultados_model->setFiltro_fecha_fin($fecha_final);
            $estado_resultados_model->setFiltro_fecha_inicio($fecha_inicial);
            $estado_resultados_model->setSaldo_inicial(SALDO_INICIAL_CUENTA);

            /*-------------------------------------------
            [ Obtiene el saldo anterior a la fecha indicada ]*/
            $importe_ingresos = $estado_resultados_model->selectImporteIngresosSaldo($estado_resultados_model);
            $importe_egresos =  $estado_resultados_model->selectImporteEgresosSaldo($estado_resultados_model);
            $saldo_anterior = $importe_ingresos - $importe_egresos;

            /*-------------------------------------------
            [ Obtiene importe total de ingresos del periodo ]*/
            $ingresos_periodo = $estado_resultados_model->selectImporteIngresosPeriodo($estado_resultados_model);

            /*-------------------------------------------
            [ Obtiene arreglo de desglose de ingresos en un periodo determinado ]*/
            $recibos_model = new RecibosModel;
            $recibos_model->setFecha_fin($fecha_final);
            $recibos_model->setFecha_inicio($fecha_inicial);
            $importe_total_ingresos_desglose = $recibos_model->selectIngresosDesglose($recibos_model);

            /*-------------------------------------------
            [ Obtiene el importe de egreso del periodo ]*/
            $gastos_periodo = $estado_resultados_model->selectImporteEgresosPeriodo($estado_resultados_model);

            /*-------------------------------------------
            [ Obtiene el saldo actual del periodo ]*/
            $saldo_periodo = $saldo_anterior + $ingresos_periodo - $gastos_periodo;


            /*-------------------------------------------
            [ Genera array con datos para el pdf ]*/
            $fecha_saldo_anterior = date("Y-m-d", strtotime($fecha_inicial . "- 1 days"));
            $fecha_saldo_anterior = formatDate($fecha_saldo_anterior);
            $arrData = array(
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
                'fecha_saldo_anterior' => $fecha_saldo_anterior,
                'saldo_anterior' => $saldo_anterior,
                'ingresos_periodo' => $ingresos_periodo,
                'importe_total_ingresos_desglose' => $importe_total_ingresos_desglose,
                'gastos_periodo' => $gastos_periodo,
                'saldo_periodo' => $saldo_periodo
            );

            /*-------------------------------------------
            [ Obtiene lista de ingresos del periodo ]*/
            $arrIngresos = $estado_resultados_model->selectIngresos($estado_resultados_model);
            $arrData['list_ingresos'] = $arrIngresos;

            /*-------------------------------------------
            [ Obtiene lista de egresos del periodo ]*/
            $arrEgresos = $estado_resultados_model->selectEgresos($estado_resultados_model);
            $arrData['list_egresos'] = $arrEgresos;

            /*-------------------------------------------
            [ Asignar Datos a varibales para Base de datos ]*/
            $date = date('YmdHis');
            $archivo = encode($date) . '.pdf';
            $modelo->setArchivo($archivo);

            /*-------------------------------------------
            [ html2pdf ]*/
            $saldo_periodo_letra = 0;
            if ($saldo_periodo > 0) {
                $saldo_periodo_letra = $saldo_periodo;
            }
            // $formatter = new NumeroALetras();
            // $formatter->conector = 'PESOS';
            // $numero_letra = $formatter->toInvoice($saldo_periodo_letra, 2, '');
            $arrData['saldo_periodo_letra'] = '';

            $html = getFile("Template/Pdf/estadoResultados",  $arrData);

            // (izquierda, superior, derecha, inferior)
            $margen_recibo = array(10, 10, 10, 10);

            $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8',  $margen_recibo);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($html, true, false, true, false, '');

            //Genera archivo fisico PDF
            $output_file =  __DIR__ . 'Assets/files/docs/' . $archivo;
            $output_file = str_replace('Models', '', $output_file);
            $html2pdf->output($output_file, 'F');

            //*==================================================================
            // [ Upload File - Copia arvhivo guardado para replicarlo en residentes ]*/
            $destination = 'Assets/files/docs/' . $archivo;
            $dstfile = '/home/histocli/residentes.amoresdedonjuan.org' . '/' . $destination;
            if (is_dir(dirname($dstfile))) {
                $response = @copy($output_file, $dstfile);
            } else {
                $response = true;
            }
        } catch (\Throwable $th) {
            getLoggerSystem()->error($th->getMessage());
        }

        //*==================================================================
        // [Retornar Resultado ]*/
        return $response;
    }


    //*==================================================================
    // [ GETTERS & SETTERS ]*/


    /**
     * Get the value of Id
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Set the value of Id
     */
    public function setId($Id): self
    {
        $this->Id = $Id;

        return $this;
    }

    /**
     * Get the value of mes
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set the value of mes
     */
    public function setMes($mes): self
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get the value of anio
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set the value of anio
     */
    public function setAnio($anio): self
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     */
    public function setDescripcion($descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of archivo
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set the value of archivo
     */
    public function setArchivo($archivo): self
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     */
    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of usuario_id_created
     */
    public function getUsuarioIdCreated()
    {
        return $this->usuario_id_created;
    }

    /**
     * Set the value of usuario_id_created
     */
    public function setUsuarioIdCreated($usuario_id_created): self
    {
        $this->usuario_id_created = $usuario_id_created;

        return $this;
    }

    /**
     * Get the value of updated_at
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     */
    public function setUpdatedAt($updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of usuario_id_updated
     */
    public function getUsuarioIdUpdated()
    {
        return $this->usuario_id_updated;
    }

    /**
     * Set the value of usuario_id_updated
     */
    public function setUsuarioIdUpdated($usuario_id_updated): self
    {
        $this->usuario_id_updated = $usuario_id_updated;

        return $this;
    }

    /**
     * Get the value of filtro_fecha_inicio
     */
    public function getFiltroFechaInicio()
    {
        return $this->filtro_fecha_inicio;
    }

    /**
     * Set the value of filtro_fecha_inicio
     */
    public function setFiltroFechaInicio($filtro_fecha_inicio): self
    {
        $this->filtro_fecha_inicio = $filtro_fecha_inicio;

        return $this;
    }

    /**
     * Get the value of filtro_fecha_fin
     */
    public function getFiltroFechaFin()
    {
        return $this->filtro_fecha_fin;
    }

    /**
     * Set the value of filtro_fecha_fin
     */
    public function setFiltroFechaFin($filtro_fecha_fin): self
    {
        $this->filtro_fecha_fin = $filtro_fecha_fin;

        return $this;
    }
}
