<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Cobro</title>

    <!-- BOOTSTRAP CSS -->
    <!-- <link href="http://localhost/amores/Assets/plugins/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" /> -->

    <!-- MDB -->
    <!-- <link href="http://localhost/amores/Assets/plugins/MDB5-3.9.0/css/mdb.min.css" rel="stylesheet" /> -->

    <!-- STYLE CSS -->
    <!-- <link href="http://localhost/amores/Assets/css/style.css" rel="stylesheet" /> -->

    <style>
        .border {
            border: solid 1px #ccc;
        }

        .border-bottom {
            border-bottom: 1px solid #ccc;
        }

        .pos-r {
            position: relative;
        }

        table {
            width: 100%;
        }

        table td,
        table th {
            font-size: 10px;
        }

        h4 {
            margin: 0;
        }

        h3 {
            margin: 0;
        }

        h5 {
            margin: 0;
        }

        h6 {
            margin: 0;
        }

        p {
            margin: 0;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }


        .text-lowercase {
            text-transform: lowercase;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        .text-color-orange {
            color: #fb6b25;
        }

        .text-color-red {
            color: #d80024;
        }

        .text-color-success {
            color: #09ad95;
        }

        .tbl-base {
            border: .5px solid #c7c7c7;
            border-radius: 5px;
            padding: 5px;
        }

        .tbl-base-totales {
            border: .5px solid #c7c7c7;
            padding: 5px;
            border-radius: 0 5px;
        }

        .border-titulo {
            border-bottom: 3px solid #ffc109;
        }

        .border {
            border: 1px solid #f8f9fa;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .bg-green {
            background-color: #09ad95;
        }

        .head-center {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .tbl-detalle-ingresos {
            border-collapse: collapse;
            border-radius: 5px;
            border: .5px solid #c7c7c7;

        }

        .tbl-detalle-ingresos thead th {
            padding: 5px;
            background-color: #09ad95;
            color: #fff;

        }

        .tbl-detalle-ingresos tbody td {
            padding: 5px;
            border-bottom: 0.5px solid #c7c7c7;
        }

        .tbl-detalle-anteriores {
            border-collapse: collapse;
            border-radius: 5px;
            border: .5px solid #c7c7c7;

        }

        .tbl-detalle-anteriores thead th {
            padding: 5px;
            background-color: #6259ca;
            color: #fff;

        }

        .tbl-detalle-anteriores tbody td {
            padding: 5px;
            border-bottom: 0.5px solid #c7c7c7;
        }



        .align-baseline {
            vertical-align: baseline;
        }

        .align-top {
            vertical-align: top;
        }

        .align-middle {
            vertical-align: middle;
        }

        .align-bottom {
            vertical-align: bottom;
        }

        .align-text-bottom {
            vertical-align: text-bottom;
        }

        .m-auto {
            margin: auto;
        }

        .mt-auto,
        .my-auto {
            margin-top: auto;
        }

        .me-auto,
        .mx-auto {
            margin-right: auto;
        }

        .mb-auto,
        .my-auto {
            margin-bottom: auto;
        }

        .ms-auto,
        .mx-auto {
            margin-left: auto;
        }

        .fw-light {
            font-weight: 300;
        }

        .fw-normal {
            font-weight: 400;
        }

        .fw-600 {
            font-weight: 600;
        }

        .fw-semibold {
            font-weight: 500;
        }

        .fw-bold {
            font-weight: 700;
        }

        .font-italic {
            font-style: italic;
        }

        .wdMain {
            width: 93%;
        }

        .wd100 {
            width: 100%;
        }

        .wd95 {
            width: 95%;
        }

        .wd90 {
            width: 90%;
        }

        .wd80 {
            width: 80%;
        }

        .wd70 {
            width: 70%;
        }

        .wd65 {
            width: 65%;
        }

        .wd60 {
            width: 60%;
        }

        .wd55 {
            width: 55%;
        }

        .wd50 {
            width: 50%;
        }

        .wd45 {
            width: 45%;
        }

        .wd40 {
            width: 40%;
        }

        .wd34 {
            width: 34%;
        }

        .wd30 {
            width: 30%;
        }

        .wd25 {
            width: 25%;
        }

        .wd20 {
            width: 20%;
        }

        .wd16 {
            width: 16%;
        }

        .wd15 {
            width: 15%;
        }

        .wd12 {
            width: 12%;
        }


        .wd10 {
            width: 10%;
        }

        .wd8 {
            width: 8%;
        }

        .fs-8 {
            font-size: 8px;
        }

        .fs-9 {
            font-size: 9px;
        }

        .fs-10 {
            font-size: 10px;
        }

        .fs-11 {
            font-size: 11px;
        }

        .fs-12 {
            font-size: 12px;
        }

        .fs-13 {
            font-size: 13px;
        }

        .fs-14 {
            font-size: 14px;
        }

        .fs-15 {
            font-size: 15px;
        }

        .fs-16 {
            font-size: 16px;
        }

        .fs-17 {
            font-size: 17px;
        }

        .fs-18 {
            font-size: 18px;
        }

        .fs-19 {
            font-size: 19px;
        }

        .fs-20 {
            font-size: 20px;
        }

        .text-bold {
            font-weight: bold;
        }



        .mt-0 {
            margin-top: 0;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mt-3 {
            margin-top: 16px;
        }

        .mt-4 {
            margin-top: 24px;
        }

        .mt-5 {
            margin-top: 48px;
        }


        .ms-0 {
            margin-left: 0;
        }

        .ms-1 {
            margin-left: 4px;
        }

        .ms-2 {
            margin-left: 8px;
        }

        .ms-3 {
            margin-left: 16px;
        }

        .ms-4 {
            margin-left: 24px;
        }

        .ms-5 {
            margin-left: 48px;
        }


        .me-0 {
            margin-right: 0;
        }

        .me-1 {
            margin-right: 4px;
        }

        .me-2 {
            margin-right: 8px;
        }

        .me-3 {
            margin-right: 16px;
        }

        .me-4 {
            margin-right: 24px;
        }

        .me-5 {
            margin-right: 48px;
        }


        .mb-0 {
            margin-bottom: 0;
        }

        .mb-1 {
            margin-bottom: 4px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mb-3 {
            margin-bottom: 16px;
        }

        .mb-4 {
            margin-bottom: 24px;
        }

        .mb-5 {
            margin-bottom: 48px;
        }


        .pt-0 {
            padding-top: 0;
        }

        .pt-1 {
            padding-top: 4px;
        }

        .pt-2 {
            padding-top: 8px;
        }

        .pt-3 {
            padding-top: 16px;
        }

        .pt-4 {
            padding-top: 24px;
        }

        .pt-5 {
            padding-top: 48px;
        }

        .pe-0 {
            padding-right: 0;
        }

        .pe-1 {
            padding-right: 4px;
        }

        .pe-2 {
            padding-right: 8px;
        }

        .pe-3 {
            padding-right: 16px;
        }

        .pe-4 {
            padding-right: 24px;
        }

        .pe-5 {
            padding-right: 48px;
        }

        .pe-6 {
            padding-right: 96px;
        }

        .pe-7 {
            padding-right: 192px;
        }

        .pe-8 {
            padding-right: 384px;
        }

        .pb-0 {
            padding-bottom: 0;
        }

        .pb-1 {
            padding-bottom: 4px;
        }

        .pb-2 {
            padding-bottom: 8px;
        }

        .pb-3 {
            padding-bottom: 16px;
        }

        .pb-4 {
            padding-bottom: 24px;
        }

        .pb-5 {
            padding-bottom: 48px;
        }

        .ps-0 {
            padding-left: 0;
        }

        .ps-1 {
            padding-left: 4px;
        }

        .ps-2 {
            padding-left: 8px;
        }

        .ps-3 {
            padding-left: 16px;
        }

        .ps-4 {
            padding-left: 24px;
        }

        .ps-5 {
            padding-left: 48px;

        }
    </style>


    <style>
        #footer {
            padding-top: 5px 0;
            border-top: .5px solid #ffc109;
            width: 100%;
        }

        #footer .fila td {
            text-align: center;
            width: 100%;
        }

        #footer .fila td span {
            font-size: 10px;
            /* color: #f5a; */
        }
    </style>

</head>

<body>

    <!-- <page> -->


    <!-- <page_header>
       
    </page_header> -->

    <table class="tbl-header">
        <tbody>
            <tr>
                <td class="text-start wd10">
                    <img src="<?= media(); ?>/images/brand/logo.png" alt="Logo" style="width: 80px;">
                </td>
                <td class="ps-2 text-start wd90 ">
                    <h4><?= NOMBRE_EMPRESA; ?></h4>
                    <p><?= DOMICILIO_EMPRESA; ?> <br>
                        <?= DOMICILIO_EMPRESA_2; ?></p>
                </td>
            </tr>
        </tbody>
    </table>


    <div class="mt-1 wd100 border-titulo"></div>

    <div class="mt-3 text-center wd100">
        <h4>REPORTE DE INGRESOS</h4>
    </div>


    <table class="mt-4 tbl-base bg-light">
        <tbody>
            <tr>
                <td class="wd100 text-center ">
                    <h4 class="fs-14"> <span class="text-uppercase">Periodo de Consulta:</span> (Del <?= formatDate($data['fecha_inicial']); ?> al <?= formatDate($data['fecha_final']); ?>)</h4>
                </td>
            </tr>
        </tbody>
    </table>


    <table class="tbl-base">
        <tbody class="">
            <tr class="">
                <td class="wd30 align-middle fs-12"><strong>Importe Total Ingresos:</strong></td>
                <td class="wd20 align-middle fs-12 text-end pe-3"><?= formatMoney($data['importe_total_ingresos']); ?> </td>
                <td class="wd30 align-middle fs-12 ps-2 text-color-success"><strong>Total de Recibos:</strong> </td>
                <td class="wd20 align-middle fs-12 text-end pe-3"><?= $data['cantidad_recibos_expedidos']; ?></td>
            </tr>
            <tr class="">
                <td colspan="2" class="wd50 align-middle fs-12 text-start ps-2">

                    <div style="display:flex; flex-direction:column;">
                        <?php
                        $arrDesglose = $data['importe_total_ingresos_desglose'];
                        for ($i_des = 0; $i_des < count($arrDesglose); $i_des++) { ?>
                            <span class="text-primary">** <?= $arrDesglose[$i_des]['concepto']; ?>: </span>
                            <span class="text-muted"><strong><?= formatMoney($arrDesglose[$i_des]['importe']); ?></strong></span><br />
                        <?php  }  ?>
                    </div>
                </td>
                <td colspan="2" class="wd50"></td>
            </tr>
            <tr class="">
                <td class="wd30 align-middle fs-12 text-color-orange"><strong>Saldo Utilizado:</strong> </td>
                <td class="wd20 align-middle fs-12 text-end pe-3"><?= formatMoney($data['saldo_utilizado']); ?></td>
                <td class="wd30 align-middle fs-12 ps-2"><strong>Importe Dejado a Cuenta:</strong> </td>
                <td class="wd20 align-middle fs-12 text-end pe-3"><strong><?= formatMoney($data['importe_dejado_a_cuenta']); ?></strong> </td>
            </tr>
        </tbody>
    </table>


    <!-- <table class="mt-4 tbl-base bg-light">
        <tbody>
            <tr>
                <td class="wd100 text-center ">
                    <h4 class="fs-14 text-uppercase">Ingresos</h4>
                </td>
            </tr>
        </tbody>
    </table> -->

    <div class="mt-3 text-left wd100">
        <h5>Detalle de Recibos de Cobro</h5>
    </div>

    <table class="tbl-detalle-ingresos mt-2">
        <thead>
            <tr>
                <th class="wd10 align-middle text-center" style="border-radius: 5px 0 0 0;">DOMICILIO</th>
                <th class="wd10 align-middle text-center" style="border-radius: 0 0 0 0;">FOLIO</th>
                <th class="wd10 align-middle text-center" style="border-radius: 0 0 0 0;">FECHA-HORA</th>
                <th class="align-middle text-center" style="width:14%; border-radius: 0 0 0 0;">CLASIFICACION</th>
                <th class="align-middle text-center" style="width:36%; border-radius: 0 0 0 0;">CONCEPTO</th>
                <th class="wd10 align-middle text-center" style="border-radius: 0 0 0 0;">IMPORTE</th>
                <th class="wd10 align-middle text-center" style="border-radius: 0 5px 0 0;">ESTATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arrIngresos = $data['list_ingresos'];
            for ($i = 0; $i < count($arrIngresos); $i++) { ?>
                <tr>
                    <?php
                    $color_estatus = '';
                    if ($arrIngresos[$i]['estatus'] != 0) {
                        $color_estatus = 'text-color-red';
                    } ?>
                    <td class="wd10 text-center <?= $color_estatus; ?>"><?= $arrIngresos[$i]['domicilio']; ?></td>

                    <td class="wd10 text-center <?= $color_estatus; ?>"><?= $arrIngresos[$i]['folio']; ?></td>
                    <td class="wd10 text-center <?= $color_estatus; ?>"><?= $arrIngresos[$i]['created_at']; ?></td>
                    <td style="width:14%;" class="text-start <?= $color_estatus; ?>"><?= $arrIngresos[$i]['clasificacion_ingreso']; ?></td>
                    <td style="width:36%;" class="text-start <?= $color_estatus; ?>"><?= $arrIngresos[$i]['concepto']; ?></td>
                    <td class="wd10 text-end <?= $color_estatus; ?> pe-1"><?= formatMoney($arrIngresos[$i]['importe']); ?></td>

                    <td class="wd10 text-center <?= $color_estatus; ?>">
                        <?php
                        if ($arrIngresos[$i]['estatus'] == 0) {
                            echo 'VIGENTE';
                        } else {
                            echo 'CANCELADO';
                        }
                        ?>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    <div class="mt-4 text-left wd100">
        <h5>Detalle de Comprobantes Aplicados con Otra Fecha</h5>
    </div>
    <?php
    $arrRecibosAnteriores = $data['list_recibos_anteriores'];
    if (count($arrRecibosAnteriores) == 0) {  ?>

        <div class="mt-2 text-left wd100">
            <p>No se encontraron registros en el Perdiodo seleccionado.</p>
        </div>

    <?php } else { ?>

        <table class="tbl-detalle-anteriores mt-2">
            <thead>
                <tr>
                    <th class="wd10 align-middle text-center" style="border-radius: 5px 0 0 0;">DOMICILIO</th>
                    <th class="wd10 align-middle text-center" style="border-radius: 0 0 0 0;">FOLIO REGISTRO</th>
                    <th class="wd10 align-middle text-center" style="border-radius: 0 0 0 0;">FECHA REGISTRO</th>
                    <th class="wd45 align-middle text-center" style="border-radius: 0 0 0 0;">MESES AMPARA COMPROBANTE</th>
                    <th class="wd15 align-middle text-center" style="border-radius: 0 0 0 0;">FOLIO ANTERIOR</th>
                    <th class="wd10 align-middle text-center" style="border-radius: 0 5px 0 0;">ESTATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $font_size = '';

                if (count($arrRecibosAnteriores) > 10) {
                    $font_size = 'fs-8';
                }
                for ($i = 0; $i < count($arrRecibosAnteriores); $i++) { ?>
                    <tr>
                        <?php
                        $color_estatus = '';
                        if ($arrRecibosAnteriores[$i]['estatus'] != 0) {
                            $color_estatus = 'text-color-red';
                        } ?>

                        <td class="wd10 text-center <?= $color_estatus; ?>"><?= $arrRecibosAnteriores[$i]['domicilio']; ?></td>
                        <td class="wd10 text-center <?= $color_estatus; ?>"><?= $arrRecibosAnteriores[$i]['folio']; ?></td>
                        <td class="wd10 text-center <?= $color_estatus; ?>"><?= $arrRecibosAnteriores[$i]['created_at']; ?></td>
                        <td class="wd45 text-start <?= $color_estatus; ?> <?= $font_size; ?>"><?= $arrRecibosAnteriores[$i]['concepto']; ?></td>
                        <td class="wd15 text-center <?= $color_estatus; ?>"><?= $arrRecibosAnteriores[$i]['folio_anterior']; ?></td>
                        <td class="wd10 text-center <?= $color_estatus; ?>">
                            <?php
                            if ($arrRecibosAnteriores[$i]['estatus'] == 0) {
                                echo 'VIGENTE';
                            } else {
                                echo 'CANCELADO';
                            }
                            ?>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } ?>


    <page_footer>
        <table id="footer">
            <tr class="fila">
                <td style="width: 100%;">
                    <span>Información Confidencial - <?= NOMBRE_EMPRESA; ?></span>
                </td>
            </tr>
        </table>
    </page_footer>

    <!-- </page> -->
</body>

</html>