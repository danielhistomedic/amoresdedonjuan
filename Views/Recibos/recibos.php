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
        </div>
        <!-- PAGE-HEADER END -->

        <!-- ROW Buscar Residente -->
        <?php require_once("Views/Template/buscar_residente.php"); ?>

        <!-- ROW Datos de Residente Seleccionado -->
        <div class="row">
            <div class="col-12">
                <div class="card">


                    <div class="card-header bg-light">
                        <h4 class="card-title w-100 d-flex">
                            <div> <i class="fa-regular fa-house-chimney-user text-secondary text-secondary-shadow fa-lg me-1"></i> Datos de Residente Seleccionado</div>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fa-regular fa-angle-up"></i></a>
                            </div>
                        </h4>
                    </div>

                    <div class="card-body">

                        <form name="formResidente" id="formResidente">

                            <div class="row">

                                <div class="form-group col-12 col-sm-6">
                                    <label for="res_nombre" class="form-label">Nombre Residente:</label>
                                    <input type="text" class="form-control inputForm100_residente" name="res_nombre" id="res_nombre" placeholder="Ingrese Nombre" requiered>
                                </div>
                                <div class="form-group col-12 col-sm-3">
                                    <label for="res_calle" class="form-label">Calle:</label>
                                    <input type="text" class="form-control inputForm100_residente" name="res_calle" id="res_calle" placeholder="" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-3">
                                    <label for="res_numero" class="form-label">Numero de Calle:</label>
                                    <input type="text" class="form-control inputForm100_residente" name="res_numero" id="res_numero" placeholder="" readonly>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label for="res_email" class="form-label">Email:</label>
                                    <input type="email" class="form-control " name="res_email" id="res_email" placeholder="Ingrese Email">
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label for="res_telefono" class="form-label">Telefono:</label>
                                    <input type="text" class="form-control " name="res_telefono" id="res_telefono" placeholder="Ingrese Telefono">
                                </div>
                                <div class="form-group col-12">
                                    <label for="res_indicaciones_generales_visitas" class="form-label">Inidcaciones Generales para Vistantes:</label>
                                    <input type="text" class="form-control " name="res_indicaciones_generales_visitas" id="res_indicaciones_generales_visitas" placeholder="Ingrese Indicaciones">
                                </div>

                                <input type="hidden" class="" name="residente_id" id="residente_id" value="">

                                <div class="form-group col d-flex botones_base_recibo">

                                    <button type="submit" class="login100-form-btn btn btn-secondary bg-warning-gradient  d-flex justify-content-center align-items-center" id="btnActualizarResidente">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <i class="fa-regular fa-floppy-disk-circle-arrow-right fa-fw fa-lg me-1"></i>
                                            <span class="">Actualizar Datos</span>
                                            <i class="far fa-spinner fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                        </div>
                                    </button>


                                    <div class="portal_info_activar mt-2 mt-sm-0">
                                        <button type="button" class="ms-3 login100-form-btn btn btn-primary bg-primary-gradient d-flex justify-content-center align-items-center" id="btnActivarPortalResidente">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="fa-regular fa-toggle-on fa-fw fa-lg me-1"></i>
                                                <span class="">Activar Portal</span>
                                                <i class="far fa-spinner fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                            </div>
                                        </button>
                                    </div>

                                    <div class="portal_info_desactivar mt-2 mt-sm-0">
                                        <button type="button" class="ms-3 login100-form-btn btn btn-danger bg-danger-gradient d-flex justify-content-center align-items-center" id="btnDesactivarPortalResidente">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="fa-regular fa-power-off fa-fw fa-lg me-1"></i>
                                                <span class="">Desactivar Portal</span>
                                                <i class="far fa-spinner fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                            </div>
                                        </button>
                                    </div>

                                    <div class="regenerar_usuario_portal mt-2 mt-sm-0">
                                        <button type="button" class="ms-3 login100-form-btn btn btn-info bg-info-gradient d-flex justify-content-center align-items-center" id="btnRegenerarUsuarioPortal">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="fa-regular fa-paper-plane fa-fw fa-lg me-1"></i>
                                                <span class="">Regenerar Usuario Portal</span>
                                                <i class="far fa-spinner fa-send fa-fw fa-lg ms-2" style="display:none;"></i>
                                            </div>
                                        </button>
                                    </div>

                                    <div class="portal_info">

                                    </div>


                                </div>

                                <!-- Aviso -->
                                <div class="form-group col-12 mt-1">
                                    <div class="alert alert-warning" role="alert">
                                        <div>
                                            <span class="alert-inner--icon"><i class="fa-light fa-bell tx-18"></i></span>
                                            <span class="alert-inner--text tx-12 ms-1">Por solicitud de Los Representantes de Calles, en oficio S/N de fecha 29 de dicimebre del 2025,
                                                a partir del 31 de diciembre del 2025 se restringe la cancelación de recibos a la Administración, debiendo solicitar autorización a dichos representantes
                                                exponiendo los motivos de cancelación.
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="page-header mt-0">
            <div class="ms-auto pageheader-btn">
                <btn class="btn btn-secondary bg-warning-gradient  btn-icon text-white me-2 crear_editar_recibo <?= $disabled = ($data['permisosMod']['c']) ? '' : 'disabled'; ?>" id="btnNuevoRecibo" data-animation="fadeIn">
                    <span>
                        <i class="fa-regular fa-circle-plus me-1"></i>
                    </span> Nuevo
                </btn>
            </div>
        </div>

        <!-- ROW Recibos de Cobro -->
        <div class="row">

            <div class="col-12">

                <div class="card">


                    <div class="card-header bg-light">
                        <h4 class="card-title">
                            <i class="fa-regular fa-file-invoice text-secondary text-secondary-shadow fa-lg me-1"></i> <?= $data['page_card_title']; ?>
                        </h4>
                    </div>

                    <div class="card-body">

                        <!-- <th scope="col" class="text-center">Opciones</th>
                                            <th scope="col">Concepto</th>
                                            <th scope="col" class="text-center">Importe</th>
                                            <th scope="col" class="text-center">Estatus</th>
                                            <th scope="col" class="text-center">Folio Recibo</th>
                                            <th scope="col" class="text-center">Fecha Recibo</th> -->


                        <!-- Lista -->
                        <div class="" id="list_recibo">

                            <div class="row">

                                <!-- Subtitulos Lista de Comprobantes Registrados -->
                                <div class="form-group col-12 mt-2">
                                    <div class="border-bottom-subtitle subtitulos_panel">
                                        <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-bars-staggered text-secondary text-secondary-shadow fs-16"></i> Lista de Recibos de Cobro Registrados.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Comprobantes Registrados -->

                                <div class="form-group col-12  mt-1">

                                    <!-- Tabla de Registros -->
                                    <div class="table-responsive">
                                        <table id="tableRecibos" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                            <thead class="bg-secondary text-white custom-text-shadow">
                                                <tr>
                                                    <th class="font-weight-bold text-center">Folio Recibo</th>
                                                    <th class="font-weight-bold text-center">Fecha Recibo</th>
                                                    <th class="font-weight-bold text-center">Concepto</th>
                                                    <th class="font-weight-bold text-center">Importe</th>
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
                        <div class="" id="crear_editar_recibo" style="display: none;">

                            <form name="formRecibo" id="formRecibo" action="">

                                <div class="row">

                                    <!-- Aviso -->
                                    <div class="form-group col-12 mt-1">
                                        <div class="alert alert-warning" role="alert">
                                            <div>
                                                <span class="alert-inner--icon"><i class="fa-light fa-bell tx-18"></i></span>
                                                <span class="alert-inner--text tx-12 ms-1">Por solicitud de Los Representantes de Calles, en oficio S/N de fecha 29 de dicimebre del 2025,
                                                    a partir del 31 de diciembre del 2025 se restringe la cancelación de recibos a la Administración, debiendo solicitar autorización a dichos representantes
                                                    exponiendo los motivos de cancelación.
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subtitulos Crear/Editar Datos -->
                                    <div class="form-group col-12 mt-1">
                                        <div class="border-bottom-subtitle subtitulos_panel">
                                            <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-pencil text-secondary text-secondary-shadow fs-16"></i> Datos de Recibo de Cobro a Registrar.</p>
                                        </div>
                                    </div>
                                    <!-- Fin Crear/Editar Datos -->

                                    <div class="form-group col-12 col-sm-2">
                                        <label for="recibo_cantidad" class="form-label">Cantidad:</label>
                                        <input type="text" class="form-control inputForm100_recibo" name="recibo_cantidad" id="recibo_cantidad" placeholder="Ingrese Cantidad" readonly requiered>
                                    </div>

                                    <div class="form-group col-12 col-sm-10">
                                        <label for="recibo_concepto" class="form-label">Concepto:</label>
                                        <input type="text" class="form-control inputForm100_recibo" name="recibo_concepto" id="recibo_concepto" placeholder="Ingrese Concepto" readonly requiered>
                                    </div>

                                    <div class="form-group col-12 col-sm-4">
                                        <label for="recibo_subtotal" class="form-label">Subtotal:</label>
                                        <input type="number" class="form-control inputForm100_recibo" name="recibo_subtotal" id="recibo_subtotal" placeholder="Ingrese Subtotal" readonly requiered>
                                    </div>

                                    <div class="form-group col-12 col-sm-4">
                                        <label for="recibo_descuento" class="form-label">% Descuento por Pago Anticipado:</label>
                                        <input type="number" class="form-control inputForm100_recibo" name="recibo_descuento" id="recibo_descuento" placeholder="Ingrese Descuento" requiered>
                                    </div>

                                    <div class="form-group col-12 col-sm-4">
                                        <label for="recibo_dejaacuenta" class="form-label">Deja a Cuenta:</label>
                                        <input type="number" class="form-control inputForm100_recibo" name="recibo_dejaacuenta" id="recibo_dejaacuenta" placeholder="Ingrese cuanto deja a cuenta" requiered>
                                    </div>

                                    <div class="form-group col-12" id="mostrar-saldo-disponible" style="display: none;">
                                    </div>

                                    <div class="form-group col-12 col-sm-4">
                                        <label for="recibo_importe" class="form-label fs-14">Total Importe:</label>
                                        <input type="number" class="form-control inputForm100_recibo h-7 fs-20" name="recibo_importe" id="recibo_importe" placeholder="Ingrese Importe" readonly requiered>
                                    </div>

                                    <div class="form-group col-12 col-sm-4">
                                        <label for="recibo_recibe" class="form-label fs-14">Recibe:</label>
                                        <input type="number" class="form-control inputForm100_recibo h-7 fs-20" name="recibo_recibe" id="recibo_recibe" placeholder="Ingrese cuanto recibe" requiered>
                                    </div>

                                    <div class="form-group col-12 col-sm-4">
                                        <label for="recibo_cambio" class="form-label fs-14">Cambio:</label>
                                        <input type="number" class="form-control inputForm100_recibo h-7 fs-20" name="recibo_cambio" id="recibo_cambio" placeholder="Ingrese Cambio" readonly requiered>
                                    </div>


                                    <div class="form-group col-12 mb-0 mt-3">

                                        <input type="hidden" class="" name="recibo_residente_id" id="recibo_residente_id" value="">

                                        <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                            <input type="hidden" name="inputIdRecibo" id="inputIdRecibo" value="">
                                            <button type="submit" class="<?php
                                                                            if ($data['permisosMod']['c'] == 0 && $data['permisosMod']['u'] == 0) {
                                                                                echo 'disabled';
                                                                            } ?> btn btn-secondary bg-warning-gradient  btn-guardar-form d-flex justify-content-center align-items-center" id="btnGuardarRecibo">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <i class="fa-regular fa-floppy-disk-pen fa-fw fa-lg me-1"></i>
                                                    <span class="">Guardar</span>
                                                    <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                </div>
                                            </button>
                                            <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center list_recibo btnCancelar_Recibo" data-animation="fadeIn">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <i class="fa-regular fa-rotate-left fa-fw fa-lg me-1"></i>
                                                    <span class="">Regresar a Listado</span>
                                                    <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                </div>
                                            </button>
                                        </div>
                                    </div>


                                    <!-- Subtitulos Crear/Editar Datos -->
                                    <div class="form-group col-12 mt-4 mb-2">
                                        <div class="border-bottom-subtitle subtitulos_panel">
                                            <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-grid-2-plus text-secondary text-secondary-shadow fs-16"></i> Opciones de Seleccion.</p>
                                        </div>
                                    </div>
                                    <!-- Fin Crear/Editar Datos -->


                                    <!-- Meses Adelantados -->
                                    <div class="meses-adelanto-head form-group col-12 mb-2" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#meses-adelanto-collapse" aria-expanded="false" aria-controls="meses-adelanto-collapse">
                                        <label class="form-label text-warning" style="cursor: pointer;"><i class="fa-regular fa-angles-right"></i> Seleccione Meses Adelantados que desee agregar al recibo:</label>
                                        <div class="dropdown-divider m-0" style="width: 380px;margin-left: 14px!important; cursor: pointer;"></div>
                                    </div>

                                    <div id="meses-adelanto-collapse" class="collapse col-12">
                                        <div class="row" id="meses-adelanto">
                                            <!-- <div class="form-group col-12 col-sm-3">
                                                <div class="selectgroup selectgroup-pills m-0 w-100">
                                                    <label class="selectgroup-item w-100">
                                                        <input type="checkbox" name="mes_pago" value="2021-12" class="selectgroup-input">
                                                        <span class="selectgroup-button selectgroup-button-warning">DICIEMBRE 2022</span>
                                                    </label>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                    <!-- Fin Meses Adelantados -->

                                    <!-- Meses Adeudo -->
                                    <div class="form-group col-12 mb-2">
                                        <label class="form-label text-danger"><i class="fa-regular fa-angles-right"></i> Seleccione Meses de Adeudo que desee agregar al recibo:</label>
                                        <div class="dropdown-divider m-0" style="width: 380px;margin-left: 14px!important;"></div>
                                    </div>

                                    <div id="meses-adeudo-collapse" class="col-12">

                                        <div class="row" id="meses-adeudo">

                                            <!-- <div class="form-group col-12 col-sm-3">
                                                <div class="selectgroup selectgroup-pills m-0 w-100">
                                                    <label class="selectgroup-item w-100">
                                                        <input type="checkbox" name="mes_pago" value="2019-12" class="selectgroup-input">
                                                        <span class="selectgroup-button selectgroup-button-danger">DICIEMBRE 2019</span>
                                                    </label>
                                                </div>
                                            </div> -->

                                        </div>

                                    </div>
                                    <!-- Fin Meses Adeudo -->

                                    <!-- Meses Pagados -->
                                    <div class="form-group col-12 mb-2 ">
                                        <label class="form-label text-success"><i class="fa-regular fa-angles-right"></i> Meses Pagados:</label>
                                        <div class="dropdown-divider m-0" style="width: 114px;margin-left: 14px!important;"></div>
                                    </div>

                                    <div id="meses-pagados-collapse" class="col-12">

                                        <div class="row" id="meses-pagados">

                                            <!-- <div class="form-group col-12 col-sm-3">
                                                <div class="selectgroup selectgroup-pills m-0 w-100">
                                                    <label class="selectgroup-item w-100">
                                                        <span class="selectgroup-button selectgroup-button-success d-flex flex-column">
                                                            <span>DICIEMBRE 2021</span>
                                                            <a class="recibos-link-pagados fs-11" href="<?= base_url(); ?>/recibos/generarComprobante/321" target="_blank">Recibo Folio: A-000321</a>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div> -->


                                        </div>
                                    </div>
                                    <!-- Fin Meses Pagados -->

                                </div>

                            </form>

                        </div>
                        <!-- Fin Editar Datos -->

                        <!-- Vista de Datos -->
                        <div class="" id="view_recibo" style="display: none;">

                            <div class="row">

                                <!-- Datos de Recibo -->
                                <div class="col-12">

                                    <!-- Subtitulos Editar Datos de Recibo-->
                                    <div class="form-group mt-1">
                                        <div class="border-bottom-subtitle subtitulos_panel">
                                            <p class="mb-0 fw-semibold fs-14"><i class="fa-regular fa-file-invoice text-secondary text-secondary-shadow fs-16"></i> Datos de Recibo.</p>
                                        </div>
                                    </div>
                                    <!-- Fin Editar Datos de Recibo -->

                                    <div class="form-group mt-2">

                                        <div class="d-flex flex-column justify-content-start align-items-start">

                                            <div class="">
                                                <label class="fs-13">Folio de Recibo: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputFolio_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class=" mt-3 ">
                                                <label class="fs-13">Concepto:</label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputConcepto_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class=" mt-3 ">
                                                <label class="fs-13">Importe:</label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputImporte_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <a class="mostrar_mas_menos mt-3" style="text-decoration: underline;" data-bs-toggle="collapse" href="#collapseMostrarMas" role="button" aria-expanded="false" aria-controls="collapseRecibos">
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
                                                    <label class="fs-13">Fecha de Registro:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="fechaRegistro_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Usuario Registró:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="usuarioRegistro_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                                <hr class="m-0">

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Fecha de Cancelación:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="fechaCancelacion_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Usuario Canceló:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="usuarioCancela_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>

                                                <div class=" mt-3 ">
                                                    <label class="fs-13">Motivo Cancelación:</label>
                                                    <h5 class="vistadatos fs-13 text-muted" id="motivoCancelacion_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                                </div>


                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <!-- Fin Datos Generales y de Contacto -->

                                <div class="form-group col-12 mb-0">
                                    <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                        <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form-only d-flex justify-content-center align-items-center list_recibo btnCancelar_Recibo" data-animation="fadeIn">
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

<!-- Panel footer -->
<!-- <?php //require_once("Views/Template/page-footer.php"); 
        ?> -->
<!--/Panel footer-->

<!-- Sidebar-right -->
<?php //require_once("Views/Template/sidebar-rigth.php"); 
?>
<!--/Sidebar-right-->

<!-- Footer -->
<?php require_once("Views/Template/footer_admin.php"); ?>

<!-- Custom Script Footer -->

<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->