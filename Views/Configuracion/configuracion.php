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
                <btn class="btn btn-secondary bg-warning-gradient  btn-icon text-white me-2 <?= $disabled = ($data['permisosMod']['c']) ? '' : 'disabled'; ?>" id="btnCrear_Usuario" data-animation="fadeInDown">
                    <span>
                        <i class="fa-regular fa-circle-plus me-1"></i>
                    </span> Nuevo
                </btn>
            </div> -->

        </div>
        <!-- PAGE-HEADER END -->

        <!-- ROW -->
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header bg-light">
                        <h4 class="card-title">
                            <?= $data['page_card_title']; ?>
                        </h4>
                    </div>

                    <div class="card-body">

                        <!-- loading start -->
                        <div id="loading">
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

                        <form id="formConfig" class="validate-form needs-validation" novalidate>

                            <div class="row">

                                <!-- Correo Electronico Remitente  -->
                                <div class="form-group col-12 col-lg-6">
                                    <label class="form-label" for="inputEmailRemitente">Correo Electronico Remitente</label>
                                    <input type="text" class="form-control" name="inputEmailRemitente" id="inputEmailRemitente" placeholder="" required>
                                    <div class="invalid-feedback">Valor requerido.</div>
                                </div>
                                <!-- Fin Correo Electronico Remitente  -->

                                <!-- Correo Electronico Destino Contabilidad  -->
                                <div class="form-group col-12 col-lg-6">
                                    <label class="form-label" for="inputEmailContabilidad">Correo Electronico Destino Contabilidad</label>
                                    <input type="text" class="form-control" name="inputEmailContabilidad" id="inputEmailContabilidad" placeholder="" required>
                                    <div class="invalid-feedback">Valor requerido.</div>
                                </div>
                                <!-- Fin Correo Electronico Destino Contabilidad  -->

                                <div class="form-group col-12 m-0">
                                    <hr>
                                </div>


                                <!-- <div class="col-12"></div> -->

                                <!-- SMTP Host  -->
                                <div class="form-group col-12 col-lg-6">
                                    <label class="form-label" for="inputSMTPHost">SMTP Host</label>
                                    <input type="text" class="form-control" name="inputSMTPHost" id="inputSMTPHost" placeholder="" required>
                                    <div class="invalid-feedback">Valor requerido.</div>
                                </div>
                                <!-- Fin SMTP Host  -->

                                <!-- SMTP Usuario  -->
                                <div class="form-group col-12 col-lg-6">
                                    <label class="form-label" for="inputSMTPUsuario">SMTP Usuario</label>
                                    <input type="text" class="form-control" name="inputSMTPUsuario" id="inputSMTPUsuario" placeholder="" required>
                                    <div class="invalid-feedback">Valor requerido.</div>
                                </div>
                                <!-- Fin SMTP Usuario  -->

                                <!-- SMTP Password  -->
                                <div class="form-group col-12 col-lg-6">
                                    <label class="form-label" for="inputSMTPPassword">SMTP Password</label>
                                    <input type="text" class="form-control" name="inputSMTPPassword" id="inputSMTPPassword" placeholder="*******" required>
                                    <div class="invalid-feedback">Valor requerido.</div>
                                </div>
                                <!-- Fin SMTP Password  -->

                                <!-- SMTP Puerto  -->
                                <div class="form-group col-12 col-lg-6">
                                    <label class="form-label" for="inputSMTPPuerto">SMTP Puerto</label>
                                    <input type="text" class="form-control" name="inputSMTPPuerto" id="inputSMTPPuerto" placeholder="" required>
                                    <div class="invalid-feedback">Valor requerido.</div>
                                </div>
                                <!-- Fin SMTP Puerto  -->


                            </div>

                            <button class="btn btn-secondary bg-warning-gradient  mt-4 mb-0 <?= $disabled = ($data['permisosMod']['u']) ? '' : 'disabled'; ?>" type="submit" id="btnGuardarConfig">
                                <i class="fa-regular fa-floppy-disk"></i> Guardar
                            </button>

                        </form>
                    </div>
                </div>
            </div>
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