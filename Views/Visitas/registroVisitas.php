<!-- Header Admin -->
<?php require_once("Views/Template/header_admin.php"); ?>

<!-- Custom Css/Script -->
<style>
#qr-reader-container {
    max-width: 500px;
    margin: 15px auto;
    border: 2px dashed #0088cc;
    border-radius: 12px;
    padding: 10px;
    background: #fff;
}
</style>
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

            <div class="form-group mt-4">
                <a role="button" class="btn btn-info bg-info-gradient" href="<?= base_url(); ?>/estatusResidente"><i class="fa-solid fa-turn-down-left"></i> Regresar a Estatus de Residente</a>
            </div>

            <div class="form-group">
                <label for="folio" class="form-label mb-0 p-0 fs-18">Folio Acceso</label>
                <label for="folio" class="form-label mt-0 fs-12 pt-0 text-info">(Solo en caso de que el códgo QR no pueda ser leído)</label>
                <input type="text" class="form-control" name="folio" id="folio" placeholder="Folio de acceso compartido" required>
            </div>

            <div class="form-group">
                <button id="btnRegistrarVisita" type="button" class="<?php
                                                                        if ($data['permisosMod']['c'] == 0 && $data['permisosMod']['u'] == 0) {
                                                                            echo 'disabled';
                                                                        } ?> btn btn-secondary bg-warning-gradient "><i class="fa-regular fa-user-check"></i> Registrar Visita</button>
                <button id="btnToggleQR" type="button" class="btn btn-info bg-info-gradient ms-2"><i class="fa-solid fa-camera"></i> Cámara QR</button>
            </div>

            <!-- Lector QR (cámara) -->
            <div class="form-group col-12">
                <div id="qr-reader-container" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                        <span class="fw-semibold text-secondary"><i class="fa-solid fa-video me-1"></i> Lector Activo</span>
                        <button class="btn btn-danger btn-sm rounded-pill px-3" id="btnStopQR">
                            <i class="fa-solid fa-stop me-1"></i> Detener
                        </button>
                    </div>
                    <div id="qr-reader"></div>
                </div>
            </div>

        </div>

    </div>

</div>
<!-- Fin App Content -->


<!-- Footer -->
<?php require_once("Views/Template/footer_admin.php"); ?>

<!-- Custom Script Footer -->
<script type="text/javascript" src="<?= media(); ?>/js/functions/app.js?v=<?= version(); ?>"></script>
<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->