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
<div class="wrapper-sidemenu d-flex align-items-stretch" id="app-content-mobile">

    <div class="container">


        <!-- Page Content  -->

        <!-- <div class="page-content-custom-bg"></div> -->

        <div id="content" class="d-flex flex-column justify-content-start align-items-start">

            <div class="page-content-subtitle-custom">

                <div class="page-header-fluid" id="page-header-desktop">
                    <div>
                        <h1 class="page-title page-title-custom">
                            <span class="fa-stack tx-6" style="width:26px;">
                                <i class="fa-regular fa-id-card" style="font-size: 22px;top: 0px;left: 0px;"></i>
                            </span>
                            <span style="margin-left: 0px;">Perfil de Usuario</span>
                        </h1>
                    </div>
                </div>

            </div>

            <div class="page-custom">

                <!-- Cards Perfil de Usuario -->
                <div class="row" id="user-profile">

                    <div class="col-12">

                        <div class="card">

                            <!-- <div class="card-header gradient-custom-content">
                                <h3 class="ms-1 card-title">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <i class="fa-regular fa-laptop-arrow-down tx-18"></i>
                                        <span class="card-header-subtitle ms-1">Registro y Edición de Datos de Perfil de Usuario</span>
                                    </div>
                                </h3>
                                <div class="card-options">
                                    <a href="#" class="card-options-fullscreen" data-bs-toggle="card-fullscreen">
                                        <i class="fa-regular fa-arrow-up-right-and-arrow-down-left-from-center"></i>
                                    </a>
                                </div>
                            </div> -->

                            <div class="card-body">

                                <!-- Editar Datos -->
                                <div class="" id="editar_perfilusuario" style="display: none;">

                                    <form name="formPerfilUsuario" id="formPerfilUsuario" action="" data-parsley-validate>

                                        <div class="row">

                                            <!-- Titulo del Form -->
                                            <div class="col-12">
                                                <div class="form-group mg-b-30-force">
                                                    <div class="card-header border-bottom-title-form pd-t-0-force pd-b-8-force titulo_detalle">
                                                        <p class="mb-0 tx-uppercase"><i class="fa-regular fa-file-lines tx-18"></i> Editar Datos de Perfil de Usuario.</p>
                                                        <div class="card-options">
                                                            <a href="#" class="card-options-fullscreen" data-bs-toggle="card-fullscreen">
                                                                <i class="fa-regular fa-arrow-up-right-and-arrow-down-left-from-center"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Fin Titulo del Form -->

                                            <!-- Subtitulos Editar Datos de Contacto -->
                                            <div class="form-group col-12 mt-1">
                                                <div class="border-bottom subtitulos_panel">
                                                    <p class="mb-0"><i class="fa-regular fa-pencil tx-15"></i> Datos de Contacto.</p>
                                                </div>
                                            </div>
                                            <!-- Fin Editar Datos de Contacto -->

                                            <!-- Foto de Perfil -->
                                            <div class="form-group col-12 col-md-6 col-xl-3">
                                                <label class="login-label" for="">Foto Actual:</label>
                                                <div class="d-flex justify-content-center align-items-center" id="logo_actual" style="padding: 9px; height: 199px;">
                                                </div>
                                            </div>

                                            <div class="form-group col-12 col-md-6 col-xl-3">
                                                <label class="login-label" for="inputFotoPerfil">Seleccione Nueva Foto:</label>
                                                <div class="">
                                                    <input type="file" class="dropify" data-bs-height="180" name="inputFotoPerfil" id="inputFotoPerfil" accept=".png, .jpg, .jpeg" autocomplete="off" />
                                                </div>
                                            </div>
                                            <!-- Fin Foto de Perfil -->

                                            <div class="form-group col-12">
                                            </div>

                                            <!-- Telefono Celular -->
                                            <div class="form-group col-12 col-lg-6">
                                                <label class="login-label" for="inputTelefono">Telefono Celular:</label>
                                                <span class="bar-left-input-row"><i class="fa-thin fa-mobile-screen-button fa-fw tx-18 lh-0 op-6"></i></span>
                                                <input type="text" class="form-control inputForm100" name="inputTelefono" id="inputTelefono" placeholder="Ingrese Telefono Celular" autocomplete="off" data-parsley-required>
                                            </div><!-- form-group -->
                                            <!-- Fin Telefono Celular -->

                                            <!-- Telefono Recepción -->
                                            <div class="form-group col-12 col-lg-6">
                                                <label class="login-label" for="inputTelefonoRecepcion">Telefono Recepción:</label>
                                                <span class="bar-left-input-row"><i class="fa-thin fa-phone-rotary fa-fw tx-18 lh-0 op-6"></i></span>
                                                <input type="text" class="form-control inputForm100" name="inputTelefonoRecepcion" id="inputTelefonoRecepcion" placeholder="Ingrese Telefono Recepción" autocomplete="off" data-parsley-required>
                                            </div><!-- form-group -->
                                            <!-- Fin Telefono Recepción -->

                                            <!-- Email -->
                                            <div class="form-group col-12 col-lg-6">
                                                <label class="login-label" for="inputEmail">Email</label>
                                                <span class="bar-left-input-row"><i class="fa-thin fa-at fa-fw tx-18 lh-0 op-6"></i></span>
                                                <input type="email" class="form-control inputForm100" name="inputEmail" id="inputEmail" placeholder="Ingrese email de contacto" autocomplete="off" data-parsley-trigger="keyup" data-parsley-type="email" data-parsley-required>
                                            </div><!-- form-group -->
                                            <!-- Fin Email -->

                                            <!-- Sitio Web -->
                                            <div class="form-group col-12 col-lg-6">
                                                <label class="login-label" for="inputSitioWeb">Sitio Web:</label>
                                                <span class="bar-left-input-row"><i class="fa-thin fa-globe fa-fw tx-18 lh-0 op-6"></i></span>
                                                <input type="text" class="form-control inputForm100" name="inputSitioWeb" id="inputSitioWeb" placeholder="Ingrese Sitio Web" autocomplete="off" data-parsley-required>
                                            </div><!-- form-group -->
                                            <!-- Fin Sitio Web -->


                                            <!-- Subtitulos Especialidades. -->
                                            <div class="form-group col-12 mt-1">
                                                <div class="border-bottom subtitulos_panel">
                                                    <p class="mb-0"><i class="fa-regular fa-pencil tx-15"></i> Especialidades.</p>
                                                </div>
                                            </div>
                                            <!-- Fin Subtitulos Especialidades. -->

                                            <!-- Especialidad, Cedula, Escuela -->
                                            <div class="repeater-custom-show-hide form-group col-12">

                                                <div data-repeater-list="especialidades" class="">

                                                    <div data-repeater-item="" class="row">

                                                        <!-- Especialidades -->
                                                        <div class="form-group col-12 col-lg-6">
                                                            <label class="login-label" for="inputEspecialidad">Especialidad:</label>
                                                            <span class="bar-left-input-row"><i class="fa-thin fa-stars fa-fw tx-18 lh-0 op-6"></i></span>
                                                            <input type="text" class="form-control inputForm100" name="inputEspecialidad" id="inputEspecialidad" placeholder="Ingrese Especialidad" autocomplete="off" data-parsley-required>
                                                        </div><!-- form-group -->

                                                        <div class="form-group col-12 col-lg-2">
                                                            <label class="login-label" for="inputCedula">Cedula:</label>
                                                            <span class="bar-left-input-row"><i class="fa-thin fa-seal-exclamation fa-fw tx-18 lh-0 op-6"></i></span>
                                                            <input type="text" class="form-control inputForm100" name="inputCedula" id="inputCedula" placeholder="Ingrese Cedula" autocomplete="off" data-parsley-required>
                                                        </div><!-- form-group -->

                                                        <div class="form-group col-12 col-lg-4">
                                                            <label class="login-label" for="inputEscuela">Escuela:</label>
                                                            <span class="bar-left-input-row"><i class="fa-thin fa-graduation-cap fa-fw tx-18 lh-0 op-6"></i></span>
                                                            <div class="d-flex">
                                                                <input type="text" style="margin-right: 5px;" class="form-control inputForm100" name="inputEscuela" id="inputEscuela" placeholder="Ingrese Escuela" autocomplete="off" data-parsley-required>
                                                                <span data-repeater-delete="" class="btn btn-outline-danger" style="height:33px; box-shadow: none!important;">
                                                                    <i class="fa-regular fa-trash-can"></i>
                                                                </span>
                                                            </div>
                                                        </div><!-- form-group -->
                                                        <!-- Fin Especialidades -->

                                                    </div>

                                                </div>
                                                <div class="form-group mb-0">
                                                    <span data-repeater-create="" class="btn btn-sm btn-pill btn-secondary" style="font-size: 11px; padding-bottom: 8px;">
                                                        <i class="fa-regular fa-plus"></i> Agregar Especialidad
                                                    </span>
                                                </div>

                                            </div>
                                            <!-- Especialidad, Cedula, Escuela -->


                                            <!-- Subtitulos Experiencia Profesional. -->
                                            <div class="form-group col-12 mt-1">
                                                <div class="border-bottom subtitulos_panel">
                                                    <p class="mb-0"><i class="fa-regular fa-pencil tx-15"></i> Experiencia Profesional.</p>
                                                </div>
                                            </div>
                                            <!-- Fin Subtitulos Experiencia Profesional. -->

                                            <!-- Acerca de Mi -->
                                            <div class="form-group col-12">
                                                <label class="login-label" for="inputAcercaDeMi">Acerca de Mi:</label>
                                                <!-- <span class="bar-left-input-row"><i class="fa-thin fa-user-doctor-message fa-fw tx-18 lh-0 op-6"></i></span> -->
                                                <textarea class="form-control inputForm100" name="inputAcercaDeMi" id="inputAcercaDeMi" cols="30" rows="10" data-parsley-required></textarea>
                                                <!-- <input type="text" class="form-control inputForm100" name="inputAcercaDeMi" id="inputAcercaDeMi" placeholder="Ingrese Descripción" autocomplete="off" data-parsley-required> -->
                                            </div><!-- form-group -->
                                            <!-- Fin Acerca de Mi -->

                                            <!-- Enfermedades mas comunes que atiende -->
                                            <div class="form-group col-12">
                                                <label class="login-label" for="inputEnfermedadesAtiende">Enfermedades mas comunes que atiende:</label>
                                                <span class="bar-left-input-row"><i class="fa-thin fa-bacterium fa-fw tx-18 lh-0 op-6"></i></span>
                                                <input type="text" class="form-control inputForm100" name="inputEnfermedadesAtiende" id="inputEnfermedadesAtiende" placeholder="Ingrese Enfermedades mas comunes" autocomplete="off" data-parsley-required>
                                            </div><!-- form-group -->
                                            <!-- Fin Enfermedades mas comunes que atiende -->

                                            <!-- Especialista en: -->
                                            <div class="form-group col-12">
                                                <label class="login-label" for="inputEsepecialistaEn">Especialista en:</label>
                                                <span class="bar-left-input-row"><i class="fa-thin fa-book-medical fa-fw tx-18 lh-0 op-6"></i></span>
                                                <input type="text" class="form-control inputForm100" name="inputEsepecialistaEn" id="inputEsepecialistaEn" placeholder="Ingrese en qué es Especialista" autocomplete="off" data-parsley-required>
                                            </div><!-- form-group -->
                                            <!-- Fin Especialista en: -->

                                            <!-- Otros estudios realizados -->
                                            <div class="form-group col-12">
                                                <label class="login-label" for="inputOtrosEstudios">Otros estudios realizados:</label>
                                                <span class="bar-left-input-row"><i class="fa-thin fa-diploma fa-fw tx-18 lh-0 op-6"></i></span>
                                                <input type="text" class="form-control inputForm100" name="inputOtrosEstudios" id="inputOtrosEstudios" placeholder="Ingrese estudio realizado" autocomplete="off" data-parsley-required>
                                            </div><!-- form-group -->
                                            <!-- Fin Otros estudios realizados -->
                                            <!-- <i class="fa-brands fa-youtube"></i> -->
                                            <!-- Idiomas: -->
                                            <div class="form-group col-12">
                                                <label class="login-label" for="inputIdiomas">Idiomas:</label>
                                                <span class="bar-left-input-row"><i class="fa-thin fa-language fa-fw tx-18 lh-0 op-6"></i></span>
                                                <input type="text" class="form-control inputForm100" name="inputIdiomas" id="inputIdiomas" placeholder="Ingrese Idioma" autocomplete="off" data-parsley-required>
                                            </div><!-- form-group -->
                                            <!-- Fin Idiomas: -->


                                            <!-- Subtitulos Editar Mis Servicios y Precios. -->
                                            <div class="form-group col-12 mt-1">
                                                <div class="border-bottom subtitulos_panel">
                                                    <p class="mb-0"><i class="fa-regular fa-pencil tx-15"></i> Mis Servicios y Precios.</p>
                                                </div>
                                            </div>
                                            <!-- Fin Subtitulos Editar Mis Servicios y Precios. -->

                                            <!-- Servicios y Precios -->
                                            <div class="repeater-custom-show-hide form-group col-12">

                                                <div data-repeater-list="servicios" class="">

                                                    <div data-repeater-item="" class="row">

                                                        <div class="form-group col-12 col-lg-6">
                                                            <label class="login-label" for="inputServicio">Servicio:</label>
                                                            <span class="bar-left-input-row"><i class="fa-thin fa-circle-check fa-fw tx-18 lh-0 op-6"></i></span>
                                                            <input type="text" class="form-control inputForm100" name="inputServicio" id="inputServicio" placeholder="Ingrese Servicio" autocomplete="off" data-parsley-required>
                                                        </div>

                                                        <div class="form-group col-12 col-lg-6">
                                                            <label class="login-label" for="inputPrecioServicio">Precio:</label>
                                                            <span class="bar-left-input-row"><i class="fa-thin fa-circle-dollar fa-fw tx-18 lh-0 op-6"></i></span>
                                                            <div class="d-flex">
                                                                <input type="text" style="margin-right: 5px;" class="form-control inputForm100" name="inputPrecioServicio" id="inputPrecioServicio" placeholder="Ingrese Precio del Servicio" autocomplete="off" data-parsley-required>
                                                                <span data-repeater-delete="" class="btn btn-outline-danger" style="height:33px; box-shadow: none!important;">
                                                                    <i class="fa-regular fa-trash-can"></i>
                                                                </span>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <span data-repeater-create="" class="btn btn-sm btn-pill btn-secondary" style="font-size: 11px; padding-bottom: 8px;">
                                                        <i class="fa-regular fa-plus"></i> Agregar Servicio
                                                    </span>
                                                </div>

                                            </div>
                                            <!-- Fin Servicios y Precios -->


                                            <!-- Subtitulos Videos y Fotos Promocionales. -->
                                            <div class="form-group col-12 mt-1">
                                                <div class="border-bottom subtitulos_panel">
                                                    <p class="mb-0"><i class="fa-regular fa-pencil tx-15"></i> Videos y Fotos Promocionales.</p>
                                                </div>
                                            </div>
                                            <!-- Fin Subtitulos Videos y Fotos Promocionales. -->



                                            <!-- Fotos -->
                                            <div class="repeater-custom-show-hide form-group col-12">

                                                <div data-repeater-list="fotos" class="">

                                                    <div data-repeater-item="" class="row">

                                                        <!-- Foto -->
                                                        <div class="form-group col-12">
                                                            <label class="login-label" for="inputFotos">Seleccione Foto:</label>
                                                            <span class="bar-left-input-row"><i class="fa-thin fa-cloud-arrow-up fa-fw tx-18 lh-0 op-6"></i></span>
                                                            <div class="d-flex">
                                                                <input type="file" class="form-control inputForm100" name="inputFotos" id="inputFotos" placeholder="Seleccione Foto" autocomplete="off" data-parsley-required>
                                                                <span data-repeater-delete="" class="btn btn-outline-danger" style="height: 33px;box-shadow: none !important;min-width: 38px;margin-left: 5px;">
                                                                    <i class="fa-regular fa-trash-can"></i>
                                                                </span>
                                                            </div>
                                                        </div><!-- form-group -->
                                                        <!-- Fin Foto -->

                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <span data-repeater-create="" class="btn btn-sm btn-pill btn-secondary" style="font-size: 11px; padding-bottom: 8px;">
                                                        <i class="fa-regular fa-plus"></i> Agregar Foto
                                                    </span>
                                                </div>

                                            </div>
                                            <!-- Fin Fotos -->


                                            <!-- Videos -->
                                            <div class="repeater-custom-show-hide form-group col-12">

                                                <div data-repeater-list="videos" class="">

                                                    <div data-repeater-item="" class="row">

                                                        <!-- Video -->
                                                        <div class="form-group col-12">
                                                            <label class="login-label" for="inputVideos">Video:</label>
                                                            <span class="bar-left-input-row"><i class="fa-brands fa-youtube fa-fw tx-18 lh-0 op-6"></i></span>
                                                            <div class="d-flex">
                                                                <input type="text" class="form-control inputForm100" name="inputVideos" id="inputVideos" placeholder="Ingrese link de video de yotutube" autocomplete="off" data-parsley-required>
                                                                <span data-repeater-delete="" class="btn btn-outline-danger" style="height: 33px;box-shadow: none !important;min-width: 38px;margin-left: 5px;">
                                                                    <i class="fa-regular fa-trash-can"></i>
                                                                </span>
                                                            </div>
                                                        </div><!-- form-group -->
                                                        <!-- Fin Video -->

                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <span data-repeater-create="" class="btn btn-sm btn-pill btn-secondary" style="font-size: 11px; padding-bottom: 8px;">
                                                        <i class="fa-regular fa-plus"></i> Agregar Video
                                                    </span>
                                                </div>

                                            </div>
                                            <!-- Fin Videos -->


                                            <div class="form-group col-12 mb-0 mt-3">
                                                <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                                    <input type="hidden" name="inputIdUser" id="inputIdUser">
                                                    <input type="hidden" name="inputIdUnidadMedica" id="inputIdUnidadMedica">
                                                    <button type="submit" class="btn btn-pill btn-primary-gradient btn-guardar-form d-flex justify-content-center align-items-center" id="btnGuardar_PerfilUsuario" data-id="<?= $unidad_medica_id; ?>">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <i class="fa-regular fa-floppy-disk-pen fa-fw fa-lg me-1"></i>
                                                            <span class="">Guardar</span>
                                                            <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                        </div>
                                                    </button>
                                                    <button type="button" class="btn btn-pill btn-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center view_perfil_usuario" id="btnCancelar_PerfilUsuario" data-animation="fadeInLeft">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <i class="fa-regular fa-rotate-left fa-fw fa-lg me-1"></i>
                                                            <span class="">Cancelar</span>
                                                            <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-group col-12 mb-0 mt-3">
                                                <div class="">
                                                    <i class="fa-light fa-message-exclamation tx-16 me-1"></i><strong>¡IMPORTANTE!</strong> El manejo y uso de datos es de acuerdo a lo marcado en la Ley Federal de Protección de Datos Personales en Posesión de Particulares
                                                </div>
                                            </div>

                                        </div>

                                    </form>

                                </div>
                                <!-- Fin Editar Datos -->

                                <!-- Vista de Datos -->
                                <div class="" id="view_perfil_usuario">

                                    <div class="row">

                                        <!-- Titulo del Form -->
                                        <div class="col-12">
                                            <div class="form-group mg-b-30-force">
                                                <div class="card-header border-bottom-title-form pd-t-0-force pd-b-8-force titulo_detalle">
                                                    <p class="mb-0 tx-uppercase"><i class="fa-regular fa-file-lines tx-18"></i> Detalle de Perfil de Usuario.</p>
                                                    <div class="card-options">
                                                        <a href="#" class="card-options-fullscreen" data-bs-toggle="card-fullscreen">
                                                            <i class="fa-regular fa-arrow-up-right-and-arrow-down-left-from-center"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin Titulo del Form -->

                                        <!-- Encabezado -->
                                        <div class="col-12">

                                            <!-- Encabezado Perfil -->
                                            <div class="form-group">

                                                <div class="d-flex flex-column flex-md-row justify-content-start align-items-start">
                                                    <div class="wideget-user-custom-img d-flex justify-content-center align-items-start">
                                                        <img class="" src="<?= $imagen_default; ?>" alt="foto de perfil" style="border-radius: 100%; margin-right: 0; max-width: 200px;">
                                                    </div>
                                                    <div class="wideget-user-custom d-flex flex-column justify-content-center align-items-start">
                                                        <h4><strong><?= $nombre_usuario_completo; ?></strong></h4>
                                                        <h5><i class="fa-light fa-seal-exclamation fa-fw"></i> Cédula Prof. (<?= $cedula; ?>)</h5>
                                                        <h6 class="text-muted mb-3"><i style="font-weight:300" class="fa-thin fa-stars tx-15 fa-fw"></i> <?= $especialidad; ?></h6>
                                                        <h6 class="text-muted mb-3"><i style="font-weight:300" class="fa-thin fa-mobile-screen tx-15 fa-fw"></i> <?= $telefono; ?></h6>
                                                        <h6 class="text-muted mb-3"><i style="font-weight:300" class="fa-thin fa-envelope tx-15 fa-fw"></i> <?= $email; ?></h6>
                                                        <!-- <a href="#" class="btn btn-primary bg-primary-gradient mt-1 mb-1"><i class="fa fa-rss"></i> Editar</a> -->
                                                        <!-- <a href="emailservices.html" class="btn btn-secondary bg-warning-gradient  mt-1 mb-1"><i class="fa fa-envelope"></i> E-mail</a> -->
                                                        <!-- <i class="fa-regular fa-files-medical"></i> -->
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- Fin Encabezado Perfil -->

                                        </div>
                                        <!-- Fin Encabezado -->

                                        <!-- Datos de Contacto -->
                                        <div class="col-12">

                                            <!-- Subtitulos Datos de Contacto -->
                                            <div class="form-group mt-1">
                                                <div class="border-bottom subtitulos_panel">
                                                    <p class="mb-0"><i class="fa-regular fa-stethoscope tx-15"></i> Datos de Contacto.</p>
                                                </div>
                                            </div>
                                            <!-- Fin Datos de Contacto -->

                                            <div class="form-group mt-2 d-flex justify-content-start align-items-start row">

                                                <div class="d-flex flex-column justify-content-start align-items-start col-12 col-lg-6">

                                                    <div class="">
                                                        <label class="tx-13">Telefono Celular: </label>
                                                        <h5 class="vistadatos tx-13" id="inputTelefono_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>

                                                    <div class="">
                                                        <label class="tx-13">Telefono Recepcion: </label>
                                                        <h5 class="vistadatos tx-13" id="inputTelefonoRecepcion_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>

                                                </div>

                                                <div class="d-flex flex-column justify-content-start align-items-start col-12 col-lg-6">

                                                    <div class="">
                                                        <label class="tx-13">Email: </label>
                                                        <h5 class="vistadatos tx-13" id="inputEmail_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>

                                                    <div class="">
                                                        <label class="tx-13">Sitio Web: </label>
                                                        <h5 class="vistadatos tx-13" id="inputSitioWeb_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                        <!-- Fin Datos de Contacto -->

                                        <!-- Especialidades -->
                                        <div class="col-12">

                                            <!-- Subtitulos Especialidades -->
                                            <div class="form-group mt-1">
                                                <div class="border-bottom subtitulos_panel">
                                                    <p class="mb-0"><i class="fa-regular fa-stethoscope tx-15"></i> Especialidades.</p>
                                                </div>
                                            </div>
                                            <!-- Fin Subtitulos Especialidades -->

                                            <div class="form-group mt-2 d-flex justify-content-start align-items-start row">
                                                <div class="d-flex flex-column justify-content-start align-items-start col-12 col-lg-6">
                                                    <div class="">
                                                        <label class="tx-13">Especialidad, Cedula y Escuela: </label>
                                                        <h5 class="vistadatos tx-13" id="inputEspecialidad_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Fin Especialidades -->


                                        <!-- Experiencia Profesional -->
                                        <div class="col-12">

                                            <!-- Subtitulos Experiencia Profesional -->
                                            <div class="form-group mt-1">
                                                <div class="border-bottom subtitulos_panel">
                                                    <p class="mb-0"><i class="fa-regular fa-stethoscope tx-15"></i> Experiencia Profesional.</p>
                                                </div>
                                            </div>
                                            <!-- Fin Experiencia Profesional -->

                                            <div class="form-group mt-2 d-flex justify-content-start align-items-start row">

                                                <div class="d-flex flex-column justify-content-start align-items-start col-12">
                                                    <div class="">
                                                        <label class="tx-13">Acerca de Mí: </label>
                                                        <h5 class="vistadatos tx-13" id="inputAcercaDeMi_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-column justify-content-start align-items-start col-12">
                                                    <div class="">
                                                        <label class="tx-13">Especialista en: </label>
                                                        <h5 class="vistadatos tx-13" id="inputEspecialidad_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-column justify-content-start align-items-start col-12">
                                                    <div class="">
                                                        <label class="tx-13">Enfermedades mas comunes que atiende: </label>
                                                        <h5 class="vistadatos tx-13" id="inputEspecialidad_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-column justify-content-start align-items-start col-12">
                                                    <div class="">
                                                        <label class="tx-13">Otros Estudios Realizados: </label>
                                                        <h5 class="vistadatos tx-13" id="inputEspecialidad_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-column justify-content-start align-items-start col-12">
                                                    <div class="">
                                                        <label class="tx-13">Idiomas: </label>
                                                        <h5 class="vistadatos tx-13" id="inputAcercaDeMi_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- Experiencia Profesional -->


                                        <!-- Mis Servicios -->
                                        <div class="col-12">

                                            <!-- Subtitulos Mis Servicios -->
                                            <div class="form-group mt-1">
                                                <div class="border-bottom subtitulos_panel">
                                                    <p class="mb-0"><i class="fa-regular fa-stethoscope tx-15"></i> Mis Servicios y Precios.</p>
                                                </div>
                                            </div>
                                            <!-- Fin Mis Servicios -->

                                            <div class="form-group mt-2">
                                                <div class="d-flex flex-column justify-content-start align-items-start">
                                                    <div class="">
                                                        <h5 class="tx-semibold tx-13">Lista de Servicios: </h5>
                                                        <h5 class="vistadatos tx-13" id="listaSericios_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Fin Mis Servicios -->


                                        <!-- Fotos y Videos -->
                                        <div class="col-12">

                                            <!-- Subtitulos Fotos y Videos -->
                                            <div class="form-group mt-1">
                                                <div class="border-bottom subtitulos_panel">
                                                    <p class="mb-0"><i class="fa-regular fa-stethoscope tx-15"></i> Videos y Fotos Promocionales.</p>
                                                </div>
                                            </div>
                                            <!-- Fin Fotos y Videos -->

                                            <div class="form-group mt-2">
                                                <div class="d-flex flex-column justify-content-start align-items-start">
                                                    <div class="">
                                                        <h5 class="tx-semibold tx-13">Fotos: </h5>
                                                        <h5 class="vistadatos tx-13" id="comboPais_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>
                                                    <div class="mt-3 ">
                                                        <h5 class="tx-semibold tx-13">Videos: </h5>
                                                        <h5 class="vistadatos tx-13" id="groupDomicilio_read">No Registrado <i class="fa-light fa-brake-warning"></i></h5>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Fin Fotos y Videos -->

                                        <!-- Barra de Botones -->
                                        <div class="form-group col-12 mb-0">
                                            <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                                <button type="button" class="btn btn-pill btn-secondary-gradient btn-editar-form d-flex justify-content-center align-items-center editar_perfilusuario" id="btnEditar_PerfilUsuario" data-animation="fadeInLeft">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <i class="fa-regular fa-pen-to-square fa-fw fa-lg me-1"></i>
                                                        <span class="">Editar</span>
                                                        <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Fin Barra de Botones -->

                                    </div>

                                </div>
                                <!-- Fin Vista de Datos -->

                            </div>

                        </div>

                    </div><!-- COL-END -->
                </div>
                <!-- Fin Perfil de Usuario -->

            </div>

        </div>
        <!-- Fin Page Content  -->

    </div>

</div>
<!-- Fin App Content -->

<!-- Panel footer -->
<?php require_once("Views/Template/page-footer.php"); ?>
<!--/Panel footer-->

<!-- Sidebar-right -->
<?php require_once("Views/Template/sidebar-rigth.php"); ?>
<!--/Sidebar-right-->

<!-- Footer -->
<?php require_once("Views/Template/footer_admin.php"); ?>

<!-- Custom Script Footer -->
<script src="<?= media(); ?>/plugins/repeater/jquery.repeater.min.js"></script>
<script src="<?= media(); ?>/plugins/repeater/jquery.form-repeater.js"></script>
<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->