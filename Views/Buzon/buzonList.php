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
                    <li class="breadcrumb-item"><a href="#">Seguimiento</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $data['page_breadcrumb']; ?></li>
                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->


        <!-- ROW  Buscar Residente -->
        <?php require_once("Views/Template/buscar_residente.php"); ?>


        <!-- ROW Datos de Residente Seleccionado -->
        <div class="row">

            <input type="hidden" class="" name="residente_id" id="residente_id" value="">

            <div class="col-12">

                <div class="card">

                    <div class="card-header bg-light">
                        <h4 class="card-title w-100 d-flex">
                            <div> <i class="fa-regular fa-house-chimney-user text-warning text-warning-shadow fa-lg me-1"></i> Datos de Residente Seleccionado</div>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fa-regular fa-angle-up"></i></a>
                            </div>
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-12 col-sm-6">
                                <div class="d-flex fs-14">
                                    <p class="me-1 text-warning"><i class="fa-light fa-user-check"></i> <strong>Nombre:</strong> </p>
                                    <p id="residente-nombre"> --- </p>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="d-flex fs-14">
                                    <p class="me-1 text-warning"><i class="fa-light fa-house"></i> <strong>Domicilio:</strong> </p>
                                    <p id="residente-domicilio"> --- </p>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="d-flex fs-14">
                                    <p class="me-1 text-warning"><i class="fa-light fa-at"></i> <strong>Email:</strong> </p>
                                    <p id="residente-email"> --- </p>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="d-flex fs-14">
                                    <p class="me-1 text-warning"><i class="fa-light fa-mobile"></i> <strong>Telefono:</strong> </p>
                                    <p id="residente-telefono"> --- </p>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="page-header mt-0">

            <!-- <div class="ms-auto pageheader-btn">
                    <btn class="btn btn-warning bg-warning-gradient btn-icon text-white me-2 crear_editar_tag" id="btnNuevaTag" data-animation="fadeInDown">
                        <span>
                            <i class="fa-regular fa-circle-plus me-1"></i>
                        </span> Nuevo
                    </btn>
                </div> -->

            <div class="ms-2 pageheader-btn">
                <btn class="btn btn-warning bg-warning-gradient btn-icon text-white me-2 crear_editar_tag" id="btnMostrarTodos" data-animation="fadeInDown">
                    <span>
                        <i class="fa-regular fa-list-radio me-1"></i>
                    </span> Mostrar Todos
                </btn>
            </div>
        </div>


        <!-- ROW Registro de Tags -->
        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header bg-light">
                        <h4 class="card-title">
                            <i class="fa-regular fa-mailbox text-warning text-warning-shadow fa-lg me-1"></i> Registros de Buzón de Residentes
                        </h4>
                    </div>

                    <div class="card-body">

                        <!-- Lista -->
                        <div class="" id="list_tag">

                            <div class="row">

                                <!-- Subtitulos Lista de Comprobantes Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-bars-staggered text-warning text-warning-shadow fs-16"></i> Lista de Mensajes de Bizón Registrados.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Comprobantes Registrados -->

                                <div class="form-group col-12  mt-1">

                                    <!-- Tabla de Registros -->
                                    <div class="table-responsive">
                                        <table id="tablePerfilSeguimientoBuzon" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                            <thead class="bg-success text-white custom-text-shadow">
                                                <tr>
                                                    <th class="font-weight-bold text-center">No.</th>
                                                    <th class="font-weight-bold text-center">Residente</th>
                                                    <th class="font-weight-bold text-center">Domicilio</th>
                                                    <th class="font-weight-bold text-center">Folio</th>
                                                    <th class="font-weight-bold text-center">Fecha</th>
                                                    <th class="font-weight-bold text-center">Tipo</th>
                                                    <th class="font-weight-bold text-center">Asunto</th>
                                                    <th class="font-weight-bold text-center">Estatus</th>
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