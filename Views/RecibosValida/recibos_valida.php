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
        <div class="page-header mt-2  mb-0">

            <div>
                <h1 class="page-title">Verificación de Recibo</h1>
                <div class="form-group mt-2">
                    <p class="mb-0 fw-semibold fs-18 text-danger">IMPORTANTE: </p>
                    <p class="fs-14 mb-0">Favor de comparar los datos del recibo mostrado a la vista con los datos en pantalla.</p>
                </div>
                <!-- <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=" <?= base_url(); ?>/inicio">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Verificación de Recibo</li>
                </ol> -->
            </div>

        </div>
        <!-- PAGE-HEADER END -->

        <!-- ROW Registro de Tags -->
        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body pt-3">

                        <!-- Lista -->
                        <div class="" id="list_estado_resultados">

                            <!-- Encabezado -->
                            <div class="row">

                                <!-- Opciones de Filtro -->
                                <div class="form-group col-12 m-0">

                                    <div class="row">


                                        <!-- ROW Datos de Residente Seleccionado -->
                                        <div class="row">

                                            <!-- Subtitulos Editar Datos de Recibo-->
                                            <div class="form-group">
                                                <div class="border-bottom-subtitle subtitulos_panel">
                                                    <p class="mb-0 fw-semibold fs-18"><i class="fa-regular fa-file-invoice text-secondary text-secondary-shadow fs-16"></i> Datos de Recibo Verificado</p>
                                                </div>
                                            </div>
                                            <!-- Fin Editar Datos de Recibo -->

                                            <div class="form-group">

                                                <div class="d-flex flex-column justify-content-start align-items-start">

                                                    <div class=" mt-1">
                                                        <label class="fw-semibold fs-16">Residente: </label>
                                                        <h5 class="vistadatos fs-16 text-muted" id="inputFolio_read"><?= $data['recibo']['calle'] . ' ' . $data['recibo']['numero']; ?></h5>
                                                    </div>

                                                    <div class=" mt-1">
                                                        <label class="fw-semibold fs-16">Folio de Recibo: </label>
                                                        <h5 class="vistadatos fs-16 text-muted" id="inputFolio_read"><?= $data['recibo']['folio']; ?></h5>
                                                    </div>

                                                    <div class=" mt-1 ">
                                                        <label class="fw-semibold fs-16">Importe:</label>
                                                        <h5 class="vistadatos fs-16 text-muted" id="inputImporte_read"><?= $data['recibo']['importe']; ?></h5>
                                                    </div>

                                                    <div class=" mt-1 ">
                                                        <label class="fw-semibold fs-16">Estatus:</label>
                                                        <h5 class="vistadatos fs-16 text-muted" id="estatus_read"><?php
                                                                                                                    if ($data['recibo']['estatus'] == 0) {
                                                                                                                        echo '<span class = "badge badge-success">Vigente</span>';
                                                                                                                    } else {
                                                                                                                        echo '<span class = "badge badge-danger">Cancelado</span>';
                                                                                                                    }
                                                                                                                    ?></h5>
                                                    </div>

                                                    <a class="mostrar_mas_menos mt-1 fs-14" style="text-decoration: underline;" data-bs-toggle="collapse" href="#collapseMostrarMas" role="button" aria-expanded="false" aria-controls="collapseRecibos">
                                                        <div id="mostrar_mas" class="">
                                                            <span>Mostrar más</span><i class="ms-1 fa-light fa-angle-down"></i>
                                                        </div>
                                                        <div id="mostrar_menos" class="d-none">
                                                            <span>Mostrar menos</span><i class="ms-1 fa-light fa-angle-up"></i>
                                                        </div>
                                                    </a>

                                                    <div class="collapse" id="collapseMostrarMas">

                                                        <div class=" mt-1 ">
                                                            <label class="fw-semibold fs-16">Concepto:</label>
                                                            <h5 class="vistadatos fs-16 text-muted" id="inputConcepto_read"><?= $data['recibo']['concepto']; ?></h5>
                                                        </div>

                                                        <div class=" mt-1 ">
                                                            <label class="fs-16 fw-semibold">Fecha de Registro:</label>
                                                            <h5 class="vistadatos fs-16 text-muted" id="fechaRegistro_read"><?= $data['recibo']['updated_at']; ?></h5>
                                                        </div>

                                                        <div class=" mt-1 ">
                                                            <label class="fs-16 fw-semibold">Usuario Registró:</label>
                                                            <h5 class="vistadatos fs-16 text-muted" id="usuarioRegistro_read"><?= $data['recibo']['usuario']; ?></h5>
                                                        </div>

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