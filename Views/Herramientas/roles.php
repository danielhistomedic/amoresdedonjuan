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

            <div class="ms-auto pageheader-btn">
                <btn class="btn btn-secondary bg-warning-gradient  btn-icon text-white me-2 <?= $disabled = ($data['permisosMod']['c']) ? '' : 'disabled'; ?>" id="btnCrear_Rol" data-animation="fadeInDown">
                    <span>
                        <i class="fa-regular fa-circle-plus me-1"></i>
                    </span> Nuevo
                </btn>
            </div>

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

                        <!-- Lista -->
                        <div class="" id="list_htas_roles">

                            <div class="row">

                                <!-- Subtitulos Lista de Roles Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom subtitulos_panel">
                                        <p class="mb-0"><i class="fa-regular fa-bars-staggered text-secondary text-secondary-shadow tx-15"></i> Lista de Roles Registrados.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Roles Registrados -->

                                <div class="form-group col-12  mt-1">

                                    <!-- Tabla de Registros -->
                                    <div class="table-responsive">
                                        <table id="tableRoles" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                            <thead class="bg-secondary text-white custom-text-shadow">
                                                <tr>
                                                    <th class="font-weight-bold text-center">Rol</th>
                                                    <th class="font-weight-bold text-center">Descripcion</th>
                                                    <th class="font-weight-bold text-center">Estatus</th>
                                                    <th class="font-weight-bold text-center">Opciones</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <!-- Fin Tabla de Registros -->

                                </div>

                            </div>

                        </div>
                        <!-- Fin Lista -->

                        <!-- Crear/Editar Datos -->
                        <div class="" id="crear_editar_htas_roles" style="display: none;">
                            <form name="formRol" id="formRol" action="">
                                <div class="row flex-column">

                                    <!-- Subtitulos Editar Datos -->
                                    <div class="form-group col-12 mt-1">
                                        <div class="border-bottom subtitulos_panel">
                                            <p class="mb-0"><i class="fa-regular fa-pencil text-secondary text-secondary-shadow tx-15"></i> Datos a Registrar/Editar.</p>
                                        </div>
                                    </div>
                                    <!-- Fin Editar Datos -->

                                    <!-- Nombre -->
                                    <div class="form-group col-12 col-lg-4">
                                        <label class="login-label" for="inputNombreRol">Nombre del Rol:</label>
                                        <input type="text" class="form-control inputForm100" name="inputNombreRol" id="inputNombreRol" placeholder="Ingrese Nombre de Rol" autocomplete="off" data-parsley-required>
                                    </div><!-- form-group -->
                                    <!-- Fin Nombre -->

                                    <!-- Descripción -->
                                    <div class="form-group col-12">
                                        <label class="login-label" for="inputDescripcionRol">Descripción del Rol:</label>
                                        <input type="text" class="form-control inputForm100" maxlength="255" name="inputDescripcionRol" id="inputDescripcionRol" placeholder="Ingrese Descripción de Rol" autocomplete="off" data-parsley-required>
                                    </div><!-- form-group -->
                                    <!-- Fin Descripción -->

                                    <div class="form-group col-12 mb-0 mt-3">
                                        <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                            <input type="hidden" name="inputIdRol" id="inputIdRol" value="">
                                            <button type="submit" class="btn btn-secondary bg-warning-gradient  btn-guardar-form d-flex justify-content-center align-items-center" id="btnGuardar_Rol">
                                                <div class="<?php
                                                            if ($data['permisosMod']['c'] == 0 && $data['permisosMod']['u'] == 0) {
                                                                echo 'disabled';
                                                            } ?> d-flex justify-content-center align-items-center">
                                                    <i class="fa-regular fa-floppy-disk-pen fa-fw fa-lg me-1"></i>
                                                    <span class="">Guardar</span>
                                                    <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                </div>
                                            </button>
                                            <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center list_htas_roles btnCancelar_Rol" data-animation="fadeIn">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <i class="fa-regular fa-rotate-left fa-fw fa-lg me-1"></i>
                                                    <span class="">Regresar a Listado</span>
                                                    <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Fin Editar Datos -->

                        <!-- Vista de Datos -->
                        <div class="" id="view_htas_roles" style="display: none;">

                            <div class="row">

                                <!-- Datos de Rol -->
                                <div class="col-12">

                                    <!-- Subtitulos Editar Datos de Rol-->
                                    <div class="form-group mt-1">
                                        <div class="border-bottom subtitulos_panel">
                                            <p class="mb-0"><i class="fa-regular fa-magnifying-glass text-secondary text-secondary-shadow tx-15"></i> Datos de Rol.</p>
                                        </div>
                                    </div>
                                    <!-- Fin Editar Datos de Rol -->

                                    <div class="form-group mt-2">

                                        <div class="d-flex flex-column justify-content-start align-items-start">

                                            <div class="">
                                                <label class="fs-13">Nombre del Rol: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputNombreRol_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class=" mt-3 ">
                                                <label class="fs-13">Descripción del Rol:</label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputDescripcionRol_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <a class="mostrar_mas_menos_rol mt-3" style="text-decoration: underline;" data-bs-toggle="collapse" href="#collapseMostrarMasVistaRol" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <div id="mostrar_mas_rol" class="">
                                                    <span>Mostrar más</span><i class="ms-1 fa-light fa-angle-down"></i>
                                                </div>
                                                <div id="mostrar_menos_rol" class="d-none">
                                                    <span>Mostrar menos</span><i class="ms-1 fa-light fa-angle-up"></i>
                                                </div>
                                            </a>

                                            <div class="collapse" id="collapseMostrarMasVistaRol">

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Estatus:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="estatus_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Fecha de Registro:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="fechaRegistro_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Usuario Registró:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="usuarioRegistro_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <!-- Fin Datos Generales y de Contacto -->

                                <div class="form-group col-12 mb-0">
                                    <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                        <button type="button" class="btn btn-secondary bg-warning-gradient  btn-editar-form d-flex justify-content-center align-items-center crear_editar_htas_roles <?= $disabled = ($data['permisosMod']['u']) ? '' : 'disabled'; ?>" id="btnEditar_Rol" data-animation="fadeIn">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="fa-regular fa-pen-to-square fa-fw fa-lg me-1"></i>
                                                <span class="">Editar</span>
                                                <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                            </div>
                                        </button>
                                        <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center list_htas_roles btnCancelar_Rol" data-animation="fadeIn">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="fa-regular fa-rotate-left fa-fw fa-lg me-1"></i>
                                                <span class="">Regresar a Listado</span>
                                                <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Fin Vista de Datos -->

                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- Fin App Content -->

<!-- Modals de Formulario -->
<div id="loadModalPermisos">

</div>
<!-- Fin Modals de Formulario -->

<!-- Footer -->
<?php require_once("Views/Template/footer_admin.php"); ?>

<!-- Custom Script Footer -->

<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->