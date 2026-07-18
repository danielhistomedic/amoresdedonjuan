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
                <h1 class="page-title">Control de Acceso de Visitantes con codigo QR</h1>
            </div>

        </div>
        <!-- PAGE-HEADER END -->

        <!-- ROW Registro de Tags -->
        <div class="row">

            <?php
            $acceso_caducado = "";
            $acceso_existoso = "";
            if ($data['visita']['result'] == true) {
                $acceso_caducado = "d-none";
                $acceso_existoso = "";
            } else {
                $acceso_caducado = "";
                $acceso_existoso = "d-none";
            } ?>

            <div class="form-group mt-4">
                <a role="button" class="btn btn-info bg-info-gradient" href="<?= base_url(); ?>/estatusResidente"><i class="fa-solid fa-turn-down-left"></i> Regresar a Estatus de Residente</a>
            </div>

            <div class="form-group col-12 <?= $acceso_existoso; ?>">
                <div class="alert alert-success" role="alert">
                    <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                    <span class="alert-inner--text"><strong>¡<?= $data['visita']['mensaje'] ?>!</strong> Registro realizado exitosamente.</span>
                </div>
                <p class="mb-0 fw-bold fs-18 text-danger">IMPORTANTE: </p>
                <p class="fs-14 mb-0">No olvide solicitar la identificación del visitante, y entregarle su tarjeta de visitante.</p>
            </div>


            <div class="form-group col-12 <?= $acceso_caducado; ?>">
                <div class="alert alert-danger mb-0" role="alert">
                    <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                    <span class="alert-inner--text"><strong> ¡<?= $data['visita']['mensaje'] ?>!</strong> Indique al visitante que solicite un nuevo codigo.</span>
                </div>
            </div>

            <div class="form-group col-12 <?= $acceso_existoso; ?>">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-user-check"></i> Datos del Visitante para verificar.</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table class="table row table-borderless m-0">
                                <tbody class="col-12 p-0">
                                    <tr>
                                        <td class="fs-18">
                                            <div class="d-flex flex-column mb-4">
                                                <span class="text-primary"><strong>Nombre del Visitante:</strong></span>
                                                <span><?= $data['visita']['nombre']; ?></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fs-18">
                                            <div class="d-flex flex-column">
                                                <span class="text-primary"><strong>Comentarios Adicionales del Residente: </strong></span>
                                                <span><?= $data['visita']['comentarios_adicionales']; ?></span>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-user-check"></i> Residente que visita.</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table class="table row table-borderless m-0">
                                <tbody class="col-12 p-0">
                                    <tr>
                                        <td class="fs-18">
                                            <div class="d-flex flex-column mb-4">
                                                <span class="text-secondary"><strong>Calle:</strong></span>
                                                <span><?= $data['visita']['calle']; ?></span>
                                            </div>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fs-18">
                                            <div class="d-flex flex-column">
                                                <span class="text-secondary"><strong>Numero: </strong></span>
                                                <span><?= $data['visita']['numero']; ?></span>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
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