<div class="row">



    <!-- Cards Facturacion -->
    <div class="panel-group1" id="accordion2">

        <!-- Configuración de Facturación de Unidad Médica -->
        <div class="panel panel-default mb-4 shadow-sm">

            <div class="panel-heading1">
                <h4 class="panel-title1">
                    <a class="accordion-toggle collapsed" data-bs-toggle="collapse" data-bs-parent="#accordion2" href="#collapseUsuarios" aria-expanded="true">
                        <div class="d-flex justify-content-start align-items-center">
                            <span class="fa-stack me-4 me-sm-2" style="height: 12px;">
                                <i class="fa-light fa-laptop-medical fa-stack-2x" style="font-size: 20px;top: -3px;"></i>
                                <i class="fa-solid fa-gears fa-stack-1x" style="margin: -14px 0 0px 11px;font-size: 14px;"></i>
                            </span>
                            <span class="tx-semibold">Módulo de Registro, Control y Edición de Usuarios.</span>
                        </div>
                    </a>
                </h4>
            </div>

            <div id="collapseUsuarios" class="panel-collapse collapse show" role="tabpanel" aria-expanded="true" style="">

                <div class="panel-body">

                    <!-- Lista -->
                    <div class="" id="list_htas_usuarios">

                        <div class="row">

                            <div class="form-group col-12">
                                <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                    <input type="hidden" name="inputIdPaciente" id="inputIdPaciente" val="">
                                    <button type="button" class="btn btn-pill btn-primary-gradient btn-editar-form d-flex justify-content-center align-items-center editar_config_usuarios_consultorio" id="btnHtas_Nuevo_Usuario" data-animation="fadeInDown">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <i class="fa-regular fa-user-plus fa-fw fa-lg me-1"></i>
                                            <span class="">Crear Usuario</span>
                                            <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!-- Subtitulos Lista de Usuarios Registrados -->
                            <div class="form-group col-12 mt-2">
                                <div class="border-bottom subtitulos_panel">
                                    <p class="mb-0"><i class="fa-regular fa-bars-staggered tx-15"></i> Lista de Usuarios Registrados.</p>
                                </div>
                            </div>
                            <!-- Fin Lista de Usuarios Registrados -->

                            <div class="form-group col-12  mt-1">
                                <div class="table-responsive">
                                    <table id="tableUsuarios" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                        <thead class="bg-secondary text-white custom-text-shadow">
                                            <tr>
                                                <th class="font-weight-bold text-center">Opciones</th>
                                                <th class="font-weight-bold text-center">Estado</th>
                                                <th class="font-weight-bold text-center">Usuario</th>
                                                <th class="font-weight-bold text-center">Nombre</th>
                                                <th class="font-weight-bold text-center">Ap. Paterno</th>
                                                <th class="font-weight-bold text-center">Rol</th>
                                                <th class="font-weight-bold text-center">Email</th>
                                                <th class="font-weight-bold text-center">Telefono</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>


                            <div class="form-group mt-2">
                                <div class="d-flex flex-column justify-content-start align-items-start">

                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Fin Lista -->

                    <!-- Editar -->
                    <div class="" id="editar_config_usuarios_consultorio" style="display: none;">

                        <form name="formConfigFactUnidadMedica" id="formConfigFactUnidadMedica" action="" data-parsley-validate>

                            <div class="row">

                                <!-- <div class="form-group col-12 mt-1 d-none">
                                    <div class="alert alert-info" role="alert">
                                        <div>
                                            <span class="alert-inner--icon"><i class="fa-light fa-bell tx-18"></i></span>
                                            <span class="alert-inner--text tx-12 ms-1">Llene los datos requeridos y presione el botón <strong>Guardar</strong></span>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="form-group col-12 mt-1">

                                    <div class="ms-auto pageheader-btn">
                                        <a href="#" class="btn btn-primary bg-primary-gradient btn-icon text-white me-2">
                                            <span>
                                                <i class="fe fe-plus"></i>
                                            </span> Nuevo Usuario
                                        </a>
                                    </div>
                                </div>

                                <!-- Subtitulos Editar Datos Fiscales -->
                                <div class="form-group col-12 mt-1">
                                    <div class="border-bottom subtitulos_panel">
                                        <p><i class="fa-regular fa-pencil tx-15"></i> Datos Fiscales.</p>
                                    </div>
                                </div>
                                <!-- Fin Editar Datos Fiscales -->

                                <!-- RFC -->
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label class="login-label" for="inputRegisterRFC">RFC:</label>
                                    <span class="bar-left-input-row"><i class="fa-thin fa-text-height fa-fw tx-18 lh-0 op-6"></i></span>
                                    <input type="text" class="form-control inputForm100" name="inputRegisterRFC" id="inputRegisterRFC" placeholder="Ingrese RFC" data-parsley-trigger="keyup" data-parsley-length="[12, 13]" data-parsley-required>
                                </div><!-- form-group -->
                                <!-- Fin RFC -->


                                <div class="form-group col-12 mb-0 mt-3">
                                    <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                        <input type="hidden" name="inputIdPaciente" id="inputIdUnidadMedica" val="<?= $unidad_medica_id; ?>">
                                        <button type="submit" class="btn btn-pill btn-primary-gradient btn-guardar-form d-flex justify-content-center align-items-center" id="btnGuardar_Users_UnidadMedica" data-id="<?= $unidad_medica_id; ?>">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="fa-regular fa-floppy-disk-pen fa-fw fa-lg me-1"></i>
                                                <span class="">Guardar</span>
                                                <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                            </div>
                                        </button>
                                        <button type="button" class="btn btn-pill btn-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center view_config_usuarios_consultorio" id="btnCancelar_Users_UnidadMedica" data-animation="fadeInLeft">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="fa-regular fa-rotate-left fa-fw fa-lg me-1"></i>
                                                <span class="">Cancelar</span>
                                                <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <!-- <div class="form-group col-12 mb-0 mt-3">
                                    <div class="">
                                        <i class="fa-light fa-message-exclamation tx-16 me-1"></i><strong>¡IMPORTANTE!</strong> El manejo y uso de datos es de acuerdo a lo marcado en la Ley Federal de Protección de Datos Personales en Posesión de Particulares
                                    </div>
                                </div> -->

                            </div>

                        </form>

                    </div>


                    <!-- Consultar -->
                    <div class="" id="view_config_usuarios_consultorio" style="display: none;">

                        <div class="row">

                            <!-- Subtitulos Editar Datos Fiscales -->
                            <div class="form-group mt-1">
                                <div class="border-bottom subtitulos_panel">
                                    <p><i class="fa-regular fa-stethoscope tx-15"></i> Datos de Usuario Seleccionado.</p>
                                </div>
                            </div>
                            <!-- Fin Editar Datos Generales y de Contacto -->

                            <div class="form-group mt-2">
                                <div class="d-flex flex-column justify-content-start align-items-start">
                                    <!-- <div class="">
                                        <label class="tx-13">Razon Social:</label>
                                        <h5 class="vistadatos tx-13" id="inputRegisterRazonSocial_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                    </div>
                                    <div class="mt-3 ">
                                        <label class="tx-13">RFC: </label>
                                        <h5 class="vistadatos tx-13" id="inputRegisterRFC_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                    </div> -->
                                </div>

                            </div>


                            <div class="form-group col-12 mb-0">
                                <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                    <input type="hidden" name="inputIdPaciente" id="inputIdPaciente" val="">
                                    <button type="button" class="btn btn-pill btn-secondary-gradient btn-editar-form d-flex justify-content-center align-items-center editar_config_usuarios_consultorio" id="btnEditar_Users_UnidadMedica" data-animation="fadeInDown">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <i class="fa-regular fa-pen-to-square fa-fw fa-lg me-1"></i>
                                            <span class="">Editar</span>
                                            <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- Fin Configuración de Facturacion de Unidad Médica -->

    </div>
    <!-- Fin Facturacion -->

</div>