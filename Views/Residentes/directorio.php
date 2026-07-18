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

            <div class="col-12">

                <div class="card">

                    <div class="card-header bg-light">
                        <h4 class="card-title">
                            <?= $data['page_card_title']; ?>
                        </h4>
                    </div>

                    <div class="card-body">

                        <!-- Lista -->
                        <div class="" id="list_tag">

                            <div class="row">

                                <!-- Subtitulos Lista de Comprobantes Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-bars-staggered text-secondary text-secondary-shadow fs-16"></i> Lista General de Residentes del Fraccionamiento.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Comprobantes Registrados -->

                                <div class="form-group col-12  mt-1">

                                    <!-- Tabla de Registros -->
                                    <div class="table-responsive">
                                        <table id="tableDirectorio" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                            <thead class="bg-info text-white custom-text-shadow">
                                                <tr>
                                                    <th class="font-weight-bold text-center">Calle</th>
                                                    <th class="font-weight-bold text-center">Numero</th>
                                                    <th class="font-weight-bold text-center">Nombre</th>
                                                    <th class="font-weight-bold text-center">Telefono</th>
                                                    <th class="font-weight-bold text-center">Email</th>
                                                    <!-- <th class="font-weight-bold text-center">Estatus</th> -->
                                                    <th class="font-weight-bold text-center">Indicaciones Visitas</th>
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