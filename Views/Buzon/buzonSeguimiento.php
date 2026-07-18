<!-- Header Admin -->
<?php require_once("Views/Template/header_admin.php"); ?>

<!-- Custom Css/Script -->

<!-- Fin Custom Css/Script -->

<?php require_once("Views/Template/header_admin_end.php"); ?>
<!-- Fin Header Admin -->

<!-- Panel Head -->
<?php require_once("Views/Template/page-head.php"); ?>
<!-- Fin Panel Head -->



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

        <!-- ROW Datos de Residente Seleccionado -->
        <div class="row">

            <input type="hidden" class="" name="residente_id" id="residente_id" value="<?= $data['residente']['id']; ?>">

            <div class="col-12 col-md-9 col-lg-8 col-xl-6">

                <div class="card">
                    <div class="card-body">
                        <div class="card-order">
                            <h6 class="mb-2 fs-18 fw-bold">Datos de Residente que envió el Buzón.</h6>
                            <p class="mb-0 fs-14 fw-semibold"><i class="fa-light fa-fw fa-user"></i> Nombre: <span class="float-left text-seconadry"><?= $data['residente']['nombre']; ?> </span></p>
                            <p class="mb-0 fs-14 fw-semibold"><i class="fa-light fa-fw fa-house"></i> Calle: <span class="float-left text-seconadry"><?= $data['residente']['calle']; ?> </span></p>
                            <p class="mb-0 fs-14 fw-semibold"><i class="fa-light fa-fw fa-house"></i> Numero: <span class="float-left text-seconadry"><?= $data['residente']['numero']; ?> </span></p>
                            <p class="mb-0 fs-14 fw-semibold"><i class="fa-light fa-fw fa-mobile"></i> Telefono: <span class="float-left text-seconadry"><?= $data['residente']['telefono']; ?> </span></p>
                            <p class="mb-0 fs-14 fw-semibold"><i class="fa-light fa-fw fa-at"></i> Email: <span class="float-left text-seconadry"><?= $data['residente']['email']; ?> </span></p>
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-12">
                <div class="page-header mt-0">

                    <div class="float-left pageheader-btn">
                        <a href="<?= base_url(); ?>/buzon/buzonAdmin/<?= $data['buzon_id']; ?>/<?= $data['residente']['id']; ?>" role="button" class="btn btn-warning bg-warning-gradient btn-icon text-white me-2 crear_editar_tag <?= $disabled = ($data['permisosMod']['c']) ? '' : 'disabled'; ?>" id="btnNuevoBuzon" data-animation="fadeInDown">
                            <span>
                                <i class="fa-regular fa-reply me-1"></i>
                            </span> Responder Buzón
                        </a>
                    </div>
                </div>
            </div>



            <div class="col-12 col-md-9 col-lg-8 col-xl-6">

                <!-- ROW-1 OPEN -->
                <input type="hidden" class="" name="buzon_id" id="buzon_id" value="<?= $data['buzon_id']; ?>">

                <h3 class="card-title mb-0"><span>Asunto: </span> <?= $data['buzon']['asunto']; ?></h3>

                <hr class="mt-1">

                <div class="mt-5" id="buzon_seguimiento">

                </div>

            </div>


        </div>


    </div>
    <!-- CONTAINER CLOSED -->
</div>
<!-- CONTENIDO PRINCIPAL END -->



<!-- Footer -->
<?php require_once("Views/Template/footer_admin.php"); ?>

<!-- Custom Script Footer -->

<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->