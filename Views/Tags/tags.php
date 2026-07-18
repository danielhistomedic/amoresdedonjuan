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
                    <li class="breadcrumb-item"><a href="<?= base_url(); ?>/inicio">Administración</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url(); ?>/inicio">Seguridad</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $data['page_breadcrumb']; ?></li>
                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->


        <!-- ROW  Buscar Residente -->
        <?php require_once("Views/Template/buscar_residente.php"); ?>


        <!-- ROW Datos de Residente Seleccionado -->
        <div class="row">

            <div class="col-12">
                <div class="card">


                    <div class="card-header bg-light">
                        <h4 class="card-title w-100 d-flex">
                            <div> <i class="fa-regular fa-house-chimney-user text-info text-info-shadow fa-lg me-1"></i> Datos de Residente Seleccionado</div>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fa-regular fa-angle-up"></i></a>
                            </div>
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-12 col-sm-6">
                                <div class="d-flex fs-14">
                                    <p class="me-1 text-info"><i class="fa-light fa-user-check"></i> <strong>Nombre:</strong> </p>
                                    <p id="residente-nombre"> --- </p>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="d-flex fs-14">
                                    <p class="me-1 text-info"><i class="fa-light fa-house"></i> <strong>Domicilio:</strong> </p>
                                    <p id="residente-domicilio"> --- </p>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="d-flex fs-14">
                                    <p class="me-1 text-info"><i class="fa-light fa-at"></i> <strong>Email:</strong> </p>
                                    <p id="residente-email"> --- </p>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="d-flex fs-14">
                                    <p class="me-1 text-info"><i class="fa-light fa-mobile"></i> <strong>Telefono:</strong> </p>
                                    <p id="residente-telefono"> --- </p>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="page-header mt-0">

            <div class="ms-auto pageheader-btn">
                <btn class="btn btn-info bg-info-gradient btn-icon text-white me-2 crear_editar_tag <?= $disabled = ($data['permisosMod']['c']) ? '' : 'disabled'; ?>" id="btnNuevaTag" data-animation="fadeInDown">
                    <span>
                        <i class="fa-regular fa-circle-plus me-1"></i>
                    </span> Nuevo
                </btn>
            </div>
        </div>


        <!-- ROW Registro de Tags -->
        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header bg-light">
                        <h4 class="card-title">
                            <i class="fa-regular fa-tags text-info text-info-shadow fa-lg me-1"></i> <?= $data['page_card_title']; ?>
                        </h4>
                    </div>

                    <div class="card-body">

                        <!-- Lista -->
                        <div class="" id="list_tag">

                            <div class="row">

                                <!-- Subtitulos Lista de Comprobantes Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-bars-staggered text-secondary text-secondary-shadow fs-16"></i> Lista de Tags Registradas.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Comprobantes Registrados -->

                                <div class="form-group col-12  mt-1">

                                    <!-- Tabla de Registros -->
                                    <div class="table-responsive">
                                        <table id="tableTags" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                            <thead class="bg-info text-white custom-text-shadow">
                                                <tr>
                                                    <th class="font-weight-bold text-center">Fecha de Registro</th>
                                                    <th class="font-weight-bold text-center">Usuario Registro Originalmente</th>
                                                    <th class="font-weight-bold text-center">Numero de Tag</th>
                                                    <th class="font-weight-bold text-center">Nombre</th>
                                                    <th class="font-weight-bold text-center">Calle</th>
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
                        <div class="" id="crear_editar_tag" style="display: none;">

                            <form name="formVigilanciaTag" id="formVigilanciaTag">

                                <div class="row">

                                    <!-- Subtitulos Editar Datos -->
                                    <div class="form-group col-12 mt-2">
                                        <div class="border-bottom-subtitle subtitulos_panel">
                                            <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-pencil text-secondary text-secondary-shadow fs-16"></i> Datos a Registrar/Editar.</p>
                                        </div>
                                    </div>
                                    <!-- Fin Editar Datos -->

                                    <div class="form-group col-12 col-sm-4">
                                        <label for="res_tag" class="mt-0 form-label inputForm100">Numero de Tag:</label>
                                        <input type="number" class="form-control " name="res_tag" id="res_tag" placeholder="Ingrese Numero de Tag">
                                    </div>

                                    <div class="form-group col-12 mb-0 mt-3">

                                        <input type="hidden" class="" name="residente_id" id="residente_id" value="">
                                        <input type="hidden" class="" name="tag_id" id="tag_id" value="">

                                        <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">

                                            <button type="submit" class="<?php
                                                                            if ($data['permisosMod']['c'] == 0 && $data['permisosMod']['u'] == 0) {
                                                                                echo 'disabled';
                                                                            } ?> btn btn-info bg-info-gradient btn-guardar-form d-flex justify-content-center align-items-center" id="btnGuardarTag">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <i class="fa-regular fa-floppy-disk-pen fa-fw fa-lg me-1"></i>
                                                    <span class="">Guardar</span>
                                                    <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                </div>
                                            </button>

                                            <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center list_tag btnCancelar_Tag" data-animation="fadeIn">
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
                        <div class="" id="view_tag" style="display: none;">

                            <div class="row">

                                <!-- Subtitulos Editar Datos de Recibo-->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-magnifying-glass text-secondary text-secondary-shadow fs-16"></i> Datos de Registro Seleccionado.</p>
                                    </div>
                                </div>
                                <!-- Fin Editar Datos de Recibo -->

                                <!-- Datos de Recibo -->
                                <div class="col-12">

                                    <div class="form-group mt-2">

                                        <div class="d-flex flex-column justify-content-start align-items-start">

                                            <div class="">
                                                <label class="fs-13">Numero de Tag: </label>
                                                <h5 class="vistadatos fs-13 text-info fw-semibold" id="inputTag_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class=" mt-3 ">
                                                <label class="fs-13">Nombre:</label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputNombre_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class=" mt-3 ">
                                                <label class="fs-13">Domicilio:</label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputDomicilio_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <a class="mostrar_mas_menos mt-3" style="text-decoration: underline;" data-bs-toggle="collapse" href="#collapseMostrarMas" role="button" aria-expanded="false" aria-controls="collapseTags">
                                                <div id="mostrar_mas" class="">
                                                    <span>Mostrar más</span><i class="ms-1 fa-light fa-angle-down"></i>
                                                </div>
                                                <div id="mostrar_menos" class="d-none">
                                                    <span>Mostrar menos</span><i class="ms-1 fa-light fa-angle-up"></i>
                                                </div>
                                            </a>


                                            <div class="collapse" id="collapseMostrarMas">

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Estatus:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="estatus_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Fecha Vigencia:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="inputFechaVigencia_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Fecha Actualiza:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="fechaRegistro_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Usuario Actualiza:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="usuarioRegistro_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <!-- Fin Datos Generales y de Contacto -->

                                <div class="form-group col-12 mb-0">
                                    <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                        <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form-only d-flex justify-content-center align-items-center list_recibo btnCancelar_Tag" data-animation="fadeIn">
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