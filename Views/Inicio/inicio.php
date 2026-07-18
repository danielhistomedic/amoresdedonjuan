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
                    <li class="breadcrumb-item active" aria-current="page"><?= $data['page_breadcrumb']; ?></li>
                </ol>
            </div>
            <!-- <div class="ms-auto pageheader-btn">
                <a href="#" class="btn btn-primary bg-primary-gradient btn-icon text-white me-2">
                    <span>
                        <i class="fa-light fa-circle-plus"></i>
                    </span> Nuevo
                </a>
                <a href="#" class="btn btn-success bg-success-gradient btn-icon text-white">
                    <span>
                        <i class="fe fe-log-in"></i>
                    </span> Export
                </a>
            </div> -->
        </div>
        <!-- PAGE-HEADER END -->

        <!-- ROW -->
        <div class="row">

            <div class="col-12">
                <h4 class="mb-4 fw-semibold" style="border-bottom: 1px solid #adb5bd; padding-bottom: 7px;"><?= $data['page_card_description']; ?></h4>
            </div>

        </div>


        <!-- ROW -->
        <div class="row">


            <!-- <i class="fa-light fa-user-lock"></i> -->
            <?php if ($data['permisos'][MOD_USUARIOS]['r']) { ?>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <a href="<?= base_url(); ?>/herramientas/usuarios" class="card-inicio">
                        <div class="card card-inicio">
                            <div class="card-body text-center">
                                <i class="fa-light fa-user-lock text-secondary text-secondary-shadow fa-3x"></i>
                                <h6 class="mt-4 mb-2 fw-semibold fs-14">Registro de Usuarios</h6>
                                <!-- <h2 class="mb-2 number-font">834</h2> -->
                                <p class="text-muted fs-12">Módulo de Registro de Alta de Usuarios que podrán acceder al sistema.</p>
                            </div>
                        </div>
                    </a>
                </div><!-- COL END -->
            <?php  } ?>

            <?php if ($data['permisos'][MOD_RECIBOS_COBRO]['r']) { ?>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <a href="<?= base_url(); ?>/recibos" class="card-inicio">
                        <div class="card card-inicio">
                            <div class="card-body text-center">
                                <i class="fa-light fa-money-check-dollar-pen text-secondary text-secondary-shadow fa-3x"></i>
                                <h6 class="mt-4 mb-2 fw-semibold fs-14">Recibos de Cobro</h6>
                                <!-- <h2 class="mb-2 number-font">834</h2> -->
                                <p class="text-muted fs-12">Módulo de Registro, Control y Expedición de Recibos de Cobro.</p>
                            </div>
                        </div>
                    </a>
                </div><!-- COL END -->
            <?php  } ?>

            <?php if ($data['permisos'][MOD_REGISTRO_GASTOS]['r']) { ?>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <a href="<?= base_url(); ?>/gastos" class="card-inicio">
                        <div class="card card-inicio">
                            <div class="card-body text-center">
                                <i class="fa-light fa-bag-shopping text-secondary text-secondary-shadow fa-3x"></i>
                                <h6 class="mt-4 mb-2 fw-semibold fs-14">Registro de Gastos</h6>
                                <!-- <h2 class="mb-2 number-font">834</h2> -->
                                <p class="text-muted fs-12">Módulo de Registro de Gastos Realizados por la Administración.</p>
                            </div>
                        </div>
                    </a>
                </div><!-- COL END -->
            <?php  } ?>


            <?php if ($data['permisos'][MOD_REGISTRO_ACTIVACION_TAGS]['r']) { ?>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <a href="<?= base_url(); ?>/tags" class="card-inicio">
                        <div class="card card-inicio">
                            <div class="card-body text-center">
                                <i class="fa-light fa-tags text-info text-info-shadow fa-3x"></i>
                                <h6 class="mt-4 mb-2 fw-semibold fs-14">Registro de Tags</h6>
                                <!-- <h2 class="mb-2 number-font">834</h2> -->
                                <p class="text-muted fs-12">Módulo de registro de tag's para asociar residente con numero de tag.</p>
                            </div>
                        </div>
                    </a>
                </div><!-- COL END -->
            <?php  } ?>
            <!-- <i class="fa-light fa-user-check"></i> -->
            <?php if ($data['permisos'][MOD_ESTATUS_RESIDENTE]['r']) { ?>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <a href="<?= base_url(); ?>/estatusResidente" class="card-inicio">
                        <div class="card card-inicio">
                            <div class="card-body text-center">
                                <i class="fa-light fa-user-check text-secondary text-secondary-shadow fa-3x"></i>
                                <h6 class="mt-4 mb-2 fw-semibold fs-14">Estatus Residente</h6>
                                <!-- <h2 class="mb-2 number-font">834</h2> -->
                                <p class="text-muted fs-12">Consulta de Estatus de Residente para acceso en Vigilancia.</p>
                            </div>
                        </div>
                    </a>
                </div><!-- COL END -->
            <?php  } ?>

            <?php if ($data['permisos'][MOD_RECIBOS_COBRO_PASADOS]['r']) { ?>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <a href="<?= base_url(); ?>/recibosAnteriores" class="card-inicio">
                        <div class="card card-inicio">
                            <div class="card-body text-center">
                                <i class="fa-light fa-money-check-dollar-pen text-primary text-primary-shadow fa-3x"></i>
                                <h6 class="mt-4 mb-2 fw-semibold fs-14">Recibos de Cobro Anteriores</h6>
                                <!-- <h2 class="mb-2 number-font">834</h2> -->
                                <p class="text-muted fs-12">Módulo de Registro, Control y Expedición de Pagos Anteriores.</p>
                            </div>
                        </div>
                    </a>
                </div><!-- COL END -->
            <?php  } ?>


            <?php if ($data['permisos'][MOD_INFORMES_ESTADO_CUENTA_RESIDENTE]['r']) { ?>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <a href="<?= base_url(); ?>/InformeEstadoCuentaResdiente" class="card-inicio">
                        <div class="card card-inicio">
                            <div class="card-body text-center">
                                <i class="fa-light fa-chart-user text-success text-success-shadow fa-3x"></i>
                                <h6 class="mt-4 mb-2 fw-semibold fs-14">Estado de Cuenta de Residente</h6>
                                <!-- <h2 class="mb-2 number-font">834</h2> -->
                                <p class="text-muted fs-12">Informe de Estado de Cuenta de Cuotas de Mantenimento.</p>
                            </div>
                        </div>
                    </a>
                </div><!-- COL END -->
            <?php  } ?>

            <?php if ($data['permisos'][MOD_REPORTES_INGRESOS]['r']) { ?>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <a href="<?= base_url(); ?>/reporteIngresos" class="card-inicio">
                        <div class="card card-inicio">
                            <div class="card-body text-center">
                                <i class="fa-light fa-file-invoice-dollar text-success text-success-shadow fa-3x"></i>
                                <h6 class="mt-4 mb-2 fw-semibold fs-14">Reporte de Ingresos</h6>
                                <!-- <h2 class="mb-2 number-font">834</h2> -->
                                <p class="text-muted fs-12">Módulo de Reporte de Ingresos por Periodo.</p>
                            </div>
                        </div>
                    </a>
                </div><!-- COL END -->
            <?php  } ?>


            <?php if ($data['permisos'][MOD_INFORMES_INGRESOS_EGRESOS]['r']) { ?>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <a href="<?= base_url(); ?>/estadoResultados" class="card-inicio">
                        <div class="card card-inicio">
                            <div class="card-body text-center">
                                <i class="fa-light fa-file-chart-column text-success text-success-shadow fa-3x"></i>
                                <h6 class="mt-4 mb-2 fw-semibold fs-14">Ingresos y Egresos</h6>
                                <!-- <h2 class="mb-2 number-font">834</h2> -->
                                <p class="text-muted fs-12">Módulo de Informe de Ingresos y Egresos.</p>
                            </div>
                        </div>
                    </a>
                </div><!-- COL END -->
            <?php  } ?>

            <?php if ($data['permisos'][MOD_REGISTRO_VISITAS_QR]['r']) { ?>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <a href="<?= base_url(); ?>/visitas/registroVisitas" class="card-inicio">
                        <div class="card card-inicio">
                            <div class="card-body text-center">
                                <i class="fa-light fa-qrcode text-secondary text-secondary-shadow fa-3x"></i>
                                <h6 class="mt-4 mb-2 fw-semibold fs-14">Visitas Codigo QR</h6>
                                <!-- <h2 class="mb-2 number-font">834</h2> -->
                                <p class="text-muted fs-12">Registro y control de acceso de visitantes por medio de QR.</p>
                            </div>
                        </div>
                    </a>
                </div><!-- COL END -->
            <?php  } ?>

        </div>
        <!-- ROW -->

    </div>

</div>
<!-- Fin App Content -->


<!-- Footer -->
<?php require_once("Views/Template/footer_admin.php"); ?>

<!-- Custom Script Footer -->

<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->