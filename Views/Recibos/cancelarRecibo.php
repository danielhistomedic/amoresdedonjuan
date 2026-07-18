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
                    <li class="breadcrumb-item"><a href="<?= base_url(); ?>/recibos">Recibos</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $data['page_breadcrumb']; ?></li>
                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->



        <!-- ROW Datos de Residente Seleccionado -->
        <div class="row">

            <div class="col-lg-12 col-xl-6 col-md-12 col-sm-12">

                <div class="card">

                    <div class="card-header bg-header-card">
                        <h4 class="card-title">
                            <?= $data['page_card_title']; ?>
                        </h4>
                    </div>

                    <div class="card-body">

                        <!-- loading start -->
                        <div id="loading-resumen">
                            <div class="dimmer active">
                                <div class="lds-ring">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                        <!-- loading end -->

                        <form id="formCancelarRecibo" class="validate-form needs-validation" novalidate>

                            <div class="form-group mt-1">
                                <div class="alert alert-warning" role="alert">
                                    <div>
                                        <span class="alert-inner--icon"><i class="fa-light fa-bell tx-18"></i></span>
                                        <span class="alert-inner--text tx-12 ms-1">Por solicitud de Los Representantes de Calles, en oficio S/N de fecha 29 de dicimebre del 2025,
                                            a partir del 31 de diciembre del 2025 se restringe la cancelación de recibos a la Administración, debiendo solicitar autorización a dichos representantes
                                            exponiendo los motivos de cancelación.
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mt-0">
                                <label for="recibo_cancela" class="form-label mt-0">Indique Folio de Recibo Completo a Cancelar:</label>
                                <input type="text" class="form-control disabled" name="recibo_cancela" id="recibo_cancela" value="<?= $data['recibo']['folio']; ?>" placeholder="" required disabled>
                            </div>

                            <div class="form-group mt-0">
                                <label for="motivo_cancela" class="form-label mt-0">Indique Motivo de Cancelación:</label>
                                <input type="text" class="form-control" name="motivo_cancela" id="motivo_cancela" placeholder="" required>
                            </div>
                            <!-- $data['recibo'] -->
                            <div class="row d-none">

                                <input type="hidden" class="" name="recibo_id" id="recibo_id" value="<?= $data['recibo_id']; ?>">
                                <input type="hidden" class="" name="residente_id" id="residente_id" value="<?= $data['recibo']['residente_id']; ?>">

                                <div class="form-group col-12">
                                    <div class="d-flex fs-14">
                                        <p class="me-1 text-info"><i class="fa-light fa-user-check fa-fw"></i> <strong>Nombre:</strong> </p>
                                        <p id="residente-nombre"> --- </p>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-6">
                                    <div class="d-flex fs-14">
                                        <p class="me-1 text-info"><i class="fa-light fa-house fa-fw"></i> <strong>Calle:</strong> </p>
                                        <p id="residente-calle"> --- </p>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-6">
                                    <div class="d-flex fs-14">
                                        <p class="me-1 text-info"><i class="fa-light fa-house fa-fw"></i> <strong>Numero:</strong> </p>
                                        <p id="residente-numero"> --- </p>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-6">
                                    <div class="d-flex fs-14">
                                        <p class="me-1 text-info"><i class="fa-light fa-mobile fa-fw"></i> <strong>Telefono:</strong> </p>
                                        <p id="residente-telefono"> --- </p>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-6">
                                    <div class="d-flex fs-14">
                                        <p class="me-1 text-info"><i class="fa-light fa-at fa-fw"></i> <strong>Email:</strong> </p>
                                        <p id="residente-email"> --- </p>
                                    </div>
                                </div>

                            </div>


                            <div class="ms-auto pageheader-btn">
                                <button type="submit" class="<?php
                                                                if ($data['permisosMod']['c'] == 0 && $data['permisosMod']['u'] == 0) {
                                                                    echo 'disabled';
                                                                } ?> btn btn-danger bg-danger-gradient btn-icon text-white me-2 crear_editar_recibo" id="btnCancelarRecibo" data-animation="fadeIn">
                                    <span>
                                        <i class="fa-regular fa-ban fa-fw"></i>
                                    </span> Cancelar
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>



        </div>

    </div>

</div>
<!-- Fin App Content -->

<!-- Panel footer -->
<!-- <?php //require_once("Views/Template/page-footer.php"); 
        ?> -->
<!--/Panel footer-->

<!-- Sidebar-right -->
<?php //require_once("Views/Template/sidebar-rigth.php"); 
?>
<!--/Sidebar-right-->

<!-- Footer -->
<?php require_once("Views/Template/footer_admin.php"); ?>

<!-- Custom Script Footer -->

<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->