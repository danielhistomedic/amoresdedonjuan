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
                <btn class="btn btn-secondary bg-warning-gradient  btn-icon text-white me-2 <?= $disabled = ($data['permisosMod']['c']) ? '' : 'disabled'; ?>" id="btnCrear_Usuario" data-animation="fadeInDown">
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
                        <div class="" id="list_htas_usuario">

                            <div class="row">

                                <!-- Subtitulos Lista de Usuarios Registrados -->
                                <div class="form-group col-12">
                                    <div class="border-bottom subtitulos_panel">
                                        <p class="mb-0 fw-semibold"><i class="fa-regular fa-bars-staggered text-secondary text-secondary-shadow fs-15"></i> Lista de Usuarios Registrados.</p>
                                    </div>
                                </div>
                                <!-- Fin Lista de Usuarios Registrados -->

                                <div class="form-group col-12  mt-1">
                                    <div class="table-responsive">
                                        <table id="tableUsuarios" class="table table-striped table-bordered table-hover tabla-sys" style="width:100%">
                                            <thead class="bg-secondary text-white custom-text-shadow">
                                                <tr>
                                                    <th class="fw-semibold text-center">Usuario</th>
                                                    <th class="fw-semibold text-center">Nombre</th>
                                                    <th class="fw-semibold text-center">Email</th>
                                                    <th class="fw-semibold text-center">Telefono</th>
                                                    <th class="fw-semibold text-center">Rol</th>
                                                    <th class="fw-semibold text-center">Estatus</th>
                                                    <th class="fw-semibold text-center">Opciones</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- Fin Lista -->

                        <!-- Crear/Editar Datos -->
                        <div class="" id="crear_editar_htas_usuario" style="display: none;">

                            <form name="formUsuario" id="formUsuario" action="">

                                <div class="row">

                                    <!-- Subtitulos Editar Datos -->
                                    <div class="form-group col-12 mt-1">
                                        <div class="border-bottom subtitulos_panel">
                                            <p class="mb-0 fw-semibold"><i class="fa-regular fa-pencil text-secondary text-secondary-shadow fs-15"></i> Datos de Usuario a Registrar/Editar.</p>
                                        </div>
                                    </div>
                                    <!-- Fin Editar Datos -->

                                    <!-- Nombre -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4">
                                        <label class="d-block tx-12 tx-medium tx-spacing-1 ml-1 login-label" for="inputNombreUsuario">Nombre:</label>
                                        <input type="text" class="form-control inputForm100" name="inputNombreUsuario" id="inputNombreUsuario" placeholder="Ingrese Nombre" required="">
                                    </div> <!-- form-group -->
                                    <!-- Fin Nombre -->

                                    <!-- Apellido Paterno -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4">
                                        <label class="d-block tx-12 tx-medium tx-spacing-1 ml-1 login-label" for="inputApellidoPaterno">Apellido Paterno:</label>
                                        <input type="text" class="form-control inputForm100" name="inputApellidoPaterno" id="inputApellidoPaterno" placeholder="Ingrese Apellido Paterno" required="">
                                    </div><!-- form-group -->
                                    <!-- Fin Apellido Paterno -->

                                    <!-- Apellido Materno -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4">
                                        <label class="d-block tx-12 tx-medium tx-spacing-1 ml-1 login-label" for="inputApellidoMaterno">Apellido Materno:</label>
                                        <input type="text" class="form-control inputForm100" name="inputApellidoMaterno" id="inputApellidoMaterno" placeholder="Ingrese Apellido Materno" required="">
                                    </div><!-- form-group -->
                                    <!-- Fin Apellido Materno -->

                                    <!-- Sexo -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4">
                                        <label class="d-block tx-12 tx-medium tx-spacing-1 ml-1 login-label" for="comboSexo">Sexo:</label>
                                        <div id="slWrapper1" class="parsley-select">
                                            <select class="select2 mb-3 custom-select selectForm100" name="comboSexo" id="comboSexo" style="width: 100%" required="">
                                                <option value="" selected="selected" disabled>Seleccione una opcion</option>
                                                <option value="2">Femenino</option>
                                                <option value="1">Masculino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Fin Sexo -->

                                    <!-- Email -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4">
                                        <label class="d-block tx-12 tx-medium tx-spacing-1 ml-1 login-label" for="inputEmail">Correo Electronico:</label>
                                        <input type="email" class="form-control inputForm100" name="inputEmail" id="inputEmail" placeholder="Ingrese Correo Electronico" required="">
                                    </div><!-- form-group -->
                                    <!-- Fin Email -->

                                    <!-- Telefono Móvil -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4">
                                        <label class="d-block tx-12 tx-medium tx-spacing-1 ml-1 login-label" for="inputTelefono">Telefono Móvil:</label>
                                        <input type="text" class="form-control inputForm100" name="inputTelefono" id="inputTelefono" placeholder="Ingrese Telefono Móvil" required="">
                                    </div><!-- form-group -->
                                    <!-- Fin Telefono Móvil -->

                                    <!-- Rol de Acceso -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4">
                                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="comboRoles">Rol de Acceso:</label>
                                        <div id="slWrapper" class="parsley-select">
                                            <select class="select2 mb-3 custom-select selectForm100" name="comboRoles" id="comboRoles" style="width: 100%" required>

                                            </select>
                                        </div>
                                    </div>
                                    <!-- Fin Rol de Acceso -->


                                    <!-- Password -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4 show-password-parent">
                                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterPassword">Contraseña:</label>
                                        <input type="password" class="form-control inputForm100" name="inputRegisterPassword" id="inputRegisterPassword" placeholder="Ingrese Contraseña" required="">
                                        <span class="show-password-register show text-secondary"><i class="fa-regular fa-eye mostrar-password"></i></span>
                                    </div><!-- form-group -->
                                    <!-- Fin Password -->

                                    <!-- Password -->
                                    <div class="form-group col-12 col-sm-6 col-xl-4 show-password-parent">
                                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterConfirmPassword">Confirmar Contraseña:</label>
                                        <input type="password" class="form-control inputForm100" name="inputRegisterConfirmPassword" id="inputRegisterConfirmPassword" placeholder="Confirmar Contraseña" required="">
                                        <span class="show-password-register show text-secondary"><i class="fa-regular fa-eye mostrar-password"></i></span>
                                    </div><!-- form-group -->
                                    <!-- Fin Password -->

                                    <div class="form-group col-12 mb-0 mt-3">
                                        <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                            <input type="hidden" name="inputIdUsuario" id="inputIdUsuario" value="">
                                            <button type="submit" class="btn btn-secondary bg-warning-gradient  btn-guardar-form d-flex justify-content-center align-items-center <?php
                                                                                                                                                                                    if ($data['permisosMod']['c'] == 0 && $data['permisosMod']['u'] == 0) {
                                                                                                                                                                                        echo 'disabled';
                                                                                                                                                                                    } ?>" id="btnGuardar_Usuario">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <i class="fa-regular fa-floppy-disk-pen fa-fw fa-lg me-1"></i>
                                                    <span class="">Guardar</span>
                                                    <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                </div>
                                            </button>
                                            <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center list_htas_usuario btnCancelar_Usuario" data-animation="fadeIn">
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
                        <div class="" id="view_htas_usuario" style="display: none;">

                            <div class="row">

                                <!-- Datos de Usuario -->
                                <div class="col-12">

                                    <!-- Subtitulos Editar Datos de Usuario -->
                                    <div class="form-group mt-1">
                                        <div class="border-bottom subtitulos_panel">
                                            <p class="mb-0 fw-semibold"><i class="fa-regular fa-circle-info text-secondary text-secondary-shadow fs-15"></i> Datos de Usuario.</p>
                                        </div>
                                    </div>
                                    <!-- Fin Editar Datos de Usuario -->

                                    <div class="form-group mt-2">

                                        <div class="d-flex flex-column justify-content-start align-items-start">

                                            <div class="">
                                                <label class="fs-13">Nombre del Usuario: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputNombreUsuario_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="mt-1">
                                                <label class="fs-13">Sexo:</label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputSexo_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="mt-1">
                                                <label class="fs-13">Email: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputEmail_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="mt-1">
                                                <label class="fs-13">Telefono: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputTelefono_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class="mt-1">
                                                <label class="fs-13">Rol: </label>
                                                <h5 class="vistadatos fs-13 text-muted" id="inputRol_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <div class=" mt-3 ">
                                                <label class="fs-13">Estatus:</label>
                                                <h5 class="vistadatos fs-13 text-muted" id="estatus_read"><i class="fa-light fa-brake-warning"></i> No Registrado</h5>
                                            </div>

                                            <a class="mostrar_mas_menos_usuario mt-3" style="text-decoration: underline;" data-bs-toggle="collapse" href="#collapseMostrarMasVistaUsuario" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <div id="mostrar_mas_usuario" class="">
                                                    <span>Mostrar más</span><i class="ms-1 fa-light fa-angle-down"></i>
                                                </div>
                                                <div id="mostrar_menos_usuario" class="d-none">
                                                    <span>Mostrar menos</span><i class="ms-1 fa-light fa-angle-up"></i>
                                                </div>
                                            </a>

                                            <div class="collapse" id="collapseMostrarMasVistaUsuario">

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
                                        <button type="button" class="btn btn-secondary bg-warning-gradient  btn-editar-form d-flex justify-content-center align-items-center crear_editar_htas_usuario <?= $disabled = ($data['permisosMod']['u']) ? '' : 'disabled'; ?>" id="btnEditar_Usuario" data-animation="fadeIn">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="fa-regular fa-file-pen fa-fw fa-lg me-1"></i>
                                                <span class="">Editar</span>
                                                <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                            </div>
                                        </button>
                                        <button type="button" class="btn btn-danger bg-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center list_htas_usuario btnCancelar_Usuario" data-animation="fadeIn">
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


<!-- Footer -->
<?php require_once("Views/Template/footer_admin.php"); ?>

<!-- Custom Script Footer -->

<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->