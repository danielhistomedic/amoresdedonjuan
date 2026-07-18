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
                    <li class="breadcrumb-item"><a href="#">Ingresos/Egresos</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $data['page_breadcrumb']; ?></li>
                </ol>
            </div>

            <div class="ms-auto pageheader-btn">
                <btn class="btn btn-secondary bg-warning-gradient  btn-icon text-white me-2 crear_editar_gastos <?= $disabled = ($data['permisosMod']['c']) ? '' : 'disabled'; ?>" id="btnNuevoGasto" data-animation="fadeInDown">
                    <span>
                        <i class="fa-regular fa-circle-plus me-1"></i>
                    </span> Nuevo
                </btn>
            </div>

        </div>
        <!-- PAGE-HEADER END -->


        <!-- ROW Registro de Tags -->
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
                        <div class="" id="list_gastos">

                            <div class="row">

                                <!-- Subtitulos Lista de Gastos Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-bars-staggered text-secondary text-secondary-shadow fs-16"></i> Lista de Gastos Registrados.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Gastos Registrados -->

                                <div class="form-group col-12  mt-1">

                                    <!-- Tabla de Registros -->
                                    <div class="table-responsive">
                                        <table id="tableGastos" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                            <thead class="bg-secondary text-white custom-text-shadow">
                                                <tr>

                                                    <th class="font-weight-bold text-center">Fecha de Pago</th>
                                                    <th class="font-weight-bold text-center">Clasificación</th>
                                                    <th class="font-weight-bold text-center">Proveedor</th>
                                                    <th class="font-weight-bold text-center">Fecha de Nota/Factura</th>
                                                    <th class="font-weight-bold text-center">Folio</th>
                                                    <th class="font-weight-bold text-center">Descripción</th>
                                                    <th class="font-weight-bold text-center">Importe</th>
                                                    <th class="font-weight-bold text-center">Archivo</th>
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
                        <div class="" id="crear_editar_gastos" style="display: none;">

                            <div class="row">
                                <!-- Subtitulos Lista de Gastos Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-pencil text-secondary text-secondary-shadow fs-16"></i> Registrar Nuevo Gasto.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Gastos Registrados -->
                            </div>

                            <div class="form-group col-12 mt-1">
                                <div class="alert alert-warning" role="alert">
                                    <div>
                                        <span class="alert-inner--icon"><i class="fa-light fa-bell tx-18"></i></span>
                                        <span class="alert-inner--text tx-12 ms-1">Para mayor transaparencia del proceso de Gastos, estos deberán registrarse el mismo día de la compra y
                                            deberá adjuntar el recibo correspondiente.</span>
                                    </div>
                                </div>
                            </div>

                            <form name="formGastos" id="formGastos">

                                <div class="row">

                                    <!-- Fecha de Pago -->
                                    <!-- <div class="form-group col-12 col-sm-6 col-xl-2">
                                        <label class="form-label" for="inputGastoFechaPago">Fecha de Pago:</label>
                                        <input type="text" class="form-control inputForm100 inputDateMask" data-toggle="datepicker" name="inputGastoFechaPago" id="inputGastoFechaPago" placeholder="dd/mm/aaaa" required>
                                    </div> -->
                                    <!-- Fin Fecha de Pago -->

                                    <!-- Clasificación -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4">
                                        <label class="form-label" for="comboClasificacionGastos">Clasificación:</label>
                                        <div id="slWrapper" class="parsley-select">
                                            <select class="select2 mb-3 custom-select selectForm100" name="comboClasificacionGastos" id="comboClasificacionGastos" style="width: 100%" required>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Fin Clasificación -->

                                    <!-- Proveedor -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4">
                                        <label for="inputGastoProveedor" class="form-label">Nombre del Proveedor:</label>
                                        <input type="text" class="form-control inputForm100" name="inputGastoProveedor" id="inputGastoProveedor" placeholder="Ingrese Nombre del Proveedor" required>
                                    </div>
                                    <!-- Fin Proveedor -->

                                    <!-- Fecha de Nota/Factura -->
                                    <div class="form-group col-12 col-sm-6 col-xl-2">
                                        <label class="form-label" for="inputGastoFechaNota">Fecha de Nota/Factura:</label>
                                        <input type="text" class="form-control inputForm100 inputDateMask" data-toggle="datepicker" name="inputGastoFechaNota" id="inputGastoFechaNota" placeholder="dd/mm/aaaa" required>
                                    </div>
                                    <!-- Fin Fecha de Nota/Factura -->


                                    <!-- Folio Factura/Nota -->
                                    <div class="form-group col-12 col-sm-6 col-xl-2">
                                        <label for="inputGastoFolioNota" class="form-label">Folio:</label>
                                        <input type="text" class="form-control inputForm100" name="inputGastoFolioNota" id="inputGastoFolioNota" placeholder="Ingrese Folio de Factura/Nota" required>
                                    </div>
                                    <!-- Fin Folio Factura/Nota -->

                                    <!-- Descrición del Gasto -->
                                    <div class="form-group col-12 col-sm-6 col-xl-8">
                                        <label for="inputGastoDescripcion" class="form-label">Finalidad del Gasto:</label>
                                        <input type="text" class="form-control inputForm100" name="inputGastoDescripcion" id="inputGastoDescripcion" placeholder="Ingrese Finalidad de Gasto" required>
                                    </div>
                                    <!-- Fin Descrición del Gasto -->

                                    <!-- Importe -->
                                    <div class="form-group col-12 col-sm-6 col-xl-2">
                                        <label for="inputGastoImporte" class="form-label">Importe:</label>
                                        <input type="text" class="form-control " name="inputGastoImporte" id="inputGastoImporte" placeholder="Ingrese Importe" required>
                                    </div>
                                    <!-- Fin Importe -->

                                    <!-- Archivo -->
                                    <div class="form-group col-12 col-sm-6 col-xl-6" id="gastos_adjunto">
                                        <label class="form-label" for="inputGastosArchivo">Seleccione Archivo:</label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" name="inputGastosArchivo" id="inputGastosArchivo" placeholder="Seleccione Archivo" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <!-- Fin Archivo -->


                                    <div class="form-group col-12 mb-0 mt-3">

                                        <input type="hidden" class="" name="inputGastoId" id="inputGastoId" value="">

                                        <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">

                                            <button type="submit" class="<?php
                                                                            if ($data['permisosMod']['c'] == 0 && $data['permisosMod']['u'] == 0) {
                                                                                echo 'disabled';
                                                                            } ?> btn btn-secondary bg-warning-gradient  btn-guardar-form d-flex justify-content-center align-items-center" id="btnGuardarGasto">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <i class="fa-regular fa-floppy-disk-pen fa-fw fa-lg me-1"></i>
                                                    <span class="">Guardar</span>
                                                    <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                </div>
                                            </button>

                                            <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center list_gastos btnRegresarLista_Gasto" data-animation="fadeIn">
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
                        <div class="" id="view_gastos" style="display: none;">

                            <div class="row">

                                <!-- Subtitulos Lista de Gastos Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-file-lines text-secondary text-secondary-shadow fs-16"></i> Detalle de Gasto Seleccionado.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Gastos Registrados -->

                                <!-- Datos de Registro -->
                                <div class="col-12">

                                    <div class="form-group mt-2">

                                        <div class="d-flex flex-column justify-content-start align-items-start">

                                            <div class="">
                                                <label class="fs-13">Fecha Pagado: </label>
                                                <h5 class="vistadatos fs-13 text-secondary fw-semibold" id="lblGasto_FechaPagado"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="">
                                                <label class="fs-13">Clasificacion: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="lblGasto_Clasificacion"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="">
                                                <label class="fs-13">Proveedor: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="lblGasto_Proveedor"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="">
                                                <label class="fs-13">Fecha Nota/Factura: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="lblGasto_FechaNota"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="">
                                                <label class="fs-13">Folio: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="lblGasto_FolioNota"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="">
                                                <label class="fs-13">Descripción: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="lblGasto_Descripcion"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="">
                                                <label class="fs-13">Importe: </label>
                                                <h5 class="vistadatos fs-13 text-secondary fw-semibold" id="lblGasto_Importe"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="">
                                                <label class="fs-13">Adjunto: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="lblGasto_Adjunto"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <a class="mostrar_mas_menos mt-3" style="text-decoration: underline;" data-bs-toggle="collapse" href="#collapseMostrarMas" role="button" aria-expanded="false" aria-controls="collapseMasMenos">
                                                <div id="mostrar_mas" class="">
                                                    <span>Mostrar más</span><i class="ms-1 fa-light fa-angle-down"></i>
                                                </div>
                                                <div id="mostrar_menos" class="d-none">
                                                    <span>Mostrar menos</span><i class="ms-1 fa-light fa-angle-up"></i>
                                                </div>
                                            </a>

                                            <div class="collapse" id="collapseMostrarMas">

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
                                        <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form-only d-flex justify-content-center align-items-center list_gastos btnRegresarLista_Gasto" data-animation="fadeIn">
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