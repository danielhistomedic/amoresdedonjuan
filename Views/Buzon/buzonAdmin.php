<!-- Header Admin -->
<?php require_once("Views/Template/header_admin.php"); ?>

<!-- Custom Css/Script -->

<!-- Fin Custom Css/Script -->

<?php require_once("Views/Template/header_admin_end.php"); ?>
<!-- Fin Header Admin -->

<!-- Panel Head -->
<?php require_once("Views/Template/page-head.php"); ?>
<!-- Fin Panel Head -->





<!-- CONTENIDO PRINCIPAL -->
<!-- app-content open -->
<div class="app-content hor-content">
    <div class="container">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

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

            </div>


            <!-- ROW-1 OPEN -->
            <div class="row">


                <div class="col-lg-12 col-xl-6 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header bg-header-card">
                            <h4 class="card-title"> <?= $data['page_card_title']; ?></h4>
                        </div>
                        <div class="card-body">

                            <!-- Preloader -->
                            <div id="loading-resumen">
                                <!-- Cargar Loader con CSS -->
                                <div class="lds-double-ring">
                                    <div></div>
                                    <div></div>
                                </div>
                                <!-- Cargar Loader con SVG -->
                                <!--   <img src="<?= media(); ?>/img/loading.svg" alt="Loading"> -->
                            </div>
                            <!-- Fin Preloader -->

                            <form id="formEnviarBuzon" class="validate-form needs-validation" novalidate>

                                <h3 class="card-title mb-0 text-danger">Asunto: <?= $data['buzon']['asunto']; ?></h3>
                                <hr>

                                <input type="hidden" class="" name="buzon_id" id="buzon_id" value="<?= $data['buzon_id']; ?>">
                                <input type="hidden" class="" name="residente_id" id="residente_id" value="<?= $data['residente']['id']; ?>">

                                <div class="form-group">
                                    <label class="form-label">Estatus Buzón:</label>
                                    <select class="form-control form-select select2" name="comboEstatusBuzon" id="comboEstatusBuzon" data-bs-placeholder="Seleccione una opción" style="width: 100%" required>
                                        <option value="" selected="selected" disabled>Seleccione una opcion</option>
                                        <option value="1">EN SEGUIMIENTO</option>
                                        <option value="2">CERRADO</option>
                                    </select>
                                    <div class="invalid-feedback">Valor requerido.</div>
                                </div>

                                <div class="form-group">
                                    <label for="mensaje" class="form-label">Mensaje de Respuesta:</label>
                                    <textarea class="form-control" placeholder="Mensaje" id="mensaje" name="mensaje" rows="4" required></textarea>
                                    <div class="invalid-feedback">Valor requerido.</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label mt-0">Adjuntar Imagen</label>
                                    <input class="form-control" type="file" accept="image/*" name="adjunto" id="adjunto">
                                </div>

                                <div class="form-group">
                                    <button type="submit" id="btnGuardarBuzon" class="btn btn-secondary bg-warning-gradient  mt-4 mb-0"><i class="fa-regular fa-paper-plane"></i> Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
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