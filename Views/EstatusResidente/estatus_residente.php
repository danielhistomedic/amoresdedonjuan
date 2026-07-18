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
                    <li class="breadcrumb-item"><a href="<?= base_url(); ?>/inicio">Administración</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url(); ?>/inicio">Seguridad</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $data['page_breadcrumb']; ?></li>
                </ol>
            </div>

        </div>
        <!-- PAGE-HEADER END -->

        <!-- ROW Registro de Tags -->
        <div class="row">

            <div class="form-group">
                <a role="button" class="btn btn-info bg-info-gradient" href="<?= base_url(); ?>/visitas/registroVisitas"><i class="fa-solid fa-qrcode"></i> Registrar Visita QR</a>
            </div>

            <div class="col-12 col-xl-6">

                <div class="card">

                    <div class="card-body pt-3">

                        <!-- Lista -->
                        <div class="" id="list_estado_resultados">

                            <!-- Encabezado -->
                            <div class="row">

                                <!-- Opciones de Filtro -->
                                <div class="form-group col-12  m-0">

                                    <div class="row">


                                        <!-- Datos de Filtro -->
                                        <div id="opciones-filtro" class="form-group col-12  m-0">

                                            <div class="row">

                                                <div class="col-12 ">
                                                    <!-- ROW  Buscar Residente -->
                                                    <?php require_once("Views/Template/buscar_residente.php"); ?>

                                                </div>
                                            </div>

                                            <!-- ROW Datos de Residente Seleccionado -->
                                            <div class="row">

                                                <div class="col-12">

                                                    <div class="card border p-0">

                                                        <div class="card-header">
                                                            <h3 class="card-title"> <i class="fa-regular fa-house-chimney-user text-secondary text-secondary-shadow fa-lg me-1"></i> Estatus de Residente:</h3>

                                                        </div>

                                                        <div class="d-block" id="residente-estatus">

                                                        </div>

                                                        <div class="ps-3 pe-3 pt-3 pb-0 d-flex flex-column fs-17">
                                                            <p class="text-dark mb-1"><strong>Indicaciones Generales de Visita: </strong></p>
                                                            <p class="fs-16 m-0"><span id="indicaciones_generales_visitas"></span></p>
                                                        </div>

                                                        <hr>

                                                        <div class="ps-3 pe-3 pt-0 pb-0 d-flex flex-column fs-17">
                                                            <p class="text-dark mb-1"><strong>Tags Registradas: </strong></p>

                                                            <!-- Tabla de Registros -->
                                                            <div class="table-responsive">
                                                                <table id="tableTagsVigilancia" style="margin-top: 0px!important;" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                                                    <thead class="bg-secondary text-white custom-text-shadow">
                                                                        <tr>
                                                                            <th class="font-weight-bold text-center">Numero</th>
                                                                            <th class="font-weight-bold text-center">Estatus</th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                            <!-- Fin Tabla de Registros -->

                                                        </div>

                                                        <input type="hidden" class="" name="residente_id" id="residente_id" value="">
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <!-- Fin Opciones de Filtro -->

                            </div>
                            <!-- Fin Encabezado -->


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