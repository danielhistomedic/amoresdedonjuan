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
                    <li class="breadcrumb-item"><a href="<?= base_url(); ?>/inicio">Seguimiento</a></li>
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


                        <div class="">

                            <div class="row">

                                <!-- Subtitulos Opciones de Filtro -->
                                <div class="form-group col-12 mb-0">
                                    <div class="border-bottom-subtitle subtitulos_panel collapse-icon" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#opciones-filtro" aria-expanded="true" aria-controls="opciones-filtro">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-filter-list text-secondary text-secondary-shadow fs-20 me-2"></i> Opciones de Filtro. <i class="collapse-down fa-regular fa-angles-up"></i></p>
                                    </div>
                                </div>

                                <!-- Datos de Filtro -->
                                <div id="opciones-filtro" class="form-group col-12 collapse show" style="">

                                    <form name="formReporteVisitas" id="formReporteVisitas">

                                        <div class="row">

                                            <div class="form-group col-12 m-0 mt-1">
                                                <label class="form-label text-secondary fw-bold">Seleccione Periodo:</label>
                                            </div>

                                            <!-- Fecha Filtro Ini -->
                                            <div class="form-group col-12 col-sm-6">
                                                <label class="form-label text-primary" for="inputFechaIniPeriodo">Fecha Inicial:</label>
                                                <input type="text" class="form-control inputForm100 inputDateMask" data-toggle="datepicker" name="inputFechaIniPeriodo" id="inputFechaIniPeriodo" placeholder="dd/mm/aaaa" required="" maxlength="10">
                                            </div>
                                            <!-- Fin Fecha Filtro Ini -->




                                            <!-- Fecha Filtro Final -->
                                            <div class="form-group col-12 col-sm-6">
                                                <label class="form-label text-primary" for="inputFechaFinPeriodo">Fecha Final:</label>
                                                <input type="text" class="form-control inputForm100 inputDateMask" data-toggle="datepicker" name="inputFechaFinPeriodo" id="inputFechaFinPeriodo" placeholder="dd/mm/aaaa" required="" maxlength="10">
                                            </div>
                                            <!-- Fin Fecha Filtro Final -->

                                            <div class="form-group col-12">
                                                <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                                    <button type="submit" class="bg-warning-gradient btn btn-secondary bg-warning-gradient  btn-guardar-form d-flex justify-content-center align-items-center" id="btnMostarReporteVisitas">
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


                        <!-- Lista -->
                        <div class="" id="list_tag">

                            <div class="row">

                                <!-- Subtitulos Lista de Comprobantes Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-bars-staggered text-secondary text-secondary-shadow fs-16"></i> Lista General de Visitas Registradas.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Comprobantes Registrados -->

                                <div class="form-group col-12  mt-1">

                                    <!-- Tabla de Registros -->
                                    <div class="table-responsive">
                                        <table id="tablePerfilVisitas" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                            <thead class="bg-success text-white custom-text-shadow">
                                                <tr>
                                                    <th class="font-weight-bold text-center">No.</th>
                                                    <th class="font-weight-bold text-center">Fecha</th>
                                                    <th class="font-weight-bold text-center">Residente</th>
                                                    <th class="font-weight-bold text-center">Visitante</th>
                                                    <th class="font-weight-bold text-center">Telefono</th>
                                                    <th class="font-weight-bold text-center">Comentarios</th>
                                                    <th class="font-weight-bold text-center">Estatus</th>
                                                    <th class="font-weight-bold text-center">Hora Ingreso</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <!-- Fin Tabla de Registros -->

                                </div>

                            </div>

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