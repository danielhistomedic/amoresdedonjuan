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
                    <li class="breadcrumb-item"><a href="#">Informes</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $data['page_breadcrumb']; ?></li>
                </ol>
            </div>

        </div>
        <!-- PAGE-HEADER END -->


        <!-- ROW Registro de Tags -->
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
                                <div class="form-group col-12 col-sm-8">

                                    <div class="row">

                                        <!-- Subtitulos Opciones de Filtro -->
                                        <div class="form-group col-12 mb-0">
                                            <div class="border-bottom-subtitle subtitulos_panel collapse-icon" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#opciones-filtro" aria-expanded="false" aria-controls="opciones-filtro">
                                                <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-filter-list text-secondary text-secondary-shadow fs-20 me-2"></i> Opciones de Filtro. <i class="collapse-down fa-regular fa-angles-up"></i></p>
                                            </div>
                                        </div>

                                        <!-- Datos de Filtro -->
                                        <div id="opciones-filtro" class="form-group col-12 collapse show ">


                                            <!-- ROW  Buscar Residente -->
                                            <?php require_once("Views/Template/buscar_residente.php"); ?>


                                            <!-- ROW Datos de Residente Seleccionado -->
                                            <div class="row">

                                                <div class="col-12">

                                                    <div class="card border p-0">
                                                        <div class="card-status card-status-left bg-red br-bl-7 br-tl-7"></div>

                                                        <div class="card-header">
                                                            <div>
                                                                <i class="fa-regular fa-house-chimney-user text-secondary text-secondary-shadow fa-lg me-1"></i>
                                                                <span class="fs-14 fw-semibold">Datos de Residente Seleccionado</span>
                                                            </div>
                                                        </div>

                                                        <div class="card-body">

                                                            <div class="row">

                                                                <input type="hidden" class="" name="residente_id" id="residente_id" value="">

                                                                <div class="col-12 col-sm-6">
                                                                    <div class="d-flex fs-14">
                                                                        <p class="me-1 text-secondary d-flex justify-content-start align-items-center"><i class="fa-light fa-user-check"></i><span class="ms-1 d-none d-xl-block" <strong>Nombre:</strong></span></p>
                                                                        <p id="residente-nombre"> --- </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 col-sm-6">
                                                                    <div class="d-flex fs-14">
                                                                        <p class="me-1 text-secondary d-flex justify-content-start align-items-center"><i class="fa-light fa-house"></i><span class="ms-1 d-none d-xl-block"><strong>Domicilio:</strong></span> </p>
                                                                        <p id="residente-domicilio"> --- </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 col-sm-6">
                                                                    <div class="d-flex fs-14">
                                                                        <p class="me-1 text-secondary d-flex justify-content-start align-items-center"><i class="fa-light fa-at"></i><span class="ms-1 d-none d-xl-block"><strong>Email:</strong></span> </p>
                                                                        <p id="residente-email"> --- </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 col-sm-6">
                                                                    <div class="d-flex fs-14">
                                                                        <p class="me-1 text-secondary d-flex justify-content-start align-items-center"><i class="fa-light fa-mobile"></i><span class="ms-1 d-none d-xl-block"><strong>Telefono:</strong></span> </p>
                                                                        <p id="residente-telefono"> --- </p>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <!-- Fin Opciones de Filtro -->

                                <!-- Resumen de Periodo -->
                                <div class="form-group col-12 col-sm-4">

                                    <div class="row">

                                        <!-- Subtitulos Resumen de Periodo -->
                                        <div class="form-group col-12 mb-0">
                                            <div class="border-bottom-subtitle subtitulos_panel collapse-icon" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#resumen-filtro" aria-expanded="false" aria-controls="resumen-filtro">
                                                <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-money-check-dollar-pen text-secondary text-secondary-shadow fs-20 me-2"></i> Resumen de Estado de Cuenta. <span id="lblPeriodo"></span> <i class="collapse-down-resumen fa-regular fa-angles-up"></i></p>
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
                                                                <label class="form-label fs-14 fw-bold text-danger">Total Adeudo:</label>
                                                                <h5 class="fs-14 text-muted" id="lblTotalAdeudo">$ 0.00</h5>
                                                            </div>
                                                        </div>
                                                        <div class="w-50 d-flex flex-column justify-content-start align-items-start">
                                                            <div class="">
                                                                <label class="form-label mt-1 fs-14 fw-bold text-success">Saldo a Cuenta:</label>
                                                                <h5 class="fs-14 text-muted" id="lblSaldoACuentaDisponible">$ 0.00</h5>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group col-12">
                                                    <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                                        <button type="submit" class="btn btn-secondary bg-warning-gradient  btn-guardar-form d-flex justify-content-center align-items-center <?= $disabled = ($data['permisosMod']['p_excel']) ? '' : 'disabled'; ?>" id="btnImprimirInformeEC">
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


                            <!-- Informe de Estado de Cuenta -->
                            <div class="row">

                                <!-- Subtitulos Lista de Estado de Cuenta Residente -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel collapse-icon" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#panel-egresos" aria-expanded="false" aria-controls="panel-egresos">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-bars-staggered text-secondary text-secondary-shadow fs-20 me-2"></i><span id="title-list">Estado de Cuenta de Residente. <i class="collapse-down-egresos fa-regular fa-angles-up"></i></span></p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Estado de Cuenta Residente -->

                                <div id="panel-egresos" class="collapse show col-12">
                                    <div class="form-group col-12  mt-1">

                                        <!-- Tabla de Registros -->
                                        <div class="table-responsive">

                                            <table id="tableEstadoCuentaResidente" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                                <thead class="bg-success text-white custom-text-shadow">
                                                    <tr>
                                                        <th class="font-weight-bold text-center">Anio</th>
                                                        <th class="font-weight-bold text-center">Mes</th>
                                                        <th class="font-weight-bold text-center">Estatus</th>
                                                        <th class="font-weight-bold text-center">Descripción</th>
                                                        <th class="font-weight-bold text-center">Folio de Pago</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <!-- Fin Tabla de Registros -->

                                    </div>
                                </div>
                            </div>
                            <!-- Fin Informe de Estado de Cuenta -->

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