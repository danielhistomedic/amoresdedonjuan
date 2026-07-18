<!-- Header Admin -->
<?php require_once("Views/Template/header_admin.php"); ?>

<!-- Custom Css/Script -->

<!-- Fin Custom Css/Script -->

<?php require_once("Views/Template/header_admin_end.php"); ?>
<!-- Fin Header Admin -->

<!-- Panel Head -->
<?php require_once("Views/Template/page-head.php"); ?>
<!-- Fin Panel Head -->

<!-- App Content -->
<div class="app-content hor-content">

    <div class="container">

        <!-- PAGE-HEADER -->
        <div class="page-header">

            <div>
                <h1 class="page-title"><?= $data['page_form_title']; ?></h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url(); ?>/inicio">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="#">Administración</a></li>
                    <li class="breadcrumb-item"><a href="#">Reportes</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $data['page_breadcrumb']; ?></li>
                </ol>
            </div>

        </div>
        <!-- PAGE-HEADER END -->


        <!-- ROW Reporte de Egresos -->
        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header bg-light">
                        <h4 class="card-title">
                            <?= $data['page_card_title']; ?>
                        </h4>
                    </div>

                    <div class="card-body">

                        <!-- Lista -->
                        <div class="" id="list_estado_resultados">

                            <!-- Encabezado -->
                            <div class="row">

                                <!-- Opciones de Filtro -->
                                <div class="form-group col-12 col-sm-6">

                                    <div class="row">

                                        <!-- Subtitulos Opciones de Filtro -->
                                        <div class="form-group col-12 mb-0">
                                            <div class="border-bottom-subtitle subtitulos_panel collapse-icon" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#opciones-filtro" aria-expanded="false" aria-controls="opciones-filtro">
                                                <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-filter-list text-secondary text-secondary-shadow fs-20 me-2"></i> Opciones de Filtro. <i class="collapse-down fa-regular fa-angles-up"></i></p>
                                            </div>
                                        </div>

                                        <!-- Datos de Filtro -->
                                        <div id="opciones-filtro" class="form-group col-12 collapse show ">

                                            <form name="formMostrarReporteEgresos" id="formMostrarReporteEgresos">

                                                <div class="row">

                                                    <div class="form-group col-12 m-0 mt-1">
                                                        <label class="form-label text-secondary fw-bold">Seleccione Periodo:</label>
                                                    </div>

                                                    <!-- Fecha Filtro Ini -->
                                                    <div class="form-group col-12 col-sm-6">
                                                        <label class="form-label text-primary" for="inputFechaIniPeriodo">Fecha Inicial:</label>
                                                        <input type="text" class="form-control inputForm100 inputDateMask" data-toggle="datepicker" name="inputFechaIniPeriodo" id="inputFechaIniPeriodo" placeholder="dd/mm/aaaa" required>
                                                    </div>
                                                    <!-- Fin Fecha Filtro Ini -->




                                                    <!-- Fecha Filtro Final -->
                                                    <div class="form-group col-12 col-sm-6">
                                                        <label class="form-label text-primary" for="inputFechaFinPeriodo">Fecha Final:</label>
                                                        <input type="text" class="form-control inputForm100 inputDateMask" data-toggle="datepicker" name="inputFechaFinPeriodo" id="inputFechaFinPeriodo" placeholder="dd/mm/aaaa" required>
                                                    </div>
                                                    <!-- Fin Fecha Filtro Final -->

                                                    <div class="form-group col-12">
                                                        <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                                            <button type="submit" class="bg-warning-gradient btn btn-secondary bg-warning-gradient  btn-guardar-form d-flex justify-content-center align-items-center" id="btnMostarReporteEgresos">
                                                                <div class="d-flex justify-content-center align-items-center">
                                                                    <i class="fa-regular fa-filter fa-fw fa-lg me-1"></i>
                                                                    <span class="">Mostrar Reporte</span>
                                                                    <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                                </div>
                                                            </button>
                                                        </div>

                                                    </div>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>
                                <!-- Fin Opciones de Filtro -->

                                <!-- Resumen de Periodo -->
                                <div class="form-group col-12 col-sm-6">

                                    <div class="row">

                                        <!-- Subtitulos Resumen de Periodo -->
                                        <div class="form-group col-12 mb-0">
                                            <div class="border-bottom-subtitle subtitulos_panel collapse-icon" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#resumen-filtro" aria-expanded="false" aria-controls="resumen-filtro">
                                                <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-money-check-dollar-pen text-secondary text-secondary-shadow fs-20 me-2"></i> Resumen de Periodo. <span id="lblPeriodo"></span> <i class="collapse-down-resumen fa-regular fa-angles-up"></i></p>
                                            </div>
                                        </div>

                                        <!-- Datos de Resumen -->
                                        <div id="resumen-filtro" class="form-group col-12 collapse show ">

                                            <div id="loading-resumen">
                                                <div>
                                                    <!-- Cargar Loader con CSS -->
                                                    <div class="lds-double-ring">
                                                        <div></div>
                                                        <div></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="form-group col-12 m-0 mt-1">

                                                    <div class="form-label mt-0 col-12 d-flex justify-content-start align-items-start">

                                                        <div class="w-50 d-flex flex-column justify-content-start align-items-start">

                                                            <div class="">
                                                                <label class="form-label fs-14 fw-bold text-primary">Importe Total Egresos:</label>
                                                                <h5 class="fs-14 text-muted" id="lblTotalEgresos">$ 0.00</h5>
                                                            </div>
                                                            <!-- <div class="">
                                                                <label class="form-label mt-1 fs-14 fw-bold text-success">Cantidad de Recibos Expedidos:</label>
                                                                <h5 class="fs-14 text-muted" id="lblCantidadRecibosExpedidos">$ 0.00</h5>
                                                            </div> -->

                                                        </div>

                                                        <!-- <div class="w-50 d-flex flex-column justify-content-start align-items-start">
                                                            <div class="">
                                                                <label class="form-label fs-14 fw-bold text-secondary">Saldo Utilizado:</label>
                                                                <h5 class="fs-14 text-muted" id="lblSaldoUtilizado">$ 0.00</h5>
                                                            </div>

                                                            <div class="">
                                                                <label class="form-label mt-1 fs-14 fw-bold text-primary">Importe Dejado a Cuenta:</label>
                                                                <h5 class="fs-14 text-muted" id="lblImporteDejadoACuenta">$ 0.00</h5>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                                <div class="form-group col-12">
                                                    <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                                        <button type="submit" class="btn btn-secondary bg-warning-gradient  btn-guardar-form d-flex justify-content-center align-items-center <?= $disabled = ($data['permisosMod']['p_excel']) ? '' : 'disabled'; ?>" id="btnImprimirReporteEgresos">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <i class="fa-regular fa-print fa-fw fa-lg me-1"></i>
                                                                <span class="">Imprimir</span>
                                                                <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                            </div>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <!-- Fin Resumen de Periodo -->

                            </div>
                            <!-- Fin Encabezado -->

                            <!-- Informe de Egresos -->
                            <div class="row">

                                <!-- Subtitulos Lista de Egresos del Periodo -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel collapse-icon" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#panel-egresos" aria-expanded="false" aria-controls="panel-egresos">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-bars-staggered text-success text-success-shadow fs-20 me-2"></i><span id="title-list">Lista de Egresos del Periodo. <i class="collapse-down-egresos fa-regular fa-angles-up"></i></span></p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Egresos del Periodo -->
                                <div id="panel-egresos" class="collapse show col-12">
                                    <div class="form-group col-12  mt-1">

                                        <!-- Tabla de Registros -->
                                        <div class="table-responsive">
                                            <table id="tableReporteEgresos" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                                <thead class="bg-success text-white custom-text-shadow">
                                                    <tr>
                                                        <th class="font-weight-bold text-center">Fecha de Pago</th>
                                                        <th class="font-weight-bold text-center">Clasificación</th>
                                                        <th class="font-weight-bold text-center">Proveedor</th>
                                                        <th class="font-weight-bold text-center">Fecha de Nota/Factura</th>
                                                        <th class="font-weight-bold text-center">Folio</th>
                                                        <th class="font-weight-bold text-center">Descripción</th>
                                                        <th class="font-weight-bold text-center">Importe</th>
                                                        <th class="font-weight-bold text-center">Archivo</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <!-- Fin Tabla de Registros -->

                                    </div>
                                </div>

                            </div>
                            <!-- Fin Informe de Egresos -->


                        </div>
                        <!-- Fin Lista -->

                    </div>

                </div>

            </div>
            <!-- ROW -->

        </div>

    </div>

</div>
<!-- Fin App Content -->


<!-- Footer -->
<?php require_once("Views/Template/footer_admin.php"); ?>

<!-- Custom Script Footer -->

<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->