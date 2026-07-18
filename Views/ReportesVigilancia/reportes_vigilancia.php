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
                    <li class="breadcrumb-item"><a href="#">Administración</a></li>
                    <li class="breadcrumb-item"><a href="#">Seguridad</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $data['page_breadcrumb']; ?></li>
                </ol>
            </div>

            <div class="ms-auto pageheader-btn">
                <btn class="btn btn-secondary bg-warning-gradient  btn-icon text-white me-2 <?= $disabled = ($data['permisosMod']['c']) ? '' : 'disabled'; ?>" id="btnCrear_ReporteVigilancia" data-animation="fadeInDown">
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
                        <div class="" id="list_reportes_vigilancia">

                            <div class="row">

                                <!-- Subtitulos Lista Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-bars-staggered text-secondary text-secondary-shadow fs-16"></i> Lista de Reportes Registrados.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista Registrados -->

                                <div class="form-group col-12  mt-1">

                                    <!-- Tabla de Registros -->
                                    <div class="table-responsive">
                                        <table id="tableReportesVigilancia" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                            <thead class="bg-secondary text-white custom-text-shadow">
                                                <tr>
                                                    <th class="font-weight-bold text-center">Asunto</th>
                                                    <th class="font-weight-bold text-center">Reporte</th>
                                                    <th class="font-weight-bold text-center">Estatus</th>
                                                    <th class="font-weight-bold text-center">Adjunto</th>
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

                        <!-- Crear/Editar Datos formReporteVigilancia -->
                        <div class="" id="crear_editar_reportes_vigilancia" style="display: none;">

                            <div class="row">
                                <!-- Subtitulos Lista de Gastos Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-pencil text-secondary text-secondary-shadow fs-16"></i> Datos a Registrar/Editar.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Gastos Registrados -->
                            </div>

                            <form class="theme-form needs-validation position-relative" id="formReporteVigilancia" novalidate="">

                                <div class="row m-t-10">

                                    <div class="form-group  col-12 col-sm-6 col-xl-4">
                                        <label class="form-label" for="asunto">Asunto:</label>
                                        <input class="form-control btn-square" id="asunto" type="text" name="asunto" placeholder="Ingrese Asunto" required="">
                                        <div class="invalid-feedback">Valor requerido.</div>
                                    </div>

                                    <!-- <div class="form-group  col-12 col-sm-6 col-xl-4">
                                        <label class="form-label" for="contacto">Estatus Reporte:</label>
                                        <select class="select2 mb-3 custom-select selectForm100" name="comboSexo" id="comboSexo" style="width: 100%" required="">
                                            <option value="" selected="selected" disabled>Seleccione una opcion</option>
                                            <option value="1">Abierto</option>
                                            <option value="2">En Seguimiento</option>
                                            <option value="3">Cerrado</option>
                                        </select>
                                        <div class="invalid-feedback">Valor requerido.</div>
                                    </div> -->

                                    <div class="form-group  col-12">
                                        <label class="form-label" for="reporte">Reporte:</label>
                                        <textarea class="form-control btn-square" name="reporte" id="reporte" cols="30" rows="10" placeholder="Ingrese Reporte" required=""></textarea>
                                        <div class="invalid-feedback">Valor requerido.</div>
                                    </div>


                                    <div class="form-group col-12 mb-0 mt-3">
                                        <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                            <input type="hidden" name="reportes_vigilancia_id" id="reportes_vigilancia_id" value="">
                                            <button type="submit" class="btn btn-secondary bg-warning-gradient  btn-guardar-form d-flex justify-content-center align-items-center" id="btnGuardar_ReporteVigilancia">
                                                <div class="<?php
                                                            if ($data['permisosMod']['c'] == 0 && $data['permisosMod']['u'] == 0) {
                                                                echo 'disabled';
                                                            } ?> d-flex justify-content-center align-items-center">
                                                    <i class="fa-regular fa-floppy-disk-pen fa-fw fa-lg me-1"></i>
                                                    <span class="">Guardar</span>
                                                    <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                </div>
                                            </button>
                                            <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center list_htas_roles btnCancelar_Reporte" data-animation="fadeIn">
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
                        <div class="" id="view_reportes_vigilancia" style="display: none;">

                            <div class="row">

                                <!-- Datos de Rol -->
                                <div class="col-12">

                                    <!-- Subtitulos Editar Datos de Rol-->
                                    <div class="form-group col-12 mt-2">
                                        <div class="border-bottom-subtitle subtitulos_panel">
                                            <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-file-lines text-secondary text-secondary-shadow fs-16"></i> Detalle de Reporte Seleccionado.</p>
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

                                        <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center list_reportes_vigilancia btnCancelar_Reporte" data-animation="fadeIn">
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